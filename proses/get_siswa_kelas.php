<?php
/**
 * GET SISWA BY KELAS - AJAX endpoint
 */

include '../config.php';

$kelas_id = isset($_GET['kelas_id']) ? (int)$_GET['kelas_id'] : 0;

if ($kelas_id <= 0) {
    header('Content-Type: application/json');
    echo json_encode([]);
    exit;
}

$result = mysqli_query($conn, "SELECT id_siswa, nama_siswa, nis_siswa FROM siswa WHERE kelas_id = $kelas_id ORDER BY nama_siswa ASC");

if (!$result) {
    header('Content-Type: application/json');
    echo json_encode([]);
    exit;
}

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
?>
