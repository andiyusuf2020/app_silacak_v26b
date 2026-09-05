<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>SiTAPIS | Eksekutif</title>
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE | Dashboard v2" />
    <meta name="author" content="ColorlibHQ" />
    <meta
        name="description"
        content="Biro Administrasi Pembangunan Setda Provinsi Lampung" />
    <meta
        name="keywords"
        content="SITAPIS,Pelaporan,Pembangunan, Provinsi Lampung, Pemerintah Daerah Lampung" />
    <!--end::Primary Meta Tags-->
    <!--begin::Fonts-->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
        crossorigin="anonymous" />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
        integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
        crossorigin="anonymous" />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin="" />

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
        crossorigin="anonymous" />
    <link rel="stylesheet" href="<?= base_url()  ?>assets/css/style.css">

    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="<?= base_url()  ?>dist/css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
        crossorigin="anonymous" />
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <!--begin::Header-->
        <nav class="app-header navbar navbar-expand bg-body">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Start Navbar Links-->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Home</a></li>
                    <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Contact</a></li>
                </ul>
                <!--end::Start Navbar Links-->
                <!--begin::End Navbar Links-->
                <ul class="navbar-nav ms-auto">
                    <!--begin::Navbar Search-->
                    <li class="nav-item">
                        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                            <i class="bi bi-search"></i>
                        </a>
                    </li>
                    <!--end::Navbar Search-->
                    <!--begin::Fullscreen Toggle-->
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                            <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                            <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                        </a>
                    </li>
                    <!--end::Fullscreen Toggle-->
                </ul>
                <!--end::End Navbar Links-->
            </div>
            <!--end::Container-->
        </nav>
        <!--end::Header-->
        <!--begin::App Main-->
        <main class="app-main">
            <!--begin::App Content Header-->
            <div class="app-content-header">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Dashboard Eksekutif Administrasi Pembangunan Provinsi Lampung</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Eksekutif</li>
                            </ol>
                        </div>
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Container-->
            </div>
            <div class="app-content">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!-- Info boxes -->
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon text-bg-primary shadow-sm">
                                    <i class="bi bi-bank"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Jumlah Program</span>
                                    <span class="info-box-number">
                                        <?= esc($totprog) ?>
                                        <small></small>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon text-bg-danger shadow-sm">
                                    <i class="bi bi-clipboard2-data"></i> </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Jumlah Kegiatan</span>
                                    <span class="info-box-number"><?= esc($totkeg) ?></span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <!-- fix for small devices only -->
                        <!-- <div class="clearfix hidden-md-up"></div> -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon text-bg-success shadow-sm">
                                    <i class="bi bi-cart-fill"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Jumlah SubKegiatan</span>
                                    <span class="info-box-number"><?= esc($totsubkeg) ?></span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon text-bg-warning shadow-sm">
                                    <i class="bi bi-people-fill"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Jumlah Perangkat Daerah</span>
                                    <span class="info-box-number">48</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title">Lokasi Pelaksanan Kegiatan Perangkat Daerah Provinsi Lampung TA 2025</h5>
                                    <div class="card-tools">
                                        <div class="action-buttons">
                                            <form action="<?= base_url() ?>eksekutif" method="get" class="search-form">
                                                <input type="text" name="keyword" placeholder="Cari lokasi..." value="<?= $keyword ?? '' ?>">
                                                <button type="submit"><i class="bi bi-search"></i></button>
                                            </form>

                                            <div class="filter-kategori">
                                                <form action="<?= base_url() ?>eksekutif" method="get">
                                                    <select name="kategori" onchange="this.form.submit()">
                                                        <option value="">Semua Kategori</option>
                                                        <?php foreach ($kategoriList as $kategoriItem): ?>
                                                            <option value="<?= $kategoriItem['kategori'] ?>" <?= isset($kategori) && $kategori == $kategoriItem['kategori'] ? 'selected' : '' ?>>
                                                                <?= $kategoriItem['kategori'] ?: 'Tanpa Kategori' ?>
                                                            </option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <!--begin::Row-->
                                    <div class="row">
                                        <div id="map"></div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- ./card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!--end::Row-->
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title">Progres Realisasi Anggaran Belanja APBD Perangkat Daerah Provinsi Lampung TA 2025</h5>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <!--begin::Row-->
                                    <div class="row">
                                        <div class="card-body p-0">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 10px">#</th>
                                                        <th>Perangkat Daerah</th>
                                                        <th>Pagu Anggaran (Rp)</th>
                                                        <th>Realisasi Anggaran</th>
                                                        <th style="width: 40px">%</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($datarealisasi as $key => $value) { ?>
                                                        <tr class="align-middle">
                                                            <td><?= esc($key + 1) ?></td>
                                                            <td><?= esc($value['nama_subunit']) ?></td>
                                                            <td><?= esc(number_format($value['pagu'], 0, '.', ',')) ?></td>
                                                            <td><?= esc(number_format($value['realisasi'], 0, '.', ',')) ?></td>
                                                            <td>
                                                                <?php
                                                                $real = $value['realisasi'] / $value['pagu'] * 100;

                                                                echo esc(number_format($real, 2, '.', ','));
                                                                ?>
                                                            </td>

                                                        </tr>
                                                    <?php }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <!-- ./card-body -->
                                    <div class="card-footer">
                                        <!--begin::Row-->
                                        <div class="row">
                                            <div class="col-md-3 col-6">
                                                <div class="text-center border-end">
                                                    <h5 class="fw-bold mb-0"><?= esc(number_format($totapbd['pagu'], 2, '.', ',')) ?></h5>
                                                    <span class="text-uppercase">PAGU APBD</span>
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-3 col-6">
                                                <div class="text-center border-end">
                                                    <h5 class="fw-bold mb-0"><?= esc(number_format($totapbd['realisasi'], 2, '.', ',')) ?></h5>
                                                    <span class="text-uppercase">REALISASI APBD</span>
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-3 col-6">
                                                <div class="text-center border-end">
                                                    <span class="text-success">
                                                        <i class="bi bi-caret-up-fill"></i>
                                                        <?php
                                                        $realapb = $totapbd['realisasi'] / $totapbd['pagu'] * 100;
                                                        echo esc(number_format($realapb, 2, '.', ','));
                                                        ?>%
                                                    </span>
                                                    <span class="text-uppercase">CAPAIAN REALISASI</span>
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-3 col-6">
                                                <div class="text-center">
                                                    <span class="text-danger">
                                                        <i class="bi bi-caret-down-fill"></i> 18%
                                                    </span>
                                                    <h5 class="fw-bold mb-0">1200</h5>
                                                    <span class="text-uppercase">GOAL COMPLETIONS</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Row-->
                                    </div>
                                    <!-- /.card-footer -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Container-->

                </div>
                <!--end::Container-->
            </div>
            <!--end::App Content-->
        </main>
        <!--end::App Main-->
        <!--begin::Footer-->
        <footer class="app-footer">
            <!--begin::To the end-->
            <div class="float-end d-none d-sm-inline">SiTAPIS</div>
            <!--end::To the end-->
            <!--begin::Copyright-->
            <strong>
                Biro Administrsi Pembangunan Setda Provinsi Lampung
            </strong>
            All rights reserved.
            <!--end::Copyright-->
        </footer>
        <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
    <!-- <script src="/assets/js/map.js"></script> -->
    <script>
        // Data lokasi dari controller
        const lokasiData = <?= json_encode($lokasi) ?>;
        // Inisialisasi peta
        // const map = L.map('map').setView([-2.5489, 118.0149], 5); // Posisi awal Indonesia
        const map = L.map('map').setView([103.56, 106.13], 5); // Posisi awal Indonesia

        // Tambahkan layer OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'SiTAPIS : Sistem Data Pengendalian dan Informasi'
            //  '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Tambahkan marker untuk setiap lokasi
        lokasiData.forEach(lokasi => {
            L.marker([lokasi.latitude, lokasi.longitude])
                .addTo(map)
                .bindPopup(`
            <b>${lokasi.nama}</b><br>
            ${lokasi.deskripsi || 'Tidak ada deskripsi'}<br>
            <small>Lat: ${lokasi.latitude}, Lng: ${lokasi.longitude}</small><br>
            ${lokasi.gambar}
        `);
        });

        // Jika ada lebih dari satu lokasi, sesuaikan view peta
        if (lokasiData.length > 0) {
            const group = new L.featureGroup(lokasiData.map(lokasi =>
                L.marker([lokasi.latitude, lokasi.longitude])
            ));
            map.fitBounds(group.getBounds().pad(0.5));
        }
        // Fungsi untuk menampilkan marker dengan gambar
        function createMarkers(lokasiData) {
            const iconDefault = L.icon({
                iconUrl: '<?= base_url()  ?>assets/img/marker-icon.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34]
            });

            lokasiData.forEach(lokasi => {
                // Buat konten popup
                let popupContent = `<b>${lokasi.nama}</b>`;

                if (lokasi.gambar_popup) {
                    popupContent += `<div class="popup-image-container">
                <img src="<?= base_url()  ?>uploads/lokasi/popup/${lokasi.gambar_popup}" 
                     class="popup-image" 
                     alt="${lokasi.nama}">
            </div>`;
                } else if (lokasi.gambar) {
                    popupContent += `<div class="popup-image-container">
                <img src="<?= base_url()  ?>uploads/lokasi/${lokasi.gambar}" 
                     class="popup-image" width="250" height="200"
                     alt="${lokasi.nama}">
            </div>`;
                }

                popupContent += `<p>${lokasi.deskripsi || ''}</p>
                        <small>Koordinat: ${lokasi.latitude}, ${lokasi.longitude}</small>`;

                // Buat marker dengan custom icon jika ada gambar
                const customIcon = lokasi.gambar ? L.icon({
                    iconUrl: `<?= base_url()  ?>uploads/lokasi/${lokasi.gambar}`,
                    iconSize: [40, 40],
                    iconAnchor: [20, 40],
                    popupAnchor: [0, -40],
                    className: 'custom-marker-icon'
                }) : iconDefault;

                const marker = L.marker([lokasi.latitude, lokasi.longitude], {
                    icon: customIcon
                }).addTo(map);

                marker.bindPopup(popupContent, {
                    maxWidth: 300,
                    minWidth: 200,
                    className: 'custom-popup'
                });

                // Tambahkan event click untuk zoom
                document.querySelector(`.lokasi-item[data-id="${lokasi.id}"]`)
                    ?.addEventListener('click', () => {
                        map.setView([lokasi.latitude, lokasi.longitude], 15);
                        marker.openPopup();
                    });
            });
        }

        // Panggil fungsi saat data ready
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lokasiData !== 'undefined') {
                createMarkers(lokasiData);
            }
        });
    </script>
    <script
        src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
        integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
        crossorigin="anonymous"></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src=".<?= base_url()  ?>/dist/js/adminlte.js"></script>
    <!-- apexcharts -->
    <script
        src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
        integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
        crossorigin="anonymous"></script>
    <script>
        // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
        // IT'S ALL JUST JUNK FOR DEMO
        // ++++++++++++++++++++++++++++++++++++++++++

        /* apexcharts
         * -------
         * Here we will create a few charts using apexcharts
         */

        //-----------------------
        // - MONTHLY SALES CHART -
        //-----------------------

        const sales_chart_options = {
            series: [{
                    name: 'Digital Goods',
                    data: [28, 48, 40, 19, 86, 27, 90],
                },
                {
                    name: 'Electronics',
                    data: [65, 59, 80, 81, 56, 55, 40],
                },
            ],
            chart: {
                height: 180,
                type: 'area',
                toolbar: {
                    show: false,
                },
            },
            legend: {
                show: false,
            },
            colors: ['#0d6efd', '#20c997'],
            dataLabels: {
                enabled: false,
            },
            stroke: {
                curve: 'smooth',
            },
            xaxis: {
                type: 'datetime',
                categories: [
                    '2023-01-01',
                    '2023-02-01',
                    '2023-03-01',
                    '2023-04-01',
                    '2023-05-01',
                    '2023-06-01',
                    '2023-07-01',
                ],
            },
            tooltip: {
                x: {
                    format: 'MMMM yyyy',
                },
            },
        };

        const sales_chart = new ApexCharts(
            document.querySelector('#sales-chart'),
            sales_chart_options,
        );
        sales_chart.render();

        //---------------------------
        // - END MONTHLY SALES CHART -
        //---------------------------

        function createSparklineChart(selector, data) {
            const options = {
                series: [{
                    data
                }],
                chart: {
                    type: 'line',
                    width: 150,
                    height: 30,
                    sparkline: {
                        enabled: true,
                    },
                },
                colors: ['var(--bs-primary)'],
                stroke: {
                    width: 2,
                },
                tooltip: {
                    fixed: {
                        enabled: false,
                    },
                    x: {
                        show: false,
                    },
                    y: {
                        title: {
                            formatter: function(seriesName) {
                                return '';
                            },
                        },
                    },
                    marker: {
                        show: false,
                    },
                },
            };

            const chart = new ApexCharts(document.querySelector(selector), options);
            chart.render();
        }

        const table_sparkline_1_data = [25, 66, 41, 89, 63, 25, 44, 12, 36, 9, 54];
        const table_sparkline_2_data = [12, 56, 21, 39, 73, 45, 64, 52, 36, 59, 44];
        const table_sparkline_3_data = [15, 46, 21, 59, 33, 15, 34, 42, 56, 19, 64];
        const table_sparkline_4_data = [30, 56, 31, 69, 43, 35, 24, 32, 46, 29, 64];
        const table_sparkline_5_data = [20, 76, 51, 79, 53, 35, 54, 22, 36, 49, 64];
        const table_sparkline_6_data = [5, 36, 11, 69, 23, 15, 14, 42, 26, 19, 44];
        const table_sparkline_7_data = [12, 56, 21, 39, 73, 45, 64, 52, 36, 59, 74];

        createSparklineChart('#table-sparkline-1', table_sparkline_1_data);
        createSparklineChart('#table-sparkline-2', table_sparkline_2_data);
        createSparklineChart('#table-sparkline-3', table_sparkline_3_data);
        createSparklineChart('#table-sparkline-4', table_sparkline_4_data);
        createSparklineChart('#table-sparkline-5', table_sparkline_5_data);
        createSparklineChart('#table-sparkline-6', table_sparkline_6_data);
        createSparklineChart('#table-sparkline-7', table_sparkline_7_data);

        //-------------
        // - PIE CHART -
        //-------------

        const pie_chart_options = {
            series: [700, 500, 400, 600, 300, 100],
            chart: {
                type: 'donut',
            },
            labels: ['Chrome', 'Edge', 'FireFox', 'Safari', 'Opera', 'IE'],
            dataLabels: {
                enabled: false,
            },
            colors: ['#0d6efd', '#20c997', '#ffc107', '#d63384', '#6f42c1', '#adb5bd'],
        };

        const pie_chart = new ApexCharts(document.querySelector('#pie-chart'), pie_chart_options);
        pie_chart.render();

        //-----------------
        // - END PIE CHART -
        //-----------------
    </script>
    <!--end::Script-->
</body>
<!--end::Body-->

</html>