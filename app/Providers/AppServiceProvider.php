<?php

namespace App\Providers;

use App\Models\User;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Schema;
use SendGrid;
use View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(SendGrid::class, function () {
            return new SendGrid(config('sendgrid.api_key'));
        });

        if (Schema::hasTable('users')) {
            View::share('users', User::select(['id', 'name'])->get());
        }

        Paginator::useBootstrap();
    }
}
