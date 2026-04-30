</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css">
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>

<script>
$(document).ready(function() {
    const tableId = "<?= isset($tblName) ? $tblName : '' ?>";

    if (tableId && document.getElementById(tableId)) {
        new DataTable('#' + tableId, {
        }); 
    }
});

function confirm() {
    alert('yakin data dihapus?');
    if(true) {
        window.location.href = "proses/hps_kelas.php";
    }
}
</script>

<!-- Java Script -->
<script src="/sis/js/script.js"></script>

<script>
window.dashboardChartData = {
    jurusan: <?= isset($jurusan) ? json_encode($jurusan) : '[]' ?>,
    siswaPerTahun: <?= isset($siswaPerTahun) ? json_encode($siswaPerTahun) : '{}' ?>
};
</script>

<script src="/sis/js/chart.js"></script>
</body>
</html>