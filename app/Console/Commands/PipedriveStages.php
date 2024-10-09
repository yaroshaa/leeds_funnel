<?php

namespace App\Console\Commands;

use App\Models\Stage;
use App\Services\Pipedrive;
use Illuminate\Console\Command;

class PipedriveStages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pipedrive:stages {--P|pipeline=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync pipedrive stages';

    /**
     * @var Pipedrive
     */
    private Pipedrive $pipedrive;

    /**
     * Create a new command instance.
     *
     * @param Pipedrive $pipedrive
     */
    public function __construct(Pipedrive $pipedrive)
    {
        parent::__construct();

        $this->pipedrive = $pipedrive;
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function handle(): int
    {
        $stages = $this->pipedrive->stages()->get();

        if ($stages->success) {
            Stage::upsert(
                collect($stages->data)->map(fn($stage) => [
                    'pipedrive_id' => $stage->id,
                    'name' => $stage->name,
                    'pipeline' => $stage->pipeline_name,
                    'order_nr' => $stage->order_nr,
                ])->toArray(),
                ['pipedrive_id'],
                ['name', 'pipeline', 'order_nr']
            );

            $this->info('Stages updated successfully.');

            return self::SUCCESS;
        }

        return self::FAILURE;
    }
}
