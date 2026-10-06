<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
                <a href="<?= hash_url('' . $wilayah . '/' . $groupuser . '', ['hal' => 'inputbulanan']) ?>" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Input Data Baru
                </a>
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

            <!-- TABEL DATA REALISASI -->
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold mb-0">
                        Laporan Realisasi Tahun <?= esc($tahunSelected) ?>
                        <?= $bulanSelected !== 'all' ? '(' . $months[$bulanSelected] . ')' : '' ?>
                    </h5>
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
                                    <th style="width: 160px;">Anggaran (Rp)</th>
                                    <th style="width: 160px;">Realisasi (Rp)</th>
                                    <th style="width: 90px;">%</th>
                                    <th style="width: 110px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($dataRealisasi)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                            Tidak ada data realisasi yang ditemukan.
                                        </td>
                                    </tr>
                                    <?php else:
                                    $totalAnggaran = 0;
                                    $totalRealisasi = 0;
                                    foreach ($dataRealisasi as $index => $row):
                                        $totalAnggaran += $row['anggaran'];
                                        $totalRealisasi += $row['realisasi'];
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
                                    <?php endforeach; ?>
                            </tbody>
                            <!-- FOOTER TOTALS -->
                            <tfoot>
                                <tr class="fw-bold bg-light">
                                    <td colspan="5" class="text-end">TOTAL AKUMULASI:</td>
                                    <td class="text-end text-primary"><?= number_format($totalAnggaran, 0, ',', '.') ?></td>
                                    <td class="text-end text-success"><?= number_format($totalRealisasi, 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <?= $totalAnggaran > 0 ? number_format(($totalRealisasi / $totalAnggaran) * 100, 1, ',', '.') : '0' ?>%
                                    </td>
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

<!-- MODAL EDIT DATA -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold" id="modalEditLabel">
                    <i class="bi bi-pencil-square me-2"></i>Edit Data Realisasi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="formEdit">
                <?= csrf_field() ?>
                <input type="hidden" id="edit_id" name="id">

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_tahun" class="form-label fw-semibold">Tahun Anggaran</label>
                            <select name="tahun_anggaran" id="edit_tahun" class="form-select" required>
                                <?php for ($y = $cYear; $y >= $cYear - 3; $y--): ?>
                                    <option value="<?= $y ?>"><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_bulan" class="form-label fw-semibold">Bulan Pelaporan</label>
                            <select name="bulan" id="edit_bulan" class="form-select" required>
                                <?php foreach ($months as $num => $name): ?>
                                    <option value="<?= $num ?>"><?= $name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_jenis" class="form-label fw-semibold">Jenis Transaksi</label>
                            <select name="jenis" id="edit_jenis" class="form-select" required>
                                <option value="Pendapatan">Pendapatan</option>
                                <option value="Belanja">Belanja</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_kode_rekening" class="form-label fw-semibold">Kode Rekening</label>
                            <input type="text" name="kode_rekening" id="edit_kode_rekening" class="form-control" placeholder="4.1.01..." required>
                        </div>

                        <div class="col-12">
                            <label for="edit_uraian" class="form-label fw-semibold">Uraian Akun / Program</label>
                            <input type="text" name="uraian" id="edit_uraian" class="form-control" placeholder="Nama Akun/Uraian" required>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_anggaran" class="form-label fw-semibold">Anggaran (Rp)</label>
                            <input type="text" name="anggaran" id="edit_anggaran" class="form-control input-number" placeholder="0" required>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_realisasi" class="form-label fw-semibold">Realisasi (Rp)</label>
                            <input type="text" name="realisasi" id="edit_realisasi" class="form-control input-number" placeholder="0" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-semibold" id="btnUpdate">
                        <i class="bi bi-check-circle me-1"></i> Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
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


        // ==========================================
        // 2. HELPER FORMAT RUPIAH & EVENT INPUT
        // ==========================================
        function formatRupiah(angka) {
            let number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            return split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        }

        $(document).on('keyup change', '.input-number', function() {
            $(this).val(formatRupiah($(this).val()));
        });


        // ==========================================
        // 3. EDIT DATA VIA AJAX
        // ==========================================
        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data('id');

            $.ajax({
                url: '<?= base_url('' . $wilayah . '/' . $groupuser . '/realisasi/get-detail') ?>/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        let d = response.data;
                        $('#edit_id').val(d.id);
                        $('#edit_tahun').val(d.tahun_anggaran);
                        $('#edit_bulan').val(d.bulan);
                        $('#edit_jenis').val(d.jenis);
                        $('#edit_kode_rekening').val(d.kode_rekening);
                        $('#edit_uraian').val(d.uraian);
                        $('#edit_anggaran').val(formatRupiah(d.anggaran.toString()));
                        $('#edit_realisasi').val(formatRupiah(d.realisasi.toString()));

                        $('#modalEdit').modal('show');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal mengambil data dari server.'
                    });
                }
            });
        });

        // Submit Update Data
        $('#formEdit').submit(function(e) {
            e.preventDefault();
            let id = $('#edit_id').val();
            let btnUpdate = $('#btnUpdate');
            btnUpdate.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Mengupdate...');

            $.ajax({
                url: '<?= base_url('' . $wilayah . '/' . $groupuser . '/realisasi/update') ?>/' + id,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    btnUpdate.prop('disabled', false).html('<i class="bi bi-check-circle me-1"></i> Update Data');
                    if (response.status === 'success') {
                        $('#modalEdit').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    btnUpdate.prop('disabled', false).html('<i class="bi bi-check-circle me-1"></i> Update Data');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan sistem.'
                    });
                }
            });
        });


        // ==========================================
        // 4. HAPUS DATA VIA AJAX & SWEETALERT2
        // ==========================================
        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id');
            let uraian = $(this).data('uraian');

            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: `Data "${uraian}" akan dihapus permanen!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus Data!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('' . $wilayah . '/' . $groupuser . '/realisasi/delete') ?>/' + id,
                        type: 'POST',
                        data: {
                            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Gagal menghapus data dari server.'
                            });
                        }
                    });
                }
            });
        });

    });
</script>
<?= $this->endSection() ?>