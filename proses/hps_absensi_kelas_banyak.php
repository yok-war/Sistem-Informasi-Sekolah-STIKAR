<?php
/**
 * BULK DELETE ABSENSI KELAS - Delete multiple records
 */

include '../config.php';
include 'helpers.php';
include 'queries.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../absensi_kelas.php");
    exit;
}

$ids = $_POST['selected_ids'] ?? [];

if (empty($ids) || !is_array($ids)) {
    scrh('Pilih minimal satu data!', '../absensi_kelas.php');
    exit;
}

$deleted = 0;
foreach ($ids as $id) {
    $id = (int)$id;
    if ($id > 0) {
        $result = deleteAbsensiKelas($id);
        if ($result['success']) {
            $deleted++;
        }
    }
}

if ($deleted > 0) {
    scrh("$deleted data absensi berhasil dihapus!", '../absensi_kelas.php');
} else {
    scrh('Tidak ada data yang berhasil dihapus!', '../absensi_kelas.php');
}
?>
