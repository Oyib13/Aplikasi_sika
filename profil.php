<?php
include 'auth.php';
include 'layout/navbar.php';
include 'koneksi.php';

// Pastikan hanya mahasiswa yg bisa akses halaman profil mahasiswa
if ($_SESSION['role'] != 'mahasiswa') {
    header("Location: index.php");
    exit;
}

// Ambil ID mahasiswa dari session login
$id = $_SESSION['id_mahasiswa'];

// Jika id_mahasiswa kosong
if (!$id) {
    echo "<div class='alert alert-danger'>Profil tidak ditemukan! (id_mahasiswa NULL)</div>";
    exit;
}

// Query ambil data mahasiswa
$query = mysqli_query($koneksi, "SELECT * FROM mahasiswa WHERE id_mahasiswa='$id'");
$data = mysqli_fetch_assoc($query);

// Jika data tidak ada
if (!$data) {
    echo "<div class='alert alert-danger'>Data mahasiswa tidak ditemukan!</div>";
    exit;
}
?>

<div class="container py-5">
    <div class="card shadow-lg p-4">
        <h3 class="mb-4"><b>Profil Mahasiswa</b></h3>

        <table class="table table-bordered">
            <tr>
                <th>NIM</th>
                <td><?= $data['NIM']; ?></td>
            </tr>

            <tr>
                <th>Nama</th>
                <td><?= $data['nama']; ?></td>
            </tr>

            <tr>
                <th>Prodi</th>
                <td><?= $data['prodi']; ?></td>
            </tr>

            <tr>
                <th>Alamat</th>
                <td><?= $data['alamat']; ?></td>
            </tr>
        </table>
    </div>
</div>

<?php include 'layout/footer.php'; ?>
