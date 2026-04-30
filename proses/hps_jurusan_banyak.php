<?php
include '../config.php';

$ids = $_POST['selected_ids'] ?? [];

if (hps_many('jurusan', 'id_jurusan', $ids) > 0) {
    echo "<script>
        alert('data jurusan terpilih berhasil dihapus');
        document.location.href = '../jurusan.php';
    </script>";
} else {
    echo "<script>
        alert('tidak ada data jurusan yang dihapus');
        document.location.href = '../jurusan.php';
    </script>";
}
?>
