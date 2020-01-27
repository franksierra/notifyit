<?php

use App\Models\Credential;
use App\Models\Project;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(Credential::class, function (Faker $faker) {
    $prefix = $faker->boolean();

    return [
        'project_id' => function () {
            return factory(Project::class)->create()->id;
        },
        'production' => $faker->boolean(),
        'prefix' => $prefix,
        'prefix_value' => $prefix ? $faker->word : '',
        'api_key' => Str::random(120),
    ];
});
