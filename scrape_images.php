<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

$products = Product::whereNull('image')->get();
foreach($products as $p) {
    $p->image = 'products/' . Str::random(10) . '.jpg';
    $imgData = @file_get_contents('https://picsum.photos/400/400?random=' . $p->id);
    if($imgData) {
        Storage::disk('public')->put($p->image, $imgData);
        $p->save();
        echo "Updated " . $p->name . "\n";
    }
}
echo "Done.\n";
