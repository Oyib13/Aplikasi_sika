<?php
$host     = "localhost";   // Server database
$user     = "root";        // Username MySQL
$password = "";            // Password MySQL (kosong jika XAMPP)
$database = "sika"; // Ganti dengan nama database kamu

// Koneksi ke MySQL
$koneksi = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (mysqli_connect_errno()) {
    echo "Koneksi gagal: " . mysqli_connect_error();
    exit;
}
?>
