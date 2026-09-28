<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>

<?php

use App\Models\DataApbdModel\RealApbdModel;

$this->realapbd = new RealApbdModel();

?>
<!--begin::Container-->
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Data Sub Kegiatan <?= esc($nama_opd) ?> </h3> <br>
            </div>
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
            <!-- /.card-header -->


            <div class="card-body table-responsive p-0" style="height: 600px;">
                <table class="table table align-middle table-head-fixed table-success table-striped text-wrap">
                    <thead>
                        <tr class="align-bottom">
                            <th style="width: 10px">#</th>
                            <th>Program/Kegiatan/<br>
                                Sub Kegiatan</th>
                            <th>Anggaran <br>Rp.</th>
                            <th>Realisasi Anggaran (SIPD)<br>Rp.
                                <a href="#" data-bs-toggle="tooltip"
                                    data-bs-title="Data ini berdasarkan data Realisasi Rencana dari SIPD Penatausahan">
                                    <i class=" bi bi-emoji-sunglasses"></i></a>
                            </th>
                            <th>>%</th>
                            <th>Capaian Kinerja belanja(output/fisik)</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($dataopd[$nama_opd] as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <?= esc($row[$nama_opd]['NAMA_PROGRAM']) ?><br>
                                    <?= esc($row[$nama_opd]['NAMA_GIAT']) ?><br>
                                    <?= esc($row[$nama_opd]['NAMA_SUB_GIAT']) ?>
                                </td>
                                <td><?= number_format($row[$nama_opd]['total_anggaran'], 0, ',', '.') ?></td>
                                <td><?= number_format($row[$nama_opd]['total_realisasi'], 0, ',', '.') ?></td>
                                <td><?= number_format(($row[$nama_opd]['total_realisasi'] / max($row[$nama_opd]['total_anggaran'], 1)) * 100, 2) ?>%</td>
                                <td><?= number_format(($row[$nama_opd]['jumlah_sro'] - $row[$nama_opd]['sro_nol']) / max($row[$nama_opd]['jumlah_sro'], 1) * 100, 2) ?>%</td>
                                <td>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar bg-success" style="width: <?= ($row[$nama_opd]['jumlah_sro'] - $row[$nama_opd]['sro_nol']) / max($row[$nama_opd]['jumlah_sro'], 1) * 100 ?>%"></div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <span class="text-muted">
                    <i class=" bi bi-info-circle-fill"></i>Data realisasi anggaran per-subkegiatan ini
                    berdasarkan https://sipd-ri.kemendagri.go.id TA <?= esc($tahunaktif) ?>. per <?= esc($tglaktif) ?>. ** </span>
            </div>
        </div>
        <!-- /.card -->

    </div>
</div>
<!--end::Container-->

<?= $this->endSection() ?>