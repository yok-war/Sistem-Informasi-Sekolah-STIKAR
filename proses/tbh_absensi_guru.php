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
    $siswa_id = (int)($_POST['siswa_id'] ?? 0);
    $status = sanitize($_POST['status'] ?? '');
    
    if (empty($tgl) || $guru_id <= 0 || $kelas_id <= 0 || empty($status)) {
        scrh('Tanggal, guru, kelas, dan status wajib diisi!', '../absensi_guru.php');
        exit;
    }
    
    if (!validateDate($tgl)) {
        scrh('Format tanggal tidak valid!', '../absensi_guru.php');
        exit;
    }
    
    if (!in_array($status, ['hadir', 'izin', 'sakit', 'alpha'])) {
        scrh('Status tidak valid!', '../absensi_guru.php');
        exit;
    }
    
    $result = insertAbsensiGuru($tgl, $guru_id, $kelas_id, $siswa_id, $status);
    
    if ($result['success']) {
        scrh('Data absensi berhasil ditambahkan!', '../absensi_guru.php');
    } else {
        scrh($result['message'], '../absensi_guru.php');
    }
} else {
    header("Location: ../absensi_guru.php");
    exit;
}
?>
