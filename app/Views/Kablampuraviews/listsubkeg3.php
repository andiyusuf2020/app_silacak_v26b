<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<style>
    :root {
        --font-main: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        /* Skema Warna Hirarki */
        --color-skpd: #0f172a;
        /* Dark Slate / Navy */
        --color-program: #1e293b;
        /* Slate Darker */
        --color-kegiatan: #334155;
        /* Slate Medium */
        --color-subgiat: #475569;
        /* Slate Light */
        --color-sro: #f8fafc;
        /* Off White / Light Neutral */
    }

    body {
        font-family: var(--font-main);
        background-color: #f1f5f9;
        color: #334155;
    }

    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .table-rekap {
        margin-bottom: 0;
        font-size: 0.88rem;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-rekap th {
        vertical-align: middle;
        text-align: center;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        background: #0284c7;
        color: #ffffff;
        border: none;
        padding: 12px;
    }

    .table-rekap td {
        vertical-align: middle;
        padding: 10px 14px;
        border-color: rgba(226, 232, 240, 0.6);
        transition: background-color 0.2s ease;
    }

    /* --- STYLING BARIS HIRARKI --- */

    /* LEVEL 1: SKPD */
    .row-skpd {
        background-color: var(--color-skpd) !important;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.95rem;
    }

    .row-skpd td {
        border-bottom: 2px solid #0284c7 !important;
    }

    /* LEVEL 2: PROGRAM */
    .row-program {
        background-color: var(--color-program) !important;
        color: #f8fafc !important;
        font-weight: 600;
    }

    /* LEVEL 3: KEGIATAN */
    .row-kegiatan {
        background-color: var(--color-kegiatan) !important;
        color: #f1f5f9 !important;
        font-weight: 500;
    }

    /* LEVEL 4: SUB-KEGIATAN */
    .row-subgiat {
        background-color: var(--color-subgiat) !important;
        color: #ffffff !important;
        font-weight: 500;
    }

    /* LEVEL 5: SRO */
    .row-sro {
        background-color: var(--color-sro) !important;
        color: #334155 !important;
        font-weight: 400;
    }

    .row-sro:hover {
        background-color: #e2e8f0 !important;
    }

    /* Indentasi Tree */
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

    /* Icon Toggle Expand/Collapse */
    .toggle-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.15);
        margin-right: 8px;
        cursor: pointer;
        user-select: none;
        transition: transform 0.2s ease, background 0.2s ease;
    }

    .toggle-icon:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .toggle-icon.collapsed {
        transform: rotate(-90deg);
    }

    /* Badges Capaian */
    .badge-capaian {
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .badge-high {
        background-color: #059669;
        color: #fff;
    }

    /* > 80% */
    .badge-medium {
        background-color: #d97706;
        color: #fff;
    }

    /* 50% - 80% */
    .badge-low {
        background-color: #dc2626;
        color: #fff;
    }

    /* < 50% */
    .badge-zero {
        background-color: #64748b;
        color: #fff;
    }

    /* 0% */

    /* Label / Tag Kode */
    .code-tag {
        background: rgba(255, 255, 255, 0.2);
        padding: 2px 6px;
        border-radius: 4px;
        font-family: monospace;
        font-size: 0.8rem;
        margin-right: 6px;
    }

    .row-sro .code-tag {
        background: #cbd5e1;
        color: #0f172a;
    }
</style>
<div class="container-fluid px-4">

    <!-- Header Page -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1 text-dark">
                <i class="fa-solid fa-sitemap me-2 text-primary"></i>Rekapitulasi Realisasi Bertingkat
            </h3>
            <p class="text-muted mb-0">Monitoring Laporan Anggaran, Realisasi, dan Sub-Rincian Output (SRO)</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-sm rounded-pill px-3" onclick="expandAll()">
                <i class="fa-solid fa-folder-open me-1"></i> Buka Semua
            </button>
            <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="collapseAll()">
                <i class="fa-solid fa-folder me-1"></i> Tutup Semua
            </button>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="card card-custom">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-rekap align-middle">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-start" style="min-width: 380px;">Program / Kegiatan / Sub-Kegiatan / SRO</th>
                            <th rowspan="2" style="width: 150px;">Anggaran (Rp)</th>
                            <th rowspan="2" style="width: 150px;">Realisasi (Rp)</th>
                            <th rowspan="2" style="width: 110px;">Capaian Realisasi</th>
                            <th colspan="2" style="width: 120px;">Indikator SRO</th>
                            <th rowspan="2" style="width: 110px;">Capaian SRO</th>
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
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </span>
                                        <span class="code-tag"><?= esc($skpd['kode']) ?></span>
                                        <span><?= esc($skpd['nama']) ?></span>
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
                                                <i class="nav-arrow bi bi-chevron-right"></i>
                                            </span>
                                            <span class="code-tag"><?= esc($prog['kode']) ?></span>
                                            <span><?= esc($prog['nama']) ?></span>
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
                                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                                </span>
                                                <span class="code-tag"><?= esc($giat['kode']) ?></span>
                                                <span><?= esc($giat['nama']) ?></span>
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
                                                        <i class="nav-arrow bi bi-chevron-right"></i>
                                                    </span>
                                                    <span class="code-tag"><?= esc($sub['kode']) ?></span>
                                                    <span><?= esc($sub['nama']) ?></span>
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
                                                        <i class="nav-icon bi bi-circle"></i>
                                                        <span class="code-tag"><?= esc($sro['kode']) ?></span>
                                                        <span><?= esc($sro['nama']) ?></span>
                                                    </td>
                                                    <td class="text-end text-secondary"><?= number_format($sro['anggaran'], 0, ',', '.') ?></td>
                                                    <td class="text-end text-secondary"><?= number_format($sro['realisasi'], 0, ',', '.') ?></td>
                                                    <td class="text-center text-muted fs-7">-</td>
                                                    <td class="text-center text-muted fs-7">-</td>
                                                    <td class="text-center text-muted fs-7">-</td>
                                                    <td class="text-center text-muted fs-7">-</td>
                                                </tr>
                                            <?php endforeach; ?>

                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center p-5 text-muted">
                                    <i class="fa-solid fa-inbox fa-3x mb-3 text-secondary"></i>
                                    <h5>Data Rekapitulasi Tidak Ditemukan</h5>
                                    <p class="mb-0 fs-7">Silakan periksa filter bulan atau kode SKPD yang Anda pilih.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
/**
 * Helper internal view untuk menampilkan Badge Persentase Capaian
 */
function renderBadgeCapaian($persen)
{
    if ($persen == 0) {
        $class = 'badge-zero';
    } elseif ($persen >= 80) {
        $class = 'badge-high';
    } elseif ($persen >= 50) {
        $class = 'badge-medium';
    } else {
        $class = 'badge-low';
    }

    return '<span class="badge-capaian ' . $class . '">' . number_format($persen, 2, ',', '.') . '%</span>';
}
?>

<!-- JavaScript Interaktif Accordion/Collapsible Tree -->
<script>
    /**
     * Menyembunyikan / Menampilkan node hirarki (Expand / Collapse)
     */
    function toggleNode(parentNodeId, toggleBtn) {
        const directChildren = document.querySelectorAll(`tr[data-parent="${parentNodeId}"]`);
        if (directChildren.length === 0) return;

        const isExpanding = directChildren[0].style.display === 'none';

        if (isExpanding) {
            // TAMPILKAN: Hanya anak langsung
            directChildren.forEach(child => {
                child.style.display = '';
            });
            toggleBtn.classList.remove('collapsed');
        } else {
            // SEMBUNYIKAN: Anak langsung + Seluruh Turunan di Bawahnya (Recursive Collapse)
            hideDescendants(parentNodeId);
            toggleBtn.classList.add('collapsed');
        }
    }

    /**
     * Menyembunyikan turunan secara rekursif
     */
    function hideDescendants(parentId) {
        const children = document.querySelectorAll(`tr[data-parent="${parentId}"]`);
        children.forEach(child => {
            child.style.display = 'none';

            // Mengubah icon toggle menjadi collapsed pada anak yang memiliki keturunan
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
     * Buka semua hirarki
     */
    function expandAll() {
        document.querySelectorAll('.table-rekap tbody tr').forEach(row => {
            row.style.display = '';
        });
        document.querySelectorAll('.toggle-icon').forEach(icon => {
            icon.classList.remove('collapsed');
        });
    }

    /**
     * Tutup semua hirarki (Menyisakan Level SKPD saja)
     */
    function collapseAll() {
        document.querySelectorAll('.table-rekap tbody tr[data-parent]').forEach(row => {
            row.style.display = 'none';
        });
        document.querySelectorAll('.toggle-icon').forEach(icon => {
            icon.classList.add('collapsed');
        });
    }
</script>


<?= $this->endSection() ?>