<?php
/**
 * HELPERS.PHP - Utility Functions for Absensi & Jurnal Module
 * Contains reusable functions for formatting, filtering, and data processing
 */

/**
 * Get status badge HTML with color coding
 * @param string $status - Status value (hadir, izin, sakit, alpha)
 * @return string - HTML badge element
 */
function getStatusBadge($status) {
    $statusMap = [
        'hadir' => ['class' => 'badge-hadir', 'label' => 'Hadir'],
        'izin' => ['class' => 'badge-izin', 'label' => 'Izin'],
        'sakit' => ['class' => 'badge-sakit', 'label' => 'Sakit'],
        'alpha' => ['class' => 'badge-alpha', 'label' => 'Alpha']
    ];
    
    $config = $statusMap[$status] ?? ['class' => 'badge-secondary', 'label' => 'Unknown'];
    return sprintf('<span class="badge %s">%s</span>', $config['class'], $config['label']);
}

/**
 * Get CSS class name for status
 * @param string $status - Status value
 * @return string - CSS class name
 */
function getStatusColor($status) {
    $colors = [
        'hadir' => 'badge-hadir',
        'izin' => 'badge-izin',
        'sakit' => 'badge-sakit',
        'alpha' => 'badge-alpha'
    ];
    return $colors[$status] ?? 'badge-secondary';
}

/**
 * Format tanggal Indonesia format
 * @param string $date - Date string (Y-m-d format)
 * @param string $format - Target format (default: d/m/Y)
 * @return string - Formatted date
 */
function formatDate($date, $format = 'd/m/Y') {
    if (empty($date)) return '-';
    try {
        $dateObj = new DateTime($date);
        return $dateObj->format($format);
    } catch (Exception $e) {
        return '-';
    }
}

/**
 * Truncate text for preview
 * @param string $text - Original text
 * @param int $length - Max length
 * @param string $suffix - Suffix if truncated (default: ...)
 * @return string - Truncated text
 */
function truncateText($text, $length = 50, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . $suffix;
}

/**
 * Build WHERE clause from filters
 * @param string $table - Table name
 * @param array $filters - Filter array
 * @return string - WHERE clause
 */
function buildFilterQuery($table, $filters = []) {
    global $conn;
    $conditions = [];
    
    // Date range filter - gunakan tgl bukan tanggal
    if (!empty($filters['date_start'])) {
        $date_start = mysqli_real_escape_string($conn, $filters['date_start']);
        $conditions[] = "`$table`.`tgl` >= '$date_start'";
    }
    
    if (!empty($filters['date_end'])) {
        $date_end = mysqli_real_escape_string($conn, $filters['date_end']);
        $conditions[] = "`$table`.`tgl` <= '$date_end'";
    }
    
    // Specific field filters
    if (!empty($filters['siswa_id'])) {
        $siswa_id = (int)$filters['siswa_id'];
        $conditions[] = "`$table`.`siswa_id` = $siswa_id";
    }
    
    if (!empty($filters['guru_id'])) {
        $guru_id = (int)$filters['guru_id'];
        $conditions[] = "`$table`.`guru_id` = $guru_id";
    }
    
    if (!empty($filters['kelas_id'])) {
        $kelas_id = (int)$filters['kelas_id'];
        $conditions[] = "`$table`.`kelas_id` = $kelas_id";
    }
    
    // Search filter
    if (!empty($filters['search'])) {
        $search = mysqli_real_escape_string($conn, $filters['search']);
        
        if ($table === 'absensi_kelas') {
            $conditions[] = "(siswa.nama_siswa LIKE '%$search%' OR siswa.nis_siswa LIKE '%$search%')";
        } elseif ($table === 'absensi_guru') {
            $conditions[] = "(guru.nama_guru LIKE '%$search%')";
        } elseif ($table === 'jurnal_kelas') {
            $conditions[] = "`$table`.keterangan LIKE '%$search%'";
        } elseif ($table === 'jurnal_guru') {
            $conditions[] = "`$table`.keterangan LIKE '%$search%'";
        }
    }
    
    return count($conditions) > 0 ? implode(' AND ', $conditions) : '1=1';
}

/**
 * Count total records based on filters
 * @param string $table - Table name
 * @param string $where - WHERE clause
 * @param string $joins - Additional JOIN clause
 * @return int - Total records
 */
function countRecords($table, $where = '1=1', $joins = '') {
    global $conn;
    
    $query = "SELECT COUNT(*) as total FROM `$table` $joins WHERE $where";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        return 0;
    }
    
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}

/**
 * Validate date format
 * @param string $date - Date string
 * @param string $format - Expected format (default: Y-m-d)
 * @return bool - True if valid
 */
function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

/**
 * Check if record already exists (prevent duplicate)
 * @param string $table - Table name
 * @param array $conditions - Array of column => value
 * @return bool - True if record exists
 */
function recordExists($table, $conditions = []) {
    global $conn;
    
    if (empty($conditions)) return false;
    
    $where = [];
    foreach ($conditions as $column => $value) {
        $value = mysqli_real_escape_string($conn, $value);
        $where[] = "`$column` = '$value'";
    }
    
    $query = "SELECT COUNT(*) as count FROM `$table` WHERE " . implode(' AND ', $where);
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        return false;
    }
    
    $row = mysqli_fetch_assoc($result);
    return $row['count'] > 0;
}

/**
 * Get status statistics
 * @param string $table - Table name
 * @param string $where - WHERE clause
 * @return array - Array with counts per status
 */
function getStatusStats($table, $where = '1=1') {
    global $conn;
    
    $query = "SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) as hadir,
        SUM(CASE WHEN status = 'izin' THEN 1 ELSE 0 END) as izin,
        SUM(CASE WHEN status = 'sakit' THEN 1 ELSE 0 END) as sakit,
        SUM(CASE WHEN status = 'alpha' THEN 1 ELSE 0 END) as alpha
        FROM `$table` WHERE $where";
    
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        return ['total' => 0, 'hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0];
    }
    
    return mysqli_fetch_assoc($result);
}

/**
 * Sanitize input
 * @param mixed $input - Input data
 * @return mixed - Sanitized data
 */
function sanitize($input) {
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}
?>
