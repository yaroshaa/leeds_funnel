<?php

namespace App\Jobs;

use App\Services\Pipedrive;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NoteUpdatedIntercomContact implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Pipedrive $pipedrive;
    private int $userId;
    private int $dealId;

    /**
     * Create a new job instance.
     *
     * @param int $dealId
     * @param int $userId
     */
    public function __construct(int $dealId, int $userId )
    {
        $this->dealId = $dealId;
        $this->userId = $userId;
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
            'deal_id' => $this->dealId,
            'content' => view('notes.leads.note-after-updated-intercom-contact', [
                'user_id'=> $this->userId
            ])->render()
        ])->post();
    }
}
