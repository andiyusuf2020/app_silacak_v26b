<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<!-- AdminLTE v4 / Bootstrap 5 Custom CSS Styling -->
<!-- AdminLTE v4 / Bootstrap 5 Custom CSS Styling -->
<style>
    /* Var Warna Level Hirarki (disesuaikan dengan palette AdminLTE v4) */
    :root {
        --bs-skpd-bg: #1e293b;
        /* Dark Slate */
        --bs-program-bg: #334155;
        /* Medium Slate */
        --bs-kegiatan-bg: #475569;
        /* Light Slate */
        --bs-subgiat-bg: #64748b;
        /* Muted Slate */
        --bs-sro-bg: #f8fafc;
        /* Light Gray/White */
    }

    /* Override Card & Table Style AdminLTE */
    .table-rekap {
        margin-bottom: 0;
        font-size: 0.875rem;
        vertical-align: middle;
    }

    .table-rekap th {
        vertical-align: middle;
        text-align: center;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.725rem;
        letter-spacing: 0.05em;
        background-color: var(--bs-dark) !important;
        color: var(--bs-white) !important;
        border-color: rgba(255, 255, 255, 0.1);
        padding: 12px 10px;
    }

    /* Styling Level Baris Tree */
    .row-skpd {
        background-color: var(--bs-skpd-bg) !important;
        color: #ffffff !important;
        font-weight: 700;
    }

    .row-program {
        background-color: var(--bs-program-bg) !important;
        color: #f8fafc !important;
        font-weight: 600;
    }

    .row-kegiatan {
        background-color: var(--bs-kegiatan-bg) !important;
        color: #f1f5f9 !important;
        font-weight: 500;
    }

    .row-subgiat {
        background-color: var(--bs-subgiat-bg) !important;
        color: #ffffff !important;
        font-weight: 500;
    }

    .row-sro {
        background-color: var(--bs-sro-bg) !important;
        color: #334155 !important;
    }

    .row-sro:hover {
        background-color: #e2e8f0 !important;
    }

    /* Indentasi Tree View */
    .indent-1 {
        padding-left: 2rem !important;
    }

    .indent-2 {
        padding-left: 3.5rem !important;
    }

    .indent-3 {
        padding-left: 5rem !important;
    }

    .indent-4 {
        padding-left: 6.5rem !important;
    }

    /* Custom Toggle Icon Collapse */
    .toggle-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        margin-right: 8px;
        cursor: pointer;
        user-select: none;
        transition: transform 0.2s ease, background 0.2s ease;
    }

    .toggle-icon:hover {
        background: rgba(255, 255, 255, 0.4);
    }

    .toggle-icon.collapsed i {
        transform: rotate(-90deg);
    }

    .toggle-icon i {
        transition: transform 0.2s ease;
        font-size: 0.75rem;
    }

    /* Badge & Tag Styling */
    .code-tag {
        background: rgba(255, 255, 255, 0.2);
        padding: 2px 6px;
        border-radius: 4px;
        font-family: var(--bs-font-monospace);
        font-size: 0.775rem;
        margin-right: 6px;
    }

    .row-sro .code-tag {
        background: #cbd5e1;
        color: #0f172a;
    }

    /* =======================================================
       STYLES KHUSUS CETAK PDF / PRINT (HANYA MENCETAK TABEL)
       ======================================================= */
    @media print {

        /* Sembunyikan semua elemen di luar tabel */
        body * {
            visibility: hidden !important;
        }

        /* Tampilkan HANYA area tabel */
        #printableTableArea,
        #printableTableArea * {
            visibility: visible !important;
        }

        /* Posisi tabel berada di paling atas kertas cetak */
        #printableTableArea {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Sembunyikan icon panah collapse saat dicetak */
        .toggle-icon {
            display: none !important;
        }

        /* Reset border dan shadow card */
        .card {
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
        }

        .table-responsive {
            overflow: visible !important;
        }

        .table-rekap {
            width: 100% !important;
            border-collapse: collapse !important;
            font-size: 9pt !important;
        }

        /* Memastikan warna background hirarki tetap muncul di hasil cetak PDF */
        tr {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Pengaturan ukuran halaman kertas PDF (Landscape) */
        @page {
            size: A4 landscape;
            margin: 1cm;
        }
    }
</style>

<!-- Load SheetJS untuk Export Excel -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<!-- Content Header (Page header AdminLTE v4) -->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-5">
                <h3 class="mb-0 fw-bold">
                    <i class="bi bi-diagram-3-fill text-primary me-2"></i>Rekapitulasi Realisasi Bertingkat
                </h3>
                <p class="text-muted small mb-0">Monitoring Laporan Anggaran, Realisasi, dan Sub-Rincian Output (SRO)</p>
            </div>
            <div class="col-sm-7 text-end">
                <div class="d-flex justify-content-end gap-2 flex-wrap">
                    <!-- Tombol Export Excel & PDF -->
                    <button type="button" class="btn btn-success btn-sm rounded-pill px-3" onclick="exportToExcel()">
                        <i class="bi bi-file-earmark-excel me-1"></i> Excel
                    </button>
                    <button type="button" class="btn btn-danger btn-sm rounded-pill px-3" onclick="printPDF()">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Cetak / PDF
                    </button>

                    <div class="vr mx-1"></div>

                    <!-- Tombol Buka/Tutup Tree -->
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill" onclick="expandAll()">
                        <i class="bi bi-folder2-open me-1"></i> Buka Semua
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" onclick="collapseAll()">
                        <i class="bi bi-folder me-1"></i> Tutup Semua
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="app-content">
    <div class="container-fluid">

        <!-- Search Bar Card -->
        <div class="card shadow-sm mb-3" id="searchInputCard">
            <div class="card-body p-3">
                <div class="row align-items-center">
                    <div class="col-md-6 col-lg-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted border-end-0">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control border-start-0 ps-0" placeholder="Cari Program / Kegiatan / Sub-Kegiatan / SRO..." onkeyup="filterTreeTable()">
                            <button class="btn btn-outline-secondary" type="button" id="btnClearSearch" onclick="clearSearch()" style="display: none;">
                                <i class="bi bi-x-circle-fill"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-8 text-end">
                        <small class="text-muted" id="searchResultInfo"></small>
                    </div>
                </div>
            </div>
        </div>

        <!-- AREA YANG DI-PRINT KE PDF (MENGGUNAKAN ID #printableTableArea) -->
        <div class="card card-outline card-primary shadow-sm" id="printableTableArea">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-rekap align-middle" id="rekapTable">
                        <thead>
                            <tr>
                                <th rowspan="2" class="text-start" style="min-width: 380px;">Program / Kegiatan / Sub-Kegiatan / SRO</th>
                                <th rowspan="2" style="width: 150px;">Anggaran (Rp)</th>
                                <th rowspan="2" style="width: 150px;">Realisasi (Rp)</th>
                                <th rowspan="2" style="width: 120px;">Capaian Realisasi</th>
                                <th colspan="2" style="width: 120px;">Indikator SRO</th>
                                <th rowspan="2" style="width: 120px;">Capaian SRO</th>
                            </tr>
                            <tr>
                                <th style="width: 60px;">Total</th>
                                <th style="width: 60px;">Nol</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($rekap)): ?>
                                <?php foreach ($rekap as $skpdId => $skpd):
                                    $nodeSkpd = "node-skpd-" . $skpdId;
                                ?>
                                    <!-- LEVEL 1: SKPD -->
                                    <tr class="row-skpd" id="<?= $nodeSkpd ?>" data-node="<?= $nodeSkpd ?>">
                                        <td class="text-start">
                                            <span class="toggle-icon" onclick="toggleNode('<?= $nodeSkpd ?>', this)">
                                                <i class="bi bi-chevron-down"></i>
                                            </span>
                                            <span class="code-tag"><?= esc($skpd['kode']) ?></span>
                                            <span class="searchable-text"><?= esc($skpd['nama']) ?></span>
                                        </td>
                                        <td class="text-end fw-bold"><?= number_format($skpd['anggaran'], 0, ',', '.') ?></td>
                                        <td class="text-end fw-bold"><?= number_format($skpd['realisasi'], 0, ',', '.') ?></td>
                                        <td class="text-center"><?= renderBadgeCapaian($skpd['capaian_realisasi']) ?></td>
                                        <td class="text-center"><?= $skpd['sro'] ?></td>
                                        <td class="text-center text-warning fw-bold"><?= $skpd['sro_nol'] ?></td>
                                        <td class="text-center"><?= renderBadgeCapaian($skpd['capaian_sro']) ?></td>
                                    </tr>

                                    <?php foreach ($skpd['program'] as $progId => $prog): $nodeProg = "node-prog-" . $skpdId . "-" . $progId;
                                    ?>
                                        <!-- LEVEL 2: PROGRAM -->
                                        <tr class="row-program" id="<?= $nodeProg ?>" data-node="<?= $nodeProg ?>" data-parent="<?= $nodeSkpd ?>">
                                            <td class="text-start indent-1">
                                                <span class="toggle-icon" onclick="toggleNode('<?= $nodeProg ?>', this)">
                                                    <i class="bi bi-chevron-down"></i>
                                                </span>
                                                <span class="code-tag"><?= esc($prog['kode']) ?></span>
                                                <span class="searchable-text"><?= esc($prog['nama']) ?></span>
                                            </td>
                                            <td class="text-end"><?= number_format($prog['anggaran'], 0, ',', '.') ?></td>
                                            <td class="text-end"><?= number_format($prog['realisasi'], 0, ',', '.') ?></td>
                                            <td class="text-center"><?= renderBadgeCapaian($prog['capaian_realisasi']) ?></td>
                                            <td class="text-center"><?= $prog['sro'] ?></td>
                                            <td class="text-center text-warning fw-bold"><?= $prog['sro_nol'] ?></td>
                                            <td class="text-center"><?= renderBadgeCapaian($prog['capaian_sro']) ?></td>
                                        </tr>

                                        <?php foreach ($prog['kegiatan'] as $giatId => $giat):
                                            $nodeGiat = "node-giat-" . $skpdId . "-" . $progId . "-" . $giatId;
                                        ?>
                                            <!-- LEVEL 3: KEGIATAN -->
                                            <tr class="row-kegiatan" id="<?= $nodeGiat ?>" data-node="<?= $nodeGiat ?>" data-parent="<?= $nodeProg ?>">
                                                <td class="text-start indent-2">
                                                    <span class="toggle-icon" onclick="toggleNode('<?= $nodeGiat ?>', this)">
                                                        <i class="bi bi-chevron-down"></i>
                                                    </span>
                                                    <span class="code-tag"><?= esc($giat['kode']) ?></span>
                                                    <span class="searchable-text"><?= esc($giat['nama']) ?></span>
                                                </td>
                                                <td class="text-end"><?= number_format($giat['anggaran'], 0, ',', '.') ?></td>
                                                <td class="text-end"><?= number_format($giat['realisasi'], 0, ',', '.') ?></td>
                                                <td class="text-center"><?= renderBadgeCapaian($giat['capaian_realisasi']) ?></td>
                                                <td class="text-center"><?= $giat['sro'] ?></td>
                                                <td class="text-center text-warning fw-bold"><?= $giat['sro_nol'] ?></td>
                                                <td class="text-center"><?= renderBadgeCapaian($giat['capaian_sro']) ?></td>
                                            </tr>

                                            <?php foreach ($giat['sub_kegiatan'] as $subId => $sub): $nodeSub = "node-sub-" . $skpdId . "-" . $progId . "-" . $giatId . "-" . $subId;
                                            ?>
                                                <!-- LEVEL 4: SUB-KEGIATAN -->
                                                <tr class="row-subgiat" id="<?= $nodeSub ?>" data-node="<?= $nodeSub ?>" data-parent="<?= $nodeGiat ?>">
                                                    <td class="text-start indent-3">
                                                        <span class="toggle-icon" onclick="toggleNode('<?= $nodeSub ?>', this)">
                                                            <i class="bi bi-chevron-down"></i>
                                                        </span>
                                                        <span class="code-tag"><?= esc($sub['kode']) ?></span>
                                                        <span class="searchable-text"><?= esc($sub['nama']) ?></span>
                                                    </td>
                                                    <td class="text-end"><?= number_format($sub['anggaran'], 0, ',', '.') ?></td>
                                                    <td class="text-end"><?= number_format($sub['realisasi'], 0, ',', '.') ?></td>
                                                    <td class="text-center"><?= renderBadgeCapaian($sub['capaian_realisasi']) ?></td>
                                                    <td class="text-center"><?= $sub['sro'] ?></td>
                                                    <td class="text-center text-warning fw-bold"><?= $sub['sro_nol'] ?></td>
                                                    <td class="text-center"><?= renderBadgeCapaian($sub['capaian_sro']) ?></td>
                                                </tr>

                                                <?php foreach ($sub['list_sro'] as $sroId => $sro): ?>
                                                    <!-- LEVEL 5: SRO -->
                                                    <tr class="row-sro" data-parent="<?= $nodeSub ?>">
                                                        <td class="text-start indent-4">
                                                            <i class="bi bi-record-circle me-1 text-muted"></i>
                                                            <span class="code-tag"><?= esc($sro['kode']) ?></span>
                                                            <span class="searchable-text"><?= esc($sro['nama']) ?></span>
                                                        </td>
                                                        <td class="text-end text-secondary"><?= number_format($sro['anggaran'], 0, ',', '.') ?></td>
                                                        <td class="text-end text-secondary"><?= number_format($sro['realisasi'], 0, ',', '.') ?>

                                                            <i class="bi bi-ui-checks-grid"></i>
                                                        </td>
                                                        <td class="text-center text-muted small">-</td>
                                                        <td class="text-center text-muted small">-</td>
                                                        <td class="text-center text-muted small">-</td>
                                                        <td class="text-center text-muted small">-</td>
                                                    </tr>
                                                <?php endforeach; ?>

                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center p-5 text-muted">
                                        <i class="bi bi-inbox fs-1 text-secondary mb-2 d-block"></i>
                                        <h5 class="fw-bold">Data Rekapitulasi Tidak Ditemukan</h5>
                                        <p class="mb-0 small">Silakan periksa filter bulan atau kode SKPD yang Anda pilih.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>

                            <!-- Row jika hasil pencarian tidak ditemukan -->
                            <tr id="noSearchResultRow" style="display: none;">
                                <td colspan="7" class="text-center p-4 text-muted">
                                    <i class="bi bi-search fs-2 d-block mb-2 text-secondary"></i>
                                    Tidak ada data yang cocok dengan pencarian.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
/**
 * Helper internal view untuk menampilkan Badge Bootstrap Capaian
 */
function renderBadgeCapaian($persen)
{
    if ($persen == 0) {
        $bg = 'bg-secondary';
    } elseif ($persen >= 80) {
        $bg = 'bg-success';
    } elseif ($persen >= 50) {
        $bg = 'bg-warning text-dark';
    } else {
        $bg = 'bg-danger';
    }

    return '<span class="badge rounded-pill ' . $bg . ' px-2 py-1">' . number_format($persen, 2, ',', '.') . '%</span>';
}
?>

<!-- Script JS Interaktif Tree Table, Live Search, PDF & Excel Export -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Default collapse saat halaman di-load
        collapseAll();
    });

    /**
     * Toggle Buka / Tutup Node
     */
    function toggleNode(parentNodeId, toggleBtn) {
        const directChildren = document.querySelectorAll(`tr[data-parent="${parentNodeId}"]`);
        if (directChildren.length === 0) return;

        const isExpanding = directChildren[0].style.display === 'none';

        if (isExpanding) {
            directChildren.forEach(child => {
                child.style.display = '';
            });
            toggleBtn.classList.remove('collapsed');
        } else {
            hideDescendants(parentNodeId);
            toggleBtn.classList.add('collapsed');
        }
    }

    /**
     * Sembunyikan anak & keturunan secara rekursif
     */
    function hideDescendants(parentId) {
        const children = document.querySelectorAll(`tr[data-parent="${parentId}"]`);
        children.forEach(child => {
            child.style.display = 'none';

            const childNodeId = child.getAttribute('data-node');
            if (childNodeId) {
                const childToggle = child.querySelector('.toggle-icon');
                if (childToggle) {
                    childToggle.classList.add('collapsed');
                }
                hideDescendants(childNodeId);
            }
        });
    }

    /**
     * Buka Seluruh Node
     */
    function expandAll() {
        document.querySelectorAll('.table-rekap tbody tr:not(#noSearchResultRow)').forEach(row => {
            row.style.display = '';
        });
        document.querySelectorAll('.toggle-icon').forEach(icon => {
            icon.classList.remove('collapsed');
        });
    }

    /**
     * Tutup Seluruh Node (Sisa SKPD saja)
     */
    function collapseAll() {
        document.querySelectorAll('.table-rekap tbody tr[data-parent]').forEach(row => {
            row.style.display = 'none';
        });
        document.querySelectorAll('.toggle-icon').forEach(icon => {
            icon.classList.add('collapsed');
        });
    }

    /**
     * Live Search Filter
     */
    function filterTreeTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase().trim();
        const btnClear = document.getElementById('btnClearSearch');
        const noResultRow = document.getElementById('noSearchResultRow');
        const resultInfo = document.getElementById('searchResultInfo');

        const allRows = document.querySelectorAll('.table-rekap tbody tr:not(#noSearchResultRow)');

        btnClear.style.display = filter.length > 0 ? 'block' : 'none';

        if (filter === '') {
            collapseAll();
            noResultRow.style.display = 'none';
            resultInfo.innerText = '';
            return;
        }

        let matchCount = 0;

        allRows.forEach(row => {
            row.style.display = 'none';
        });

        allRows.forEach(row => {
            const codeTag = row.querySelector('.code-tag')?.innerText.toLowerCase() || '';
            const searchableText = row.querySelector('.searchable-text')?.innerText.toLowerCase() || '';

            if (codeTag.includes(filter) || searchableText.includes(filter)) {
                row.style.display = '';
                matchCount++;
                showAncestors(row);
            }
        });

        if (matchCount === 0) {
            noResultRow.style.display = '';
            resultInfo.innerText = 'Hasil pencarian: 0 ditemukan';
        } else {
            noResultRow.style.display = 'none';
            resultInfo.innerText = `Menampilkan ${matchCount} hasil pencarian`;
        }
    }

    /**
     * Tampilkan seluruh parent/ancestor
     */
    function showAncestors(row) {
        const parentId = row.getAttribute('data-parent');
        if (parentId) {
            const parentRow = document.getElementById(parentId);
            if (parentRow) {
                parentRow.style.display = '';

                const toggleBtn = parentRow.querySelector('.toggle-icon');
                if (toggleBtn) {
                    toggleBtn.classList.remove('collapsed');
                }

                showAncestors(parentRow);
            }
        }
    }

    /**
     * Clear Search
     */
    function clearSearch() {
        const input = document.getElementById('searchInput');
        input.value = '';
        filterTreeTable();
        input.focus();
    }

    /**
     * CETAK HANYA TABEL KE PDF
     */
    function printPDF() {
        window.print();
    }

    /**
     * EXPORT EXCEL SESUAI TAMPILAN TABEL AKTIF (SheetJS)
     */
    function exportToExcel() {
        const originalTable = document.getElementById('rekapTable');
        const clonedTable = originalTable.cloneNode(true);

        const rows = clonedTable.querySelectorAll('tbody tr');
        rows.forEach(row => {
            if (row.style.display === 'none') {
                row.remove();
            } else {
                const toggleIcon = row.querySelector('.toggle-icon');
                if (toggleIcon) toggleIcon.remove();
            }
        });

        const wb = XLSX.utils.table_to_book(clonedTable, {
            sheet: "Rekap Realisasi"
        });
        const today = new Date().toISOString().slice(0, 10);
        const fileName = `Rekap_Realisasi_Bertingkat_${today}.xlsx`;

        XLSX.writeFile(wb, fileName);
    }
</script>
<?= $this->endSection() ?>