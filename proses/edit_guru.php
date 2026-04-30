<?php
include '../config.php';
$id  = $_GET['id'];
$data = query("SELECT * FROM guru WHERE id_guru = $id");

if (isset($_POST['submit'])) {
    if (edit_guru($_POST) > 0) {
        echo "
            <script>
                alert('data berhasil diubah');
                document.location.href = '../guru.php';
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
            <h5 class="mb-0">Ubah Data Guru</h5>
        </div>

        <div class="card-body">

            <form id="formguru" method="post" enctype="multipart/form-data">
                <div class="row">
                    <?php foreach($data as $row) : ?>
                    <input type="hidden" name="id" value="<?= $row['id_guru'] ?>">
                    <input type="hidden" name="foto_lama" value="<?= $row['foto_guru'] ?>">
                    <div class="form-group col-md-6">
                        <label class="form-label">Nama Guru</label>
                        <input type="text" name="nama" value="<?= $row['nama_guru'] ?>" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label">TTL</label>
                        <input type="date" name="ttl" value="<?= $row['ttl_guru'] ?>" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="alamat" value="<?= $row['alamat_guru'] ?>" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label">WA</label>
                        <input type="text" name="wa" value="<?= $row['wa_guru'] ?>" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label">foto</label></label>
                        <input type="file" name="foto" id="fotoGuru" class="form-control" accept="image/*">
                        <?php if (!empty($row['foto_guru'])) : ?>
                            <small class="d-block mt-2">Foto saat ini:</small>
                            <img src="../assets/img/new/<?= $row['foto_guru'] ?>" alt="foto guru" height="70" id="previewFotoGuruEdit">
                        <?php else : ?>
                            <img src="" alt="preview foto guru" height="70" id="previewFotoGuruEdit" class="mt-2 d-none">
                        <?php endif; ?>
                    </div>
                     <div class="modal-footer mt-4">
                        <a href="../guru.php" class="me-3">
                            <button type="button" class="btn btn-secondary">Batal</button>
                        </a>
                        <button type="submit" name="submit" class="btn btn-primary me-3">Simpan</button>
                    </div>
                    <?php endforeach; ?>
                </div>
            </form>

        </div>
    </div>

</div>

<script>
    const fotoGuruInput = document.getElementById('fotoGuru');
    const previewFotoGuruEdit = document.getElementById('previewFotoGuruEdit');

    if (fotoGuruInput && previewFotoGuruEdit) {
        fotoGuruInput.addEventListener('change', function () {
            const file = this.files[0];

            if (!file) {
                if (!previewFotoGuruEdit.getAttribute('src')) {
                    previewFotoGuruEdit.classList.add('d-none');
                }
                return;
            }

            previewFotoGuruEdit.src = URL.createObjectURL(file);
            previewFotoGuruEdit.classList.remove('d-none');
        });
    }
</script>

<?php include '../includes/footer.php'; ?>
