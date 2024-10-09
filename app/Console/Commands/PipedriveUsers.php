<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\Pipedrive;
use Hash;
use Illuminate\Console\Command;
use Illuminate\Http\Client\RequestException;
use Str;

class PipedriveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pipedrive:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync users with Pipedrive';

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
     */
    public function handle(): int
    {
        $users = $this->pipedrive->users()->get();

        if ($users->success) {
            foreach ($users->data as $user) {
                $attributes = [
                    'pipedrive_id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->is_admin ? User::ADMIN : User::VPA,
                    'timezone' => $user->timezone_name
                ];

                if ($stored = User::where('email', $user->email)->first()) {
                    $stored->update($attributes);
                } else {
                    $attributes['email'] = $user->email;
                    $attributes['password'] = Hash::make(Str::slug($user->name, ''));

                    User::create($attributes);
                }
            }

            $this->info('Users was updated.');

            return self::SUCCESS;
        }

        return self::FAILURE;
    }
}
