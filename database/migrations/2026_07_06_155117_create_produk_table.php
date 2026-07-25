<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('produk', function (Blueprint $table) {
        $table->id('id_produk'); // <-- UBAH BAGIAN INI (Tambahkan 'id_produk' di dalam kurung)
        $table->string('nama_produk'); 
        $table->integer('harga');
        $table->integer('stok')->default(1);
        $table->string('kategori');
        $table->string('ukuran');
        $table->string('gambar')->nullable();
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
