<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    .table-dynamic th {
        background-color: #f8f9fa;
        text-align: center;
        vertical-align: middle;
    }

    .chart-container {
        position: relative;
        height: 260px;
        width: 100%;
    }

    .filter-statis-select {
        font-size: 0.8rem;
        padding: 0.2rem 0.4rem;
    }
</style>
<div class="app-wrapper">
    <main class="app-main p-4">
        <div class="container-fluid">

            <!-- SECTION 1: DASHBOARD GRAFIK RINGKASAN -->
            <div class="row mb-4">
                <!-- Grafik 1: Statis dengan Filter Periode -->
                <div class="col-md-6">
                    <div class="card card-outline card-info shadow-sm h-100">
                        <div class="card-header border-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <h5 class="card-title fw-bold text-info mb-0">
                                <i class="bi bi-pie-chart-fill me-1"></i>Realisasi Histori (Database)
                            </h5>

                            <!-- Filter Dropdown untuk Grafik Statis -->
                            <div class="d-flex align-items-center gap-1">
                                <select id="filterStatisBulan" class="form-select form-select-sm filter-statis-select">
                                    <?php
                                    $months = [
                                        1 => 'Jan',
                                        2 => 'Feb',
                                        3 => 'Mar',
                                        4 => 'Apr',
                                        5 => 'Mei',
                                        6 => 'Jun',
                                        7 => 'Jul',
                                        8 => 'Agu',
                                        9 => 'Sep',
                                        10 => 'Okt',
                                        11 => 'Nov',
                                        12 => 'Des'
                                    ];
                                    $selectedBulan = $summaryStatis['bulan'] ?? date('n');
                                    foreach ($months as $num => $name): ?>
                                        <option value="<?= $num ?>" <?= $selectedBulan == $num ? 'selected' : '' ?>><?= $name ?></option>
                                    <?php endforeach; ?>
                                </select>

                                <select id="filterStatisTahun" class="form-select form-select-sm filter-statis-select">
                                    <?php
                                    $currentYear = date('Y');
                                    $selectedTahun = $summaryStatis['tahun'] ?? $currentYear;
                                    for ($y = $currentYear; $y >= $currentYear - 3; $y--): ?>
                                        <option value="<?= $y ?>" <?= $selectedTahun == $y ? 'selected' : '' ?>><?= $y ?></option>
                                    <?php endfor; ?>
                                </select>

                                <button type="button" class="btn btn-sm btn-info text-white" id="btnFilterStatis" title="Terapkan Filter">
                                    <i class="bi bi-funnel"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="chartStatis"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grafik 2: Dinamis (Live Form Input) -->
                <div class="col-md-6">
                    <div class="card card-outline card-success shadow-sm h-100">
                        <div class="card-header border-0">
                            <h5 class="card-title fw-bold text-success">
                                <i class="bi bi-bar-chart-line-fill me-2"></i>Preview Dinamis (Input Saat Ini)
                            </h5>
                            <span class="badge bg-success float-end">Live Update</span>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="chartDinamis"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: FORM INPUT DATA DINAMIS -->
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header">
                    <h3 class="card-title fw-bold">
                        <i class="bi bi-journal-plus me-2"></i>Form Input Realisasi APBD
                    </h3>
                </div>

                <form id="formRealisasi">
                    <?= csrf_field() ?>
                    <div class="card-body">

                        <!-- Filter Periode Input -->
                        <div class="row g-3 mb-4 p-3 bg-light rounded border">
                            <div class="col-md-4">
                                <label for="tahun_anggaran" class="form-label fw-semibold">Tahun Anggaran</label>
                                <select name="tahun_anggaran" id="tahun_anggaran" class="form-select" required>
                                    <?php for ($y = $currentYear; $y >= $currentYear - 3; $y--): ?>
                                        <option value="<?= $y ?>"><?= $y ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="bulan" class="form-label fw-semibold">Bulan Pelaporan</label>
                                <select name="bulan" id="bulan" class="form-select" required>
                                    <?php
                                    $fullMonths = [
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
                                    foreach ($fullMonths as $num => $name): ?>
                                        <option value="<?= $num ?>" <?= date('n') == $num ? 'selected' : '' ?>><?= $name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-primary w-100" id="btnAddRow">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Baris
                                </button>
                            </div>
                        </div>

                        <!-- Tabel Input -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-dynamic">
                                <thead>
                                    <tr>
                                        <th style="width: 130px;">Jenis</th>
                                        <th style="width: 170px;">Kode Rekening</th>
                                        <th>Uraian Akun / Program</th>
                                        <th style="width: 170px;">Anggaran (Rp)</th>
                                        <th style="width: 170px;">Realisasi (Rp)</th>
                                        <th style="width: 80px;">%</th>
                                        <th style="width: 50px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyRealisasi">
                                    <tr class="row-item">
                                        <td>
                                            <select name="items[0][jenis]" class="form-select form-select-sm input-jenis">
                                                <option value="Pendapatan">Pendapatan</option>
                                                <option value="Belanja">Belanja</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="items[0][kode_rekening]" class="form-control form-control-sm" placeholder="4.1.01..." required>
                                        </td>
                                        <td>
                                            <input type="text" name="items[0][uraian]" class="form-control form-control-sm" placeholder="Nama Akun/Uraian" required>
                                        </td>
                                        <td>
                                            <input type="text" name="items[0][anggaran]" class="form-control form-control-sm input-number input-anggaran" placeholder="0" required>
                                        </td>
                                        <td>
                                            <input type="text" name="items[0][realisasi]" class="form-control form-control-sm input-number input-realisasi" placeholder="0" required>
                                        </td>
                                        <td class="text-center align-middle fw-bold cell-persentase">0%</td>
                                        <td class="text-center align-middle">
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <div class="card-footer text-end bg-white border-top">
                        <button type="reset" class="btn btn-secondary me-2">Reset</button>
                        <button type="submit" class="btn btn-primary" id="btnSubmit">
                            <i class="bi bi-save me-1"></i> Simpan Data
                        </button>
                    </div>
                </form>

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
        let rowIndex = 1;

        // ==========================================
        // 1. INISIALISASI GRAFIK STATIS
        // ==========================================
        const ctxStatis = document.getElementById('chartStatis').getContext('2d');
        const chartStatis = new Chart(ctxStatis, {
            type: 'bar',
            data: {
                labels: ['Pendapatan', 'Belanja'],
                datasets: [{
                        label: 'Anggaran (Rp)',
                        data: [0, 0],
                        backgroundColor: '#0dcaf0'
                    },
                    {
                        label: 'Realisasi (Rp)',
                        data: [0, 0],
                        backgroundColor: '#0d6efd'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });

        // Helper untuk mengisi data ke Chart Statis
        function populateStatisChart(rawSummary) {
            let pAnggaran = 0,
                pRealisasi = 0;
            let bAnggaran = 0,
                bRealisasi = 0;

            if (rawSummary && rawSummary.data) {
                rawSummary.data.forEach(item => {
                    if (item.jenis === 'Pendapatan') {
                        pAnggaran = parseFloat(item.total_anggaran) || 0;
                        pRealisasi = parseFloat(item.total_realisasi) || 0;
                    } else if (item.jenis === 'Belanja') {
                        bAnggaran = parseFloat(item.total_anggaran) || 0;
                        bRealisasi = parseFloat(item.total_realisasi) || 0;
                    }
                });
            }

            chartStatis.data.datasets[0].data = [pAnggaran, bAnggaran];
            chartStatis.data.datasets[1].data = [pRealisasi, bRealisasi];
            chartStatis.update();
        }

        // Load awal data statis dari PHP
        const initialStatis = <?= json_encode($summaryStatis) ?>;
        populateStatisChart(initialStatis);

        // Event AJAX Filter Grafik Statis
        $('#btnFilterStatis, #filterStatisBulan, #filterStatisTahun').on('click change', function(e) {
            // Mencegah multiple trigger bersamaan jika mengklik tombol
            if (e.type === 'click' && this.id !== 'btnFilterStatis') return;

            let bulan = $('#filterStatisBulan').val();
            let tahun = $('#filterStatisTahun').val();

            $.ajax({
                url: '<?= base_url('realisasi/get-summary') ?>',
                type: 'GET',
                data: {
                    tahun: tahun,
                    bulan: bulan
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        populateStatisChart(response.data);
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal mengambil data filter grafik.'
                    });
                }
            });
        });

        // ==========================================
        // 2. GRAFIK DINAMIS (LIVE FORM)
        // ==========================================
        const ctxDinamis = document.getElementById('chartDinamis').getContext('2d');
        const chartDinamis = new Chart(ctxDinamis, {
            type: 'bar',
            data: {
                labels: ['Pendapatan', 'Belanja'],
                datasets: [{
                        label: 'Anggaran (Rp)',
                        data: [0, 0],
                        backgroundColor: '#198754'
                    },
                    {
                        label: 'Realisasi (Rp)',
                        data: [0, 0],
                        backgroundColor: '#ffc107'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });

        function updateLiveChart() {
            let totalAnggaranPendapatan = 0,
                totalRealisasiPendapatan = 0;
            let totalAnggaranBelanja = 0,
                totalRealisasiBelanja = 0;

            $('#tbodyRealisasi tr').each(function() {
                let jenis = $(this).find('.input-jenis').val();
                let anggaranVal = $(this).find('.input-anggaran').val().replace(/\./g, '').replace(',', '.');
                let realisasiVal = $(this).find('.input-realisasi').val().replace(/\./g, '').replace(',', '.');

                let anggaran = parseFloat(anggaranVal) || 0;
                let realisasi = parseFloat(realisasiVal) || 0;

                if (jenis === 'Pendapatan') {
                    totalAnggaranPendapatan += anggaran;
                    totalRealisasiPendapatan += realisasi;
                } else if (jenis === 'Belanja') {
                    totalAnggaranBelanja += anggaran;
                    totalRealisasiBelanja += realisasi;
                }
            });

            chartDinamis.data.datasets[0].data = [totalAnggaranPendapatan, totalAnggaranBelanja];
            chartDinamis.data.datasets[1].data = [totalRealisasiPendapatan, totalRealisasiBelanja];
            chartDinamis.update();
        }

        // ==========================================
        // 3. EVENT HANDLER INPUT FORM
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

        $(document).on('keyup change', '.input-number, .input-jenis', function() {
            if ($(this).hasClass('input-number')) {
                $(this).val(formatRupiah($(this).val()));

                let row = $(this).closest('tr');
                let anggaran = parseFloat(row.find('.input-anggaran').val().replace(/\./g, '').replace(',', '.')) || 0;
                let realisasi = parseFloat(row.find('.input-realisasi').val().replace(/\./g, '').replace(',', '.')) || 0;
                let persentase = anggaran > 0 ? (realisasi / anggaran) * 100 : 0;
                row.find('.cell-persentase').text(persentase.toFixed(1) + '%');
            }

            updateLiveChart();
        });

        // Tambah baris
        $('#btnAddRow').click(function() {
            let newRow = `
          <tr class="row-item">
            <td>
              <select name="items[${rowIndex}][jenis]" class="form-select form-select-sm input-jenis">
                <option value="Pendapatan">Pendapatan</option>
                <option value="Belanja">Belanja</option>
              </select>
            </td>
            <td>
              <input type="text" name="items[${rowIndex}][kode_rekening]" class="form-control form-control-sm" placeholder="4.1.01..." required>
            </td>
            <td>
              <input type="text" name="items[${rowIndex}][uraian]" class="form-control form-control-sm" placeholder="Nama Akun/Uraian" required>
            </td>
            <td>
              <input type="text" name="items[${rowIndex}][anggaran]" class="form-control form-control-sm input-number input-anggaran" placeholder="0" required>
            </td>
            <td>
              <input type="text" name="items[${rowIndex}][realisasi]" class="form-control form-control-sm input-number input-realisasi" placeholder="0" required>
            </td>
            <td class="text-center align-middle fw-bold cell-persentase">0%</td>
            <td class="text-center align-middle">
              <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row">
                <i class="bi bi-trash"></i>
              </button>
            </td>
          </tr>`;

            $('#tbodyRealisasi').append(newRow);
            rowIndex++;
            updateLiveChart();
        });

        // Hapus baris
        $(document).on('click', '.btn-remove-row', function() {
            if ($('#tbodyRealisasi tr').length > 1) {
                $(this).closest('tr').remove();
                updateLiveChart();
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Minimal harus ada 1 baris data!'
                });
            }
        });

        // Form Submit
        $('#formRealisasi').submit(function(e) {
            e.preventDefault();
            let btnSubmit = $('#btnSubmit');
            btnSubmit.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

            $.ajax({
                url: '<?= base_url('realisasi/store') ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    btnSubmit.prop('disabled', false).html('<i class="bi bi-save me-1"></i> Simpan Data');
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 1800,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    btnSubmit.prop('disabled', false).html('<i class="bi bi-save me-1"></i> Simpan Data');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan sistem.'
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>