<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>

<!--begin::Tabulator CSS (Bootstrap 5 theme)-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css" crossorigin="anonymous" />
<!--end::Tabulator CSS-->

<!-- SheetJS for Excel Export support in Tabulator -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<!-- Chart.js untuk Grafik Ringkasan -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<div class="app-content">
    <div class="container-fluid">
        <div class="card shadow-sm border-0 mb-4">
            <!-- SECTION 1: GRAFIK RINGKASAN CHART.JS -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-transparent py-3">
                            <h3 class="card-title fw-bold text-dark m-0">
                                <i class="bi bi-bar-chart-fill me-2 text-primary"></i>Top 10 Pagu vs Realisasi Anggaran SKPD
                            </h3>
                        </div>
                        <div class="card-body">
                            <div style="height: 320px; position: relative;">
                                <canvas id="skpdSummaryChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
                <h3 class="card-title fw-bold text-dark m-0"><i class="bi bi-table me-2"></i>Daftar SKPD & Realisasi Anggaran</h3>
                <div class="card-tools m-0">
                    <div class="input-group input-group-sm" style="width: 20rem;">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search text-muted" aria-hidden="true"></i>
                        </span>
                        <input
                            id="table-filter"
                            type="search"
                            class="form-control border-start-0 ps-0"
                            placeholder="Cari Nama SKPD atau Kode..."
                            aria-label="Filter rows" />
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Action Toolbar / Export Buttons -->
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div class="d-flex gap-2 flex-wrap">
                        <button id="export-xlsx" type="button" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-file-earmark-excel me-1" aria-hidden="true"></i> Export Excel
                        </button>
                        <button id="export-csv" type="button" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-filetype-csv me-1" aria-hidden="true"></i> Export CSV
                        </button>
                        <button id="export-json" type="button" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-filetype-json me-1" aria-hidden="true"></i> Export JSON
                        </button>
                    </div>
                    <div>
                        <button id="print-table" type="button" class="btn btn-sm btn-outline-dark">
                            <i class="bi bi-printer me-1" aria-hidden="true"></i> Print Tabel
                        </button>
                    </div>
                </div>

                <!-- Tabulator Container -->
                <div id="listopdadmin-table" class="rounded border"></div>
            </div>

        </div>
    </div>
</div>

<!--begin::Required Script Plugins-->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>

<!-- Tabulator JS Library -->
<script src="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/js/tabulator.min.js" crossorigin="anonymous"></script>

<!-- Page Specific Script -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // 1. Inisialisasi Chart.js Ringkasan Anggaran
        fetch('<?= base_url('' . $wilayah . '/' . $groupuser . '/getChartDataOpdRangking') ?>')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => item.NAMA_UNIT_SKPD);
                const CapaianAnggaran = data.map(item => item.persen_capaian_anggaran);
                const CapaianSRO = data.map(item => item.persen_capaian_sro);

                const ctx = document.getElementById('skpdSummaryChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                                label: 'Capaian Anggaran (%)',
                                data: CapaianAnggaran,
                                backgroundColor: 'rgba(13, 110, 253, 0.7)',
                                borderColor: 'rgba(13, 110, 253, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Capaian Sub Rincian Objek (%)',
                                data: CapaianSRO,
                                backgroundColor: 'rgba(25, 135, 84, 0.7)',
                                borderColor: 'rgba(25, 135, 84, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                ticks: {
                                    callback: function(value) {
                                        return value + '%';
                                    }
                                }
                            }
                        }
                    }
                });
            });

        // Memuat data JSON dari Controller CodeIgniter secara aman
        const tableData = <?= $datajson; ?>;

        // Inisialisasi Tabulator
        const table = new Tabulator('#listopdadmin-table', {
            data: tableData,
            layout: 'fitColumns',
            responsiveLayout: 'collapse',
            pagination: true,
            paginationSize: 10,
            paginationSizeSelector: [10, 25, 50, 100],
            movableColumns: true,
            placeholder: 'Tidak ada data SKPD tersedia',
            columns: [{
                    formatter: 'responsiveCollapse',
                    width: 30,
                    minWidth: 30,
                    hozAlign: 'center',
                    resizable: false,
                    headerSort: false
                },
                {
                    title: 'KODE SKPD',
                    field: 'KODE_SKPD',
                    width: 110,
                    headerSort: true,
                    headerFilter: 'input'
                },
                {
                    title: 'NAMA UNIT SKPD',
                    field: 'NAMA_UNIT_SKPD',
                    minWidth: 250,
                    headerFilter: 'input',
                    formatter: 'plaintext'
                },
                {
                    title: 'PAGU ANGGARAN',
                    field: 'total_anggaran',
                    hozAlign: 'right',
                    headerFilter: 'input',
                    minWidth: 150,
                    formatter: function(cell) {
                        var val = cell.getValue();
                        if (!val) return 'Rp 0';
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            maximumFractionDigits: 0
                        }).format(val);
                    }
                },
                {
                    title: 'REALISASI ANGGARAN',
                    field: 'total_realisasi',
                    hozAlign: 'right',
                    headerFilter: 'input',
                    minWidth: 150,
                    formatter: function(cell) {
                        var val = cell.getValue();
                        if (!val) return 'Rp 0';
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            maximumFractionDigits: 0
                        }).format(val);
                    }
                },
                {
                    title: '% Realisasi',
                    field: 'total_realisasi',
                    hozAlign: 'center',
                    minWidth: 120,
                    formatter: function(cell) {
                        const data = cell.getRow().getData();
                        const anggaran = parseFloat(data.total_anggaran) || 0;
                        const realisasi = parseFloat(data.total_realisasi) || 0;
                        const pct = anggaran > 0 ? Math.round((realisasi / anggaran) * 100) : 0;

                        let colorClass = 'bg-danger';
                        if (pct >= 75) colorClass = 'bg-success';
                        else if (pct >= 40) colorClass = 'bg-warning text-dark';

                        return `<div class="progress" style="height: 18px; width: 100%;">
                                        <div class="progress-bar ${colorClass}" role="progressbar" style="width: ${Math.min(pct, 100)}%; font-size: 0.75rem;" aria-valuenow="${pct}" aria-valuemin="0" aria-valuemax="100">${pct}%</div>
                                    </div>`;
                    },
                    headerSort: false
                },
                {
                    title: 'Aktivitas Belanja',
                    field: 'jumlah_item_sro_terealisasi',
                    hozAlign: 'center',
                    minWidth: 130,
                    formatter: function(cell) {
                        const row = cell.getRow().getData();
                        const total = row.jumlah_item_sro || 0;
                        const done = row.jumlah_item_sro_terealisasi || 0;
                        return `<span class="badge text-bg-light border text-dark">${done} / ${total}</span>`;
                    }
                },
                {
                    title: 'Aksi',
                    field: 'KODE_SKPD',
                    hozAlign: 'center',
                    headerSort: false,
                    width: 100,
                    formatter: function(cell) {
                        const rowData = cell.getRow().getData();
                        const token = rowData.update_token || '';
                        return `
                    <a href="superadmin/dataperopd?token=${rowData.update_token}" class="btn btn-sm btn-primary">detail</a>
                `;
                    }
                }
            ]
        });

        // Live Global Search Filter
        document.getElementById('table-filter').addEventListener('input', (e) => {
            const keyword = e.target.value;
            if (keyword.trim() !== '') {
                table.setFilter([
                    [{
                            field: 'NAMA_UNIT_SKPD',
                            type: 'like',
                            value: keyword
                        },
                        {
                            field: 'KODE_SKPD',
                            type: 'like',
                            value: keyword
                        }
                    ]
                ]);
            } else {
                table.clearFilter();
            }
        });

        // Tombol Ekspor & Print Events
        document.getElementById('export-xlsx').addEventListener('click', () => table.download('xlsx', 'Data_SKPD_SiTAPIS.xlsx', {
            sheetName: "Realisasi SKPD"
        }));
        document.getElementById('export-csv').addEventListener('click', () => table.download('csv', 'Data_SKPD_SiTAPIS.csv'));
        document.getElementById('export-json').addEventListener('click', () => table.download('json', 'Data_SKPD_SiTAPIS.json'));
        document.getElementById('print-table').addEventListener('click', () => table.print(false, true));
    });
</script>
<?= $this->endSection() ?>