<?php
include '../config.php';

if (!absensi_table_exists()) {
    echo "
        <script>
            alert('tabel absensi belum ada');
            document.location.href = '../absensi.php';
        </script>
    ";
    exit;
}

$id = $_GET['id'];

if (hps_absensi($id) > 0) {
    echo "
        <script>
            alert('data absensi berhasil dihapus');
            document.location.href = '../absensi.php';
        </script>
    ";
} else {
    echo "
        <script>
            alert('data absensi gagal dihapus');
            document.location.href = '../absensi.php';
        </script>
    ";
}
?>
