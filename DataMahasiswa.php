<?php
include 'auth.php';
include 'layout/navbar.php';
include 'koneksi.php';

// Search

if (isset($_GET['cari']) && $_GET['cari'] != '') {
    $cari = mysqli_real_escape_string($koneksi, $_GET['cari']);
    $query = "SELECT * FROM mahasiswa 
              WHERE NIM LIKE '%$cari%' 
              OR nama LIKE '%$cari%'
              OR prodi LIKE '%$cari%'
              OR alamat LIKE '%$cari%'
              ORDER BY id_mahasiswa DESC";
} else {
    $query = "SELECT * FROM mahasiswa ORDER BY id_mahasiswa DESC";
}

$result = mysqli_query($koneksi, $query);

?>

<div class="card-header text-white py-3 rounded-top-4 d-flex justify-content-between align-items-center">
    <h4 class="mb-0 text-dark"><b>Data Mahasiswa</b></h4>

    <!-- Search di kanan -->
    <form method="GET" class="d-flex" style="max-width: 300px;">
        <input type="text" name="cari" class="form-control me-2" 
            placeholder="Cari NIM / Nama / Prodi" 
            value="<?= isset($_GET['cari']) ? $_GET['cari'] : ''; ?>">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>

                   <!-- Form Search -->



        <div class="card-body">

            <!-- Tombol Tambah hanya muncul untuk admin -->
            <?php if ($_SESSION['role'] == 'admin') : ?>
                <a href="tambah.php" class="btn btn-primary mb-3">Tambah Data</a>
            <?php endif; ?>
 

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Program Studi</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="text-center">
                        <?php 
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $row['NIM']; ?></td>
                                <td><?= $row['nama']; ?></td>
                                <td><?= $row['prodi']; ?></td>
                                <td><?= $row['alamat']; ?></td>

                                <td>
                                    <?php if ($_SESSION['role'] == 'admin') : ?>
                                        <a href="edit.php?id=<?= $row['id_mahasiswa']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="hapus.php?id=<?= $row['id_mahasiswa']; ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Yakin ingin menghapus?')">
                                           Hapus
                                        </a>
                                        
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Tidak Ada Akses</span>
                                    <?php endif; ?>
                                </td>

                            </tr> 
                        <?php endwhile; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'layout/footer.php'; ?>
