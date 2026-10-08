<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<!-- AdminLTE v4 & Bootstrap 5 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta2/dist/css/adminlte.min.css"> -->

<style>
    .app-content-header {
        background: #ffffff;
        border-bottom: 1px solid #e9ecef;
    }

    .stat-card-preview {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card-preview:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.08);
    }

    .icon-box {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 1.25rem;
    }
</style>
<!-- Main Content -->
<div class="app-content">
    <!-- Main Content Header -->
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold text-dark"><i class="bi bi-speedometer2 text-primary me-2"></i>Dashboard Pembangunan</h3>
                <small class="text-secondary">Kelola statistik & indikator kinerja Kabupaten Tanggamus</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active">Dashboard Input</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Container Alert -->
    <div id="alertContainer">
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    </div>

    <form id="formDashboardStat" action="<?= base_url('admin/dashboard-stats/update') ?>" method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="enc_id" value="<?= esc($encryptedId) ?>">

        <!-- Preview Live Widgets -->
        <div class="row g-3 mb-4">
            <div class="col-12">
                <h5 class="fw-semibold text-secondary mb-2"><i class="bi bi-eye me-1"></i> Live Preview Display</h5>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card card-custom stat-card-preview border-0 shadow-sm p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-primary-subtle text-primary me-3"><i class="bi bi-building"></i></div>
                        <div>
                            <small class="text-muted d-block">Perangkat Daerah</small>
                            <h4 class="mb-0 fw-bold" id="prev_perangkat_daerah"><?= esc($stat['jumlah_perangkat_daerah']) ?></h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card card-custom stat-card-preview border-0 shadow-sm p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-success-subtle text-success me-3"><i class="bi bi-map"></i></div>
                        <div>
                            <small class="text-muted d-block">Kecamatan</small>
                            <h4 class="mb-0 fw-bold" id="prev_kecamatan"><?= esc($stat['jumlah_kecamatan']) ?></h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card card-custom stat-card-preview border-0 shadow-sm p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-warning-subtle text-warning me-3"><i class="bi bi-houses"></i></div>
                        <div>
                            <small class="text-muted d-block">Tiuh / Kampung</small>
                            <h4 class="mb-0 fw-bold" id="prev_tiuh_kampung"><?= esc($stat['jumlah_tiuh_kampung']) ?></h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card card-custom stat-card-preview border-0 shadow-sm p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-info-subtle text-info me-3"><i class="bi bi-cash-stack"></i></div>
                        <div>
                            <small class="text-muted d-block">Total APBD</small>
                            <h4 class="mb-0 fw-bold" id="prev_apbd"><?= esc($stat['total_anggaran_apbd']) ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Form Sections -->
        <div class="row g-4">
            <!-- Section 1: Kewilayahan & Anggaran -->
            <div class="col-lg-6">
                <div class="card card-primary card-outline shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="card-title fw-bold m-0 text-primary"><i class="bi bi-geo-alt me-2"></i>Data Wilayah & APBD</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="jumlah_perangkat_daerah" class="form-label fw-semibold">Jumlah Perangkat Daerah</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-building"></i></span>
                                <input type="number" class="form-control live-input" id="jumlah_perangkat_daerah" name="jumlah_perangkat_daerah" data-target="#prev_perangkat_daerah" value="<?= esc(old('jumlah_perangkat_daerah', $stat['jumlah_perangkat_daerah'])) ?>" required>
                            </div>
                            <small class="text-muted">Total instansi/dinas dibawah naungan daerah</small>
                        </div>

                        <div class="mb-3">
                            <label for="jumlah_kecamatan" class="form-label fw-semibold">Jumlah Kecamatan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-map"></i></span>
                                <input type="number" class="form-control live-input" id="jumlah_kecamatan" name="jumlah_kecamatan" data-target="#prev_kecamatan" value="<?= esc(old('jumlah_kecamatan', $stat['jumlah_kecamatan'])) ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="jumlah_tiuh_kampung" class="form-label fw-semibold">Jumlah Tiuh / Kampung</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-houses"></i></span>
                                <input type="number" class="form-control live-input" id="jumlah_tiuh_kampung" name="jumlah_tiuh_kampung" data-target="#prev_tiuh_kampung" value="<?= esc(old('jumlah_tiuh_kampung', $stat['jumlah_tiuh_kampung'])) ?>" required>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label for="total_anggaran_apbd" class="form-label fw-semibold">Total Anggaran APBD</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-cash-stack"></i></span>
                                <input type="text" class="form-control live-input" id="total_anggaran_apbd" name="total_anggaran_apbd" data-target="#prev_apbd" value="<?= esc(old('total_anggaran_apbd', $stat['total_anggaran_apbd'])) ?>" placeholder="Contoh: 1.5T" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Indikator Kinerja & Sosial -->
            <div class="col-lg-6">
                <div class="card card-success card-outline shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="card-title fw-bold m-0 text-success"><i class="bi bi-graph-up-arrow me-2"></i>Indikator Kinerja & Sosial</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="index_sakip" class="form-label fw-semibold">Index SAKIP</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-bank2"></i></span>
                                <input type="text" class="form-control" id="index_sakip" name="index_sakip" value="<?= esc(old('index_sakip', $stat['index_sakip'])) ?>" placeholder="Contoh: (B) 90.19" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="index_rb" class="form-label fw-semibold">Index Reformasi Birokrasi (IRB)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-rocket-takeoff"></i></span>
                                <input type="text" class="form-control" id="index_rb" name="index_rb" value="<?= esc(old('index_rb', $stat['index_rb'])) ?>" placeholder="Contoh: 7,392" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="tingkat_kemiskinan" class="form-label fw-semibold">Tingkat Kemiskinan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-pie-chart"></i></span>
                                <input type="text" class="form-control" id="tingkat_kemiskinan" name="tingkat_kemiskinan" value="<?= esc(old('tingkat_kemiskinan', $stat['tingkat_kemiskinan'])) ?>" placeholder="Contoh: +28.5%" required>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label for="angka_stunting" class="form-label fw-semibold">Angka Stunting</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-activity"></i></span>
                                <input type="text" class="form-control" id="angka_stunting" name="angka_stunting" value="<?= esc(old('angka_stunting', $stat['angka_stunting'])) ?>" placeholder="Contoh: 99.9%" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Action Buttons -->
        <div class="row mt-4">
            <div class="col-12 text-end">
                <button type="reset" class="btn btn-light border px-4 py-2 me-2">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Form
                </button>
                <button type="submit" class="btn btn-primary px-4 py-2 shadow-sm" id="btnSubmit">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </form>

</div>

<!-- JS Dependencies -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta2/dist/js/adminlte.min.js"></script> -->

<script>
    $(document).ready(function() {
        // Live preview updater untuk statistik ringkas
        $('.live-input').on('input', function() {
            let target = $(this).data('target');
            let val = $(this).val();
            $(target).text(val ? val : '-');
        });

        // Form submission via AJAX
        $('#formDashboardStat').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let btn = $('#btnSubmit');
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    btn.prop('disabled', false).html('<i class="bi bi-save me-1"></i> Simpan Perubahan');

                    if (response.status === 'success') {
                        $('#alertContainer').html(`
                        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);
                        $('html, body').animate({
                            scrollTop: 0
                        }, 'fast');
                    } else {
                        let errHtml = '<ul class="mb-0 ps-3">';
                        if (response.errors) {
                            $.each(response.errors, function(key, val) {
                                errHtml += `<li>${val}</li>`;
                            });
                        } else if (response.message) {
                            errHtml += `<li>${response.message}</li>`;
                        }
                        errHtml += '</ul>';

                        $('#alertContainer').html(`
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Gagal Menyimpan:</strong>
                            ${errHtml}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);
                        $('html, body').animate({
                            scrollTop: 0
                        }, 'fast');
                    }
                },
                error: function() {
                    btn.prop('disabled', false).html('<i class="bi bi-save me-1"></i> Simpan Perubahan');
                    $('#alertContainer').html(`
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Terjadi kesalahan sistem/koneksi server.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
                    $('html, body').animate({
                        scrollTop: 0
                    }, 'fast');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>