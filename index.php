<?php
include 'auth.php';
include 'layout/navbar.php';
include 'koneksi.php';

// Jika role = admin → ambil semua data
if ($_SESSION['role'] == 'admin') {
    $q_user = mysqli_query($koneksi, "SELECT COUNT(*) AS total_user FROM user");
    $total_user = mysqli_fetch_assoc($q_user)['total_user'];

    $q_mhs = mysqli_query($koneksi, "SELECT COUNT(*) AS total_mhasiswa FROM mahasiswa");
    $total_mhs = mysqli_fetch_assoc($q_mhs)['total_mhasiswa'];
}

// Jika role = mahasiswa → ambil data dirinya saja
if ($_SESSION['role'] == 'id_mahasiswa') {
    $id_user = $_SESSION['id_mahasiswa'];
    $data_mhs = mysqli_query($koneksi, "SELECT * FROM mahasiswa WHERE id_mahasiswa='$id_user'");
    $mhs = mysqli_fetch_assoc($data_mhs);
}
?>

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

    <!-- ====== DASHBOARD ADMIN ====== -->
    <?php if ($_SESSION['role'] == 'admin') { ?>

    <div class="row">
        <!-- Total User -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Total User</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_user ?></div>
                </div>
            </div>
        </div>

        <!-- Total Mahasiswa -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Total Mahasiswa</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_mhs ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Contoh -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Grafik</h6>
        </div>
        <div class="card-body">
            <canvas id="chartMahasiswa"></canvas>
        </div>
    </div>

    <?php } ?>


    <!-- ====== DASHBOARD MAHASISWA ====== -->
    <?php if ($_SESSION['role'] == 'mahasiswa') { ?>

    <div class="row">
        <!-- Nama Mahasiswa -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        Nama Mahasiswa</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $mhs['nama'] ?></div>
                </div>
            </div>
        </div>

        <!-- Prodi -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        Program Studi</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $mhs['prodi'] ?></div>
                </div>
            </div>
        </div>

        <!-- NIM -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                        NIM</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $mhs['NIM'] ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Akademik</h6>
        </div>
        <div class="card-body">
            Selamat datang <b><?= $mhs['nama'] ?></b>.  
            Ini adalah dashboard mahasiswa yang menampilkan data pribadi Anda.
        </div>
    </div>

    <?php } ?>

</div>

<!-- Chart JS untuk admin -->
<?php if ($_SESSION['role'] == 'admin') { ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
var ctx = document.getElementById("chartMahasiswa").getContext('2d');
var chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ["2021", "2022", "2023"],
        datasets: [{
            label: "Jumlah Mahasiswa",
            data: [30, 50, 40]
        }]
    }
});
</script>
<?php } ?>
<?php include 'layout/footer.php'; ?>