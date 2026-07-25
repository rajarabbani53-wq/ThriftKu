<?php
include 'koneksi.php';

// Pastikan pelanggan sudah login
if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: auth.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pelanggan = $_SESSION['id_pelanggan'];
    $id_produk = $_POST['id_produk']; // Dikirim lewat input hidden dari form
    $kuantitas = 1;

    // 1. Cek apakah barang tersebut sudah ada di keranjang pelanggan
    $cek_keranjang = mysqli_query($koneksi, "SELECT * FROM keranjang WHERE id_pelanggan = '$id_pelanggan' AND id_produk = '$id_produk'");
    
    if (mysqli_num_rows($cek_keranjang) > 0) {
        // Jika sudah ada, update kuantitasnya (tambah 1)
        $query = "UPDATE keranjang SET kuantitas = kuantitas + 1 WHERE id_pelanggan = '$id_pelanggan' AND id_produk = '$id_produk'";
    } else {
        // Jika belum ada, masukkan data baru ke keranjang
        $query = "INSERT INTO keranjang (id_pelanggan, id_produk, kuantitas) VALUES ('$id_pelanggan', '$id_produk', '$kuantitas')";
    }

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Produk berhasil dimasukkan ke keranjang!'); window.location.href='index.php';</script>";
    } else {
        echo "Gagal: " . mysqli_error($koneksi);
    }
}
?>