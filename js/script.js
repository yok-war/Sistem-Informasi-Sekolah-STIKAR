    // Toogle Sidebar
    function toggleSidebar() {
      const sidebar = document.getElementById("sidebar");
      const overlay = document.getElementById("overlay");

      sidebar.classList.toggle("show");
      overlay.classList.toggle("show");
    }

    function closeSidebar() {
      document.getElementById("sidebar").classList.remove("show");
      document.getElementById("overlay").classList.remove("show");
    }

    // Sidebar Submenu Toggle
    document.addEventListener('DOMContentLoaded', function() {
      const sidebarLabels = document.querySelectorAll('.sidebar-label');
      
      sidebarLabels.forEach(label => {
        const parent = label.closest('.sidebar-parent');
        const submenu = parent.querySelector('.sidebar-submenu');
        
        // Check if any submenu item is active
        const hasActiveChild = submenu.querySelector('a.active');
        if (hasActiveChild) {
          parent.classList.add('open');
        }
        
        label.addEventListener('click', function(e) {
          e.preventDefault();
          parent.classList.toggle('open');
        });
      });

      // Close submenu when clicking on submenu links
      const submenuLinks = document.querySelectorAll('.sidebar-submenu a');
      submenuLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          // Allow navigation to happen
          const parent = this.closest('.sidebar-parent');
          if (!parent.classList.contains('open')) {
            parent.classList.add('open');
          }
        });
      });
    });

    // Toogle Dark Mode
    function applyTheme(isDark) {
      document.body.classList.toggle("dark-mode", isDark);
      localStorage.setItem("theme", isDark ? "dark" : "light");
      updateIcons(isDark);
      updateChartColors(isDark);
    }

    function toggleDarkMode() {
      const isDark = !document.body.classList.contains("dark-mode");
      applyTheme(isDark);
    }

    function updateIcons(isDark) {
      const icons = document.querySelectorAll("#darkIconDesktop, #darkIconMobile");

      icons.forEach(icon => {
        if (!icon) return;

        icon.classList.remove("bi-moon-stars", "bi-moon", "bi-sun");
        icon.classList.add(isDark ? "bi-sun" : "bi-moon-stars");
      });
    }

    /* Load saved theme */
    window.addEventListener("DOMContentLoaded", function() {
      const saved = localStorage.getItem("theme");
      const isDark = saved ? saved === "dark" : document.body.classList.contains("dark-mode");
      applyTheme(isDark);
    });

    function updateChartColors(isDark) {
      const color = isDark ? '#e2e8f0' : '#6b7280';

      if (typeof barChart === "undefined" || !barChart) return;

      barChart.options.scales.x.ticks.color = color;
      barChart.options.scales.y.ticks.color = color;

      barChart.update();   
    }

    function getCheckedValues(selector) {
      return Array.from(document.querySelectorAll(selector + ":checked")).map(item => item.value);
    }

    function confirmBulkDelete(selector) {
      const checked = getCheckedValues(selector);

      if (checked.length === 0) {
        alert("Pilih minimal satu data terlebih dahulu.");
        return false;
      }

      return confirm("Yakin ingin menghapus data yang dipilih?");
    }

    function requireSelection(selector, message) {
      const checked = getCheckedValues(selector);

      if (checked.length === 0) {
        alert(message || "Pilih minimal satu data terlebih dahulu.");
        return false;
      }

      return true;
    }

    function setupBulkActions() {
      const selectAllItems = document.querySelectorAll(".select-all");

      selectAllItems.forEach(toggle => {
        toggle.addEventListener("change", function () {
          const selector = this.dataset.checkbox;
          const checkboxes = document.querySelectorAll(selector);

          checkboxes.forEach(item => {
            item.checked = this.checked;
          });
        });
      });

      const bulkEditButtons = document.querySelectorAll(".bulk-edit-btn");

      bulkEditButtons.forEach(button => {
        button.addEventListener("click", function () {
          const selector = this.dataset.checkbox;
          const editBase = this.dataset.editBase;
          const checked = getCheckedValues(selector);

          if (checked.length === 0) {
            alert("Pilih minimal satu data yang ingin diedit.");
            return;
          }

          // Support multiple data edit - redirect to bulk edit page with selected IDs
          window.location.href = editBase + checked.join(',');
        });
      });
    }

    // Chart

    // search
    const searchInput = document.getElementById("searchInput");

    function performGlobalSearch(query) {
      const value = query.trim().toLowerCase();
      if (!value) return;

      const pathSegments = window.location.pathname.split('/');
      const basePath = pathSegments.length > 1 ? '/' + pathSegments[1] : '';

      if (value.includes('profile') || value.includes('profil') || value.includes('akun')) {
        window.location.href = basePath + '/profile.php';
        return;
      }
      if (value.includes('absensi kelas')) {
        window.location.href = basePath + '/absensi_kelas.php';
        return;
      }
      if (value.includes('absensi guru')) {
        window.location.href = basePath + '/absensi_guru.php';
        return;
      }
      if (value.includes('absensi')) {
        window.location.href = basePath + '/absensi_kelas.php';
        return;
      }
      if (value.includes('jurnal kelas')) {
        window.location.href = basePath + '/jurnal_kelas.php';
        return;
      }
      if (value.includes('jurnal guru')) {
        window.location.href = basePath + '/jurnal_guru.php';
        return;
      }
      if (value.includes('jurnal')) {
        window.location.href = basePath + '/jurnal_kelas.php';
        return;
      }
      if (value.includes('siswa')) {
        window.location.href = basePath + '/siswa.php';
        return;
      }
      if (value.includes('kelas')) {
        window.location.href = basePath + '/kelas.php';
        return;
      }
      if (value.includes('jurusan')) {
        window.location.href = basePath + '/jurusan.php';
        return;
      }
      if (value.includes('guru')) {
        window.location.href = basePath + '/guru.php';
        return;
      }
      if (value.includes('dashboard')) {
        window.location.href = basePath + '/dashboard.php';
        return;
      }
      window.location.href = basePath + '/siswa.php';
    }

    if (searchInput) {
      searchInput.addEventListener("keyup", function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll("#tableBody tr");

        rows.forEach(row => {
          row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
        });
      });

      searchInput.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
          event.preventDefault();
          performGlobalSearch(this.value);
        }
      });
    }

    function showPageLoader() {
      const loader = document.getElementById('pageLoader');
      if (loader) {
        loader.classList.add('visible');
      }
    }

    function hidePageLoader() {
      const loader = document.getElementById('pageLoader');
      if (loader) {
        loader.classList.remove('visible');
      }
    }

    document.addEventListener('click', function(event) {
      const anchor = event.target.closest('a');
      if (!anchor) return;
      const href = anchor.getAttribute('href');
      if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:') || anchor.target === '_blank') return;
      if (href.startsWith('http') && !href.includes(window.location.host)) return;
      if (href.startsWith('javascript:')) return;

      event.preventDefault();
      showPageLoader();

      setTimeout(function() {
        window.location.href = href;
      }, 300);
    });

    document.addEventListener('submit', function(event) {
      if (event.target.closest('form')) {
        showPageLoader();
      }
    });

    window.addEventListener("DOMContentLoaded", function () {
      setupBulkActions();
      hidePageLoader();
    });

    
