<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Delivery::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'sound_id' => $faker->numberBetween(1,40),
        'sound_id_no_answer' => $faker->numberBetween(1,40),
        'schedule' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s")
    ];
});
