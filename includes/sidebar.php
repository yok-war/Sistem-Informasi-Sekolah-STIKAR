<?php
include_once __DIR__ . '/../config.php';
?>
<div class="overlay" id="overlay" onclick="closeSidebar()"></div>
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <img src="assets/img/logo.png" alt="SMK TI Bali Global Karangasem" class="brand-logo">
        <div class="brand-text">
            <strong>SMK TI Bali Global</strong>
            <small>Karangasem</small>
        </div>
    </div>
    
    <!-- Dashboard Section -->
    <a href="<?= $base_url ?>dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>"><i class="bi bi-grid"></i> Dashboard</a>
    
    <!-- Siswa Section -->
    <a href="<?= $base_url ?>siswa.php" class="<?= $current_page == 'siswa.php' ? 'active' : '' ?>"><i class="bi bi-people"></i> Data Siswa</a>
    
    <!-- Kelas Section -->
    <a href="<?= $base_url ?>kelas.php" class="<?= $current_page == 'kelas.php' ? 'active' : '' ?>"><i class="bi bi-building"></i> Data Kelas</a>
    
    <!-- Jurusan Section -->
    <a href="<?= $base_url ?>jurusan.php" class="<?= $current_page == 'jurusan.php' ? 'active' : '' ?>"><i class="bi bi-book"></i> Data Jurusan</a>
    
    <!-- Guru Section -->
    <a href="<?= $base_url ?>guru.php" class="<?= $current_page == 'guru.php' ? 'active' : '' ?>"><i class="bi bi-person-badge"></i> Data Guru</a>
    
    <!-- Absensi Kelas Section -->
    <div class="sidebar-parent <?= ($current_page == 'absensi_kelas.php' || $current_page == 'jurnal_kelas.php') ? 'active' : '' ?>">
        <div class="sidebar-label">
            <i class="bi bi-bookmark"></i> Absensi Kelas
        </div>
        <ul class="sidebar-submenu">
            <li><a href="<?= $base_url ?>absensi_kelas.php" class="<?= $current_page == 'absensi_kelas.php' ? 'active' : '' ?>"><i class="bi bi-clipboard-check"></i> Absensi</a></li>
            <li><a href="<?= $base_url ?>jurnal_kelas.php" class="<?= $current_page == 'jurnal_kelas.php' ? 'active' : '' ?>"><i class="bi bi-journal-text"></i> Jurnal</a></li>
        </ul>
    </div>
    
    <!-- Absensi Guru Section -->
    <div class="sidebar-parent <?= ($current_page == 'absensi_guru.php' || $current_page == 'jurnal_guru.php') ? 'active' : '' ?>">
        <div class="sidebar-label">
            <i class="bi bi-bookmark"></i> Absensi Guru
        </div>
        <ul class="sidebar-submenu">
            <li><a href="<?= $base_url ?>absensi_guru.php" class="<?= $current_page == 'absensi_guru.php' ? 'active' : '' ?>"><i class="bi bi-clipboard-check"></i> Absensi</a></li>
            <li><a href="<?= $base_url ?>jurnal_guru.php" class="<?= $current_page == 'jurnal_guru.php' ? 'active' : '' ?>"><i class="bi bi-journal-text"></i> Jurnal</a></li>
        </ul>
    </div>
</div>