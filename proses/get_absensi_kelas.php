<?php
header('Content-Type: application/json');
include '../config.php';
include 'helpers.php';
include 'queries.php';

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID tidak diberikan']);
    exit;
}

$id = (int)$_GET['id'];
$data = getAbsensiKelasById($id);

if (!$data) {
    http_response_code(404);
    echo json_encode(['error' => 'Data tidak ditemukan']);
    exit;
}

echo json_encode($data);
?>
