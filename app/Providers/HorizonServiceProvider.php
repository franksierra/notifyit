<?php

namespace App\Providers;

use App\Notifications\JobFailedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobFailed;

use Laravel\Horizon\Horizon;
use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        // Horizon::routeSmsNotificationsTo('15556667777');
        // Horizon::routeMailNotificationsTo('example@example.com');
        Horizon::routeSlackNotificationsTo(
            env('HORIZON_SLACK_URL', ''),
            env('HORIZON_SLACK_CHANNEL', '')
        );

        Horizon::night();

        // Send notification to Slack when a job fails
        Queue::failing(function (JobFailed $event) {
            $eventData = [];
            $eventData['connectionName'] = $event->connectionName;
            $eventData['job'] = $event->job->resolveName();
            $eventData['queue'] = $event->job->getQueue();
            $eventData['exception'] = [];
            $eventData['exception']['message'] = $event->exception->getMessage();
            $eventData['exception']['file'] = $event->exception->getFile();
            $eventData['exception']['line'] = $event->exception->getLine();

            // Get some details about the failed job
            $job = unserialize($event->job->payload()['data']['command']);
            if (property_exists($job, 'order')) {
                $eventData['id'] = $job->order->id;
                $eventData['name'] = $job->order->name;
            }

            // Send slack notification of the failed job
            Notification::route(
                'slack',
                env('HORIZON_SLACK_URL')
            )->notify(new JobFailedNotification($eventData));
        });
    }

    /**
     * Register the Horizon gate.
     *
     * This gate determines who can access Horizon in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewHorizon', function ($user) {
            return in_array($user->email, [
                'admin@notifyit.io'
            ]);
        });
    }
}
