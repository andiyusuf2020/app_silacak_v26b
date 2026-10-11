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
                    <a href="<?= base_url('' . $wilayah . '/' . $groupuser . '/realisasi/export-excel?tahun=' . $tahunSelected . '&bulan=' . $bulanSelected) ?>" class="btn btn-sm btn-success">
                        <i class="bi bi-file-earmark-excel me-1"></i> Cetak XLSX
                    </a>
                    <a href="<?= base_url('' . $wilayah . '/' . $groupuser . '/realisasi/export-pdf?tahun=' . $tahunSelected . '&bulan=' . $bulanSelected) ?>" target="_blank" class="btn btn-sm btn-danger">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Cetak PDF
                    </a>
                    <a href="<?= base_url('' . $wilayah . '/' . $groupuser . '/realisasi') ?>" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Input Data Baru
                    </a>
                </div>
            </div>

            <!-- CARD FILTER PERIODE -->
            <div class="card card-outline card-secondary shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="<?= base_url('' . $wilayah . '/' . $groupuser . '/realisasi/data-list') ?>" class="row g-3 align-items-end">

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

                            <!-- TOTAL AKUMULASI KHUSUS BULAN TERAKHIR -->
                            <?php if (!empty($dataRealisasi) && $bulanTerakhir > 0):
                                $namaBulanTerakhir = $months[$bulanTerakhir] ?? "Bulan $bulanTerakhir";
                                $pctPendapatan = $subtotalPendapatanBulanTerakhir['anggaran'] > 0
                                    ? ($subtotalPendapatanBulanTerakhir['realisasi'] / $subtotalPendapatanBulanTerakhir['anggaran']) * 100
                                    : 0;
                                $pctBelanja = $subtotalBelanjaBulanTerakhir['anggaran'] > 0
                                    ? ($subtotalBelanjaBulanTerakhir['realisasi'] / $subtotalBelanjaBulanTerakhir['anggaran']) * 100
                                    : 0;
                            ?>
                                <tfoot>
                                    <!-- Subtotal Pendapatan Bulan Terakhir -->
                                    <tr class="fw-bold table-success">
                                        <td colspan="5" class="text-end">
                                            TOTAL AKUMULASI PENDAPATAN (<?= strtoupper($namaBulanTerakhir) ?>):
                                        </td>
                                        <td class="text-end"><?= number_format($subtotalPendapatanBulanTerakhir['anggaran'], 0, ',', '.') ?></td>
                                        <td class="text-end"><?= number_format($subtotalPendapatanBulanTerakhir['realisasi'], 0, ',', '.') ?></td>
                                        <td class="text-center"><?= number_format($pctPendapatan, 1, ',', '.') ?>%</td>
                                        <td></td>
                                    </tr>
                                    <!-- Subtotal Belanja Bulan Terakhir -->
                                    <tr class="fw-bold table-danger">
                                        <td colspan="5" class="text-end">
                                            TOTAL AKUMULASI BELANJA (<?= strtoupper($namaBulanTerakhir) ?>):
                                        </td>
                                        <td class="text-end"><?= number_format($subtotalBelanjaBulanTerakhir['anggaran'], 0, ',', '.') ?></td>
                                        <td class="text-end"><?= number_format($subtotalBelanjaBulanTerakhir['realisasi'], 0, ',', '.') ?></td>
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

        // Helper Format Rupiah & Event Edit/Delete
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

        // EDIT AJAX
        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            $.ajax({
                url: '<?= base_url('realisasi/get-detail') ?>/' + id,
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
                    }
                }
            });
        });

        $('#formEdit').submit(function(e) {
            e.preventDefault();
            let id = $('#edit_id').val();
            $.ajax({
                url: '<?= base_url('realisasi/update') ?>/' + id,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#modalEdit').modal('hide');
                        location.reload();
                    }
                }
            });
        });

        // DELETE AJAX
        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id');
            let uraian = $(this).data('uraian');

            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: `Data "${uraian}" akan dihapus permanen!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('realisasi/delete') ?>/' + id,
                        type: 'POST',
                        data: {
                            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                location.reload();
                            }
                        }
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>