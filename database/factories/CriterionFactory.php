<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Criterion;
use Faker\Generator as Faker;

$factory->define(Criterion::class, function (Faker $faker) {
    return [
      'name' => $faker->word,
      'description' => $faker->paragraph,
      'max_score' => $faker->randomDigit,
      'caption_id' => factory(App\Caption::class)
    ];
});
