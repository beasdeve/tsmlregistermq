<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Jobs\RegisterCustomerExcelUpload;
use App\Jobs\SecurityQuestionsCreated;
use App\Jobs\SecurityQuestionsUpdated;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {

          \App::bindMethod(RegisterCustomerExcelUpload::class . '@handle' , function($job) {
            return $job->handle();
          });

           \App::bindMethod(SecurityQuestionsCreated::class . '@handle' , function($job) {
            return $job->handle();
          });

            \App::bindMethod(SecurityQuestionsUpdated::class . '@handle' , function($job) {
            return $job->handle();
          });
    }
}