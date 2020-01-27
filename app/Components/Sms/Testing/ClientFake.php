<?php

namespace App\Components\Sms\Testing;


use App\Components\Sms\Contracts\Client as ClientContract;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Assert as PHPUnit;

class ClientFake implements ClientContract
{
    /**
     * All of the texts that have been sent.
     *
     * @var array
     */
    protected $sentMessages = [];

    /**
     * Assert if a message was sent based on a truth-test callback.
     *
     * @param string $text
     * @param int $times
     * @return void
     */
    public function assertSent($text, $times = 1)
    {
        PHPUnit::assertTrue(
            ($count = $this->sent($text)->count()) === $times,
            "The expected text [{$text}] was sent {$count} times instead of {$times} times."
        );
    }

    /**
     * Determine if a message was not sent based on a truth-test callback.
     *
     * @param string $text
     * @param callable|null $callback
     * @return void
     */
    public function assertNotSent($text, $callback = null)
    {
        PHPUnit::assertTrue(
            $this->sent($text, $callback)->count() === 0,
            "The unexpected text [{$text}] was sent."
        );
    }

    /**
     * Assert that no messages were sent.
     *
     * @return void
     */
    public function assertNothingSent()
    {
        PHPUnit::assertEmpty($this->sentMessages, 'Messages were sent unexpectedly.');
    }

    /**
     * Get all of the messages matching a truth-test callback.
     *
     * @param string $text
     * @param callable|null $callback
     * @return Collection
     */
    public function sent($text, $callback = null)
    {
        if (!$this->hasSent($text)) {
            return collect();
        }

        $callback = $callback ?: function () {
            return true;
        };

        return $this->messagesOf($text)->filter(function ($sms) use ($callback) {
            return $callback($sms);
        });
    }

    /**
     * Determine if the given message has been sent.
     *
     * @param string $text
     * @return bool
     */
    public function hasSent($text)
    {
        return $this->messagesOf($text)->count() > 0;
    }

    /**
     * Get all of the messages sent for a given text.
     *
     * @param string $text
     * @return Collection
     */
    protected function messagesOf($text)
    {
        return collect($this->sentMessages)->filter(function ($sms) use ($text) {
            return $sms['message'] == $text;
        });
    }

    /**
     * Send a new message using a view.
     *
     * @param string|array $recipients
     * @param string $message
     * @return void
     */
    public function send($recipients, $message)
    {
        if (!array($recipients)) {
            $recipients[] = $recipients;
        }
        foreach ($recipients as $to) {
            $this->sentMessages[] = [
                'to' => $to,
                'message' => $message,
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
