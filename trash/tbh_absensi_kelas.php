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

$statusOptions = [
    'hadir' => 'Hadir',
    'sakit' => 'Sakit',
    'izin' => 'Izin',
    'alpha' => 'Alpha',
    'terlambat' => 'Terlambat'
];

$jurusanOptions = query("SELECT * FROM jurusan ORDER BY nama_jurusan ASC");
$guruOptions = query("SELECT * FROM guru ORDER BY nama_guru ASC");
$selectedJurusan = $_GET['jurusan'] ?? ($_POST['jurusan'] ?? 'semua');
$selectedKelas = $_GET['kelas'] ?? ($_POST['kelas'] ?? '');
$kelasOptions = get_kelas_with_jurusan($selectedJurusan);
$siswaKelas = !empty($selectedKelas) ? get_siswa_by_kelas($selectedKelas) : [];
$pesanHasil = null;

if (isset($_POST['submit_massal'])) {
    $selectedJurusan = $_POST['jurusan'] ?? 'semua';
    $selectedKelas = $_POST['kelas'] ?? '';
    $kelasOptions = get_kelas_with_jurusan($selectedJurusan);
    $siswaKelas = !empty($selectedKelas) ? get_siswa_by_kelas($selectedKelas) : [];

    $hasil = tbh_absensi_massal($_POST);
    $pesanHasil = $hasil;

    if ($hasil['inserted'] > 0 && $hasil['skipped'] === 0) {
        echo "
            <script>
                alert('absensi massal berhasil disimpan untuk " . $hasil['inserted'] . " siswa');
                document.location.href = '../absensi.php?tanggal=" . $_POST['tanggal'] . "&kelas=" . $selectedKelas . "';
            </script>
        ";
        exit;
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="content container mt-4">
    <div class="card shadow-sm mt-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="mb-0">Absensi Massal per Kelas</h5>
                    <small>Pilih jurusan dan kelas, lalu isi kehadiran semua siswa sekaligus.</small>
                </div>
                <a href="../absensi.php" class="btn btn-outline-secondary btn-sm">Kembali</a>
            </div>
        </div>

        <div class="card-body">
            <form method="get" class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Jurusan</label>
                    <select name="jurusan" class="form-select">
                        <option value="semua">Semua Jurusan</option>
                        <?php foreach ($jurusanOptions as $jurusan) : ?>
                            <option value="<?= $jurusan['id_jurusan'] ?>" <?= $selectedJurusan == $jurusan['id_jurusan'] ? 'selected' : '' ?>>
                                <?= $jurusan['nama_jurusan'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Kelas</label>
                    <select name="kelas" class="form-select">
                        <option value="">Pilih Kelas</option>
                        <?php foreach ($kelasOptions as $kelas) : ?>
                            <option value="<?= $kelas['id_kelas'] ?>" <?= $selectedKelas == $kelas['id_kelas'] ? 'selected' : '' ?>>
                                <?= $kelas['nama_kelas'] ?><?= !empty($kelas['nama_jurusan']) ? ' - ' . $kelas['nama_jurusan'] : '' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i> Tampilkan Siswa
                    </button>
                    <a href="tbh_absensi_kelas.php" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>

            <?php if ($pesanHasil && ($pesanHasil['inserted'] > 0 || $pesanHasil['skipped'] > 0)) : ?>
                <div class="alert alert-info">
                    <?= $pesanHasil['inserted'] ?> data berhasil disimpan, <?= $pesanHasil['skipped'] ?> data dilewati karena sudah ada atau gagal diproses.
                </div>
            <?php endif; ?>

            <?php if (!empty($selectedKelas) && count($siswaKelas) > 0) : ?>
                <form method="post">
                    <input type="hidden" name="jurusan" value="<?= $selectedJurusan ?>">
                    <input type="hidden" name="kelas" value="<?= $selectedKelas ?>">

                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Guru</label>
                            <select name="guru" class="form-select">
                                <option value="">Pilih Guru</option>
                                <?php foreach ($guruOptions as $guru) : ?>
                                    <option value="<?= $guru['id_guru'] ?>"><?= $guru['nama_guru'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Semester</label>
                            <input type="text" name="semester" class="form-control" placeholder="Ganjil">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Tahun Ajaran</label>
                            <input type="text" name="tahun_ajaran" class="form-control" placeholder="2025/2026">
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="is_verified" id="is_verified" checked>
                                <label class="form-check-label" for="is_verified">
                                    Verifikasi
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    <th>Jurusan</th>
                                    <th>Kelas</th>
                                    <th>Status</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($siswaKelas as $index => $siswa) : ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= $siswa['nis_siswa'] ?></td>
                                        <td><?= $siswa['nama_siswa'] ?></td>
                                        <td><?= $siswa['nama_jurusan'] ?: '-' ?></td>
                                        <td><?= $siswa['nama_kelas'] ?: '-' ?></td>
                                        <td>
                                            <select name="status[<?= $siswa['id_siswa'] ?>]" class="form-select form-select-sm">
                                                <?php foreach ($statusOptions as $value => $label) : ?>
                                                    <option value="<?= $value ?>" <?= $value === 'hadir' ? 'selected' : '' ?>><?= $label ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="time" name="jam_masuk[<?= $siswa['id_siswa'] ?>]" class="form-control form-control-sm">
                                        </td>
                                        <td>
                                            <input type="time" name="jam_keluar[<?= $siswa['id_siswa'] ?>]" class="form-control form-control-sm">
                                        </td>
                                        <td>
                                            <input type="text" name="keterangan[<?= $siswa['id_siswa'] ?>]" class="form-control form-control-sm" placeholder="Opsional">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer mt-4">
                        <a href="../absensi.php" class="me-3">
                            <button type="button" class="btn btn-secondary">Batal</button>
                        </a>
                        <button type="submit" name="submit_massal" class="btn btn-primary me-3">Simpan Semua</button>
                    </div>
                </form>
            <?php elseif (!empty($selectedKelas)) : ?>
                <div class="alert alert-warning mb-0">
                    Tidak ada siswa pada kelas yang dipilih.
                </div>
            <?php else : ?>
                <div class="alert alert-secondary mb-0">
                    Pilih jurusan dan kelas terlebih dahulu untuk menampilkan daftar siswa.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
