<?php
include 'config.php';
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
$title = "Data Siswa";
$tblName = "dataSiswa";
$siswa = query("SELECT * FROM siswa JOIN kelas ON siswa.kelas_id = kelas.id_kelas JOIN jurusan ON kelas.jurusan_id = jurusan.id_jurusan ORDER BY id_siswa");
?>
<!-- HEADER -->
<?php include 'includes/header.php'; ?>


<div class="content">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-semibold mb-1">Data Siswa</h4>
            <small><?= count($siswa); ?> siswa terdaftar</small>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button type="button" class="btn btn-outline-warning bulk-edit-btn" data-checkbox=".select-siswa" data-edit-base="proses/edit_siswa_banyak.php?id=">
                <i class="bi bi-pencil-square"></i> Edit Terpilih
            </button>
            <button type="submit" form="bulkDeleteSiswaForm" class="btn btn-outline-danger" onclick="return confirmBulkDelete('.select-siswa')">
                <i class="bi bi-trash"></i> Hapus Terpilih
            </button>
            <a href="proses/tbh_siswa.php" class="text-white">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalSiswa">
                    <i class="bi bi-plus-lg"></i> Tambah Siswa
                </button>
            </a>
        </div>
    </div>

    <!-- Card Table -->
    <div class="card p-4">
        <form id="bulkDeleteSiswaForm" method="post" action="proses/hps_siswa_banyak.php">
        <div class="table-responsive">
            <table id="<?= $tblName ?>" class="table align-middle">
                <thead>
                    <tr>
                        <th><input type="checkbox" class="form-check-input select-all" data-checkbox=".select-siswa"></th>
                        <th>No</th>
                        <th>NISN</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>TTL</th>
                        <th>Alamat</th>
                        <th>WA</th>
                        <th>Foto</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php foreach ($siswa as $row) : ?>
                        <tr>
                            <td><input type="checkbox" class="form-check-input select-siswa" name="selected_ids[]" value="<?= $row['id_siswa'] ?>"></td>
                            <td><?= $num++ ?></td>
                            <td><?= $row['nisn_siswa'] ?></td>
                            <td><?= $row['nis_siswa'] ?></td>
                            <td><?= $row['nama_siswa'] ?></td>
                            <td><?= $row['ttl_siswa'] ?></td>
                            <td><?= $row['alamat_siswa'] ?></td>
                            <td><?= $row['wa_siswa'] ?></td>
                            <td>
                                <a href="assets/img/new/<?= $row['foto_siswa'] ?>">
                                    <img src="assets/img/new/<?= $row['foto_siswa'] ?>" alt="foto siswa" height="50px">
                                </a>
                            </td>
                            <td><?= $row['nama_kelas'] ?></td>
                            <td><?= $row['nama_jurusan'] ?></td>
                            <td class="text-center action-btn">
                                <a href="proses/edit_siswa.php?id=<?= $row['id_siswa'] ?>">
                                    <i class="bi bi-pencil text-warning me-2"></i>
                                </a>
                                <a href="proses/hps_siswa.php?id=<?= $row['id_siswa'] ?>">

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
<!-- FOOTER -->
<?php include 'includes/footer.php'; ?>
