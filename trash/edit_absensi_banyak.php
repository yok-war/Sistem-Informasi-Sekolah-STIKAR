<?php
include '../config.php';

if (!absensi_table_exists()) {
    echo "<script>
        alert('tabel absensi belum ada');
        document.location.href = '../absensi.php';
    </script>";
    exit;
}

$selectedIds = normalize_ids($_POST['selected_ids'] ?? []);
$guru = query("SELECT * FROM guru ORDER BY nama_guru ASC");
$statusOptions = [
    'hadir' => 'Hadir',
    'sakit' => 'Sakit',
    'izin' => 'Izin',
    'alpha' => 'Alpha',
    'terlambat' => 'Terlambat'
];

if (empty($selectedIds)) {
    echo "<script>
        alert('pilih minimal satu absensi');
        document.location.href = '../absensi.php';
    </script>";
    exit;
}

if (isset($_POST['submit_bulk'])) {
    $selectedIds = normalize_ids($_POST['selected_ids'] ?? []);

    if (edit_absensi_many($selectedIds, $_POST) > 0) {
        echo "<script>
            alert('data absensi terpilih berhasil diperbarui');
            document.location.href = '../absensi.php';
        </script>";
    } else {
        echo "<script>
            alert('tidak ada data absensi yang berubah');
        </script>";
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="content container mt-4">
    <div class="card shadow-sm mt-4">
        <div class="card-header">
            <h5 class="mb-0">Edit Massal Absensi</h5>
        </div>
        <div class="card-body">
            <p class="text-secondary mb-4"><?= count($selectedIds) ?> data absensi dipilih. Isi field yang ingin diterapkan ke semua data terpilih.</p>

            <form method="post">
                <?php foreach ($selectedIds as $id) : ?>
                    <input type="hidden" name="selected_ids[]" value="<?= $id ?>">
                <?php endforeach; ?>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Jangan Ubah</option>
                            <?php foreach ($statusOptions as $value => $label) : ?>
                                <option value="<?= $value ?>"><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Guru</label>
                        <select name="guru" class="form-select">
                            <option value="__nochange__">Jangan Ubah</option>
                            <option value="__clear__">Kosongkan Guru</option>
                            <?php foreach ($guru as $row) : ?>
                                <option value="<?= $row['id_guru'] ?>"><?= $row['nama_guru'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Semester</label>
                        <input type="text" name="semester" class="form-control" placeholder="Kosongkan jika tidak diubah">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" class="form-control" placeholder="Kosongkan jika tidak diubah">
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="set_verified" id="set_verified" value="1">
                            <label class="form-check-label" for="set_verified">
                                Atur status verifikasi
                            </label>
                        </div>
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_verified" id="is_verified" value="1">
                            <label class="form-check-label" for="is_verified">
                                Jadikan terverifikasi
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer mt-4">
                        <a href="../absensi.php" class="me-3">
                            <button type="button" class="btn btn-secondary">Batal</button>
                        </a>
                        <button type="submit" name="submit_bulk" class="btn btn-primary me-3">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
