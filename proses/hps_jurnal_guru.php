<?php
include '../config.php';
include 'helpers.php';
include 'queries.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    scrh('ID tidak valid!', '../jurnal_guru.php');
    exit;
}

$result = deleteJurnalGuru($id);

if ($result['success']) {
    scrh('Jurnal berhasil dihapus!', '../jurnal_guru.php');
} else {
    scrh($result['message'], '../jurnal_guru.php');
}
?>
