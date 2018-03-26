<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Repositories\FacebookProfileRepository::class, \App\Repositories\FacebookProfileRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\FacebookPostRepository::class, \App\Repositories\FacebookPostRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\YoutubeChannelRepository::class, \App\Repositories\YoutubeChannelRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\YoutubeVideoRepository::class, \App\Repositories\YoutubeVideoRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\InstagramProfileRepository::class, \App\Repositories\InstagramProfileRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\InstagramMediaRepository::class, \App\Repositories\InstagramMediaRepositoryEloquent::class);
        //:end-bindings:
    }
}
