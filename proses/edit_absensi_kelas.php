<?php
/**
 * EDIT ABSENSI KELAS - Update attendance record
 */

include '../config.php';
include 'helpers.php';
include 'queries.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $tgl = sanitize($_POST['tgl'] ?? '');
    $kelas_id = (int)($_POST['kelas_id'] ?? 0);
    $siswa_id = (int)($_POST['siswa_id'] ?? 0);
    $status = sanitize($_POST['status'] ?? '');
    
    // Validation
    if ($id <= 0 || empty($status) || empty($tgl) || $kelas_id <= 0 || $siswa_id <= 0) {
        scrh('Data tidak valid!', '../absensi_kelas.php');
        exit;
    }
    
    if (!in_array($status, ['hadir', 'izin', 'sakit', 'alpha'])) {
        scrh('Status tidak valid!', '../absensi_kelas.php');
        exit;
    }
    
    // Update
    $result = updateAbsensiKelas($id, $tgl, $kelas_id, $siswa_id, $status);
    
    if ($result['success']) {
        scrh('Data absensi berhasil diperbarui!', '../absensi_kelas.php');
    } else {
        scrh($result['message'], '../absensi_kelas.php');
    }
} else {
    header("Location: ../absensi_kelas.php");
    exit;
}
?>
