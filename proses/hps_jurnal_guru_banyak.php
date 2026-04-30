<?php
include '../config.php';
include 'helpers.php';
include 'queries.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../jurnal_guru.php");
    exit;
}

$ids = $_POST['selected_ids'] ?? [];

if (empty($ids) || !is_array($ids)) {
    scrh('Pilih minimal satu data!', '../jurnal_guru.php');
    exit;
}

$deleted = 0;
foreach ($ids as $id) {
    $id = (int)$id;
    if ($id > 0) {
        $result = deleteJurnalGuru($id);
        if ($result['success']) {
            $deleted++;
        }
    }
}

if ($deleted > 0) {
    scrh("$deleted jurnal berhasil dihapus!", '../jurnal_guru.php');
} else {
    scrh('Tidak ada data yang berhasil dihapus!', '../jurnal_guru.php');
}
?>
