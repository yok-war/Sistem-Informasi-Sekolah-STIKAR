<?php
include '../config.php';

if (!absensi_table_exists()) {
    echo "
        <script>
            alert('tabel absensi belum ada, jalankan database/absensi.sql terlebih dahulu');
            document.location.href = '../absensi.php';
        </script>
    ";
    exit;
}

$siswa = query("SELECT siswa.id_siswa, siswa.nis_siswa, siswa.nama_siswa, kelas.nama_kelas
                FROM siswa
                LEFT JOIN kelas ON siswa.kelas_id = kelas.id_kelas
                ORDER BY siswa.nama_siswa ASC");
$guru = query("SELECT * FROM guru ORDER BY nama_guru ASC");
$statusOptions = [
    'hadir' => 'Hadir',
    'sakit' => 'Sakit',
    'izin' => 'Izin',
    'alpha' => 'Alpha',
    'terlambat' => 'Terlambat'
];

if (isset($_POST['submit'])) {
    if (tbh_absensi($_POST) > 0) {
        echo "
            <script>
                alert('data absensi berhasil ditambah');
                document.location.href = '../absensi.php';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('data absensi gagal ditambah');
            </script>
        ";
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="content container mt-4">
    <div class="card shadow-sm mt-4">
        <div class="card-header">
            <h5 class="mb-0">Tambah Data Absensi</h5>
        </div>

        <div class="card-body">
            <form method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Siswa</label>
                        <select name="siswa" class="form-select" required>
                            <option value="">Pilih Siswa</option>
                            <?php foreach ($siswa as $row) : ?>
                                <option value="<?= $row['id_siswa'] ?>">
                                    <?= $row['nis_siswa'] ?> - <?= $row['nama_siswa'] ?> (<?= $row['nama_kelas'] ?: '-' ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <?php foreach ($statusOptions as $value => $label) : ?>
                                <option value="<?= $value ?>"><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Jam Masuk</label>
                        <input type="time" name="jam_masuk" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Jam Keluar</label>
                        <input type="time" name="jam_keluar" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Guru</label>
                        <select name="guru" class="form-select">
                            <option value="">Pilih Guru</option>
                            <?php foreach ($guru as $row) : ?>
                                <option value="<?= $row['id_guru'] ?>"><?= $row['nama_guru'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Semester</label>
                        <input type="text" name="semester" class="form-control" placeholder="Ganjil / Genap">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" class="form-control" placeholder="2025/2026">
                    </div>

                    <div class="col-12">
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="is_verified" id="is_verified">
                            <label class="form-check-label" for="is_verified">
                                Tandai sebagai data terverifikasi
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Tambahkan catatan jika diperlukan"></textarea>
                    </div>

                    <div class="modal-footer mt-4">
                        <a href="../absensi.php" class="me-3">
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
