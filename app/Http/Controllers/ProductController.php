<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Menampilkan halaman daftar produk.
     * Mengambil semua data produk dari database dan dikirim ke Frontend (Vue).
     */
    public function index(Request $request)
    {
        // Fitur Search & Pagination (10 barang per halaman)
        $products = Product::query()
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(10) 
            ->withQueryString(); 

        return Inertia::render('Products/Index', [
            'products' => $products,
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Menyimpan produk baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0', // Stok awal tidak boleh minus
            'price' => 'required|numeric|min:0',
        ]);

        // 2. Simpan ke Database
        Product::create([
            'name' => $request->name,
            'stock' => $request->stock,
            'price' => $request->price,
        ]);

        // 3. Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Logika Transaksi Penjualan (Mengurangi Stok).
     * Sesuai syarat: Stok tidak boleh menjadi negatif.
     */
    public function sell($id)
    {
        return DB::transaction(function () use ($id) {
            
            // Lock baris data ini agar tidak bentrok jika ada 2 user klik bersamaan (Pessimistic Locking)
            $product = Product::lockForUpdate()->find($id);

            if (!$product) {
                return redirect()->back()->with('error', 'Produk tidak ditemukan.');
            }

            // LOGIKA UTAMA: Cek ketersediaan stok
            if ($product->stock > 0) {
                // Kurangi stok
                $product->decrement('stock');
                
                return redirect()->back()->with('success', 'Berhasil terjual! Stok berkurang.');
            } else {
                // Jika stok 0 atau kurang, tolak transaksi
                return redirect()->back()->with('error', 'Gagal! Stok habis.');
            }
        });
    }
}