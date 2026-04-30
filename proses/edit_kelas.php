<?php
include '../config.php';
$id  = $_GET['id'];
$kelas = query("SELECT * FROM kelas JOIN jurusan ON kelas.jurusan_id = jurusan.id_jurusan WHERE id_kelas = $id");
$jurusan = query("SELECT * FROM jurusan");
$all_kelas = query("SELECT * FROM kelas");

if (isset($_POST['submit'])) {
    if (edit_kelas($_POST) > 0) {
        echo "
            <script>
                alert('data berhasil diubah');
                document.location.href = '../kelas.php';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('data gagal diubah');
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

            <form id="formKelas" method="post">
                <?php foreach ($kelas as $row) : ?>
                    <div class="row">
                        <input type="hidden" name="id" value="<?= $row['id_kelas'] ?>">
                        <div class="form-group col-md-6">
                            <label class="form-label" for="nama">Nama Kelas</label>
                            <input type="text" name="nama" class="form-control" value="<?= $row['nama_kelas'] ?>" />
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">Jurusan</label>
                            <select name="jurusan" class="form-select" id="jurusan">
                                <option value="<?= $row['id_jurusan'] ?>"><?= $row['nama_jurusan'] ?></option>
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
                <?php endforeach; ?>
            </form>

        </div>
    </div>

</div>

<?php include '../includes/footer.php'; ?>