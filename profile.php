<?php
include 'config.php';
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Ambil data user (asumsikan admin id=1 atau berdasarkan session)
$user = query("SELECT * FROM users WHERE id=1")[0]; // Asumsikan admin tunggal

if (isset($_POST['update_profile'])) {
    $nama = htmlspecialchars($_POST['nama']);
    $email = htmlspecialchars($_POST['email']);

    $query = "UPDATE users SET nama='$nama', email='$email' WHERE id=1";
    mysqli_query($conn, $query);
    echo "<script>alert('Profil berhasil diupdate'); document.location.href='profile.php';</script>";
}

if (isset($_POST['change_password'])) {
    $old_pass = md5($_POST['old_password']);
    $new_pass = md5($_POST['new_password']);
    $confirm_pass = md5($_POST['confirm_password']);

    if ($old_pass != $user['password']) {
        echo "<script>alert('Password lama salah');</script>";
    } elseif ($new_pass != $confirm_pass) {
        echo "<script>alert('Konfirmasi password tidak cocok');</script>";
    } else {
        mysqli_query($conn, "UPDATE users SET password='$new_pass' WHERE id=1");
        echo "<script>alert('Password berhasil diubah'); document.location.href='profile.php';</script>";
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="content container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Profil Admin</h5>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" value="<?= $user['username'] ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" value="<?= $user['nama'] ?? '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= $user['email'] ?? '' ?>" required>
                        </div>
                        <button type="submit" name="update_profile" class="btn btn-primary">Update Profil</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Ganti Password</h5>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Password Lama</label>
                            <input type="password" name="old_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" name="change_password" class="btn btn-warning">Ganti Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>