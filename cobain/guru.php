<?php
include 'config.php';
$guru = query("SELECT * FROM guru")
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>nama</th>
                <th>aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($guru as $gr) { ?>
                <tr>
                    <td><?= $gr['nama_guru'] ?></td>
                    <td>
                        <button type="submit"><a href="hapus.php?id=<?= $gr['id_guru'] ?>">hapus</a></button>
                    </td>
                </tr>
            <?php } ?>

        </tbody>
    </table>

</body>

</html>