<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<!--begin::Container-->
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
        <div class="col-6">
            <!-- Default box -->
            <?= form_open_multipart('lrfkadmin/ubahopdprov'); ?>
            <?= csrf_field(); ?>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Halaman Ubah Nama Perangkat Daerah pada User OPD Provinsi Lampung</h3>
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
                                <label class="col-sm-10 col-form-label">Email User</label>
                                <div class="col-sm-10">
                                    <label class="col-sm-10 col-form-label"><?= $listuser['email'] ?>:<?= $listuser['id'] ?></label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="basic-url" class="form-label">Pilih Perangkat Daerah :</label>
                                <div class="input-group">
                                    <select name="nm_opd" class="form-select" id="validationCustom04" required>
                                        <?php
                                        foreach ($listopd as $key => $row) {
                                        ?>
                                            <option value="<?= $row['nm_sub_unit']; ?>"><?= $row['nm_sub_unit']; ?></option>

                                        <?php
                                            // echo form_hidden('kd_skpd', $row['kd_sub_unit']);
                                            // echo form_hidden('kd_sub_unit', $row['kd_sub_unit']);
                                        }
                                        echo form_hidden('id', $listuser['id']);

                                        ?>
                                    </select>
                                </div>
                                <div class="form-text" id="basic-addon4">
                                    Ubah Nama Pejabat Penanggung Jawab Data Jika diperlukan.
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