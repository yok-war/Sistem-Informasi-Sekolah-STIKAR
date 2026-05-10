
      (function() {
        if (typeof Chart === 'undefined') return;

        const barCanvas = document.getElementById('barChart');
        const donutCanvas = document.getElementById('donutChart');

        const rawJurusan = (window.dashboardChartData && window.dashboardChartData.jurusan) || [];
        const rawAttendance = (window.dashboardChartData && window.dashboardChartData.siswaPerTahun) || {};

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

        let attendanceLabels = [];
        let attendanceDatasets = [];

        if (rawAttendance && typeof rawAttendance === 'object') {
          if (Array.isArray(rawAttendance.labels) && Array.isArray(rawAttendance.datasets)) {
            attendanceLabels = rawAttendance.labels;
            attendanceDatasets = rawAttendance.datasets.map(function(dataset, index) {
              return {
                label: dataset.label || 'Data ' + (index + 1),
                data: Array.isArray(dataset.data) ? dataset.data.map(function(val) {
                  return Number(val) || 0;
                }) : [],
                borderColor: dataset.borderColor || '#10b981',
                backgroundColor: dataset.backgroundColor || 'rgba(16, 185, 129, 0.2)',
                fill: true,
                tension: 0.3,
                pointRadius: 4,
                pointHoverRadius: 6
              };
            });
          } else {
            attendanceLabels = Object.keys(rawAttendance);
            attendanceDatasets = [{
              label: 'Jumlah Siswa',
              data: Object.values(rawAttendance).map(function(val) {
                return Number(val) || 0;
              }),
              borderColor: '#10b981',
              backgroundColor: 'rgba(16, 185, 129, 0.2)',
              fill: true,
              tension: 0.3,
              pointRadius: 4,
              pointHoverRadius: 6
            }];
          }
        }

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
              labels: attendanceLabels,
              datasets: attendanceDatasets
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