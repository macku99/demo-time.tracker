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

use App\DataModels\User\User;

$factory->define(User::class, function (Faker\Generator $faker) {
    return [
        'role'                  => $faker->randomElement(['regular', 'admin']),
        'name'                  => $faker->name,
        'email'                 => $faker->email,
        'password'              => bcrypt('Qw3rt!'),
        'preferred_daily_hours' => rand(1, 24),
        'remember_token'        => str_random(10),
    ];
});

$factory->defineAs(User::class, 'admin', function () use ($factory) {
    $user = $factory->raw(User::class);

    return array_merge($user, ['role' => 'admin']);
});

$factory->defineAs(User::class, 'regular', function () use ($factory) {
    $user = $factory->raw(User::class);

    return array_merge($user, ['role' => 'regular']);
});

$factory->define(\App\DataModels\TimeSheet\TimeSheet::class, function (Faker\Generator $faker) {
    return [
        'date'        => $faker->dateTimeThisYear->format('Y-m-d'),
        'hours'       => rand(1, 10),
        'description' => $faker->paragraph(5),
    ];
});