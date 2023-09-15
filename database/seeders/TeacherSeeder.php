<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Teacher\Entities\Teacher;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];

        for($i = 0 ; $i < 10 ; $i++){
            $data[] = [
                'name' => fake()->name(),
                'email' => fake()->unique()->email(),
                'password' => bcrypt('password'),
                'about' => fake()->paragraph(),
                'profile' => 'profile',
                'email_verified_at' => now(),
            ];
        }

        Teacher::insert($data);
    }
}
