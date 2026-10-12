<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Rekapitulasi Realisasi Anggaran</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Rekap Realisasi</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <!-- Card Filter -->
        <div class="card card-primary card-outline mb-4">
            <div class="card-header">
                <h3 class="card-title"><i class="bi bi-filter"></i> Filter Data Laporan</h3>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label for="filterBulan" class="form-label">Bulan</label>
                        <select id="filterBulan" class="form-select">
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                <option value="<?= sprintf('%02d', $i); ?>" <?= ($i == date('n')) ? 'selected' : ''; ?>>
                                    <?= date('F', mktime(0, 0, 0, $i, 10)); ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label for="filterAkun" class="form-label">Level Pengelompokan</label>
                        <select id="filterAkun" class="form-select">
                            <option value="nama_kelompok">Kelompok</option>
                            <option value="nama_jenis" selected>Jenis</option>
                            <option value="nama_rincian_objek">Rincian Objek</option>
                            <option value="nama_akun">Akun</option>
                            <option value="NAMA_BIDANG_URUSAN">Bidang Urusan</option>
                            <option value="NAMA_URUSAN">Urusan</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button id="btnTampilkan" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row Grafik -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title"><i class="bi bi-bar-chart-fill"></i> Grafik Anggaran vs Realisasi</h3>
                    </div>
                    <div class="card-body">
                        <div style="position: relative; height:350px; width:100%">
                            <canvas id="realisasiChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row Tabel Tabulator -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title"><i class="bi bi-table"></i> Detail Data Rekapitulasi</h3>
                        <div class="card-tools">
                            <button id="download-xlsx" class="btn btn-success btn-sm"><i class="bi bi-file-earmark-excel"></i> Export Excel</button>
                            <button id="download-pdf" class="btn btn-danger btn-sm"><i class="bi bi-file-earmark-pdf"></i> Export PDF</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="rekapTable"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Load Chart.js & Tabulator Assets (Pastikan CDN/Local sesuai project Anda) -->
<link href="https://unpkg.com/tabulator-tables@6.2.5/dist/css/tabulator_bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/tabulator-tables@6.2.5/dist/js/tabulator.min.js"></script>
<!-- SheetJS & jspdf untuk export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.pluginAutotable.min.js"></script>

<script>
    let rekapTable;
    let realisasiChart;

    document.addEventListener("DOMContentLoaded", function() {
        initChart();
        initTable();
        loadData();

        document.getElementById('btnTampilkan').addEventListener('click', function() {
            loadData();
        });
    });

    function initChart() {
        const ctx = document.getElementById('realisasiChart').getContext('2d');
        realisasiChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                        label: 'Total Anggaran',
                        backgroundColor: 'rgba(13, 110, 253, 0.7)',
                        borderColor: 'rgb(13, 110, 253)',
                        borderWidth: 1,
                        data: []
                    },
                    {
                        label: 'Total Realisasi',
                        backgroundColor: 'rgba(25, 135, 84, 0.7)',
                        borderColor: 'rgb(25, 135, 84)',
                        borderWidth: 1,
                        data: []
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
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR'
                                    }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

    function initTable() {
        rekapTable = new Tabulator("#rekapTable", {
            layout: "fitColumns",
            responsiveLayout: "collapse",
            placeholder: "Tidak ada data tersedia",
            pagination: "local",
            paginationSize: 10,
            paginationSizeSelector: [10, 25, 50, 100],
            columns: [{
                    formatter: "responsiveCollapse",
                    width: 30,
                    minWidth: 30,
                    hozAlign: "center",
                    resizable: false,
                    headerSort: false
                },
                {
                    title: "Uraian / Akun",
                    field: "dataview_field",
                    widthGrow: 2,
                    headerFilter: "input"
                },
                {
                    title: "Total Anggaran",
                    field: "jumlah_total_anggaran",
                    hozAlign: "right",
                    formatter: "money",
                    formatterParams: {
                        thousand: ".",
                        decimal: ",",
                        symbol: "Rp ",
                        precision: 0
                    },
                    headerFilter: "input"
                },
                {
                    title: "Total Realisasi",
                    field: "total_realisasi",
                    hozAlign: "right",
                    formatter: "money",
                    formatterParams: {
                        thousand: ".",
                        decimal: ",",
                        symbol: "Rp ",
                        precision: 0
                    }
                },
                {
                    title: "Capaian (%)",
                    field: "capaian_realisasi",
                    hozAlign: "center",
                    formatter: function(cell) {
                        let val = parseFloat(cell.getValue()) || 0;
                        let badgeClass = val >= 75 ? 'bg-success' : (val >= 50 ? 'bg-warning text-dark' : 'bg-danger');
                        return `<span class="badge ${badgeClass}">${val.toFixed(2)}%</span>`;
                    }
                },
            ],
        });

        // Trigger download buttons
        document.getElementById("download-xlsx").addEventListener("click", function() {
            rekapTable.download("xlsx", "rekap_realisasi.xlsx", {
                sheetName: "Realisasi"
            });
        });

        document.getElementById("download-pdf").addEventListener("click", function() {
            rekapTable.download("pdf", "rekap_realisasi.pdf", {
                orientation: "landscape",
                title: "Laporan Rekapitulasi Realisasi Anggaran"
            });
        });
    }

    function loadData() {
        let bulan = document.getElementById('filterBulan').value;
        let akun = document.getElementById('filterAkun').value;

        // Tampilkan loading state jika perlu
        fetch(`<?= base_url('rekap/get-data-json') ?>?bulan=${bulan}&akun=${akun}`)
            .then(response => response.json())
            .then(res => {
                // Mapping data agar key dinamis sesuai $dataview konsisten untuk Tabulator
                let formattedData = res.map(item => {
                    return {
                        dataview_field: item[akun] || '-',
                        jumlah_total_anggaran: parseFloat(item.jumlah_total_anggaran) || 0,
                        total_realisasi: parseFloat(item.total_realisasi) || 0,
                        capaian_realisasi: parseFloat(item.capaian_realisasi) || 0
                    };
                });

                // Update Tabulator Data
                rekapTable.setData(formattedData);

                // Update Chart Data
                let labels = formattedData.map(item => item.dataview_field);
                let dataAnggaran = formattedData.map(item => item.jumlah_total_anggaran);
                let dataRealisasi = formattedData.map(item => item.total_realisasi);

                realisasiChart.data.labels = labels;
                realisasiChart.data.datasets[0].data = dataAnggaran;
                realisasiChart.data.datasets[1].data = dataRealisasi;
                realisasiChart.update();
            })
            .catch(err => console.error('Gagal mengambil data:', err));
    }
</script>
<?= $this->endSection() ?>