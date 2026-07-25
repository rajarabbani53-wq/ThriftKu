<?php
include 'koneksi.php';

if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: auth.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pelanggan = $_SESSION['id_pelanggan'];
    $metode_pengiriman = $_POST['delivery_method']; // 'Ambil di Tempat' atau 'Diantar Admin'
    $alamat_cod = $_POST['alamat_cod'];
    $total_harga = 0;

    // Matikan autocommit untuk memulai transaksi database
    mysqli_begin_transaction($koneksi);

    try {
        // 1. Ambil semua item di keranjang untuk menghitung total harga
        $query_cart = mysqli_query($koneksi, "SELECT keranjang.*, produk.harga FROM keranjang JOIN produk ON keranjang.id_produk = produk.id_produk WHERE id_pelanggan = '$id_pelanggan'");
        
        if (mysqli_num_rows($query_cart) == 0) {
            throw new Exception("Keranjang belanja kosong.");
        }

        $items = [];
        while ($row = mysqli_fetch_assoc($query_cart)) {
            $total_harga += ($row['harga'] * $row['kuantitas']);
            $items[] = $row;
        }

        // 2. Buat data pesanan induk (Status default: Pending)
        $insert_pesanan = mysqli_query($koneksi, "INSERT INTO pesanan (id_pelanggan, total_harga, metode_pengiriman, alamat_cod, status_pesanan) VALUES ('$id_pelanggan', '$total_harga', '$metode_pengiriman', '$alamat_cod', 'Pending')");
        $id_pesanan_baru = mysqli_insert_id($koneksi); // Mengambil ID pesanan yang baru saja dibuat

        // 3. Pindahkan item dari keranjang ke detail pesanan & kurangi stok produk
        foreach ($items as $item) {
            $id_produk = $item['id_produk'];
            $qty = $item['kuantitas'];
            $harga_saat_ini = $item['harga'];

            // Masukkan ke detail pesanan
            mysqli_query($koneksi, "INSERT INTO detail_pesanan (id_pesanan, id_produk, kuantitas, harga_saat_ini) VALUES ('$id_pesanan_baru', '$id_produk', '$qty', '$harga_saat_ini')");

            // Potong stok produk baju/celana tersebut
            mysqli_query($koneksi, "UPDATE produk SET stok = stok - '$qty' WHERE id_produk = '$id_produk'");
        }

        // 4. Kosongkan keranjang belanja pelanggan karena sudah checkout
        mysqli_query($koneksi, "DELETE FROM keranjang WHERE id_pelanggan = '$id_pelanggan'");

        // Jika semua kueri di atas sukses tanpa error, simpan permanen ke database
        mysqli_commit($koneksi);
        echo "<script>alert('Pesanan berhasil dibuat! Silakan tunggu konfirmasi admin.'); window.location.href='orders.php';</script>";

    } catch (Exception $e) {
        // Jika ada satu saja kueri yang gagal, batalkan semua perubahan data
        mysqli_rollback($koneksi);
        echo "<script>alert('Checkout Gagal: " . $e->getMessage() . "'); window.location.href='cart.php';</script>";
    }
}
?>