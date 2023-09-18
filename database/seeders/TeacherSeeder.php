<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Teacher\Entities\Teacher;
use Faker\Factory as FakerFactory;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = FakerFactory::create();

        $data = [];

        for ($i = 0; $i < 10; $i++) {
            $about = $faker->paragraph();
            // Truncate the 'about' data to fit within the column length (e.g., 255 characters)
            $truncatedAbout = substr($about, 0, 255);

            $data[] = [
                'name' => $faker->name(),
                'email' => $faker->unique()->email(),
                'password' => bcrypt('password'),
                'about' => $truncatedAbout,
                'profile' => 'profile',
                'email_verified_at' => now(),
            ];
        }

        Teacher::insert($data);
    }
}
