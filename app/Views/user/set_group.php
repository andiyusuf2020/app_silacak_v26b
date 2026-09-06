<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>


<!-- Horizontal Form -->
<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Horizontal Form</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <!-- form start -->
    <?= form_open_multipart('superadmin/groupset', 'class="form-horizontal'); ?>
    <?= csrf_field(); ?>
    <div class="card-body">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Perangkat Daerah</label>
            <div class="col-sm-10">
                <label class="col-sm-5 col-form-label"><?= $listuser['sub_unit'] ?></label>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Email User</label>
            <div class="col-sm-10">
                <label class="col-sm-5 col-form-label"><?= $listuser['email'] ?>:<?= $listuser['id'] ?></label>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-5">
                <select name="nm_group" class="form-select" id="validationCustom04" required>
                    <?php
                    foreach ($groups as $key => $row) {
                    ?>
                        <option value="<?= $row->id; ?>"><?= $row->name; ?></option>
                    <?php
                    }
                    echo form_hidden('id', $listuser['id'])
                    ?>
                </select>
            </div>

        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <button type="submit" class="btn btn-info">SIMPAN</button>
    </div>
    <!-- /.card-footer -->
    <?= form_close(); ?>
</div>
<!-- /.card -->

<?= $this->endSection() ?>