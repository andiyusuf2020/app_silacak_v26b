<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard SiTAPIS - KAB,</title>
    <link rel="icon" href="<?php echo base_url(); ?>/favicon.ico" type="image/gif">

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
            <a href="<?= base_url('dashboard') ?>" class="logo">
                <div class="logo-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M3 13h2v8H3zm4-8h2v13H7zm4-2h2v15h-2zm4 4h2v11h-2zm4-2h2v13h-2z" />
                    </svg>
                </div>
                <span class="logo-text">Dashboard SiTAPIS-KAB</span>
            </a>
            <ul class="nav-links">
                <li><a href="<?= base_url() ?>" class="active">Home</a></li>
                <li><a href="#dashboard">Dashboard Pembangunan</a></li>
                <li><a href="#analytics">Capaian Kinerja Anggaran</a></li>
                <li><a href="#reports">Program Unggulan Daerah</a></li>
                <li><a href="#contact">Contact</a></li>
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
            <li><a href="#dashboard">Dashboard Pembangunan</a></li>
            <li><a href="#analytics">Capaian Kinerja Anggaran</a></li>
            <li><a href="#reports">Program Unggulan Daerah</a></li>
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
                <img src="<?= base_url() ?>cssportal/img_home/sitapis-kab.png" width="40%" alt="Logo Kabupaten/Kota" />
                <h1>SiTAPIS-KAB<br>Dashboard</h1>
                <p>
                    Penyampaian Resume Data Administrasi Pembangunan .... yang
                    selaras antara Pemerintah Pusat, Provinsi, dan Kabupaten/Kota melalui
                    <strong>SiTAPIS-KAB</strong> (Sistem Data Pengendalian dan Informasi - Kabupaten/Kota)
                </p>

                <a href="<?= base_url('pilihakses') ?>" class="cta-button">LOGIN</a>

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
            <h2 class="section-title">Dashboard Pembangunan</h2>
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">📊</div>
                        <div class="stat-title">Jumlah Perangkat Daerah</div>
                    </div>
                    <div class="stat-value">125</div>
                    <div class="stat-description">Total number of departments and agencies under the provincial government.</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">📊</div>
                        <div class="stat-title">Jumlah Kecamatan</div>
                    </div>
                    <div class="stat-value">12</div>
                    <div class="stat-description">Total number of districts within the regency.</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">📊</div>
                        <div class="stat-title">Jumlah Tiuh/Kampung</div>
                    </div>
                    <div class="stat-value">120</div>
                    <div class="stat-description">Total number of villages within the regency.</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">💰</div>
                        <div class="stat-title">Total Anggaran APBD</div>
                    </div>
                    <div class="stat-value">1.5T</div>
                    <div class="stat-description">Jumlah Anggaran APBD yang dialokasikan untuk pembangunan di wilayah ...,.</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon"><i class="bi bi-bank2"></i></div>
                        <div class="stat-title">Index SAKIP</div>
                    </div>
                    <div class="stat-value">(B) 90.19</div>
                    <div class="stat-description">Sistem ini merupakan rangkaian aktivitas, alat,
                        dan prosedur yang dirancang untuk menetapkan,
                        mengukur, mengumpulkan data, serta melaporkan kinerja pada instansi pemerintah</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">🚀</div>
                        <div class="stat-title">Index Reformasi Birokrasi (IRB)</div>
                    </div>
                    <div class="stat-value">7,392</div>
                    <div class="stat-description">skor penilaian dari Kementerian Pendayagunaan Aparatur Negara dan Reformasi Birokrasi
                        untuk mengukur tingkat keberhasilan perbaikan tata kelola pemerintahan pada instansi pusat maupun daerah.</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">💰</div>
                        <div class="stat-title">Tingkat Kemiskinan</div>
                    </div>
                    <div class="stat-value">+28.5%</div>
                    <div class="stat-description">persentase penduduk yang memiliki pengeluaran per kapita di bawah garis kemiskinan</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">⚡</div>
                        <div class="stat-title">Angka Stunting</div>
                    </div>
                    <div class="stat-value">99.9%</div>
                    <div class="stat-description">persentase atau jumlah balita yang mengalami stunting, yaitu kondisi gagal tumbuh pada anak akibat kekurangan gizi kronis dan
                        infeksi berulang terutama dalam 1.000 Hari Pertama Kehidupan (HPK)—dari
                        dalam kandungan hingga anak berusia 5 tahun.</div>
                </div>
            </div>
        </div>
        </div>
        </div>
    </section>

    <!-- Analytics Section -->
    <section class="analytics-section" id="analytics">
        <div class="dashboard-container">
            <h2 class="section-title">Capaian Kinerja Anggaran</h2>

            <!-- Key Metrics Overview -->
            <div class="metrics-grid">
                <div class="metric-item">
                    <div class="metric-value">2.4M</div>
                    <div class="metric-label">PAGU PENDAPATAN </div>
                </div>
                <div class="metric-item">
                    <div class="metric-value">156K</div>
                    <div class="metric-label">REALISASI PENDAPATAN</div>
                </div>
                <div class="metric-item">
                    <div class="metric-value">4.2M</div>
                    <div class="metric-label">PAGU BELANJA(APBD)</div>
                </div>
                <div class="metric-item">
                    <div class="metric-value">68M</div>
                    <div class="metric-label">REALISASI BELANJA(APBD)</div>
                </div>
                <div class="metric-item">
                    <div class="metric-value">89</div>
                    <div class="metric-label">APBD PERANGKAT DAERAH</div>
                </div>
                <div class="metric-item">
                    <div class="metric-value">3.2K</div>
                    <div class="metric-label">REALISASI APBD PERANGKAT DAERAH</div>
                </div>
            </div>

            <!-- Chart Cards -->
            <div class="charts-grid">
                <div class="chart-card">
                    <div class="chart-header">
                        <h3 class="chart-title">📈 Tren Realisasi Belanja APBD Bulanan</h3>
                        <!-- <div class="chart-options">
                            <span class="chart-option active">2024</span>
                            <span class="chart-option">2023</span>
                            <span class="chart-option">2022</span>
                        </div> -->
                    </div>
                    <div class="chart-container">
                        <div class="bar-chart" id="barChart">
                            <div class="bar" style="height: 10%">
                                <span class="bar-value">10</span>
                                <span class="bar-label">Jan</span>
                            </div>
                            <div class="bar" style="height: 80%">
                                <span class="bar-value">180</span>
                                <span class="bar-label">Feb</span>
                            </div>
                            <div class="bar" style="height: 45%">
                                <span class="bar-value">90</span>
                                <span class="bar-label">Mar</span>
                            </div>
                            <div class="bar" style="height: 70%">
                                <span class="bar-value">140</span>
                                <span class="bar-label">Apr</span>
                            </div>
                            <div class="bar" style="height: 90%">
                                <span class="bar-value">200</span>
                                <span class="bar-label">May</span>
                            </div>
                            <div class="bar" style="height: 65%">
                                <span class="bar-value">130</span>
                                <span class="bar-label">Jun</span>
                            </div>
                            <div class="bar" style="height: 75%">
                                <span class="bar-value">150</span>
                                <span class="bar-label">Jul</span>
                            </div>
                            <div class="bar" style="height: 85%">
                                <span class="bar-value">170</span>
                                <span class="bar-label">Aug</span>
                            </div>
                            <div class="bar" style="height: 85%">
                                <span class="bar-value">25</span>
                                <span class="bar-label">Sept</span>
                            </div>
                            <div class="bar" style="height: 85%">
                                <span class="bar-value">100</span>
                                <span class="bar-label">Oct</span>
                            </div>
                            <div class="bar" style="height: 85%">
                                <span class="bar-value">170</span>
                                <span class="bar-label">Nov</span>
                            </div>
                            <div class="bar" style="height: 85%">
                                <span class="bar-value">170</span>
                                <span class="bar-label">Dec</span>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="chart-card">
                    <div class="chart-header">
                        <h3 class="chart-title">📊 Tren Realisasi Pendapatan Bulanan</h3>

                    </div>
                    <div class="chart-container">
                        <div class="bar-chart" id="barChart">
                            <div class="bar" style="height: 10%">
                                <span class="bar-value">10</span>
                                <span class="bar-label">Jan</span>
                            </div>
                            <div class="bar" style="height: 80%">
                                <span class="bar-value">180</span>
                                <span class="bar-label">Feb</span>
                            </div>
                            <div class="bar" style="height: 45%">
                                <span class="bar-value">90</span>
                                <span class="bar-label">Mar</span>
                            </div>
                            <div class="bar" style="height: 70%">
                                <span class="bar-value">140</span>
                                <span class="bar-label">Apr</span>
                            </div>
                            <div class="bar" style="height: 90%">
                                <span class="bar-value">200</span>
                                <span class="bar-label">May</span>
                            </div>
                            <div class="bar" style="height: 65%">
                                <span class="bar-value">130</span>
                                <span class="bar-label">Jun</span>
                            </div>
                            <div class="bar" style="height: 75%">
                                <span class="bar-value">150</span>
                                <span class="bar-label">Jul</span>
                            </div>
                            <div class="bar" style="height: 85%">
                                <span class="bar-value">170</span>
                                <span class="bar-label">Aug</span>
                            </div>
                            <div class="bar" style="height: 85%">
                                <span class="bar-value">25</span>
                                <span class="bar-label">Sept</span>
                            </div>
                            <div class="bar" style="height: 85%">
                                <span class="bar-value">100</span>
                                <span class="bar-label">Oct</span>
                            </div>
                            <div class="bar" style="height: 85%">
                                <span class="bar-value">170</span>
                                <span class="bar-label">Nov</span>
                            </div>
                            <div class="bar" style="height: 85%">
                                <span class="bar-value">170</span>
                                <span class="bar-label">Dec</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="chart-card">
                    <div class="chart-header">
                        <h3 class="chart-title">🌍 Tren Realisasi Belanja pertriwulan</h3>
                    </div>
                    <div class="chart-container">
                        <div class="bar-chart">
                            <div class="bar" style="height: 85%; background: linear-gradient(180deg, #ff6b6b 0%, #ff8e53 100%);">
                                <span class="bar-value">42%</span>
                                <span class="bar-label">Triwulan 1</span>
                            </div>
                            <div class="bar" style="height: 65%; background: linear-gradient(180deg, #4ecdc4 0%, #44a08d 100%);">
                                <span class="bar-value">28%</span>
                                <span class="bar-label">Triwulan 2</span>
                            </div>
                            <div class="bar" style="height: 45%; background: linear-gradient(180deg, #45b7d1 0%, #96c93d 100%);">
                                <span class="bar-value">18%</span>
                                <span class="bar-label">Triwulan 3</span>
                            </div>
                            <div class="bar" style="height: 25%; background: linear-gradient(180deg, #f093fb 0%, #f5576c 100%);">
                                <span class="bar-value">12%</span>
                                <span class="bar-label">Triwulan 4</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="chart-card">
                    <div class="chart-header">
                        <h3 class="chart-title">🌍 APBD Per Rekening Belanja</h3>
                    </div>
                    <div class="chart-container">
                        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: center;">
                            <thead>
                                <tr>
                                    <th>Uraian Belanja</th>
                                    <th>Pagu</th>
                                    <th>Persentase Realisasi </th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>Belanja Pegawai</td>
                                    <td>Rp 42.000.000</td>
                                    <td>42%</td>
                                </tr>
                                <tr>
                                    <td>Belanja Barang dan Jasa</td>
                                    <td>Rp 28.000.000</td>
                                    <td>28%</td>
                                </tr>
                                <tr>
                                    <td>Belanja Modal</td>
                                    <td>Rp 18.000.000</td>
                                    <td>18%</td>
                                </tr>
                                <tr>
                                    <td>Belanja Perjalanan Dinas</td>
                                    <td>Rp 12.000.000</td>
                                    <td>12%</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th>Rp 100.000.000</th>
                                    <th>100%</th>
                                </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="dashboard-container">
            <h2 class="section-title">Dashboard Realisasi Anggaran dan capaian aktivitas belanja yang merupakan
                output barang/jasa pada
                perangkat daerah
            </h2>
            <!-- Stats Cards -->

            <!-- Key Metrics Overview -->
            <div class="metrics-grid">
                <?php

                use App\Models\DataApbdModel\RealApbdModel;

                $this->realapbd = new RealApbdModel();

                use App\Models\DataApbdModel\PendApbdModel;
                use App\Models\DataApbdModel\RealPendApbdModel;

                $this->pendapbd = new PendApbdModel();
                $this->realpendapbd = new RealPendApbdModel();

                use App\Models\CapkinModel\TaSubKegCapkin2026;

                $this->subkegcapkin2026model = new TaSubKegCapkin2026();

                use App\Models\CapkinModel\TaKegPokokCapkinModel;

                $this->kegpokokmodal = new TaKegPokokCapkinModel();

                use App\Models\CapkinModel\TaRKegPokokCapkinModel;

                $this->rdkegpokokmodal = new TaRKegPokokCapkinModel();
                ?>
                <?php foreach ($dataopdadmin['data'] as $key => $value) { ?>


                    <div class="metric-item stat-card">
                        <div class="metric-label">Capaian Anggaran</div>

                        <div class="metric-value"><?= number_format(esc($value['PersentaseRealisasi']), 2, ',', '.') ?>%</div>
                        <div class="metric-label">Capaian Aktivitas Belanja</div>
                        <div class="metric-value"><?= number_format(esc($value['JumlahBelanja']), 0, ',', '.') ?></div>

                        <div class="metric-label"><?= esc($value['NAMA_UNIT_SKPD']) ?></div>
                    </div>
                <?php } ?>
            </div>
        </div>

    </section>

    <!-- Reports Section -->
    <section class="reports-section" id="reports">
        <div class="dashboard-container">
            <div align="center">
                <!-- <h2 class="section-title">Capaian Program Unggulan</h2> -->

                <img style="max-height: 200px;" src="<?php echo base_url('cssportal/img_home'); ?>/Xbupati.png" alt="Bupati">
                <img style="max-height: 200px;" src="<?php echo base_url('cssportal/img_home'); ?>/Xwakilbup.png" alt="Wakil Bupati">
            </div>
            <!-- <h2 class="section-title">Program Unggulan Bupati dan Wakil Bupati</h2><br> -->
            <h2 class="section-title">
                Progam Strategis/Unggulan/Prioritas Daerah.

            </h2>
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-icon">💼</div>
                    <h3 class="info-title">Bidang Infrastruktur</h3>
                    <!-- <div class="info-value">98.5%</div> -->
                    <p style="font-size: 14px; color: #a0a0a0;">
                        Pembangunan infrastruktur yang berkualitas dan berkelanjutan merupakan salah satu prioritas utama
                        pemerintah daerah. Dengan fokus pada pembangunan jalan, jembatan, fasilitas publik,
                        dan infrastruktur lainnya, pemerintah daerah berupaya meningkatkan konektivitas antarwilayah,
                        memperkuat perekonomian lokal, dan meningkatkan kualitas hidup masyarakat secara keseluruhan
                    </p>
                    </p>
                </div>
                <div class="info-card">
                    <div class="info-icon">📱</div>
                    <h3 class="info-title">Bidang Pendidikan</h3>
                    <p style="font-size: 14px; color: #a0a0a0;">
                        Pemerintah daerah berkomitmen untuk meningkatkan kualitas pendidikan di wilayahnya.
                        Dengan fokus pada peningkatan fasilitas pendidikan, pelatihan guru,
                        dan pengembangan kurikulum yang relevan, pemerintah daerah bertujuan untuk menciptakan
                        lingkungan belajar yang mendukung pertumbuhan akademik dan perkembangan pribadi siswa.
                        Upaya ini diharapkan dapat menghasilkan generasi muda yang berkualitas, siap menghadapi tantangan global,
                        dan mampu berkontribusi positif bagi pembangunan daerah.
                    </p>
                </div>
                <div class="info-card">
                    <div class="info-icon">🌍</div>
                    <h3 class="info-title">Bidang Ekonomi,Sosial dan Budaya<br>
                        "Meningkatkan Kesejahteraan Masyarakat"</h3>
                    </h3>
                    <p style="font-size: 14px; color: #a0a0a0;">
                        Pemerintah daerah berfokus pada pengembangan sektor ekonomi untuk meningkatkan kesejahteraan masyarakat.
                        Dengan mendorong investasi, mendukung usaha mikro, kecil, dan menengah (UMKM),
                        serta menciptakan lapangan kerja yang berkelanjutan,
                        pemerintah daerah bertujuan untuk memperkuat perekonomian lokal.
                        Upaya ini diharapkan dapat meningkatkan pendapatan masyarakat, mengurangi tingkat pengangguran
                    </p>
                </div>
                <div class="info-card">
                    <div class="info-icon">🚀</div>
                    <h3 class="info-title">Bidang Lingkungan Hidup<br>
                        "Meningkatkan Kualitas Lingkungan Hidup"</h3>
                    </h3>
                    <p style="font-size: 14px; color: #a0a0a0;">
                        Pemerintah daerah berkomitmen untuk menjaga dan meningkatkan kualitas lingkungan hidup di wilayahnya.
                        Dengan fokus pada pengelolaan sumber daya alam yang berkelanjutan, pengurangan polusi,
                        dan pelestarian ekosistem, pemerintah daerah bertujuan untuk menciptakan lingkungan yang sehat dan lestari bagi masyarakat.
                        Upaya ini mencakup program-program seperti penghijauan, pengelolaan limbah,
                        dan edukasi lingkungan untuk meningkatkan kesadaran masyarakat akan pentingnya menjaga lingkungan hidup.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section" id="contact">
        <div class="dashboard-container">
            <h2 class="section-title">Get In Touch</h2>
            <!-- Contact Info -->
            <div class="contact-info">
                <h3>BAGIAN ADMINISTRASI PEMBANGUNAN SETDA ......</h3>

                <a href="mailto:bagadbang@tubaba.go.id" class="contact-item" style="text-decoration: none; color: inherit;">
                    <div class="contact-icon">📧</div>
                    <div class="contact-details">
                        <h4>Email Address</h4>
                        <p>bagadbang@kab....go.id<br>adminsupport@tubaba.go.id</p>
                    </div>
                </a>

                <a href="tel:+6281234567890" class="contact-item" style="text-decoration: none; color: inherit;">
                    <div class="contact-icon">📞</div>
                    <div class="contact-details">
                        <h4>Phone Number</h4>
                        <p>+62 812-3456-7890<br>Available 24/7</p>
                    </div>
                </a>

                <a href="https://maps.google.com/?q=Kantor+Bupati+Tulang+Bawang+Barat" target="_blank" rel="noopener" class="contact-item" style="text-decoration: none; color: inherit;">
                    <div class="contact-icon">📍</div>
                    <div class="contact-details">
                        <h4>Office Location</h4>
                        <p>Jl. Diponegoro No. 86 ..., <br>Kab. ......., Lampung 34693</p>
                    </div>
                </a>

                <div class="contact-item">
                    <div class="contact-icon">🕒</div>
                    <div class="contact-details">
                        <h4>Business Hours</h4>
                        <p>Monday - Friday: 9:00 AM - 6:00 PM<br>Weekend: Emergency support only</p>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p class="copyright">© 2026 SIPDal.
                | Designed by <a href="https://adbang.lampungprov.go.id" rel="nofollow noopener" target="_blank">Bagian Administrasi Pembangunan Setda ...,</a></p>
        </div>
    </footer>

    <script src="<?php echo base_url('cssportal/templatemo-graph-script.js'); ?>"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>

</body>

</html>