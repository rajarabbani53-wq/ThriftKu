<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeder bawaan untuk User
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // --- KODE SEEDER PRODUK YANG SUDAH DIPERBAIKI SINTAKS-NYA ---
        DB::table('produk')->insert([
    [
        'nama_produk' => 'Kemeja Flannel Vintage',
        'kategori' => 'Baju',
        'harga' => 850000,
        'ukuran' => 'L',
        'gambar' => 'flannel.jpg',
        'stok' => 1, // <--- Tambahkan ini
    ],
    [
        'nama_produk' => "Celana Levi's 501 Original",
        'kategori' => 'Celana',
        'harga' => 1500000,
        'ukuran' => '32',
        'gambar' => 'levis.jpg',
        'stok' => 1, // <--- Tambahkan ini
    ],
    [
        'nama_produk' => 'Kaos Oversize Nike',
        'kategori' => 'Baju',
        'harga' => 650000,
        'ukuran' => 'XL',
        'gambar' => 'nike.jpg',
        'stok' => 1, // <--- Tambahkan ini
    ]
]);
    }
}