<?php

use App\Http\Controllers\AboutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PesananTrackingController;
use App\Http\Controllers\ProfileController;
// Route Produk
Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');

// Route About
Route::get('/about', [AboutController::class, 'index'])->name('about.index');

// Route HomePage
Route::get('/', [HomeController::class, 'homepage']);

// Route Login dan Register
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Route yang perlu Auth
Route::middleware(['auth'])->group(function () {
    //Logout routes
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    //Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::put('/cart/update-quantity/{cartItem}', [CartController::class, 'updateQuantity'])->name('cart.update-quantity');
    Route::delete('/cart/remove-item/{cartItem}', [CartController::class, 'removeItem'])->name('cart.remove-item');

    // Checkout routes
    Route::get('/checkout', [CartController::class, 'showCheckout'])->name('checkout.index');
    Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('checkout.process');

    // Route Voucher Post
    Route::post('/cart/apply-voucher', [CartController::class, 'applyVoucher'])->name('cart.apply-voucher');

    // Route Pesanan Sukses
    Route::get('/pesanan/success/{pesanan}', [CartController::class, 'success'])->name('pesanan.success');

    // Route Pesanan Tracking dan Detail Pesanan
    Route::get('/pesanan-saya', [PesananTrackingController::class, 'index'])->name('pesanan.tracking');
    Route::get('/pesanan/{pesanan}', [PesananTrackingController::class, 'show'])->name('pesanan.detail');

    // Route Cancel User
    Route::post('/pesanan/{pesanan}/cancel', [PesananTrackingController::class, 'cancelPesanan'])->name('pesanan.cancel');

    // Route Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update'); 
});

Route::middleware(['auth', 'admin.access'])->prefix('admin')->group(function () {
    // routes admin
});