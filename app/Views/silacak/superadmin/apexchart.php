<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AdminLTE 4 | ApexCharts</title>

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
    <meta name="title" content="AdminLTE 4 | ApexCharts" />
    <meta name="author" content="ColorlibHQ" />
    <meta
        name="description"
        content="AdminLTE is a free Bootstrap 5 admin dashboard template with almost 50 example pages, built with vanilla JS and designed with accessibility in mind." />
    <meta
        name="keywords"
        content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel" />
    <!--end::Primary Meta Tags-->

    <!--begin::Accessibility Features-->
    <!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="<?= base_url() ?>dist_v4/css/adminlte.css" as="style" />
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
</head>
<!--end::Head-->
<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <!--begin::Header-->
        <?php echo view('template/_part/nav'); ?>

        <!--end::Header-->
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
        if ($groupmenu == 'superadmin') {
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
                <div class="container-fluid">
                    <!--begin::Row-->
                    <div class="row g-4">
                        <!--begin::Col-->
                        <div class="col-12 col-lg-6">
                            <!--begin::Card-->
                            <div class="card h-100">
                                <div class="card-header">
                                    <h3 class="card-title">Tren Realisasi Belanja APBD Kabupaten Tulang Bawang Barat</h3>

                                    <div class="card-tools">
                                        <button
                                            type="button"
                                            class="btn btn-tool"
                                            data-lte-toggle="card-collapse"
                                            aria-label="Collapse card">
                                            <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                                            <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div id="line-chart"></div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!--end::Card-->
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-12 col-lg-6">
                            <!--begin::Card-->
                            <div class="card h-100">
                                <div class="card-header">
                                    <h3 class="card-title">Tren Realisasi Pendapatan APBD Kabupaten Tulang Bawang Barat</h3>

                                    <div class="card-tools">
                                        <button
                                            type="button"
                                            class="btn btn-tool"
                                            data-lte-toggle="card-collapse"
                                            aria-label="Collapse card">
                                            <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                                            <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div id="line-chart-2"></div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!--end::Card-->
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-12 col-lg-6">
                            <!--begin::Card-->
                            <div class="card h-100">
                                <div class="card-header">
                                    <h3 class="card-title">Perbandingan Realisasi Pendapatan dan Belanja APBD per-Triwulan</h3>

                                    <div class="card-tools">
                                        <button
                                            type="button"
                                            class="btn btn-tool"
                                            data-lte-toggle="card-collapse"
                                            aria-label="Collapse card">
                                            <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                                            <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div id="column-chart"></div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!--end::Card-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Container-->
            </div>
            <!--end::App Content-->
        </main>
        <!--end::App Main-->
        <!--begin::Footer-->
        <footer class="app-footer">
            <!--begin::To the end-->
            <div class="float-end d-none d-sm-inline">v26b-SILACAK</div>
            <!--end::To the end-->
            <!--begin::Copyright-->
            <strong>
                Copyright &copy; <?php echo date('Y'); ?>&nbsp;
                <a href="https://adbang.lampungprov.go.id/kabtbb/" class="text-decoration-none">Bagian Administrasi Pembangunan Setda Kabupaten Tulang Bawang Barat</a>.
            </strong>
            <!--end::Copyright-->
        </footer>
        <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
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
         * ----------
         * A small showcase of the most common ApexCharts chart types.
         * All charts get an explicit height to avoid an ApexCharts
         * ResizeObserver feedback loop on browser zoom (see #6019).
         */

        //--------------
        // - LINE CHART -
        //--------------

        const line_chart_options = {
            series: [{
                name: 'Revenue',
                data: [
                    31200, 34800, 32600, 39400, 42100, 45800, 44300, 49700, 52400, 56900, 60200, 65800,
                ],
            }, ],
            chart: {
                height: 300,
                type: 'line',
                toolbar: {
                    show: false,
                },
            },
            colors: ['#0d6efd'],
            stroke: {
                curve: 'straight',
                width: 3,
            },
            dataLabels: {
                enabled: false,
            },
            markers: {
                size: 4,
            },
            xaxis: {
                categories: [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May',
                    'Jun',
                    'Jul',
                    'Aug',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dec',
                ],
            },
            yaxis: {
                labels: {
                    formatter(value) {
                        return `$${Math.round(value / 1000)}k`;
                    },
                },
            },
            tooltip: {
                y: {
                    formatter(value) {
                        return `$${value.toLocaleString()}`;
                    },
                },
            },
        };

        const line_chart = new ApexCharts(document.querySelector('#line-chart'), line_chart_options);
        line_chart.render();

        //------------------
        // - END LINE CHART -
        //------------------

        //--------------
        // - LINE CHART 2-
        //--------------

        const line_chart2_options = {
            series: [{
                name: 'Revenue',
                data: [
                    31200, 34800, 32600, 39400, 42100, 45800, 44300, 49700, 52400, 56900, 60200, 65800,
                ],
            }, ],
            chart: {
                height: 300,
                type: 'line',
                toolbar: {
                    show: false,
                },
            },
            colors: ['#0d6efd'],
            stroke: {
                curve: 'straight',
                width: 3,
            },
            dataLabels: {
                enabled: false,
            },
            markers: {
                size: 4,
            },
            xaxis: {
                categories: [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May',
                    'Jun',
                    'Jul',
                    'Aug',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dec',
                ],
            },
            yaxis: {
                labels: {
                    formatter(value) {
                        return `$${Math.round(value / 1000)}k`;
                    },
                },
            },
            tooltip: {
                y: {
                    formatter(value) {
                        return `$${value.toLocaleString()}`;
                    },
                },
            },
        };

        const line_chart2 = new ApexCharts(document.querySelector('#line-chart-2'), line_chart2_options);
        line_chart2.render();

        //------------------
        // - END LINE CHART 2-
        //------------------

        //----------------
        // - COLUMN CHART -
        //----------------

        const column_chart_options = {
            series: [{
                    name: 'Online Store',
                    data: [44300, 55600, 57200, 61400],
                },
                {
                    name: 'Retail Stores',
                    data: [35100, 41200, 36800, 45300],
                },
            ],
            chart: {
                height: 300,
                type: 'bar',
                toolbar: {
                    show: false,
                },
            },
            colors: ['#6f42c1', '#20c997'],
            plotOptions: {
                bar: {
                    columnWidth: '55%',
                    borderRadius: 4,
                },
            },
            dataLabels: {
                enabled: false,
            },
            xaxis: {
                categories: ['Q1 2025', 'Q2 2025', 'Q3 2025', 'Q4 2025'],
            },
            yaxis: {
                labels: {
                    formatter(value) {
                        return `$${Math.round(value / 1000)}k`;
                    },
                },
            },
            tooltip: {
                y: {
                    formatter(value) {
                        return `$${value.toLocaleString()}`;
                    },
                },
            },
        };

        const column_chart = new ApexCharts(
            document.querySelector('#column-chart'),
            column_chart_options,
        );
        column_chart.render();

        //--------------------
        // - END COLUMN CHART -
        //--------------------

        //---------------
        // - DONUT CHART -
        //---------------

        const donut_chart_options = {
            series: [3450, 2210, 1160, 940, 620],
            chart: {
                type: 'donut',
                height: 350,
            },
            labels: ['Organic Search', 'Direct', 'Referral', 'Social Media', 'Email'],
            dataLabels: {
                enabled: false,
            },
            colors: ['#0d6efd', '#20c997', '#ffc107', '#d63384', '#6f42c1'],
        };

        const donut_chart = new ApexCharts(
            document.querySelector('#donut-chart'),
            donut_chart_options,
        );
        donut_chart.render();

        //-------------------
        // - END DONUT CHART -
        //-------------------

        //--------------------
        // - RADIAL BAR CHART -
        //--------------------

        const radialbar_chart_options = {
            series: [76, 67, 61],
            chart: {
                type: 'radialBar',
                height: 350,
            },
            labels: ['Sales', 'Marketing', 'Support'],
            colors: ['#0d6efd', '#20c997', '#ffc107'],
            plotOptions: {
                radialBar: {
                    dataLabels: {
                        total: {
                            show: true,
                            label: 'Average',
                        },
                    },
                },
            },
        };

        const radialbar_chart = new ApexCharts(
            document.querySelector('#radialbar-chart'),
            radialbar_chart_options,
        );
        radialbar_chart.render();

        //------------------------
        // - END RADIAL BAR CHART -
        //------------------------

        //---------------
        // - MIXED CHART -
        //---------------

        const mixed_chart_options = {
            series: [{
                    name: 'Orders',
                    type: 'column',
                    data: [440, 505, 414, 671, 227, 413, 201, 352, 752],
                },
                {
                    name: 'Revenue',
                    type: 'line',
                    data: [23100, 26200, 22800, 34100, 18300, 24400, 15600, 21900, 38200],
                },
            ],
            chart: {
                height: 300,
                type: 'line',
                toolbar: {
                    show: false,
                },
            },
            colors: ['#20c997', '#0d6efd'],
            stroke: {
                width: [0, 3],
                curve: 'smooth',
            },
            plotOptions: {
                bar: {
                    columnWidth: '55%',
                    borderRadius: 4,
                },
            },
            dataLabels: {
                enabled: false,
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
            },
            yaxis: [{
                    title: {
                        text: 'Orders',
                    },
                },
                {
                    opposite: true,
                    title: {
                        text: 'Revenue',
                    },
                    labels: {
                        formatter(value) {
                            return `$${Math.round(value / 1000)}k`;
                        },
                    },
                },
            ],
        };

        const mixed_chart = new ApexCharts(
            document.querySelector('#mixed-chart'),
            mixed_chart_options,
        );
        mixed_chart.render();

        //-------------------
        // - END MIXED CHART -
        //-------------------
    </script>
    <!--end::Script-->
</body>
<!--end::Body-->

</html>