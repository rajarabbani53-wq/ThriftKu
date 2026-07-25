<?php
include 'koneksi.php';

// Pastikan yang mengakses adalah admin
if (!isset($_SESSION['username_admin'])) {
    header("Location: login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pesanan = $_POST['id_pesanan'];
    $aksi = $_POST['aksi']; // Nilainya bisa 'Konfirmasi' atau 'Batalkan'

    if ($aksi == 'Konfirmasi') {
        // Update status pesanan menjadi Terkonfirmasi
        $query = "UPDATE pesanan SET status_pesanan = 'Terkonfirmasi' WHERE id_pesanan = '$id_pesanan'";
        if (mysqli_query($koneksi, $query)) {
            header("Location: pesanan.php?status=sukses_konfirmasi");
        }
    } 
    
    elseif ($aksi == 'Batalkan') {
        $alasan_pembatalan = $_POST['alasan_pembatalan']; // Wajib diisi dari form modal admin

        mysqli_begin_transaction($koneksi);
        try {
            // 1. Ubah status pesanan menjadi Dibatalkan beserta alasannya
            mysqli_query($koneksi, "UPDATE pesanan SET status_pesanan = 'Dibatalkan', alasan_pembatalan = '$alasan_pembatalan' WHERE id_pesanan = '$id_pesanan'");

            // 2. Ambil detail barang dari pesanan ini untuk mengembalikan stoknya
            $query_detail = mysqli_query($koneksi, "SELECT id_produk, kuantitas FROM detail_pesanan WHERE id_pesanan = '$id_pesanan'");
            
            while ($row = mysqli_fetch_assoc($query_detail)) {
                $id_produk = $row['id_produk'];
                $qty = $row['kuantitas'];

                // Tambahkan kembali stok produk yang batal dibeli
                mysqli_query($koneksi, "UPDATE produk SET stok = stok + '$qty' WHERE id_produk = '$id_produk'");
            }

            mysqli_commit($koneksi);
            header("Location: pesanan.php?status=sukses_pembatalan");
        } catch (Exception $e) {
            mysqli_rollback($koneksi);
            echo "Gagal membatalkan pesanan: " . $e->getMessage();
        }
    }
}
?>