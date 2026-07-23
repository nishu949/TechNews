<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Laravel',
            'PHP',
            'JavaScript',
            'React',
            'Python',
            'DevOps',
            'AI',
            'Cloud Computing'
        ];

        foreach ($categories as $category) {
    Category::firstOrCreate(
        ['slug' => Str::slug($category)],
        [
            'name' => $category,
            'slug' => Str::slug($category),
        ]
    );
}
    }
}