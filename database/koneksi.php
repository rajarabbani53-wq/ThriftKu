<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "thriftku_db";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Memulai session untuk menyimpan data login user/admin
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>  