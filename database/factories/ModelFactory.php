<?php

use App\User;
use App\Role;
use App\Admin;
use App\Permission;
use App\SocialAccount;

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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'verified' => false,
    ];
});

$factory->define(SocialAccount::class, function (Faker\Generator $faker) {

    return [
        'user_id' => function() { return factory(User::class)->create()->id; },
        'provider' => $faker->randomElement(['google', 'facebook', 'twitter']),
        'provider_id' => $faker->uuid,
    ];
});

$factory->define(Admin::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Role::class, function (Faker\Generator $faker) {

    return [
        'name' => 'executive',
        'label' => 'Executive',
    ];
});

$factory->define(Permission::class, function (Faker\Generator $faker) {

    return [
        'name' => 'view-revenue',
        'label' => 'View revenue',
    ];
});
