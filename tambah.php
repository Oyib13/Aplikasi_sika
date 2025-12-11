<?php
include 'auth.php';
include 'layout/navbar.php';
include 'koneksi.php';

// =========================
//   PROSES SIMPAN DATA
// =========================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nim    = $_POST['NIM'];
    $nama   = $_POST['nama'];
    $prodi  = $_POST['prodi'];
    $alamat = $_POST['alamat'];

    $query = "INSERT INTO mahasiswa (NIM, nama, prodi, alamat) 
              VALUES ('$nim', '$nama', '$prodi', '$alamat')";

if (mysqli_query($koneksi, $query)) {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Autentikasi berhasil!',
            showConfirmButton: false,
            timer: 1500
        }).then(() => {
            window.location = 'DataMahasiswa.php';
        });
    </script>
    ";
} else {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Autentikasi gagal!',
            showConfirmButton: true,
            confirmButtonColor: '#d33'
        }).then(() => {
            window.location = 'login.php';
        });
    </script>
    ";
}

}

// =========================
//   AMBIL ENUM PRODI
// =========================
function getEnumValues($koneksi, $table, $column)
{
    $query = mysqli_query($koneksi, "SHOW COLUMNS FROM $table LIKE '$column'");
    $row = mysqli_fetch_array($query);
    preg_match("/^enum\('(.*)'\)$/", $row['Type'], $matches);
    return explode("','", $matches[1]);
}

$prodi_list = getEnumValues($koneksi, 'mahasiswa', 'prodi');
?>


<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white py-3 rounded-top-4">
            <h4 class="mb-0">Tambah Data Mahasiswa</h4>
        </div>

        <div class="card-body">

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">NIM</label>
                    <input type="text" name="NIM" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Program Studi</label>
                    <select name="prodi" class="form-control" required>
                        <option value="">-- Pilih Prodi --</option>

                        <?php foreach ($prodi_list as $p): ?>
                            <option value="<?= $p; ?>"><?= $p; ?></option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="index.php" class="btn btn-secondary">Kembali</a>

            </form>

        </div>
    </div>
</div>

<?php include 'layout/footer.php'; ?>
