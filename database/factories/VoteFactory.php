<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Audience::class, function (Faker $faker) {
    return [
        'alias_name' => $faker->word,
        'is_dark' => $faker->numberBetween(0, 1),
        'social' => ["facebook" => "", "twitter" => "", "instagram" => ""],
        'limit_result' => $faker->numberBetween(1, 6),
        'is_premium_vote' => $faker->numberBetween(0, 1),
        'disable_vote' => $faker->numberBetween(0, 1),
        'is_required_login' => $faker->numberBetween(0, 1),
        'banner_type' => 'hide',
    ];
});
