<?php

use App\Models\Credential;
use App\Models\PushNotificationSetting;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(PushNotificationSetting::class, function (Faker $faker) {

    return [
        'credential_id' => function () {
            return factory(Credential::class)->create()->id;
        },
        'driver' => 'null',
        'config' => [
            'endpoint' => $faker->randomElement(['https://127.0.0.1/', 'sms.fakedomain.com']),
            'key' => $faker->password
        ]
    ];
});
