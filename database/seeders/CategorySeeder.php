<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Category\Entities\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'category one',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'category two',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'category three',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ];

        Category::insert($data);
    }
}
