<?php
/**
 * QUERIES.PHP - Database Query Functions for Absensi & Jurnal Module
 * All database operations are centralized here
 */

include_once 'helpers.php';

/**
 * ================================
 * ABSENSI KELAS QUERIES
 * ================================
 */

/**
 * Get all absensi kelas with filters and pagination
 */
function getAbsensiKelas($filters = [], $limit = 0, $offset = 0) {
    global $conn;
    
    $where = buildFilterQuery('absensi_kelas', $filters);
    $joins = "JOIN siswa ON absensi_kelas.siswa_id = siswa.id_siswa 
              JOIN kelas ON absensi_kelas.kelas_id = kelas.id_kelas
              JOIN jurusan ON kelas.jurusan_id = jurusan.id_jurusan";
    
    $query = "SELECT absensi_kelas.*, siswa.nama_siswa, siswa.nis_siswa, kelas.nama_kelas, jurusan.nama_jurusan 
              FROM absensi_kelas $joins 
              WHERE $where 
              ORDER BY absensi_kelas.tgl DESC";
    
    if ($limit > 0) {
        $query .= " LIMIT $limit OFFSET $offset";
    }
    
    return query($query);
}

/**
 * Get absensi kelas by ID
 */
function getAbsensiKelasById($id) {
    global $conn;
    $id = (int)$id;
    
    $result = mysqli_query($conn, "SELECT absensi_kelas.*, siswa.nama_siswa, siswa.nis_siswa, kelas.nama_kelas 
                                   FROM absensi_kelas 
                                   JOIN siswa ON absensi_kelas.siswa_id = siswa.id_siswa 
                                   JOIN kelas ON absensi_kelas.kelas_id = kelas.id_kelas 
                                   WHERE absensi_kelas.id_absensi_kelas = $id");
    
    return mysqli_fetch_assoc($result);
}

/**
 * Insert absensi kelas
 */
function insertAbsensiKelas($tgl, $kelas_id, $siswa_id, $status) {
    global $conn;
    
    // Validation
    if (!validateDate($tgl)) {
        return ['success' => false, 'message' => 'Format tanggal tidak valid'];
    }
    
    if (!in_array($status, ['hadir', 'izin', 'sakit', 'alpha'])) {
        return ['success' => false, 'message' => 'Status tidak valid'];
    }
    
    // Check duplicate
    if (recordExists('absensi_kelas', ['tgl' => $tgl, 'siswa_id' => $siswa_id, 'kelas_id' => $kelas_id])) {
        return ['success' => false, 'message' => 'Data absensi sudah ada untuk tanggal dan siswa ini'];
    }
    
    // Prepare statement
    $stmt = mysqli_prepare($conn, "INSERT INTO absensi_kelas (tgl, kelas_id, siswa_id, status) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'siis', $tgl, $kelas_id, $siswa_id, $status);
    
    if (mysqli_stmt_execute($stmt)) {
        return ['success' => true, 'message' => 'Data absensi berhasil ditambahkan'];
    } else {
        return ['success' => false, 'message' => 'Gagal menambahkan data: ' . mysqli_error($conn)];
    }
}

/**
 * Update absensi kelas
 */
function updateAbsensiKelas($id, $tgl, $kelas_id, $siswa_id, $status) {
    global $conn;
    
    $id = (int)$id;
    
    if (!validateDate($tgl)) {
        return ['success' => false, 'message' => 'Format tanggal tidak valid'];
    }
    
    if (!in_array($status, ['hadir', 'izin', 'sakit', 'alpha'])) {
        return ['success' => false, 'message' => 'Status tidak valid'];
    }
    
    $stmt = mysqli_prepare($conn, "UPDATE absensi_kelas SET tgl = ?, kelas_id = ?, siswa_id = ?, status = ? WHERE id_absensi_kelas = ?");
    mysqli_stmt_bind_param($stmt, 'siiis', $tgl, $kelas_id, $siswa_id, $status, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        return ['success' => true, 'message' => 'Data absensi berhasil diperbarui'];
    } else {
        return ['success' => false, 'message' => 'Gagal memperbarui data'];
    }
}

/**
 * Delete absensi kelas
 */
function deleteAbsensiKelas($id) {
    global $conn;
    
    $id = (int)$id;
    $stmt = mysqli_prepare($conn, "DELETE FROM absensi_kelas WHERE id_absensi_kelas = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    
    if (mysqli_stmt_execute($stmt)) {
        return ['success' => true, 'message' => 'Data absensi berhasil dihapus'];
    } else {
        return ['success' => false, 'message' => 'Gagal menghapus data'];
    }
}

/**
 * Get kelas options for dropdown
 */
function getKelasOptions() {
    return query("SELECT id_kelas, nama_kelas FROM kelas ORDER BY nama_kelas ASC");
}
/**
 * Get jurusan options for dropdown
 */
function getJurusanOptions() {
    return query("SELECT id_jurusan, nama_jurusan FROM jurusan ORDER BY nama_jurusan ASC");
}

/**
 * Get siswa by kelas
 */
function getSiswaByKelas($kelas_id) {
    $kelas_id = (int)$kelas_id;
    return query("SELECT id_siswa, nama_siswa, nis_siswa FROM siswa WHERE kelas_id = $kelas_id ORDER BY nama_siswa ASC");
}

/**
 * ================================
 * ABSENSI GURU QUERIES
 * ================================
 */

/**
 * Get all absensi guru with filters and pagination
 */
function getAbsensiGuru($filters = [], $limit = 0, $offset = 0) {
    global $conn;
    
    $where = buildFilterQuery('absensi_guru', $filters);
    $joins = "JOIN guru ON absensi_guru.guru_id = guru.id_guru 
              JOIN kelas ON absensi_guru.kelas_id = kelas.id_kelas
              JOIN jurusan ON kelas.jurusan_id = jurusan.id_jurusan
              LEFT JOIN siswa ON absensi_guru.siswa_id = siswa.id_siswa";
    
    $query = "SELECT absensi_guru.*, guru.nama_guru, kelas.nama_kelas, jurusan.nama_jurusan, siswa.nama_siswa, siswa.nis_siswa 
              FROM absensi_guru $joins 
              WHERE $where 
              ORDER BY absensi_guru.tgl DESC";
    
    if ($limit > 0) {
        $query .= " LIMIT $limit OFFSET $offset";
    }
    
    return query($query);
}

/**
 * Get absensi guru by ID
 */
function getAbsensiGuruById($id) {
    global $conn;
    $id = (int)$id;
    
    $result = mysqli_query($conn, "SELECT absensi_guru.*, guru.nama_guru, kelas.nama_kelas 
                                   FROM absensi_guru 
                                   JOIN guru ON absensi_guru.guru_id = guru.id_guru 
                                   JOIN kelas ON absensi_guru.kelas_id = kelas.id_kelas 
                                   WHERE absensi_guru.id_absensi_guru = $id");
    
    return mysqli_fetch_assoc($result);
}

/**
 * Insert absensi guru
 */
function insertAbsensiGuru($tgl, $guru_id, $kelas_id, $siswa_id, $status) {
    global $conn;
    
    if (!validateDate($tgl)) {
        return ['success' => false, 'message' => 'Format tanggal tidak valid'];
    }
    
    if (!in_array($status, ['hadir', 'izin', 'sakit', 'alpha'])) {
        return ['success' => false, 'message' => 'Status tidak valid'];
    }
    
    if (recordExists('absensi_guru', ['tgl' => $tgl, 'guru_id' => $guru_id, 'kelas_id' => $kelas_id])) {
        return ['success' => false, 'message' => 'Data absensi sudah ada untuk tanggal dan guru ini'];
    }
    
    $siswa_id = !empty($siswa_id) ? (int)$siswa_id : null;
    
    $stmt = mysqli_prepare($conn, "INSERT INTO absensi_guru (tgl, guru_id, kelas_id, siswa_id, status) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'siisis', $tgl, $guru_id, $kelas_id, $siswa_id, $status);
    
    if (mysqli_stmt_execute($stmt)) {
        return ['success' => true, 'message' => 'Data absensi berhasil ditambahkan'];
    } else {
        return ['success' => false, 'message' => 'Gagal menambahkan data'];
    }
}

/**
 * Update absensi guru
 */
function updateAbsensiGuru($id, $tgl, $guru_id, $kelas_id, $siswa_id, $status) {
    global $conn;
    
    $id = (int)$id;
    
    if (!validateDate($tgl)) {
        return ['success' => false, 'message' => 'Format tanggal tidak valid'];
    }
    
    if (!in_array($status, ['hadir', 'izin', 'sakit', 'alpha'])) {
        return ['success' => false, 'message' => 'Status tidak valid'];
    }
    
    $siswa_id = !empty($siswa_id) ? (int)$siswa_id : null;
    
    $stmt = mysqli_prepare($conn, "UPDATE absensi_guru SET tgl = ?, guru_id = ?, kelas_id = ?, siswa_id = ?, status = ? WHERE id_absensi_guru = ?");
    mysqli_stmt_bind_param($stmt, 'siissi', $tgl, $guru_id, $kelas_id, $siswa_id, $status, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        return ['success' => true, 'message' => 'Data absensi berhasil diperbarui'];
    } else {
        return ['success' => false, 'message' => 'Gagal memperbarui data'];
    }
}

/**
 * Delete absensi guru
 */
function deleteAbsensiGuru($id) {
    global $conn;
    
    $id = (int)$id;
    $stmt = mysqli_prepare($conn, "DELETE FROM absensi_guru WHERE id_absensi_guru = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    
    if (mysqli_stmt_execute($stmt)) {
        return ['success' => true, 'message' => 'Data absensi berhasil dihapus'];
    } else {
        return ['success' => false, 'message' => 'Gagal menghapus data'];
    }
}

/**
 * Get guru options for dropdown
 */
function getGuruOptions() {
    return query("SELECT id_guru, nama_guru FROM guru ORDER BY nama_guru ASC");
}

/**
 * ================================
 * JURNAL KELAS QUERIES
 * ================================
 */

/**
 * Get all jurnal kelas with filters and pagination
 */
function getJurnalKelas($filters = [], $limit = 0, $offset = 0) {
    global $conn;
    
    $where = buildFilterQuery('jurnal_kelas', $filters);
    $joins = "JOIN kelas ON jurnal_kelas.kelas_id = kelas.id_kelas";
    
    $query = "SELECT jurnal_kelas.*, kelas.nama_kelas 
              FROM jurnal_kelas $joins 
              WHERE $where 
              ORDER BY jurnal_kelas.tgl DESC";
    
    if ($limit > 0) {
        $query .= " LIMIT $limit OFFSET $offset";
    }
    
    return query($query);
}

/**
 * Get jurnal kelas by ID
 */
function getJurnalKelasById($id) {
    global $conn;
    $id = (int)$id;
    
    $result = mysqli_query($conn, "SELECT jurnal_kelas.*, kelas.nama_kelas 
                                   FROM jurnal_kelas 
                                   JOIN kelas ON jurnal_kelas.kelas_id = kelas.id_kelas 
                                   WHERE jurnal_kelas.id_jurnal_kelas = $id");
    
    return mysqli_fetch_assoc($result);
}

/**
 * Insert jurnal kelas
 */
function insertJurnalKelas($tgl, $kelas_id, $keterangan) {
    global $conn;
    
    if (!validateDate($tgl)) {
        return ['success' => false, 'message' => 'Format tanggal tidak valid'];
    }
    
    if (empty($keterangan)) {
        return ['success' => false, 'message' => 'Keterangan tidak boleh kosong'];
    }
    
    $stmt = mysqli_prepare($conn, "INSERT INTO jurnal_kelas (tgl, kelas_id, keterangan) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'sis', $tgl, $kelas_id, $keterangan);
    
    if (mysqli_stmt_execute($stmt)) {
        return ['success' => true, 'message' => 'Jurnal berhasil ditambahkan'];
    } else {
        return ['success' => false, 'message' => 'Gagal menambahkan jurnal'];
    }
}

/**
 * Update jurnal kelas
 */
function updateJurnalKelas($id, $tgl, $kelas_id, $keterangan) {
    global $conn;
    
    $id = (int)$id;
    
    if (!validateDate($tgl)) {
        return ['success' => false, 'message' => 'Format tanggal tidak valid'];
    }
    
    if (empty($keterangan)) {
        return ['success' => false, 'message' => 'Keterangan tidak boleh kosong'];
    }
    
    $stmt = mysqli_prepare($conn, "UPDATE jurnal_kelas SET tgl = ?, kelas_id = ?, keterangan = ? WHERE id_jurnal_kelas = ?");
    mysqli_stmt_bind_param($stmt, 'sisi', $tgl, $kelas_id, $keterangan, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        return ['success' => true, 'message' => 'Jurnal berhasil diperbarui'];
    } else {
        return ['success' => false, 'message' => 'Gagal memperbarui jurnal'];
    }
}

/**
 * Delete jurnal kelas
 */
function deleteJurnalKelas($id) {
    global $conn;
    
    $id = (int)$id;
    $stmt = mysqli_prepare($conn, "DELETE FROM jurnal_kelas WHERE id_jurnal_kelas = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    
    if (mysqli_stmt_execute($stmt)) {
        return ['success' => true, 'message' => 'Jurnal berhasil dihapus'];
    } else {
        return ['success' => false, 'message' => 'Gagal menghapus jurnal'];
    }
}

/**
 * ================================
 * JURNAL GURU QUERIES
 * ================================
 */

/**
 * Get all jurnal guru with filters and pagination
 */
function getJurnalGuru($filters = [], $limit = 0, $offset = 0) {
    global $conn;
    
    $where = buildFilterQuery('jurnal_guru', $filters);
    $joins = "JOIN guru ON jurnal_guru.guru_id = guru.id_guru 
              JOIN kelas ON jurnal_guru.kelas_id = kelas.id_kelas";
    
    $query = "SELECT jurnal_guru.*, guru.nama_guru, kelas.nama_kelas 
              FROM jurnal_guru $joins 
              WHERE $where 
              ORDER BY jurnal_guru.tgl DESC";
    
    if ($limit > 0) {
        $query .= " LIMIT $limit OFFSET $offset";
    }
    
    return query($query);
}

/**
 * Get jurnal guru by ID
 */
function getJurnalGuruById($id) {
    global $conn;
    $id = (int)$id;
    
    $result = mysqli_query($conn, "SELECT jurnal_guru.*, guru.nama_guru, kelas.nama_kelas 
                                   FROM jurnal_guru 
                                   JOIN guru ON jurnal_guru.guru_id = guru.id_guru 
                                   JOIN kelas ON jurnal_guru.kelas_id = kelas.id_kelas 
                                   WHERE jurnal_guru.id_jurnal_guru = $id");
    
    return mysqli_fetch_assoc($result);
}

/**
 * Insert jurnal guru
 */
function insertJurnalGuru($tgl, $guru_id, $kelas_id, $keterangan) {
    global $conn;
    
    if (!validateDate($tgl)) {
        return ['success' => false, 'message' => 'Format tanggal tidak valid'];
    }
    
    if (empty($keterangan)) {
        return ['success' => false, 'message' => 'Keterangan tidak boleh kosong'];
    }
    
    $stmt = mysqli_prepare($conn, "INSERT INTO jurnal_guru (tgl, guru_id, kelas_id, keterangan) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'siis', $tgl, $guru_id, $kelas_id, $keterangan);
    
    if (mysqli_stmt_execute($stmt)) {
        return ['success' => true, 'message' => 'Jurnal berhasil ditambahkan'];
    } else {
        return ['success' => false, 'message' => 'Gagal menambahkan jurnal'];
    }
}

/**
 * Update jurnal guru
 */
function updateJurnalGuru($id, $tgl, $guru_id, $kelas_id, $keterangan) {
    global $conn;
    
    $id = (int)$id;
    
    if (!validateDate($tgl)) {
        return ['success' => false, 'message' => 'Format tanggal tidak valid'];
    }
    
    if (empty($keterangan)) {
        return ['success' => false, 'message' => 'Keterangan tidak boleh kosong'];
    }
    
    $stmt = mysqli_prepare($conn, "UPDATE jurnal_guru SET tgl = ?, guru_id = ?, kelas_id = ?, keterangan = ? WHERE id_jurnal_guru = ?");
    mysqli_stmt_bind_param($stmt, 'siisi', $tgl, $guru_id, $kelas_id, $keterangan, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        return ['success' => true, 'message' => 'Jurnal berhasil diperbarui'];
    } else {
        return ['success' => false, 'message' => 'Gagal memperbarui jurnal'];
    }
}

/**
 * Delete jurnal guru
 */
function deleteJurnalGuru($id) {
    global $conn;
    
    $id = (int)$id;
    $stmt = mysqli_prepare($conn, "DELETE FROM jurnal_guru WHERE id_jurnal_guru = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    
    if (mysqli_stmt_execute($stmt)) {
        return ['success' => true, 'message' => 'Jurnal berhasil dihapus'];
    } else {
        return ['success' => false, 'message' => 'Gagal menghapus jurnal'];
    }
}
?>
