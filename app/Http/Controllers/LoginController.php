<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function autentikasi(Request $request)
    {
        // 1. Validasi input agar tidak kosong (mengatasi peringatan di image_d13fdc.png)
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Data akun uji coba hardcoded
        $dummyEmail = 'pengguna@thriftku.com';
        $dummyPassword = 'pengguna123';

        // 3. Pengecekan kecocokan data
        if ($request->email === $dummyEmail && $request->password === $dummyPassword) {
            // Jika benar, langsung arahkan ke halaman Utama/Katalog Pelanggan
            return redirect('/');
        }

        // Jika salah, kembali ke halaman login dengan pesan eror
        return back()->withErrors([
            'email' => 'Email atau kata sandi salah!',
        ])->withInput();
    }
}
