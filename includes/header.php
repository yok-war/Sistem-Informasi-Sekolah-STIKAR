<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistem Informasi Sekolah</title>
  <!-- Bootstrap CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- Style CSS -->
  <link rel="stylesheet" href="/sis/css/style.css">
  
</head>

<body class="dark-mode">
  <script>
    (function () {
      try {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'light') {
          document.body.classList.remove('dark-mode');
        } else if (savedTheme === 'dark') {
          document.body.classList.add('dark-mode');
        }
      } catch (e) {
        // ignore storage errors
      }
    })();
  </script>
  <!-- ===== MOBILE NAVBAR ===== -->
  <?php include 'mobile-navbar.php'; ?>

  <!-- ===== SIDEBAR ===== -->
  <?php include 'sidebar.php'; ?>
  <!-- ===== CONTENT ===== -->
    <div class="main-content">
      <?php include 'topbar.php'; ?>
