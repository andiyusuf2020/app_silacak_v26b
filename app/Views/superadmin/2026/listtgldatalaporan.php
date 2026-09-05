<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">PILIH TANGGAL DATA YANG AKAN DILIHAT::</h3><br>
                </div>
                <?php if (session()->getFlashdata('message')): ?>
                    <div class="alert alert-primary" role="alert">
                        <ul>
                            <p><?= session()->getFlashdata('message') ?></p>
                        </ul>
                    </div>
                <?php endif; ?>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>TAHUN</th>
                                <th>BULAN</th>
                                <th>TANGGALDATA</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tglapbd as $key => $tgl) : ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td><?= $tahunaktif ?? null;  ?></td>
                                    <td>
                                        <?= $tgl['BULAN'] ?? null; ?>
                                    </td>
                                    <td>
                                        <?= $tgl['CREATE_AT'] ?? null; ?>
                                    </td>
                                    <td>
                                        <!-- <input type="checkbox" class="form-check-input" id="exampleCheck1" checked />
                                        <label class="form-check-label" for="exampleCheck1"> -->
                                        <a href="<?= hash_url('adminprov/laporanapbdopd/', [
                                                        'tgldata' => $tgl['CREATE_AT'],
                                                    ]);
                                                    ?>">
                                            <button type="button" class="btn btn-outline-primary mb-2">Detail</button>
                                        </a> </label>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- /.card -->
<?= $this->endSection() ?>