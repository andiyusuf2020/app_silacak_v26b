<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<!--begin::Container-->
<div class="container">
    <h1><?= $title ?></h1>

    <div class="action-buttons">
        <a href="<?= base_url() ?>lokasi/tambah" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Lokasi</a>

        <form action="<?= base_url() ?>lokasi" method="get" class="search-form">
            <input type="text" name="keyword" placeholder="Cari lokasi..." value="<?= $keyword ?? '' ?>">
            <button type="submit"><i class="bi bi-search"></i></button>
        </form>

        <div class="filter-kategori">
            <form action="<?= base_url() ?>lokasi" method="get">
                <select name="kategori" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($kategoriList as $kategoriItem): ?>
                        <option value="<?= $kategoriItem['kategori'] ?>" <?= isset($kategori) && $kategori == $kategoriItem['kategori'] ? 'selected' : '' ?>>
                            <?= $kategoriItem['kategori'] ?: 'Tanpa Kategori' ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </form>
        </div>
    </div>

    <?php if (session()->has('message')): ?>
        <div class="alert alert-success">
            <?= session('message') ?>
        </div>
    <?php endif ?>

    <div class="map-container">
        <div id="map"></div>
        <div class="map-sidebar">
            <h3>Daftar Lokasi</h3>
            <div class="lokasi-list">
                <?php if (empty($lokasi)): ?>
                    <p>Tidak ada lokasi ditemukan</p>
                <?php else: ?>
                    <?php foreach ($lokasi as $item): ?>
                        <?php if ($item['gambar']): ?>
                            <img src="<?= base_url() ?>uploads/lokasi/<?= esc($item['gambar']) ?>"
                                class="lokasi-thumbnail"
                                style="width: 100%; height: 150px; object-fit: cover; border-radius: 4px;">
                        <?php else: ?>
                            <div class="no-image-placeholder" style="height: 150px; background: #eee; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
                                <i class="bi bi-image" style="font-size: 2rem; color: #999;"></i>
                            </div>
                        <?php endif; ?>
                        <div class="lokasi-item" data-lat="<?= $item['latitude'] ?>" data-lng="<?= $item['longitude'] ?>">
                            <h4><?= $item['nama'] ?></h4>
                            <p><small><?= $item['kategori'] ?></small></p>
                            <p><?= substr($item['deskripsi'], 0, 50) ?>...</p>
                            <div class="action-links">
                                <a href="<?= base_url() ?>lokasi/detail/<?= $item['id'] ?>" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                                <a href="<?= base_url() ?>lokasi/edit/<?= $item['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                <form action="<?= base_url() ?>lokasi/hapus/<?= $item['id'] ?>" method="post" class="d-inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin?')"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach ?>
                <?php endif ?>
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
        //  '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Tambahkan marker untuk setiap lokasi
    lokasiData.forEach(lokasi => {
        L.marker([lokasi.latitude, lokasi.longitude])
            .addTo(map)
            .bindPopup(`
            <b>${lokasi.nama}</b><br>
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
            iconUrl: '<?= base_url() ?>assets/img/marker-icon.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34]
        });

        lokasiData.forEach(lokasi => {
            // Buat konten popup
            let popupContent = `<b>${lokasi.nama}</b>`;

            if (lokasi.gambar_popup) {
                popupContent += `<div class="popup-image-container">
                <img src="<?= base_url() ?>uploads/lokasi/popup/${lokasi.gambar_popup}" 
                     class="popup-image" 
                     alt="${lokasi.nama}">
            </div>`;
            } else if (lokasi.gambar) {
                popupContent += `<div class="popup-image-container">
                <img src="<?= base_url() ?>uploads/lokasi/${lokasi.gambar}" 
                     class="popup-image" width="250" height="200"
                     alt="${lokasi.nama}">
            </div>`;
            }

            popupContent += `<p>${lokasi.deskripsi || ''}</p>
                        <small>Koordinat: ${lokasi.latitude}, ${lokasi.longitude}</small>`;

            // Buat marker dengan custom icon jika ada gambar
            const customIcon = lokasi.gambar ? L.icon({
                iconUrl: `<?= base_url() ?>uploads/lokasi/${lokasi.gambar}`,
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
<?= $this->endSection() ?>