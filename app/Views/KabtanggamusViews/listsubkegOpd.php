<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>


<!-- Librari SheetJS untuk Export XLS Langsung dari Browser (Client-side) -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<!-- Content Wrapper AdminLTE v4 -->
<div class="content-wrapper p-3">
    <!-- Content Header -->
    <div class="content-header mb-3 print-hide">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="m-0 text-dark font-weight-bold">Rekap Laporan Efisiensi Anggaran & SRO</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Rekap Lengkap</li>
            </ol>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Action Toolbar Card (Disembunyikan saat cetak PDF) -->
            <div class="card card-outline card-primary shadow-sm mb-4 print-hide">
                <div class="card-body">
                    <div class="row align-items-center g-3">

                        <!-- Live Search Bar -->
                        <div class="col-md-5 col-12">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" id="tableSearchInput" class="form-control border-start-0 ps-0" placeholder="Cari Kode/Nama SKPD, Program, Giat, Sub-Giat, SRO...">
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-md-7 col-12 text-md-end">
                            <!-- Tombol Import XLS -->
                            <button type="button" class="btn btn-outline-success me-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalImportExcel">
                                <i class="fas fa-file-upload me-1"></i> Import XLS
                            </button>

                            <!-- Tombol Export XLS Data Tabel -->
                            <div class="btn-group me-1">
                                <button type="button" class="btn btn-success shadow-sm" onclick="exportTableToExcel('rekapTable', 'Rekap_Anggaran_SRO')">
                                    <i class="fas fa-file-excel me-1"></i> Export XLS (Tabel)
                                </button>
                                <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-menu-item dropdown-item" href="<?= base_url('rekap/exportExcel') ?>">
                                            <i class="fas fa-download me-2 text-success"></i> Export All Data (Backend)
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <!-- Tombol Cetak PDF (Hanya Tabel) -->
                            <button type="button" class="btn btn-danger shadow-sm" onclick="printTableOnly()">
                                <i class="fas fa-print me-1"></i> Cetak PDF (Tabel Data)
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Container Tabel Utama (Ditandai untuk Cetak) -->
            <div id="printArea" class="card shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-3 d-flex justify-content-between align-items-center">
                    <h3 class="card-title text-semibold mb-0">
                        <i class="fas fa-sitemap text-primary me-2 print-hide"></i>Rincian Hierarki Rekapitulasi Anggaran & SRO
                    </h3>
                    <div class="card-tools print-hide">
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
                                <th class="text-center" style="width: 7%;">Efisiensi</th>
                                <th class="text-center" style="width: 8%;">Status / Aksi</th>
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
                                            <i class="fas fa-chevron-down tree-icon me-2 print-hide"></i>
                                            <span class="badge bg-primary text-light me-1 print-badge">SKPD</span>
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
                                                <i class="fas fa-chevron-down tree-icon me-2 print-hide"></i>
                                                <span class="badge bg-info text-dark me-1 print-badge">PROGRAM</span>
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
                                                    <i class="fas fa-chevron-down tree-icon me-2 print-hide"></i>
                                                    <span class="badge bg-secondary me-1 print-badge">GIAT</span>
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
                                                        <i class="fas fa-chevron-down tree-icon me-2 print-hide"></i>
                                                        <span class="badge bg-warning text-dark me-1 print-badge">SUB GIAT</span>
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
                                                    <!-- LEVEL 5: SRO (Dengan Tombol Edit Action) -->
                                                    <tr class="tree-level-5 text-muted parent-skpd-<?= esc($kdSkpd) ?> parent-prog-<?= esc($kdProg) ?> parent-giat-<?= esc($kdGiat) ?> parent-subgiat-<?= esc($kdSubGiat) ?>">
                                                        <td style="padding-left: 4.5rem;">
                                                            <i class="fas fa-minus text-secondary me-2 print-hide"></i>
                                                            <span class="badge bg-dark me-1 print-badge">SRO</span>
                                                            [<?= esc($sro['kode']) ?>] <?= esc($sro['nama']) ?>
                                                        </td>
                                                        <td class="text-end"><?= number_format($sro['anggaran'], 0, ',', '.') ?></td>
                                                        <td class="text-end"><?= number_format($sro['realisasi'], 0, ',', '.') ?></td>
                                                        <td class="text-center" colspan="3"><span class="text-muted small">- Detail Target SRO -</span></td>
                                                        <td class="text-center print-hide">
                                                            <button type="button" class="btn btn-xs btn-outline-warning shadow-sm"
                                                                title="Edit Realisasi SRO"
                                                                onclick="openEditSroModal(<?= htmlspecialchars(json_encode([
                                                                                                'id_sro' => $kdSro,
                                                                                                'kode_sro' => $sro['kode'],
                                                                                                'nama_sro' => $sro['nama'],
                                                                                                'kode_giat' => $giat['kode'],
                                                                                                'nama_giat' => $giat['nama'],
                                                                                                'anggaran' => $sro['anggaran'],
                                                                                                'realisasi' => $sro['realisasi']
                                                                                            ]), ENT_QUOTES, 'UTF-8') ?>)">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </button>
                                                        </td>
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

<!-- Modal Edit Realisasi SRO -->
<div class="modal fade" id="modalEditSro" tabindex="-1" aria-labelledby="modalEditSroLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= base_url('rekap/update_sro') ?>" method="post">
                <?= csrf_field() ?> <!-- Token CSRF jika menggunakan CodeIgniter 4 -->

                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold" id="modalEditSroLabel">
                        <i class="fas fa-edit me-2"></i>Edit Realisasi SRO
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="edit_id_sro" name="id_sro">

                    <!-- Informasi Kegiatan -->
                    <div class="mb-3 p-2 bg-light rounded border">
                        <label class="form-label text-muted mb-0 small fw-bold">KEGIATAN:</label>
                        <div id="edit_info_giat" class="fw-bold text-dark">-</div>
                    </div>

                    <!-- Informasi SRO -->
                    <div class="mb-3 p-2 bg-light rounded border">
                        <label class="form-label text-muted mb-0 small fw-bold">SRO YANG DIEDIT:</label>
                        <div id="edit_info_sro" class="fw-bold text-primary">-</div>
                    </div>

                    <div class="row g-2">
                        <!-- Pagu Anggaran (Readonly) -->
                        <div class="col-md-6 mb-3">
                            <label for="edit_anggaran_display" class="form-label">Pagu Anggaran (Rp)</label>
                            <input type="text" class="form-control bg-light" id="edit_anggaran_display" readonly>
                        </div>

                        <!-- Realisasi SRO (Dapat Diubah) -->
                        <div class="col-md-6 mb-3">
                            <label for="edit_realisasi" class="form-label fw-bold">Realisasi SRO (Rp)</label>
                            <input type="number" class="form-control fw-bold" id="edit_realisasi" name="realisasi" min="0" step="1" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CSS Custom Styling & Isolasi Cetak PDF -->
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

    /* REGULASI KHUSUS CETAK PDF / PRINT (HANYA TABEL DATA) */
    @media print {

        /* Sembunyikan seluruh komponen halaman */
        body * {
            visibility: hidden !important;
        }

        /* Sembunyikan elemen non-tabel secara permanen */
        .print-hide,
        .app-header,
        .app-sidebar,
        .app-footer,
        .modal,
        .btn {
            display: none !important;
        }

        /* Tampilkan HANYA area tabel data (#printArea) */
        #printArea,
        #printArea * {
            visibility: visible !important;
        }

        #printArea {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            border: none !important;
            box-shadow: none !important;
        }

        /* Buka seluruh row yang ter-collapse agar data tercetak lengkap */
        #rekapTable tbody tr {
            display: table-row !important;
        }

        /* Styling tabel agar optimal di kertas PDF */
        table {
            width: 100% !important;
            border-collapse: collapse !important;
            font-size: 11px !important;
        }

        table th,
        table td {
            border: 1px solid #333 !important;
            padding: 5px !important;
        }

        thead {
            background-color: #212529 !important;
            color: #fff !important;
            -webkit-print-color-adjust: exact;
        }

        /* Opsi cetak lanskap otomatis */
        @page {
            size: landscape;
            margin: 1cm;
        }
    }
</style>

<!-- JavaScript Interaktivitas, Modal Action, Export XLS, dan Print PDF -->
<script>
    // 1. Function Toggle Row Tree Level (Expand / Collapse)
    function toggleTreeRow(id) {
        const row = document.querySelector(`tr[data-id="${id}"]`);
        if (!row) return;

        const isCollapsed = row.classList.toggle('collapsed');
        const childRows = document.querySelectorAll(`tr[class*="parent-${id}"]`);

        childRows.forEach(child => {
            if (isCollapsed) {
                child.style.display = 'none';
            } else {
                child.style.display = 'table-row';
            }
        });
    }

    // 2. Expand/Collapse Semua Row
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

    // 3. Live Search Filter Client-side
    document.getElementById('tableSearchInput').addEventListener('input', function() {
        const filter = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('#rekapTable tbody tr');

        if (filter === '') {
            rows.forEach(row => {
                row.style.display = 'table-row';
                if (row.classList.contains('clickable-row')) {
                    row.classList.remove('collapsed');
                }
            });
            return;
        }

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            if (text.includes(filter)) {
                row.style.display = 'table-row';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // 4. Export Data Tabel ke File Excel (.xlsx) Client-Side
    function exportTableToExcel(tableID, filename = '') {
        toggleAllRows(true);

        const table = document.getElementById(tableID);
        const wb = XLSX.utils.table_to_book(table, {
            sheet: "Rekap Data"
        });
        const fileNameFull = (filename ? filename : 'Export_Rekap_Anggaran') + '_' + new Date().toISOString().slice(0, 10) + '.xlsx';

        XLSX.writeFile(wb, fileNameFull);
    }

    // 5. Cetak PDF Khusus Tabel Data
    function printTableOnly() {
        toggleAllRows(true);
        window.print();
    }

    // 6. Function Membuka Modal Edit SRO dan Memuat Data SRO
    function openEditSroModal(data) {
        // Set ID SRO pada hidden input
        document.getElementById('edit_id_sro').value = data.id_sro;

        // Tampilkan Informasi Kegiatan
        document.getElementById('edit_info_giat').innerText = `[${data.kode_giat}] ${data.nama_giat}`;

        // Tampilkan Informasi SRO
        document.getElementById('edit_info_sro').innerText = `[${data.kode_sro}] ${data.nama_sro}`;

        // Format tampilan anggaran ke format Rupiah
        const formattedAnggaran = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(data.anggaran);
        document.getElementById('edit_anggaran_display').value = formattedAnggaran;

        // Set nilai realisasi saat ini
        document.getElementById('edit_realisasi').value = data.realisasi;

        // Tampilkan Modal via Bootstrap 5
        const modalEdit = new bootstrap.Modal(document.getElementById('modalEditSro'));
        modalEdit.show();
    }
</script>
<?= $this->endSection() ?>