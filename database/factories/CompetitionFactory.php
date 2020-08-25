<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Competition;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Competition::class, function (Faker $faker) {

    $competition = implode(" ", $faker->words(2))." ".$faker->year;
    $end = $faker->dateTimeThisMonth;
    $beginning = $faker->dateTimeThisMonth($end);

    return [
      'name' => $competition,
      'slug' => Str::slug($competition),
      'access_code' => Str::snake($competition),
      'use_runner_up_names' => $faker->boolean,
      'is_archived' => $faker->boolean,
      'is_completed' => $faker->boolean,
      'begin_date' => $beginning,
      'end_date' => $end,
      'organization_id' => factory(App\Organization::class)
    ];
});

$factory->afterCreating(Competition::class, function($competition, $faker) {
  $competition->place()->save(factory(App\Place::class)->create());
});
