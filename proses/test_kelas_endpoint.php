<?php
include 'config.php';

$test_jurusan_id = isset($_GET['jurusan_id']) ? (int)$_GET['jurusan_id'] : 1;

echo "Testing endpoint untuk jurusan_id = " . $test_jurusan_id . "\n";
echo "========================================\n\n";

// Test 1: Check if jurusan exists
echo "1. Check Jurusan:\n";
$result = mysqli_query($conn, "SELECT * FROM jurusan WHERE id_jurusan = $test_jurusan_id");
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo "   ✓ Found: " . print_r($row, true) . "\n";
} else {
    echo "   ✗ Jurusan tidak ditemukan\n";
}

// Test 2: Check kelas in that jurusan
echo "\n2. Check Kelas dalam Jurusan $test_jurusan_id:\n";
$result = mysqli_query($conn, "SELECT id_kelas, nama_kelas, jurusan_id FROM kelas WHERE jurusan_id = $test_jurusan_id");
if ($result && mysqli_num_rows($result) > 0) {
    echo "   ✓ Found " . mysqli_num_rows($result) . " kelas:\n";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "      - {$row['nama_kelas']} (ID: {$row['id_kelas']})\n";
    }
} else {
    echo "   ✗ Tidak ada kelas dalam jurusan ini\n";
}

// Test 3: Test endpoint response
echo "\n3. Test JSON Response:\n";
header('Content-Type: application/json');
echo "   GET /proses/get_kelas_by_jurusan.php?jurusan_id=" . $test_jurusan_id . "\n";
echo "   Response:\n";

$query = "SELECT id_kelas, nama_kelas FROM kelas WHERE jurusan_id = ? ORDER BY nama_kelas ASC";
$stmt = mysqli_prepare($conn, $query);
if (!$stmt) {
    echo json_encode(['error' => mysqli_error($conn)]);
    exit;
}

mysqli_stmt_bind_param($stmt, 'i', $test_jurusan_id);
if (!mysqli_stmt_execute($stmt)) {
    echo json_encode(['error' => mysqli_stmt_error($stmt)]);
    exit;
}

$result = mysqli_stmt_get_result($stmt);
$kelas = [];
while ($row = mysqli_fetch_assoc($result)) {
    $kelas[] = $row;
}

echo json_encode($kelas, JSON_PRETTY_PRINT);
?>
