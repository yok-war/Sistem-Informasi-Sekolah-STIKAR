<?php

$db_server = "localhost";
$db_user   = "root";
$db_pass   = "";
$db_name   = "akademik";

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);


if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

    function query($query) {
        global $conn;
        $result = mysqli_query($conn, $query);
        $rows = [];
        while($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

function add($data) {
    global $conn;
    $nis = htmlspecialchars($data['nis']);
    $nisn = htmlspecialchars($data['nisn']);
    $nama = htmlspecialchars($data['nama']);
    $ttl = htmlspecialchars($data['ttl']);
    $alamat = htmlspecialchars($data['alamat']);
    $wa = htmlspecialchars($data['wa']);
    $foto = htmlspecialchars($data['foto']);
    $kelas = htmlspecialchars($data['kelas']);

    $query = "INSERT INTO siswa VALUES('','$nama','$nis','$nisn','$ttl','$alamat','$wa','$foto','$kelas')";
    mysqli_query($conn,$query);

    return mysqli_affected_rows($conn);
}


function hapus($id) {
    global $conn;
    mysqli_query($conn, "DELETE FROM guru WHERE id_guru = $id");

    return mysqli_affected_rows($conn);
}
?>