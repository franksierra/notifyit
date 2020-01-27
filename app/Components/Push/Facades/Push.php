<?php

namespace App\Components\Push\Facades;

use App\Components\Push\Testing\ClientFake;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void send(string|array $to, string $message)
 * @method static array failures()
 * @method static void assertSent(string|array $to, int $times = 1)
 * @method static void assertNothingSent()
 *
 * @see \App\Components\Push\Client
 * @see \App\Components\Push\Testing\ClientFake
 */
class Push extends Facade
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
        return 'push.client';
    }
}
