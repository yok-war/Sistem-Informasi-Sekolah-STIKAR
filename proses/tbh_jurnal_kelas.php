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
    $kelas_id = (int)($_POST['kelas_id'] ?? 0);
    $keterangan = sanitize($_POST['keterangan'] ?? '');
    
    if (empty($tgl) || $kelas_id <= 0 || empty($keterangan)) {
        scrh('Semua field wajib diisi!', '../jurnal_kelas.php');
        exit;
    }
    
    if (!validateDate($tgl)) {
        scrh('Format tanggal tidak valid!', '../jurnal_kelas.php');
        exit;
    }
    
    $result = insertJurnalKelas($tgl, $kelas_id, $keterangan);
    
    if ($result['success']) {
        scrh('Jurnal berhasil ditambahkan!', '../jurnal_kelas.php');
    } else {
        scrh($result['message'], '../jurnal_kelas.php');
    }
} else {
    header("Location: ../jurnal_kelas.php");
    exit;
}
?>
