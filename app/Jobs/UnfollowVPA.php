<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Models\User;
use App\Services\Pipedrive;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UnfollowVPA implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const VPA = 8979191;
    /**
     * @var Lead|\Illuminate\Database\Eloquent\Builder
     */
    private $leads;
    private $user_id;

    /**
     * Create a new job instance.
     *
     */
    public function __construct()
    {
        $this->user_id = User::where('pipedrive_id', self::VPA)->first()->id;
        $this->leads = Lead::where('created_at', '<' , now()->subDays(21)->toDateTimeString())->where('user_id', $this->user_id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pipedrive = new Pipedrive;
        if($this->leads->count() > 0) {
            foreach($this->leads as $lead){
                $pipedrive->personFollowers($lead->person_id)->followersDelete(['user_id' => self::VPA]);
                $pipedrive->dealFollowers($lead->deal_id)->followersDelete(['user_id' => self::VPA]);
            }
        }

    }
}
