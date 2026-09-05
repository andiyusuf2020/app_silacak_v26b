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
                        <label for="Program" class="form-label">Pilih Program</label>
                        <form action="<?= hash_url('capkin2026/pilihprogram') ?>" method="post">
                            <?= csrf_field(); ?>
                            <select name="program" onchange="this.form.submit()">
                                <option value="">Pilih Program</option>
                                <?php foreach ($program as $listprogram): ?>
                                    <option required value="<?= $listprogram['KODE_PROGRAM'] ?>" <?= isset($program) && $program == $listprogram['NAMA_PROGRAM'] ? 'selected' : '' ?>>
                                        <?= $listprogram['NAMA_PROGRAM']  ?>
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