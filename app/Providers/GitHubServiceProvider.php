<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\GitHubService;
use GrahamCampbell\GitHub\GitHubManager;

class GitHubServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(GitHubService::class, function ($app) {
            return new GitHubService($app->make(GitHubManager::class));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
