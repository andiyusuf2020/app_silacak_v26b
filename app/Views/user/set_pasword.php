<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>

<?php if (isset($validation)) { ?>
    <div class="col-md-12">
        <?php foreach ($validation->getErrors() as $error) : ?>
            <div class="alert alert-warning" role="alert">
                <i class="mdi mdi-alert-outline me-2"></i>
                <?= esc($error) ?>
            </div>
        <?php endforeach ?>
    </div>
<?php } ?>
<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Horizontal Form</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <!-- form start -->
    <?= form_open_multipart('' . $wilayah . '/' . $groupuser . '/opdsetpass', 'class="form-horizontal'); ?>
    <?= csrf_field(); ?>
    <?= form_hidden('id', $listuser['id']) ?>
    <div class="card-body">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Perangkat Daerah</label>
            <div class="col-sm-10">
                <label class="col-sm-5 col-form-label"><?= $listuser['sub_unit'] ?></label>

            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Email </label>
            <div class="col-sm-10">
                <label class="col-sm-5 col-form-label"><?= $listuser['email'] ?></label>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Username </label>
            <div class="col-sm-10">
                <label class="col-sm-5 col-form-label"><?= $listuser['username'] ?></label>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Password </label>
            <div class="col-6">
                <input type="password" name="password" class="form-control form-control-user ">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Konfirmasi Password </label>
            <div class="col-6">
                <input type="password" name="pass_confirm" class="form-control form-control-user ">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <?= form_close(); ?>
    </div>
</div>
<?= $this->endSection() ?>