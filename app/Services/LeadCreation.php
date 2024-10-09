<?php

namespace App\Services;

use App\Http\Resources\DealPipedriveResource;
use App\Http\Resources\GoogleSheetsResource;
use App\Jobs\AddNoteInPipedriveGradeDUpdate;
use App\Jobs\DealCreated;
use App\Jobs\MessageForSales;
use App\Jobs\NotificationForLead;
use App\Jobs\SheetsStoreLeadData;
use App\Models\BouncedEmails;
use App\Models\Field;
use App\Models\Lead;
use App\Models\Option;
use App\Models\User;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

class LeadCreation
{
    const VPA = 8979191;
    const EDU_ADMIN = 7069357;
    const CRIS = 11237992;

    private Pipedrive $pipedrive;
    private Lead $lead;
    private ApiLayer $apiLayer;
    private ?Field $phoneValid;
    private ?Field $emailValid;
    private Intercom $intercom;
    private Clearout $clearout;

    public function __construct(Pipedrive $pipedrive, ApiLayer $apiLayer, Intercom $intercom, Clearout $clearout)
    {
        $this->pipedrive = $pipedrive;
        $this->apiLayer = $apiLayer;
        $this->intercom = $intercom;
        $this->clearout = $clearout;
        $this->phoneValid = Field::firstWhere('key', '06fee410d6356622d48881b1ca1052ee8b904b61');
        $this->emailValid = Field::firstWhere('key', 'c979cccee6fd6580ab22906dc28604cae62c3a7b');
    }

    public function fromFacebook(array $data): void
    {
        $this->lead = Lead::updateOrCreate([
            'lead_user_id' => $data['user_id'],
        ], [
            'channel' => Lead::FACEBOOK,
            'data' => json_encode($data),
        ]);
        $data['owner_id'] = self::VPA;
        $data['qualifier'] = self::VPA;


        $this->handle($data);
    }

    public function fromLeadgen(array $data): void
    {
        $this->lead = Lead::updateOrCreate([
            'lead_user_id' => $data['user_id'],
        ], [
            'channel' => Lead::LEADGEN,
            'data' => json_encode($data),
        ]);
        $data['owner_id'] = ($data['vip'] !== null && $data['vip'] === 'YES') ? self::CRIS : self::VPA;
        $data['qualifier'] =  $data['owner_id'];


        $this->handle($data);
    }


    public function fromIntercom(array $data)
    {
        $this->lead = Lead::updateOrCreate([
            'lead_user_id' => $data['user_id'],
        ], [
            'channel' => Lead::INTERCOM,
            'data' => json_encode($data),
        ]);

        $data['owner_id'] =  self::CRIS;
        $data['qualifier'] =  self::EDU_ADMIN;

        $conversations = $this->intercom->findConversations([
            'field' => 'contact_ids',
            'operator' => '=',
            'value' => $data['user_id'],
        ]);

        if ($conversations->type === 'conversation.list' && $conversations->total_count) {

            $dat = json_decode($this->lead->data, true);
            $dat['conversion_id'] = $conversations->conversations[0]->id;
            $conversation = $this->intercom->conversation($conversations->conversations[0]->id);

            if ($conversation->type === 'conversation' && isset($conversation->first_contact_reply->url)) {
                $dat['conversion_page'] = str_replace(['https://www.eduopinions.com', 'https://eduopinions.com'], '', $conversation->first_contact_reply->url);
            }

            $this->lead->data = json_encode($dat);
            $this->lead->save();
        }

        $this->handle($data);
    }

    private function createPerson(array $data): object
    {
        $checkPhone = $this->apiLayer->validate($data['phone']);
        $checkEmail = $this->clearout->email($data['email']);

        if (isset($checkEmail->data) && $checkEmail->data->status !== 'valid' ) {
            $bouncedEmails = new BouncedEmails();
            $bouncedEmails->email = $data['email'];
            $bouncedEmails->datetime = now()->toDateTimeString();
            $bouncedEmails->save();
        }

        $attributes = [
            'name' => $data['name'] . ' ' . $data['last_name'],
            'email' => $data['email'],
            $this->emailValid->key => $this->emailValid->options
                ->filter(fn(Option $option) => strtoupper($option->label) === ((isset($checkEmail->data) && $checkEmail->data->status === 'valid') ? 'YES' : 'NO'))
                ->first()
                ->pipedrive_id,
            'phone' => [$data['phone']],
            $this->phoneValid->key => $this->phoneValid->options
                ->filter(fn(Option $option) => strtoupper($option->label) === ((isset($checkPhone->valid) &&  $checkPhone->valid) ? 'YES' : 'NO'))
                ->first()
                ->pipedrive_id,
        ];

        $personExists = $this->pipedrive->people('search', [
            'term' => $data['email'],
            'fields' => 'email',
            'exact_match' => true
        ])->get()->data->items;

        if (count($personExists) > 0) {

            $personExist = $personExists[0]->item;
            $phones[] = collect($personExist->phones)->each(function ($item) use ($data) {
                return $item !== [$data['phone']];
            });
            $emails[] = collect($personExist->emails)->each(function ($item) use ($data) {
                return $item !== [$data['email']];
            });

            $attributes['phone'] = array_merge($phones, [$data['phone']]);
            $attributes['email'] = array_merge($emails, [$data['email']]);
            $person = $this->pipedrive->person($personExist->id)->put($attributes);

        } else {
            $person = $this->pipedrive->addPeople()->post($attributes);
        }

        return $person;
    }

    /**
     * @param array $data
     */
    private function handle(array $data): void
    {
        try {

            $person = $this->createPerson($data);
            $attr = (new DealPipedriveResource)->toArray($data);
            $ownerId = $data['owner_id'];

            if($data['grade'] === config('grade.d')) {
                $attr[config('pipedrive.fields.qualifier')] = null;
                $ownerId = self::EDU_ADMIN;
            }

            $leadData = json_decode($this->lead->data, true);
            $this->lead->user_id = User::where('pipedrive_id', $ownerId)->first()->id;

            $funnelStage = ($data['grade'] === config('grade.d'))
                ? config('pipeline.student_leads.discarded')
                : config('pipeline.student_leads.received');

            Log::info("Name Before Updated : ". $data['name']);
            Log::info("Email Before Updated : ". $data['email']);
            Log::info("Phone Before Updated : ". $data['phone']);
            Log::info("Intercom Contact ID Before Updated : ". $leadData['user_id']);

            if ($person->success) {

                $this->lead->person_id = $person->data->id;

                $person = $person->data;
                $deals = $this->pipedrive->personDeals($person->id)->get();

                if ($deals->success && $deals->data !== null) {
                    $existing = collect($deals->data)->filter(fn($deal) => mb_stristr($deal->title, ' lead') !== false);

                    if ($existing && $existing->count() > 0) {
                        foreach ($existing as $deal) {
                            if($data['grade'] !== config('grade.d')){
                                $oldDeal = $this->pipedrive->deal($deal->id, [
                                    'title' => $person->name . ' lead',
                                    'person_id' => $person->id,
                                    'stage_id' => $funnelStage,
                                ])->put($attr);
                                if ($oldDeal->success) {
                                    DealCreated::dispatch($this->lead, $oldDeal->data);

                                    $this->ownerUpdate($deal->id, $person->id, $ownerId);
                                    $this->addFollower($deal->id, $person->id, $ownerId);

                                    MessageForSales::dispatch($oldDeal->data);
                                }
                                $this->contactIntercomUpdate($data, $oldDeal->data, $leadData, $ownerId, $person->id);

                            }else{
                                $dataObj = (object) $data;
                                AddNoteInPipedriveGradeDUpdate::dispatch($dataObj, $deal);
                                MessageForSales::dispatch($deal);
                            }
                        }
                    }
                } else {

                    $deal = $this->pipedrive->deals([
                        'title' => $person->name . ' lead',
                        'person_id' => $person->id,
                        'stage_id' => $funnelStage,
                    ])->post($attr);

                    if ($deal->success) {
                        DealCreated::dispatch($this->lead, $deal->data);

                        if($data['grade'] !== config('grade.d')) {
                            $this->ownerUpdate($deal->data->id, $person->id, $ownerId);
                            $this->addFollower($deal->data->id, $person->id, $ownerId);
                        }

                        $this->contactIntercomUpdate($data, $deal->data, $leadData, $ownerId, $person->id);
                    }
                }
            }

            $this->lead->update();

        } catch (RequestException $e) {
            Log::info('ERROR MESSAGE : ' . $e->getMessage());
        }

        NotificationForLead::dispatch($data);

        $zapier = new Zapier;
        $dataForGoogle = (new GoogleSheetsResource)->toArray($data);
        SheetsStoreLeadData::dispatch($zapier, $dataForGoogle);
    }

    /**
     * @param $dealId
     * @param $personId
     * @param $ownerId
     * @return bool
     */
    private function ownerUpdate($dealId, $personId, $ownerId): bool
    {
        $person = $this->pipedrive->person($personId)->put(['owner_id' => $ownerId]);
        $deal = $this->pipedrive->deal($dealId)->put(['user_id' => $ownerId]);

        return $person->success && $deal->success;
    }

    /**
     * @param $dealId
     * @param $personId
     * @param $ownerId
     * @return void
     */
    private function addFollower($dealId, $personId, $ownerId): void
    {
        if($ownerId == self::VPA ){
            $this->pipedrive->personFollowers($personId)->post(['user_id' => self::VPA]);
            $this->pipedrive->dealFollowers($dealId)->post(['user_id' => self::VPA]);
        }
    }

    /**
     * @param array $data
     * @param \stdClass|null $deal
     * @param array $leadData
     * @param int $ownerId
     * @param int $personId
     * @return void
     */
    private function contactIntercomUpdate(array $data, ?\stdClass $deal, array $leadData, int $ownerId, int $personId): void
    {
        $name = $data['name'] . ' ' . $data['last_name'];
        $attr = [
            "role" => "user",
            "email" => $data['email'],
            "name" => $name,
            "phone" => $data['phone'],
            "custom_attributes" => [
                "Pipedrive URL" => 'https://eduopinions.pipedrive.com/deal/' . $deal->id
            ]
        ];

        Log::info("Name After Updated : ". $name) ;
        Log::info("Email After Updated : ". $data['email']) ;
        Log::info("Phone After Updated : ". $data['phone']) ;
        Log::info("Intercom Contact ID After Updated : ". $leadData['user_id']) ;

        $contact = null;

        if ($this->lead->channel === Lead::INTERCOM) {
            $contact = $this->intercom->updateContact($leadData['user_id'], $attr);
        } else {

            $attr += ['app_id' => config('intercom.app_id')];
            $intercomContact = $this->intercom->findContacts([
                'field' => 'email',
                'operator' => '=',
                'value' => $data['email'],
            ]);

            $contact = ($intercomContact !== null && $intercomContact->data !== null && count($intercomContact->data) > 0)
                ? $this->intercom->updateContact($intercomContact->data[0]->id, $attr)
                : $this->intercom->createContact($attr);

            $this->lead->lead_user_id = $contact->id;
            $this->lead->update();
        }

        if ($contact) {
            $this->pipedrive->notes([
                'deal_id' => $deal->id,
                'pinned_to_deal_flag' => 1,
                'content' => view('notes.leads.note-after-updated-intercom-contact', [
                    'user_id' => $contact->id,
                    'deal_id' => $deal->id,
                ])->render()
            ])->post();
        }
    }

    /** NOT REMOVE !!!!!
     * For Log Dump
     * @param $var
     * @return string
     */
    public static function ks($var): string
    {
        $arrayKeys = 'null';
        $type = gettype($var);
        if ($type === 'array') {
            $arrayKeys = implode(' , ', array_keys($var));
        } elseif ($type === 'object') {
            $attr = get_object_vars($var);
            if (count($attr) > 0) {
                $arrayKeys = implode(' , ', array_keys($attr));
            }
        }
        return 'Type: ' . $type . ' | Keys : ' . $arrayKeys;
    }
}




