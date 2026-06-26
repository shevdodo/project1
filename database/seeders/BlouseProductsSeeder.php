<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class BlouseProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure Blouse category exists
        $category = Category::firstOrCreate(
            ['slug' => 'blouse'],
            ['name' => 'Blouse', 'type' => 'product']
        );

        $blouses = [
            'Dyna BLOUSE',
            'Jeny BLOUSE',
            'Syifa BLOUSE',
            'LIRIS KAWUNG BLOUSE',
            'NAVIE BLOUSE DAUN',
            'SANTICA BLOUSE LIRIS CEPLOK',
            'FEBY BLOUSE BERAS SELING',
            'GEBRY BLOUSE KAWUNG',
            'GEDEK BLOUSE ANYAM GEDEK',
            'NAVI BLOUSE KAWUNG',
            'NAVI BLOUSE DAUN SELING'
        ];

        foreach ($blouses as $blouseName) {
            Product::firstOrCreate(
                ['slug' => Str::slug($blouseName)],
                [
                    'name' => $blouseName,
                    'description' => 'Blouse cantik dengan motif ' . $blouseName . '. Dibuat dari bahan berkualitas tinggi dan sangat nyaman dipakai untuk berbagai acara.',
                    'price' => rand(150, 450) * 1000,
                    'status' => 'available',
                    'category_id' => $category->id
                ]
            );
        }
    }
}
