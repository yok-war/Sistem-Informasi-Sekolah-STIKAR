<?php
include '../config.php';
include 'helpers.php';
include 'queries.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tgl = sanitize($_POST['tgl'] ?? '');
    $guru_id = (int)($_POST['guru_id'] ?? 0);
    $kelas_id = (int)($_POST['kelas_id'] ?? 0);
    $keterangan = sanitize($_POST['keterangan'] ?? '');
    
    if (empty($tgl) || $guru_id <= 0 || $kelas_id <= 0 || empty($keterangan)) {
        scrh('Semua field wajib diisi!', '../jurnal_guru.php');
        exit;
    }
    
    if (!validateDate($tgl)) {
        scrh('Format tanggal tidak valid!', '../jurnal_guru.php');
        exit;
    }
    
    $result = insertJurnalGuru($tgl, $guru_id, $kelas_id, $keterangan);
    
    if ($result['success']) {
        scrh('Jurnal berhasil ditambahkan!', '../jurnal_guru.php');
    } else {
        scrh($result['message'], '../jurnal_guru.php');
    }
} else {
    header("Location: ../jurnal_guru.php");
    exit;
}
?>
