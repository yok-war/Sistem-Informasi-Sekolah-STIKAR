<?php
include 'config.php';
include 'proses/helpers.php';
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
$title = "Dashboard";

$today = date('Y-m-d');

$totalSiswa = countRecords('siswa');
$totalGuru = countRecords('guru');
$totalJurusan = countRecords('jurusan');
$totalKelas = countRecords('kelas');
$jurnalKelasToday = countRecords('jurnal_kelas', "tgl = '$today'");
$jurnalGuruToday = countRecords('jurnal_guru', "tgl = '$today'");

$absensiKelasToday = getStatusStats('absensi_kelas', "tgl = '$today'");
$absensiGuruToday = getStatusStats('absensi_guru', "tgl = '$today'");

$lastSevenDays = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $lastSevenDays[$date] = [
        'label' => date('d M', strtotime($date)),
        'siswa' => 0,
        'guru' => 0
    ];
}

$absensiSiswaTrend = query(
    "SELECT tgl, SUM(status = 'hadir') AS hadir
     FROM absensi_kelas
     WHERE tgl BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND CURDATE()
     GROUP BY tgl
     ORDER BY tgl"
);
foreach ($absensiSiswaTrend as $row) {
    if (isset($lastSevenDays[$row['tgl']])) {
        $lastSevenDays[$row['tgl']]['siswa'] = (int)$row['hadir'];
    }
}

$absensiGuruTrend = query(
    "SELECT tgl, SUM(status = 'hadir') AS hadir
     FROM absensi_guru
     WHERE tgl BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND CURDATE()
     GROUP BY tgl
     ORDER BY tgl"
);
foreach ($absensiGuruTrend as $row) {
    if (isset($lastSevenDays[$row['tgl']])) {
        $lastSevenDays[$row['tgl']]['guru'] = (int)$row['hadir'];
    }
}

$siswaPerTahun = [
    'labels' => array_column($lastSevenDays, 'label'),
    'datasets' => [
        [
            'label' => 'Siswa Hadir',
            'data' => array_column($lastSevenDays, 'siswa'),
            'borderColor' => '#10b981',
            'backgroundColor' => 'rgba(16, 185, 129, 0.2)'
        ],
        [
            'label' => 'Guru Hadir',
            'data' => array_column($lastSevenDays, 'guru'),
            'borderColor' => '#3b82f6',
            'backgroundColor' => 'rgba(59, 130, 246, 0.2)'
        ]
    ]
];

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
                <h6>Total Guru</h6>
                <h3><?= $totalGuru ?></h3>
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
                <h6>Jurnal Kelas Hari Ini</h6>
                <h3><?= $jurnalKelasToday ?></h3>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card card-stat p-3">
                <h6>Jurnal Guru Hari Ini</h6>
                <h3><?= $jurnalGuruToday ?></h3>
            </div>
        </div>

    </div>

    <div class="row g-4 mt-3">
        <div class="col-12 col-lg-6">
            <div class="card card-stat p-4">
                <h6>Absensi Kelas Hari Ini</h6>
                <div class="row text-center mt-3">
                    <div class="col-6 col-sm-3 mb-2">
                        <div class="absen-box bg-success-subtle"><?= intval($absensiKelasToday['hadir'] ?? 0) ?><br>Hadir</div>
                    </div>
                    <div class="col-6 col-sm-3 mb-2">
                        <div class="absen-box bg-danger-subtle"><?= intval($absensiKelasToday['alpha'] ?? 0) ?><br>Alpha</div>
                    </div>
                    <div class="col-6 col-sm-3 mb-2">
                        <div class="absen-box bg-warning-subtle"><?= intval($absensiKelasToday['sakit'] ?? 0) ?><br>Sakit</div>
                    </div>
                    <div class="col-6 col-sm-3 mb-2">
                        <div class="absen-box bg-primary-subtle"><?= intval($absensiKelasToday['izin'] ?? 0) ?><br>Izin</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card card-stat p-4">
                <h6>Absensi Guru Hari Ini</h6>
                <div class="row text-center mt-3">
                    <div class="col-6 col-sm-3 mb-2">
                        <div class="absen-box bg-success-subtle"><?= intval($absensiGuruToday['hadir'] ?? 0) ?><br>Hadir</div>
                    </div>
                    <div class="col-6 col-sm-3 mb-2">
                        <div class="absen-box bg-danger-subtle"><?= intval($absensiGuruToday['alpha'] ?? 0) ?><br>Alpha</div>
                    </div>
                    <div class="col-6 col-sm-3 mb-2">
                        <div class="absen-box bg-warning-subtle"><?= intval($absensiGuruToday['sakit'] ?? 0) ?><br>Sakit</div>
                    </div>
                    <div class="col-6 col-sm-3 mb-2">
                        <div class="absen-box bg-primary-subtle"><?= intval($absensiGuruToday['izin'] ?? 0) ?><br>Izin</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== CHART ===== -->
    <div class="card card-stat p-4 mt-4">
        <h6>Siswa per Jurusan</h6>
        <canvas id="barChart"></canvas>
    </div>

    <div class="card card-stat p-4 mt-4">
        <h6>Statistik Kehadiran Minggu Ini</h6>
        <canvas id="donutChart"></canvas>
    </div>

</div>

<!-- FOOTER -->
<?php include 'includes/footer.php'; ?>

