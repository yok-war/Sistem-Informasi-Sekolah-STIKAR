<?php
include 'config.php';
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
$title = "Data Jurusan";
$tblName = "datajurusan";
$data_jurusan = query("SELECT * FROM jurusan");

?>
<!-- HEADER -->
<?php include 'includes/header.php'; ?>

<div class="content">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-semibold mb-1">Data Jurusan</h4>
            <small><?= count($data_jurusan) ?> Jurusan terdaftar</small>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button type="button" class="btn btn-outline-warning bulk-edit-btn" data-checkbox=".select-jurusan" data-edit-base="proses/edit_jurusan.php?id=">
                <i class="bi bi-pencil-square"></i> Edit Terpilih
            </button>
            <button type="submit" form="bulkDeleteJurusanForm" class="btn btn-outline-danger" onclick="return confirmBulkDelete('.select-jurusan')">
                <i class="bi bi-trash"></i> Hapus Terpilih
            </button>
            <button class="btn btn-primary" type="button">
                <i class="bi bi-plus-lg"></i> <a href="proses/tbh_jurusan.php" class="text-white"> Tambah Jurusan</a>
            </button>
        </div>
    </div>

    <!-- Card Table -->
    <div class="card p-4">
        <form id="bulkDeleteJurusanForm" method="post" action="proses/hps_jurusan_banyak.php">
        <div class="table-responsive">
            <table id="<?= $tblName ?>" class="table align-middle">
                <thead>
                    <tr>
                        <th><input type="checkbox" class="form-check-input select-all" data-checkbox=".select-jurusan"></th>
                        <th>No</th>
                        <th>Nama Jurusan</th>
                        <th>Jumlah Siswa</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php foreach ($data_jurusan as $row): ?>
                        <?php $siswa_jurusan = query("SELECT nama_siswa, nama_kelas, nama_jurusan FROM `siswa` 
                                INNER JOIN `kelas` 
                                ON siswa.kelas_id = kelas.id_kelas
                                INNER JOIN jurusan
                                ON kelas.jurusan_id = jurusan.id_jurusan
                                WHERE nama_jurusan = '$row[nama_jurusan]'
                                "); ?>
                        <tr>
                            <td><input type="checkbox" class="form-check-input select-jurusan" name="selected_ids[]" value="<?= $row['id_jurusan'] ?>"></td>
                            <td><?= $num++ ?></td>
                            <td><?= $row['nama_jurusan'] ?></td>
                            <td><?= count($siswa_jurusan); ?></td>
                            <td class="text-center action-btn">
                                <a href="proses/edit_jurusan.php?id=<?= $row['id_jurusan'] ?>">
                                    <i class="bi bi-pencil text-warning me-2"></i>
                                </a>
                                <a href="proses/hps_jurusan.php?id=<?= $row['id_jurusan'] ?>">
                                    <i class="bi bi-trash text-danger"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <?php $siswa_jurusan = query("SELECT nama_siswa, nama_kelas, nama_jurusan FROM `siswa` 
                                INNER JOIN `kelas` 
                                ON siswa.kelas_id = kelas.id_kelas
                                INNER JOIN jurusan
                                ON kelas.jurusan_id = jurusan.id_jurusan
                                "); ?>
                        <th>Jumlah Total: </th>
                        <th><?= count($data_jurusan) ?></th>
                        <th><?= count($siswa_jurusan) ?></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        </form>
    </div>

</div>

<!-- FOOTER -->
<?php include 'includes/footer.php'; ?>
