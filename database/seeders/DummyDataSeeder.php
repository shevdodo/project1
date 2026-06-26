<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $categoriesData = [
            'Teknologi',
            'Gaya Hidup',
            'Edukasi',
            'Kesehatan'
        ];

        foreach ($categoriesData as $catName) {
            $category = Category::create([
                'name' => $catName,
                'slug' => Str::slug($catName),
                'type' => 'post',
            ]);

            for ($i = 1; $i <= 5; $i++) {
                $title = $faker->sentence(mt_rand(4, 8));
                Post::create([
                    'title' => $title,
                    'slug' => Str::slug($title),
                    'excerpt' => $faker->paragraph(2),
                    'content' => implode("\n\n", $faker->paragraphs(mt_rand(3, 6))),
                    'status' => 'published',
                    'category_id' => $category->id,
                ]);
            }
        }
    }
}
