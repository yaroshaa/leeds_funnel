<?php

namespace App\Jobs;

use App\Services\Pipedrive;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddNoteInPipedriveGradeDUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private \stdClass $data;
    private \stdClass $deal;
    /**
     * @var Pipedrive
     */
    private Pipedrive $pipedrive;

    /**
     * Create a new job instance.
     *
     * @param \stdClass $data
     * @param \stdClass $deal
     */
    public function __construct(\stdClass $data, \stdClass $deal)
    {
        $this->data = $data;
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
        $this->pipedrive->notes([
            'deal_id' => $this->deal->id,
            'content' => view('notes.leads.create', [
                'data'=> $this->data,
            ])->render()
        ])->post();

        $this->pipedrive->notes([
            'deal_id' => $this->deal->id,
            'content' => view('notes.leads.note-about-google', [
                'data'=> $this->data,
            ])->render()
        ])->post();
    }
}
