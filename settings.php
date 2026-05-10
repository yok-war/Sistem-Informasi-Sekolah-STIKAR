<?php
include 'config.php';
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Ambil pengaturan dari DB atau file, asumsikan ada tabel settings
$settings = query("SELECT * FROM settings WHERE id=1")[0] ?? ['theme' => 'light', 'notifications' => 1];

if (isset($_POST['update_settings'])) {
    $theme = $_POST['theme'];
    $notifications = isset($_POST['notifications']) ? 1 : 0;

    $query = "UPDATE settings SET theme='$theme', notifications='$notifications' WHERE id=1";
    mysqli_query($conn, $query);
    echo "<script>alert('Pengaturan berhasil disimpan'); document.location.href='settings.php';</script>";
}
?>

<?php include 'includes/header.php'; ?>

<script>
  try {
    const theme = '<?= $settings['theme'] === 'dark' ? 'dark' : 'light' ?>';
    localStorage.setItem('theme', theme);
    document.body.classList.toggle('dark-mode', theme === 'dark');
  } catch (e) {
    // ignore storage errors
  }
</script>

<div class="content container mt-4">
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Pengaturan</h5>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Tema Default</label>
                    <select name="theme" class="form-select">
                        <option value="light" <?= $settings['theme'] == 'light' ? 'selected' : '' ?>>Terang</option>
                        <option value="dark" <?= $settings['theme'] == 'dark' ? 'selected' : '' ?>>Gelap</option>
                    </select>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="notifications" class="form-check-input" id="notifications" <?= $settings['notifications'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="notifications">Aktifkan Notifikasi</label>
                </div>
                <button type="submit" name="update_settings" class="btn btn-primary">Simpan Pengaturan</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>