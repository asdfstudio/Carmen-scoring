<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Round;
use Faker\Generator as Faker;

$factory->define(Round::class, function (Faker $faker) {

    return [
      'name' => $faker->word,
      'competition_id' => factory(App\Competition::class),
      'caption_weighting_id' => factory(App\CaptionWeighting::class),
      'scoring_method_id' => factory(App\ScoringMethod::class),
      'sheet_id' => factory(App\Sheet::class),
      'sequence' => $faker->numberBetween(0,1)
    ];
});
