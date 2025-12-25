<?php
include 'auth.php';
include 'layout/navbar.php';
require_once "Database.php";

$db = Database::connect();

// ===== ADMIN =====
if ($_SESSION['role'] === 'admin') {
    // Total user
    $stmt_user = $db->query("SELECT COUNT(*) AS total_user FROM user");
    $total_user = $stmt_user->fetch(PDO::FETCH_ASSOC)['total_user'];

    // Total mahasiswa
    $stmt_mhs = $db->query("SELECT COUNT(*) AS total_mhasiswa FROM mahasiswa");
    $total_mhs = $stmt_mhs->fetch(PDO::FETCH_ASSOC)['total_mhasiswa'];

    // Data untuk chart (misal berdasarkan prodik)
    $stmt_chart = $db->query("SELECT prodi, COUNT(*) AS jumlah FROM mahasiswa GROUP BY prodi ORDER BY prodi ASC");
    $chart_data = $stmt_chart->fetchAll(PDO::FETCH_ASSOC);
    $chart_labels = [];
    $chart_values = [];
    foreach ($chart_data as $row) {
        $chart_labels[] = $row['prodi'];
        $chart_values[] = $row['jumlah'];
    }
}

// ===== MAHASISWA =====
if ($_SESSION['role'] === 'mahasiswa') {
    $id_user = $_SESSION['id_user']; // pastikan ini id_user yang ada di tabel mahasiswa

    // Ambil data mahasiswa yang terkait dengan user login
    $stmt = $db->prepare("SELECT * FROM mahasiswa WHERE id_user=?");
    $stmt->execute([$id_user]);
    $mhs = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

    <!-- DASHBOARD ADMIN -->
    <?php if ($_SESSION['role'] === 'admin') { ?>
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

    <!-- Grafik Mahasiswa -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Grafik Mahasiswa</h6>
        </div>
        <div class="card-body">
            <canvas id="chartMahasiswa"></canvas>
        </div>
    </div>
    <?php } ?>

    <!-- DASHBOARD MAHASISWA -->
    <?php if ($_SESSION['role'] === 'mahasiswa') { ?>
    <div class="row">
        <!-- Nama Mahasiswa -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Nama Mahasiswa</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $mhs['nama'] ?></div>
                </div>
            </div>
        </div>

        <!-- Prodi -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Program Studi</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $mhs['prodi'] ?></div>
                </div>
            </div>
        </div>

        <!-- NIM -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">NIM</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $mhs['NIM'] ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Akademik -->
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
        labels: <?= json_encode($chart_labels) ?>,
        datasets: [{
            label: "Jumlah Mahasiswa",
            data: <?= json_encode($chart_values) ?>,
            backgroundColor: "rgba(78, 115, 223, 0.7)"
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>
<?php } ?>

<?php include 'layout/footer.php'; ?>