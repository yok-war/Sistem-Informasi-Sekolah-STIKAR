<?php
include '../config.php';
$data = query("SELECT * FROM guru");

if (isset($_POST['submit'])) {
    if (tbh_guru($_POST) > 0) {
        echo "
                <script>
                    alert('data berhasil ditambah');
                    document.location.href = '../guru.php';
                </script>
            ";
    } else {
        echo "
                <script>
                    alert('data gagal ditambah');
                </script>
            ";
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="content container mt-4">

    <div class="card shadow-sm mt-5">
        <div class="card-header">
            <h5 class="mb-0"> Tambah Data Guru</h5>
        </div>

        <div class="card-body">

            <form id="formguru" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="form-label">Nama Guru</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label">TTL</label>
                        <input type="date" name="ttl" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="alamat" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label">WA</label>
                        <input type="text" name="wa" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label">foto</label></label>
                        <input type="file" name="foto" id="fotoGuru" class="form-control" accept="image/*" required>
                        <img src="" alt="preview foto guru" id="previewFotoGuru" class="mt-2 d-none" height="70">
                    </div>
                     <div class="modal-footer mt-4">
                        <a href="../guru.php" class="me-3">
                            <button type="button" class="btn btn-secondary">Batal</button>
                        </a>
                        <button type="submit" name="submit" class="btn btn-primary me-3">Simpan</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

</div>

<script>
    const fotoGuruInput = document.getElementById('fotoGuru');
    const previewFotoGuru = document.getElementById('previewFotoGuru');

    if (fotoGuruInput && previewFotoGuru) {
        fotoGuruInput.addEventListener('change', function () {
            const file = this.files[0];

            if (!file) {
                previewFotoGuru.src = '';
                previewFotoGuru.classList.add('d-none');
                return;
            }

            previewFotoGuru.src = URL.createObjectURL(file);
            previewFotoGuru.classList.remove('d-none');
        });
    }
</script>

<?php include '../includes/footer.php'; ?>
