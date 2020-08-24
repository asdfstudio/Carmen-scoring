<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Caption;
use Faker\Generator as Faker;

$factory->define(Caption::class, function (Faker $faker) {
    return [
      'name' => $faker->word,
      'color_id' => $faker->randomDigitNotNull
    ];
});
