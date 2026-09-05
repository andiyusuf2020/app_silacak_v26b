<?= $this->extend('template/layout') ?>
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

    <!-- <form action="/lokasi/simpan" method="post" class="lokasi-form"> -->
    <?= form_open_multipart('/capkin/simpandokumentasi', ['class=lokasi-form']); ?>
    <?= csrf_field(); ?>
    <div class="form-group">
        <label>Realisasi Aktifitas/Kegiatan Pokok SubKegiatan:</label>
        <code>
            <label><strong><?= esc($datarkp['nm_subkegiatan']) ?></strong></label>
        </code>
        <label><strong> Kegiatan Pokok/Aktifitas : </strong></label>
        <code>
            <label>
                <?= esc($datarkp['r_uraian']) ?> dengan sasaran
                <?= esc($datarkp['r_target']) . '  ' . esc($datarkp['sat_target']) ?> </strong>
                <?= form_hidden('idRKP', esc($datarkp['id_r'])); ?>

            </label>
        </code>
    </div>
    <div class="form-group">
        <label>Nama Aktifitas/Kegiatan* </label>
        <input type="text" name="kegiatan" id="kegiatan" value="<?= old('kegiatan') ?>" required>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Lokasi Aktifitas/Kegiatan</label>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Kabupaten/Kota*</label>
            <input class="form-control" list="datalistkab" name="kabupaten" id="kabupaten" value="<?= old('kabupaten') ?>" required
                placeholder="Type to search...">
            <datalist id="datalistkab">
                <?php foreach ($kab as $key => $namakab) { ?>
                    <option value="<?= esc($namakab['nama']) ?>">
                    <?php }  ?>
            </datalist>
        </div>

        <div class="form-group">
            <label for="exampleDataList" class="form-label">Kecamatan*</label>
            <input class="form-control" list="datalistkec" name="kecamatan" id="kecamatan" value="<?= old('kecamatan') ?>" required
                placeholder="Type to search...">
            <datalist id="datalistkec">
                <?php foreach ($kec as $key => $namakec) { ?>
                    <option value="<?= esc($namakec['nama_kecamatan']) ?>">
                    <?php }  ?>
            </datalist>
        </div>
        <div class="form-group">
            <label for="exampleDataList" class="form-label">Desa/Kelurahan*</label>
            <input class="form-control" list="datalistdesa" name="desa" id="desa" value="<?= old('desa') ?>" required
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
            <input type="text" name="latitude" id="latitude" value="<?= old('latitude') ?>" required>
        </div>

        <div class="form-group">
            <label for="longtitude">longitude*</label>
            <input type="text" name="longitude" id="longitude" value="<?= old('longitude') ?>" required>
        </div>
    </div>

    <!-- <div class="form-group">
        <label for="alamat">Alamat</label>
        <textarea name="alamat" id="alamat"><?php // old('alamat') 
                                            ?></textarea>
    </div> -->

    <div class="form-group">
        <label for="deskripsi">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" required><?= old('deskripsi') ?></textarea>
    </div>

    <div class="form-group">
        <label for="kategori">Kategori</label>
        <select name="kategori" id="kategori" required>
            <option value="">Pilih Kategori</option>
            <?php foreach ($kategoriList as $kategoriItem): ?>
                <option value="<?= $kategoriItem['nm_kategori'] ?>" <?= old('nm_kategori') == $kategoriItem['nm_kategori'] ? 'selected' : '' ?>>
                    <?= $kategoriItem['nm_kategori'] ?: 'Tanpa Kategori' ?>
                </option>
            <?php endforeach ?>
        </select>
        <!-- <small>Atau tambahkan kategori baru:</small>
        <input type="text" name="kategori_baru" id="kategori_baru" placeholder="Kategori baru"> -->
    </div>
    <div class="form-group">
        <label for="gambar">Foto Aktifitas/Kegiatan</label>
        <input type="file" name="gambar" id="gambar" class="form-control" required>
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

    <div class="d-flex justify-content-between mt-4">
        <?php if (!(session()->getFlashdata('message'))): ?>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Simpan
            </button>
        <?php endif; ?>
        <?php if ((session()->getFlashdata('message'))): ?>
        <?php endif; ?>
    </div>
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

<?= $this->endSection() ?>