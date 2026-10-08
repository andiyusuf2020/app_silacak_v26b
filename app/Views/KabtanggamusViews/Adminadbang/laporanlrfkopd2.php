<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-dark">Rekapitulasi Per SKPD</h1>
                <p class="text-muted mb-0 small">Monitoring Realisasi Anggaran & Capaian SRO (Urutan Capaian SRO Terendah)</p>
            </div>
            <div class="col-sm-6">
                <form id="formFilter" class="d-flex justify-content-sm-end gap-2 mt-2 mt-sm-0">
                    <div class="input-group input-group-sm style-filter">
                        <span class="input-group-text bg-white"><i class="bi bi-calendar3"></i></span>
                        <select name="bulan" id="selectBulan" class="form-select">
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                <?php $b = str_pad($i, 2, '0', STR_PAD_LEFT); ?>
                                <option value="<?= $b ?>" <?= $b == $bulan ? 'selected' : '' ?>>
                                    Bulan <?= $b ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <button type="button" id="btnExportXlsx" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1">
                        <i class="bi bi-file-earmark-excel-fill"></i> XLSX
                    </button>
                    <button type="button" id="btnExportPdf" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1">
                        <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <!-- Ringkasan Statistik & Grafik -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-lg-4">
                <div class="card card-outline card-primary h-100 shadow-sm">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bold"><i class="bi bi-pie-chart-fill me-1"></i> Ringkasan Total</h3>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="mb-3">
                            <span class="text-muted d-block small">Total Anggaran</span>
                            <h4 class="font-weight-bold text-primary mb-0" id="statAnggaran">Rp 0</h4>
                        </div>
                        <div class="mb-3">
                            <span class="text-muted d-block small">Total Realisasi</span>
                            <h4 class="font-weight-bold text-success mb-0" id="statRealisasi">Rp 0</h4>
                        </div>
                        <div>
                            <span class="text-muted d-block small">Rata-rata Capaian SRO</span>
                            <h4 class="font-weight-bold text-warning mb-0" id="statRataSro">0%</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <div class="card card-outline card-info h-100 shadow-sm">
                    <div class="card-header border-0 d-flex justify-content-between align-items-center">
                        <h3 class="card-title font-weight-bold"><i class="bi bi-bar-chart-line-fill me-1"></i> Top 5 Capaian SRO Terendah</h3>
                    </div>
                    <div class="card-body">
                        <div style="height: 200px;">
                            <canvas id="chartSroTerendah"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Interactive Tabulator -->
        <div class="card card-outline card-secondary shadow-sm">
            <div class="card-header border-0 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h3 class="card-title font-weight-bold mb-0">Rincian Data SKPD</h3>
                <div class="input-group input-group-sm" style="max-width: 280px;">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" id="globalSearch" class="form-control" placeholder="Cari Kode atau Nama SKPD...">
                </div>
            </div>
            <div class="card-body p-0">
                <div id="tableRekapSKPD" class="border-0"></div>
            </div>
        </div>

    </div>
</div>
<!-- CDN CSS AdminLTE v4/Bootstrap 5, Tabulator, & Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://unpkg.com/tabulator-tables@5.5.2/dist/css/tabulator_bootstrap5.min.css">
<style>
    .style-filter {
        max-width: 160px;
    }

    .tabulator {
        border: none !important;
        font-size: 0.875rem;
    }

    .tabulator-header {
        border-bottom: 2px solid #dee2e6 !important;
        background-color: #f8f9fa !important;
    }

    .tabulator-row .tabulator-cell {
        vertical-align: middle !important;
        padding: 8px 12px !important;
    }
</style>
<!-- CDN JS Tabulator & Chart.js -->
<script src="https://unpkg.com/tabulator-tables@5.5.2/dist/js/tabulator.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let rawData = <?= json_encode($dataRekap) ?>;
        let chartInstance = null;

        // 1. Inisialisasi Tabulator Table
        const table = new Tabulator("#tableRekapSKPD", {
            data: rawData,
            layout: "fitColumns",
            responsiveLayout: "collapse",
            pagination: "local",
            paginationSize: 10,
            paginationSizeSelector: [10, 25, 50, 100],
            placeholder: "<div class='text-center p-4 text-muted'>Tidak ada data ditemukan</div>",
            columns: [{
                    title: "NO",
                    formatter: "rownum",
                    width: 65,
                    hozAlign: "center",
                    headerHozAlign: "center",
                    headerSort: false
                },
                {
                    title: "KODE SKPD",
                    field: "kode",
                    width: 140,
                    hozAlign: "center",
                    headerHozAlign: "center"
                },
                {
                    title: "NAMA SKPD",
                    field: "nama",
                    minWidth: 220,
                    headerHozAlign: "left"
                },
                {
                    title: "ANGGARAN",
                    field: "anggaran",
                    hozAlign: "right",
                    headerHozAlign: "right",
                    formatter: cell => "Rp " + new Intl.NumberFormat("id-ID").format(cell.getValue())
                },
                {
                    title: "REALISASI",
                    field: "realisasi",
                    hozAlign: "right",
                    headerHozAlign: "right",
                    formatter: cell => "Rp " + new Intl.NumberFormat("id-ID").format(cell.getValue())
                },
                {
                    title: "TOTAL SRO",
                    field: "sro",
                    hozAlign: "center",
                    headerHozAlign: "center",
                    width: 110
                },
                {
                    title: "SRO NOL",
                    field: "sro_nol",
                    hozAlign: "center",
                    headerHozAlign: "center",
                    width: 100
                },
                {
                    title: "CAPAIAN REALISASI",
                    field: "capaian_realisasi",
                    hozAlign: "center",
                    headerHozAlign: "center",
                    formatter: cell => cell.getValue().toFixed(2) + "%"
                },
                {
                    title: "CAPAIAN SRO",
                    field: "capaian_sro",
                    hozAlign: "center",
                    headerHozAlign: "center",
                    formatter: function(cell) {
                        let val = cell.getValue().toFixed(2);
                        return `<span class="badge bg-warning text-dark font-monospace">${val}%</span>`;
                    }
                },
                {
                    title: "STATUS",
                    field: "status_efisiensi",
                    hozAlign: "center",
                    headerHozAlign: "center",
                    width: 120,
                    formatter: function(cell) {
                        let isInefisien = cell.getValue() === "Inefisien";
                        let badgeClass = isInefisien ? "bg-danger" : "bg-success";
                        return `<span class="badge ${badgeClass}">${cell.getValue()}</span>`;
                    }
                }
            ]
        });

        // 2. Live Search Filtering
        document.getElementById("globalSearch").addEventListener("keyup", function(e) {
            let value = e.target.value;
            table.setFilter([
                [{
                        field: "kode",
                        type: "like",
                        value: value
                    },
                    {
                        field: "nama",
                        type: "like",
                        value: value
                    }
                ]
            ]);
        });

        // 3. Kalkulasi & Render Ringkasan Card + Chart
        function updateDashboardMetrics(data) {
            let totalAnggaran = 0;
            let totalRealisasi = 0;
            let sumSro = 0;

            data.forEach(item => {
                totalAnggaran += parseFloat(item.anggaran) || 0;
                totalRealisasi += parseFloat(item.realisasi) || 0;
                sumSro += parseFloat(item.capaian_sro) || 0;
            });

            let avgSro = data.length > 0 ? (sumSro / data.length).toFixed(2) : 0;

            document.getElementById("statAnggaran").textContent = "Rp " + new Intl.NumberFormat("id-ID").format(totalAnggaran);
            document.getElementById("statRealisasi").textContent = "Rp " + new Intl.NumberFormat("id-ID").format(totalRealisasi);
            document.getElementById("statRataSro").textContent = avgSro + "%";

            // Update Chart top 5 SRO terendah
            let sortedData = [...data].sort((a, b) => a.capaian_sro - b.capaian_sro).slice(0, 5);
            let labels = sortedData.map(item => item.nama.length > 20 ? item.nama.substring(0, 20) + "..." : item.nama);
            let chartValues = sortedData.map(item => item.capaian_sro);

            if (chartInstance) {
                chartInstance.destroy();
            }

            const ctx = document.getElementById("chartSroTerendah").getContext("2d");
            chartInstance = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Capaian SRO (%)",
                        data: chartValues,
                        backgroundColor: "rgba(255, 193, 7, 0.8)",
                        borderColor: "rgba(255, 193, 7, 1)",
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });
        }

        // Render statistik awal
        updateDashboardMetrics(rawData);

        // 4. AJAX Update saat Mengubah Filter Bulan
        document.getElementById("selectBulan").addEventListener("change", function() {
            let bulan = this.value;
            fetch(`<?= base_url('rekap/getDataJson') ?>?bulan=${bulan}`)
                .then(response => response.json())
                .then(resData => {
                    table.setData(resData);
                    updateDashboardMetrics(resData);
                })
                .catch(err => console.error("Gagal memperbarui data:", err));
        });

        // 5. Ekspor XLSX & PDF
        document.getElementById("btnExportXlsx").addEventListener("click", function() {
            let bulan = document.getElementById("selectBulan").value;
            window.location.href = `<?= base_url('rekap/exportXlsx') ?>?bulan=${bulan}`;
        });

        document.getElementById("btnExportPdf").addEventListener("click", function() {
            let bulan = document.getElementById("selectBulan").value;
            window.open(`<?= base_url('rekap/exportPdf') ?>?bulan=${bulan}`, "_blank");
        });
    });
</script>
<?= $this->endSection() ?>