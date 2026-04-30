<?php
include 'config.php';
$id = $_GET['id'];
if (hapus($id) > 0) {
    echo "<script>
        alert('data berhasil dihapus')
        document.location.href = 'guru.php';
        </script>";
} else {
    echo "<script>
        alert('data gagal dihapus');
        </script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Tambah data</title>
</head>

<body>

</body>

</html>