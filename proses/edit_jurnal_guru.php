<?php
include '../config.php';
include 'helpers.php';
include 'queries.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $keterangan = sanitize($_POST['keterangan'] ?? '');
    
    if ($id <= 0 || empty($keterangan)) {
        scrh('Data tidak valid!', '../jurnal_guru.php');
        exit;
    }
    
    $result = updateJurnalGuru($id, $keterangan);
    
    if ($result['success']) {
        scrh('Jurnal berhasil diperbarui!', '../jurnal_guru.php');
    } else {
        scrh($result['message'], '../jurnal_guru.php');
    }
} else {
    header("Location: ../jurnal_guru.php");
    exit;
}
?>
