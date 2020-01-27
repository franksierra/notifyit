<?php

namespace App\Components\Sms\Facades;

use App\Components\Sms\Testing\ClientFake;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void send(string|array $to, string $message)
 * @method static array failures()
 * @method static void assertSent(string $text, int $times = 1)
 * @method static void assertNothingSent()
 * @method static \Illuminate\Support\Collection sent(string $mailable, \Closure|string $callback = null)
 *
 * @see \App\Components\Sms\Client
 * @see \App\Components\Sms\Testing\ClientFake
 */
class Sms extends Facade
{
    /**
     * Replace the bound instance with a fake.
     *
     * @return ClientFake
     */
    public static function fake()
    {
        static::swap($fake = new ClientFake);

        return $fake;
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'sms.client';
    }
}
