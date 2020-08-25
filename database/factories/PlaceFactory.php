<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Place;
use Faker\Generator as Faker;

$factory->define(Place::class, function (Faker $faker) {
    return [
      'address' => $faker->buildingNumber." ".$faker->streetName,
      'address_2' => $faker->secondaryAddress,
      'city' => $faker->city,
      'state' => $faker->stateAbbr,
      'postal_code' => $faker->postcode
    ];
});
