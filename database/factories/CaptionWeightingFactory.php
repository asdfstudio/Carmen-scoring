<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CaptionWeighting;
use Faker\Generator as Faker;

$factory->define(CaptionWeighting::class, function (Faker $faker) {
    $music = $faker->randomNumber(2);
    return [
      'name' => $music . '/' . (100 - $music)
    ];
});
