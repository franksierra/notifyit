<?php

use App\Models\Credential;
use App\Models\EmailNotificationSetting;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(EmailNotificationSetting::class, function (Faker $faker) {

    return [
        'credential_id' => function () {
            return factory(Credential::class)->create()->id;
        },
        'driver' => $faker->randomElement(['sendmail', 'log', 'array']),
        'config' => [
            'host' => $faker->randomElement(['127.0.0.1', 'mail.fakedomain.com']),
            'port' => $faker->randomElement(['25', '586']),
            'from' => [
                'address' => $faker->companyEmail,
                'name' => $faker->company
            ],
            'encryption' => $faker->randomElement(['tls', 'none']),
            'username' => $faker->userName,
            'password' => $faker->password,
            'pretend' => false,
        ]
    ];
});
