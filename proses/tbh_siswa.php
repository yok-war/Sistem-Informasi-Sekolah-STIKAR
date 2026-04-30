<?php
include '../config.php';
$kelas = mysqli_query($conn, "SELECT * FROM kelas");
if (isset($_POST['submit'])) {
    if (tbh_siswa($_POST) > 0) {
        echo "
                <script>
                    alert('data berhasil ditambah');
                    document.location.href = '../siswa.php';
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

    <div class="card shadow-sm mt-4">
        <div class="card-header">
            <h5 class="mb-0"> Tambah Data Siswa</h5>
        </div>

        <div class="card-body">

            <form id="formSiswa" method="post" enctype="multipart/form-data">

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">NIS</label>
                        <input type="text" name="nis" class="form-control" pattern="[0-9]{4}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">NISN</label>
                        <input type="text" name="nisn" class="form-control" pattern="[0-9]{10}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">TTL</label>
                        <input type="date" name="ttl" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="alamat" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">WA</label>
                        <input type="text" name="wa" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Foto</label>
                        <input type="file" name="foto" id="fotoSiswa" class="form-control" accept="image/*">
                        <img src="" alt="preview foto siswa" id="previewFotoSiswa" class="mt-2 d-none" height="70">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Kelas</label>
                        <select name="kelas" id="kelas" class="form-select">
                            <?php while ($row = mysqli_fetch_assoc($kelas)) { ?>
                                <option value="<?= $row['id_kelas'] ?>">
                                    <?= $row['nama_kelas'] ?>
                                </option>
                            <?php } ?>
                        </select>
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

</div>

<script>
    const fotoSiswaInput = document.getElementById('fotoSiswa');
    const previewFotoSiswa = document.getElementById('previewFotoSiswa');

    if (fotoSiswaInput && previewFotoSiswa) {
        fotoSiswaInput.addEventListener('change', function () {
            const file = this.files[0];

            if (!file) {
                previewFotoSiswa.src = '';
                previewFotoSiswa.classList.add('d-none');
                return;
            }

            previewFotoSiswa.src = URL.createObjectURL(file);
            previewFotoSiswa.classList.remove('d-none');
        });
    }
</script>

<?php include '../includes/footer.php'; ?>
