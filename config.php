<?php
session_start();
$num = 1;
$base_url = "http://localhost/sis/";
$current_page = basename($_SERVER['PHP_SELF']);

$conn = mysqli_connect("localhost", "root", "", "akademik");
if (!$conn) {
    error_log(mysqli_connect_error());
    die("Terjadi Masalah Pada Sistem!");
}

function scr($msg)
{
    echo "
            <script>
                alert('$msg');
            </script>
        ";
}
function scrh($msg, $loc)
{
    echo "
            <script>
                alert('$msg');
                document.location.href = '$loc';
            </script>
        ";
}

function query($query)
{
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function tbh_siswa($data)
{
    global $conn;
    $nis = htmlspecialchars($data['nis']);
    $nisn = htmlspecialchars($data['nisn']);
    $nama = htmlspecialchars($data['nama']);
    $ttl = htmlspecialchars($data['ttl']);
    $alamat = htmlspecialchars($data['alamat']);
    $wa = htmlspecialchars($data['wa']);
    $kelas = htmlspecialchars($data['kelas']);
    $foto = upload_foto();

    // validasi foto
    if (!$foto) {
        return false;
    }

    // cek format nis/nisn
    if (!ctype_digit($nis)) {
        scrh('NISN harus angka', 'tbh_siswa.php');
    }
    if (strlen($nis) != 4) {
        scrh('NIS harus 4 digit', 'tbh_siswa.php');
    }
    if (!ctype_digit($nisn)) {
        scrh('NISN harus angka', 'tbh_siswa.php');
    }
    if (strlen($nisn) != 10) {
        scrh('NISN harus 10 digit', 'tbh_siswa.php');
    }

    // cek format nomor wa 
    if (!preg_match('/^08[0-9]{10}$/', $wa)) {
        scrh('format WA tidak valid! \n harus diawali 08 dan dilanjutkan angka sampai dengan 10 digit', 'tbh_siswa.php');
        die;
    }

    $query = " INSERT INTO siswa (nama_siswa, nis_siswa, nisn_siswa, ttl_siswa, alamat_siswa, wa_siswa, foto_siswa, kelas_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmn = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmn, 'sssssssi', $nama, $nis, $nisn, $ttl, $alamat, $wa, $foto, $kelas);
    mysqli_stmt_execute($stmn);
    return mysqli_affected_rows($conn);
}

function tbh_kelas($data)
{
    global $conn;
    $nama = htmlspecialchars($data['nama']);
    $jurusan_id = htmlspecialchars($data['jurusan']);

    $stmn = mysqli_prepare($conn, "INSERT INTO kelas (nama_kelas, jurusan_id) VALUES (?,?)");

    mysqli_stmt_bind_param($stmn, "sii", $nama, $jurusan_id,);
    mysqli_stmt_execute($stmn);

    return mysqli_affected_rows($conn);
}

function tbh_jurusan($data)
{
    global $conn;
    $nama = htmlspecialchars($data['nama']);

    $stmn = mysqli_prepare($conn, "INSERT INTO jurusan (nama_jurusan) VALUES (?)");

    mysqli_stmt_bind_param($stmn, "s", $nama);
    mysqli_stmt_execute($stmn);

    return mysqli_affected_rows($conn);
}

function tbh_guru($data) {
    global $conn;
    $nama = htmlspecialchars($data['nama']);
    $ttl = htmlspecialchars($data['ttl']);
    $alamat = htmlspecialchars($data['alamat']);
    $wa = htmlspecialchars($data['wa']);
    $foto = upload_foto();

    $stmn = mysqli_prepare($conn,"INSERT INTO guru (nama_guru, ttl_guru, alamat_guru, wa_guru, foto_guru) VALUES (?,?,?,?,?)");
    mysqli_stmt_bind_param($stmn, "sssss", $nama, $ttl, $alamat, $wa, $foto);
    mysqli_stmt_execute($stmn);

    return mysqli_affected_rows($conn);
}

function edit_siswa($data)
{
    global $conn;
    $id = htmlspecialchars($data['id']);
    $nis = htmlspecialchars($data['nis']);
    $nisn = htmlspecialchars($data['nisn']);
    $nama = htmlspecialchars($data['nama']);
    $ttl = htmlspecialchars($data['ttl']);
    $alamat = htmlspecialchars($data['alamat']);
    $wa = htmlspecialchars($data['wa']);
    $kelas = htmlspecialchars($data['kelas']);
    $fotoLama = htmlspecialchars($data['foto_lama']);
    $foto = upload_foto(false);

    if ($foto === false) {
        $foto = $fotoLama;
    }

    $query = "UPDATE siswa SET 
        id_siswa='$id',
        nama_siswa='$nama',
        nis_siswa='$nis',
        nisn_siswa='$nisn',
        ttl_siswa='$ttl',
        alamat_siswa='$alamat',
        wa_siswa='$wa',
        foto_siswa='$foto',
        kelas_id='$kelas'
        WHERE id_siswa = $id
        ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function edit_kelas($data)
{
    global $conn;
    $id = htmlspecialchars($data['id']);
    $nama = htmlspecialchars(strtoupper($data['nama']));
    $jurusan = htmlspecialchars($data['jurusan']);

    $query = "UPDATE kelas SET
        id_kelas='$id',
        jurusan_id='$jurusan',
        nama_kelas='$nama'
        WHERE id_kelas = $id
        ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}
function edit_jurusan($data)
{
    global $conn;
    $id = htmlspecialchars($data['id']);
    $nama = htmlspecialchars($data['nama']);

    $query = "UPDATE jurusan SET 
        id_jurusan='$id',
        nama_jurusan='$nama'
        WHERE id_jurusan = $id
        ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function edit_guru($data) 
{
    global $conn;
    $id = htmlspecialchars($data['id']);
    $nama = htmlspecialchars($data['nama']);
    $ttl = htmlspecialchars($data['ttl']);
    $alamat = htmlspecialchars($data['alamat']);
    $wa = htmlspecialchars($data['wa']);
    $fotoLama = htmlspecialchars($data['foto_lama']);
    $foto = upload_foto(false);

    if ($foto === false) {
        $foto = $fotoLama;
    }

    $query = "UPDATE guru SET
        id_guru = '$id',
        nama_guru = '$nama',
        ttl_guru = '$ttl',
        alamat_guru = '$alamat',
        wa_guru = '$wa',
        foto_guru = '$foto'

        WHERE id_guru = $id;
    ";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function hps_siswa($id)
{
    global $conn;
    mysqli_query($conn, "DELETE FROM siswa WHERE id_siswa = $id");

    return mysqli_affected_rows($conn);
}

function hps_kelas($id)
{
    global $conn;
    mysqli_query($conn, "DELETE FROM kelas WHERE id_kelas = $id");

    return mysqli_affected_rows($conn);
}

function hps_jurusan($id)
{
    global $conn;
    mysqli_query($conn, "DELETE FROM jurusan WHERE id_jurusan = $id");

    return mysqli_affected_rows($conn);
}

function hps_guru($id) {
    global $conn;
    mysqli_query($conn,"DELETE FROM guru WHERE id_guru = $id");

    return mysqli_affected_rows($conn);
}

function absensi_table_exists()
{
    global $conn;
    $result = mysqli_query($conn, "SHOW TABLES LIKE 'absensi'");

    return $result && mysqli_num_rows($result) > 0;
}

function get_absensi($filters = [])
{
    global $conn;

    $query = "SELECT 
                absensi.id_absensi,
                absensi.tanggal,
                absensi.status,
                absensi.jam_masuk,
                absensi.jam_keluar,
                absensi.keterangan,
                absensi.semester,
                absensi.tahun_ajaran,
                absensi.is_verified,
                siswa.id_siswa,
                siswa.nis_siswa,
                siswa.nama_siswa,
                kelas.id_kelas,
                kelas.nama_kelas,
                jurusan.id_jurusan,
                jurusan.nama_jurusan,
                guru.nama_guru
            FROM absensi
            JOIN siswa ON absensi.siswa_id = siswa.id_siswa
            LEFT JOIN kelas ON siswa.kelas_id = kelas.id_kelas
            LEFT JOIN jurusan ON kelas.jurusan_id = jurusan.id_jurusan
            LEFT JOIN guru ON absensi.guru_id = guru.id_guru
            WHERE 1=1";

    if (!empty($filters['jurusan']) && $filters['jurusan'] !== 'semua') {
        $jurusan = (int) $filters['jurusan'];
        $query .= " AND jurusan.id_jurusan = $jurusan";
    }

    if (!empty($filters['tanggal'])) {
        $tanggal = mysqli_real_escape_string($conn, $filters['tanggal']);
        $query .= " AND absensi.tanggal = '$tanggal'";
    }

    if (!empty($filters['kelas']) && $filters['kelas'] !== 'semua') {
        $kelas = (int) $filters['kelas'];
        $query .= " AND kelas.id_kelas = $kelas";
    }

    if (!empty($filters['status']) && $filters['status'] !== 'semua') {
        $status = mysqli_real_escape_string($conn, $filters['status']);
        $query .= " AND absensi.status = '$status'";
    }

    $query .= " ORDER BY absensi.tanggal DESC, siswa.nama_siswa ASC";

    return query($query);
}

function get_absensi_by_id($id)
{
    $id = (int) $id;
    $data = query("SELECT * FROM absensi WHERE id_absensi = $id");

    return $data ? $data[0] : null;
}

function tbh_absensi($data)
{
    global $conn;

    $siswa = (int) htmlspecialchars($data['siswa']);
    $tanggal = htmlspecialchars($data['tanggal']);
    $status = htmlspecialchars($data['status']);
    $jamMasuk = !empty($data['jam_masuk']) ? htmlspecialchars($data['jam_masuk']) : null;
    $jamKeluar = !empty($data['jam_keluar']) ? htmlspecialchars($data['jam_keluar']) : null;
    $keterangan = !empty($data['keterangan']) ? htmlspecialchars($data['keterangan']) : null;
    $guru = !empty($data['guru']) ? (int) htmlspecialchars($data['guru']) : null;
    $semester = !empty($data['semester']) ? htmlspecialchars($data['semester']) : null;
    $tahunAjaran = !empty($data['tahun_ajaran']) ? htmlspecialchars($data['tahun_ajaran']) : null;
    $isVerified = isset($data['is_verified']) ? 1 : 0;

    $cek = mysqli_prepare($conn, "SELECT id_absensi FROM absensi WHERE siswa_id = ? AND tanggal = ?");
    mysqli_stmt_bind_param($cek, "is", $siswa, $tanggal);
    mysqli_stmt_execute($cek);
    mysqli_stmt_store_result($cek);

    if (mysqli_stmt_num_rows($cek) > 0) {
        scr('absensi siswa pada tanggal tersebut sudah ada');
        return 0;
    }

    $query = "INSERT INTO absensi (siswa_id, tanggal, status, jam_masuk, jam_keluar, keterangan, guru_id, semester, tahun_ajaran, is_verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "isssssissi", $siswa, $tanggal, $status, $jamMasuk, $jamKeluar, $keterangan, $guru, $semester, $tahunAjaran, $isVerified);

    if (mysqli_stmt_execute($stmt)) {
        return 1;
    }

    return 0;
}

function edit_absensi($data)
{
    global $conn;

    $id = (int) htmlspecialchars($data['id']);
    $siswa = (int) htmlspecialchars($data['siswa']);
    $tanggal = htmlspecialchars($data['tanggal']);
    $status = htmlspecialchars($data['status']);
    $jamMasuk = !empty($data['jam_masuk']) ? htmlspecialchars($data['jam_masuk']) : null;
    $jamKeluar = !empty($data['jam_keluar']) ? htmlspecialchars($data['jam_keluar']) : null;
    $keterangan = !empty($data['keterangan']) ? htmlspecialchars($data['keterangan']) : null;
    $guru = !empty($data['guru']) ? (int) htmlspecialchars($data['guru']) : null;
    $semester = !empty($data['semester']) ? htmlspecialchars($data['semester']) : null;
    $tahunAjaran = !empty($data['tahun_ajaran']) ? htmlspecialchars($data['tahun_ajaran']) : null;
    $isVerified = isset($data['is_verified']) ? 1 : 0;

    $cek = mysqli_prepare($conn, "SELECT id_absensi FROM absensi WHERE siswa_id = ? AND tanggal = ? AND id_absensi != ?");
    mysqli_stmt_bind_param($cek, "isi", $siswa, $tanggal, $id);
    mysqli_stmt_execute($cek);
    mysqli_stmt_store_result($cek);

    if (mysqli_stmt_num_rows($cek) > 0) {
        scr('absensi siswa pada tanggal tersebut sudah ada');
        return 0;
    }

    $query = "UPDATE absensi SET
                siswa_id = ?,
                tanggal = ?,
                status = ?,
                jam_masuk = ?,
                jam_keluar = ?,
                keterangan = ?,
                guru_id = ?,
                semester = ?,
                tahun_ajaran = ?,
                is_verified = ?
              WHERE id_absensi = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "isssssissii", $siswa, $tanggal, $status, $jamMasuk, $jamKeluar, $keterangan, $guru, $semester, $tahunAjaran, $isVerified, $id);

    if (mysqli_stmt_execute($stmt)) {
        return 1;
    }

    return 0;
}

function hps_absensi($id)
{
    global $conn;
    $id = (int) $id;

    mysqli_query($conn, "DELETE FROM absensi WHERE id_absensi = $id");

    return mysqli_affected_rows($conn);
}

function hps_many($table, $idColumn, $ids)
{
    global $conn;

    $allowed = [
        'siswa' => 'id_siswa',
        'guru' => 'id_guru',
        'jurusan' => 'id_jurusan',
        'kelas' => 'id_kelas',
        'absensi' => 'id_absensi'
    ];

    if (!isset($allowed[$table]) || $allowed[$table] !== $idColumn || empty($ids) || !is_array($ids)) {
        return 0;
    }

    $ids = array_map('intval', $ids);
    $ids = array_filter($ids, fn($id) => $id > 0);

    if (empty($ids)) {
        return 0;
    }

    $idList = implode(',', $ids);
    mysqli_query($conn, "DELETE FROM $table WHERE $idColumn IN ($idList)");

    return mysqli_affected_rows($conn);
}

/**
 * Delete kelas dan semua dependencies
 */
function hps_kelas_with_dependencies($kelas_ids)
{
    global $conn;
    
    if (empty($kelas_ids) || !is_array($kelas_ids)) {
        return 0;
    }
    
    $kelas_ids = array_map('intval', $kelas_ids);
    $kelas_ids = array_filter($kelas_ids, fn($id) => $id > 0);
    
    if (empty($kelas_ids)) {
        return 0;
    }
    
    $idList = implode(',', $kelas_ids);
    
    // Delete semua records yang depend on kelas
    // 1. Delete dari absensi_kelas
    mysqli_query($conn, "DELETE FROM absensi_kelas WHERE kelas_id IN ($idList)");
    
    // 2. Delete dari absensi_guru
    mysqli_query($conn, "DELETE FROM absensi_guru WHERE kelas_id IN ($idList)");
    
    // 3. Delete dari jurnal_kelas
    mysqli_query($conn, "DELETE FROM jurnal_kelas WHERE kelas_id IN ($idList)");
    
    // 4. Delete dari jurnal_guru
    mysqli_query($conn, "DELETE FROM jurnal_guru WHERE kelas_id IN ($idList)");
    
    // 5. Delete dari siswa (update kelas_id ke NULL atau delete)
    // Pilih: update ke NULL agar siswa tidak hilang
    mysqli_query($conn, "UPDATE siswa SET kelas_id = NULL WHERE kelas_id IN ($idList)");
    
    // 6. Finally, delete kelas itu sendiri
    mysqli_query($conn, "DELETE FROM kelas WHERE id_kelas IN ($idList)");
    
    return mysqli_affected_rows($conn);
}

function get_kelas_with_jurusan($jurusanId = null)
{
    global $conn;

    $query = "SELECT kelas.*, jurusan.nama_jurusan
              FROM kelas
              LEFT JOIN jurusan ON kelas.jurusan_id = jurusan.id_jurusan";

    if (!empty($jurusanId) && $jurusanId !== 'semua') {
        $jurusanId = (int) $jurusanId;
        $query .= " WHERE kelas.jurusan_id = $jurusanId";
    }

    $query .= " ORDER BY kelas.nama_kelas ASC";

    return query($query);
}

function normalize_ids($ids)
{
    if (empty($ids) || !is_array($ids)) {
        return [];
    }

    $ids = array_map('intval', $ids);
    $ids = array_filter($ids, fn($id) => $id > 0);

    return array_values(array_unique($ids));
}

function get_siswa_by_kelas($kelasId)
{
    $kelasId = (int) $kelasId;

    return query("SELECT siswa.id_siswa, siswa.nis_siswa, siswa.nama_siswa, kelas.nama_kelas, jurusan.nama_jurusan
                  FROM siswa
                  LEFT JOIN kelas ON siswa.kelas_id = kelas.id_kelas
                  LEFT JOIN jurusan ON kelas.jurusan_id = jurusan.id_jurusan
                  WHERE siswa.kelas_id = $kelasId
                  ORDER BY siswa.nama_siswa ASC");
}

function tbh_absensi_massal($data)
{
    global $conn;

    $tanggal = htmlspecialchars($data['tanggal']);
    $guru = !empty($data['guru']) ? (int) htmlspecialchars($data['guru']) : null;
    $semester = !empty($data['semester']) ? htmlspecialchars($data['semester']) : null;
    $tahunAjaran = !empty($data['tahun_ajaran']) ? htmlspecialchars($data['tahun_ajaran']) : null;
    $isVerified = isset($data['is_verified']) ? 1 : 0;
    $statusList = $data['status'] ?? [];
    $jamMasukList = $data['jam_masuk'] ?? [];
    $jamKeluarList = $data['jam_keluar'] ?? [];
    $keteranganList = $data['keterangan'] ?? [];

    if (empty($tanggal) || empty($statusList) || !is_array($statusList)) {
        return [
            'inserted' => 0,
            'skipped' => 0
        ];
    }

    $checkStmt = mysqli_prepare($conn, "SELECT id_absensi FROM absensi WHERE siswa_id = ? AND tanggal = ?");
    $insertStmt = mysqli_prepare($conn, "INSERT INTO absensi (siswa_id, tanggal, status, jam_masuk, jam_keluar, keterangan, guru_id, semester, tahun_ajaran, is_verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $inserted = 0;
    $skipped = 0;

    foreach ($statusList as $siswaId => $status) {
        $siswaId = (int) $siswaId;
        $status = htmlspecialchars($status);
        $jamMasuk = !empty($jamMasukList[$siswaId]) ? htmlspecialchars($jamMasukList[$siswaId]) : null;
        $jamKeluar = !empty($jamKeluarList[$siswaId]) ? htmlspecialchars($jamKeluarList[$siswaId]) : null;
        $keterangan = !empty($keteranganList[$siswaId]) ? htmlspecialchars($keteranganList[$siswaId]) : null;

        mysqli_stmt_bind_param($checkStmt, "is", $siswaId, $tanggal);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);

        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            $skipped++;
            continue;
        }

        mysqli_stmt_bind_param($insertStmt, "isssssissi", $siswaId, $tanggal, $status, $jamMasuk, $jamKeluar, $keterangan, $guru, $semester, $tahunAjaran, $isVerified);

        if (mysqli_stmt_execute($insertStmt)) {
            $inserted++;
        } else {
            $skipped++;
        }
    }

    return [
        'inserted' => $inserted,
        'skipped' => $skipped
    ];
}

function edit_siswa_many_kelas($ids, $kelasId)
{
    global $conn;

    $ids = normalize_ids($ids);
    $kelasId = (int) $kelasId;

    if (empty($ids) || $kelasId <= 0) {
        return 0;
    }

    $idList = implode(',', $ids);
    mysqli_query($conn, "UPDATE siswa SET kelas_id = $kelasId WHERE id_siswa IN ($idList)");

    return mysqli_affected_rows($conn);
}

function edit_absensi_many($ids, $data)
{
    global $conn;

    $ids = normalize_ids($ids);

    if (empty($ids)) {
        return 0;
    }

    $fields = [];

    if (!empty($data['status'])) {
        $status = mysqli_real_escape_string($conn, htmlspecialchars($data['status']));
        $fields[] = "status = '$status'";
    }

    if (array_key_exists('guru', $data)) {
        if ($data['guru'] === '__nochange__') {
            // jangan ubah
        } elseif ($data['guru'] === '__clear__' || $data['guru'] === '' || $data['guru'] === null) {
            $fields[] = "guru_id = NULL";
        } else {
            $guru = (int) $data['guru'];
            $fields[] = "guru_id = $guru";
        }
    }

    if (isset($data['semester'])) {
        $semester = trim($data['semester']);
        if ($semester === '') {
            $fields[] = "semester = NULL";
        } else {
            $semester = mysqli_real_escape_string($conn, htmlspecialchars($semester));
            $fields[] = "semester = '$semester'";
        }
    }

    if (isset($data['tahun_ajaran'])) {
        $tahunAjaran = trim($data['tahun_ajaran']);
        if ($tahunAjaran === '') {
            $fields[] = "tahun_ajaran = NULL";
        } else {
            $tahunAjaran = mysqli_real_escape_string($conn, htmlspecialchars($tahunAjaran));
            $fields[] = "tahun_ajaran = '$tahunAjaran'";
        }
    }

    $isVerified = isset($data['is_verified']) ? 1 : 0;
    if (isset($data['set_verified'])) {
        $fields[] = "is_verified = $isVerified";
    }

    if (empty($fields)) {
        return 0;
    }

    $idList = implode(',', $ids);
    $setClause = implode(', ', $fields);
    mysqli_query($conn, "UPDATE absensi SET $setClause WHERE id_absensi IN ($idList)");

    return mysqli_affected_rows($conn);
}



function upload_foto($required = true)
{
    if (!isset($_FILES['foto'])) {
        return false;
    }

    $namaFile   = $_FILES['foto']['name'];
    $ukuranFile = $_FILES['foto']['size'];
    $error      = $_FILES['foto']['error'];
    $tmpName    = $_FILES['foto']['tmp_name'];

    if ($error === 4) {
        if ($required) {
            echo "<script>alert('pilih gambar terlebih dahulu');</script>";
        }
        return false;
    }

    $extensiFileValid = ['jpg', 'jpeg', 'png'];
    $extensiFile = explode('.', $namaFile);
    $extensiFile = strtolower(end($extensiFile));

    if (!in_array($extensiFile, $extensiFileValid)) {
        echo "<script>alert('extensi file harus JPG/JPEG/PNG!');</script>";
        return false;
    }

    if ($ukuranFile > 3000000) {
        echo "<script>alert('ukuran gambar maks 3MB!');</script>";
        return false;
    }

    // Dapatkan tanggal hari ini
    $date = date('Ymd');

    // Folder target
    $folder = __DIR__ . '/assets/img/new/';

    // Cari file yang match pattern YYYYMMDD* untuk semua ekstensi yang diizinkan
    $files = glob($folder . $date . '*.*');

    $max_code = 0;
    foreach ($files as $file) {
        $filename = pathinfo($file, PATHINFO_FILENAME);
        if (strlen($filename) == 12) { // YYYYMMDDXXXX
            $code = (int)substr($filename, 8, 4);
            if ($code > $max_code) {
                $max_code = $code;
            }
        }
    }

    // Kode baru
    $new_code = sprintf('%04d', $max_code + 1);

    $namaFileBaru = $date . $new_code . '.' . $extensiFile;
    $target = $folder . $namaFileBaru;

    if (move_uploaded_file($tmpName, $target)) {
        return $namaFileBaru;
    } else {
        echo "<script>alert('gagal upload gambar');</script>";
        return false;
    }
}


// CREATE TABLE users (
//     id INT(11) NOT NULL AUTO_INCREMENT,
//     username VARCHAR(50) NOT NULL UNIQUE,
//     password VARCHAR(255) NOT NULL,
//     nama VARCHAR(100) DEFAULT NULL,
//     email VARCHAR(100) DEFAULT NULL UNIQUE,
//     PRIMARY KEY (id)
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

// INSERT INTO users (username, password, nama, email) VALUES ('admin', MD5('password123'), 'Administrator', 'admin@example.com');

// CREATE TABLE settings (
//     id INT(11) NOT NULL DEFAULT 1,
//     theme VARCHAR(10) DEFAULT 'light',
//     notifications TINYINT(1) DEFAULT 1,
//     PRIMARY KEY (id)
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

// INSERT INTO settings (id) VALUES (1) ON DUPLICATE KEY UPDATE id=id;
