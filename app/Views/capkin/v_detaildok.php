<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css">
    <style>
        #map-detail {
            height: 400px;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .detail-container {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .detail-row {
            display: flex;
            margin-bottom: 10px;
        }

        .detail-label {
            font-weight: bold;
            min-width: 120px;
        }

        .detail-value {
            flex: 1;
        }

        .action-buttons {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1><?= esc($title) ?></h1>

        <button onclick="history.back()" class="btn btn-secondary"><i class="bi bi-arrow-left"></i>Kembali ke Dashboard</button>
        <div id="map-detail" style="width: 100%;"></div>

        <div class="detail-container">
            <table class="table">
                <tbody>
                    <tr class="align-middle">
                        <td style="width: 200px;"><strong>Perangkat Daerah</strong></td>
                        <td>:</td>
                        <td><?= esc($lokasi['nm_sub_skpd']) ?></td>
                    </tr>
                    <tr class="align-middle">
                        <td style="width: 200px;"><strong>Program</strong></td>
                        <td>:</td>
                        <td><?= esc($lokasi['nm_program']) ?></td>
                    </tr>
                    <tr class="align-middle">
                        <td style="width: 200px;"><strong>Kegiatan</strong></td>
                        <td>:</td>
                        <td><?= esc($lokasi['nm_kegiatan']) ?></td>
                    </tr>
                    <tr class="align-middle">
                        <td style="width: 200px;"><strong>Sub Kegiatan</strong></td>
                        <td>:</td>
                        <td><?= esc($lokasi['nm_sub_giat']) ?></td>
                    </tr>
                    <tr class="align-middle">
                        <td style="width: 200px;"><strong>Aktifitas Kegiatan</strong></td>
                        <td>:</td>
                        <td><?= esc($lokasi['kegiatan']) ?></td>
                    </tr>
                    <tr class="align-middle">
                        <td style="width: 200px;"><strong>Kategori Aktifitas</strong></td>
                        <td>:</td>
                        <td><?= esc($lokasi['kategori'] ?: 'Tanpa kategori') ?></td>
                    </tr>
                    <tr class="align-middle">
                        <td style="width: 200px;"><strong>Lokasi Aktifitas</strong></td>
                        <td>:</td>
                        <td><?= esc($lokasi['kabupaten'] ?: '-') ?>,
                            <?= esc($lokasi['kecamatan'] ?: '-') ?>,
                            <?= esc($lokasi['desa'] ?: '-') ?></td>
                    </tr>
                    <tr class="align-middle">
                        <td style="width: 200px;"><strong>Koordinat Lokasi </strong></td>
                        <td>:</td>
                        <td><i class="bi bi-geo-alt-fill"></i>Latitude: <?= esc($lokasi['latitude']) ?>,
                            Longitude: <?= esc($lokasi['longitude']) ?></td>
                    </tr>
                    <tr class="align-middle">
                        <td style="width: 200px;"><strong>Data diinput pada </strong></td>
                        <td>:</td>
                        <td><?= date('d-m-Y H:i', strtotime($lokasi['create_at'])) ?></td>
                    </tr>
                    <tr class="align-middle">
                        <td style="width: 200px;"><strong>Data diubah pada </strong></td>
                        <td>:</td>
                        <td><?= date('d-m-Y H:i', strtotime($lokasi['update_at'])) ?></td>
                    </tr>
                    <tr>
                        <td style="width: 200px;"><strong>Deskripsi </strong></td>
                        <td>:</td>
                        <td><?= nl2br(esc($lokasi['deskripsi'] ?: 'Tidak ada deskripsi')) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="detail-container">
            <div class="detail-row">
                <div class="detail-label">Dokumentasi Aktifitas:</div>
                <div class="detail-value">
                    <?php if ($lokasi['gambar']): ?>
                        <img src="<?= base_url() ?>uploads/dokumentasi/<?= esc($lokasi['gambar']) ?>"
                            class="img-thumbnail"
                            style="max-width: 400px; max-height: 300px;">
                    <?php else: ?>
                        <span class="text-muted">Tidak ada foto</span>
                    <?php endif; ?>
                </div>
                <!-- <div class="detail-value" id="map-detail"></div> -->
            </div>

            <div class="detail-row">
                <div class="detail-label">Aktifitas Kegiatan ini dilaksanakan untuk mendukung Misi Gubernur dan Wakil Gubernur Lampung
                    <?= esc($lokasi['misi']) ?>, dengan Sasaran Prioritas: <?= esc($lokasi['nm_progprioritas']) ?>
                </div>
                <div class="detail-value"></div>
            </div>
        </div>


    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Inisialisasi peta
        const map = L.map('map-detail').setView(
            [<?= esc($lokasi['latitude']) ?>, <?= esc($lokasi['longitude']) ?>],
            15
        );

        // Tambahkan layer OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'SiTAPIS : Sistem Data Pengendalian dan Informasi'
        }).addTo(map);


        // Tambahkan marker untuk lokasi ini
        L.marker([<?= esc($lokasi['latitude']) ?>, <?= esc($lokasi['longitude']) ?>])
            .addTo(map)
            .bindPopup(`
                <b><?= esc($lokasi['kegiatan']) ?></b><br>
                <b><?= esc('Alamat') ?></b><br>
                <?= esc($lokasi['kabupaten'] ?: 'Tidak ada alamat') ?><br>
                <?= esc($lokasi['kecamatan'] ?: 'Tidak ada alamat') ?><br>
                <?= esc($lokasi['desa'] ?: 'Tidak ada alamat') ?>

            `)
            .openPopup();

        // Tambahkan circle untuk menunjukkan akurasi (jika diperlukan)
        L.circle([<?= esc($lokasi['latitude']) ?>, <?= esc($lokasi['longitude']) ?>], {
            color: 'blue',
            fillColor: '#1e90ff',
            fillOpacity: 0.2,
            radius: 50
        }).addTo(map);
    </script>
</body>

</html>