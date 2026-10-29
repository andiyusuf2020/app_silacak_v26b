<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<!-- Font Awesome Icons -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> -->
<!-- DataTables Bootstrap 5 CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<div class="app-wrapper">
    <!-- Main Content -->
    <main class="app-main p-4">
        <div class="container-fluid">

            <!-- Page Header -->
            <div class="row mb-4 align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold text-dark"><i class="fas fa-chart-line me-2 text-primary"></i>Rekap Realisasi Anggaran</h3>
                    <p class="text-muted text-sm m-0">Monitoring data anggaran dan realisasi berdasarkan periode dan klasifikasi akun.</p>
                </div>
                <div class="col-sm-6 text-sm-end mt-2 mt-sm-0 d-flex justify-content-sm-end align-items-center gap-2">
                    <span class="badge bg-primary px-3 py-2 fs-6 shadow-sm">Periode Bulan: <?= esc($bulan ?? 'Semua') ?>
                    </span>

                    <!-- Tombol Cetak PDF -->
                    <button type="button" class="btn btn-danger shadow-sm px-3" id="btnExportPdf">
                        <i class="fas fa-file-pdf me-1"></i> Cetak PDF
                    </button>
                </div>
            </div>

            <!-- Filter Card -->
            <div class="card shadow-sm border-0 mb-4 rounded-4">
                <div class="card-body">
                    <form action="<?= base_url($wilayah . '/' . $groupuser . '/tampillaporanperakun') ?>" method="GET" class="row g-3 align-items-end">
                        <?= csrf_field() ?>
                        <div class="col-md-5">
                            <label for="bulan" class="form-label fw-semibold">Bulan</label>
                            <input type="text" class="form-control rounded-3" id="bulan" name="bulan" value="<?= esc($bulan ?? '') ?>" placeholder="Contoh: 01 atau Januari">
                        </div>
                        <div class="col-md-5">
                            <label for="akun" class="form-label fw-semibold">Berdasarkan Klasifikasi (Akun)</label>
                            <select class="form-select rounded-3" id="akun" name="akun">
                                <?php
                                $options = [
                                    'nama_akun' => 'Nama Akun',
                                    'nama_kelompok' => 'Nama Kelompok',
                                    'nama_jenis' => 'Nama Jenis',
                                    'nama_rincian_objek' => 'Nama Rincian Objek',
                                    'NAMA_BIDANG_URUSAN' => 'Nama Bidang Urusan',
                                    'NAMA_URUSAN' => 'Nama Urusan'
                                ];
                                foreach ($options as $key => $val):
                                    $selected = (isset($akun) && $akun == $key) ? 'selected' : '';
                                ?>
                                    <option value="<?= $key ?>" <?= $selected ?>><?= $val ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100 shadow-sm rounded-3"><i class="fas fa-filter me-1"></i> Tampilkan</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Graphic Section (Chart.js) -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h5 class="card-title fw-bold m-0"><i class="fas fa-chart-bar me-2 text-success"></i>Grafik Capaian Realisasi (%)</h5>
                        </div>
                        <div class="card-body px-4">
                            <div style="position: relative; height:350px; width:100%">
                                <canvas id="rekapChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h5 class="card-title fw-bold m-0"><i class="fas fa-table me-2 text-info"></i>Tabel Detail Rekapitulasi</h5>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <div class="table-responsive">
                                <table id="rekapTable" class="table table-hover align-middle border-light">
                                    <thead class="table-light text-uppercase fs-7">
                                        <tr>
                                            <th class="py-3">Klasifikasi</th>
                                            <th class="py-3 text-end">Total Anggaran</th>
                                            <th class="py-3 text-end">Total Realisasi</th>
                                            <th class="py-3 text-center">Capaian (%)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($rekapData)): ?>
                                            <?php foreach ($rekapData as $row):
                                                $dataviewKey = $akun ?? 'nama_akun';
                                                $label = $row[$dataviewKey] ?? '-';
                                                $anggaran = $row['jumlah_total_anggaran'] ?? 0;
                                                $realisasi = $row['total_realisasi'] ?? 0;
                                                $capaian = $row['capaian_realisasi'] ?? 0;
                                            ?>
                                                <tr>
                                                    <td class="fw-semibold text-dark"><?= esc($label) ?></td>
                                                    <td class="text-end">Rp <?= number_format($anggaran, 0, ',', '.') ?></td>
                                                    <td class="text-end">Rp <?= number_format($realisasi, 0, ',', '.') ?></td>
                                                    <td class="text-center">
                                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                                            <div class="progress flex-grow-1" style="height: 8px; max-width: 100px;">
                                                                <div class="progress-bar bg-success" role="progressbar" style="width: <?= min($capaian, 100) ?>%;" aria-valuenow="<?= $capaian ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                            <span class="badge bg-success-subtle text-success fw-bold"><?= number_format($capaian, 2, ',', '.') ?>%</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

<!-- Form Hidden untuk Submit cetak PDF beserta Image Chart -->
<form id="formPdfExport" action="<?= base_url($wilayah . '/' . $groupuser . '/cetakpdfrekap') ?>" method="POST" target="_blank" class="d-none">
    <?= csrf_field() ?>
    <input type="hidden" name="bulan" value="<?= esc($bulan ?? '') ?>">
    <input type="hidden" name="akun" value="<?= esc($akun ?? 'nama_akun') ?>">
    <input type="hidden" name="chart_image" id="chartImageInput">

</form>

<!-- Required Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/@adminlte/adminlte@v4.0.0-beta/dist/js/adminlte.min.js"></script> -->
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    let rekapChartInstance;

    $(document).ready(function() {
        // Inisialisasi DataTables
        $('#rekapTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            pageLength: 10,
            ordering: true,
            responsive: true
        });

        // Persiapan Data untuk Chart.js
        <?php
        $labels = [];
        $dataCapaian = [];
        if (!empty($rekapData)) {
            $keyCol = $akun ?? 'nama_akun';
            foreach ($rekapData as $item) {
                $labels[] = $item[$keyCol] ?? '-';
                $dataCapaian[] = round((float)($item['capaian_realisasi'] ?? 0), 2);
            }
        }
        ?>

        const labels = <?= json_encode($labels) ?>;
        const dataValues = <?= json_encode($dataCapaian) ?>;

        // Inisialisasi Grafik Chart.js
        const ctx = document.getElementById('rekapChart').getContext('2d');
        rekapChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Capaian Realisasi (%)',
                    data: dataValues,
                    backgroundColor: 'rgba(13, 110, 253, 0.75)',
                    borderColor: 'rgb(13, 110, 253)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    onComplete: function() {
                        // Simpan Base64 image setiap kali animasi chart selesai render
                        $('#chartImageInput').val(rekapChartInstance.toBase64Image());
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });

        // Trigger Event Tombol Cetak PDF
        $('#btnExportPdf').on('click', function() {
            if (rekapChartInstance) {
                // Ambil base64 gambar chart secara instan saat tombol diklik
                $('#chartImageInput').val(rekapChartInstance.toBase64Image());
            }
            $('#formPdfExport').submit();
        });
    });
</script>
<?= $this->endSection() ?>