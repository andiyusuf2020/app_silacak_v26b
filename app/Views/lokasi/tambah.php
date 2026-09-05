<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1><?= $title ?></h1>

        <a href="<?= base_url() ?>lokasi" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>

        <?php if (session()->has('errors')): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>

        <!-- <form action="/lokasi/simpan" method="post" class="lokasi-form"> -->
        <?= form_open_multipart('/lokasi/simpan', ['class=lokasi-form']); ?>
        <?= csrf_field(); ?>

        <?= csrf_field() ?>
        <div class="form-group">
            <label for="nama">Nama Lokasi*</label>
            <input type="text" name="nama" id="nama" value="<?= old('nama') ?>" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="latitude">Latitude*</label>
                <input type="text" name="latitude" id="latitude" value="<?= old('latitude') ?>" required>
            </div>

            <div class="form-group">
                <label for="longitude">Longitude*</label>
                <input type="text" name="longitude" id="longitude" value="<?= old('longitude') ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label for="alamat">Alamat</label>
            <textarea name="alamat" id="alamat"><?= old('alamat') ?></textarea>
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi"><?= old('deskripsi') ?></textarea>
        </div>

        <div class="form-group">
            <label for="kategori">Kategori</label>
            <select name="kategori" id="kategori">
                <option value="">Pilih Kategori</option>
                <?php foreach ($kategoriList as $kategoriItem): ?>
                    <option value="<?= $kategoriItem['kategori'] ?>" <?= old('kategori') == $kategoriItem['kategori'] ? 'selected' : '' ?>>
                        <?= $kategoriItem['kategori'] ?: 'Tanpa Kategori' ?>
                    </option>
                <?php endforeach ?>
            </select>
            <small>Atau tambahkan kategori baru:</small>
            <input type="text" name="kategori_baru" id="kategori_baru" placeholder="Kategori baru">
        </div>
        <div class="form-group">
            <label for="gambar">Foto Lokasi</label>
            <input type="file" name="gambar" id="gambar" class="form-control">
            <small class="text-muted">Format: JPG/PNG, Maksimal 2MB</small>
        </div>
        <div class="map-container">
            <div id="map"></div>
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
        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
        <?= form_close(); ?>
    </div>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="<?= base_url() ?>assets/js/lokasi.js"></script>
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
</body>

</html>