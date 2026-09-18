<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<div class="container-fluid">

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pilih Bulan yang akan diaktifkan</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>TAHUN</th>
                                <th>BULAN</th>
                                <th style="width: 40px">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($jadwal as $key => $value) { ?>
                                <tr>
                                    <td><?= $key + 1  ?></td>
                                    <td><?= date('Y');  ?>
                                    </td>
                                    <td>
                                        <a
                                            href="<?= hash_url('superadmin/jadwaladmin/', ['idUbah' => $value['id'], 'action' => 'edit']);
                                                    ?>">
                                            <?= $value['bulan'];  ?>
                                        </a>

                                    </td>
                                    <td>
                                        <?= $value['status'] ? 'Aktif' : 'Non Aktif';  ?>
                                    </td>
                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- /.card -->
<?= $this->endSection() ?>