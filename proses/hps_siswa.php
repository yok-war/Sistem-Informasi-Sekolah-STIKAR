<?php
    include '../config.php';
    $id = $_GET['id'];
    if (hps_siswa($id) > 0) {
        echo "<script>
        
        alert('data berhasil dihapus')
        document.location.href = '../siswa.php';
        </script>";
    } else {
        echo "<script>
        alert('data gagal dihapus!');
        </script>";
    }
?>