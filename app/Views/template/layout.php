<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Biro Administrasi Pembangunan | SiTAPIS v26</title>
    <!--begin::Theme Init (prevents flash of incorrect theme on load, #6043)-->

    <script>
        (() => {
            'use strict';
            const root = document.documentElement;

            // Applications with their own theming opt out of AdminLTE's color mode
            // entirely, here as well as in the bundle.
            if (root.getAttribute('data-lte-color-mode') === 'off') {
                return;
            }

            const STORAGE_KEY = 'lte-theme';
            let stored = null;
            try {
                stored = localStorage.getItem(STORAGE_KEY);
            } catch {
                // localStorage may be unavailable (private mode, sandboxed iframe).
            }
            // Mirror the precedence in color-mode.ts: the visitor's stored choice
            // wins, then a theme this page declared itself, then the OS preference.
            const authored = root.getAttribute('data-bs-theme');
            let resolved = 'light';
            if (stored === 'dark' || stored === 'light') {
                resolved = stored;
            } else if (authored === 'dark' || authored === 'light') {
                resolved = authored;
            } else if (globalThis.matchMedia('(prefers-color-scheme: dark)').matches) {
                resolved = 'dark';
            }
            root.setAttribute('data-bs-theme', resolved);
            root.style.colorScheme = resolved;
            // Flag values computed here, so the bundle does not mistake them for a
            // theme the page declared and stop following the OS preference.
            if (resolved !== authored) {
                root.setAttribute('data-lte-theme-resolved', '');
            }
        })();
    </script>
    <!--end::Theme Init-->


    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->

    <!--begin::Primary Meta Tags-->
    <meta name="title" content="Biro Administrasi Pembangunan | SiTAPIS v26" />
    <meta name="author" content="ColorlibHQ" />
    <meta
        name="description"
        content="
        SiTAPIS Adalah sistem informasi terintegrasi untuk pengelolaan data pembangunan dan aktifitas perangkat daerah
        di Provinsi Lampung, 
        yang dikelola oleh Biro Administrasi Pembangunan Setda Provinsi Lampung sejak tahun 2021." />
    <meta
        name="keywords"
        content="SiTAPIS, Biro Adbang, Biro Administrasi Pembangunan, 
        Sekretariat Daerah Provinsi Lampung, Sekretariat Daerah Provinsi, 
        Administrasi Pembangunan, Biro Provinsi Lampung, 
        Laporan Administrasi Pembangunan, Laporan Aktifitas Perangkat Daerah, 
        Laporan Realisasi Fisik Anggaran, Capaian Kinerja Perangkat Daerah, 
        Capkin, LRFK" />
    <!--end::Primary Meta Tags-->

    <!--begin::Accessibility Features-->
    <!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark" />
    <!-- <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin="" /> -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"> -->

    <!-- <link rel="preload" href="<?= base_url() ?>dist_v4/css/adminlte.css" as="style" /> -->
    <!--end::Accessibility Features-->

    <!--begin::Fonts-->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
        crossorigin="anonymous"
        media="print"
        onload="this.media = 'all'" />
    <!--end::Fonts-->

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
        crossorigin="anonymous" />
    <!--end::Third Party Plugin(OverlayScrollbars)-->

    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        crossorigin="anonymous" />
    <!--end::Third Party Plugin(Bootstrap Icons)-->

    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="<?= base_url() ?>dist_v4/css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->

    <!-- apexcharts -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
        crossorigin="anonymous" />

    <!-- jsvectormap -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
        integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4="
        crossorigin="anonymous" />
    <?= csrf_meta() ?>

    <style>
        .wizard-steps {
            counter-reset: step;
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: space-between;
            position: relative;
        }

        .wizard-steps::before {
            content: '';
            position: absolute;
            top: 1rem;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--bs-border-color);
            z-index: 0;
        }

        .wizard-steps li {
            position: relative;
            z-index: 1;
            background: var(--bs-body-bg);
            padding: 0 0.75rem;
            text-align: center;
            color: var(--bs-secondary-color);
            font-size: 0.875rem;
        }

        .wizard-steps li::before {
            counter-increment: step;
            content: counter(step);
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            margin: 0 auto 0.5rem;
            border-radius: 50%;
            background: var(--bs-body-tertiary-bg);
            border: 2px solid var(--bs-border-color);
            color: var(--bs-secondary-color);
            font-weight: 600;
        }

        .wizard-steps li.active {
            color: var(--bs-primary);
            font-weight: 600;
        }

        .wizard-steps li.active::before {
            background: var(--bs-primary);
            border-color: var(--bs-primary);
            color: #fff;
        }

        .wizard-steps li.completed::before {
            background: var(--bs-success);
            border-color: var(--bs-success);
            color: #fff;
            content: '\f633';
            font-family: 'bootstrap-icons';
        }
    </style>
</head>
<!--end::Head-->
<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <?php echo view('template/_part/nav'); ?>
        <!--begin::Sidebar-->
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

        <!--end::Sidebar-->
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
                            <nav aria-label="breadcrumb">

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
                            </nav>
                        </div>
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Container-->
            </div>
            <!--end::App Content Header-->
            <!--begin::App Content-->
            <div class="app-content">
                <!--begin::Container-->
                <?= $this->renderSection('content') ?>
                <?= $this->renderSection('div-modal') ?>
                <!--end::Container-->
            </div>
            <!--end::App Content-->
        </main>
        <!--end::App Main-->
        <!--begin::Footer-->
        <footer class="app-footer">
            <!--begin::To the end-->
            <div class="float-end d-none d-sm-inline">v26b-SiTAPIS</div>
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
    <!-- <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin="">
    </script> -->

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
        src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
        crossorigin="anonymous"></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        crossorigin="anonymous"></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
        crossorigin="anonymous"></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="<?= base_url() ?>dist_v4/js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)-->
    <!--begin::OverlayScrollbars Configure-->
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
        const Default = {
            scrollbarTheme: 'os-theme-light',
            scrollbarAutoHide: 'leave',
            scrollbarClickScroll: true,
        };
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);

            // Disable OverlayScrollbars on mobile devices to prevent touch interference
            const isMobile = window.innerWidth <= 992;

            if (
                sidebarWrapper &&
                OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined &&
                !isMobile
            ) {
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
    <!--end::OverlayScrollbars Configure-->

    <!--begin::Color Mode Toggle-->
    <!-- The light/dark/auto switcher ships in adminlte.js as the ColorMode
     module (since 4.1) — no page script needed. Only the no-flash snippet
     in <head> stays inline, because it must run before first paint. -->
    <!--end::Color Mode Toggle-->

    <!-- OPTIONAL SCRIPTS -->

    <!-- sortablejs -->
    <script
        src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"
        crossorigin="anonymous"></script>
    <!-- sortablejs -->
    <script>
        new Sortable(document.querySelector('.connectedSortable'), {
            group: 'shared',
            handle: '.card-header',
        });

        const cardHeaders = document.querySelectorAll('.connectedSortable .card-header');
        cardHeaders.forEach((cardHeader) => {
            cardHeader.style.cursor = 'move';
        });
    </script>
    <!-- apexcharts -->
    <script
        src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
        integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
        crossorigin="anonymous"></script>
    <!-- ChartJS -->
    <script>
        // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
        // IT'S ALL JUST JUNK FOR DEMO
        // ++++++++++++++++++++++++++++++++++++++++++

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
                height: 300,
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
            document.querySelector('#revenue-chart'),
            sales_chart_options,
        );
        sales_chart.render();
    </script>
    <!-- jsvectormap -->
    <script
        src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"
        integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y="
        crossorigin="anonymous"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"
        integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY="
        crossorigin="anonymous"></script>
    <!-- jsvectormap -->
    <script>
        // World map by jsVectorMap
        // new jsVectorMap({
        //     selector: '#world-map',
        //     map: 'world',
        // });

        // // Sparkline charts
        // const option_sparkline1 = {
        //     series: [{
        //         data: [1000, 1200, 920, 927, 931, 1027, 819, 930, 1021],
        //     }, ],
        //     chart: {
        //         type: 'area',
        //         height: 50,
        //         sparkline: {
        //             enabled: true,
        //         },
        //     },
        //     stroke: {
        //         curve: 'straight',
        //     },
        //     fill: {
        //         opacity: 0.3,
        //     },
        //     yaxis: {
        //         min: 0,
        //     },
        //     colors: ['#DCE6EC'],
        // };

        // const sparkline1 = new ApexCharts(document.querySelector('#sparkline-1'), option_sparkline1);
        // sparkline1.render();

        // const option_sparkline2 = {
        //     series: [{
        //         data: [515, 519, 520, 522, 652, 810, 370, 627, 319, 630, 921],
        //     }, ],
        //     chart: {
        //         type: 'area',
        //         height: 50,
        //         sparkline: {
        //             enabled: true,
        //         },
        //     },
        //     stroke: {
        //         curve: 'straight',
        //     },
        //     fill: {
        //         opacity: 0.3,
        //     },
        //     yaxis: {
        //         min: 0,
        //     },
        //     colors: ['#DCE6EC'],
        // };

        // const sparkline2 = new ApexCharts(document.querySelector('#sparkline-2'), option_sparkline2);
        // sparkline2.render();

        // const option_sparkline3 = {
        //     series: [{
        //         data: [15, 19, 20, 22, 33, 27, 31, 27, 19, 30, 21],
        //     }, ],
        //     chart: {
        //         type: 'area',
        //         height: 50,
        //         sparkline: {
        //             enabled: true,
        //         },
        //     },
        //     stroke: {
        //         curve: 'straight',
        //     },
        //     fill: {
        //         opacity: 0.3,
        //     },
        //     yaxis: {
        //         min: 0,
        //     },
        //     colors: ['#DCE6EC'],
        // };

        // const sparkline3 = new ApexCharts(document.querySelector('#sparkline-3'), option_sparkline3);
        // sparkline3.render();
    </script>
    <!--end::Script-->
</body>
<!--end::Body-->

</html>