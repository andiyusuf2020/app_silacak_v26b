<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard SILACAK - Kabupaten Tulang Bawang Barat</title>
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
                <span class="logo-text">Dashboard SILACAK</span>
            </a>
            <ul class="nav-links">
                <li><a href="<?= base_url() ?>" class="active">Home</a></li>
                <li><a href="#dashboard">Dashboard Pembangunan</a></li>
                <li><a href="#analytics">Capaian Kinerja Anggaran</a></li>
                <li><a href="#reports">Capaian Program Unggulan</a></li>
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
            <li><a href="#reports">Capaian Program Unggulan</a></li>
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
                <img src="<?= base_url() ?>cssportal/img_home/tubaba.png" width="20%" />

                <h1>SILACAK<br>Executive Dashboard</h1>
                <p>Penyampaian Resume Data Pembangunan Kabupaten Tulang Bawang Barat melalui Sistem Laporan Capaian Kinerja (SILACAK)</p>
                <a href="<?= base_url('pilihakses') ?>" class="cta-button">MASUK</a>

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
                    <div class="stat-description">Jumlah Anggaran APBD yang dialokasikan untuk pembangunan di wilayah Kabupaten Tulang Bawang Barat.</div>
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
                        <div class="chart-options">
                            <span class="chart-option active">2024</span>
                            <span class="chart-option">2023</span>
                            <span class="chart-option">2022</span>
                        </div>
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
        </div>
    </section>

    <!-- Reports Section -->
    <section class="reports-section" id="reports">
        <div class="dashboard-container">
            <div align="center">
                <!-- <h2 class="section-title">Capaian Program Unggulan</h2> -->

                <img style="max-height: 200px;" src="<?php echo base_url('cssportal/img_home'); ?>/bupati.png">
                <img style="max-height: 200px;" src="<?php echo base_url('cssportal/img_home'); ?>/wakilbup.png">
            </div>
            <!-- <h2 class="section-title">Program Unggulan Bupati dan Wakil Bupati</h2><br> -->
            <h2 class="section-title">
                5 Program Unggulan dan Prioritas daerah Kabupaten Tulang Bawang Barat (Tubaba) tahun 2026
                di bawah kepemimpinan Bupati Novriwan Jaya difokuskan pada peningkatan kualitas sumber daya manusia,
                pemberdayaan ekonomi masyarakat, serta pembangunan berkelanjutan.

            </h2>
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-icon">💼</div>
                    <h3 class="info-title">Tubaba Q Sehat</h3>
                    <!-- <div class="info-value">98.5%</div> -->
                    <p style="font-size: 14px; color: #a0a0a0;">
                        "Tubaba Q Sehat" hadir sebagai bentuk
                        komitmen pemerintah daerah untuk memastikan bahwa setiap warga
                        memiliki akses yang memadai dan berkulitas terhadap layanan kesehatan,
                        sehingga dapat meningkatkan kualitas hidup dan kesejahteraan masyarakat secara keseluruhan.</p>
                    </p>
                </div>
                <div class="info-card">
                    <div class="info-icon">📱</div>
                    <h3 class="info-title">Tubaba Q Cerdas</h3>
                    <p style="font-size: 14px; color: #a0a0a0;">
                        Dengan target angka putus sekolah 0% Pemerintah Kabupaten Tulang Bawang Barat
                        berkomitmen untuk menciptakan generasi muda yang cerdas, kreatif, dan inovatif serta
                        membangun generasi berkarakter unggul melalui 5 pilar pendidikan karakter
                        (Cinta Tuhan, Disiplin, Sopan santun, Kerja sama, serta Cinta tanah air dan lingkungan)</p>
                </div>
                <div class="info-card">
                    <div class="info-icon">🌍</div>
                    <h3 class="info-title">Tubaba Q Berdaya (KUR Super Mikro)<br>
                        "UMKM Tumbuh,ekonomi berdaya"
                    </h3>
                    <p style="font-size: 14px; color: #a0a0a0;">
                        Program pemberdayaan ekonomi rakyat ini menyasar para pelaku UMKM, usaha kecil,
                        hingga peternak lokal. Melalui fasilitas Kredit Usaha Rakyat (KUR) Super Mikro
                        tanpa agunan dengan bunga rendah (berkisar Rp5 juta hingga Rp50 juta),
                        warga didorong untuk mengubah usaha sampingan menjadi sumber penghasilan utama</p>
                </div>
                <div class="info-card">
                    <div class="info-icon">🚀</div>
                    <h3 class="info-title">Bank Sampah Bergerak <br>
                        "Dari sampah menjadi berkah"</h3>
                    </h3>
                    <p style="font-size: 14px; color: #a0a0a0;">
                        Merupakan inovasi unggulan di bidang lingkungan hidup. Program ini telah dibentuk di seluruh tiyuh (kampung)
                        di Tubaba untuk membersihkan lingkungan
                        sekaligus mengubah sampah menjadi barang yang memiliki nilai ekonomi tinggi</p>
                </div>
                <div class="info-card">
                    <div class="info-icon">⚡</div>
                    <h3 class="info-title">Penguatan Infrastruktur <br>
                        "Infrastruktur berkualitas, Lancar Konektifitas"</h3>
                    <p style="font-size: 14px; color: #a0a0a0;">
                        Pembangunan/pemeliharaan infrastruktur dalam bentuk sinergitas program
                        dilaksanakan dalam skema sinkronisasi kebutuhan program di bidang infrastruktur
                        yang dapat dibiayai oleh APBD Pemkab Tubaba; APBD Pemprov Lampung hingga
                        APBN Pemerintah Pusat.</p>
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
                <h3>BAGIAN ADMINISTRASI PEMBANGUNAN SETDA KABUPATEN TULANG BAWANG BARAT</h3>

                <a href="mailto:bagadbang@tubaba.go.id" class="contact-item" style="text-decoration: none; color: inherit;">
                    <div class="contact-icon">📧</div>
                    <div class="contact-details">
                        <h4>Email Address</h4>
                        <p>bagadbang@tubaba.go.id<br>adminsupport@tubaba.go.id</p>
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
                        <p>Jl. Diponegoro No. 86 Panaragan Jaya, Panaragan,
                            Kec. Tulang Bawang Tengah, <br>Kab. Tulang Bawang Barat, Lampung 34693</p>
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
            <p class="copyright">© 2026 SILACAK.
                | Designed by <a href="https://silacak.tubaba.go.id" rel="nofollow noopener" target="_blank">Bagian Administrasi Pembangunan Setda Kabupaten Tulang Bawang Barat</a></p>
        </div>
    </footer>

    <script src="<?php echo base_url('cssportal/templatemo-graph-script.js'); ?>"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>

</body>

</html>