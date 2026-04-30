<?php
    include "config.php";
    if (!isset($_SESSION['login'])) {
        header("Location: login.php");
        exit;
    }
    $title = "Data Guru";
    $tblName = "dataguru";

    $guru = query("SELECT * FROM guru");
?>
<?php include 'includes/header.php'?>
    <div class="content">
          <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-semibold mb-1">Data Guru</h4>
            <small><?= count($guru); ?> guru terdaftar</small>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button type="button" class="btn btn-outline-warning bulk-edit-btn" data-checkbox=".select-guru" data-edit-base="proses/edit_guru_banyak.php?id=">
                <i class="bi bi-pencil-square"></i> Edit Terpilih
            </button>
            <button type="submit" form="bulkDeleteGuruForm" class="btn btn-outline-danger" onclick="return confirmBulkDelete('.select-guru')">
                <i class="bi bi-trash"></i> Hapus Terpilih
            </button>
            <a href="proses/tbh_guru.php" class="text-white">
                <button type="button" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> Tambah Guru
                </button>
            </a>
        </div>
    </div>

    <!-- Card Table -->
    <div class="card p-4">
        <form id="bulkDeleteGuruForm" method="post" action="proses/hps_guru_banyak.php">
        <div class="table-responsive">
            <table id="<?= $tblName ?>" class="table align-middle">
                <thead>
                    <tr>
                        <th><input type="checkbox" class="form-check-input select-all" data-checkbox=".select-guru"></th>
                        <th>No</th>
                        <th>Nama</th>
                        <th>TTL</th>
                        <th>Alamat</th>
                        <th>WA</th>
                        <th>Foto</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php foreach ($guru as $row) : ?>
                        <tr>
                            <td><input type="checkbox" class="form-check-input select-guru" name="selected_ids[]" value="<?= $row['id_guru'] ?>"></td>
                            <td><?= $num++ ?></td>
                            <td><?= $row['nama_guru'] ?></td>
                            <td><?= $row['ttl_guru'] ?></td>
                            <td><?= $row['alamat_guru'] ?></td>
                            <td><?= $row['wa_guru'] ?></td>
                            <td>
                                <a href="assets/img/new/<?= $row['foto_guru'] ?>">
                                    <img src="assets/img/new/<?= $row['foto_guru'] ?>" alt="foto guru" height="50px">
                                </a>
                            </td>
                            <td class="text-center action-btn">
                                <a href="proses/edit_guru.php?id=<?= $row['id_guru'] ?>">
                                    <i class="bi bi-pencil text-warning me-2"></i>
                                </a>
                                <a href="proses/hps_guru.php?id=<?= $row['id_guru'] ?>">

                                    <i class="bi bi-trash text-danger"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        </form>
    </div>
    </div>
<?php include 'includes/footer.php'?>
