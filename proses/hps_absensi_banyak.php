<?php
include '../config.php';

$ids = $_POST['selected_ids'] ?? [];

if (hps_many('absensi', 'id_absensi', $ids) > 0) {
    echo "<script>
        alert('data absensi terpilih berhasil dihapus');
        document.location.href = '../absensi.php';
    </script>";
} else {
    echo "<script>
        alert('tidak ada data absensi yang dihapus');
        document.location.href = '../absensi.php';
    </script>";
}
?>
