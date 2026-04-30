<?php
include 'config.php';

if (isset($_POST['register'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = md5($_POST['password']);
    $confirm_password = md5($_POST['confirm_password']);
    $nama = htmlspecialchars($_POST['nama']);
    $email = htmlspecialchars($_POST['email']);

    // Validasi
    if ($password != $confirm_password) {
        $error = "Konfirmasi password tidak cocok!";
    } elseif (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE username='$username'")) > 0) {
        $error = "Username sudah digunakan!";
    } elseif (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE email='$email'")) > 0) {
        $error = "Email sudah digunakan!";
    } else {
        // Insert
        $query = "INSERT INTO users (username, password, nama, email) VALUES ('$username', '$password', '$nama', '$email')";
        if (mysqli_query($conn, $query)) {
            $success = "Registrasi berhasil! Silakan login.";
        } else {
            $error = "Registrasi gagal!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register SIS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center" style="height:100vh">

<div class="card p-4 shadow" style="width:400px">
<h4 class="text-center mb-3">Register SIS</h4>

<?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
<?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>

<form method="POST">
<input type="text" name="nama" class="form-control mb-3" placeholder="Nama Lengkap" required>
<input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
<input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
<input type="password" name="confirm_password" class="form-control mb-3" placeholder="Konfirmasi Password" required>
<button name="register" class="btn btn-primary w-100">Register</button>
</form>

<p class="text-center mt-3">Sudah punya akun? <a href="login.php">Login</a></p>
</div>

</body>
</html>