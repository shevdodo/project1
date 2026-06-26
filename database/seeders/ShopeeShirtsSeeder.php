<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ShopeeShirtsSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Batik Kemeja Pria Lengan Panjang Premium Batik Mukti Solo',
                'slug' => 'batik-kemeja-pria-lengan-panjang-premium-batik-mukti-solo',
                'price' => 398000,
                'description' => "Kemeja Batik Pria Lengan Panjang/Pendek Batik Premium. Jahitan rapi dan halus dengan standar taylor. Proses Handmade Asli Solo (batik Kombinasi tulis tradisional).",
                'category_id' => 12,
                'stock' => 15,
                'sizes' => 'M, L, XL',
                'weight' => 300,
                'image' => 'media/2026/06/kemeja-batik-premium.png',
                'status' => 'available'
            ],
            [
                'name' => 'Batik Mukti Solo - Kemeja Batik Pria Lengan Panjang CP012',
                'slug' => 'batik-mukti-solo-kemeja-batik-pria-lengan-panjang-cp012',
                'price' => 298500,
                'description' => "Kemeja Batik Pria Lengan Panjang Premium, Batik Handmade Solo, Lapis Furing.",
                'category_id' => 12,
                'stock' => 20,
                'sizes' => 'S, M, L, XL, XXL',
                'weight' => 200,
                'image' => 'media/2026/06/kemeja-batik-cp012.png',
                'status' => 'available'
            ],
            [
                'name' => 'Batik Mukti Solo - Kemeja Batik Cap Indigo Bledak Lengan Pendek Premium',
                'slug' => 'batik-mukti-solo-kemeja-batik-cap-indigo-bledak-lengan-pendek-premium',
                'price' => 252000,
                'description' => "Kemeja Batik Cap Indigo Bledak Lengan Pendek Premium, Batik Handmade Solo.",
                'category_id' => 12,
                'stock' => 12,
                'sizes' => 'S, M, L, XL, XXL',
                'weight' => 150,
                'image' => 'media/2026/06/kemeja-batik-indigo.png',
                'status' => 'available'
            ],
            [
                'name' => 'Batik Mukti Solo - Kemeja Pria Lengan Panjang Kombinasi Tulis Katun Lapis Furing Elegan Mewah',
                'slug' => 'batik-mukti-solo-kemeja-pria-lengan-panjang-kombinasi-tulis-katun-lapis-furing-elegan-mewah',
                'price' => 307300,
                'description' => "Kemeja Pria Lengan Panjang Kombinasi Tulis Katun Lapis Furing Elegan Mewah, Batik Handmade Solo.",
                'category_id' => 12,
                'stock' => 8,
                'sizes' => 'S, M, L, XL, XXL, XXXL',
                'weight' => 200,
                'image' => 'media/2026/06/kemeja-batik-tulis.png',
                'status' => 'available'
            ]
        ];

        foreach ($products as $prod) {
            Product::updateOrCreate(
                ['slug' => $prod['slug']],
                $prod
            );
        }
    }
}
