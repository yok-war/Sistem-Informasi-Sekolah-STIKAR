<?php
include 'config.php';
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
$title = "Dashboard";
// ===== Dummy Data  =====
$totalSiswa = 6;
$siswaAktif = 6;
$totalJurusan = 4;
$totalKelas = 4;
$totalGuru = 6;
$jurnalHariIni = 0;

$jurusan = query("
    SELECT
        jurusan.nama_jurusan,
        COUNT(siswa.kelas_id) AS total_siswa
    FROM jurusan
    LEFT JOIN kelas ON kelas.jurusan_id = jurusan.id_jurusan
    LEFT JOIN siswa ON siswa.kelas_id = kelas.id_kelas
    GROUP BY jurusan.id_jurusan, jurusan.nama_jurusan
    ORDER BY jurusan.nama_jurusan
");

$siswa = query("SELECT * FROM siswa");

// Dummy data array untuk chart statistik siswa per tahun.
$siswaPerTahun = [
    '2021' => 120,
    '2022' => 138,
    '2023' => 149,
    '2024' => 163,
    '2025' => 171
];
?>

<!-- ===== HEADER ===== -->
<?php include 'includes/header.php'; ?>

<div class="content">
    <div class="row g-4">

        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card card-stat p-3">
                <h6>Total Siswa</h6>
                <h3><?= $totalSiswa ?></h3>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card card-stat p-3">
                <h6>Siswa Aktif</h6>
                <h3><?= $siswaAktif ?></h3>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card card-stat p-3">
                <h6>Jurusan</h6>
                <h3><?= $totalJurusan ?></h3>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card card-stat p-3">
                <h6>Kelas</h6>
                <h3><?= $totalKelas ?></h3>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card card-stat p-3">
                <h6>Guru</h6>
                <h3><?= $totalGuru ?></h3>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card card-stat p-3">
                <h6>Jurnal Hari Ini</h6>
                <h3><?= $jurnalHariIni ?></h3>
            </div>
        </div>

    </div>

    <!-- ===== CHART ===== -->
    <div class="card card-stat p-4 mt-4">
        <h6>Siswa per Jurusan</h6>
        <canvas id="barChart"></canvas>
    </div>

    <div class="card card-stat p-4 mt-4">
        <h6>Statistik Jumlah Siswa per Tahun</h6>
        <canvas id="donutChart"></canvas>
    </div>

    <!-- ===== ABSENSI ===== -->
    <div class="card card-stat p-4 mt-4">
        <h6>Absensi Hari Ini</h6>
        <div class="row text-center mt-3">
            <div class="col-md-3 mb-2">
                <div class="absen-box bg-success-subtle">0<br>Hadir</div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="absen-box bg-warning-subtle">0<br>Sakit</div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="absen-box bg-primary-subtle">0<br>Izin</div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="absen-box bg-danger-subtle">0<br>Alpha</div>
            </div>
        </div>
    </div>

</div>

<!-- FOOTER -->
<?php include 'includes/footer.php'; ?>

