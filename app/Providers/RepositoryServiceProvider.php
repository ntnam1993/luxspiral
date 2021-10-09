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
        $this->app->bind(\App\Repositories\Contracts\UserRepository::class, \App\Repositories\Eloquents\UserRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\DeliveryRepository::class, \App\Repositories\Eloquents\DeliveryRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\NotificationRepository::class, \App\Repositories\Eloquents\NotificationRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\AnswerRepository::class, \App\Repositories\Eloquents\AnswerRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\CallRepository::class, \App\Repositories\Eloquents\CallRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\NotifyRepository::class, \App\Repositories\Eloquents\NotifyRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\SoundRepository::class, \App\Repositories\Eloquents\SoundRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\QuestionRepository::class, \App\Repositories\Eloquents\QuestionRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\FAQRepository::class, \App\Repositories\Eloquents\FAQRepositoryEloquent::class);
        //:end-bindings:
    }
}
