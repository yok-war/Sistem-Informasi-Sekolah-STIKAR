<?php
include '../config.php';

$ids = $_POST['selected_ids'] ?? [];

if (hps_many('guru', 'id_guru', $ids) > 0) {
    echo "<script>
        alert('data guru terpilih berhasil dihapus');
        document.location.href = '../guru.php';
    </script>";
} else {
    echo "<script>
        alert('tidak ada data guru yang dihapus');
        document.location.href = '../guru.php';
    </script>";
}
?>
