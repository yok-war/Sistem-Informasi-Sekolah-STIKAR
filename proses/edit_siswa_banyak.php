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
        alert('Pilih minimal satu siswa');
        document.location.href = '../siswa.php';
    </script>";
    exit;
}

$kelas = query("SELECT kelas.*, jurusan.nama_jurusan
                FROM kelas
                LEFT JOIN jurusan ON kelas.jurusan_id = jurusan.id_jurusan
                ORDER BY kelas.nama_kelas ASC");

// Get selected students data
$idsList = implode(',', $selectedIds);
$siswa = query("SELECT siswa.*, kelas.nama_kelas 
                FROM siswa 
                LEFT JOIN kelas ON siswa.kelas_id = kelas.id_kelas 
                WHERE siswa.id_siswa IN (" . $idsList . ")");

if (isset($_POST['submit_bulk'])) {
    $updated = 0;
    
    // Handle individual field updates per student
    if (isset($_POST['siswa_data'])) {
        foreach ($_POST['siswa_data'] as $id => $data) {
            $id = (int)$id;
            $nis = htmlspecialchars($data['nis']);
            $nisn = htmlspecialchars($data['nisn']);
            $nama = htmlspecialchars($data['nama']);
            $ttl = htmlspecialchars($data['ttl']);
            $alamat = htmlspecialchars($data['alamat']);
            $wa = htmlspecialchars($data['wa']);
            $kelas_id = !empty($data['kelas']) ? (int)$data['kelas'] : null;
            
            // Handle foto upload
            $foto_siswa = null;
            if (!empty($_FILES['siswa_data']['name'][$id]['foto'])) {
                $file = $_FILES['siswa_data']['tmp_name'][$id]['foto'];
                $fileName = $_FILES['siswa_data']['name'][$id]['foto'];
                $fileError = $_FILES['siswa_data']['error'][$id]['foto'];
                
                if ($fileError === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                    
                    if (in_array($ext, $allowed)) {
                        $newFileName = 'siswa_' . $id . '_' . time() . '.' . $ext;
                        $uploadPath = '../assets/img/' . $newFileName;
                        
                        if (move_uploaded_file($file, $uploadPath)) {
                            $foto_siswa = $newFileName;
                        }
                    }
                }
            }
            
            $setClauses = [];
            $params = [];
            $types = '';
            
            if (!empty($nis)) {
                $setClauses[] = "nis_siswa = ?";
                $params[] = &$nis;
                $types .= 's';
            }
            if (!empty($nisn)) {
                $setClauses[] = "nisn_siswa = ?";
                $params[] = &$nisn;
                $types .= 's';
            }
            if (!empty($nama)) {
                $setClauses[] = "nama_siswa = ?";
                $params[] = &$nama;
                $types .= 's';
            }
            if (!empty($ttl)) {
                $setClauses[] = "ttl_siswa = ?";
                $params[] = &$ttl;
                $types .= 's';
            }
            if (!empty($alamat)) {
                $setClauses[] = "alamat_siswa = ?";
                $params[] = &$alamat;
                $types .= 's';
            }
            if (!empty($wa)) {
                $setClauses[] = "wa_siswa = ?";
                $params[] = &$wa;
                $types .= 's';
            }
            if ($kelas_id !== null) {
                $setClauses[] = "kelas_id = ?";
                $params[] = &$kelas_id;
                $types .= 'i';
            }
            if ($foto_siswa !== null) {
                $setClauses[] = "foto_siswa = ?";
                $params[] = &$foto_siswa;
                $types .= 's';
            }
            
            if (!empty($setClauses)) {
                $params[] = &$id;
                $types .= 'i';
                
                $sql = "UPDATE siswa SET " . implode(', ', $setClauses) . " WHERE id_siswa = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, $types, ...$params);
                mysqli_stmt_execute($stmt);
                $updated += mysqli_affected_rows($conn);
            }
        }
    }
    
    if ($updated > 0) {
        echo "<script>
            alert('$updated data siswa berhasil diperbarui');
            document.location.href = '../siswa.php';
        </script>";
    } else {
        echo "<script>
            alert('Tidak ada data siswa yang berubah');
        </script>";
    }

    
    if ($updated > 0) {
        echo "<script>
            alert('Data siswa terpilih berhasil diperbarui');
            document.location.href = '../siswa.php';
        </script>";
    } else {
        echo "<script>
            alert('Tidak ada data siswa yang berubah');
        </script>";
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="content container mt-4">
    <div class="card shadow-sm mt-4">
        <div class="card-header">
            <h5 class="mb-0">Edit Banyak Siswa (<?= count($siswa) ?> data)</h5>
        </div>
        <div class="card-body">
            <p class="text-secondary mb-4"><?= count($siswa) ?> siswa dipilih. Ubah data yang ingin diperbarui untuk setiap siswa.</p>

            <form method="post" enctype="multipart/form-data">
                <?php foreach ($selectedIds as $id) : ?>
                    <input type="hidden" name="selected_ids[]" value="<?= $id ?>">
                <?php endforeach; ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>NIS</th>
                                <th>NISN</th>
                                <th>Nama</th>
                                <th>TTL</th>
                                <th>Alamat</th>
                                <th>WA</th>
                                <th>Kelas</th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($siswa as $row) : ?>
                                <tr>
                                    <td>
                                        <input type="text" name="siswa_data[<?= $row['id_siswa'] ?>][nis]" class="form-control form-control-sm" value="<?= $row['nis_siswa'] ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="siswa_data[<?= $row['id_siswa'] ?>][nisn]" class="form-control form-control-sm" value="<?= $row['nisn_siswa'] ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="siswa_data[<?= $row['id_siswa'] ?>][nama]" class="form-control form-control-sm" value="<?= $row['nama_siswa'] ?>">
                                    </td>
                                    <td>
                                        <input type="date" name="siswa_data[<?= $row['id_siswa'] ?>][ttl]" class="form-control form-control-sm" value="<?= $row['ttl_siswa'] ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="siswa_data[<?= $row['id_siswa'] ?>][alamat]" class="form-control form-control-sm" value="<?= $row['alamat_siswa'] ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="siswa_data[<?= $row['id_siswa'] ?>][wa]" class="form-control form-control-sm" value="<?= $row['wa_siswa'] ?>">
                                    </td>
                                    <td>
                                        <select name="siswa_data[<?= $row['id_siswa'] ?>][kelas]" class="form-select form-select-sm">
                                            <option value="<?= $row['kelas_id'] ?>"><?= $row['nama_kelas'] ?? 'Pilih Kelas' ?></option>
                                            <?php foreach ($kelas as $kls) : ?>
                                                <option value="<?= $kls['id_kelas'] ?>">
                                                    <?= $kls['nama_kelas'] ?><?= !empty($kls['nama_jurusan']) ? ' - ' . $kls['nama_jurusan'] : '' ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-2">
                                            <?php if (!empty($row['foto_siswa'])) : ?>
                                                <img src="../assets/img/<?= htmlspecialchars($row['foto_siswa']); ?>" alt="Foto" class="img-thumbnail" style="width: 60px; height: 70px; object-fit: cover;">
                                            <?php else : ?>
                                                <span class="text-muted small">Belum ada foto</span>
                                            <?php endif; ?>
                                            <input type="file" name="siswa_data[<?= $row['id_siswa'] ?>][foto]" class="form-control form-control-sm" accept="image/*">
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="modal-footer mt-4">
                    <a href="../siswa.php" class="me-3">
                        <button type="button" class="btn btn-secondary">Batal</button>
                    </a>
                    <button type="submit" name="submit_bulk" class="btn btn-primary me-3">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
