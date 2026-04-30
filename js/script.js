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
      const icons = document.querySelectorAll("#darkIcon, #darkIconDesktop");

      icons.forEach(icon => {
        if (!icon) return;

        icon.classList.remove("bi-moon-stars", "bi-sun");
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

    // siswa
    const searchInput = document.getElementById("searchInput");

    if (searchInput) {
      searchInput.addEventListener("keyup", function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll("#tableBody tr");

        rows.forEach(row => {
          row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
        });
      });
    }

    window.addEventListener("DOMContentLoaded", function () {
      setupBulkActions();
    });

    
