<?php
include '../config.php';

$ids = $_POST['selected_ids'] ?? [];

if (hps_many('siswa', 'id_siswa', $ids) > 0) {
    echo "<script>
        alert('data siswa terpilih berhasil dihapus');
        document.location.href = '../siswa.php';
    </script>";
} else {
    echo "<script>
        alert('tidak ada data siswa yang dihapus');
        document.location.href = '../siswa.php';
    </script>";
}
?>
