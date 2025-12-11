<?php
session_start();
include 'koneksi.php';

// Ambil data dari form
$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = mysqli_real_escape_string($koneksi, $_POST['password']);

// Enkripsi MD5
$password_md5 = md5($password);

// Query cek username & password
$query = "SELECT * FROM user WHERE username='$username' AND pasword='$password_md5'";
$result = mysqli_query($koneksi, $query);

$login_status = "";
$login_message = "";

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    // Simpan session
    $_SESSION['user_id']       = $user['id_user'];
    $_SESSION['username']      = $user['username'];
    $_SESSION['role']          = $user['role'];
    $_SESSION['id_mahasiswa']  = $user['id_mahasiswa'];  // ✔ diperbaiki
    $_SESSION['token']         = bin2hex(random_bytes(32));

    $login_status = "success";
    $login_message = "Selamat datang, " . $user['username'];

} else {
    $login_status = "error";
    $login_message = "Username atau password salah!";
}
?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<script>
    Swal.fire({
        icon: '<?php echo $login_status; ?>',
        title: '<?php echo $login_message; ?>',
        timer: 2000,
        showConfirmButton: false
    }).then(() => {
        <?php if ($login_status == "success") { ?>
            window.location = "index.php";
        <?php } else { ?>
            window.location = "login.php";
        <?php } ?>
    });
</script>

</body>
</html>
