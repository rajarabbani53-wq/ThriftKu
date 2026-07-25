<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk; // Pastikan model disesuaikan

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        // Mulai query ke model Produk
        $query = Produk::query();

        // 1. Filter Kategori (Pria/Wanita)
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // 2. Filter Jenis (Baju/Celana)
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Ambil data produk terbaru
        $produks = $query->latest()->get();

        // Lempar data ke view beranda
        return view('beranda', compact('produks'));
    }
}