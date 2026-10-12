<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .tabulator .tabulator-row.tabulator-tree-level-0 {
        font-weight: bold;
        background-color: #f8f9fa;
    }

    .tabulator .tabulator-row.tabulator-tree-level-1 {
        font-weight: 600;
        background-color: #ffffff;
    }

    .tabulator .tabulator-row.tabulator-tree-level-2 {
        font-weight: normal;
    }
</style>
<!-- Main Content -->
<div class="app-content">
    <div class="container-fluid">

        <!-- Row Grafik Visualisasi -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-bar me-1"></i> Grafik Anggaran vs Realisasi per Jenis Belanja</h3>
                    </div>
                    <div class="card-body">
                        <div style="height: 300px;">
                            <canvas id="jenisChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row Tabel Tree View Tabulator -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title"><i class="fas fa-layer-group me-1"></i> Data Hierarki: Unit SKPD &rarr; Sub Kegiatan &rarr; Jenis Belanja</h3>
                        <div class="card-tools">
                            <button id="btn-expand-all" class="btn btn-outline-secondary btn-sm me-1"><i class="fas fa-folder-open me-1"></i> Buka Semua</button>
                            <button id="btn-collapse-all" class="btn btn-outline-secondary btn-sm me-2"><i class="fas fa-folder me-1"></i> Tutup Semua</button>
                            <button id="download-excel" class="btn btn-success btn-sm me-1"><i class="fas fa-file-excel me-1"></i> Excel</button>
                            <button id="download-pdf" class="btn btn-danger btn-sm"><i class="fas fa-file-pdf me-1"></i> PDF</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Live Filter -->
                        <div class="mb-3">
                            <input type="text" id="filter-text" class="form-control" placeholder="Cari berdasarkan kata kunci di semua level...">
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- SCRIPTS -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/@adminlte/adminlte@v4.0.0-beta/dist/js/adminlte.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@6.2.5/dist/js/tabulator.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.autotable.min.js"></script>

<script>
    const rawData = <?= json_encode($rekap); ?>;

    // Function pembantu format Rupiah
    const formatRupiah = (val) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(val || 0);
    };

    // --- Transformasi Data Flat menjadi Data Pohon (Tree Data) ---
    // Level 1: Unit SKPD -> Level 2: Sub Kegiatan -> Level 3: Jenis / SRO (Detail)
    function buildTreeData(flatData) {
        const tree = [];
        const unitMap = {};

        flatData.forEach(item => {
            const unitKey = item.kode_unit_skpd + ' - ' + item.nama_unit_skpd;
            const subGiatKey = item.nama_sub_giat;

            // Level 1: Unit SKPD
            if (!unitMap[unitKey]) {
                unitMap[unitKey] = {
                    hirarki_nama: unitKey,
                    level_type: "Unit SKPD",
                    jumlah_total_anggaran: 0,
                    total_realisasi: 0,
                    _childrenMap: {},
                    _children: []
                };
                tree.push(unitMap[unitKey]);
            }

            // Level 2: Sub Kegiatan
            let unitNode = unitMap[unitKey];
            if (!unitNode._childrenMap[subGiatKey]) {
                let subNode = {
                    hirarki_nama: "Sub Giat: " + subGiatKey,
                    level_type: "Sub Kegiatan",
                    nama_program: item.nama_program,
                    nama_giat: item.nama_giat,
                    jumlah_total_anggaran: 0,
                    total_realisasi: 0,
                    _children: []
                };
                unitNode._childrenMap[subGiatKey] = subNode;
                unitNode._children.push(subNode);
            }

            // Accumulate totals
            const ang = parseFloat(item.jumlah_total_anggaran) || 0;
            const rea = parseFloat(item.total_realisasi) || 0;

            let subGiatNode = unitNode._childrenMap[subGiatKey];

            unitNode.jumlah_total_anggaran += ang;
            unitNode.total_realisasi += rea;
            subGiatNode.jumlah_total_anggaran += ang;
            subGiatNode.total_realisasi += rea;

            // Level 3: Detail Jenis & SRO
            subGiatNode._children.push({
                hirarki_nama: item.nama_jenis + " (" + item.nama_sro + ")",
                level_type: "Jenis / SRO",
                nama_jenis: item.nama_jenis,
                nama_rincian_objek: item.nama_rincian_objek,
                nama_sro: item.nama_sro,
                jumlah_total_anggaran: ang,
                total_realisasi: rea,
                capaian_realisasi: ang > 0 ? (rea / ang) * 100 : 0
            });
        });

        // Calculate percentage for node parents
        const calculatePercentages = (nodes) => {
            nodes.forEach(node => {
                node.capaian_realisasi = node.jumlah_total_anggaran > 0 ? (node.total_realisasi / node.jumlah_total_anggaran) * 100 : 0;
                if (node._children && node._children.length > 0) {
                    calculatePercentages(node._children);
                }
            });
        };
        calculatePercentages(tree);

        return tree;
    }

    const treeData = buildTreeData(rawData);

    // --- Inisialisasi Tabulator Tree View ---
    var table = new Tabulator("#tabel-tree", {
        data: treeData,
        dataTree: true,
        dataTreeStartExpanded: true,
        dataTreeChildField: "_children",
        layout: "fitColumns",
        responsiveLayout: "collapse",
        pagination: "local",
        paginationSize: 15,
        columns: [{
                title: "Struktur Hierarki (Unit SKPD / Sub Giat / Jenis)",
                field: "hirarki_nama",
                minWidth: 350,
                responsive: 0
            },
            {
                title: "Tipe Level",
                field: "level_type",
                width: 130,
                hozAlign: "center",
                formatter: function(cell) {
                    let val = cell.getValue();
                    let bg = val === 'Unit SKPD' ? 'bg-primary' : (val === 'Sub Kegiatan' ? 'bg-info text-dark' : 'bg-secondary');
                    return `<span class="badge ${bg}">${val}</span>`;
                }
            },
            {
                title: "Total Anggaran",
                field: "jumlah_total_anggaran",
                minWidth: 160,
                hozAlign: "right",
                formatter: function(cell) {
                    return formatRupiah(cell.getValue());
                }
            },
            {
                title: "Total Realisasi",
                field: "total_realisasi",
                minWidth: 160,
                hozAlign: "right",
                formatter: function(cell) {
                    return formatRupiah(cell.getValue());
                }
            },
            {
                title: "Capaian (%)",
                field: "capaian_realisasi",
                minWidth: 120,
                hozAlign: "center",
                formatter: function(cell) {
                    let val = parseFloat(cell.getValue()) || 0;
                    let badgeClass = val >= 75 ? 'bg-success' : (val >= 40 ? 'bg-warning text-dark' : 'bg-danger');
                    return `<span class="badge ${badgeClass}">${val.toFixed(2)}%</span>`;
                }
            }
        ]
    });

    // Toggle Expand/Collapse
    document.getElementById("btn-expand-all").addEventListener("click", () => table.expandTree());
    document.getElementById("btn-collapse-all").addEventListener("click", () => table.collapseTree());

    // Live Search Filter
    document.getElementById("filter-text").addEventListener("keyup", function() {
        let keyword = this.value;
        if (keyword) {
            table.setFilter(function(data) {
                return JSON.stringify(data).toLowerCase().includes(keyword.toLowerCase());
            });
        } else {
            table.clearFilter();
        }
    });

    // Export Actions
    document.getElementById("download-excel").addEventListener("click", () => table.download("xlsx", "rekap_anggaran_bertingkat.xlsx"));
    document.getElementById("download-pdf").addEventListener("click", () => table.download("pdf", "rekap_anggaran_bertingkat.pdf", {
        orientation: "landscape"
    }));

    // --- Render Chart.js (Aggregated per Nama Jenis) ---
    const jenisSummary = {};
    rawData.forEach(item => {
        let j = item.nama_jenis;
        if (!jenisSummary[j]) jenisSummary[j] = {
            ang: 0,
            rea: 0
        };
        jenisSummary[j].ang += parseFloat(item.jumlah_total_anggaran) || 0;
        jenisSummary[j].rea += parseFloat(item.total_realisasi) || 0;
    });

    const labels = Object.keys(jenisSummary);
    const dataAng = labels.map(l => jenisSummary[l].ang);
    const dataRea = labels.map(l => jenisSummary[l].rea);

    new Chart(document.getElementById('jenisChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                    label: 'Total Anggaran',
                    data: dataAng,
                    backgroundColor: 'rgba(13, 110, 253, 0.7)'
                },
                {
                    label: 'Total Realisasi',
                    data: dataRea,
                    backgroundColor: 'rgba(25, 135, 84, 0.7)'
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
                        callback: (v) => 'Rp ' + v.toLocaleString('id-ID')
                    }
                }
            }
        }
    });
</script>
<?= $this->endSection() ?>