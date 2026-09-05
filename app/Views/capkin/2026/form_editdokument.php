<?= $this->extend('template/layout_v3') ?>
<?= $this->section('content') ?>
<!--begin::Container-->
<div class="container">
    <button onclick="history.back()" class="btn btn-secondary"><i class="bi bi-arrow-left"></i>Kembali</button>
    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-danger" role="alert">
            <ul>
                <p><?= session()->getFlashdata('message') ?></p>
                <button onclick="history.back()" class="btn btn-secondary">Kembali</button>

            </ul>
        </div>
    <?php endif; ?>
    <?php if (session()->has('errors')): ?>
        <div class="alert alert-danger" role="alert">
            <ul>
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <?php if (!session()->getFlashdata('message')): ?>
        <!-- <form action="/lokasi/simpan" method="post" class="lokasi-form"> -->
        <?= form_open_multipart('/capkin2026/simpandokumentasi', ['class=lokasi-form']); ?>
        <?= csrf_field(); ?>
        <div class="form-group">
            <label>Realisasi Aktifitas/Kegiatan Pokok SubKegiatan:</label>
            <code>
                <label><strong><?= esc($dataDR['nm_sub_giat']) ?></strong></label>
            </code>
            <label><strong> Kegiatan Pokok/Aktifitas : </strong></label>
            <code>
                <label>
                    <?= esc($dataDR['r_uraian']) ?> dengan sasaran
                    <?= esc($dataDR['r_target']) . '  ' . esc($dataDR['sat_target']) ?> </strong>
                    <?= form_hidden('id_dr', esc($dataDR['id_dr'])); ?>
                </label>
            </code>
        </div>
        <div class="form-group">
            <label>Aktifitas/Kegiatan ini mendukung Sasaran Prioritas RPJMD ::* </label>
            <code>
                <label><strong><?= esc($dataDR['nm_progprioritas']) ?></strong></label>
                <?= form_hidden('kegiatan', esc($dataDR['nm_progprioritas'])); ?>

            </code>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Lokasi Aktifitas/Kegiatan</label>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Kabupaten/Kota*</label>
                <input class="form-control" list="datalistkab" name="kabupaten" id="kabupaten" value="<?= old('kabupaten', esc($dataDR['kabupaten'])) ?>" required
                    placeholder="Type to search...">
                <datalist id="datalistkab">
                    <?php foreach ($kab as $key => $namakab) { ?>
                        <option value="<?= esc($namakab['nama']) ?>">
                        <?php }  ?>
                </datalist>
            </div>

            <div class="form-group">
                <label for="exampleDataList" class="form-label">Kecamatan*</label>
                <input class="form-control" list="datalistkec" name="kecamatan" id="kecamatan" value="<?= old('kecamatan', esc($dataDR['kecamatan'])) ?>" required
                    placeholder="Type to search...">
                <datalist id="datalistkec">
                    <?php foreach ($kec as $key => $namakec) { ?>
                        <option value="<?= esc($namakec['nama_kecamatan']) ?>">
                        <?php }  ?>
                </datalist>
            </div>
            <div class="form-group">
                <label for="exampleDataList" class="form-label">Desa/Kelurahan*</label>
                <input class="form-control" list="datalistdesa" name="desa" id="desa" value="<?= old('desa', esc($dataDR['desa'])) ?>" required
                    placeholder="Type to search...">
                <datalist id="datalistdesa">
                    <?php foreach ($desa as $key => $namadesa) { ?>
                        <option value="<?= esc($namadesa['nm_desa']) ?>">
                        <?php }  ?>
                </datalist>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Koordinat lokasi Aktifitas/Kegiatan*</label>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="latitude">Latitude*</label>
                <input type="text" name="latitude" id="latitude" value="<?= old('latitude', esc($dataDR['latitude'])) ?>" required>
            </div>

            <div class="form-group">
                <label for="longtitude">longitude*</label>
                <input type="text" name="longitude" id="longitude" value="<?= old('longitude', esc($dataDR['longitude'])) ?>" required>
            </div>
        </div>

        <!-- <div class="form-group">
        <label for="alamat">Alamat</label>
        <textarea name="alamat" id="alamat"><?php // old('alamat') 
                                            ?></textarea>
    </div> -->

        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" required><?= old('deskripsi', esc($dataDR['deskripsi'])) ?></textarea>
        </div>

        <div class="form-group">
            <label for="kategori">Kategori</label>
            <select name="kategori" id="kategori" required>
                <option value="">Pilih Kategori</option>
                <?php foreach ($kategoriList as $kategoriItem): ?>
                    <option value="<?= esc($kategoriItem['nm_kategori']) ?>"
                        <?= old('kategori', $dataDR['kategori']) == $kategoriItem['nm_kategori'] ? 'selected' : '' ?>>
                        <?= esc($kategoriItem['nm_kategori'] ?: 'Tanpa Kategori') ?>
                    </option>

                <?php endforeach ?>
            </select>
            <!-- <small>Atau tambahkan kategori baru:</small>
        <input type="text" name="kategori_baru" id="kategori_baru" placeholder="Kategori baru"> -->
        </div>
        <div class="form-group">
            <label for="gambar">Foto Aktifitas/Kegiatan</label>
            <input type="file" name="gambar" id="gambar" class="form-control">
            <small class="text-muted">Format: JPG/PNG, Maksimal 2MB</small>
        </div>
        <?php if (isset($dataDR['gambar'])): ?>
            <div class="form-group">
                <label>Foto Saat Ini</label>
                <img src="<?= base_url() ?>uploads/dokumentasi/<?= $dataDR['gambar'] ?>" class="img-preview" style="max-width: 300px; display: block;">
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
        <div class="d-flex justify-content-between mt-4">
            <?php if (!(session()->getFlashdata('message'))): ?>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan Perubahan
                </button>
            <?php endif; ?>
            <?php if ((session()->getFlashdata('message'))): ?>
            <?php endif; ?>
        </div>
        <?= form_close(); ?>
    <?php endif; ?>

</div>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- <script src="<?= base_url() ?>assets/js/lokasi.js"></script> -->
<script>
    // Preview gambar sebelum upload
    document.getElementById('gambar').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                let preview = document.querySelector('.img-preview');
                if (!preview) {
                    preview = document.createElement('img');
                    preview.className = 'img-preview';
                    preview.style.maxWidth = '300px';
                    preview.style.display = 'block';
                    preview.style.marginBottom = '10px';
                    e.target.parentNode.insertBefore(preview, e.target.nextSibling);
                }
                preview.src = event.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Pastikan elemen peta ada
        const mapElement = document.getElementById('map-edit');
        if (!mapElement) {
            console.error('Elemen peta tidak ditemukan');
            return;
        }

        // Default koordinat jika data tidak ada
        const defaultLat = <?= $dataDR['latitude'] ?? -6.175392 ?>;
        const defaultLng = <?= $dataDR['longitude'] ?? 106.827153 ?>;
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

        // // Handle kategori baru
        // const kategoriSelect = document.getElementById('kategori');
        // const kategoriBaruInput = document.getElementById('kategori_baru');

        // kategoriBaruInput.addEventListener('input', function() {
        //     if (this.value) {
        //         kategoriSelect.value = '';
        //     }
        // });

        // kategoriSelect.addEventListener('change', function() {
        //     if (this.value) {
        //         kategoriBaruInput.value = '';
        //     }
        // });
    });
</script>
<?= $this->endSection() ?>