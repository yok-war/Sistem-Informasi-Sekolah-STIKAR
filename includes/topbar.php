<div class="topbar">

    <!-- LEFT -->
    <div class="topbar-left">
        <h4>Dashboard</h4>
        <small>
            <?php echo date('l, d F Y'); ?>
        </small>
    </div>

    <!-- CENTER -->
    <div class="search-box">
        <i class="bi bi-search"></i>
        <input type="text" id="searchInput" placeholder="Cari sesuatu...">
    </div>

    <!-- RIGHT -->
    <div class="topbar-right">

        <!-- Theme Toggle -->
         <div  onclick="toggleDarkMode()" class="">
             <i class="bi bi-moon fs-5" id="darkIconDesktop"></i>
         </div>

        <!-- Profile Dropdown -->
        <div class="dropdown">
            <div class="profile-box" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle fs-5"></i>
                <p class="fw-1 m-0">Admin</p>
            </div>

            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item text-danger" href="p/logout.php">Logout</a></li>
            </ul>
        </div>

    </div>

</div>