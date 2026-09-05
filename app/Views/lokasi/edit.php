<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin="" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css">
    <style>
        #map-edit {
            height: 400px;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .map-controls {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1><?= esc($title) ?></h1>

        <a href="<?= base_url() ?>lokasi/detail/<?= $lokasi['id'] ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Detail
        </a>

        <?php if (session()->has('errors')): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>

        <?php if (session()->has('message')): ?>
            <div class="alert alert-success">
                <?= session('message') ?>
            </div>
        <?php endif ?>
        <?= form_open_multipart('/lokasi/update/' . $lokasi['id'], ['class=lokasi-form']); ?>
        <!-- <form action="/lokasi/update/< $lokasi['id'] ?>" method="post" class="lokasi-form"> -->
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="PUT">
        <div class="form-group">
            <label for="nama">Nama Lokasi*</label>
            <input type="text" name="nama" id="nama" value="<?= old('nama', esc($lokasi['nama'])) ?>" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="latitude">Latitude*</label>
                <input type="text" name="latitude" id="latitude" value="<?= old('latitude', esc($lokasi['latitude'])) ?>" required>
            </div>

            <div class="form-group">
                <label for="longitude">Longitude*</label>
                <input type="text" name="longitude" id="longitude" value="<?= old('longitude', esc($lokasi['longitude'])) ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label for="alamat">Alamat</label>
            <textarea name="alamat" id="alamat"><?= old('alamat', esc($lokasi['alamat'])) ?></textarea>
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi"><?= old('deskripsi', esc($lokasi['deskripsi'])) ?></textarea>
        </div>

        <div class="form-group">
            <label for="kategori">Kategori</label>
            <select name="kategori" id="kategori">
                <option value="">Pilih Kategori</option>
                <?php foreach ($kategoriList as $kategoriItem): ?>
                    <option value="<?= esc($kategoriItem['kategori']) ?>"
                        <?= old('kategori', $lokasi['kategori']) == $kategoriItem['kategori'] ? 'selected' : '' ?>>
                        <?= esc($kategoriItem['kategori'] ?: 'Tanpa Kategori') ?>
                    </option>
                <?php endforeach ?>
            </select>
            <small>Atau tambahkan kategori baru:</small>
            <input type="text" name="kategori_baru" id="kategori_baru" placeholder="Kategori baru"
                value="<?= !in_array(old('kategori', $lokasi['kategori']), array_column($kategoriList, 'kategori')) ? old('kategori', $lokasi['kategori']) : '' ?>">
        </div>
        <div class="form-group">
            <label for="gambar">Foto Lokasi</label>
            <input type="file" name="gambar" id="gambar" class="form-control">
            <small class="text-muted">Format: JPG/PNG, Maksimal 2MB</small>
        </div>
        <!-- Tambahkan preview gambar di edit.php -->
        <?php if (isset($lokasi['gambar'])): ?>
            <div class="form-group">
                <label>Foto Saat Ini</label>
                <img src="<?= base_url() ?>uploads/lokasi/<?= $lokasi['gambar'] ?>" class="img-preview" style="max-width: 300px; display: block;">
            </div>
        <?php endif; ?>
        <div class="map-container">
            <div id="map-edit" style="height: 500px; width: 100%;"></div>
            <div class="map-controls">
                <button type="button" id="lokasi-sekarang" class="btn btn-sm btn-info">
                    <i class="bi bi-geo-alt"></i> Gunakan Lokasi Saya
                </button>
                <button type="button" id="cari-koordinat" class="btn btn-sm btn-secondary">
                    <i class="bi bi-geo"></i> Cari dengan Koordinat
                </button>
                <button type="button" id="cari-alamat" class="btn btn-sm btn-secondary">
                    <i class="bi bi-search"></i> Cari dengan Alamat
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Perubahan</button>
        <?= form_close(); ?>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Pastikan elemen peta ada
            const mapElement = document.getElementById('map-edit');
            if (!mapElement) {
                console.error('Elemen peta tidak ditemukan');
                return;
            }

            // Default koordinat jika data tidak ada
            const defaultLat = <?= $lokasi['latitude'] ?? -6.175392 ?>;
            const defaultLng = <?= $lokasi['longitude'] ?? 106.827153 ?>;
            const defaultZoom = 15;

            // // Inisialisasi peta
            // const map = L.map('map-edit').setView(
            //     [<?php //esc($lokasi['latitude']) ;
                    ?>, <?php // esc($lokasi['longitude']) 
                        ?>],
            //     15
            // );
            // Inisialisasi peta
            const map = L.map('map-edit').setView([defaultLat, defaultLng], defaultZoom);
            // Tambahkan layer OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'SiTAPIS : Sistem Data Pengendalian dan Informasi'
            }).addTo(map);

            // Tambahkan marker
            const marker = L.marker([defaultLat, defaultLng], {
                    draggable: true
                }).addTo(map)
                .bindPopup('Lokasi saat ini')
                .openPopup();


            // Update form saat marker di-drag
            marker.on('dragend', function(e) {
                const newPos = marker.getLatLng();
                document.getElementById('latitude').value = newPos.lat;
                document.getElementById('longitude').value = newPos.lng;
            });

            // Fungsi untuk update posisi marker
            window.updateMarkerPosition = function(lat, lng) {
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng], defaultZoom);
            };

            // Tombol "Gunakan Lokasi Saya"
            document.getElementById('lokasi-sekarang').addEventListener('click', function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        // Update marker dan input
                        marker.setLatLng([lat, lng]);
                        document.getElementById('latitude').value = lat;
                        document.getElementById('longitude').value = lng;
                        map.setView([lat, lng], 15);

                        marker.setPopupContent(`
                            Lokasi terpilih:<br>
                            Lat: ${lat.toFixed(6)}<br>
                            Lng: ${lng.toFixed(6)}
                        `).openPopup();
                    }, function(error) {
                        alert('Gagal mendapatkan lokasi: ' + error.message);
                    });
                } else {
                    alert('Browser tidak mendukung geolokasi');
                }
            });

            // Tombol "Cari dengan Koordinat"
            document.getElementById('cari-koordinat').addEventListener('click', function() {
                const lat = parseFloat(prompt('Masukkan latitude:', document.getElementById('latitude').value));
                const lng = parseFloat(prompt('Masukkan longitude:', document.getElementById('longitude').value));

                if (!isNaN(lat) && !isNaN(lng)) {
                    // Update marker dan input
                    marker.setLatLng([lat, lng]);
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;
                    map.setView([lat, lng], 15);

                    marker.setPopupContent(`
                        Lokasi terpilih:<br>
                        Lat: ${lat.toFixed(6)}<br>
                        Lng: ${lng.toFixed(6)}
                    `).openPopup();
                } else {
                    alert('Koordinat tidak valid');
                }
            });

            // Tombol "Cari dengan Alamat" (Geocoding menggunakan Nominatim)
            document.getElementById('cari-alamat').addEventListener('click', function() {
                const alamat = prompt('Masukkan alamat:', document.getElementById('alamat').value);

                if (alamat) {
                    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(alamat)}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.length > 0) {
                                const firstResult = data[0];
                                const lat = parseFloat(firstResult.lat);
                                const lng = parseFloat(firstResult.lon);

                                // Update marker dan input
                                marker.setLatLng([lat, lng]);
                                document.getElementById('latitude').value = lat;
                                document.getElementById('longitude').value = lng;
                                document.getElementById('alamat').value = firstResult.display_name;
                                map.setView([lat, lng], 15);

                                marker.setPopupContent(`
                                    ${firstResult.display_name}<br>
                                    Lat: ${lat.toFixed(6)}<br>
                                    Lng: ${lng.toFixed(6)}
                                `).openPopup();
                            } else {
                                alert('Alamat tidak ditemukan');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat mencari alamat');
                        });
                }
            });

            // Handle kategori baru
            const kategoriSelect = document.getElementById('kategori');
            const kategoriBaruInput = document.getElementById('kategori_baru');

            kategoriBaruInput.addEventListener('input', function() {
                if (this.value) {
                    kategoriSelect.value = '';
                }
            });

            kategoriSelect.addEventListener('change', function() {
                if (this.value) {
                    kategoriBaruInput.value = '';
                }
            });
        });
    </script>
</body>

</html>