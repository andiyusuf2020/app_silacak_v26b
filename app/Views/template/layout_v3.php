<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Biro Administrasi Pembangunan | SiTAPIS v25</title>
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="Biro Administrasi Pembangunan | SiTAPIS v25" />
    <meta
        name="description"
        content="Biro Administrasi Pembangunan Setda Provinsi Lampung" />
    <meta
        name="keywords"
        content="Biro Adbang, Adbang, SiTAPIS, Pelaporan Adbang, Program Prioritas Lampung, SiTAPIS Pelaporan Adbang, 
        Pelaporan Pengendalian, pengendalian adbang, sistem informasi pelaporan, pembangunan provinsi lampung, 
        dashboard pembangunan lampung" />
    <!--end::Primary Meta Tags-->
    <!--begin::Fonts-->

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin="" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css">

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
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
        crossorigin="anonymous" />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="<?= base_url() ?>dist/css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
        crossorigin="anonymous" />
    <link rel="icon" href="<?php echo base_url(); ?>/favicon.ico" type="image/gif">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>dist/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>dist/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>dist/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <?= csrf_meta() ?>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <!--begin::Header-->
        <?php echo view('template/_part/nav'); ?>
        <!--end::Header-->
        <!--begin::Sidebar Menu-->
        <?php
        if ($groupuser == 'forbiddenopd') {
        }
        if ($groupuser == 'user') {
        }
        // if ($groupmenu == 'tahunkosong') {
        // }

        if ($groupmenu == 'usercapkinprov') {
            echo view('template/_part/menuusercapkinopd');
        }
        if ($groupmenu == 'userlrfkprov') {
            echo view('template/_part/menulrfkprov');
        }
        if ($groupuser == 'adminprogkerja') {
            echo view('template/_part/menuadmin');
        }
        if ($groupmenu == 'adminprov') {
            echo view('template/_part/menusuperadmin');
        }
        if ($groupmenu == 'userdesakumaju') {
            echo view('template/_part/menudesakuopd');
        }
        ?>
        <!--end::Sidebar Menu-->
        <!--begin::App Main-->
        <main class="app-main">
            <!--begin::App Content Header-->
            <div class="app-content-header">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-sm-9">
                            <h3 class="mb-0"><?= $titlepage ?></h3>
                        </div>
                        <div class="col-sm-3">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><?= $groupuser ?></li>
                            </ol>
                            <ol class="breadcrumb float-sm-end">
                                <?php $tahunX = session()->get('tahun') ?>
                                <button type="button" class="btn btn-outline-primary mb-2"><?= esc($tahunX) ?></button>
                                <?php if ($groupuser == 'useropdprov') { ?>
                                    <button type="button" class="btn btn-outline-success mb-2"> <a href="<?= base_url('lrfkopd'); ?>" class="nav-link">LRFK</a> </button>
                                    <button type="button" class="btn btn-outline-info mb-2"><a href="<?= base_url('capkin'); ?>" class="nav-link">CAPKIN</a></button>
                                <?php } ?>
                                <?php if ($groupuser == 'adminprov') { ?>
                                    <button type="button" class="btn btn-outline-success mb-2"> <a href="<?= base_url('lrfkadmin'); ?>" class="nav-link">ADMIN</a> </button>
                                <?php } ?>

                            </ol>
                        </div>

                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Container-->
            </div>
            <div class="app-content">
                <?= $this->renderSection('content') ?>
                <?= $this->renderSection('div-modal') ?>
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
                Copyright &copy; <?php echo date('Y'); ?>&nbsp;
                <a href="https://adbang.lampungprov.go.id/e-tapis" class="text-decoration-none">Biro Administrasi Pembangunan Setda Provinsi Lampung</a>.
            </strong>
            <!--end::Copyright-->
        </footer>
        <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <?php $this->renderSection('script-js') ?>

    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

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
    <script src="<?= base_url() ?>dist/js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->

    <script>
        const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
        const Default = {
            scrollbarTheme: 'os-theme-light',
            scrollbarAutoHide: 'leave',
            scrollbarClickScroll: true,
        };
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>
    <!--begin::Bootstrap Tooltips-->
    <script>
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(
            (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl),
        );
    </script>
    <!--end::Bootstrap Tooltips-->
    <!--begin::Bootstrap Toasts-->
    <script>
        const toastTriggerList = document.querySelectorAll('[data-bs-toggle="toast"]');
        toastTriggerList.forEach((btn) => {
            btn.addEventListener('click', (event) => {
                event.preventDefault();
                const toastEle = document.getElementById(btn.getAttribute('data-bs-target'));
                const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastEle);
                toastBootstrap.show();
            });
        });
    </script>
    <!-- DataTables  & Plugins -->
    <script src="<?php echo base_url(); ?>dist/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>dist/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?php echo base_url(); ?>dist/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?php echo base_url(); ?>dist/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="<?php echo base_url(); ?>dist/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url(); ?>dist/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="<?php echo base_url(); ?>dist/plugins/jszip/jszip.min.js"></script>
    <script src="<?php echo base_url(); ?>dist/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="<?php echo base_url(); ?>dist/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="<?php echo base_url(); ?>dist/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="<?php echo base_url(); ?>dist/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="<?php echo base_url(); ?>dist/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
    <script
        src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
        integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
        crossorigin="anonymous"></script>
    <script>
        // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
        // IT'S ALL JUST JUNK FOR DEMO
        // ++++++++++++++++++++++++++++++++++++++++++

        const visitors_chart_options = {
            series: [{
                    name: 'High - 2023',
                    data: [100, 120, 170, 167, 180, 177, 160],
                },
                {
                    name: 'Low - 2023',
                    data: [60, 80, 70, 67, 80, 77, 100],
                },
            ],
            chart: {
                height: 200,
                type: 'line',
                toolbar: {
                    show: false,
                },
            },
            colors: ['#0d6efd', '#adb5bd'],
            stroke: {
                curve: 'smooth',
            },
            grid: {
                borderColor: '#e7e7e7',
                row: {
                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5,
                },
            },
            legend: {
                show: false,
            },
            markers: {
                size: 1,
            },
            xaxis: {
                categories: ['22th', '23th', '24th', '25th', '26th', '27th', '28th'],
            },
        };

        const visitors_chart = new ApexCharts(
            document.querySelector('#visitors-chart'),
            visitors_chart_options,
        );
        visitors_chart.render();

        const _chart_options = {
            series: [{
                    name: 'Net Profit',
                    data: [44, 55, 57, 56, 61, 58, 63, 60, 66],
                },
                {
                    name: 'Revenue',
                    data: [76, 85, 101, 98, 87, 105, 91, 114, 94],
                },
                {
                    name: 'Free Cash Flow',
                    data: [35, 41, 36, 26, 45, 48, 52, 53, 41],
                },
            ],
            chart: {
                type: 'bar',
                height: 200,
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded',
                },
            },
            legend: {
                show: false,
            },
            colors: ['#0d6efd', '#20c997', '#ffc107'],
            dataLabels: {
                enabled: false,
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent'],
            },
            xaxis: {
                categories: ['Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
            },
            fill: {
                opacity: 1,
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return '$ ' + val + ' thousands';
                    },
                },
            },
        };

        const _chart = new ApexCharts(
            document.querySelector('#chart'),
            _chart_options,
        );
        _chart.render();
    </script>
    <!--end::Script-->

</body>
<!--end::Body-->

</html>