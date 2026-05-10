<?php
include '../config.php';

header('Content-Type: application/json');

if (!isset($_GET['jurusan_id']) || empty($_GET['jurusan_id'])) {
    echo json_encode([]);
    exit;
}

$jurusan_id = (int)$_GET['jurusan_id'];

// Try without prepared statement first
$query = "SELECT id_kelas, nama_kelas FROM kelas WHERE jurusan_id = $jurusan_id ORDER BY nama_kelas ASC";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(['error' => 'Database query failed: ' . mysqli_error($conn)]);
    exit;
}

$kelas = [];
while ($row = mysqli_fetch_assoc($result)) {
    $kelas[] = $row;
}

// Return the result with debug info if empty
if (empty($kelas)) {
    // Silently return empty array - user will see "Tidak ada kelas" message
    echo json_encode([]);
} else {
    echo json_encode($kelas);
}
?>
