<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>

<!-- AdminLTE v4 Container Wrapper -->
<div class="content-wrapper p-3">
    <!-- Content Header -->
    <div class="content-header mb-3">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="m-0 text-dark font-weight-bold">Rekap Laporan Efisiensi Anggaran & SRO</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Rekap Lengkap</li>
            </ol>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Action Toolbar Card -->
            <div class="card card-outline card-primary shadow-sm mb-4">
                <div class="card-body">
                    <div class="row align-items-center g-3">

                        <!-- Live Search Bar -->
                        <div class="col-md-6 col-12">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" id="tableSearchInput" class="form-control border-start-0 ps-0" placeholder="Cari Kode atau Nama SKPD / Program / Kegiatan / Sub-Kegiatan / SRO...">
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-md-6 col-12 text-md-end">
                            <button type="button" class="btn btn-success me-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalImportExcel">
                                <i class="fas fa-file-excel me-1"></i> Import XLS
                            </button>
                            <button type="button" class="btn btn-danger shadow-sm" onclick="window.print()">
                                <i class="fas fa-file-pdf me-1"></i> Cetak PDF
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Data Table Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-3">
                    <h3 class="card-title text-semibold">
                        <i class="fas fa-sitemap text-primary me-2"></i>Rincian Hierarki Rekapitulasi
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool btn-sm btn-outline-secondary" onclick="toggleAllRows(true)">
                            <i class="fas fa-angle-double-down"></i> Expand Semua
                        </button>
                        <button type="button" class="btn btn-tool btn-sm btn-outline-secondary me-2" onclick="toggleAllRows(false)">
                            <i class="fas fa-angle-double-up"></i> Collapse Semua
                        </button>
                    </div>
                </div>

                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover align-middle mb-0" id="rekapTable">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 35%;">Kode & Nama Program / Kegiatan / SRO</th>
                                <th class="text-end" style="width: 15%;">Anggaran (Rp)</th>
                                <th class="text-end" style="width: 15%;">Realisasi (Rp)</th>
                                <th class="text-center" style="width: 10%;">Capaian Realisasi</th>
                                <th class="text-center" style="width: 10%;">Capaian SRO</th>
                                <th class="text-center" style="width: 8%;">Efisiensi</th>
                                <th class="text-center" style="width: 7%;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($rekap)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Data rekapitulasi tidak ditemukan.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($rekap as $kdSkpd => $skpd): ?>
                                    <!-- LEVEL 1: SKPD -->
                                    <tr class="tree-level-1 table-primary fw-bold clickable-row" data-id="skpd-<?= esc($kdSkpd) ?>" onclick="toggleTreeRow('skpd-<?= esc($kdSkpd) ?>')">
                                        <td>
                                            <i class="fas fa-chevron-down tree-icon me-2"></i>
                                            <span class="badge bg-primary text-light me-1">SKPD</span>
                                            [<?= esc($skpd['kode']) ?>] <?= esc($skpd['nama']) ?>
                                        </td>
                                        <td class="text-end"><?= number_format($skpd['anggaran'], 0, ',', '.') ?></td>
                                        <td class="text-end"><?= number_format($skpd['realisasi'], 0, ',', '.') ?></td>
                                        <td class="text-center"><?= $skpd['capaian_realisasi'] ?>%</td>
                                        <td class="text-center"><?= $skpd['capaian_sro'] ?>%</td>
                                        <td class="text-center"><?= $skpd['efisiensi'] ?>%</td>
                                        <td class="text-center">
                                            <span class="badge bg-<?= $skpd['status_efisiensi'] === 'Efisien' ? 'success' : 'danger' ?>">
                                                <?= esc($skpd['status_efisiensi']) ?>
                                            </span>
                                        </td>
                                    </tr>

                                    <?php foreach ($skpd['program'] as $kdProg => $prog): ?>
                                        <!-- LEVEL 2: PROGRAM -->
                                        <tr class="tree-level-2 table-light fw-bold clickable-row parent-skpd-<?= esc($kdSkpd) ?>" data-id="prog-<?= esc($kdProg) ?>" onclick="toggleTreeRow('prog-<?= esc($kdProg) ?>')">
                                            <td class="ps-4">
                                                <i class="fas fa-chevron-down tree-icon me-2"></i>
                                                <span class="badge bg-info text-dark me-1">PROGRAM</span>
                                                [<?= esc($prog['kode']) ?>] <?= esc($prog['nama']) ?>
                                            </td>
                                            <td class="text-end"><?= number_format($prog['anggaran'], 0, ',', '.') ?></td>
                                            <td class="text-end"><?= number_format($prog['realisasi'], 0, ',', '.') ?></td>
                                            <td class="text-center"><?= $prog['capaian_realisasi'] ?>%</td>
                                            <td class="text-center"><?= $prog['capaian_sro'] ?>%</td>
                                            <td class="text-center"><?= $prog['efisiensi'] ?>%</td>
                                            <td class="text-center">
                                                <span class="badge bg-<?= $prog['status_efisiensi'] === 'Efisien' ? 'success' : 'danger' ?>">
                                                    <?= esc($prog['status_efisiensi']) ?>
                                                </span>
                                            </td>
                                        </tr>

                                        <?php foreach ($prog['kegiatan'] as $kdGiat => $giat): ?>
                                            <!-- LEVEL 3: KEGIATAN -->
                                            <tr class="tree-level-3 clickable-row parent-skpd-<?= esc($kdSkpd) ?> parent-prog-<?= esc($kdProg) ?>" data-id="giat-<?= esc($kdGiat) ?>" onclick="toggleTreeRow('giat-<?= esc($kdGiat) ?>')">
                                                <td class="ps-5">
                                                    <i class="fas fa-chevron-down tree-icon me-2"></i>
                                                    <span class="badge bg-secondary me-1">GIAT</span>
                                                    [<?= esc($giat['kode']) ?>] <?= esc($giat['nama']) ?>
                                                </td>
                                                <td class="text-end"><?= number_format($giat['anggaran'], 0, ',', '.') ?></td>
                                                <td class="text-end"><?= number_format($giat['realisasi'], 0, ',', '.') ?></td>
                                                <td class="text-center"><?= $giat['capaian_realisasi'] ?>%</td>
                                                <td class="text-center"><?= $giat['capaian_sro'] ?>%</td>
                                                <td class="text-center"><?= $giat['efisiensi'] ?>%</td>
                                                <td class="text-center">
                                                    <span class="badge bg-<?= $giat['status_efisiensi'] === 'Efisien' ? 'success' : 'danger' ?>">
                                                        <?= esc($giat['status_efisiensi']) ?>
                                                    </span>
                                                </td>
                                            </tr>

                                            <?php foreach ($giat['sub_kegiatan'] as $kdSubGiat => $subGiat): ?>
                                                <!-- LEVEL 4: SUB KEGIATAN -->
                                                <tr class="tree-level-4 clickable-row parent-skpd-<?= esc($kdSkpd) ?> parent-prog-<?= esc($kdProg) ?> parent-giat-<?= esc($kdGiat) ?>" data-id="subgiat-<?= esc($kdSubGiat) ?>" onclick="toggleTreeRow('subgiat-<?= esc($kdSubGiat) ?>')">
                                                    <td style="padding-left: 3.5rem;">
                                                        <i class="fas fa-chevron-down tree-icon me-2"></i>
                                                        <span class="badge bg-warning text-dark me-1">SUB GIAT</span>
                                                        [<?= esc($subGiat['kode']) ?>] <?= esc($subGiat['nama']) ?>
                                                    </td>
                                                    <td class="text-end"><?= number_format($subGiat['anggaran'], 0, ',', '.') ?></td>
                                                    <td class="text-end"><?= number_format($subGiat['realisasi'], 0, ',', '.') ?></td>
                                                    <td class="text-center"><?= $subGiat['capaian_realisasi'] ?>%</td>
                                                    <td class="text-center"><?= $subGiat['capaian_sro'] ?>%</td>
                                                    <td class="text-center"><?= $subGiat['efisiensi'] ?>%</td>
                                                    <td class="text-center">
                                                        <span class="badge bg-<?= $subGiat['status_efisiensi'] === 'Efisien' ? 'success' : 'danger' ?>">
                                                            <?= esc($subGiat['status_efisiensi']) ?>
                                                        </span>
                                                    </td>
                                                </tr>

                                                <?php foreach ($subGiat['list_sro'] as $kdSro => $sro): ?>
                                                    <!-- LEVEL 5: SRO -->
                                                    <tr class="tree-level-5 text-muted parent-skpd-<?= esc($kdSkpd) ?> parent-prog-<?= esc($kdProg) ?> parent-giat-<?= esc($kdGiat) ?> parent-subgiat-<?= esc($kdSubGiat) ?>">
                                                        <td style="padding-left: 4.5rem;">
                                                            <i class="fas fa-minus text-secondary me-2"></i>
                                                            <span class="badge bg-dark me-1">SRO</span>
                                                            [<?= esc($sro['kode']) ?>] <?= esc($sro['nama']) ?>
                                                        </td>
                                                        <td class="text-end"><?= number_format($sro['anggaran'], 0, ',', '.') ?></td>
                                                        <td class="text-end"><?= number_format($sro['realisasi'], 0, ',', '.') ?></td>
                                                        <td class="text-center" colspan="4"><span class="text-muted small">- Detail Target SRO -</span></td>
                                                    </tr>
                                                <?php endforeach; ?>

                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</div>

<!-- Modal Import Excel -->
<div class="modal fade" id="modalImportExcel" tabindex="-1" aria-labelledby="modalImportExcelLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('rekap/import') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalImportExcelLabel"><i class="fas fa-file-upload me-2"></i>Import File Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file_excel" class="form-label">Pilih File Excel (.xls / .xlsx)</label>
                        <input class="form-control" type="file" id="file_excel" name="file_excel" accept=".xls,.xlsx" required>
                    </div>
                    <p class="small text-muted mb-0">Format kolom disesuaikan dengan skema template Laporan SRO terbaru.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-upload me-1"></i> Upload & Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CSS Specific Styling -->
<style>
    .clickable-row {
        cursor: pointer;
        user-select: none;
    }

    .tree-icon {
        transition: transform 0.2s ease-in-out;
    }

    .collapsed .tree-icon {
        transform: rotate(-90deg);
    }

    /* Style penyesuaian cetak PDF/Print */
    @media print {

        .content-header,
        .card-tools,
        #tableSearchInput,
        .btn,
        .modal {
            display: none !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }

        table tr {
            display: table-row !important;
        }
    }
</style>

<!-- JavaScript Interaktivitas & Pencarian -->
<script>
    // Fungsi Expand / Collapse berdasarkan Hierarki ID
    function toggleTreeRow(id) {
        const row = document.querySelector(`tr[data-id="${id}"]`);
        if (!row) return;

        const isCollapsed = row.classList.toggle('collapsed');
        const childRows = document.querySelectorAll(`tr[class*="parent-${id}"]`);

        childRows.forEach(child => {
            if (isCollapsed) {
                child.style.display = 'none';
            } else {
                // Hanya tampilkan jika parent tingkat atasnya juga dalam keadaan ter-expand
                child.style.display = 'table-row';
            }
        });
    }

    // Fungsi Toggle Expand/Collapse Semua Baris
    function toggleAllRows(expand = true) {
        const rows = document.querySelectorAll('#rekapTable tbody tr');
        rows.forEach(row => {
            if (row.classList.contains('clickable-row')) {
                if (expand) {
                    row.classList.remove('collapsed');
                } else {
                    row.classList.add('collapsed');
                }
            } else {
                row.style.display = expand ? 'table-row' : 'none';
            }
        });
    }

    // Live Client-Side Search Filter untuk semua tingkat
    document.getElementById('tableSearchInput').addEventListener('input', function() {
        const filter = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('#rekapTable tbody tr');

        if (filter === '') {
            // Jika kolom pencarian kosong, kembalikan tampilan ke kondisi default
            rows.forEach(row => {
                row.style.display = 'table-row';
                if (row.classList.contains('clickable-row')) {
                    row.classList.remove('collapsed');
                }
            });
            return;
        }

        // Apabila sedang mencari, tampilkan semua baris yang relevan dengan keyword
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            if (text.includes(filter)) {
                row.style.display = 'table-row';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
<?= $this->endSection() ?>