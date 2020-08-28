<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
      'judge_id' => factory(App\Judge::class),
      'choir_id' => $choir = factory(App\Choir::class),
      'recipient_type' => 'App\Choir', // or App\Performer for a solo division
      'recipient_id' => $choir,
      'subject_type' => 'App\Round',
      'subject_id' => factory(App\Round::class),
      'comments' => implode('  ', $faker->sentences)
    ];
});
