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
            <?php if (!(session()->getFlashdata('message'))): ?>

                <!--begin::Form-->
                <?= form_open_multipart('capkin2026/simpanrencanaaktifitas', 'id="myForm"'); ?>
                <?= csrf_field(); ?>
                <!--begin::Body-->
                <div class="card-body">
                    <div id="input-container">
                        <div class="mb-3">
                            <label for="subkegiatan" class="form-label">SUBKEGIATAN</label>
                            <h2><?= esc($datakegpokok['nm_sub_giat'])  ?></h2>
                            <!-- <a href="
                          <?php
                            // hash_url('capkin/kegpokok/', [
                            //     'page' => 'kegpokok',
                            //     'action' => 'ubahsubkeg',
                            //     'idUbah' => $id_targetsubkeg,
                            // ]);
                            ?> " <button type="button" class="btn btn-outline-primary">
                            <i class="bi bi-database-add"></i>UBAH SUBKEGIATAN
                            </button></a> -->
                        </div>
                        <div class="col-md-3">
                            <label for="hasil" class="form-label">Kelompok Aktifitas (pilih):</label>
                            <select name="data[0][kelompok]" id="kelompok" required class="form-select form-select-sm" aria-label=".form-select-lg example">
                                <option value="<?php echo esc($datakegpokok['kelompok']); ?>"><?php echo esc($datakegpokok['kelompok']); ?></option>
                                <option value="Aktifitas Utama">Aktifitas Utama</option>
                                <option value="Aktifitas Pendukung">Aktifitas Pendukung</option>
                            </select>
                        </div><br>
                        <div class="input-group row g-3">
                            <div class="col-md-2">
                                <label for="target" class="form-label">Jumlah Target Pelaksanaan/Sasaran</label>
                                <input type="number" name="data[0][vol_target]" value="<?= esc($datakegpokok['vol_target']) ?>" class="form-control"
                                    oninput="this.value = this.value.replace(/[^0-9\s]/g, '')"
                                    placeholder="jumlah sasaran/target" required>
                                <br>
                                <select name="data[0][sat_target]" id="satuan" required class="form-select form-select-sm" aria-label=".form-select-lg example">
                                    <option value="<?php echo esc($datakegpokok['sat_target']); ?>"><?php echo esc($datakegpokok['sat_target']); ?></option>
                                    <option value="orang">Orang</option>
                                    <option value="kali">Kali</option>
                                    <option value="bulan">Bulan</option>
                                    <option value="paket">Paket</option>
                                    <option value="unit">Unit</option>
                                    <option value="km">Km</option>
                                    <!-- <option value="paket">Paket</option> -->
                                </select>
                                <br>
                                <label for="target" class="form-label">Rencana Lokasi Pelaksanaan : </label>
                                <br>
                                <select name="data[0][lokasi]" id="lokasi" required class="form-select form-select-sm" aria-label=".form-select-lg example">
                                    <option value="<?php echo esc($datakegpokok['lokasi']); ?>"><?php echo esc($datakegpokok['lokasi']); ?></option>
                                    <option value="Tersebar Sesuai Realisasi">Tersebar Sesuai Realisasi</option>
                                    <option value="Kota Bandar Lampung">Kota Bandar Lampung</option>
                                    <option value="Kota Metro">Kota Metro</option>
                                    <option value="Kabupaten Lampung Barat">Kabupaten Lampung Barat</option>
                                    <option value="Kabupaten Tanggamus">Kabupaten Tanggamus</option>
                                    <option value="Kabupaten Lampung Selatan">Kabupaten Lampung Selatan</option>
                                    <option value="Kabupaten Lampung Timur">Kabupaten Lampung Timur</option>
                                    <option value="Kabupaten Lampung Utara">Kabupaten Lampung Utara</option>
                                    <option value="Kabupaten Way Kanan">Kabupaten Way Kanan</option>
                                    <option value="Kabupaten Tulang Bawang">Kabupaten Tulang Bawang</option>
                                    <option value="Kabupaten Pesawaran">Kabupaten Pesawaran</option>
                                    <option value="Kabupaten Pringsewu">Kabupaten Pringsewu</option>
                                    <option value="Kabupaten Mesuji">Kabupaten Mesuji </option>
                                    <option value="Kabupaten Tulang Bawang Barat">Kabupaten Tulang Bawang Barat</option>
                                    <!-- <option value="paket">Paket</option> -->
                                </select>
                                <br>

                                <!-- <input type="text" name="data[0][sat_target]" class="form-control"
                                oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')"
                                placeholder="satuan sasaran/target" required> -->
                                <input type="hidden" name="data[0][id_kp]" value="<?= esc($datakegpokok['id_kp']) ?>" ?>
                                <input type="hidden" name="data[0][id_targetsubkeg]" value="<?= esc($datakegpokok['id_targetsubkeg']) ?>" ?>
                                <input type="hidden" name="data[0][tahun]" value="<?= esc($datakegpokok['tahun'])  ?>" ?>
                                <input type="hidden" name="data[0][kd_subunit]" value="<?= esc($datakegpokok['kd_subunit'])  ?>" ?>
                                <input type="hidden" name="data[0][kd_subkegiatan]" value="<?= esc($datakegpokok['kd_subkegiatan'])  ?>" ?>
                                <input type="hidden" name="data[0][pagu]" value="<?= esc($datakegpokok['pagu']) ?>" ?>

                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-5">
                                <label for="uraian" class="form-label">Uraian Target Pelaksanaan Aktivitas/Kegiatan Subkegiatan Tahun <?= esc($tahunaktif) ?></label>
                                <textarea id="uraian" value="<?= esc($datakegpokok['uraian_target']) ?>" name="data[0][uraian_target]" class="form-control" required
                                    oninput="this.value = this.value.replace(/[^a-zA-Z0-9,.%()?/\s]/g, '')"
                                    placeholder="uraian/penjelasan mengenai sasaran" rows="5">
                            <?= esc($datakegpokok['uraian_target']) ?>
                            </textarea>
                            </div>
                            <div class="col-md-4">
                                <label for="hasil" class="form-label">Outcome/Benefit sesuai Output Aktifitas yang dihasilkan sampai akhir Tahun Anggaran <?= esc($tahunaktif) ?></label>
                                <textarea name="data[0][hasil]" class="form-control" id="hasil" value="<?= esc($datakegpokok['hasil']) ?>" required
                                    oninput="this.value = this.value.replace(/[^a-zA-Z0-9,.%()?/\s]/g, '')"
                                    placeholder="diawali dengan Ter...." rows="5">
                            <?= esc($datakegpokok['hasil']) ?>
                            </textarea>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger remove-input" disabled>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div><br>
                            <div class="col-md-3">
                                <label for="hasil" class="form-label">Mendukung Sasaran Pembangunan (pilih):</label>
                                <?= form_dropdown('data[0][id_progprioritas]', $dataprogprioritas, $datakegpokok['nm_progprioritas'], $inputs[0]['id_pprio'] ?? null, ['class' => 'form-select', 'required']); ?>
                            </div>

                        </div>
                    </div>
                <?php endif; ?>

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
                            1. Identifikasi Aktifitas-aktifitas kegiatan dalam subkegiatan apa saja yang mendukung Sasaran RPJMD 2026-2029 Provinsi Lampung sesuai Sasaran RPMJD yang di ampu oleh Perangkat Daerah saudara.<br>
                            2. Identifikasi aktifitas kegiatan ke dalam 2 kelompok aktifitas; yaitu<br>
                            a. Aktifitas Utama<br>
                            Merupakan aktifitas yang dilaksanakan yang menjadi output dari subkegiatan atau output perangkat daerah yang mendukung sasaran RPJMD<br>
                            b. Aktifitas Pendukung<br>
                            Merupakan aktifitas yang dilaksanakan untuk mendukung aktifitas utama subkegiatan atau output perangkat daerah<br>
                            3. Tentukan rencana target aktifitas, rencana lokasi aktifitas, dan uraian aktifitas yang akan dilaksanakan dalam 1 tahun anggaran<br>
                            4. Tentukan Indikator Outcome yang sesuai dengan aktifitas kegiatan yang direncanakan, atau dapat diisi dari Dokumen RENSTRA Perangkat Daerah dan Dokumen RPJMD 2026-2029 Provinsi Lampung. Indikator Outcome harus dapat terukur<br>
                            Contoh Data:<br>
                            Dinas BMBK dalam RPJMD 2026-2029 Provinsi Lampung mengampu 2 Sasaran RPJMD yaitu:<br>
                            1. Meningkatnya kualitas Infrastruktur pembangunan<br>
                            2. Meningkatnya kesejahteraan dan kestabilan daya beli masyarakat<br>
                            Setelah di Identifikasi sampai pada Rincian Belanja pada salah satu Subkegiatan Rehabilitasi Jalan dalam Program Penyelenggaraan Jalan mendukung sasaran Mengingkatnya kualitas Infrastruktur Pemangunan, data aktifitas disusun sebagai berikut:
                            <br>
                            <strong>Subkegiatan</strong> : Rehabilitasi Jalan<br>
                            <strong>Kelompok Aktifitas</strong> : Aktifitas Utama<br>
                            <strong>Sasaran RPJMD/Program Prioritas</strong>: Meningkatnya kualitas Infrastruktur Pembangunan<br>
                            <strong>Target Pelaksanaan</strong> : 12 Paket<br>
                            <strong>Uraian Target Pelaksanaan</strong> : Pada tahun anggaran 2026 ini direncanakan akan melaksanakan Rehabilitasi Jalan sebanyak 12 Paket pekerjaan yang berlokasi di Desa Natar,Desa Wonosari, Desa Bangun Rejo dst..(Uraikan dan jelaskan)
                            <br><strong>Indikator Outcome</strong> : Meningkatnya aksesibilitas masyarakat yang nyaman
                            dan aman dengan Tingkat Kemantapan Jalan sebesar 75% (data ini dapat bersumber dari Renstra atau RPMD)<br>
                            <strong>Kelompok Aktifitas</strong> : Aktifitas Pendukung<br>
                            <strong>Sasaran RPJMD/Program Prioritas</strong>: Meningkatnya kualitas Infrastruktur Pembangunan<br>
                            <strong>Target Pelaksanaan</strong> : 5 Paket<br>
                            <strong>Uraian Target Pelaksanaan</strong> : Pada tahun anggaran 2026 ini untuk mendukung pelaksanaan Pemeliharaan Peralatan dan Mesin sebanyak 5 unit,diperlukan pengadaan ATK dan Cetak sebanyak 4 paket yang realisasi perpaket diadakan per triwulan , dan pelaksanaan sosialisasi bagi penyedia 1 paket (Uraikan dan jelaskan)
                            <br>
                            <strong>Indikator Outcome</strong> : Meningkatnya aksesibilitas masyarakat yang nyaman
                            dan aman dengan Tingkat Kemantapan Jalan sebesar 75% (data ini dapat bersumber dari Renstra atau RPMD)<br>
                        </small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="acceptBtn">Get Started</button>
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


<?= $this->endSection() ?>
<!-- /.card -->