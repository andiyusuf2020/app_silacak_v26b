<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-dark">Rekapitulasi Per SKPD</h1>
                <p class="text-muted mb-0 small">Monitoring Realisasi Anggaran & Capaian SRO (Urutan Capaian SRO Terendah)</p>
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
        <div class="row mb-2 align-items-center">

            <div class="col-sm-6">
                <form action="<?= base_url($wilayah . '/' . $groupuser . '/exportExcelLrfkOpd') ?>" method="POST" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1">
                    <?= csrf_field() ?>
                    <div class="input-group input-group-sm style-filter">
                        <span class="input-group-text bg-white"><i class="bi bi-calendar3"></i></span>
                        <select name="bulan" id="selectBulan" class="form-select">
                            <option value="13">
                                TRIWULAN 1
                            </option>
                            <option value="14">
                                TRIWULAN 2
                            </option>
                            <option value="15">
                                TRIWULAN 3
                            </option>
                            <option value="16">
                                TRIWULAN 4
                            </option>
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                <?php $b = str_pad($i, 2, '0', STR_PAD_LEFT); ?>
                                <option value="<?= $b ?>" <?= $b == $bulan ? 'selected' : '' ?>>
                                    Bulan <?= $b ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                        <input hidden name="kodesubunit" value="<?= $kdSubUnit ?>" required>
                    </div>
                    <button type="submit" id="btnExportXlsx" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1">
                        <i class="bi bi-file-earmark-excel-fill"></i> XLSX
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>