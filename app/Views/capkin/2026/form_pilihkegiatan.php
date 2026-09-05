<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- SELECT2 EXAMPLE -->
    <div class="card card-default">
        <div class="card card-primary card-outline mb-4">
            <!--begin::Header-->
            <div class="card-header">
                <div class="card-title">
                    Form Mapping SubKegiatan Aktifitas Utama Kegiatan
                </div>
            </div>
            <div class="card-header">
                <?php if (session()->getFlashdata('message')): ?>
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            <p><?= session()->getFlashdata('message') ?></p>
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
            <div class="card-body">
                <div id="input-container">
                    <div class="mb-3">
                        <label for="Program" class="form-label">Nama Program : <?= esc($kegiatan[0]['NAMA_PROGRAM'] ?? '')  ?></label> <br>
                        <label for="Program" class="form-label">Pilih Kegiatan</label>
                        <form action="<?= hash_url('capkin2026/pilihprogram') ?>" method="post">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="program" value="<?= esc($kode_program) ?>">
                            <input type="hidden" name="kode_program" value="<?= esc($kode_program) ?>">
                            <select name="kode_kegiatan" onchange="this.form.submit()">
                                <option value="">Pilih Kegiatan</option>
                                <?php foreach ($kegiatan as $listkegiatan): ?>
                                    <option required value="<?= $listkegiatan['KODE_GIAT'] ?>" <?= isset($kode_kegiatan) && $kode_kegiatan == $listkegiatan['KODE_GIAT'] ? 'selected' : '' ?>>
                                        <?= $listkegiatan['NAMA_GIAT']  ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </form>
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