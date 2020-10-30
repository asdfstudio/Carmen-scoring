<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(App\Audience::class, function (Faker $faker) {
    return [
        'alias_name' => $faker->word,
        'is_dark' => $faker->numberBetween(0, 1),
        'limit_result' => $faker->numberBetween(1, 6),
        'is_premium_vote' => $faker->numberBetween(0, 1),
        'disable_vote' => $faker->numberBetween(0, 1),
        'banner_type' => 'hide',
    ];
});
