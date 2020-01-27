<?php

namespace App\Components\Push\Testing;


use App\Components\Push\Contracts\Client as ClientContract;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Assert as PHPUnit;

class ClientFake implements ClientContract
{
    /**
     * All of the texts that have been sent.
     *
     * @var array
     */
    protected $sentNotifications = [];

    /**
     * Assert if a message was sent based on a truth-test callback.
     *
     * @param string $to
     * @param int $times
     * @return void
     */
    public function assertSent($to, $times = 1)
    {
        PHPUnit::assertTrue(
            ($count = $this->sent($to)->count()) === $times,
            "The expected notification was sent {$count} times instead of {$times} times."
        );
    }

    /**
     * Determine if a message was not sent based on a truth-test callback.
     *
     * @param string $to
     * @param callable|null $callback
     * @return void
     */
    public function assertNotSent($to)
    {
        PHPUnit::assertTrue(
            $this->sent($to)->count() === 0,
            "The unexpected notification was sent."
        );
    }

    /**
     * Assert that no messages were sent.
     *
     * @return void
     */
    public function assertNothingSent()
    {
        PHPUnit::assertEmpty($this->sentNotifications, 'Messages were sent unexpectedly.');
    }

    /**
     * Get all of the messages matching a truth-test callback.
     *
     * @param array $to
     * @return Collection
     */
    public function sent($to)
    {
        return collect($this->sentNotifications)->filter(function ($id) use ($to) {
            return in_array($id['to'], $to);
        });

    }

    /**
     * Send a new message using a view.
     *
     * @param string|array $recipients
     * @param string $payload
     * @return void
     */
    public function send($recipients, $payload)
    {
        if (!array($recipients)) {
            $recipients[] = $recipients;
        }
        foreach ($recipients as $to) {
            $this->sentNotifications[] = [
                'to' => $to
            ];
        }
    }


    /**
     * Get the array of failed recipients.
     *
     * @return array
     */
    public function failures()
    {
        return [];
    }

}
