
      (function() {
        if (typeof Chart === 'undefined') return;

        const barCanvas = document.getElementById('barChart');
        const donutCanvas = document.getElementById('donutChart');

        const rawJurusan = (window.dashboardChartData && window.dashboardChartData.jurusan) || [];
        const rawTahunan = (window.dashboardChartData && window.dashboardChartData.siswaPerTahun) || {}

        let barLabels = [];
        let barValues = [];

        if (Array.isArray(rawJurusan)) {
          if (rawJurusan.length > 0 && typeof rawJurusan[0] === 'object' && rawJurusan[0] !== null) {
            rawJurusan.forEach(function(item, index) {
              const label = item.nama_jurusan || item.label || ('Data ' + (index + 1));
              const value = Number(item.total_siswa ?? item.total ?? item.jumlah ?? item.value ?? 0);
              barLabels.push(label);
              barValues.push(value);
            });
          } else {
            barLabels = rawJurusan.map(function(_, index) {
              return 'Data ' + (index + 1);
            });
            barValues = rawJurusan.map(function(val) {
              return Number(val) || 0;
            });
          }
        } else if (rawJurusan && typeof rawJurusan === 'object') {
          barLabels = Object.keys(rawJurusan);
          barValues = Object.values(rawJurusan).map(function(val) {
            return Number(val) || 0;
          });
        }

        const yearLabels = Object.keys(rawTahunan);
        const yearValues = Object.values(rawTahunan).map(function(val) {
          return Number(val) || 0;
        });

        if (barCanvas) {
          window.barChart = new Chart(barCanvas, {
            type: 'bar',
            data: {
              labels: barLabels,
              datasets: [{
                data: barValues,
                backgroundColor: '#3b82f6'
              }]
            },
            options: {
              plugins: {
                legend: {
                  display: false
                }
              },
              scales: {
                x: {
                  ticks: {
                    color: '#6b7280'
                  }
                },
                y: {
                  ticks: {
                    color: '#6b7280'
                  }
                }
              }
            }
          });
        }

        if (donutCanvas) {
          window.donutChart = new Chart(donutCanvas, {
            type: 'line',
            data: {
              labels: yearLabels,
              datasets: [{
                label: 'Jumlah Siswa',
                data: yearValues,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.2)',
                fill: true,
                tension: 0.3,
                pointRadius: 4,
                pointHoverRadius: 6
              }]
            },
            options: {
              plugins: {
                legend: {
                  display: true
                }
              },
              scales: {
                x: {
                  ticks: {
                    color: '#6b7280'
                  }
                },
                y: {
                  beginAtZero: true,
                  ticks: {
                    precision: 0,
                    color: '#6b7280'
                  }
                }
              }
            }
          });
        }
      })();