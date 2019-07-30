<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Messages\SlackAttachment;
use Spatie\FailedJobMonitor\Notification;

class JobFailedNotification extends Notification
{

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->error()
            ->subject('A job failed at ' . config('app.url'))
            ->line("Exception message: {$this->event->exception->getMessage()}")
            ->line("Job class: {$this->event->job->resolveName()}")
            ->line("Job body: {$this->event->job->getRawBody()}")
            ->line("Exception: {$this->event->exception->getTraceAsString()}");
    }

    public function toSlack(): SlackMessage
    {
        $job = $this->event->job->payload();
        $job_dec = unserialize($job["data"]["command"]);
        return (new SlackMessage)
            ->from(env('FAILED_JOB_SLACK_USER'))
            ->to(env('FAILED_JOB_SLACK_CHANNEL'))
            ->error()
            ->content('@all Queued job failed: ' . $this->event->job->resolveName())
            ->attachment(function ($attachment) use ($job, $job_dec) {
                $attachment
                    ->title($this->event->exception->getMessage())
                    ->fields([
                        'ID' => $job_dec->uuid,
                        'Name' => $job["data"]["commandName"],
                        'File' => $this->event->exception->getFile(),
                        'Line' => $this->event->exception->getLine(),
                        'Server' => env('APP_ENV'),
                        'Queue' => $this->event->job->getQueue(),
                        'Telescope' => env('APP_URL') . "/telescope/jobs/" . $job["telescope_uuid"],
                    ]);
            });
    }
}
