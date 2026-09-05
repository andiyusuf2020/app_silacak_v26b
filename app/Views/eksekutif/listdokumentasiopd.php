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
                <li><a href="<?= base_url('eksekutif') ?>" class="active">Home</a></li>
                <!-- <li><a href="#dashboard">Dashboard Realisasi Anggaran</a></li>
                <li><a href="#reports">Aktifitas Kegiatan</a></li> -->

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
            <li><a href="<?= base_url('eksekutif') ?>" class="active">Home</a></li>
            <!-- <li><a href="#dashboard">Dashboard Realisasi Anggaran</a></li>
            <li><a href="#reports">Aktifitas Kegiatan</a></li> -->
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
                <h1>Data Analytics<br>Executive Dashboard</h1>
                <p>Penyampaian Resume Data Pembangunan Provinsi Lampung dari Sistem Data Pengadalian dan Informasi (SiTAPIS)</p>
                <a href="<?= base_url('eksekutif') ?>" class="cta-button">Get Started</a>

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
            <h2 class="section-title">Aktifitas Utama Perangkat Daerah Provinsi Lampung pendukun RPJMD 2026-2030 Provinsi Lampung</h2>
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">📊</div>
                        <div class="stat-title">Sasaran Pembangunan</div>
                    </div>
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

                    <!--begin::Container-->
                    <div class="container-fluid">
                        <div class="container">

                            <div class="map-container">
                                <!-- <div id="map"></div> -->
                                <div class="map-sidebar">
                                    <h3 class="section-title"><?= esc($nama_opd) ?></h3>
                                    <div class="lokasi-list">
                                        <?php if (empty($lokasi)): ?>
                                            <p>Tidak ada lokasi ditemukan</p>
                                        <?php else: ?>
                                            <?php foreach ($lokasi as $item): ?>
                                                <?php if ($item['gambar']): ?>
                                                    <img src="<?= base_url() ?>/uploads/dokumentasi/<?= esc($item['gambar']) ?>"
                                                        class="lokasi-thumbnail"
                                                        style="width: 100%; height: 150px; object-fit: cover; border-radius: 4px;">
                                                <?php else: ?>
                                                    <div class="no-image-placeholder" style="height: 300px; background: #eee; display: flex; align-items: center; justify-content: center; border-radius: 12px;">
                                                        <i class="bi bi-image" style="font-size: 2rem; color: #999;"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="lokasi-item" data-lat="<?= $item['latitude'] ?>" data-lng="<?= $item['longitude'] ?>">
                                                    <h4><?= $item['kegiatan'] ?></h4>
                                                    <p><small><?= $item['kategori'] ?></small></p>
                                                    <p><?= substr($item['deskripsi'], 0, 150) ?>...</p>
                                                </div>
                                                <div class="action-links">
                                                    <a href="<?= hash_url('eksekutif/capkin', [
                                                                    'kdSU' => $kdSU ?? null,
                                                                    'idD' => $item['id_dr'] ?? null
                                                                ]);
                                                                ?>" class="btn btn-sm btn-info"><i class="bi bi-eye"></i>Detail</a>
                                                    <!-- <a href="/lokasi/edit/<?= $item['id_dr'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a> -->
                                                    <?= $item['tahun'] ?>
                                                </div>
                                            <?php endforeach ?>
                                        <?php endif ?>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
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
                            // '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                        }).addTo(map);
                        // L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        //     attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                        // }).addTo(map);
                        // Tambahkan marker untuk setiap lokasi
                        lokasiData.forEach(lokasi => {
                            L.marker([lokasi.latitude, lokasi.longitude])
                                .addTo(map)
                                .bindPopup(`
            <b>${lokasi.kegiatan}</b><br>
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
                                iconUrl: '<?= base_url()  ?>/assets/img/marker-icon.png',
                                iconSize: [25, 41],
                                iconAnchor: [12, 41],
                                popupAnchor: [1, -34]
                            });

                            lokasiData.forEach(lokasi => {
                                // Buat konten popup
                                let popupContent = `<b>${lokasi.kegiatan}</b>`;

                                if (lokasi.gambar_popup) {
                                    popupContent += `<div class="popup-image-container">
                <img src="<?= base_url()  ?>/uploads/lokasi/popup/${lokasi.gambar_popup}" 
                     class="popup-image" 
                     alt="${lokasi.kegiatan}">
            </div>`;
                                } else if (lokasi.gambar) {
                                    popupContent += `<div class="popup-image-container">
                <img src="<?= base_url()  ?>/uploads/dokumentasi/${lokasi.gambar}" 
                     class="popup-image" width="250" height="200"
                     alt="${lokasi.kegiatan}">
            </div>`;
                                }

                                popupContent += `<p>${lokasi.deskripsi || ''}</p>
                        <small>Koordinat: ${lokasi.latitude}, ${lokasi.longitude}</small>`;

                                // Buat marker dengan custom icon jika ada gambar
                                const customIcon = lokasi.gambar ? L.icon({
                                    iconUrl: `<?= base_url()  ?>/uploads/dokumentasi/${lokasi.gambar}`,
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

                </div>
            </div>
        </div>
    </section>


    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p class="copyright">© <?= date('Y') ?>
                <a href="https://adbang.lampungprov.go.id/e-tapis" rel="nofollow noopener" target="_blank">SiTAPIS</a>
            </p>
        </div>
    </footer>

    <script src="<?php echo base_url('cssportal/templatemo-graph-script.js'); ?>"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>

</body>

</html>