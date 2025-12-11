<?php
session_start();

// Pastikan token ada dan cocok
if (!isset($_POST['token']) || !isset($_SESSION['token']) || !hash_equals($_SESSION['token'], $_POST['token'])) {
    $status = "error";
    $message = "Token tidak valid!";
} 
else 
{
    $status = "success";
    $message = "Anda berhasil logout!";
}

// Hapus session SETELAH pengecekan token
session_unset();
session_destroy();

// Redirect
$redirect = "login.php";
?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<script>
Swal.fire({
    icon: '<?php echo $status; ?>',
    title: '<?php echo $message; ?>',
    timer: 2000,
    showConfirmButton: false
}).then(() => {
    window.location = '<?php echo $redirect; ?>';
});
</script>

</body>
</html>
