<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard SiTAPIS - Provinsi Lampung</title>
    <link rel="stylesheet" href="<?php echo base_url('cssportal/templatemo-graph-page.css'); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
        crossorigin="anonymous">

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
                <li><a href="<?= base_url('desakumaju/opd') ?>" class="active">Login</a></li>
                <li><a href="<?= base_url() ?>" class="active">Home</a></li>
                <li><a href="#skema">Skema Desaku Maju</a></li>
                <!-- <li><a href="#analytics">Analytics</a></li> -->
                <li><a href="#pelaksanaan">Pelaksanaan Desaku Maju</a></li>
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
            <li><a href="#skema">Skema Desaku Maju</a></li>
            <li><a href="#analytics">Analytics</a></li>
            <li><a href="#pelaksanaan">Pelaksanaan Desaku Maju</a></li>
            <li><a href="#contact">Contact</a></li>
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
                <img src="<?= base_url() ?>cssportal/img_home/desakumaju.png" width="60%" />
                <h1>Membangun Ekosistem Ekonomi <br> Berbasis Desa</h1>
                <p>Mendorong Terwujudnya Ekonomi yang Inklusif, Mandiri dan Inovatif
                </p>
                <a href="<?= base_url() ?>" class="cta-button">SiTAPIS</a>

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
    <section class="dashboard-section" id="skema">
        <div class="dashboard-container">
            <h2 class="section-title">Skema Desaku Maju</h2>
            <!-- Stats Cards -->
            <!-- <div class="stats-grid">
                <div class="stat-card">
                    <img align="center" src="<?= base_url() ?>cssportal/img_home/desakumaju1.jpg" width="100%" />
                </div>
            </div>
            <div class="stats-grid"> -->
            <div class="stat-card">
                <div id="carouselExample" class="carousel slide">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="<?= base_url() ?>cssportal/img_home/desakumaju1.jpg" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="<?= base_url() ?>cssportal/img_home/desakumaju2.jpg" class="d-block w-100" alt="...">
                        </div>

                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
        </div>
    </section>
    <!-- Reports Section -->
    <section class="reports-section" id="pelaksanaan">
        <div class="dashboard-container">
            <h2 class="section-title">Pelaksanaan Desaku Maju</h2>
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-icon">💼</div>
                    <h3 class="info-title">Business Intelligence</h3>
                    <div class="info-value">98.5%</div>
                    <p style="font-size: 14px; color: #a0a0a0;">Accuracy in predictive analytics and business forecasting models.</p>
                </div>
                <div class="info-card">
                    <div class="info-icon">📱</div>
                    <h3 class="info-title">Mobile Analytics</h3>
                    <div class="info-value">2.4M</div>
                    <p style="font-size: 14px; color: #a0a0a0;">Mobile app downloads and active user engagement metrics.</p>
                </div>
            </div>
        </div>
    </section>


    <script src="<?php echo base_url('cssportal/templatemo-graph-script.js'); ?>"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

</body>

</html>