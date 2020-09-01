<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Choir;
use Faker\Generator as Faker;

$factory->define(Choir::class, function (Faker $faker) {
    return [
      'name' => $faker->word,
      'school_id' => factory(App\School::class)->create()
    ];
});
