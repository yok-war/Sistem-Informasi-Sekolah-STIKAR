<?php
include 'config.php';
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
?>
<?php include 'includes/header.php'?>
<div class="content">
    <h1 class="text-center text-danger">WILL SOON!</h1>
</div>
<?php include 'includes/footer.php'?>