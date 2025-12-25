<?php
ob_start(); // mulai output buffering
session_start();

require_once "auth.php";
require_once "formcrud.php";

// hanya admin boleh akses
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: index.php");
    exit;
}

// Proses CRUD sebelum HTML
$fields = ["username","pasword","role",];
FormCRUD::handleRequest("user", $fields, "id_user");

// HTML / template
include 'layout/navbar.php';

$predefined = ["role" => ["admin", "mahasiswa"]];
FormCRUD::render("user", $fields, "id_user", [], $predefined);

include 'layout/footer.php';
ob_end_flush();
?>
