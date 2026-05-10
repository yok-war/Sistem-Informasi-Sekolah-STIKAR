<?php
include 'config.php';
include 'proses/helpers.php';
include 'proses/queries.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$title = "Absensi Cepat Kelas";

// Get options
$kelasOptions = getKelasOptions();
$siswaOptions = [];

// Get selected data from GET
$selectedDate = $_GET['tgl'] ?? date('Y-m-d');
$selectedKelas = $_GET['kelas_id'] ?? '';

if (!empty($selectedKelas)) {
    $siswaOptions = getSiswaByKelas($selectedKelas);
}

$message = $_SESSION['flash_message'] ?? '';
unset($_SESSION['flash_message']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_quick_absensi'])) {
    $tgl = htmlspecialchars($_POST['tgl']);
    $kelas_id = (int)$_POST['kelas_id'];
    $inserted = 0;
    $failed = 0;
    
    if (!empty($siswaOptions)) {
        foreach ($siswaOptions as $siswa) {
            $siswa_id = $siswa['id_siswa'];
            $status = 'hadir'; // Default hadir
            
            // Check if siswa is marked as not present
            if (isset($_POST['siswa_status'][$siswa_id]) && in_array($_POST['siswa_status'][$siswa_id], ['izin', 'sakit', 'alpha'])) {
                $status = $_POST['siswa_status'][$siswa_id];
            }
            
            // Check if record already exists
            $checkQuery = "SELECT id_absensi_kelas FROM absensi_kelas WHERE tgl = ? AND siswa_id = ? AND kelas_id = ?";
            $checkStmt = mysqli_prepare($conn, $checkQuery);
            mysqli_stmt_bind_param($checkStmt, 'sii', $tgl, $siswa_id, $kelas_id);
            mysqli_stmt_execute($checkStmt);
            $checkResult = mysqli_stmt_get_result($checkStmt);
            
            if (mysqli_num_rows($checkResult) > 0) {
                // Update existing record
                $updateQuery = "UPDATE absensi_kelas SET status = ? WHERE tgl = ? AND siswa_id = ? AND kelas_id = ?";
                $updateStmt = mysqli_prepare($conn, $updateQuery);
                mysqli_stmt_bind_param($updateStmt, 'ssii', $status, $tgl, $siswa_id, $kelas_id);
                if (mysqli_stmt_execute($updateStmt)) {
                    $inserted++;
                } else {
                    $failed++;
                }
            } else {
                // Insert new record
                $insertQuery = "INSERT INTO absensi_kelas (tgl, kelas_id, siswa_id, status) VALUES (?, ?, ?, ?)";
                $insertStmt = mysqli_prepare($conn, $insertQuery);
                mysqli_stmt_bind_param($insertStmt, 'siis', $tgl, $kelas_id, $siswa_id, $status);
                if (mysqli_stmt_execute($insertStmt)) {
                    $inserted++;
                } else {
                    $failed++;
                }
            }
        }
        
        $message = "$inserted data absensi berhasil disimpan";
        if ($failed > 0) {
            $message .= " ($failed gagal)";
        }
        $_SESSION['flash_message'] = $message;
        header('Location: absensi_kelas.php');
        exit;
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-semibold mb-1">Absensi Cepat Kelas</h4>
            <small>Pilih siswa yang TIDAK hadir, otomatis siswa lain hadir</small>
        </div>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= htmlspecialchars($message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card p-4 mb-4">
        <h5 class="mb-3">Pilih Data</h5>
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                <input type="date" name="tgl" class="form-control" value="<?= htmlspecialchars($selectedDate); ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Kelas <span class="text-danger">*</span></label>
                <select name="kelas_id" class="form-select" onchange="this.form.submit()" required>
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach ($kelasOptions as $kelas): ?>
                        <option value="<?= $kelas['id_kelas']; ?>" <?= $selectedKelas == $kelas['id_kelas'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($kelas['nama_kelas'] . ' - ' . $kelas['nama_jurusan']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Cari
                </button>
            </div>
        </form>
    </div>

    <?php if (!empty($siswaOptions)): ?>
        <div class="card p-4">
            <h5 class="mb-3">Siswa Kelas <?= htmlspecialchars($selectedKelas ? $kelasOptions[array_search($selectedKelas, array_column($kelasOptions, 'id_kelas'))]['nama_kelas'] : ''); ?></h5>
            <p class="text-secondary mb-3">Total: <?= count($siswaOptions); ?> siswa. Centang siswa yang TIDAK hadir</p>
            
            <form method="POST">
                <input type="hidden" name="tgl" value="<?= htmlspecialchars($selectedDate); ?>">
                <input type="hidden" name="kelas_id" value="<?= htmlspecialchars($selectedKelas); ?>">
                
                <div class="table-responsive mb-4">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>NISN</th>
                                <th>Nama Siswa</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($siswaOptions as $siswa): ?>
                                <tr>
                                    <td><?= htmlspecialchars($siswa['nis_siswa']); ?></td>
                                    <td><?= htmlspecialchars($siswa['nama_siswa']); ?></td>
                                    <td>
                                        <select name="siswa_status[<?= $siswa['id_siswa']; ?>]" class="form-select form-select-sm" style="max-width: 150px;">
                                            <option value="">Hadir</option>
                                            <option value="izin">Izin</option>
                                            <option value="sakit">Sakit</option>
                                            <option value="alpha">Alpha</option>
                                        </select>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" name="submit_quick_absensi" class="btn btn-success">
                        <i class="bi bi-check-lg"></i> Simpan Absensi
                    </button>
                    <a href="absensi_kelas.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    <?php elseif (!empty($selectedKelas)): ?>
        <div class="alert alert-info">Tidak ada siswa di kelas ini</div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
