<?php
include '../config.php';
if (isset($_POST['submit'])) {
    if (tbh_jurusan($_POST) > 0) {
        echo "
                <script>
                    alert('data berhasil ditambah');
                    document.location.href = '../jurusan.php';
                </script>
            ";
    } else {
        echo "
                <script>
                    alert('data gagal ditambah');
                    document.location.href = '../jurusan.php';
                </script>
            ";
    }
}
?>
<?php include '../includes/header.php' ?>
<div class="content">
    
    <div class="card shadow-sm mt-4">
        <div class="card-header">
            <h5 class="mb-0"> Tambah Data Jursan</h5>
        </div>

        <div class="card-body">

            <form id="formSiswa" method="post" enctype="multipart/form-data">

                <div class="row g-3 d-flex justify-content-center">

                    <div class="col-md-8">
                        <label class="form-label">Nama Jurusan</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    <div class="modal-footer mt-4">
                        <a href="../siswa.php" class="me-3">
                            <button type="button" class="btn btn-secondary">Batal</button>
                        </a>
                        <button type="submit" name="submit" class="btn btn-primary me-3">Simpan</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
<?php include '../includes/footer.php' ?>
