<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    .table-custom th {
        background-color: #f1f3f5;
        text-align: center;
        vertical-align: middle;
    }

    .badge-pendapatan {
        background-color: #198754;
    }

    .badge-belanja {
        background-color: #dc3545;
    }

    .chart-container {
        position: relative;
        height: 320px;
        width: 100%;
    }
</style>
<div class="app-wrapper">
    <main class="app-main p-4">
        <div class="container-fluid">



            <!-- SECTION: GRAFIK REALISASI GABUNGAN (PENDAPATAN VS BELANJA) -->
            <div class="card card-outline card-info shadow-sm mb-4">
                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold text-info mb-0">
                        <i class="bi bi-graph-up me-2"></i>Grafik Perbandingan Realisasi Pendapatan vs Belanja (%) Tahun <?= esc($tahunSelected) ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="chartRealisasiCombined"></canvas>
                    </div>
                </div>
            </div>

            <!-- SECTION 1: GRAFIK RINGKASAN CHART.JS -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-transparent py-3">
                            <h3 class="card-title fw-bold text-dark m-0">
                                <i class="bi bi-bar-chart-fill me-2 text-primary"></i>Top 10 Capaian Realisasi Anggaran SKPD <?= session()->get('namawilayah') ?>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div style="height: 320px; position: relative;">
                                <canvas id="skpdSummaryChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </main>
</div>


<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta2/dist/js/adminlte.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(document).ready(function() {

        // ==========================================
        // 1. INITIALIZE COMBINED CHART (PENDAPATAN VS BELANJA)
        // ==========================================
        const monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const ctxCombined = document.getElementById('chartRealisasiCombined').getContext('2d');

        const chartCombined = new Chart(ctxCombined, {
            type: 'line',
            data: {
                labels: monthLabels,
                datasets: [{
                        label: 'Realisasi Pendapatan (%)',
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.3,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    },
                    {
                        label: 'Realisasi Belanja (%)',
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.3,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                weight: 'bold'
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y + '%';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Persentase (%)'
                        },
                        ticks: {
                            callback: value => value + '%'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Bulan'
                        }
                    }
                }
            }
        });

        // Helper Update Data Chart
        function updateCombinedChart(data) {
            if (data.pendapatan) {
                chartCombined.data.datasets[0].data = data.pendapatan;
            }
            if (data.belanja) {
                chartCombined.data.datasets[1].data = data.belanja;
            }
            chartCombined.update();
        }

        // Load data awal grafik dari PHP
        const initialTren = <?= json_encode($trenBulanan) ?>;
        updateCombinedChart(initialTren);
        // 1. Inisialisasi Chart.js Ringkasan Anggaran
        fetch('<?= base_url('' . $wilayah . '/' . $groupuser . '/getChartDataOpdRangking') ?>')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => item.NAMA_UNIT_SKPD);
                const CapaianAnggaran = data.map(item => item.persen_capaian_anggaran);
                const CapaianSRO = data.map(item => item.persen_capaian_sro);

                const ctx = document.getElementById('skpdSummaryChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                                label: 'Capaian Anggaran (%)',
                                data: CapaianAnggaran,
                                backgroundColor: 'rgba(13, 110, 253, 0.7)',
                                borderColor: 'rgba(13, 110, 253, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Capaian Sub Rincian Objek (%)',
                                data: CapaianSRO,
                                backgroundColor: 'rgba(25, 135, 84, 0.7)',
                                borderColor: 'rgba(25, 135, 84, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                ticks: {
                                    callback: function(value) {
                                        return value + '%';
                                    }
                                }
                            }
                        }
                    }
                });
            });

    });
</script>
<?= $this->endSection() ?>