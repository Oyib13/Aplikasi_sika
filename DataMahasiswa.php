<?php
ob_start(); // mulai output buffering
session_start();

require_once "auth.php";
require_once "formcrud.php";
include 'layout/navbar.php';

// hanya admin boleh akses
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: index.php");
    exit;
}

// Proses CRUD sebelum HTML
$fields = ["NIM","nama","prodi","alamat","id_user",];
FormCRUD::handleRequest("mahasiswa", $fields, "id_mahasiswa");

// // HTML / template
// include 'layout/navbar.php';

$predefined = ["prodi" => ["Teknik Informatika", "Sistem Informasi"]];
FormCRUD::render("mahasiswa", $fields, "id_mahasiswa", [], $predefined);

include 'layout/footer.php';
ob_end_flush();
?>
