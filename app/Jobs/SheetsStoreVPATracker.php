<?php

namespace App\Jobs;

use App\Mail\GSheetUpdateFailure;
use App\Models\Lead;
use App\Models\User;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;
use Revolution\Google\Sheets\Facades\Sheets;

class SheetsStoreVPATracker implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $deal;

    private User $user;

    private ?string $state;

    private int $minutes;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param array $deal
     * @param int $minutes
     * @param string|null $state
     */
    public function __construct(User $user, array $deal, int $minutes, ?string $state = null)
    {
        $this->user = $user;
        $this->deal = $deal;
        $this->state = $state;
        $this->minutes = $minutes;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->createLead();

        if ($this->user->hasRole('vpa')) {
            try {
                $sheet = Sheets::spreadsheet(config('spreadsheets.vpa_tracker.id'))
                    ->sheet(config('spreadsheets.vpa_tracker.list'));

                $sheet->append(array_values([
                    [
                        $this->deal['person_name'],
                        $this->state,
                        now()->toDateString(),
                        $this->minutes,
                        '.',
                        $this->makeHyperlink(
                            "https://eduopinions.pipedrive.com/deal/{$this->deal['id']}",
                            'Deal at Pipedrive'
                        ),
                    ]
                ]), 'USER_ENTERED');
            } catch (Exception $exception) {
                Mail::send(new GSheetUpdateFailure($this->deal));
            }
        }
    }

    /**
     * Google spreadsheet hyperlink transformer
     *
     * @param $url
     * @param $text
     * @return string
     */
    private function makeHyperlink($url, $text): string
    {
        return "=HYPERLINK(\"{$url}\";\"{$text}\")";
    }

    /**
     * Store lead
     */
    private function createLead(): void
    {
        $lead = Lead::create([
            'user_id' => $this->user->id,
            'deal_id' => $this->deal['id'],
            'credits' => $this->minutes,
            'state' => $this->state,
        ]);

        if (Lead::whereDealId($lead->deal_id)
            ->whereNotIn('channel', [Lead::PIPEDRIVE])
            ->exists()) {
            Lead::whereDealId($lead->deal_id)
                ->whereNotIn('channel', [Lead::PIPEDRIVE])
                ->update([
                    'state' => $this->state,
                    'credits' => $this->minutes,
                ]);
        }
    }
}
