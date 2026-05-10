<div class="mobile-navbar">

    <button class="btn" onclick="toggleSidebar()">
        <i class="bi bi-list fs-3"></i>
    </button>

    <div class="mobile-user-meta">
        <span class="mobile-user-role">Admin</span>
        <span class="mobile-user-name"><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></span>
    </div>

    <div class="dropdown">
        <button class="btn p-0" type="button" id="mobileAccountMenu" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle fs-4"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="mobileAccountMenu">
            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
            <li><a class="dropdown-item" href="settings.php">Settings</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="<?= isset($base_url) ? $base_url . 'logout.php' : '/sis/logout.php' ?>">Logout</a></li>
        </ul>
    </div>

</div>