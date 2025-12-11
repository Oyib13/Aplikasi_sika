<?php
include 'auth.php';
include 'layout/navbar.php';
include 'koneksi.php';

// ===== Ambil data lama berdasarkan ID =====
$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM mahasiswa WHERE id_mahasiswa = '$id'");
$data  = mysqli_fetch_assoc($query);

// ===== Ambil daftar ENUM =====
function getEnumValues($koneksi, $table, $column)
{
    $query = mysqli_query($koneksi, "SHOW COLUMNS FROM $table LIKE '$column'");
    $row = mysqli_fetch_array($query);
    preg_match("/^enum\('(.*)'\)$/", $row['Type'], $matches);
    return explode("','", $matches[1]);
}

$prodi_list = getEnumValues($koneksi, 'mahasiswa', 'prodi');

// ===== Update data ketika submit =====
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nim    = $_POST['NIM'];
    $nama   = $_POST['nama'];
    $prodi  = $_POST['prodi'];
    $alamat = $_POST['alamat'];

    $update = "UPDATE mahasiswa SET 
                NIM = '$nim',
                nama = '$nama',
                prodi = '$prodi',
                alamat = '$alamat'
               WHERE id_mahasiswa = '$id'";

    if (mysqli_query($koneksi, $update)) {
        echo "<script>
                alert('Data berhasil diperbarui!');
                window.location='DataMahasiswa.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Gagal memperbarui data!');
                window.location='edit.php?id=$id';
              </script>";
        exit;
    }
}
?>


<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-dark py-3 rounded-top-4">
            <h4 class="mb-0 text-light"><b>Edit Data Mahasiswa</b> </h4>
        </div>

        <div class="card-body">

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">NIM</label>
                    <input type="text" name="NIM" class="form-control" value="<?= $data['NIM']; ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" value="<?= $data['nama']; ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Program Studi</label>
                    <select name="prodi" class="form-control" required>
                        <option value="">-- Pilih Prodi --</option>

                        <?php foreach ($prodi_list as $p): ?>
                            <option value="<?= $p; ?>" <?= $data['prodi'] == $p ? 'selected' : ''; ?>>
                                <?= $p; ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" required><?= $data['alamat']; ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Perbarui</button>
                <a href="DataMahasiswa.php" class="btn btn-secondary">Kembali</a>

            </form>

        </div>
    </div>
</div>

<?php include 'layout/footer.php'; ?>
