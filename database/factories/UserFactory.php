<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Models\User::class, function (Faker $faker) {
    $type = ['android', 'ios'];
    $expired = [
        '2018-10-15',
        '2018-11-15',
        '2019-11-15',
        '2018-11-15',
        '2018-12-15',
        '2018-11-20',
        '2018-11-01',
        '2018-11-09',
        '2020-11-15',
        '2018-11-10'
    ];

    return [
        'device_name' => $faker->name,
        'device_type' => $type[rand(0,1)],
        'device_token'=> md5($faker->name),
        'expired'     => $expired[rand(0,count($expired)-1)],
        'tel'         => $faker->phoneNumber,
        'verify_code' => null,
        'verify_status'=> 1,
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s")
    ];
});
