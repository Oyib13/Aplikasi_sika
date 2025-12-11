<?php
include 'auth.php';
include 'koneksi.php';
include 'layout/navbar.php';

// Hanya admin yang boleh akses
if ($_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

$query = "SELECT * FROM user ORDER BY id_user DESC";
$result = mysqli_query($koneksi, $query);
$no = 1;
?>

<div class="container py-4">
    <h3><b>Data User</b></h3>
    <a href="user_tambah.php" class="btn btn-primary my-3">+ Tambah User</a>

    <table class="table table-bordered">
        <thead class="table-primary">
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Role</th>
                <th>Password</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $row['username'] ?></td>

                <td><?= $row['role']; ?></td>

                <!-- Tampilkan password (hati-hati, ini tidak aman) -->
                <td><?= $row['pasword']; ?></td>

                <td>
                    <a href="user_edit.php?id=<?= $row['id_user'] ?>" class="btn btn-success btn-sm">Edit</a>
                    <a onclick="return confirm('Hapus user ini?')" 
                       href="user_hapus.php?id=<?= $row['id_user'] ?>" 
                       class="btn btn-danger btn-sm">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<!-- <?php include 'layout/footer.php'; ?> -->
