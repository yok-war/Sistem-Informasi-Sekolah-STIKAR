<?php
include 'config.php';
$title = "Absensi";
$tblName = "dataAbsensi";

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$statusOptions = [
    'hadir' => 'Hadir',
    'sakit' => 'Sakit',
    'izin' => 'Izin',
    'alpha' => 'Alpha',
    'terlambat' => 'Terlambat'
];

function statusBadgeClass($status)
{
    $map = [
        'hadir' => 'success',
        'sakit' => 'warning text-dark',
        'izin' => 'info text-dark',
        'alpha' => 'danger',
        'terlambat' => 'secondary'
    ];

    return $map[$status] ?? 'secondary';
}

$selectedDate = $_GET['tanggal'] ?? date('Y-m-d');
$selectedJurusan = $_GET['jurusan'] ?? 'semua';
$selectedKelas = $_GET['kelas'] ?? 'semua';
$selectedStatus = $_GET['status'] ?? 'semua';
$jurusanOptions = query("SELECT * FROM jurusan ORDER BY nama_jurusan ASC");
$kelasOptions = query("SELECT * FROM kelas ORDER BY nama_kelas ASC");

$summary = [
    'hadir' => 0,
    'sakit' => 0,
    'izin' => 0,
    'alpha' => 0,
    'terlambat' => 0
];

$rekapKelas = [];
$absensi = [];
$tableExists = absensi_table_exists();

if ($tableExists) {
    $absensi = get_absensi([
        'tanggal' => $selectedDate,
        'jurusan' => $selectedJurusan,
        'kelas' => $selectedKelas,
        'status' => $selectedStatus
    ]);

    foreach ($absensi as $row) {
        if (isset($summary[$row['status']])) {
            $summary[$row['status']]++;
        }

        $kelasNama = $row['nama_kelas'] ?: '-';
        if (!isset($rekapKelas[$kelasNama])) {
            $rekapKelas[$kelasNama] = [
                'total' => 0,
                'hadir' => 0
            ];
        }

        $rekapKelas[$kelasNama]['total']++;
        if ($row['status'] === 'hadir') {
            $rekapKelas[$kelasNama]['hadir']++;
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="content">
    <div class="card card-stat p-4 absensi-hero">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <span class="absensi-kicker">Modul Absensi</span>
                <h3 class="mt-2 mb-2">Kelola Absensi Siswa</h3>
                <p class="mb-0 text-secondary">
                    Pantau kehadiran siswa, filter berdasarkan jurusan dan kelas, lalu kelola data absensi dari satu halaman.
                </p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="proses/tbh_absensi_kelas.php" class="btn btn-outline-primary">
                    <i class="bi bi-people me-1"></i> Absensi per Kelas
                </a>
                <a href="proses/tbh_absensi.php" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Absensi
                </a>
            </div>
        </div>
    </div>

    <?php if (!$tableExists) : ?>
        <div class="alert alert-warning mt-4">
            Tabel <strong>absensi</strong> belum ada di database. Jalankan file SQL di
            <code>database/absensi.sql</code> dulu lewat phpMyAdmin, lalu refresh halaman ini.
        </div>
    <?php else : ?>
        <div class="row g-4 mt-1">
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card card-stat p-3 absensi-summary hadir">
                    <small>Hadir</small>
                    <h3><?= $summary['hadir'] ?></h3>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card card-stat p-3 absensi-summary sakit">
                    <small>Sakit</small>
                    <h3><?= $summary['sakit'] ?></h3>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card card-stat p-3 absensi-summary izin">
                    <small>Izin</small>
                    <h3><?= $summary['izin'] ?></h3>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card card-stat p-3 absensi-summary alpha">
                    <small>Alpha</small>
                    <h3><?= $summary['alpha'] ?></h3>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-12 col-xl-8">
                <div class="card card-stat p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <div>
                            <h5 class="mb-1">Filter & Tabel Absensi</h5>
                            <small class="text-secondary">Gunakan filter untuk melihat absensi sesuai tanggal, jurusan, kelas, dan status.</small>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" form="bulkAbsensiForm" formaction="proses/edit_absensi_banyak.php" formmethod="post" class="btn btn-outline-primary btn-sm" onclick="return requireSelection('.select-absensi', 'Pilih minimal satu data absensi untuk edit massal.')">
                                <i class="bi bi-sliders"></i> Edit Massal
                            </button>
                            <button type="submit" form="bulkAbsensiForm" formaction="proses/hps_absensi_banyak.php" formmethod="post" class="btn btn-outline-danger btn-sm" onclick="return confirmBulkDelete('.select-absensi')">
                                <i class="bi bi-trash"></i> Hapus Terpilih
                            </button>
                        </div>
                    </div>

                    <form method="get" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="<?= $selectedDate ?>">
                        </div>
                        <div class="col-md-3">
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
                        <div class="col-md-3">
                            <label class="form-label">Kelas</label>
                            <select name="kelas" class="form-select">
                                <option value="semua">Semua Kelas</option>
                                <?php foreach ($kelasOptions as $kelas) : ?>
                                    <option value="<?= $kelas['id_kelas'] ?>" <?= $selectedKelas == $kelas['id_kelas'] ? 'selected' : '' ?>>
                                        <?= $kelas['nama_kelas'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="semua">Semua Status</option>
                                <?php foreach ($statusOptions as $value => $label) : ?>
                                    <option value="<?= $value ?>" <?= $selectedStatus === $value ? 'selected' : '' ?>>
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i> Tampilkan
                            </button>
                            <a href="absensi.php" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i> Reset
                            </a>
                        </div>
                    </form>

                    <form id="bulkAbsensiForm" method="post">
                    <div class="table-responsive mt-4">
                        <table id="<?= $tblName ?>" class="table align-middle">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="form-check-input select-all" data-checkbox=".select-absensi"></th>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    <th>Jurusan</th>
                                    <th>Kelas</th>
                                    <th>Status</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Guru</th>
                                    <th>Keterangan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($absensi) > 0) : ?>
                                    <?php foreach ($absensi as $index => $row) : ?>
                                        <tr>
                                            <td><input type="checkbox" class="form-check-input select-absensi" name="selected_ids[]" value="<?= $row['id_absensi'] ?>"></td>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= $row['tanggal'] ?></td>
                                            <td><?= $row['nis_siswa'] ?></td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="absensi-avatar">
                                                        <?= strtoupper(substr($row['nama_siswa'], 0, 1)) ?>
                                                    </div>
                                                    <span><?= $row['nama_siswa'] ?></span>
                                                </div>
                                            </td>
                                            <td><?= $row['nama_jurusan'] ?: '-' ?></td>
                                            <td><?= $row['nama_kelas'] ?: '-' ?></td>
                                            <td>
                                                <span class="badge bg-<?= statusBadgeClass($row['status']) ?>">
                                                    <?= $statusOptions[$row['status']] ?? ucfirst($row['status']) ?>
                                                </span>
                                            </td>
                                            <td><?= $row['jam_masuk'] ?: '-' ?></td>
                                            <td><?= $row['jam_keluar'] ?: '-' ?></td>
                                            <td><?= $row['nama_guru'] ?: '-' ?></td>
                                            <td><?= $row['keterangan'] ?: '-' ?></td>
                                            <td class="text-center action-btn">
                                                <a href="proses/edit_absensi.php?id=<?= $row['id_absensi'] ?>">
                                                    <i class="bi bi-pencil text-warning me-2"></i>
                                                </a>
                                                <a href="proses/hps_absensi.php?id=<?= $row['id_absensi'] ?>" onclick="return confirm('Yakin ingin menghapus absensi ini?')">
                                                    <i class="bi bi-trash text-danger"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="13" class="text-center py-4">Belum ada data absensi untuk filter yang dipilih.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    </form>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="card card-stat p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Rekap per Kelas</h5>
                        <span class="badge text-bg-light"><?= count($rekapKelas) ?> kelas</span>
                    </div>
                    <?php if (count($rekapKelas) > 0) : ?>
                        <?php foreach ($rekapKelas as $kelasNama => $rekap) :
                            $persentase = $rekap['total'] > 0 ? round(($rekap['hadir'] / $rekap['total']) * 100) : 0;
                        ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span><?= $kelasNama ?></span>
                                    <strong><?= $persentase ?>%</strong>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: <?= $persentase ?>%"></div>
                                </div>
                                <small class="text-secondary"><?= $rekap['hadir'] ?> dari <?= $rekap['total'] ?> siswa hadir</small>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p class="mb-0 text-secondary">Belum ada rekap kelas untuk filter yang dipilih.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
