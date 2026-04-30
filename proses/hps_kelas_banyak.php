<?php
include '../config.php';

$ids = $_POST['selected_ids'] ?? [];

if (hps_kelas_with_dependencies($ids) > 0) {
    echo "<script>
        alert('data kelas terpilih dan data terkaitnya berhasil dihapus');
        document.location.href = '../kelas.php';
    </script>";
} else {
    echo "<script>
        alert('tidak ada data kelas yang dihapus');
        document.location.href = '../kelas.php';
    </script>";
}
?>
