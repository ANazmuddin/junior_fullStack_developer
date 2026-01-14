<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Semua route di dalam grup ini WAJIB Login
Route::middleware('auth')->group(function () {
    
    // --- Profile Routes (Bawaan Breeze) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- PRODUCT ROUTES (Inventory System) ---
    // Menampilkan halaman list produk
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    // Menyimpan produk baru
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    // Transaksi penjualan (mengurangi stok)
    Route::post('/products/{id}/sell', [ProductController::class, 'sell'])->name('products.sell');

    // --- USER MANAGEMENT ROUTES ---
    // Menampilkan daftar user
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    // Mengubah role user (PUT sesuai soal)
    Route::put('/users/{id}/change-role', [UserController::class, 'updateRole'])->name('users.updateRole');

});

require __DIR__.'/auth.php';