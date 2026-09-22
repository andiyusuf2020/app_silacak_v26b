<!doctype html>
<html lang="en">

<head>
    <script>
        (function(w, i, g) {
            w[g] = w[g] || [];
            if (typeof w[g].push == 'function') w[g].push(i)
        })
        (window, 'GTM-WHH7CJ83', 'google_tags_first_party');
    </script>
    <script>
        (function(w, d, s, l) {
            w[l] = w[l] || [];
            (function() {
                w[l].push(arguments);
            })('set', 'developer_id.dYzg1YT', true);
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s);
            j.async = true;
            j.src = '/wzrt/';
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer');
    </script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>SiTAPIS-KAB | Data User </title>

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
    <meta name="title" content="AdminLTE 4 | Data Tables" />
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

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css"
        crossorigin="anonymous" />
    <script data-cfasync="false" nonce="3a408718-bcc3-4fe8-ad91-641bef2a4679">
        try {
            (function(w, d) {
                ! function(W, X, Y, Z) {
                    if (W.zaraz) console.error("zaraz is loaded twice");
                    else {
                        W[Y] = W[Y] || {};
                        W[Y].executed = [];
                        W.zaraz = {
                            deferred: [],
                            listeners: []
                        };
                        W.zaraz._v = "20";
                        W.zaraz._n = "3a408718-bcc3-4fe8-ad91-641bef2a4679";
                        W.zaraz.q = [];
                        W.zaraz._f = function($) {
                            return async function() {
                                var ba = Array.prototype.slice.call(arguments);
                                W.zaraz.q.push({
                                    m: $,
                                    a: ba
                                })
                            }
                        };
                        for (const bb of ["track", "set", "debug"]) W.zaraz[bb] = W.zaraz._f(bb);
                        W.zaraz.init = () => {
                            var bc = X.getElementsByTagName(Z)[0],
                                bd = X.createElement(Z),
                                be = X.getElementsByTagName("title")[0];
                            be && (W[Y].t = X.getElementsByTagName("title")[0].text);
                            W[Y].x = Math.random();
                            W[Y].w = W.screen.width;
                            W[Y].h = W.screen.height;
                            W[Y].j = W.innerHeight;
                            W[Y].e = W.innerWidth;
                            W[Y].l = W.location.href;
                            W[Y].r = X.referrer;
                            W[Y].k = W.screen.colorDepth;
                            W[Y].n = X.characterSet;
                            W[Y].o = (new Date).getTimezoneOffset();
                            if (W.dataLayer)
                                for (const bf of Object.entries(Object.entries(dataLayer).reduce((bg, bh) => ({
                                        ...bg[1],
                                        ...bh[1]
                                    }), {}))) zaraz.set(bf[0], bf[1], {
                                    scope: "page"
                                });
                            W[Y].q = [];
                            for (; W.zaraz.q.length;) {
                                const bi = W.zaraz.q.shift();
                                W[Y].q.push(bi)
                            }
                            bd.defer = !0;
                            for (const bj of [localStorage, sessionStorage]) Object.keys(bj || {}).filter(bl => bl.startsWith("_zaraz_")).forEach(bk => {
                                try {
                                    W[Y]["z_" + bk.slice(7)] = JSON.parse(bj.getItem(bk))
                                } catch {
                                    W[Y]["z_" + bk.slice(7)] = bj.getItem(bk)
                                }
                            });
                            bd.referrerPolicy = "origin";
                            bd.src = "/cdn-cgi/zaraz/s.js?z=" + btoa(encodeURIComponent(JSON.stringify(W[Y])));
                            bc.parentNode.insertBefore(bd, bc)
                        };
                        ["complete", "interactive"].includes(X.readyState) ? zaraz.init() : W.addEventListener("DOMContentLoaded", zaraz.init)
                    }
                }(w, d, "zarazData", "script");
                window.zaraz._p = async mU => new Promise(mV => {
                    if (mU) {
                        mU.e && mU.e.forEach(mW => {
                            try {
                                const mX = d.querySelector("script[nonce]"),
                                    mY = mX?.nonce || mX?.getAttribute("nonce"),
                                    mZ = d.createElement("script");
                                mY && (mZ.nonce = mY);
                                mZ.innerHTML = mW;
                                mZ.onload = () => {
                                    d.head.removeChild(mZ)
                                };
                                d.head.appendChild(mZ)
                            } catch (m$) {
                                console.error(`Error executing script: ${mW}\n`, m$)
                            }
                        });
                        Promise.allSettled((mU.f || []).map(na => fetch(na[0], na[1])))
                    }
                    mV()
                });
                zaraz._p({
                    "e": ["(function(w,d){})(window,document)"]
                });
            })(window, document)
        } catch (e) {
            throw fetch("/cdn-cgi/zaraz/t"), e;
        };
    </script>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <!--begin::Header-->
        <?php echo view('template/_part/nav'); ?>

        <!--end::Header-->
        <!--begin::Sidebar-->
        <?php
        if ($groupmenu == 'superadmin') {
            echo view('template/_part/menusuperadmin');
        }
        ?>
        <!--end::Sidebar-->
        <main class="app-main">
            <div class="app-content-header">
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
                                    <?php if ($groupuser == 'superadmin') { ?>
                                        <button type="button" class="btn btn-outline-success mb-2"> <a href="<?= base_url('lrfkadmin'); ?>" class="nav-link">ADMIN</a> </button>
                                    <?php } ?>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <!--end::Row-->
                </div>
            </div>
            <div class="app-content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Users</h3>
                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 16rem">
                                    <span class="input-group-text">
                                        <i class="bi bi-search" aria-hidden="true"></i>
                                    </span>
                                    <input
                                        id="table-filter"
                                        type="search"
                                        class="form-control"
                                        placeholder="Filter rows&hellip;"
                                        aria-label="Filter rows" />
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex gap-2 mb-3">
                                <button id="export-csv" type="button" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-filetype-csv me-1" aria-hidden="true"></i>
                                    Export CSV
                                </button>
                                <button id="export-json" type="button" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-filetype-json me-1" aria-hidden="true"></i>
                                    Export JSON
                                </button>
                                <button id="print-table" type="button" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-printer me-1" aria-hidden="true"></i>
                                    Print
                                </button>
                            </div>
                            <div id="users-table"></div>
                        </div>
                        <div class="card-footer text-secondary small">
                            Powered by
                            <a href="https://adbang.lampungprov.go.id/sitapis-kab" target="_blank" rel="noopener">SiTAPIS-KAB</a>
                            &mdash; Biro Administrasi Pembangunan Setda Provinsi Lampung.
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!--begin::Footer-->
        <footer class="app-footer">
            <!--begin::To the end-->
            <div class="float-end d-none d-sm-inline">Anything you want</div>
            <!--end::To the end-->
            <!--begin::Copyright-->
            <strong>
                Copyright &copy; 2014-2026&nbsp;
                <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
            </strong>
            All rights reserved.
            <!--end::Copyright-->
        </footer>
        <!--end::Footer-->
    </div>
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
    <!--begin::Charts follow the colour mode-->
    <script>
        // ApexCharts draws light-theme tooltips and axis text unless told otherwise,
        // which is unreadable in dark mode (#6105). Give it the page's colour mode as
        // a global default before any chart is created — this runs before the chart
        // pages' own scripts — and keep every chart that has a `chart.id` in step
        // when the mode changes (ColorMode, the OS in auto mode, or your own code).
        (() => {
            'use strict';
            const mode = () =>
                document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light';
            // `Apex` is ApexCharts' global-options object; it must exist before the library loads.
            // theme.mode also sets a dark chart background — keep the card's instead.
            // eslint-disable-next-line unicorn/no-global-object-property-assignment
            globalThis.Apex ||= {};
            const apex = globalThis.Apex;
            apex.theme = {
                mode: mode()
            };
            apex.chart = Object.assign(apex.chart || {}, {
                background: 'transparent'
            });
            new MutationObserver(() => {
                const next = mode();
                apex.theme = {
                    mode: next
                };
                const instances = apex._chartInstances || [];
                for (const {
                        chart
                    }
                    of instances) {
                    chart.updateOptions({
                        theme: {
                            mode: next
                        }
                    }, false, false);
                }
            }).observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['data-bs-theme'],
            });
        })();
    </script>
    <!--end::Charts follow the colour mode-->

    <!--begin::Color Mode Toggle-->
    <!-- The light/dark/auto switcher ships in adminlte.js as the ColorMode
     module (since 4.1) — no page script needed. Only the no-flash snippet
     in <head> stays inline, because it must run before first paint. -->
    <!--end::Color Mode Toggle-->

    <script
        src="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/js/tabulator.min.js"
        crossorigin="anonymous"></script>
    <script>
        const statusBadge = (cell) => {
            const value = cell.getValue();
            const map = {
                Active: 'success',
                Invited: 'info',
                Suspended: 'secondary'
            };
            const color = map[value] || 'secondary';
            return `<span class="badge text-bg-${color}">${value}</span>`;
        };

        document.addEventListener('DOMContentLoaded', () => {


            const data = <?php echo $datajson; ?>;
            // Fungsi enkripsi (Base64)
            function encryptUrl(url) {
                return btoa(url);
            }

            // Fungsi dekripsi (Base64)
            function decryptUrl(encoded) {
                return atob(encoded);
            }

            // Fungsi hashing SHA-256 (async)
            async function hashSHA256(message) {
                const msgBuffer = new TextEncoder().encode(message);
                const hashBuffer = await crypto.subtle.digest("SHA-256", msgBuffer);
                const hashArray = Array.from(new Uint8Array(hashBuffer));
                return hashArray.map(b => b.toString(16).padStart(2, "0")).join("");
            }

            const prefix = "updateuser/";
            const table = new Tabulator('#users-table', {
                data: data,
                layout: 'fitColumns',
                pagination: true,
                paginationSize: 10,
                paginationSizeSelector: [10, 25, 50, 100],
                movableColumns: true,
                columns: [{
                        title: '#',
                        field: 'id',
                        width: 60,
                        headerSort: true
                    },
                    {
                        title: 'Name',
                        field: 'username',
                        headerFilter: 'input'
                    },
                    {
                        title: 'Email',
                        field: 'email',
                        headerFilter: 'input'
                    },
                    {
                        title: 'Role',
                        field: 'group_name',
                        headerFilter: 'list',
                        headerFilterParams: {
                            values: ['', 'Admin', 'Editor', 'Viewer']
                        },
                        width: 120,
                    },
                    {
                        title: 'Status',
                        field: 'active',
                        formatter: function(cell) {
                            const value = cell.getValue();
                            // Jika nilai 1 atau "1" => Aktif, jika 0 atau "0" => NonAktif
                            if (value == 1) {
                                return '<span class="badge bg-success">Aktif</span>';
                            } else {
                                return '<span class="badge bg-danger">NonAktif</span>';
                            }
                        },
                        headerFilter: 'list',
                        headerFilterParams: {
                            values: [' ', 'Aktif', 'NonAktif']
                        },
                        width: 130,
                        hozAlign: 'center',
                    },
                    {
                        title: 'Joined',
                        field: 'created_at',
                        sorter: 'date',
                        width: 130
                    },
                    {
                        title: 'Actions',
                        field: 'id',
                        hozAlign: 'center',
                        width: 180,
                        formatter: function(cell) {
                            const rowData = cell.getRow().getData();

                            return `
                    <a href="updateuser?token=${rowData.update_token}" class="btn btn-sm btn-primary">Update</a>
                    <a href="deleteuser/${rowData.id}" class="btn btn-sm btn-danger">Delete</a>
                `;
                        }
                    }
                ],
            });

            document.getElementById('table-filter').addEventListener('input', (e) => {
                const value = e.target.value;
                if (value) {
                    table.setFilter([
                        [{
                                field: 'name',
                                type: 'like',
                                value: value
                            },
                            {
                                field: 'email',
                                type: 'like',
                                value: value
                            },
                        ],
                    ]);
                } else {
                    table.clearFilter();
                }
            });

            document
                .getElementById('export-csv')
                .addEventListener('click', () => table.download('csv', 'users.csv'));
            document
                .getElementById('export-json')
                .addEventListener('click', () => table.download('json', 'users.json'));
            document
                .getElementById('print-table')
                .addEventListener('click', () => table.print(false, true));
        });
    </script>
    <script type="module" src="https://static.cloudflareinsights.com/beacon.min.js/v31edd6df95cf4e85bb4c19e7a9bdbcba1788362987495" integrity="sha512-iIg7k2xntmwu6/uSb5tpc/hySgZc4eoL31yB29W6tJFo2akwjPWcEqnCEdJvGexCL0KEQwVYv5BlowfhVz26hg==" data-cf-beacon='{"version":"2024.11.0","token":"2437d112162f4ec4b63c3ca0eb38fb20","r":1,"spa":2}' crossorigin="anonymous"></script>
</body>

</html>