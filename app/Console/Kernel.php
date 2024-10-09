<?php

namespace App\Console;

use App\Console\Commands\PipedriveFields;
use App\Console\Commands\PipedriveStages;
use App\Console\Commands\PipedriveAll;
use App\Console\Commands\PipedriveUsers;
use App\Console\Commands\TestJob;
use App\Jobs\CheckEmail;
use App\Jobs\UnfollowVPA;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        PipedriveStages::class,
        PipedriveFields::class,
        PipedriveUsers::class,
        PipedriveAll::class,
        /**
         * Command for  testing jobs
         */
        TestJob::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(PipedriveAll::class)->twiceDaily();
//        $schedule->job(new CheckEmail() )->dailyAt('7:00');
//        $schedule->job(new UnfollowVPA() )->dailyAt('8:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
