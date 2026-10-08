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

            <!-- HEADER & BREADCRUMB -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">
                    <i class="bi bi-table me-2"></i>Daftar Total Realisasi Pendapatan dan Belanja perBulan APBD Tahun <?= esc($tahunSelected) ?>
                </h4>
            </div>

            <!-- CARD FILTER PERIODE LAPORAN & GRAFIK -->
            <div class="card card-outline card-secondary shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="<?= base_url('realisasi/data-list') ?>" id="formFilter" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="filter_tahun" class="form-label fw-semibold">Tahun Anggaran</label>
                            <select name="tahun" id="filter_tahun" class="form-select">
                                <?php
                                $cYear = date('Y');
                                for ($y = $cYear; $y >= $cYear - 3; $y--): ?>
                                    <option value="<?= $y ?>" <?= $tahunSelected == $y ? 'selected' : '' ?>><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="filter_bulan" class="form-label fw-semibold">Bulan</label>
                            <select name="bulan" id="filter_bulan" class="form-select">
                                <option value="all" <?= $bulanSelected == 'all' ? 'selected' : '' ?>>-- Semua Bulan --</option>
                                <?php
                                $months = [
                                    1 => 'Januari',
                                    2 => 'Februari',
                                    3 => 'Maret',
                                    4 => 'April',
                                    5 => 'Mei',
                                    6 => 'Juni',
                                    7 => 'Juli',
                                    8 => 'Agustus',
                                    9 => 'September',
                                    10 => 'Oktober',
                                    11 => 'November',
                                    12 => 'Desember'
                                ];
                                foreach ($months as $num => $name): ?>
                                    <option value="<?= $num ?>" <?= $bulanSelected == $num ? 'selected' : '' ?>><?= $name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-secondary w-100">
                                <i class="bi bi-filter me-1"></i> Terapkan Filter
                            </button>
                            <a href="<?= base_url('realisasi/data-list') ?>" class="btn btn-outline-secondary" title="Reset Filter">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

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


    });
</script>
<?= $this->endSection() ?>