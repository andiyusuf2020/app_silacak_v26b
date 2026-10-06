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
        height: 280px;
        width: 100%;
    }
</style>
<div class="app-wrapper">
    <main class="app-main p-4">
        <div class="container-fluid">

            <!-- HEADER FILTER TAHUN GRAFIK -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">Dashboard & Form Realisasi APBD</h4>
                <div class="d-flex align-items-center gap-2">
                    <label for="filterTahunGrafik" class="fw-semibold mb-0">Tahun Grafik:</label>
                    <select id="filterTahunGrafik" class="form-select form-select-sm" style="width: 120px;">
                        <?php
                        $cYear = date('Y');
                        for ($y = $cYear; $y >= $cYear - 3; $y--): ?>
                            <option value="<?= $y ?>" <?= $tahunSelected == $y ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <!-- SECTION 1: GRAFIK PERSENTASE TREN BULANAN -->
            <div class="row mb-4">
                <!-- Grafik 1: Persentase Realisasi Pendapatan -->
                <div class="col-md-6">
                    <div class="card card-outline card-success shadow-sm h-100">
                        <div class="card-header border-0">
                            <h5 class="card-title fw-bold text-success mb-0">
                                <i class="bi bi-graph-up-arrow me-2"></i>Persentase Realisasi Pendapatan Bulanan (%)
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="chartPendapatanBulanan"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grafik 2: Persentase Realisasi Belanja -->
                <div class="col-md-6">
                    <div class="card card-outline card-danger shadow-sm h-100">
                        <div class="card-header border-0">
                            <h5 class="card-title fw-bold text-danger mb-0">
                                <i class="bi bi-graph-down-arrow me-2"></i>Persentase Realisasi Belanja Bulanan (%)
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="chartBelanjaBulanan"></canvas>
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
                                    <?php for ($y = $cYear; $y >= $cYear - 3; $y--): ?>
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
        const monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        // ==========================================
        // 1. CHART PENDAPATAN BULANAN
        // ==========================================
        const ctxPendapatan = document.getElementById('chartPendapatanBulanan').getContext('2d');
        const chartPendapatan = new Chart(ctxPendapatan, {
            type: 'line',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Capaian Pendapatan (%)',
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => value + '%'
                        }
                    }
                }
            }
        });

        // ==========================================
        // 2. CHART BELANJA BULANAN
        // ==========================================
        const ctxBelanja = document.getElementById('chartBelanjaBulanan').getContext('2d');
        const chartBelanja = new Chart(ctxBelanja, {
            type: 'line',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Capaian Belanja (%)',
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => value + '%'
                        }
                    }
                }
            }
        });

        // Helper update data grafik
        function updateCharts(data) {
            if (data.pendapatan) {
                chartPendapatan.data.datasets[0].data = data.pendapatan;
                chartPendapatan.update();
            }
            if (data.belanja) {
                chartBelanja.data.datasets[0].data = data.belanja;
                chartBelanja.update();
            }
        }

        // Load data grafik pertama kali dari server
        const initialData = <?= json_encode($trenBulanan) ?>;
        updateCharts(initialData);

        // Filter Tahun Grafik via AJAX
        $('#filterTahunGrafik').change(function() {
            let tahun = $(this).val();
            $.ajax({
                url: '<?= base_url('realisasi/get-tren-bulanan') ?>',
                type: 'GET',
                data: {
                    tahun: tahun
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        updateCharts(res.data);
                    }
                }
            });
        });

        // ==========================================
        // 3. EVENT HANDLER FORM INPUT
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

            let row = $(this).closest('tr');
            let anggaran = parseFloat(row.find('.input-anggaran').val().replace(/\./g, '').replace(',', '.')) || 0;
            let realisasi = parseFloat(row.find('.input-realisasi').val().replace(/\./g, '').replace(',', '.')) || 0;
            let persentase = anggaran > 0 ? (realisasi / anggaran) * 100 : 0;
            row.find('.cell-persentase').text(persentase.toFixed(1) + '%');
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
        });

        // Hapus baris
        $(document).on('click', '.btn-remove-row', function() {
            if ($('#tbodyRealisasi tr').length > 1) {
                $(this).closest('tr').remove();
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Minimal harus ada 1 baris data!'
                });
            }
        });

        // Form Submit via AJAX
        $('#formRealisasi').submit(function(e) {
            e.preventDefault();
            let btnSubmit = $('#btnSubmit');
            btnSubmit.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

            $.ajax({
                url: '<?= base_url('' . $wilayah . '/' . $groupuser . '/realisasi/store') ?>',
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