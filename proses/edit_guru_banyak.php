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
        alert('Pilih minimal satu guru');
        document.location.href = '../guru.php';
    </script>";
    exit;
}

// Get selected guru data
$idsList = implode(',', $selectedIds);
$guru = query("SELECT * FROM guru WHERE id_guru IN (" . $idsList . ")");

if (isset($_POST['submit_bulk'])) {
    $updated = 0;
    
    // Handle individual field updates per guru
    if (isset($_POST['guru_data'])) {
        foreach ($_POST['guru_data'] as $id => $data) {
            $id = (int)$id;
            $nip = htmlspecialchars($data['nip']);
            $nama = htmlspecialchars($data['nama']);
            $ttl = htmlspecialchars($data['ttl']);
            $alamat = htmlspecialchars($data['alamat']);
            $wa = htmlspecialchars($data['wa']);
            
            // Handle foto upload
            $foto_guru = null;
            if (!empty($_FILES['guru_data']['name'][$id]['foto'])) {
                $file = $_FILES['guru_data']['tmp_name'][$id]['foto'];
                $fileName = $_FILES['guru_data']['name'][$id]['foto'];
                $fileError = $_FILES['guru_data']['error'][$id]['foto'];
                
                if ($fileError === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                    
                    if (in_array($ext, $allowed)) {
                        $newFileName = 'guru_' . $id . '_' . time() . '.' . $ext;
                        $uploadPath = '../assets/img/' . $newFileName;
                        
                        if (move_uploaded_file($file, $uploadPath)) {
                            $foto_guru = $newFileName;
                        }
                    }
                }
            }
            
            $setClauses = [];
            $params = [];
            $types = '';
            
            if (!empty($nip)) {
                $setClauses[] = "nip_guru = ?";
                $params[] = &$nip;
                $types .= 's';
            }
            if (!empty($nama)) {
                $setClauses[] = "nama_guru = ?";
                $params[] = &$nama;
                $types .= 's';
            }
            if (!empty($ttl)) {
                $setClauses[] = "ttl_guru = ?";
                $params[] = &$ttl;
                $types .= 's';
            }
            if (!empty($alamat)) {
                $setClauses[] = "alamat_guru = ?";
                $params[] = &$alamat;
                $types .= 's';
            }
            if (!empty($wa)) {
                $setClauses[] = "wa_guru = ?";
                $params[] = &$wa;
                $types .= 's';
            }
            if ($foto_guru !== null) {
                $setClauses[] = "foto_guru = ?";
                $params[] = &$foto_guru;
                $types .= 's';
            }
            
            if (!empty($setClauses)) {
                $params[] = &$id;
                $types .= 'i';
                
                $sql = "UPDATE guru SET " . implode(', ', $setClauses) . " WHERE id_guru = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, $types, ...$params);
                mysqli_stmt_execute($stmt);
                $updated += mysqli_affected_rows($conn);
            }
        }
    }
    
    if ($updated > 0) {
        echo "<script>
            alert('$updated data guru berhasil diperbarui');
            document.location.href = '../guru.php';
        </script>";
    } else {
        echo "<script>
            alert('Tidak ada data guru yang berubah');
        </script>";
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="content container mt-4">
    <div class="card shadow-sm mt-4">
        <div class="card-header">
            <h5 class="mb-0">Edit Banyak Guru (<?= count($guru) ?> data)</h5>
        </div>
        <div class="card-body">
            <p class="text-secondary mb-4"><?= count($guru) ?> guru dipilih. Ubah data yang ingin diperbarui untuk setiap guru.</p>

            <form method="post" enctype="multipart/form-data">
                <?php foreach ($selectedIds as $id) : ?>
                    <input type="hidden" name="selected_ids[]" value="<?= $id ?>">
                <?php endforeach; ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <!-- <th>NIP</th> -->
                                <th>Nama</th>
                                <th>TTL</th>
                                <th>Alamat</th>
                                <th>WA</th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($guru as $row) : ?>
                                <tr>
                                    <!-- <td>
                                        <input type="text" name="guru_data[<?= $row['id_guru'] ?>][nip]" class="form-control form-control-sm" value="<?= $row['nip_guru'] ?>">
                                    </td> -->
                                    <td>
                                        <input type="text" name="guru_data[<?= $row['id_guru'] ?>][nama]" class="form-control form-control-sm" value="<?= $row['nama_guru'] ?>">
                                    </td>
                                    <td>
                                        <input type="date" name="guru_data[<?= $row['id_guru'] ?>][ttl]" class="form-control form-control-sm" value="<?= $row['ttl_guru'] ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="guru_data[<?= $row['id_guru'] ?>][alamat]" class="form-control form-control-sm" value="<?= $row['alamat_guru'] ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="guru_data[<?= $row['id_guru'] ?>][wa]" class="form-control form-control-sm" value="<?= $row['wa_guru'] ?>">
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-2">
                                            <?php if (!empty($row['foto_guru'])) : ?>
                                                <img src="../assets/img/<?= htmlspecialchars($row['foto_guru']); ?>" alt="Foto" class="img-thumbnail" style="width: 60px; height: 70px; object-fit: cover;">
                                            <?php else : ?>
                                                <span class="text-muted small">Belum ada foto</span>
                                            <?php endif; ?>
                                            <input type="file" name="guru_data[<?= $row['id_guru'] ?>][foto]" class="form-control form-control-sm" accept="image/*">
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="modal-footer mt-4">
                    <a href="../guru.php" class="me-3">
                        <button type="button" class="btn btn-secondary">Batal</button>
                    </a>
                    <button type="submit" name="submit_bulk" class="btn btn-primary me-3">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>