<?php
include '../config.php';
$id  = $_GET['id'];
$jurusan = query("SELECT * FROM jurusan WHERE id_jurusan = $id");
if (isset($_POST['submit'])) {
    if (edit_jurusan($_POST) > 0) {
        echo "
            <script>
                alert('data berhasil diubah');
                document.location.href = '../jurusan.php';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('data gagal diubah');
                document.location.href = '../edit_jurusan.php';
            </script>
        ";
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="content">

    <div class="card shadow-sm mt-5">
        <div class="card-header">
            <h5 class="mb-0">Ubah Data Siswa</h5>
        </div>

        <div class="card-body">
                <form id="forJurusan" method="post">
                    <?php foreach ($jurusan as $row) : ?>
                        <input type="hidden" name="id" value="<?= $row['id_jurusan'] ?>">
                        <div class="form-group">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <div class="form-group">
                                <label class="form-label">Nama Jurusan</label>
                                <input type="text" name="nama" class="form-control" value="<?= $row['nama_jurusan'] ?>">
                            </div>
                        </div>
                        <div class="form-group mt-4">
                            <a href="../jurusan.php" class="me-3">
                                <button type="button" class="btn btn-secondary">Batal</button>
                            </a>
                            <button type="submit" name="submit" class="btn btn-primary me-3">Simpan</button>
                        </div>
                    <?php endforeach; ?>
                </form>
        </div>
    </div>

</div>

<?php include '../includes/footer.php'; ?>