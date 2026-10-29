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

            <div class="card-header bg-transparent d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
                <h3 class="card-title fw-bold text-dark m-0"><i class="bi bi-table me-2"></i>Daftar SKPD & Anggaran Kas</h3>
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
        // Memuat data JSON dari Controller CodeIgniter secara aman
        const tableData = <?= $datajson; ?>;

        // Inisialisasi Tabulator
        const groupUser = "<?= $groupuser ?>"; // Jika pakai Blade Laravel
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
                    textwrap: true,
                    resizable: false,
                    headerSort: false
                },
                {
                    title: 'KODE SKPD',
                    field: 'KODE_SKPD',
                    width: 200,
                    headerSort: true,
                    // headerFilter: 'input'
                },
                {
                    title: 'NAMA UNIT SKPD',
                    field: 'NAMA_UNIT_SKPD',
                    textwrap: true,
                    minWidth: 350,
                    // headerFilter: 'input',
                    formatter: 'plaintext'
                },
                {
                    title: 'PAGU ANGGARAN',
                    field: 'total_anggaran',
                    hozAlign: 'right',
                    // headerFilter: 'input',
                    minWidth: 50,
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
                    title: 'TOTAL ANGKAS',
                    field: 'total_realisasi',
                    hozAlign: 'right',
                    // headerFilter: 'input',
                    minWidth: 50,
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
                    title: 'Aksi',
                    field: 'KODE_SKPD',
                    hozAlign: 'center',
                    headerSort: false,
                    width: 100,
                    formatter: function(cell) {
                        const rowData = cell.getRow().getData();
                        const token = rowData.angkas_token || '';
                        // if (groupUser === 'superadmin' ||
                        //     groupUser === 'Adminadbang') {
                        //     return `<a href="${groupUser}/dataperopd?token=${token}" class="btn btn-sm btn-primary">Detail</a>`;
                        // } else {
                        //     return `<span class="text-muted">Tidak ada aksi</span>`;
                        // }
                        return `<a href="${groupUser}/dataperopd?token=${token}" class="btn btn-sm btn-primary">Detail</a>`;

                        //         return `
                        //     <a href="superadmin/dataperopd?token=${rowData.angkas_token}" class="btn btn-sm btn-primary">detail</a>
                        // `;
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