<?php
include 'config.php';
include 'proses/helpers.php';
include 'proses/queries.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$title = "Absensi Guru";

$filters = [
    'date_start' => $_GET['date_start'] ?? '',
    'date_end' => $_GET['date_end'] ?? '',
    'guru_id' => $_GET['guru_id'] ?? '',
    'kelas_id' => $_GET['kelas_id'] ?? '',
    'jurusan_id' => $_GET['jurusan_id'] ?? '',
    'status' => $_GET['status'] ?? '',
    'search' => $_GET['search'] ?? ''
];

$limit = 100;
$offset = isset($_GET['page']) ? ((int)$_GET['page'] - 1) * $limit : 0;

$absensiGuru = getAbsensiGuru($filters, $limit, $offset);
$totalRecords = countRecords('absensi_guru', buildFilterQuery('absensi_guru', $filters), 
    "JOIN guru ON absensi_guru.guru_id = guru.id_guru JOIN kelas ON absensi_guru.kelas_id = kelas.id_kelas");

$stats = getStatusStats('absensi_guru', buildFilterQuery('absensi_guru', $filters));

$guruOptions = getGuruOptions();
$kelasOptions = getKelasOptions();
$jurusanOptions = getJurusanOptions();

$totalPages = ceil($totalRecords / $limit);
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
?>

<?php include 'includes/header.php'; ?>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-semibold mb-1">Absensi Guru</h4>
            <small><?= $stats['total'] ?? 0; ?> data absensi</small>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAbsensiGuru">
                <i class="bi bi-plus-lg"></i> Tambah Absensi
            </button>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card p-3 text-center">
                <h5 class="text-muted mb-2">Total</h5>
                <h3 class="text-primary"><?= $stats['total'] ?? 0; ?></h3>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card p-3 text-center">
                <h5 class="text-muted mb-2">Hadir</h5>
                <h3 class="text-success"><?= $stats['hadir'] ?? 0; ?></h3>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card p-3 text-center">
                <h5 class="text-muted mb-2">Izin</h5>
                <h3 class="text-info"><?= $stats['izin'] ?? 0; ?></h3>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card p-3 text-center">
                <h5 class="text-muted mb-2">Sakit/Alpha</h5>
                <h3 class="text-warning"><?= ($stats['sakit'] ?? 0) + ($stats['alpha'] ?? 0); ?></h3>
            </div>
        </div>
    </div>

    <div class="card p-4 mb-4">
        <h5 class="mb-3">Filter Data</h5>
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="date_start" class="form-control" value="<?= htmlspecialchars($filters['date_start']); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="date_end" class="form-control" value="<?= htmlspecialchars($filters['date_end']); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Guru</label>
                <select name="guru_id" class="form-select">
                    <option value="">-- Semua Guru --</option>
                    <?php foreach ($guruOptions as $guru): ?>
                        <option value="<?= $guru['id_guru']; ?>" <?= $filters['guru_id'] == $guru['id_guru'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($guru['nama_guru']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Kelas</label>
                <select name="kelas_id" class="form-select">
                    <option value="">-- Semua Kelas --</option>
                    <?php foreach ($kelasOptions as $kelas): ?>
                        <option value="<?= $kelas['id_kelas']; ?>" <?= $filters['kelas_id'] == $kelas['id_kelas'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($kelas['nama_kelas']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Jurusan</label>
                <select name="jurusan_id" class="form-select">
                    <option value="">-- Semua Jurusan --</option>
                    <?php foreach ($jurusanOptions as $jurusan): ?>
                        <option value="<?= $jurusan['id_jurusan']; ?>" <?= $filters['jurusan_id'] == $jurusan['id_jurusan'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($jurusan['nama_jurusan']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <option value="hadir" <?= $filters['status'] == 'hadir' ? 'selected' : ''; ?>>Hadir</option>
                    <option value="izin" <?= $filters['status'] == 'izin' ? 'selected' : ''; ?>>Izin</option>
                    <option value="sakit" <?= $filters['status'] == 'sakit' ? 'selected' : ''; ?>>Sakit</option>
                    <option value="alpha" <?= $filters['status'] == 'alpha' ? 'selected' : ''; ?>>Alpha</option>
                </select>
            </div>
            <div class="col-md-6 d-flex gap-2 align-items-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel"></i> Terapkan Filter
                </button>
                <a href="absensi_guru.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <div class="card p-4">
        <form id="bulkDeleteForm" method="POST" action="proses/hps_absensi_guru_banyak.php">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Data Absensi Guru</h5>
                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirmBulkDelete('.select-absensi')">
                    <i class="bi bi-trash"></i> Hapus Terpilih
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="form-check-input select-all" data-checkbox=".select-absensi"></th>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Guru</th>
                            <th>Kelas</th>
                            <th>Jurusan</th>
                            <th>Siswa</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = $offset + 1; foreach ($absensiGuru as $row): ?>
                            <tr>
                                <td><input type="checkbox" class="form-check-input select-absensi" name="selected_ids[]" value="<?= $row['id_absensi_guru']; ?>"></td>
                                <td><?= $no++; ?></td>
                                <td><?= formatDate($row['tgl']); ?></td>
                                <td><?= htmlspecialchars($row['nama_guru']); ?></td>
                                <td><?= htmlspecialchars($row['nama_kelas']); ?></td>
                                <td><?= htmlspecialchars($row['nama_jurusan']); ?></td>
                                <td><?= !empty($row['nama_siswa']) ? htmlspecialchars($row['nama_siswa']) : '-'; ?></td>
                                <td><?= getStatusBadge($row['status']); ?></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalAbsensiGuru">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <a href="proses/hps_absensi_guru.php?id=<?= $row['id_absensi_guru']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($totalRecords == 0): ?>
                <div class="alert alert-info mt-3">Tidak ada data absensi</div>
            <?php endif; ?>
        </form>

        <?php if ($totalPages > 1): ?>
            <nav aria-label="Page navigation" class="mt-3">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $currentPage ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?= $i; ?><?= !empty($filters['date_start']) ? '&date_start=' . urlencode($filters['date_start']) : ''; ?><?= !empty($filters['date_end']) ? '&date_end=' . urlencode($filters['date_end']) : ''; ?><?= !empty($filters['guru_id']) ? '&guru_id=' . urlencode($filters['guru_id']) : ''; ?><?= !empty($filters['kelas_id']) ? '&kelas_id=' . urlencode($filters['kelas_id']) : ''; ?><?= !empty($filters['jurusan_id']) ? '&jurusan_id=' . urlencode($filters['jurusan_id']) : ''; ?><?= !empty($filters['status']) ? '&status=' . urlencode($filters['status']) : ''; ?>">
                                <?= $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Add/Edit -->
<div class="modal fade" id="modalAbsensiGuru" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Absensi Guru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formAbsensiGuru" method="POST" action="proses/tbh_absensi_guru.php">
                <input type="hidden" name="id" id="absensiGuruId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" id="tglGuru" name="tgl" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Guru <span class="text-danger">*</span></label>
                        <select id="guruId" name="guru_id" class="form-select" required>
                            <option value="">-- Pilih Guru --</option>
                            <?php foreach ($guruOptions as $guru): ?>
                                <option value="<?= $guru['id_guru']; ?>"><?= htmlspecialchars($guru['nama_guru']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kelas <span class="text-danger">*</span></label>
                        <select id="kelasIdGuru" name="kelas_id" class="form-select" required onchange="loadSiswaForAbsensiGuru()">
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($kelasOptions as $kelas): ?>
                                <option value="<?= $kelas['id_kelas']; ?>"><?= htmlspecialchars($kelas['nama_kelas']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Siswa (opsional)</label>
                        <select id="siswaIdGuru" name="siswa_id" class="form-select">
                            <option value="">-- Opsional --</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusGuruHadir" value="hadir" required>
                                <label class="form-check-label" for="statusGuruHadir">Hadir</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusGuruIzin" value="izin">
                                <label class="form-check-label" for="statusGuruIzin">Izin</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusGuruSakit" value="sakit">
                                <label class="form-check-label" for="statusGuruSakit">Sakit</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusGuruAlpha" value="alpha">
                                <label class="form-check-label" for="statusGuruAlpha">Alpha</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function loadSiswaForAbsensiGuru() {
    const kelasId = document.getElementById('kelasIdGuru').value;
    const siswaSelect = document.getElementById('siswaIdGuru');
    
    if (!kelasId) {
        siswaSelect.innerHTML = '<option value="">-- Opsional --</option>';
        return;
    }
    
    fetch('proses/get_siswa_kelas.php?kelas_id=' + kelasId)
        .then(response => response.json())
        .then(data => {
            siswaSelect.innerHTML = '<option value="">-- Opsional --</option>';
            data.forEach(siswa => {
                const option = document.createElement('option');
                option.value = siswa.id_siswa;
                option.textContent = siswa.nama_siswa + ' (' + siswa.nis_siswa + ')';
                siswaSelect.appendChild(option);
            });
        });
}

function editAbsensiGuru(id) {
    // Reset form
    document.getElementById('formAbsensiGuru').reset();
    document.getElementById('formAbsensiGuru').action = 'proses/edit_absensi_guru.php';
    document.getElementById('absensiGuruId').value = id;
    
    // Fetch data (optional - can implement later)
    // For now, just open modal
}
</script>

<?php include 'includes/footer.php'; ?>
