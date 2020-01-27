<?php

use App\Models\Credential;
use App\Models\SmsNotificationSetting;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(SmsNotificationSetting::class, function (Faker $faker) {

    return [
        'credential_id' => function () {
            return factory(Credential::class)->create()->id;
        },
        'driver' => 'null',
        'config' => [
            'endpoint' => $faker->randomElement(['https://127.0.0.1/', 'sms.fakedomain.com']),
            'login' => $faker->userName,
            'pwd' => $faker->password
        ]
    ];
});
