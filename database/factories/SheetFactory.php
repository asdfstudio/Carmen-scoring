<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Sheet;
use Faker\Generator as Faker;

$factory->define(Sheet::class, function (Faker $faker) {
    return [
      'name' => $faker->word
    ];
});
