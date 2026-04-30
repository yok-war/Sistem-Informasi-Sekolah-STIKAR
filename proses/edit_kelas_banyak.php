<?php
include '../config.php';

// Support both GET (from bulk edit button) and POST (from form submission)
$selectedIds = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    // Coming from bulk edit button - IDs in URL parameter
    $idsParam = $_GET['id'];
    $selectedIds = array_filter(explode(',', $idsParam));
    $selectedIds = array_map('intval', $selectedIds);
    $selectedIds = array_filter($selectedIds);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_ids'])) {
    // Coming from form submission
    $selectedIds = array_map('intval', $_POST['selected_ids']);
    $selectedIds = array_filter($selectedIds);
}

if (empty($selectedIds)) {
    echo "<script>
        alert('Pilih minimal satu kelas');
        document.location.href = '../kelas.php';
    </script>";
    exit;
}

$jurusan = query("SELECT * FROM jurusan ORDER BY nama_jurusan ASC");

// Get selected kelas data
$idsList = implode(',', $selectedIds);
$kelas = query("SELECT kelas.*, jurusan.nama_jurusan 
                FROM kelas 
                LEFT JOIN jurusan ON kelas.jurusan_id = jurusan.id_jurusan 
                WHERE kelas.id_kelas IN (" . $idsList . ")");

if (isset($_POST['submit_bulk'])) {
    $updated = 0;
    
    // Handle individual field updates per kelas
    if (isset($_POST['kelas_data'])) {
        foreach ($_POST['kelas_data'] as $id => $data) {
            $id = (int)$id;
            $nama = htmlspecialchars($data['nama']);
            $jurusan_id = !empty($data['jurusan']) ? (int)$data['jurusan'] : null;
            
            $setClauses = [];
            $params = [];
            $types = '';
            
            if (!empty($nama)) {
                $setClauses[] = "nama_kelas = ?";
                $params[] = &$nama;
                $types .= 's';
            }
            if ($jurusan_id !== null) {
                $setClauses[] = "jurusan_id = ?";
                $params[] = &$jurusan_id;
                $types .= 'i';
            }
            if (!empty($setClauses)) {
                $params[] = &$id;
                $types .= 'i';
                
                $sql = "UPDATE kelas SET " . implode(', ', $setClauses) . " WHERE id_kelas = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, $types, ...$params);
                mysqli_stmt_execute($stmt);
                $updated += mysqli_affected_rows($conn);
            }
        }
    }
    
    if ($updated > 0) {
        echo "<script>
            alert('$updated data kelas berhasil diperbarui');
            document.location.href = '../kelas.php';
        </script>";
    } else {
        echo "<script>
            alert('Tidak ada data kelas yang berubah');
        </script>";
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="content container mt-4">
    <div class="card shadow-sm mt-4">
        <div class="card-header">
            <h5 class="mb-0">Edit Banyak Kelas (<?= count($kelas) ?> data)</h5>
        </div>
        <div class="card-body">
            <p class="text-secondary mb-4"><?= count($kelas) ?> kelas dipilih. Ubah data yang ingin diperbarui untuk setiap kelas.</p>

            <form method="post">
                <?php foreach ($selectedIds as $id) : ?>
                    <input type="hidden" name="selected_ids[]" value="<?= $id ?>">
                <?php endforeach; ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Kelas</th>
                                <th>Jurusan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($kelas as $row) : ?>
                                <tr>
                                    <td>
                                        <input type="text" name="kelas_data[<?= $row['id_kelas'] ?>][nama]" class="form-control form-control-sm" value="<?= $row['nama_kelas'] ?>">
                                    </td>
                                    <td>
                                        <select name="kelas_data[<?= $row['id_kelas'] ?>][jurusan]" class="form-select form-select-sm">
                                            <option value="<?= $row['jurusan_id'] ?>"><?= $row['nama_jurusan'] ?? 'Pilih Jurusan' ?></option>
                                            <?php foreach ($jurusan as $jr) : ?>
                                                <option value="<?= $jr['id_jurusan'] ?>"><?= $jr['nama_jurusan'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="modal-footer mt-4">
                    <a href="../kelas.php" class="me-3">
                        <button type="button" class="btn btn-secondary">Batal</button>
                    </a>
                    <button type="submit" name="submit_bulk" class="btn btn-primary me-3">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>