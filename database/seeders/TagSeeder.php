<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'Laravel',
            'PHP',
            'JavaScript',
            'React',
            'Vue',
            'Node.js',
            'Python',
            'MySQL',
            'Docker',
            'AWS',
            'API',
            'Authentication',
            'CSS',
            'Bootstrap',
            'Git',
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(
                ['slug' => Str::slug($tag)],
                [
                    'name' => $tag,
                    'slug' => Str::slug($tag),
                ]
            );
        }
    }
}