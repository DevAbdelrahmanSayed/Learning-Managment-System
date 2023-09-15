<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Category\Entities\Category;
use Modules\Course\Entities\Course;
use Modules\Teacher\Entities\Teacher;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coursesData = [];
        $teacherIds = Teacher::pluck('id');
        $categoriesIds = Category::pluck('id');

        for ($i = 0; $i < 5; $i++) {
            $coursesData[] = [
                'teacher_id' => $teacherIds->random(),
                'category_id' => $categoriesIds->random(),
                'title' => fake()->paragraph(1),
                'description' => fake()->paragraph(),
                'photo' => 'photo',
                'price' => rand(1000 , 9999),
                'slug' => 'slug',
            ];
        }

        Course::insert($coursesData);
    }
}
