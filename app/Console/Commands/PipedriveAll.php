<?php

namespace App\Console\Commands;

use Artisan;
use Illuminate\Console\Command;

class PipedriveAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pipedrive:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Artisan::call(PipedriveFields::class);
        Artisan::call(PipedriveStages::class);
        Artisan::call(PipedriveUsers::class);

        return self::SUCCESS;
    }
}
