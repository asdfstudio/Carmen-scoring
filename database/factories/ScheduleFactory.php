<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

use App\Schedule;
use App\Competition;

$factory->define(Schedule::class, function (Faker $faker) {
    return [
        'name' => $faker->dayOfWeek(),
        'competition_id' => factory(Competition::class)
    ];
});
