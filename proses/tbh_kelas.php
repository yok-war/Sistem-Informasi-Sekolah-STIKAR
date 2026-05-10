<?php
include '../config.php';
$jurusan = query("SELECT * FROM jurusan");
if (isset($_POST['submit'])) {
    if (tbh_kelas($_POST) > 0) {
        echo "
                <script>
                    alert('data berhasil ditambah');
                    document.location.href = '../kelas.php';
                </script>
            ";
    } else {
        echo "
                <script>
                    alert('data gagal ditambah');
                    document.location.href = '../kelas.php';
                </script>
            ";
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="content container mt-4">

    <div class="card shadow-sm mt-5">
        <div class="card-header">
            <h5 class="mb-0"> Tambah Data Kelas</h5>
        </div>

        <div class="card-body">

            <form id="formKelas" method="post">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="form-label" for="nama">Tingkat</label>
                        <select name="kelas" class="form-select" id="kelas">
                            <option value="X">X</option>
                            <option value="XI">XI</option>
                            <option value="XII">XII</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label">Jurusan</label>
                        <select name="jurusan" class="form-select" id="jurusan">
                            <?php foreach ($jurusan as $row) : ?>
                                <option value="<?= $row['id_jurusan'] ?>"><?= $row['nama_jurusan'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="modal-footer mt-4">
                        <a href="../kelas.php" class="me-3">
                            <button type="button" class="btn btn-secondary">Batal</button>
                        </a>
                        <button type="submit" name="submit" class="btn btn-primary me-3">Simpan</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

</div>

<?php include '../includes/footer.php'; ?>