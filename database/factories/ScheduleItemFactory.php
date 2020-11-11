<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

use App\Choir;
use app\Division;
use App\Schedule;
use App\ScheduleItem;

$factory->define(ScheduleItem::class, function (Faker $faker) {
    return [
        'schedule_id' => factory(Schedule::class),
        'division_id' => factory(Division::class),
        'choir_id' => factory(Choir::class),
        'scheduled_time' => $faker->time('Y-m-d G:i'),
        'performance_order' => 0
    ];
});
