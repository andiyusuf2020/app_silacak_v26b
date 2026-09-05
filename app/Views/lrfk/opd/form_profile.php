<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<!--begin::Container-->
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
        <div class="col-6">
            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Mohon Lengkapi data Profile User untuk melanjutkan</h3>
                    <div class="card-tools">
                        <button
                            type="button"
                            class="btn btn-tool"
                            data-lte-toggle="card-collapse"
                            title="Collapse">
                            <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                            <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                        </button>
                        <button
                            type="button"
                            class="btn btn-tool"
                            data-lte-toggle="card-remove"
                            title="Remove">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body"> <!--begin::Body-->
                    <?= form_open_multipart('lrfkopd/profile'); ?>
                    <?= csrf_field(); ?>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="mb-3">
                                <?php if (session()->getFlashdata('message')): ?>
                                    <div class="alert alert-primary" role="alert">
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
                                <label for="basic-url" class="form-label">Nama Perangkat Daerah ::</label>
                                <div class="input-group">
                                    <?php
                                    echo form_label(esc($datauser['sub_unit']))
                                    ?>
                                </div>
                                <div class="form-text" id="basic-addon4">
                                    Silakan menghubungi Admin Adbang untuk mengubah Perangkat Daerah </div>
                            </div>
                            <div class="mb-3">
                                <label for="basic-url" class="form-label">Nama Penanggung Jawab Data</label>
                                <div class="input-group">
                                    <?php
                                    $nama = [
                                        'type' => 'text',
                                        'name' => 'nama',
                                        'value' => old('nama'),
                                        'class' => 'form-control',
                                        'placeholder' => 'isi nama lengkap',
                                        'required' => 'true'
                                    ];
                                    echo form_input($nama);
                                    ?>
                                </div>
                                <div class="form-text" id="basic-addon4">
                                    Nama Pejabat Penanggung Jawab Data .
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="basic-url" class="form-label">NIP</label>
                                <div class="input-group">
                                    <?php
                                    $nip = [
                                        'type' => 'text',
                                        'name' => 'nip',
                                        'value' => old('nip'),
                                        'class' => 'form-control',
                                        'placeholder' => 'isi nip penanggung jawab',
                                        'required' => 'true'
                                    ];
                                    echo form_input($nip);
                                    ?>
                                </div>
                                <div class="form-text" id="basic-addon4">
                                    NIP Pejabat Penanggung Jawab Data .
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="basic-url" class="form-label">Jabatan</label>
                                <div class="input-group">
                                    <?php
                                    $jabatan = [
                                        'type' => 'text',
                                        'name' => 'jabatan',
                                        'value' => old('jabatan'),
                                        'class' => 'form-control',
                                        'placeholder' => 'isi jabatan',
                                        'required' => 'true'
                                    ];
                                    echo form_input($jabatan);
                                    ?>
                                </div>
                                <div class="form-text" id="basic-addon4">
                                    Jabatan Penanggung Jawab .
                                </div>
                            </div>

                        </div>
                        <!--end::Body-->

                    </div>
                    <!--begin::Footer-->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                    <!--end::Footer-->
                </div>
                <?= form_close(); ?>
                <!--end::Input Group-->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!--end::Row-->
</div>
<!--end::Container-->

<?= $this->endSection() ?>