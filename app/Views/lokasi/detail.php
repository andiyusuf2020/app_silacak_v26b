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

        <a href="<?= base_url() ?>lokasi" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali ke Daftar</a>

        <?php if (session()->has('message')): ?>
            <div class="alert alert-success">
                <?= session('message') ?>
            </div>
        <?php endif ?>

        <div class="detail-container">
            <div class="detail-row">
                <div class="detail-label">Nama Lokasi:</div>
                <div class="detail-value"><?= esc($lokasi['nama']) ?></div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Kategori:</div>
                <div class="detail-value"><?= esc($lokasi['kategori'] ?: 'Tanpa kategori') ?></div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Alamat:</div>
                <div class="detail-value"><?= esc($lokasi['alamat'] ?: '-') ?></div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Koordinat:</div>
                <div class="detail-value">
                    Latitude: <?= esc($lokasi['latitude']) ?>,
                    Longitude: <?= esc($lokasi['longitude']) ?>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Dibuat pada:</div>
                <div class="detail-value"><?= date('d-m-Y H:i', strtotime($lokasi['created_at'])) ?></div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Diperbarui pada:</div>
                <div class="detail-value"><?= date('d-m-Y H:i', strtotime($lokasi['updated_at'])) ?></div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Deskripsi:</div>
                <div class="detail-value"><?= nl2br(esc($lokasi['deskripsi'] ?: 'Tidak ada deskripsi')) ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Foto:</div>
                <div class="detail-value">
                    <?php if ($lokasi['gambar']): ?>
                        <img src="<?= base_url() ?>uploads/lokasi/<?= esc($lokasi['gambar']) ?>"
                            class="img-thumbnail"
                            style="max-width: 400px; max-height: 300px;">
                    <?php else: ?>
                        <span class="text-muted">Tidak ada foto</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div id="map-detail"></div>

        <div class="action-buttons">
            <a href="<?= base_url() ?>lokasi/edit/<?= $lokasi['id'] ?>" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit Lokasi
            </a>

            <form action="<?= base_url() ?>lokasi/hapus/<?= $lokasi['id'] ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus lokasi ini?')">
                    <i class="bi bi-trash"></i> Hapus Lokasi
                </button>
            </form>
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
                <b><?= esc($lokasi['nama']) ?></b><br>
                <?= esc($lokasi['alamat'] ?: 'Tidak ada alamat') ?>
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