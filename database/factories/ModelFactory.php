<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(\App\DataModels\User\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt('Qw3rt!'),
        'preferred_daily_hours' => rand(1, 24),
        'remember_token' => str_random(10),
    ];
});
$factory->define(\App\DataModels\TimeSheet\TimeSheet::class, function (Faker\Generator $faker) {
    return [
        'date' => $faker->dateTimeThisYear->format('Y-m-d'),
        'hours' => rand(1, 24),
        'description' => $faker->paragraph(5),
    ];
});
