<?php

namespace App\Jobs;

use App\Models\BouncedEmails;
use App\Services\Clearout;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var BouncedEmails[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    private $bouncedEmails;
    /**
     * @var Clearout
     */
    private Clearout $clearout;

    /**
     * Create a new job instance.
     *
     */
    public function __construct()
    {
        $this->clearout = new Clearout ;
        $this->bouncedEmails = BouncedEmails::where('datetime' , now()->subDay()->toDateTimeString())->get();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->bouncedEmails->count()){
            $this->bouncedEmails->each(function($item){
               if($this->clearout->email($item->email) === 'success'){
                   $item->delete();
               } else {
                   $item->datetime = now()->toDateTimeString();
                   $item->update();
               }
            });
        }
    }
}
