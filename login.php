<?php
include 'config.php';

if(isset($_POST['login'])){
    $username = trim($_POST['username'] ?? '');
    $password = md5($_POST['password'] ?? '');

    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    if(mysqli_num_rows($cek) > 0){
        $_SESSION['login'] = true;
        $_SESSION['username'] = $username;
        $success = 'Login berhasil! Anda akan diarahkan ke Dashboard dalam beberapa detik.';
    } else {
        $error = 'Username atau Password salah. Silakan periksa kembali username dan password Anda.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login SIS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/sis/css/style.css">
</head>
<body class="bg-light d-flex justify-content-center align-items-center" style="min-height:100vh">

<div class="card rounded-4 shadow-lg p-4" style="width:100%; max-width:420px;">
  <div class="text-center mb-4">
    <img src="assets/img/logo.png" alt="Logo" style="width:64px; height:64px; object-fit:contain;">
    <h4 class="mt-3 fw-bold">SIS Login</h4>
    <p class="text-secondary">Masuk untuk mengelola data absensi, jurnal, dan statistik sekolah.</p>
  </div>

  <?php if(!empty($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>Login gagal!</strong> <?= htmlspecialchars($error) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <?php if(!empty($success)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="loginSuccessAlert">
      <strong>Berhasil!</strong> <?= htmlspecialchars($success) ?>
    </div>
  <?php endif; ?>

  <form method="POST" autocomplete="off">
    <div class="mb-3">
      <label class="form-label">Username</label>
      <input type="text" name="username" class="form-control" placeholder="Username" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" placeholder="Password" required>
    </div>
    <button name="login" class="btn btn-primary w-100">Masuk</button>
  </form>

  <p class="text-center text-muted mt-4 mb-0">Belum punya akun? <a href="register.php">Daftar sekarang</a></p>
</div>

<?php if(!empty($success)): ?>
<script>
  setTimeout(function() {
    window.location.href = 'dashboard.php';
  }, 2200);
</script>
<?php endif; ?>

</body>
</html>