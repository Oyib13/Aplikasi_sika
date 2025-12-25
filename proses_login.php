<?php
session_start(); // HARUS DI PALING ATAS

require_once "Database.php";

if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['pasword']); // SESUAI DB

    $db = Database::connect();

    $stmt = $db->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "<script>alert('Username salah!'); window.location='login.php';</script>";
        exit;
    }

    // CEK PASSWORD
    if (md5($password) !== $user['pasword']) {
        echo "<script>alert('Password salah!'); window.location='login.php';</script>";
        exit;
    }

    // LOGIN BERHASIL
    $_SESSION['id_user'] = $user['id_user'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    header("Location: index.php");
    exit;
}
else {
    header("Location: login.php");
    exit;
}
