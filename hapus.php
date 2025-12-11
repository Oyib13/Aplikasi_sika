<?php
include 'auth.php';
include 'koneksi.php';

$id = $_GET['id'];

$query = "DELETE FROM mahasiswa WHERE id_mahasiswa = '$id'";

if (mysqli_query($koneksi, $query)) {
    echo "<script>
            alert('Data berhasil dihapus!');
            window.location='DataMahasiswa.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal menghapus data!');
            window.location='DataMahasiswa.php';
          </script>";
}
?>
