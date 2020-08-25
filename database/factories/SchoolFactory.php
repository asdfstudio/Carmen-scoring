<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\School;
use Faker\Generator as Faker;

$factory->define(School::class, function (Faker $faker) {
  return [
    'name' => implode(" ", $faker->words(3))
  ];
});

$factory->afterCreating(School::class, function($school, $faker) {
  $school->place()->save(factory(App\Place::class)->create());
});
