<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Models\Stage;
use App\Services\Pipedrive;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DealUpdatedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private \stdClass $deal;
    private Pipedrive $pipedrive;
    private array $data;

    /**
     * Create a new job instance.
     *
     * @param array $data
     * @param \stdClass $deal
     */
    public function __construct( array $data, \stdClass $deal)
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
            'content' => view('notes.leads.note-after-change-of-owner', [
                'data'=> $this->data,
                'deal'=> $this->deal
            ])->render()
        ])->post();
    }
}
