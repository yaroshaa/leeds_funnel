<?php

namespace App\Jobs;

use App\Http\Resources\GoogleSheetsResource;
use App\Services\Zapier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SheetsStoreLeadData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $data;
    /**
     * @var Zapier
     */
    private Zapier $zapier;

    /**
     * Create a new job instance.
     *
     * @param Zapier $zapier
     * @param array $data
     */
    public function __construct(Zapier $zapier, array $data)
    {
        $this->zapier = $zapier;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       $this->zapier->triggerZapier($this->data);
    }
}


