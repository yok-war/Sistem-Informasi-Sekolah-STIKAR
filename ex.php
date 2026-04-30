<?php
include 'config.php';
$data = query("SELECT * FROM siswa JOIN kelas ON siswa.kelas_id = kelas.id_kelas WHERE id_siswa = 14");
$kelas = query("SELECT * FROM kelas");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php foreach ($data as $row) : ?>
        <label for="kelas">nama kelas</label>
        <select name="kelas" id="kelas">
            <option value="<?= $row['id_kelas'] ?>"><?= $row['nama_kelas'] ?></option>
            <?php foreach ($kelas as $kls) : ?>
                <option value="<?= $kls['id_kelas'] ?>"><?= $kls['nama_kelas'] ?></option>
            <?php endforeach; ?>
        </select>
        <h6><?= str_pad($row['id_siswa'], 4, '0', STR_PAD_LEFT) ?></h6>
    <?php endforeach; ?>
</body>

</html>