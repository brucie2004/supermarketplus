<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use app\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminUserController;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/product/{product:slug}', [ProductController::class, 'show'])->name('product.show');

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/update/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('cart.clear');
});





// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/checkout', function () {
    $cartItems = session()->get('cart', []);
    $total = 0;
    
    // Calculate total
    foreach ($cartItems as $productId => $item) {
        $product = \App\Models\Product::find($productId);
        if ($product) {
            $total += $product->price * $item['quantity'];
        }
    }
    
    return view('checkout.index', compact('total'));
})->name('checkout')->middleware('auth');






Route::prefix('checkout')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('checkout')->middleware('auth');
    Route::post('/process', [CheckoutController::class, 'process'])->name('checkout.process')->middleware('auth');
    Route::get('/confirmation/{order}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation')->middleware('auth');
});




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/myorders', [ProfileController::class, 'orders'])->name('profile.orders');
    Route::get('/myorders/{order}', [ProfileController::class, 'orderDetails'])->name('profile.order.details');
});




Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    
      
    Route::get('/categories', [AdminController::class, 'categories'])->name('admin.categories');
    
    
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
    
    
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');



    Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');




});




Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/categories', [AdminCategoryController::class, 'index'])->name('admin.categories.index');
Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('admin.categories.create');
Route::post('/categories', [AdminCategoryController::class, 'store'])->name('admin.categories.store');
Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('admin.categories.edit');
Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('admin.categories.update');
Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('admin.categories.destroy');



Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.update-status');
Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy'])->name('admin.orders.destroy');



Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // ... your existing admin routes ...
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::put('/users/{user}/toggle-admin', [AdminUserController::class, 'toggleAdmin'])->name('admin.users.toggle-admin');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
});
