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

<?= form_open_multipart('superadmin/setpassword'); ?>
<?= csrf_field(); ?>
<input type="hidden" name="id" class="id" value="<?= $id; ?>">
<div class="form-group row">
    <div class="col-6">
        <input type="password" name="password" class="form-control form-control-user <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" placeholder="<?= lang('Auth.password') ?>" autocomplete="off">
    </div>
</div>
<div class="form-group row">
    <div class="col-6">
        <input type="password" name="pass_confirm" class="form-control form-control-user <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>" placeholder="<?= lang('Auth.repeatPassword') ?>" autocomplete="off">
    </div>
</div>
<button type="submit" class="btn btn-primary">Simpan</button>
<?= form_close(); ?>
<?= $this->endSection() ?>