<?php
include 'config.php';

// Test jurusan
echo "=== TEST JURUSAN ===\n";
$jurusan = mysqli_query($conn, "SELECT id_jurusan, nama_jurusan FROM jurusan LIMIT 1");
$jr = mysqli_fetch_assoc($jurusan);
echo "Jurusan: "; var_dump($jr);

// Test kelas with jurusan_id = 1
echo "\n=== TEST KELAS (jurusan_id = 1) ===\n";
$kelas = mysqli_query($conn, "SELECT id_kelas, nama_kelas, jurusan_id FROM kelas WHERE jurusan_id = 1 LIMIT 5");
while ($k = mysqli_fetch_assoc($kelas)) {
    echo "Kelas: {$k['nama_kelas']} (ID: {$k['id_kelas']}, Jurusan: {$k['jurusan_id']})\n";
}

// Test siswa with kelas_id = 1
echo "\n=== TEST SISWA (kelas_id = 1) ===\n";
$siswa = mysqli_query($conn, "SELECT id_siswa, nama_siswa, nis_siswa, kelas_id FROM siswa WHERE kelas_id = 1 LIMIT 5");
while ($s = mysqli_fetch_assoc($siswa)) {
    echo "Siswa: {$s['nama_siswa']} (ID: {$s['id_siswa']}, NIS: {$s['nis_siswa']}, Kelas: {$s['kelas_id']})\n";
}
?>
