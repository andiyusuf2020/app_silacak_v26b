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
    <?= form_open_multipart('' . $wilayah . '/' . $groupuser . '/opdset', 'class="form-horizontal'); ?>
    <?= csrf_field(); ?>
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
            <label class="col-sm-2 col-form-label">Ubah Perangkat Daerah</label>
            <div class="col-sm-10">
                <?php
                $wilayah = session()->get('wilayah');
                if ($wilayah == null) {
                    return redirect()->to(base_url());
                    // return redirect()->to(base_url());
                }

                use App\Models\KabtanggamusModel\RealApbdModel as sipdtanggamus;
                use App\Models\KabtanggamusModel\TglApbdModel as  tglapbdtanggamus;

                $this->sipdtanggamus = new sipdtanggamus();
                $this->tglapbdtanggamus = new tglapbdtanggamus();
                if ($wilayah == 'tanggamus') {
                    $tgldata = $this->tglapbdtanggamus->tgldataaktif();
                    $listopdadmin = $this->sipdtanggamus->listopduserreg($tgldata['tanggal']);
                } ?>
                <select name="sub_unit" class="form-control">
                    <option value=" ">pilih sub unit</option>
                    <?php foreach ($listopdadmin as $row) {
                        $selected = ($row['NAMA_UNIT_SKPD'] == session()->get('wilayah')) ? 'selected' : '';
                        echo '<option value="' . $row['NAMA_UNIT_SKPD'] . '" ' . $selected . '>' . $row['NAMA_UNIT_SKPD'] . '</option>';
                    } ?>
                </select>
                <?= form_hidden('id', $listuser['id']) ?>
            </div>

        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <button type="submit" class="btn btn-info">SIMPAN</button>
    </div>
    <!-- /.card-footer -->

    <!-- /.card-footer -->
    <?= form_close(); ?>
</div>
<!-- /.card -->

<?= $this->endSection() ?>