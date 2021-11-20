<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Thread;
use App\User;
use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    return [

        'content' => $faker->realText(),
        'thread_id' => factory(Thread::class)->create()->id,
        'user_id' => factory(User::class)->create()->id,

    ];
});
