<?php
    include 'config.php';
    if (!isset($_SESSION['login'])) {
        header("Location: login.php");
        exit;
    }
    $title = "Data Kelas";
    $tblName = "dataKelas";
    $data_kelas = query("SELECT * FROM kelas JOIN jurusan ON kelas.jurusan_id = jurusan.id_jurusan ");
?>
<!-- HEADER -->
<?php include 'includes/header.php'; ?>
  
<div class="content">

    <!-- Header --> 
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-semibold mb-1">Data Kelas</h4>
            <small><?= count($data_kelas) ?> kelas terdaftar</small>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button type="button" class="btn btn-outline-warning bulk-edit-btn" data-checkbox=".select-kelas" data-edit-base="proses/edit_kelas_banyak.php?id=">
                <i class="bi bi-pencil-square"></i> Edit Terpilih
            </button>
            <button type="submit" form="bulkDeleteKelasForm" class="btn btn-outline-danger" onclick="return confirmBulkDelete('.select-kelas')">
                <i class="bi bi-trash"></i> Hapus Terpilih
            </button>
            <button class="btn btn-primary" type="button">
                <i class="bi bi-plus-lg"></i><a href="proses/tbh_kelas.php" class=" text-white"> Tambah Kelas</a>
            </button>
        </div>
    </div>

    <!-- Card Table -->
    <div class="card p-4">
        <form id="bulkDeleteKelasForm" method="post" action="proses/hps_kelas_banyak.php">
        <div class="table-responsive">
            <table id="<?= $tblName ?>" class="table align-middle">
                <thead>
                    <tr>
                        <th><input type="checkbox" class="form-check-input select-all" data-checkbox=".select-kelas"></th>
                        <th>No</th>
                        <th>Nama Kelas</th>
                        <th>Jurusan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php foreach ($data_kelas as $row): ?>
                        <tr>
                            <td><input type="checkbox" class="form-check-input select-kelas" name="selected_ids[]" value="<?= $row['id_kelas'] ?>"></td>
                            <td><?= $num++ ?></td>
                            <td><?= $row['nama_kelas'] ?></td>
                            <td><?= $row['nama_jurusan'] ?></td>
                            <td class="text-center action-btn">
                                <a href="proses/edit_kelas.php?id=<?= $row['id_kelas'] ?>">
                                    <i class="bi bi-pencil text-warning me-2"></i>
                                </a>
                                <a href="proses/hps_kelas.php?id=<?= $row['id_kelas'] ?>" onclick="confirm()">
                                    <i class="bi bi-trash text-danger"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
        </form>

    </div>

</div>

<!-- FOOTER -->
<?php include 'includes/footer.php'; ?>
