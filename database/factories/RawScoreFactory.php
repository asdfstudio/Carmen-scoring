<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\RawScore;
use Faker\Generator as Faker;

$factory->define(RawScore::class, function (Faker $faker) {
    return [
      'score' => $faker->randomDigit,
      'division_id' => factory(App\Division::class),
      'round_id' => factory(App\Round::class),
      'choir_id' => factory(App\Choir::class),
      'judge_id' => factory(App\Judge::class),
      'criterion_id' => factory(App\Criterion::class),
    ];
});
