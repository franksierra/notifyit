<?php

use App\Models\Credential;
use App\Models\SmsNotificationLog;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(SmsNotificationLog::class, function (Faker $faker) {

    return [
        'credential_id' => function () {
            return factory(Credential::class)->create()->id;
        },
        'job_id' => uniqid("", true),
        'status' => $faker->randomElement(['queued', 'sent', 'failed']),
        'payload' => '{"param1":"value1","param2":"value2","param3":"value3"}',
        'exception' => '{"param1":"value1","param2":"value2","param3":"value3"}'
    ];
});
