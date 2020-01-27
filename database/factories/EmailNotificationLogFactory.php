<?php

use App\Models\Credential;
use App\Models\EmailNotificationLog;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(EmailNotificationLog::class, function (Faker $faker) {

    $status = $faker->randomElement(['queued', 'sent', 'failed']);
    $exception = $status=='failed'?'{"exceptioninfo":"value1"}':'';
    return [
        'credential_id' => function () {
            return factory(Credential::class)->create()->id;
        },
        'job_id' => uniqid("", true),
        'status' => $status,
        'payload' => '{"param1":"value1","param2":"value2","param3":"value3"}',
        'exception' => $exception
    ];
});
