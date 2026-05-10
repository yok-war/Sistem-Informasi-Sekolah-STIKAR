<?php
include '../config.php';
$id = $_GET['id'];
$siswa = query("SELECT * FROM siswa JOIN kelas ON siswa.kelas_id = kelas.id_kelas JOIN jurusan ON kelas.jurusan_id = jurusan.id_jurusan WHERE id_siswa = $id");
$kelas = query("SELECT * FROM kelas JOIN jurusan ON kelas.jurusan_id = jurusan.id_jurusan ORDER BY jurusan.nama_jurusan ASC, kelas.nama_kelas ASC");
if (isset($_POST['submit'])) {
    if (edit_siswa($_POST) > 0) {
        echo "
            <script>
                alert('data berhasil diubah');
                document.location.href = '../siswa.php';
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

<div class="content container mt-5">

    <div class="card shadow-sm mt-4">
        <div class="card-header">
            <h5 class="mb-0">Ubah Data Siswa</h5>
        </div>

        <div class="card-body">

            <form id="formSiswa" method="post" enctype="multipart/form-data">
                <?php foreach ($siswa as $row) : ?>
                    <div class="row">
                        <input type="hidden" name="id" value="<?= $row['id_siswa'] ?>">
                        <input type="hidden" name="foto_lama" value="<?= $row['foto_siswa'] ?>">
                        <div class="form-group col-md-6">
                            <label class="form-label">NIS</label>
                            <input type="text" name="nis" class="form-control" value="<?= $row['nis_siswa'] ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">NISN</label>
                            <input type="text" name="nisn" class="form-control" value="<?= $row['nisn_siswa'] ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="<?= $row['nama_siswa'] ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">TTL</label>
                            <input type="date" name="ttl" class="form-control" value="<?= $row['ttl_siswa'] ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">Alamat</label>
                            <input type="text" name="alamat" class="form-control" value="<?= $row['alamat_siswa'] ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">WA</label>
                            <input type="text" name="wa" class="form-control" value="<?= $row['wa_siswa'] ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">Foto</label>
                            <input type="file" name="foto" class="form-control">
                            <?php if (!empty($row['foto_siswa'])) : ?>
                                <small class="d-block mt-2">Foto saat ini:</small>
                                <img src="../assets/img/new/<?= $row['foto_siswa'] ?>" alt="foto siswa" height="70">
                            <?php endif; ?>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">Kelas</label>
                            <select name="kelas" id="kelas" class="form-select">
                                <option value="<?= $row['id_kelas'] ?>" class="bg-success text-white"><?= $row['nama_kelas'] ?> - <?= $row['nama_jurusan'] ?></option>
                                <?php foreach ($kelas as $kls) : ?>
                                    <option value="<?= $kls['id_kelas'] ?>">
                                        <?= $kls['nama_kelas'] ?> - <?= $kls['nama_jurusan'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="modal-footer mt-4">
                            <a href="../siswa.php" class="me-3">
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
