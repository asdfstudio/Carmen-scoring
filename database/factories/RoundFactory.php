<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Round;
use Faker\Generator as Faker;

$factory->define(Round::class, function (Faker $faker) {
    return [
      'division_id' => factory(App\Division::class),
      'name' => 'Only Round',
      'sequence' => 1,
      'max_choirs' => 4
    ];
});
