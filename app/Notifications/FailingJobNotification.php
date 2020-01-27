<?php

namespace App\Notifications;

use App\Jobs\SendEmailJob;
use App\Jobs\SendPushJob;
use App\Jobs\SendSmsJob;
use App\Models\EmailNotificationLog;
use App\Models\PushNotificationLog;
use App\Models\SmsNotificationLog;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Config;

class FailingJobNotification extends Notification
{
    /** @var JobFailed */
    protected $event;

    /**
     * Create a new notification instance.
     *
     * @param JobFailed $event
     */
    public function __construct(JobFailed $event)
    {
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return SlackMessage
     */
    public function toSlack(): SlackMessage
    {
        $job = $this->event->job->payload();
        $job_dec = unserialize($job["data"]["command"]);

        $jobLog = null;
        switch (get_class($job_dec)) {
            case SendEmailJob::class:
                $jobLog = EmailNotificationLog::whereJobId($job_dec->jobData["uuid"])->first();
                break;
            case SendPushJob::class:
                $jobLog = PushNotificationLog::whereJobId($job_dec->jobData["uuid"])->first();
                break;
            case SendSmsJob::class:
                $jobLog = SmsNotificationLog::whereJobId($job_dec->jobData["uuid"])->first();
                break;
        }

        return (new SlackMessage)
            ->from(Config::get('logging.channels.slack.username'))
            ->to(Config::get('logging.channels.slack.channel'))
            ->error()
            ->content('Queued job failed: ' . $this->event->job->resolveName())
            ->attachment(function (SlackAttachment $attachment) use ($job, $job_dec, $jobLog) {
                $attachment
                    ->title($this->event->exception->getMessage())
                    ->fields([
                        'ID' => $job_dec->jobData["uuid"],
                        'Name' => $job["data"]["commandName"],
                        'File' => $this->event->exception->getFile(),
                        'Line' => $this->event->exception->getLine(),
                        'Server' => Config::get('app.env'),
                        'Queue' => $this->event->job->getQueue(),
                        'Telescope' => Config::get('app.url') . "/telescope/jobs/" . ($job["telescope_uuid"] ?? ""),
                        'AddInfo' => $jobLog->additional ?? [],
                    ]);
            });
    }
}
