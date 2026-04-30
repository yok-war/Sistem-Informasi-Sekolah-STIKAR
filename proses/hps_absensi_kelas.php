<?php
/**
 * DELETE ABSENSI KELAS - Single record deletion
 */

include '../config.php';
include 'helpers.php';
include 'queries.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    scrh('ID tidak valid!', '../absensi_kelas.php');
    exit;
}

$result = deleteAbsensiKelas($id);

if ($result['success']) {
    scrh('Data absensi berhasil dihapus!', '../absensi_kelas.php');
} else {
    scrh($result['message'], '../absensi_kelas.php');
}
?>
