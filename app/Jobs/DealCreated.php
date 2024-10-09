<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Models\Stage;
use App\Services\Pipedrive;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DealCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Lead $lead;
    private \stdClass $deal;
    private Pipedrive $pipedrive;

    /**
     * Create a new job instance.
     *
     * @param Lead $lead
     * @param \stdClass $deal
     */
    public function __construct(Lead $lead, \stdClass $deal)
    {
        $this->lead = $lead;
        $this->deal = $deal;
        $this->pipedrive = new Pipedrive;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->lead->update([
            'deal_id' => $this->deal->id,
            'state' => Stage::wherePipedriveId($this->deal->stage_id)->first()->name,
        ]);
        $data = json_decode($this->lead->data);

        $this->pipedrive->notes([
            'deal_id' => $this->deal->id,
            'content' => view('notes.leads.create', [
                'data'=> $data,
            ])->render()
        ])->post();

        $this->pipedrive->notes([
            'deal_id' => $this->deal->id,
            'content' => view('notes.leads.note-about-google', [
                'data'=> $data,
            ])->render()
        ])->post();

        \Mail::send(new \App\Mail\DealCreated($this->lead->data, $this->deal));
    }
}
