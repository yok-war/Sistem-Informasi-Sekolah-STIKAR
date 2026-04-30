<?php
include 'config.php';
include 'proses/helpers.php';
include 'proses/queries.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$title = "Absensi Kelas";

// Get filters from GET
$filters = [
    'date_start' => $_GET['date_start'] ?? '',
    'date_end' => $_GET['date_end'] ?? '',
    'kelas_id' => $_GET['kelas_id'] ?? '',
    'jurusan_id' => $_GET['jurusan_id'] ?? '',
    'siswa_id' => $_GET['siswa_id'] ?? '',
    'status' => $_GET['status'] ?? '',
    'search' => $_GET['search'] ?? ''
];

$limit = 100;
$offset = isset($_GET['page']) ? ((int)$_GET['page'] - 1) * $limit : 0;

// Get data
$absensiKelas = getAbsensiKelas($filters, $limit, $offset);
$totalRecords = countRecords('absensi_kelas', buildFilterQuery('absensi_kelas', $filters), 
    "JOIN siswa ON absensi_kelas.siswa_id = siswa.id_siswa JOIN kelas ON absensi_kelas.kelas_id = kelas.id_kelas");

// Get statistics
$stats = getStatusStats('absensi_kelas', buildFilterQuery('absensi_kelas', $filters));

// Get options for dropdowns
$kelasOptions = getKelasOptions();
$jurusanOptions = getJurusanOptions();
$siswaOptions = !empty($filters['kelas_id']) ? getSiswaByKelas($filters['kelas_id']) : [];

$totalPages = ceil($totalRecords / $limit);
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
?>

<?php include 'includes/header.php'; ?>

<div class="content">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-semibold mb-1">Absensi Kelas</h4>
            <small><?= $stats['total'] ?? 0; ?> data absensi</small>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAbsensiKelas">
                <i class="bi bi-plus-lg"></i> Tambah Absensi
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
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

    <!-- Filter Section -->
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
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <option value="hadir" <?= $filters['status'] == 'hadir' ? 'selected' : ''; ?>>Hadir</option>
                    <option value="izin" <?= $filters['status'] == 'izin' ? 'selected' : ''; ?>>Izin</option>
                    <option value="sakit" <?= $filters['status'] == 'sakit' ? 'selected' : ''; ?>>Sakit</option>
                    <option value="alpha" <?= $filters['status'] == 'alpha' ? 'selected' : ''; ?>>Alpha</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Siswa (opsional)</label>
                <select name="siswa_id" class="form-select">
                    <option value="">-- Semua Siswa --</option>
                    <?php foreach ($siswaOptions as $siswa): ?>
                        <option value="<?= $siswa['id_siswa']; ?>" <?= $filters['siswa_id'] == $siswa['id_siswa'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($siswa['nama_siswa']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Cari Nama/NISN Siswa</label>
                <input type="text" name="search" class="form-control" placeholder="Masukkan nama atau NISN" value="<?= htmlspecialchars($filters['search']); ?>">
            </div>
            <div class="col-md-6 d-flex gap-2 align-items-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel"></i> Terapkan Filter
                </button>
                <a href="absensi_kelas.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="card p-4">
        <form id="bulkDeleteForm" method="POST" action="proses/hps_absensi_kelas_banyak.php">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Data Absensi Kelas</h5>
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
                            <th>Kelas</th>
                            <th>Jurusan</th>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = $offset + 1; foreach ($absensiKelas as $row): ?>
                            <tr>
                                <td><input type="checkbox" class="form-check-input select-absensi" name="selected_ids[]" value="<?= $row['id_absensi_kelas']; ?>"></td>
                                <td><?= $no++; ?></td>
                                <td><?= formatDate($row['tgl']); ?></td>
                                <td><?= htmlspecialchars($row['nama_kelas']); ?></td>
                                <td><?= htmlspecialchars($row['nama_jurusan']); ?></td>
                                <td><?= htmlspecialchars($row['nis_siswa']); ?></td>
                                <td><?= htmlspecialchars($row['nama_siswa']); ?></td>
                                <td><?= getStatusBadge($row['status']); ?></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalAbsensiKelas" onclick="editAbsensi(<?= $row['id_absensi_kelas']; ?>)">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <a href="proses/hps_absensi_kelas.php?id=<?= $row['id_absensi_kelas']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
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

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav aria-label="Page navigation" class="mt-3">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $currentPage ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?= $i; ?><?= !empty($filters['date_start']) ? '&date_start=' . urlencode($filters['date_start']) : ''; ?><?= !empty($filters['date_end']) ? '&date_end=' . urlencode($filters['date_end']) : ''; ?><?= !empty($filters['kelas_id']) ? '&kelas_id=' . urlencode($filters['kelas_id']) : ''; ?><?= !empty($filters['jurusan_id']) ? '&jurusan_id=' . urlencode($filters['jurusan_id']) : ''; ?><?= !empty($filters['status']) ? '&status=' . urlencode($filters['status']) : ''; ?><?= !empty($filters['search']) ? '&search=' . urlencode($filters['search']) : ''; ?>">
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
<div class="modal fade" id="modalAbsensiKelas" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Absensi Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formAbsensi" method="POST" action="proses/tbh_absensi_kelas.php">
                <input type="hidden" name="id" id="absensiId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" id="tgl" name="tgl" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kelas <span class="text-danger">*</span></label>
                        <select id="kelasId" name="kelas_id" class="form-select" required onchange="loadSiswa()">
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($kelasOptions as $kelas): ?>
                                <option value="<?= $kelas['id_kelas']; ?>"><?= htmlspecialchars($kelas['nama_kelas']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Siswa <span class="text-danger">*</span></label>
                        <select id="siswaId" name="siswa_id" class="form-select" required>
                            <option value="">-- Pilih Siswa --</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusHadir" value="hadir" required>
                                <label class="form-check-label" for="statusHadir">Hadir</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusIzin" value="izin">
                                <label class="form-check-label" for="statusIzin">Izin</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusSakit" value="sakit">
                                <label class="form-check-label" for="statusSakit">Sakit</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusAlpha" value="alpha">
                                <label class="form-check-label" for="statusAlpha">Alpha</label>
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
function loadSiswa() {
    const kelasId = document.getElementById('kelasId').value;
    const siswaSelect = document.getElementById('siswaId');
    
    if (!kelasId) {
        siswaSelect.innerHTML = '<option value="">-- Pilih Siswa --</option>';
        return;
    }
    
    fetch('proses/get_siswa_kelas.php?kelas_id=' + kelasId)
        .then(response => response.json())
        .then(data => {
            siswaSelect.innerHTML = '<option value="">-- Pilih Siswa --</option>';
            data.forEach(siswa => {
                const option = document.createElement('option');
                option.value = siswa.id_siswa;
                option.textContent = siswa.nama_siswa + ' (' + siswa.nis_siswa + ')';
                siswaSelect.appendChild(option);
            });
        });
}

function editAbsensi(id) {
    // Reset form
    document.getElementById('formAbsensi').reset();
    document.getElementById('modalTitle').textContent = 'Edit Absensi Kelas';
    document.getElementById('formAbsensi').action = 'proses/edit_absensi_kelas.php';
    document.getElementById('absensiId').value = id;
    
    // Fetch data (optional - can implement later)
    // For now, just open modal
}
</script>

<?php include 'includes/footer.php'; ?>
