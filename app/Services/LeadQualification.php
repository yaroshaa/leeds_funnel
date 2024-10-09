<?php

namespace App\Services;

use App\Exceptions\UserNotFound;
use App\Interfaces\DealQualification as DealQualificationContract;
use App\Jobs\SheetsStoreVPATracker;
use App\Mail\AdminUserNotFound;
use App\Mail\EmptyPersonEmails;
use App\Mail\LeadRequiresAttention;
use App\Mail\PipedriveNoteNotCreated;
use App\Mail\PipedrivePersonNotUpdated;
use App\Mail\PipedriveStageNotUpdated;
use App\Mail\SendGridFailure;
use App\Mail\ZapierHookError;
use App\Models\Field;
use App\Models\Location;
use App\Models\Option;
use App\Models\Stage;
use App\Models\User;
use App\Services\Pipedrive;
use App\Repositories\FieldsRepository;
use Exception;
use Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Mail;
use SendGrid;
use SendGrid\Mail\Mail as SendGridMail;
use SendGrid\Mail\TypeException;
use Str;

class LeadQualification implements DealQualificationContract
{
    public Pipedrive $pipedrive;

    private FieldsRepository $fieldsRepository;

    public int $minutes = 3;

    public ?Option $state;

    public ?User $user;

    private ?array $deal;

    /**
     * LeadQualification constructor.
     *
     * @param FieldsRepository $fieldsRepository
     * @param Pipedrive $pipedrive
     */
    public function __construct(FieldsRepository $fieldsRepository, Pipedrive $pipedrive)
    {
        $this->fieldsRepository = $fieldsRepository;
        $this->pipedrive = $pipedrive;
    }

    /**
     * @param array|null $deal
     */
    public function setDeal(?array $deal): void
    {
        $this->deal = $deal;
    }

    /**
     * @param int|string $user_id
     */
    public function setUser($user_id): void
    {
        $this->user = User::firstWhere('pipedrive_id', $user_id);
    }

    /**
     * Init service
     */
    public function init(): void
    {
        $qualification = $this->fieldsRepository->findOne(Field::QUALIFICATION);

        $this->state = $qualification->options->firstWhere('pipedrive_id', $this->deal[$qualification->key]);

        $methodName = $this->state ? Str::camel($this->state->label) : null;

        if ($methodName !== null && method_exists($this, $methodName)) {
            $this->{$methodName}();
        }
    }

    /**
     * Handle "Qualified" state
     */
    public function qualified(): void
    {
        $this->minutes = 30;

        try {
            $this->prepareLocations();
            $this->setAdminAsOwner();
            $this->updatePipedriveStage('Processed');

            SheetsStoreVPATracker::dispatch($this->user, $this->deal, $this->minutes, $this->state->label);
        } catch (UserNotFound $exception) {
            Mail::send(new AdminUserNotFound());
        }
    }

    /**
     * Handle "Missing info" state
     */
    public function missingInfo(): void
    {
        $this->minutes = 30;
        $this->leadRequiresAttention('missing some important information');

        try {
            $this->prepareLocations();
            $this->setAdminAsOwner();
            $this->updatePipedriveStage('Processed');

            SheetsStoreVPATracker::dispatch($this->user, $this->deal, $this->minutes, $this->state->label);
        } catch (UserNotFound $exception) {
            Mail::send(new AdminUserNotFound());
        }
    }

    /**
     * Handle "Unqualified" state
     */
    public function unqualified(): void
    {
        try {
            $this->prepareLocations();
            $this->setAdminAsOwner();
            $this->updatePipedriveStage('Discarded');

            SheetsStoreVPATracker::dispatch($this->user, $this->deal, $this->minutes, $this->state->label);
        } catch (UserNotFound $exception) {
            Mail::send(new AdminUserNotFound());
        }
    }

    /**
     * Handle "Another channel" state
     */
    public function anotherChannel(): void
    {
        $this->leadRequiresAttention('another channel');
        $this->personSurvey('non_vip', 'vip');

        try {
            $this->setAdminAsOwner();
            $this->updatePipedriveStage('Processed');
        } catch (UserNotFound $exception) {
            Mail::send(new AdminUserNotFound());
        }
    }

    /**
     * Handle "Never replied" state
     */
    public function neverReplied(): void
    {
        $this->personSurvey('non_vip', 'vip');
        $this->updatePipedriveStage('Discarded');
    }

    /**
     * Handle "No WhatsApp" state
     */
    public function noWhatsApp(): void
    {
        $this->personSurvey('non_vip', 'vip');
        $this->updatePipedriveStage('Discarded');
    }

    /**
     * Handle "Lost contact" state
     */
    public function lostContact(): void
    {
        $this->personSurvey('lost_contact');
        $this->updatePipedriveStage('Discarded');
    }

    /**
     * @return Collection|null
     */
    private function prepareLocations(): ?Collection
    {
        $locations = null;
        $nationality = $this->fieldsRepository->findOne(Field::NATIONALITY);

        if ($this->deal[$nationality->key] !== null) {
            $nationality = $nationality->options
                ->whereIn('pipedrive_id', explode(',', $this->deal[$nationality->key]))
                ->pluck('label')->toArray();

            $locations = Location::whereIn('name', $nationality)->get();
        }

        return $locations;
    }

    /**
     * @throws UserNotFound
     */
    private function setAdminAsOwner(): void
    {
        $adminUser = User::whereEmail('admin@eduopinions.com')->first();

        if ($adminUser === null) throw new UserNotFound('Admin user not found.');

        try {
            $this->pipedrive->person($this->deal['person_id'])->put([
                'owner_id' => $adminUser->pipedrive_id,
            ]);

            $this->pipedrive->deal($this->deal['id'])->put([
                'user_id' => $adminUser->pipedrive_id,
            ]);
        } catch (Exception $exception) {
            Mail::send(new PipedrivePersonNotUpdated($this->deal));
        }
    }

    /**
     * Update Pipedrive stage and insert new row in GSheet if user is VPA
     *
     * @param string $name
     */
    private function updatePipedriveStage(string $name): void
    {
        try {
            $stage = Stage::studentLeads()->whereName($name)->first();

            if ($stage === null) throw new Exception();

            $this->pipedrive->deal($this->deal['id'])->put([
                'stage_id' => $stage->pipedrive_id,
            ]);
        } catch (Exception $exception) {
            Mail::send(new PipedriveStageNotUpdated($this->deal));
        }
    }

    /**
     * @param string $channel
     */
    private function leadRequiresAttention(string $channel): void
    {
        try {
            $this->pipedrive->notes()->post([
                'content' => "<p>WARNING: This lead requires personal attention ({$channel}) " . now()->format('d/m/Y') . "</p>",
                'user_id' => User::whereEmail('jordi@eduopinions.com')->first()->pipedrive_id,
                'deal_id' => $this->deal['id'],
            ]);

            Mail::send(new LeadRequiresAttention($this->deal));
        } catch (Exception $exception) {
            Mail::send(new PipedriveNoteNotCreated($this->deal));
        }
    }

    /**
     * Handle person survey
     *
     * @param string $default
     * @param string|null $alternative
     */
    private function personSurvey(string $default, ?string $alternative = null): void
    {
        $emails = [];
        $category = config("sendgrid.categories.{$default}");
        $template = config("sendgrid.templates.{$default}");

        $locations = $this->prepareLocations();

        if ($locations && $alternative !== null) {
            $continents = $locations->pluck('continent')->unique();

            if (!!$continents->intersect(['Europe', 'North America'])->count()) {
                $category = config("sendgrid.categories.{$alternative}");
                $template = config("sendgrid.templates.{$alternative}");
            }
        }

        try {
            $person = $this->pipedrive->person($this->deal['person_id'])->get()->data;
            $emails = array_filter(array_column($person->email, 'value'));
        } catch (RequestException $exception) {
            // TODO notify if Pipedrive error
        }

        if (count($emails)) {
            try {
                $email = $this->prepareSendGridEmail();
                $email->addSubstitutions([
                    'name' => Str::title($this->deal['person_name']),
                ]);
                $email->addCategory($category);
                $email->setTemplateId($template);
                $email->addCustomArgs([
                    'Deal ID' => (string)$this->deal['id'],
                ]);

                foreach ($emails as $personEmail) {
                    $email->addTo($personEmail);
                }

                app(SendGrid::class)->send($email);
            } catch (TypeException $e) {
                Mail::send(new SendGridFailure($this->deal));
            }

            $this->callZapierHook();

            SheetsStoreVPATracker::dispatch($this->user, $this->deal, $this->minutes, $this->state->label);
        } else {
            Mail::send(new EmptyPersonEmails($this->deal));
        }
    }

    /**
     * @return SendGridMail
     * @throws TypeException
     */
    private function prepareSendGridEmail(): SendGridMail
    {
        $email = new SendGridMail;
        $email->addBcc(config('sendgrid.from.email'));
        $email->setFrom(config('sendgrid.from.email'), config('sendgrid.from.name'));
        $email->setReplyTo(config('sendgrid.from.email'), config('sendgrid.from.name'));

        return $email;
    }

    /**
     * Call Zapier hook for outer logic (temporary)
     */
    private function callZapierHook(): void
    {
        try {
            Http::post('https://hooks.zapier.com/hooks/catch/2027976/ocmasw8/silent/', [])->throw();
        } catch (Exception $exception) {
            Mail::send(new ZapierHookError($this->deal));
        }
    }
}
