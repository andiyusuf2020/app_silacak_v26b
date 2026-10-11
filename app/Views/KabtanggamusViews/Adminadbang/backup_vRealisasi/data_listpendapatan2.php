<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
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
        height: 300px;
        width: 100%;
    }
</style>

<div class="app-wrapper">
    <main class="app-main p-4">
        <div class="container-fluid">

            <!-- HEADER & TOMBOL AKSI -->
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h4 class="fw-bold mb-0">
                    <i class="bi bi-table me-2"></i>Daftar Realisasi Anggaran APBD
                </h4>
                <div class="d-flex gap-2">
                    <a href="<?= base_url('realisasi/export-excel?tahun=' . $tahunSelected . '&bulan=' . $bulanSelected) ?>" class="btn btn-sm btn-success">
                        <i class="bi bi-file-earmark-excel me-1"></i> Cetak XLSX
                    </a>
                    <a href="<?= base_url('realisasi/export-pdf?tahun=' . $tahunSelected . '&bulan=' . $bulanSelected) ?>" target="_blank" class="btn btn-sm btn-danger">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Cetak PDF
                    </a>
                    <a href="<?= base_url('realisasi') ?>" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Input Data Baru
                    </a>
                </div>
            </div>

            <!-- CARD FILTER PERIODE -->
            <div class="card card-outline card-secondary shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="<?= base_url('realisasi/data-list') ?>" class="row g-3 align-items-end">
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
                            <a href="<?= base_url('realisasi/data-list') ?>" class="btn btn-outline-secondary" title="Reset">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- GRAFIK GABUNGAN -->
            <div class="card card-outline card-info shadow-sm mb-4">
                <div class="card-header border-0">
                    <h5 class="card-title fw-bold text-info mb-0">
                        <i class="bi bi-graph-up me-2"></i>Grafik Perbandingan Pendapatan vs Belanja (%)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="chartRealisasiCombined"></canvas>
                    </div>
                </div>
            </div>

            <!-- TABEL DATA REALISASI -->
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold mb-0">Laporan Realisasi Tahun <?= esc($tahunSelected) ?></h5>
                    <span class="badge bg-info text-dark">Total Records: <?= count($dataRealisasi) ?></span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-custom mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th style="width: 100px;">Bulan</th>
                                    <th style="width: 110px;">Jenis</th>
                                    <th style="width: 150px;">Kode Rekening</th>
                                    <th>Uraian Akun / Program</th>
                                    <th style="width: 150px;">Anggaran (Rp)</th>
                                    <th style="width: 150px;">Realisasi (Rp)</th>
                                    <th style="width: 80px;">%</th>
                                    <th style="width: 100px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($dataRealisasi)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">Tidak ada data realisasi ditemukan.</td>
                                    </tr>
                                    <?php else:
                                    foreach ($dataRealisasi as $index => $row):
                                        $pct = $row['anggaran'] > 0 ? ($row['realisasi'] / $row['anggaran']) * 100 : 0;
                                        $badgeClass = $row['jenis'] === 'Pendapatan' ? 'badge-pendapatan' : 'badge-belanja';
                                    ?>
                                        <tr>
                                            <td class="text-center align-middle"><?= $index + 1 ?></td>
                                            <td class="text-center align-middle"><?= $months[$row['bulan']] ?? $row['bulan'] ?></td>
                                            <td class="text-center align-middle">
                                                <span class="badge <?= $badgeClass ?>"><?= esc($row['jenis']) ?></span>
                                            </td>
                                            <td class="align-middle fw-semibold"><?= esc($row['kode_rekening']) ?></td>
                                            <td class="align-middle"><?= esc($row['uraian']) ?></td>
                                            <td class="text-end align-middle"><?= number_format($row['anggaran'], 0, ',', '.') ?></td>
                                            <td class="text-end align-middle"><?= number_format($row['realisasi'], 0, ',', '.') ?></td>
                                            <td class="text-center align-middle fw-bold <?= $pct >= 100 ? 'text-success' : 'text-primary' ?>">
                                                <?= number_format($pct, 1, ',', '.') ?>%
                                            </td>
                                            <td class="text-center align-middle">
                                                <button type="button" class="btn btn-sm btn-outline-warning btn-edit" data-id="<?= $row['id'] ?>" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-id="<?= $row['id'] ?>" data-uraian="<?= esc($row['uraian']) ?>" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                <?php endforeach;
                                endif; ?>
                            </tbody>

                            <!-- TOTAL AKUMULASI TERPISAH PER JENIS -->
                            <?php if (!empty($dataRealisasi)):
                                $pctPendapatan = $subtotalPendapatan['anggaran'] > 0 ? ($subtotalPendapatan['realisasi'] / $subtotalPendapatan['anggaran']) * 100 : 0;
                                $pctBelanja    = $subtotalBelanja['anggaran'] > 0 ? ($subtotalBelanja['realisasi'] / $subtotalBelanja['anggaran']) * 100 : 0;
                            ?>
                                <tfoot>
                                    <!-- Subtotal Pendapatan -->
                                    <tr class="fw-bold table-success">
                                        <td colspan="5" class="text-end">TOTAL AKUMULASI PENDAPATAN:</td>
                                        <td class="text-end"><?= number_format($subtotalPendapatan['anggaran'], 0, ',', '.') ?></td>
                                        <td class="text-end"><?= number_format($subtotalPendapatan['realisasi'], 0, ',', '.') ?></td>
                                        <td class="text-center"><?= number_format($pctPendapatan, 1, ',', '.') ?>%</td>
                                        <td></td>
                                    </tr>
                                    <!-- Subtotal Belanja -->
                                    <tr class="fw-bold table-danger">
                                        <td colspan="5" class="text-end">TOTAL AKUMULASI BELANJA:</td>
                                        <td class="text-end"><?= number_format($subtotalBelanja['anggaran'], 0, ',', '.') ?></td>
                                        <td class="text-end"><?= number_format($subtotalBelanja['realisasi'], 0, ',', '.') ?></td>
                                        <td class="text-center"><?= number_format($pctBelanja, 1, ',', '.') ?>%</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

<!-- MODAL EDIT DATA (Sama seperti sebelumnya) -->
<!-- Script JS Chart.js, Edit, & Delete -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(document).ready(function() {
        const monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const ctxCombined = document.getElementById('chartRealisasiCombined').getContext('2d');

        const chartCombined = new Chart(ctxCombined, {
            type: 'line',
            data: {
                labels: monthLabels,
                datasets: [{
                        label: 'Realisasi Pendapatan (%)',
                        data: <?= json_encode($trenBulanan['pendapatan']) ?>,
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Realisasi Belanja (%)',
                        data: <?= json_encode($trenBulanan['belanja']) ?>,
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: v => v + '%'
                        }
                    }
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>