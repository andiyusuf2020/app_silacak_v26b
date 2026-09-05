<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- SELECT2 EXAMPLE -->
    <div class="card card-default">
        <div class="card card-primary card-outline mb-4">
            <!--begin::Header-->
            <div class="card-header">
                <div class="card-title">Form Input Realisasi Aktifitas pada Subkegiatan bulan <?= esc($bulanaktif) ?></div>
            </div>
            <div class="card-header">
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
            <!--begin::Form-->
            <?= form_open_multipart('capkin2026/simpanrealisasiaktifitas', 'id="myForm"'); ?>
            <?= csrf_field(); ?>
            <!--begin::Body-->
            <div class="card-body">
                <div id="input-container">
                    <div class="mb-3">
                        <label for="subkegiatan" class="form-label">Uraian Target Pelaksanaan Aktivitas/Kegiatan Subkegiatan Tahun <?= esc($tahunaktif) ?> </label>
                        <h2><?= esc($datarkp['uraian_target'] . ' dengan target pelaksanaan  :' . $datarkp['vol_target'] . '  ' . $datarkp['sat_target']) ?></h2>
                    </div>

                    <div class="col-md">
                        <label for="hasil" class="form-label">Mendukung Sasaran RPJMD/Program Prioritas:</label>
                        <h3><?= esc($datarkp['nm_progprioritas']) ?></h3>
                    </div>
                    <br>
                    <div class="mb-3">
                        <h2> <label for="kegpokok" class="form-label">Realisasi s.d Bulan <?= esc($bulanaktif) ?></label></h2>
                    </div>
                    <div class="input-group row g-3">
                        <div class="col-md-2">
                            <label for="target" class="form-label">Jumlah Realisasi dari Target/Sasaran</label>
                            <input type="number" value="<?= esc($datarkp['r_target']) ?>" name="r_target" class="form-control"
                                oninput="this.value = this.value.replace(/[^0-9\s]/g, '')"
                                placeholder="jumlah realisasi sasaran" required>
                            <label for="target" class="form-label"><?= esc($datarkp['sat_target']) ?></label>
                            <!-- <input type="text" class="form-control" id="kegpokok" /> -->
                            <input type="hidden" name="idKP" value="<?= esc($datarkp['id_kegpokok'])  ?>" ?>
                            <input type="hidden" name="id_r" value="<?= esc($datarkp['id_r'])  ?>" ?>
                            <input type="hidden" name="bulan" value="<?= esc($bulanaktif)  ?>" ?>

                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-md-5">
                            <label for="uraian" class="form-label">Uraian Realisasi Pelaksanaan Aktivitas/Kegiatan Subkegiatan
                                Bulan <?= esc($bulanaktif) ?> Tahun <?= esc($tahunaktif) ?></label>
                            <?php
                            $r_uraian = [
                                'type' => 'text',
                                'name' => 'r_uraian',
                                'value' => esc($datarkp['r_uraian']),
                                'class' => 'form-control',
                                'placeholder' => 'isi penjelasan mengenai target atau sasaran',
                                'oninput'  => "this.value = this.value.replace(/[^a-zA-Z0-9,.%()?/\s]/g, '')",
                                'required' => 'true'
                            ];
                            echo form_textarea($r_uraian);
                            ?>
                        </div>
                    </div>
                </div>
                <!--end::Body-->
                <!--begin::Footer-->
                <div class="d-flex justify-content-between mt-4">
                    <?php if (!(session()->getFlashdata('message'))): ?>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan
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
<?= $this->endSection() ?>
<!-- /.card -->