<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Division;
use Faker\Generator as Faker;

$factory->define(Division::class, function (Faker $faker) {

    return [
      'name' => $faker->word,
      'combo_award_count' => $faker->numberBetween(0, 3),
      'music_award_count' => $faker->numberBetween(0, 3),
      'show_award_count' => $faker->numberBetween(0, 3),
      'overall_award_count'=> $faker->numberBetween(0, 3),
      'rating_system' => [
          ["name" => $faker->word." Rating", "min_score" => $faker->numberBetween(0, 40)]
      ],
      'max_choirs' => $faker->numberBetween(1, 4),
      'round_id' => factory(App\Round::class)

      // These don't seem to be used in the database
      // 'overall_award_sponsors' => $faker,
      // 'music_award_sponsors',
      // 'show_award_sponsors',
      // 'combo_award_sponsors',
      // 'rating_system'
    ];
});
