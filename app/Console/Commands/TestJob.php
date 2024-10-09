<?php

namespace App\Console\Commands;

use App\Jobs\CheckEmail;
use App\Jobs\UnfollowVPA;
use Illuminate\Console\Command;

class TestJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testjob:go {--no=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test jobs';

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
     * @return int|void
     */
    public function handle()
    {
        if ($this->option('no') == 1 || empty($this->options())) CheckEmail::dispatch();
        if ($this->option('no') == 2 || empty($this->options())) UnfollowVPA::dispatch();
    }
}
