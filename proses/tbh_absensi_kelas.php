<?php
/**
 * ADD ABSENSI KELAS - Insert new attendance record
 */

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
    $siswa_id = (int)($_POST['siswa_id'] ?? 0);
    $status = sanitize($_POST['status'] ?? '');
    
    // Validation
    if (empty($tgl) || $kelas_id <= 0 || $siswa_id <= 0 || empty($status)) {
        scrh('Semua field wajib diisi!', '../absensi_kelas.php');
        exit;
    }
    
    if (!validateDate($tgl)) {
        scrh('Format tanggal tidak valid!', '../absensi_kelas.php');
        exit;
    }
    
    if (!in_array($status, ['hadir', 'izin', 'sakit', 'alpha'])) {
        scrh('Status tidak valid!', '../absensi_kelas.php');
        exit;
    }
    
    // Insert
    $result = insertAbsensiKelas($tgl, $kelas_id, $siswa_id, $status);
    
    if ($result['success']) {
        scrh('Data absensi berhasil ditambahkan!', '../absensi_kelas.php');
    } else {
        scrh($result['message'], '../absensi_kelas.php');
    }
} else {
    header("Location: ../absensi_kelas.php");
    exit;
}
?>
