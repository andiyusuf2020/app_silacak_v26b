<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- SELECT2 EXAMPLE -->

    <div class="card card-default">
        <div class="card card-primary card-outline mb-4">
            <!--begin::Header-->
            <div class="card-header">
                <div class="card-title">Form Input Rencana Aktifitas/Kegiatan Pokok Sub Kegiatan</div>
            </div>
            <div class="card-header">
                <?php if (session()->getFlashdata('peringatan')): ?>
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            <p><?= session()->getFlashdata('peringatan') ?></p>
                        </ul>
                    </div>
                <?php endif; ?>
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
            </div>
            <!--end::Header-->

            <?php if (!session()->getFlashdata('message')): ?>
                <!--begin::Form-->
                <?= form_open_multipart('capkin2026/simpanrencanaaktifitas', 'id="myForm"'); ?>
                <?= csrf_field(); ?>
                <!--begin::Body-->
                <div class="card-body">
                    <div id="input-container">
                        <div class="mb-3">
                            <label for="subkegiatan" class="form-label">SUBKEGIATAN</label>
                            <h2><?= esc($subkegcapkindipilih['nm_sub_giat'])  ?></h2>
                        </div>
                        <br>
                        <div class="mb-3">
                            <label for="kegpokok" class="form-label">KEGIATAN POKOK</label>
                            <button type="button" class="btn btn-secondary" id="tambah-input">
                                <i class="bi bi-plus-circle"></i> Tambah Data
                            </button>
                        </div>
                        <br>
                        <div class="col-md-3">
                            <label for="hasil" class="form-label">Mendukung Sasaran RPJMD/Program Prioritas (pilih):</label>
                            <?= form_dropdown('data[0][id_progprioritas]', $dataprogprioritas, $inputs[0]['id_pprio'] ?? null, ['class' => 'form-select', 'required']); ?>
                        </div><br>
                        <div class="col-md-3">
                            <label for="hasil" class="form-label">Kelompok Aktifitas (pilih):</label>
                            <select name="data[0][kelompok]" id="kelompok" required class="form-select form-select-sm" aria-label=".form-select-lg example">
                                <option value="">-- Pilih Kelompok --</option>
                                <option value="Aktifitas Utama">Aktifitas Utama</option>
                                <!-- <option value="Aktifitas Pendukung">Aktifitas Pendukung</option> -->
                            </select>
                        </div>
                        <br>
                        <div class="input-group row g-3">
                            <!--begin::Col-->
                            <div class="col-md-5">
                                <label for="uraian" class="form-label">Uraian Target Pelaksanaan Aktivitas Tahun <?= esc($tahunaktif) ?></label>
                                <textarea id="uraian" name="data[0][uraian_target]" class="form-control" required
                                    oninput="this.value = this.value.replace(/[^a-zA-Z0-9,.%()?/\s]/g, '')"
                                    placeholder="uraian/penjelasan mengenai sasaran" rows="5"></textarea>
                            </div>
                            <div class="col-md-2">
                                <label for="target" class="form-label">Jumlah Target Aktifitas</label>
                                <input type="number" name="data[0][vol_target]" class="form-control"
                                    oninput="this.value = this.value.replace(/[^0-9\s]/g, '')"
                                    placeholder="jumlah sasaran/target" required>
                                <select name="data[0][sat_target]" id="satuan" required class="form-select form-select-sm" aria-label=".form-select-lg example">
                                    <option value="">-- Pilih Satuan --</option>
                                    <option value="kegiatan">Kegiatan</option>
                                    <option value="laporan">Laporan</option>
                                    <option value="orang">Orang</option>
                                    <option value="kali">Kali</option>
                                    <option value="bulan">Bulan</option>
                                    <option value="paket">Paket</option>
                                    <option value="unit">Unit</option>
                                    <option value="km">Km</option>
                                    <option value="Kg">Kg</option>
                                    <option value="Buah">Buah</option>
                                </select>
                                <input type="hidden" name="data[0][id_targetsubkeg]" value="<?= esc($id_subgiatmapping) ?>">
                                <input type="hidden" name="data[0][tahun]" value="<?= esc($subkegcapkindipilih['tahun'])  ?>">
                                <input type="hidden" name="data[0][kd_subunit]" value="<?= esc($subkegcapkindipilih['kd_sub_skpd'])  ?>">
                                <input type="hidden" name="data[0][kd_subkegiatan]" value="<?= esc($subkegcapkindipilih['kd_sub_giat'])  ?>">
                                <input type="hidden" name="data[0][pagu]" value="<?= esc($subkegcapkindipilih['total_anggaran']) ?>">
                                <input type="hidden" name="data[0][hasil]" value="kosong">
                            </div>
                            <!--end::Col-->
                            <div class="col-md-3">
                                <label for="target" class="form-label">Rencana Lokasi Aktifitas : </label>
                                <br>
                                <select name="data[0][lokasi]" id="lokasi" required class="form-select form-select-sm" aria-label=".form-select-lg example">
                                    <option value="">-- Pilih Kab/Kota --</option>
                                    <option value="tersebar">Tersebar Sesuai Realisasi</option>
                                    <option value="Kota Bandar Lampung">Kota Bandar Lampung</option>
                                    <option value="Kota Metro">Kota Metro</option>
                                    <option value="Kabupaten Lampung Barat">Kabupaten Lampung Barat</option>
                                    <option value="Kabupaten Tanggamus">Kabupaten Tanggamus</option>
                                    <option value="Kabupaten Lampung Selatan">Kabupaten Lampung Selatan</option>
                                    <option value="Kabupaten Lampung Timur">Kabupaten Lampung Timur</option>
                                    <option value="Kabupaten Lampung Utara">Kabupaten Lampung Utara</option>
                                    <option value="Kabupaten Lampung Tengah">Kabupaten Lampung Tengah</option>
                                    <option value="Kabupaten Way Kanan">Kabupaten Way Kanan</option>
                                    <option value="Kabupaten Tulang Bawang">Kabupaten Tulang Bawang</option>
                                    <option value="Kabupaten Pesawaran">Kabupaten Pesawaran</option>
                                    <option value="Kabupaten Pringsewu">Kabupaten Pringsewu</option>
                                    <option value="Kabupaten Mesuji">Kabupaten Mesuji </option>
                                    <option value="Kabupaten Tulang Bawang Barat">Kabupaten Tulang Bawang Barat</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger remove-input" disabled>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <!--end::Body-->
                    <!--begin::Footer-->
                    <div class="d-flex justify-content-between mt-4">
                        <?php if (!(session()->getFlashdata('message'))): ?>
                            <button type="submit" class="btn btn-primary" value="Kirim">
                                <i class="bi bi-save"></i> Simpan Semua
                            </button>
                        <?php endif; ?>
                        <?php if ((session()->getFlashdata('message'))): ?>
                        <?php endif; ?>
                    </div>
                </div>
                <!--end::Footer-->
                <?= form_close(); ?>
                <!--end::Form-->
            <?php endif; ?>

        </div>
        <!--end::Quick Example-->
    </div>
</div>
<!-- Modal Structure -->
<div class="modal fade" id="welcomeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered custom-modal">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Perhatian Petunjuk Pengisian Data</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-left">
                    <div class="alert alert-info mt-3">
                        <small>
                            1. Identifikasi Aktifitas-aktifitas Utama kegiatan dalam subkegiatan apa saja yang mendukung Sasaran RPJMD 2026-2029 Provinsi Lampung sesuai Sasaran RPMJD yang di ampu oleh Perangkat Daerah saudara.<br>
                            Aktifitas Utama<br>
                            Merupakan aktifitas yang dilaksanakan yang menjadi output dari subkegiatan atau output perangkat daerah yang mendukung sasaran RPJMD<br>
                            <!-- b. Aktifitas Pendukung<br>
                            Merupakan aktifitas yang dilaksanakan untuk mendukung aktifitas utama subkegiatan atau output perangkat daerah<br> -->
                            2. Tentukan rencana target aktifitas, rencana lokasi aktifitas, dan uraian aktifitas yang akan dilaksanakan dalam 1 tahun anggaran<br>
                            <!-- 4. Tentukan Indikator Outcome yang sesuai dengan aktifitas kegiatan yang direncanakan, atau dapat diisi dari Dokumen RENSTRA Perangkat Daerah dan Dokumen RPJMD 2026-2029 Provinsi Lampung. Indikator Outcome harus dapat terukur<br> -->
                            Contoh Data:<br>
                            Dinas BMBK dalam RPJMD 2026-2029 Provinsi Lampung mengampu 2 Sasaran RPJMD yaitu:<br>
                            1. Meningkatnya kualitas Infrastruktur pembangunan<br>
                            2. Meningkatnya kesejahteraan dan kestabilan daya beli masyarakat<br>
                            Setelah di Identifikasi sampai pada Rincian Belanja pada salah satu Subkegiatan Rehabilitasi Jalan dalam Program Penyelenggaraan Jalan mendukung sasaran Mengingkatnya kualitas Infrastruktur Pemangunan, data aktifitas disusun sebagai berikut:
                            <br>
                            <hr>
                            <strong>Subkegiatan</strong> : Rehabilitasi Jalan<br>
                            <hr>
                            <strong>Kelompok Aktifitas</strong> : Aktifitas Utama<br>
                            <strong>Sasaran RPJMD/Program Prioritas</strong>: Meningkatnya kualitas Infrastruktur Pembangunan<br>
                            <strong>Target Pelaksanaan</strong> : 12 Paket<br>
                            <strong>Uraian Target Pelaksanaan</strong> : Pada tahun anggaran 2026 ini direncanakan akan melaksanakan Rehabilitasi Jalan sebanyak 12 Paket pekerjaan yang berlokasi di Desa Natar,Desa Wonosari, Desa Bangun Rejo dst..(Uraikan dan jelaskan)
                            guna peningkatan aksesibilitas masyarakat yang nyaman
                            dan aman dengan Tingkat Kemantapan Jalan sebesar 75% (data ini dapat bersumber dari Renstra atau RPMD)<br>
                            <hr>
                            <!-- <strong>Kelompok Aktifitas</strong> : Aktifitas Pendukung<br>
                            <strong>Sasaran RPJMD/Program Prioritas</strong>: Meningkatnya kualitas Infrastruktur Pembangunan<br>
                            <strong>Target Pelaksanaan</strong> : 5 Paket<br>
                            <strong>Uraian Target Pelaksanaan</strong> : Pada tahun anggaran 2026 ini untuk mendukung pelaksanaan Pemeliharaan Peralatan dan Mesin sebanyak 5 unit,diperlukan pengadaan ATK dan Cetak sebanyak 4 paket yang realisasi perpaket diadakan per triwulan , dan pelaksanaan sosialisasi bagi penyedia 1 paket (Uraikan dan jelaskan)
                            <br>
                            <strong>Indikator Outcome</strong> : Meningkatnya aksesibilitas masyarakat yang nyaman
                            dan aman dengan Tingkat Kemantapan Jalan sebesar 75% (data ini dapat bersumber dari Renstra atau RPMD)<br> -->
                        </small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    // Check if modal has been shown before using localStorage
    let modalShown = localStorage.getItem('modalShown');

    // Auto show modal when page loads
    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($showModal ?? true): ?>
            // Show modal if not shown before or you can always show it
            if (!modalShown) {
                var myModal = new bootstrap.Modal(document.getElementById('welcomeModal'));
                myModal.show();
                // Set flag bahwa modal sudah ditampilkan (optional)
                // localStorage.setItem('modalShown', 'true');
            }
        <?php endif; ?>
    });

    // Handle Get Started button
    document.getElementById('acceptBtn').addEventListener('click', function() {
        var myModal = bootstrap.Modal.getInstance(document.getElementById('welcomeModal'));
        myModal.hide();
        // Show success notification
        alert('Welcome! Enjoy exploring our website.');
    });

    // Show modal manually with button
    document.getElementById('showModalBtn').addEventListener('click', function() {
        var myModal = new bootstrap.Modal(document.getElementById('welcomeModal'));
        myModal.show();
    });

    // Optional: Clear localStorage to test modal again
    // localStorage.removeItem('modalShown');
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let counter = 1;
        const inputContainer = document.getElementById('input-container');

        // Tambah input baru
        document.getElementById('tambah-input').addEventListener('click', function() {
            const newInputGroup = document.createElement('div');
            newInputGroup.className = 'input-group row g-3';

            newInputGroup.innerHTML = `
            <hr>
                    <div class="input-group row g-3">
                    <!--begin::Col-->
                    <br>
                    <div class="col-md-3">
                        <label for="hasil" class="form-label">Mendukung Sasaran RPJMD/Program Prioritas (pilih):</label>
                        <?= form_dropdown('data[${counter}][id_progprioritas]', $dataprogprioritas, $inputs['${counter}']['id_pprio'] ?? null, ['class' => 'form-select']); ?>
                    </div>
                    <br>
                    <div class="col-md-3">
                        <label for="hasil" class="form-label">Kelompok Aktifitas (pilih):</label>
                        <select name="data[${counter}][kelompok]" id="kelompok" required class="form-select form-select-sm" aria-label=".form-select-lg example">
                            <option value="">-- Pilih Kelompok --</option>
                            <option value="Aktifitas Utama">Aktifitas Utama</option>
                        </select>
                    </div>
                    <br>   
                        <div class="col-md-5">
                            <label for="uraian" class="form-label">Uraian Target Pelaksanaan Aktivitas Tahun <?= esc($tahunaktif) ?></label>
                            <textarea id="uraian" name="data[${counter}][uraian_target]" class="form-control" required
                                oninput="this.value = this.value.replace(/[^a-zA-Z0-9,.%()?/\s]/g, ' ')"
                                placeholder="uraian/penjelasan mengenai sasaran" rows="5"></textarea>
                        </div>
                        <div class="col-md-2">
                            <label for="target" class="form-label">Jumlah Target Aktifitas</label>
                            <input type="number" name="data[${counter}][vol_target]" class="form-control"
                                oninput="this.value = this.value.replace(/[^0-9\s]/g, '')"
                                placeholder="jumlah sasaran/target" required>
                            <select name="data[${counter}][sat_target]" id="satuan" required class="form-select form-select-sm" aria-label=".form-select-lg example">
                                <option value="">-- Pilih Satuan --</option>
                                <option value="kegiatan">Kegiatan</option>
                                <option value="orang">Orang</option>
                                <option value="kali">Kali</option>
                                <option value="bulan">Bulan</option>
                                <option value="paket">Paket</option>
                                <option value="unit">Unit</option>
                                <option value="km">Km</option>
                                <option value="Kg">Kg</option>
                                <option value="Buah">Buah</option>
                            </select>
                            <input type="hidden" name="data[${counter}][id_targetsubkeg]" value="<?= esc($id_subgiatmapping) ?>">
                            <input type="hidden" name="data[${counter}][tahun]" value="<?= esc($subkegcapkindipilih['tahun'])  ?>">
                            <input type="hidden" name="data[${counter}][kd_subunit]" value="<?= esc($subkegcapkindipilih['kd_sub_skpd'])  ?>">
                            <input type="hidden" name="data[${counter}][kd_subkegiatan]" value="<?= esc($subkegcapkindipilih['kd_sub_giat'])  ?>">
                            <input type="hidden" name="data[${counter}][pagu]" value="<?= esc($subkegcapkindipilih['total_anggaran']) ?>">
                            <input type="hidden" name="data[${counter}][hasil]" value="kosong">
                        </div>
                        <!--end::Col-->
                        <div class="col-md-3">
                            <label for="target" class="form-label">Rencana Lokasi Aktifitas : </label>
                            <br>
                            <select name="data[${counter}][lokasi]" id="lokasi" required class="form-select form-select-sm" aria-label=".form-select-lg example">
                                <option value="">-- Pilih Kab/Kota --</option>
                                <option value="tersebar">Tersebar Sesuai Realisasi</option>
                                <option value="Kota Bandar Lampung">Kota Bandar Lampung</option>
                                <option value="Kota Metro">Kota Metro</option>
                                <option value="Kabupaten Lampung Barat">Kabupaten Lampung Barat</option>
                                <option value="Kabupaten Tanggamus">Kabupaten Tanggamus</option>
                                <option value="Kabupaten Lampung Selatan">Kabupaten Lampung Selatan</option>
                                <option value="Kabupaten Lampung Timur">Kabupaten Lampung Timur</option>
                                <option value="Kabupaten Lampung Utara">Kabupaten Lampung Utara</option>
                                <option value="Kabupaten Lampung Tengah">Kabupaten Lampung Tengah</option>
                                <option value="Kabupaten Way Kanan">Kabupaten Way Kanan</option>
                                <option value="Kabupaten Tulang Bawang">Kabupaten Tulang Bawang</option>
                                <option value="Kabupaten Pesawaran">Kabupaten Pesawaran</option>
                                <option value="Kabupaten Pringsewu">Kabupaten Pringsewu</option>
                                <option value="Kabupaten Mesuji">Kabupaten Mesuji </option>
                                <option value="Kabupaten Tulang Bawang Barat">Kabupaten Tulang Bawang Barat</option>
                                <!-- <option value="paket">Paket</option> -->
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-input" disabled>
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <hr>`;
            inputContainer.appendChild(newInputGroup);
            counter++;
            // Aktifkan tombol hapus pada semua input kecuali yang pertama
            document.querySelectorAll('.remove-input').forEach((btn, index) => {
                btn.disabled = index === 0;
            });
        });

        // Hapus input
        inputContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-input') || e.target.closest('.remove-input')) {
                const inputGroup = e.target.closest('.input-group');
                if (inputContainer.children.length > 1) {
                    inputGroup.remove();

                    // Perbarui nama input untuk memastikan array tetap berurutan
                    document.querySelectorAll('#input-container .input-group').forEach((group, index) => {
                        const inputs = group.querySelectorAll('input');
                        inputs[0].name = `data[${index}][vol_target]`;
                        inputs[1].name = `data[${index}][sat_target]`;
                        inputs[2].name = `data[${index}][id_targetsubkeg]`;
                        inputs[3].name = `data[${index}][tahun]`;
                        inputs[4].name = `data[${index}][kd_subunit]`;
                        inputs[5].name = `data[${index}][kd_subkegiatan]`;
                        inputs[6].name = `data[${index}][pagu]`;
                        inputs[7].name = `data[${index}][uraian_target]`;
                        inputs[8].name = `data[${index}][hasil]`;
                        inputs[9].name = `data[${index}][lokasi]`;
                        inputs[10].name = `data[${index}][id_progprioritas]`;
                        // inputs[11].name = `data[${index}][id_progunggulan]`;
                        // inputs[12].name = `data[${index}][id_progtematik]`;
                        // Nonaktifkan tombol hapus jika hanya tersisa satu input
                        const removeBtn = group.querySelector('.remove-input');
                        removeBtn.disabled = inputContainer.children.length === 1;
                    });
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>
<!-- /.card -->