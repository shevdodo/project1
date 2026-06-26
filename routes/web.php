<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\SuperuserDashboardController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MediaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Landing Page
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing');
})->name('landing');

/*
|--------------------------------------------------------------------------
| User Dashboard Route
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:user'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Superuser Dashboard Route
|--------------------------------------------------------------------------
*/
Route::prefix('pranotoweb')
    ->middleware(['auth', 'verified', 'role:superuser'])
    ->name('superuser.')
    ->group(function () {
        Route::get('/', [SuperuserDashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/users', [SuperuserDashboardController::class, 'usersIndex'])->name('users.index');
        Route::get('/users/create', [SuperuserDashboardController::class, 'usersCreate'])->name('users.create');
        Route::post('/users', [SuperuserDashboardController::class, 'usersStore'])->name('users.store');
        Route::get('/users/{user}/edit', [SuperuserDashboardController::class, 'usersEdit'])->name('users.edit');
        Route::put('/users/{user}', [SuperuserDashboardController::class, 'usersUpdate'])->name('users.update');
        Route::delete('/users/{user}', [SuperuserDashboardController::class, 'usersDestroy'])->name('users.destroy');

        Route::resource('pages', PageController::class);
        Route::resource('posts', PostController::class);
        Route::delete('/posts/bulk-delete', [PostController::class, 'bulkDestroy'])->name('posts.bulk-destroy');
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::delete('/products/bulk-delete', [ProductController::class, 'bulkDestroy'])->name('products.bulk-destroy');
        
        Route::resource('menus', MenuController::class)->except(['create', 'edit', 'update', 'show']);
        Route::post('menus/{menu}/items', [MenuController::class, 'addItem'])->name('menus.items.add');
        Route::put('menus/items/{item}', [MenuController::class, 'updateItem'])->name('menus.items.update');
        Route::delete('menus/items/{item}', [MenuController::class, 'removeItem'])->name('menus.items.destroy');
        Route::post('menus/{menu}/structure', [MenuController::class, 'saveStructure'])->name('menus.structure.save');

        Route::get('/settings/general', [SuperuserDashboardController::class, 'settingsGeneral'])->name('settings.general');
        Route::post('/settings/general', [SuperuserDashboardController::class, 'settingsGeneralUpdate'])->name('settings.general.update');

        Route::get('/settings/theme', [SuperuserDashboardController::class, 'settingsTheme'])->name('settings.theme');
        Route::post('/settings/theme', [SuperuserDashboardController::class, 'settingsThemeUpdate'])->name('settings.theme.update');

        Route::get('/settings/api', [SuperuserDashboardController::class, 'settingsApi'])->name('settings.api');
        Route::post('/settings/api', [SuperuserDashboardController::class, 'settingsApiUpdate'])->name('settings.api.update');

        Route::get('/settings/footer', [SuperuserDashboardController::class, 'settingsFooter'])->name('settings.footer');
        Route::post('/settings/footer', [SuperuserDashboardController::class, 'settingsFooterUpdate'])->name('settings.footer.update');

        Route::get('/settings/permalink', [SuperuserDashboardController::class, 'settingsPermalink'])->name('settings.permalink');
        Route::post('/settings/permalink', [SuperuserDashboardController::class, 'settingsPermalinkUpdate'])->name('settings.permalink.update');

        // Media Library Routes
        Route::get('/media', [MediaController::class, 'index'])->name('media.index');
        Route::get('/media/api', [MediaController::class, 'api'])->name('media.api');
        Route::post('/media/upload', [MediaController::class, 'upload'])->name('media.upload');
        Route::delete('/media/delete', [MediaController::class, 'destroy'])->name('media.destroy');

    });

/*
|--------------------------------------------------------------------------
| Authenticated User Profile Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Shipping API Routes
    Route::get('/cart/cities/{provinceId}', [App\Http\Controllers\CartController::class, 'getCities'])->name('cart.cities');
    Route::post('/cart/ongkir', [App\Http\Controllers\CartController::class, 'checkOngkir'])->name('cart.ongkir');

    // Cart Routes
    Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{id}', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add')->where('id', '[0-9]+');
    Route::patch('/cart/{id}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update')->where('id', '[0-9]+');
    Route::delete('/cart/{id}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove')->where('id', '[0-9]+');

    Route::post('/checkout', [App\Http\Controllers\CartController::class, 'checkout'])->name('checkout');

    Route::get('/orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
});

require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Dynamic Pages Route (Must be at the bottom)
|--------------------------------------------------------------------------
*/

try {
    $postBase = \App\Models\Setting::where('key', 'post_permalink_base')->value('value') ?: 'post';
    $productBase = \App\Models\Setting::where('key', 'product_permalink_base')->value('value') ?: 'product';
    
    // Post Routes
    Route::get("/{$postBase}", function (\Illuminate\Http\Request $request) {
        $query = \App\Models\Post::where('status', 'published')->latest();
        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }
        $posts = $query->paginate(9)->appends($request->all());
        $categories = \App\Models\Category::where('type', 'post')->get();
        return view('posts', compact('posts', 'categories'));
    })->name('post.index');

    Route::get("/{$postBase}/{category_slug}", function (\Illuminate\Http\Request $request, $category_slug) {
        $category = \App\Models\Category::where('slug', $category_slug)->firstOrFail();
        $query = \App\Models\Post::where('category_id', $category->id)->where('status', 'published')->latest();
        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }
        $posts = $query->paginate(9)->appends($request->all());
        $categories = \App\Models\Category::where('type', 'post')->get();
        return view('posts', compact('posts', 'category', 'categories'));
    })->name('post.category');

    Route::get("/{$postBase}/{category_slug}/{slug}", function ($category_slug, $slug) {
        $postQuery = \App\Models\Post::where('slug', $slug)->where('status', 'published');
        if ($category_slug === 'uncategorized') {
            $postQuery->whereNull('category_id');
        } else {
            $postQuery->whereHas('category', function($q) use ($category_slug) {
                $q->where('slug', $category_slug);
            });
        }
        $post = $postQuery->firstOrFail();
        return view('post', compact('post'));
    })->name('post.show');

    // Product Routes
    Route::get("/{$productBase}", function (\Illuminate\Http\Request $request) {
        $query = \App\Models\Product::where('status', 'available')->latest();
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }
        $products = $query->paginate(12)->appends($request->all());
        $categories = \App\Models\Category::where('type', 'product')->get();
        return view('products', compact('products', 'categories'));
    })->name('product.index');

    Route::get("/{$productBase}/{category_slug}", function (\Illuminate\Http\Request $request, $category_slug) {
        $category = \App\Models\Category::where('slug', $category_slug)->firstOrFail();
        $query = \App\Models\Product::where('category_id', $category->id)->where('status', 'available')->latest();
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }
        $products = $query->paginate(12)->appends($request->all());
        $categories = \App\Models\Category::where('type', 'product')->get();
        return view('products', compact('products', 'category', 'categories'));
    })->name('product.category');

    Route::get("/{$productBase}/{category_slug}/{slug}", function ($category_slug, $slug) {
        $productQuery = \App\Models\Product::where('slug', $slug)->where('status', 'available');
        if ($category_slug === 'uncategorized') {
            $productQuery->whereNull('category_id');
        } else {
            $productQuery->whereHas('category', function($q) use ($category_slug) {
                $q->where('slug', $category_slug);
            });
        }
        $product = $productQuery->firstOrFail();
        
        $relatedProducts = \App\Models\Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'available')
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('product', compact('product', 'relatedProducts'));
    })->name('product.show');
} catch (\Exception $e) {
    // DB might not be ready yet
}

Route::get('/{slug}', function ($slug) {
    $page = \App\Models\Page::where('slug', $slug)
        ->where('status', 'published')
        ->firstOrFail();
    return view('page', compact('page'));
})->name('page.show');
