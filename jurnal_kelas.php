<?php
include 'config.php';
include 'proses/helpers.php';
include 'proses/queries.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$title = "Jurnal Kelas";

$filters = [
    'date_start' => $_GET['date_start'] ?? '',
    'date_end' => $_GET['date_end'] ?? '',
    'kelas_id' => $_GET['kelas_id'] ?? '',
    'search' => $_GET['search'] ?? ''
];

$limit = 100;
$offset = isset($_GET['page']) ? ((int)$_GET['page'] - 1) * $limit : 0;

$jurnalKelas = getJurnalKelas($filters, $limit, $offset);
$totalRecords = countRecords('jurnal_kelas', buildFilterQuery('jurnal_kelas', $filters), 
    "JOIN kelas ON jurnal_kelas.kelas_id = kelas.id_kelas");

$kelasOptions = getKelasOptions();

$totalPages = ceil($totalRecords / $limit);
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
?>

<?php include 'includes/header.php'; ?>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-semibold mb-1">Jurnal Kelas</h4>
            <small><?= $totalRecords; ?> data jurnal</small>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalJurnalKelas" onclick="resetJurnalKelasForm()">
                <i class="bi bi-plus-lg"></i> Tambah Jurnal
            </button>
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
            <div class="col-md-6">
                <label class="form-label">Cari Keterangan</label>
                <input type="text" name="search" class="form-control" placeholder="Masukkan kata kunci" value="<?= htmlspecialchars($filters['search']); ?>">
            </div>
            <div class="col-md-6 d-flex gap-2 align-items-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel"></i> Terapkan Filter
                </button>
                <a href="jurnal_kelas.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <div class="card p-4">
        <form id="bulkDeleteForm" method="POST" action="proses/hps_jurnal_kelas_banyak.php">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Data Jurnal Kelas</h5>
                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirmBulkDelete('.select-jurnal')">
                    <i class="bi bi-trash"></i> Hapus Terpilih
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="form-check-input select-all" data-checkbox=".select-jurnal"></th>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kelas</th>
                            <th>Keterangan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = $offset + 1; foreach ($jurnalKelas as $row): ?>
                            <tr>
                                <td><input type="checkbox" class="form-check-input select-jurnal" name="selected_ids[]" value="<?= $row['id_jurnal_kelas']; ?>"></td>
                                <td><?= $no++; ?></td>
                                <td><?= formatDate($row['tgl']); ?></td>
                                <td><?= htmlspecialchars($row['nama_kelas']); ?></td>
                                <td>
                                    <div style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($row['keterangan']); ?>">
                                        <?= truncateText($row['keterangan'], 50); ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalJurnalKelas" onclick="editJurnalKelas(<?= $row['id_jurnal_kelas']; ?>)">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <a href="proses/hps_jurnal_kelas.php?id=<?= $row['id_jurnal_kelas']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($totalRecords == 0): ?>
                <div class="alert alert-info mt-3">Tidak ada data jurnal</div>
            <?php endif; ?>
        </form>

        <?php if ($totalPages > 1): ?>
            <nav aria-label="Page navigation" class="mt-3">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $currentPage ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?= $i; ?>">
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
<div class="modal fade" id="modalJurnalKelas" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalJurnalKelasTitle">Tambah Jurnal Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formJurnalKelas" method="POST" action="proses/tbh_jurnal_kelas.php">
                <input type="hidden" name="id" id="jurnalKelasId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" id="tglJurnalKelas" name="tgl" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kelas <span class="text-danger">*</span></label>
                        <select id="kelasIdJurnalKelas" name="kelas_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($kelasOptions as $kelas): ?>
                                <option value="<?= $kelas['id_kelas']; ?>"><?= htmlspecialchars($kelas['nama_kelas']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan <span class="text-danger">*</span></label>
                        <textarea id="keteranganJurnalKelas" name="keterangan" class="form-control" rows="5" placeholder="Masukkan keterangan kegiatan/pembelajaran kelas..." required></textarea>
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
</div>

<script>
function resetJurnalKelasForm() {
    document.getElementById('formJurnalKelas').reset();
    document.getElementById('modalJurnalKelasTitle').textContent = 'Tambah Jurnal Kelas';
    document.getElementById('formJurnalKelas').action = 'proses/tbh_jurnal_kelas.php';
    document.getElementById('jurnalKelasId').value = '';
}

function editJurnalKelas(id) {
    // Reset form
    document.getElementById('formJurnalKelas').reset();
    document.getElementById('modalJurnalKelasTitle').textContent = 'Edit Jurnal Kelas';
    document.getElementById('formJurnalKelas').action = 'proses/edit_jurnal_kelas.php';
    document.getElementById('jurnalKelasId').value = id;
    
    // Fetch data
    fetch('proses/get_jurnal_kelas.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            console.log('Jurnal kelas data:', data);
            
            // Set form values
            document.getElementById('tglJurnalKelas').value = data.tgl;
            document.getElementById('kelasIdJurnalKelas').value = data.kelas_id;
            document.getElementById('keteranganJurnalKelas').value = data.keterangan;
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat data jurnal');
        });
}
</script>

<?php include 'includes/footer.php'; ?>
