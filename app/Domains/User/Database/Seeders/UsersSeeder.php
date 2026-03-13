<?php

declare(strict_types=1);

namespace App\Domains\User\Database\Seeders;

use App\Domains\User\Enums\UserStatusEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 50; $i++) {
            $firstName = $faker->firstName();
            $lastName = $faker->lastName();
            $slug = Str::slug($firstName . ' ' . $lastName . '-' . Str::random(5));

            DB::table('users')->insert([
                'status' => UserStatusEnum::Active,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'name' => $firstName . ' ' .$lastName,
                'slug' => $slug,
                'email' => $faker->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
