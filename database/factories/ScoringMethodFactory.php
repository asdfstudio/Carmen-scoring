<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ScoringMethod;
use Faker\Generator as Faker;

$factory->define(ScoringMethod::class, function (Faker $faker) {
    return [
        'name' => $faker->word
    ];
});
