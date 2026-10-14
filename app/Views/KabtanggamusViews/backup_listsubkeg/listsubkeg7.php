<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold text-dark">
                    <i class="bi bi-diagram-3-fill text-primary me-2"></i>Rekapitulasi Capaian SKPD & Program
                </h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Laporan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Hierarki SKPD - Program</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="app-content">
    <div class="container-fluid">

        <?php if (empty($rekap)): ?>
            <!-- Alert jika data kosong -->
            <div class="alert alert-warning d-flex align-items-center rounded-3 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-3 me-3"></i>
                <div>
                    <h5 class="alert-heading mb-1 fw-bold">Data Tidak Ditemukan</h5>
                    <p class="mb-0">Tidak ada data rekapitulasi untuk bulan dan SKPD yang dipilih.</p>
                </div>
            </div>
        <?php else: ?>

            <!-- Global Search Filter -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-3 bg-white rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-funnel-fill text-primary fs-5"></i>
                        <span class="fw-bold text-dark">Filter Pencarian Data</span>
                    </div>
                    <div style="min-width: 280px;">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="globalSearchInput" class="form-control bg-light border-start-0" placeholder="Cari Kode/Nama SKPD atau Program...">
                        </div>
                    </div>
                </div>
            </div>

            <?php $skpdIndex = 0;
            foreach ($rekap as $kdSkpd => $skpd): $skpdIndex++; ?>

                <!-- CONTAINER SKPD (CARD LEVEL 1) -->
                <div class="card card-outline card-primary mb-4 shadow-sm border-0 skpd-card">

                    <!-- HEADER SKPD -->
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge bg-primary fs-6 px-3 py-2 rounded-3">SKPD #<?= $skpdIndex ?></span>
                                <div>
                                    <h4 class="mb-1 fw-bold text-dark skpd-name">[<?= htmlspecialchars($skpd['kode']) ?>] <?= htmlspecialchars($skpd['nama']) ?></h4>
                                    <span class="text-muted small">
                                        <i class="bi bi-layers me-1"></i> Total Program: <strong><?= count($skpd['program']) ?></strong>
                                    </span>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <?php if ($skpd['status_efisiensi'] === 'Efisien'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success px-3 py-2 fs-6 rounded-pill">
                                        <i class="bi bi-check-circle-fill me-1"></i> Efisien (<?= sprintf('%.2f', $skpd['efisiensi']) ?>%)
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 fs-6 rounded-pill">
                                        <i class="bi bi-x-circle-fill me-1"></i> Inefisien (<?= sprintf('%.2f', $skpd['efisiensi']) ?>%)
                                    </span>
                                <?php endif; ?>

                                <button class="btn btn-sm btn-outline-secondary rounded-circle ms-2"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapseSkpd<?= $skpdIndex ?>"
                                    aria-expanded="true"
                                    aria-controls="collapseSkpd<?= $skpdIndex ?>">
                                    <i class="bi bi-chevron-down"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="collapseSkpd<?= $skpdIndex ?>" class="collapse show">

                        <!-- RINGKASAN STATISTIK LEVEL SKPD -->
                        <div class="card-body bg-light border-bottom p-3">
                            <div class="row g-3">
                                <div class="col-12 col-sm-6 col-md-3">
                                    <div class="info-box shadow-none border bg-white rounded-3 mb-0">
                                        <span class="info-box-icon bg-info text-white rounded-2"><i class="bi bi-wallet2"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text text-muted">Anggaran SKPD</span>
                                            <span class="info-box-number fw-bold fs-6">Rp <?= number_format($skpd['anggaran'], 0, ',', '.') ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-3">
                                    <div class="info-box shadow-none border bg-white rounded-3 mb-0">
                                        <span class="info-box-icon bg-success text-white rounded-2"><i class="bi bi-cash-stack"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text text-muted">Realisasi SKPD</span>
                                            <span class="info-box-number fw-bold fs-6">Rp <?= number_format($skpd['realisasi'], 0, ',', '.') ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-3">
                                    <div class="info-box shadow-none border bg-white rounded-3 mb-0">
                                        <span class="info-box-icon bg-warning text-white rounded-2"><i class="bi bi-pie-chart-fill"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text text-muted">Capaian Keuangan</span>
                                            <span class="info-box-number fw-bold fs-6"><?= sprintf('%.2f', $skpd['capaian_realisasi']) ?>%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-3">
                                    <div class="info-box shadow-none border bg-white rounded-3 mb-0">
                                        <span class="info-box-icon bg-primary text-white rounded-2"><i class="bi bi-trophy-fill"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text text-muted">Capaian SRO SKPD</span>
                                            <span class="info-box-number fw-bold fs-6"><?= sprintf('%.2f', $skpd['capaian_sro']) ?>%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- DAFTAR PROGRAM (LEVEL 2) -->
                        <div class="card-body p-0">
                            <div class="px-3 pt-3 pb-2 bg-white">
                                <h6 class="fw-bold text-uppercase text-secondary mb-0">
                                    <i class="bi bi-list-nested me-2 text-primary"></i>Rincian Program (Terurut Capaian SRO Tertinggi)
                                </h6>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 program-table">
                                    <thead class="table-light border-top border-bottom">
                                        <tr>
                                            <th class="text-center" style="width: 50px;">#</th>
                                            <th style="width: 140px;">Kode Program</th>
                                            <th>Nama Program</th>
                                            <th class="text-end" style="width: 160px;">Anggaran</th>
                                            <th class="text-end" style="width: 160px;">Realisasi</th>
                                            <th class="text-center" style="width: 120px;">Capaian Keu</th>
                                            <th style="width: 220px;">Capaian SRO</th>
                                            <th class="text-center" style="width: 130px;">Efisiensi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($skpd['program'])): ?>
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">Tidak ada data program pada SKPD ini.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php $progNo = 1;
                                            foreach ($skpd['program'] as $prog): ?>
                                                <tr class="program-row">
                                                    <td class="text-center fw-bold text-muted"><?= $progNo++ ?></td>
                                                    <td>
                                                        <span class="badge bg-secondary-subtle text-secondary font-monospace border px-2 py-1">
                                                            <?= htmlspecialchars($prog['kode']) ?>
                                                        </span>
                                                    </td>
                                                    <td class="fw-semibold text-dark program-name">
                                                        <?= htmlspecialchars($prog['nama']) ?>
                                                    </td>
                                                    <td class="text-end">Rp <?= number_format($prog['anggaran'], 0, ',', '.') ?></td>
                                                    <td class="text-end">Rp <?= number_format($prog['realisasi'], 0, ',', '.') ?></td>

                                                    <!-- Capaian Keuangan -->
                                                    <td class="text-center fw-bold text-dark">
                                                        <?= sprintf('%.2f', $prog['capaian_realisasi']) ?>%
                                                    </td>

                                                    <!-- Capaian SRO dengan Progress Bar -->
                                                    <td>
                                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                                            <span class="small fw-bold text-dark"><?= sprintf('%.2f', $prog['capaian_sro']) ?>%</span>
                                                            <span class="small text-muted" style="font-size: 0.75rem;">
                                                                (<?= ($prog['sro'] - $prog['sro_nol']) ?>/<?= $prog['sro'] ?> SRO)
                                                            </span>
                                                        </div>
                                                        <div class="progress" style="height: 7px;">
                                                            <?php
                                                            $sroVal = $prog['capaian_sro'];
                                                            $barColor = ($sroVal >= 80) ? 'bg-success' : (($sroVal >= 50) ? 'bg-warning' : 'bg-danger');
                                                            ?>
                                                            <div class="progress-bar <?= $barColor ?> progress-bar-striped progress-bar-animated"
                                                                role="progressbar"
                                                                style="width: <?= min(100, max(0, $sroVal)) ?>%"
                                                                aria-valuenow="<?= $sroVal ?>"
                                                                aria-valuemin="0"
                                                                aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <!-- Status Efisiensi -->
                                                    <td class="text-center">
                                                        <?php if ($prog['status_efisiensi'] === 'Efisien'): ?>
                                                            <span class="badge bg-success-subtle text-success border border-success rounded-pill px-2 py-1">
                                                                <i class="bi bi-check-lg me-1"></i>Efisien
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger-subtle text-danger border border-danger rounded-pill px-2 py-1">
                                                                <i class="bi bi-x-lg me-1"></i>Inefisien
                                                            </span>
                                                        <?php endif; ?>
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

            <?php endforeach; ?>

        <?php endif; ?>

    </div>
</div>

<!-- JavaScript untuk Filter Pencarian Realtime SKPD & Program -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("globalSearchInput");

        if (searchInput) {
            searchInput.addEventListener("keyup", function() {
                const filter = this.value.toLowerCase().trim();
                const skpdCards = document.querySelectorAll(".skpd-card");

                skpdCards.forEach(card => {
                    const skpdText = card.querySelector(".skpd-name").textContent.toLowerCase();
                    const programRows = card.querySelectorAll(".program-row");
                    let hasVisibleProgram = false;

                    programRows.forEach(row => {
                        const progText = row.querySelector(".program-name").textContent.toLowerCase();
                        const progCode = row.querySelector(".font-monospace").textContent.toLowerCase();

                        if (progText.includes(filter) || progCode.includes(filter) || skpdText.includes(filter)) {
                            row.style.display = "";
                            hasVisibleProgram = true;
                        } else {
                            row.style.display = "none";
                        }
                    });

                    // Tampilkan card SKPD jika nama SKPD cocok atau ada program di dalamnya yang cocok
                    if (skpdText.includes(filter) || hasVisibleProgram) {
                        card.style.display = "";
                    } else {
                        card.style.display = "none";
                    }
                });
            });
        }
    });
</script>
<?= $this->endSection() ?>