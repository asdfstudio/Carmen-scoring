<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Judge;
use Faker\Generator as Faker;

$factory->define(Judge::class, function (Faker $faker) {
  return factory(App\Person::class)->raw([
    'person_type' => 'App/Judge'
  ]);
});
