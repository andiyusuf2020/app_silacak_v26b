<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard SiTAPIS - Provinsi Lampung</title>
    <link rel="stylesheet" href="<?php echo base_url('cssportal/templatemo-graph-page.css'); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
        crossorigin="anonymous" />
</head>

<body>
    <!-- Navigation -->
    <nav id="navbar">
        <div class="nav-container">
            <a href="#home" class="logo">
                <div class="logo-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M3 13h2v8H3zm4-8h2v13H7zm4-2h2v15h-2zm4 4h2v11h-2zm4-2h2v13h-2z" />

                    </svg>
                </div>
                <span class="logo-text">Dashboard SiTAPIS</span>
            </a>
            <ul class="nav-links">
                <li><a href="<?= base_url() ?>" class="active">Home</a></li>
                <li><a href="#dashboard">Dashboard</a></li>
                <!-- <li><a href="#analytics">Analytics</a></li>
                <li><a href="#reports">Reports</a></li>
                <li><a href="#contact">Contact</a></li> -->
            </ul>
            <!-- <a href="https://www.google.com/search" target="_blank" rel="noopener" title="Search">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
            </a> -->
            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <ul class="nav-links-mobile" id="navLinksMobile">
            <li><a href="<?= base_url() ?>" class="active">Home</a></li>
            <li><a href="#dashboard">Dashboard</a></li>
            <!-- <li><a href="#analytics">Analytics</a></li>
            <li><a href="#reports">Reports</a></li>
            <li><a href="#contact">Contact</a></li> -->
        </ul>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-bg"></div>
        <div class="geometric-shapes">
            <div class="shape shape1"></div>
            <div class="shape shape2"></div>
            <div class="shape shape3"></div>
            <div class="shape shape4"></div>
            <div class="shape shape5"></div>
            <div class="shape shape6"></div>


        </div>

        <div class="hero-content">
            <div class="hero-text">
                <img src="<?= base_url() ?>cssportal/img_home/lampung.png" width="20%" />

                <h1>Pemanfaatan<br>Dana APBN</h1>
                <p>Dekonsentrasi dan Tugas Pembantuan</p>
                <a href="#dashboard" class="cta-button">Get Started</a>

            </div>
            <div class="hero-visual">
                <div class="city-container">
                    <div class="building building1">
                        <div class="building-fill"></div>
                        <div class="building-windows"></div>
                    </div>
                    <div class="building building2">
                        <div class="building-fill"></div>
                        <div class="building-windows"></div>
                    </div>
                    <div class="building building3">
                        <div class="building-fill"></div>
                        <div class="building-windows"></div>
                    </div>
                    <div class="building building4">
                        <div class="building-fill"></div>
                        <div class="building-windows"></div>
                    </div>
                    <div class="neon-line neon-line1"></div>
                    <div class="neon-line neon-line2"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dashboard Section -->
    <section class="dashboard-section" id="dashboard">
        <div class="dashboard-container">
            <h2 class="section-title">Dashboard Overview</h2>
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">📊</div>
                        <div class="stat-title">Sasaran Pembangunan</div>
                    </div>
                    <table border="1" class="stat-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>PERANGKAT DAERAH</th>
                                <th>PAGU DIPA DEKONSENTASI</th>
                                <th>REALISASI DEKONSENTRASI</th>
                                <th>%</th>
                                <th>SISA PAGU DEKONSENTRASI</th>
                                <th>PAGU DIPA TUGAS PEMBANTUAN</th>
                                <th>REALISASI TUGAS PEMBANTUAN</th>
                                <th>%</th>
                                <th>SISA PAGU TUGAS PEMBANTUAN</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($danaapbn as $key => $value) { ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $value['nm_subunit']; ?></td>
                                    <td><?php echo number_format($value['dana_dekon'], 2, ',', '.'); ?></td>
                                    <td><?php echo number_format($value['real_dekon'], 2, ',', '.'); ?></td>
                                    <td><?php echo $value['dana_dekon'] > 0 ? round(($value['real_dekon'] / $value['dana_dekon']) * 100, 2) : 0; ?>%</td>
                                    <td><?php echo number_format($value['dana_dekon'] - $value['real_dekon'], 2, ',', '.'); ?></td>
                                    <td><?php echo number_format($value['dana_tp'], 2, ',', '.'); ?></td>
                                    <td><?php echo number_format($value['real_tp'], 2, ',', '.'); ?></td>
                                    <td><?php echo $value['dana_tp'] > 0 ? round(($value['real_tp'] / $value['dana_tp']) * 100, 2) : 0; ?>%
                                    <td><?php echo number_format($value['dana_tp'] - $value['real_tp'], 2, ',', '.'); ?></td>
                                </tr>

                            <?php } ?>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2">Total</td>
                                <td><?php echo number_format(array_sum(array_column($danaapbn, 'dana_dekon')), 2, ',', '.'); ?></td>
                                <td><?php echo number_format(array_sum(array_column($danaapbn, 'real_dekon')), 2, ',', '.'); ?></td>
                                <td><?php echo array_sum(array_column($danaapbn, 'dana_dekon')) > 0 ? round((array_sum(array_column($danaapbn, 'real_dekon')) / array_sum(array_column($danaapbn, 'dana_dekon'))) * 100, 2) : 0; ?>%</td>
                                <td><?php echo number_format(array_sum(array_column($danaapbn, 'dana_dekon')) - array_sum(array_column($danaapbn, 'real_dekon')), 2, ',', '.'); ?></td>
                                <td><?php echo number_format(array_sum(array_column($danaapbn, 'dana_tp')), 2, ',', '.'); ?></td>
                                <td><?php echo number_format(array_sum(array_column($danaapbn, 'real_tp')), 2, ',', '.'); ?></td>
                                <td><?php echo array_sum(array_column($danaapbn, 'dana_tp')) > 0 ? round((array_sum(array_column($danaapbn, 'real_tp')) / array_sum(array_column($danaapbn, 'dana_tp'))) * 100, 2) : 0; ?>%</td>
                                <td><?php echo number_format(array_sum(array_column($danaapbn, 'dana_tp')) - array_sum(array_column($danaapbn, 'real_tp')), 2, ',', '.'); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="stat-chart">
                        Sumber : Kanwil Direktorat Jendral Perbendaharaan Provinsi Lampung. Sesuai Penyerahan DIPA TA 2026
                        <canvas class="mini-chart" id="miniChart1"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            Copyright &copy; <?php echo date('Y'); ?>&nbsp;
            <a href="https://adbang.lampungprov.go.id/e-tapis" class="text-decoration-none">Biro Administrasi Pembangunan Setda Provinsi Lampung</a>.
        </div>
    </footer>

    <script src="<?php echo base_url('cssportal/templatemo-graph-script.js'); ?>"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>

</body>

</html>