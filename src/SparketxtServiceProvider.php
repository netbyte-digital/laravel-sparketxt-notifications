<?php

namespace NotificationChannels\Sparketxt;

use Illuminate\Support\ServiceProvider;

class SparketxtServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Bootstrap code here.

        /**
         * Here's some example code we use for the pusher package.

        $this->app->when(Channel::class)
            ->needs(Pusher::class)
            ->give(function () {
                $pusherConfig = config('broadcasting.connections.pusher');

                return new Pusher(
                    $pusherConfig['key'],
                    $pusherConfig['secret'],
                    $pusherConfig['app_id']
                );
            });
         */

    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind(SparketxtClient::class, function () {
            return new SparketxtClient(config('services.sparketxt.api-key'), config('services.sparketxt.api-secret'));
        });
    }
}
