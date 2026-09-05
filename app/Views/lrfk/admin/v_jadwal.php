<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<div class="container-fluid">

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Jadwal Input Data yang aktif saat ini ::</h3>
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
                            <tr>
                                <td>1.</td>
                                <td><?= $tahunaktif ?? null;  ?></td>
                                <td>
                                    <?= $jadwalaktif['bulan'] ?? null; ?>
                                </td>
                                <td>
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1" checked />
                                    <label class="form-check-label" for="exampleCheck1">
                                        <a
                                            href="<?= hash_url('lrfkadmin/jadwaladmin/', ['action' => 'ganti']); ?>"
                                            rel="noopener noreferrer"
                                            class="callout-link">
                                            Ganti Jadwal Aktif
                                        </a>
                                    </label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aktifasi Data Master Realisasi APBD ::</h3><br>
                    <span class="text-danger">Tanggal Data APBD yang aktif saat ini :: <?= $tglapbdaktif['tanggal'] ?? null;  ?></span>
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
                                <th>TGL UPLOAD DATA</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tglapbd as $tgl) : ?>
                                <tr>
                                    <td>1.</td>
                                    <td><?= $tahunaktif ?? null;  ?></td>
                                    <td>
                                        <?= $tgl['BULAN'] ?? null; ?>
                                    </td>
                                    <td>
                                        <?= $tgl['CREATE_AT'] ?? null; ?>
                                    </td>
                                    <td>
                                        <input type="checkbox" class="form-check-input" id="exampleCheck1" checked />
                                        <label class="form-check-label" for="exampleCheck1">
                                            <a
                                                href="<?= hash_url('lrfkadmin/jadwaladmin/', [
                                                            'action' => 'aktifasitgl',
                                                            'tglpilih' => $tgl['CREATE_AT']
                                                        ]); ?>"
                                                rel="noopener noreferrer"
                                                class="callout-link">
                                                Aktifkan Tanggal Data APBD
                                            </a>
                                        </label>
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