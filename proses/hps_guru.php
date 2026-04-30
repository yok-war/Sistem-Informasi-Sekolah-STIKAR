<?php
    include '../config.php';
    $id = $_GET['id'];
    if (hps_guru($id) > 0) {
        echo "<script>
            
            alert('data berhasil dihapus')
            document.location.href = '../guru.php';
            </script>";
    } else {
        echo "<script>
            alert('data gagal dihapus!');
            </script>";
    }
?>