<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\FormFile;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

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

$factory->define(FormFile::class, function (Faker $faker) {
    $fileName = $faker->file('/tmp', storage_path('app/' . config('uploads.folder')), false);
    return [
        'name' => $fileName,
        'title' => $faker->sentence(2),
        'original_name' => $faker->word . '.' . $faker->fileExtension
    ];
});
