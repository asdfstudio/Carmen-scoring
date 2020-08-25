<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\DivisionAwardSetting;
use Faker\Generator as Faker;

$factory->define(DivisionAwardSetting::class, function (Faker $faker) {
    return [
      'division_id' => factory(App\Division::class),
      'caption_id' => factory(App\Caption::class),
      'award_count' => $faker->numberBetween(0, 3),
      'award_sponsors' => []
    ];
});

$factory->afterCreating(DivisionAwardSetting::class, function($setting, $faker) {
    $sponsors = [];
    for ($i = 0; $i < $setting->award_count; $i++) {
      $sponsors[] = $faker->company;
    }

    $setting->award_sponsors = implode(PHP_EOL, $sponsors);
    $setting->save();
});
