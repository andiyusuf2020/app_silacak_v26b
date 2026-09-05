<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<!--begin::Container-->
<?php

use App\Models\LrfkProvModel\SubKegModel; // as lrfkModel;
use App\Models\LrfkProvModel\TaRealisasiRinciModel;
use App\Models\CapkinModel\TaSubKegCapkinModel;
use App\Models\CapkinModel\TaKegPokokCapkinModel;
use App\Models\CapkinModel\TaRealisasiKegPokokModel;
use App\Models\CapkinModel\TaRKegPokokCapkinModel;

$this->subkegmodel = new SubKegModel();
$this->realisasilrfkrinci = new TaRealisasiRinciModel();
$this->targetsubkegmodel = new TaSubKegCapkinModel();
$this->kegpokokmodal = new TaKegPokokCapkinModel();
$this->rkegpokokmodal = new TaRealisasiKegPokokModel();
$this->rdkegpokokmodal = new TaRKegPokokCapkinModel();
?>
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Daftar Perangkat Daerah yang berproses input data Capaian Kinerja Bulan <?= esc($bulan) ?></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr style="text-align: center; vertical-align: middle;">
                            <th>No</th>
                            <th>Sub Kegiatan</th>
                            <th>Jumlah Aktifitas termapping</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($dataskcapkinopd as $key => $listsubkeg) { ?>
                            <tr>
                                <td><?= esc($key + 1) ?></td>
                                <td>
                                    <?= esc($listsubkeg['kd_subkegiatan']) ?><br>
                                    <?= esc($listsubkeg['nm_subkegiatan']) ?>
                                </td>
                                <td>
                                    <?php
                                    $ASasaran = $this->kegpokokmodal->selectCount('id_kp')
                                        ->where('tahun', $listsubkeg['tahun'])
                                        ->where('kd_subunit', $listsubkeg['kd_subunit'])
                                        ->where('kd_subkegiatan', $listsubkeg['kd_subkegiatan'])
                                        ->where('id_progprioritas<>', 0)
                                        ->get()->getRowArray();
                                    $AUnggul = $this->kegpokokmodal->selectCount('vol_target')
                                        ->where('tahun', $listsubkeg['tahun'])
                                        ->where('kd_subunit', $listsubkeg['kd_subunit'])
                                        ->where('kd_subkegiatan', $listsubkeg['kd_subkegiatan'])
                                        ->where('id_progunggulan<>', 0)
                                        ->get()->getRowArray();
                                    $ATematik = $this->kegpokokmodal->selectCount('vol_target')
                                        ->where('tahun', $listsubkeg['tahun'])
                                        ->where('kd_subunit', $listsubkeg['kd_subunit'])
                                        ->where('kd_subkegiatan', $listsubkeg['kd_subkegiatan'])
                                        ->where('id_progtematik<>', 0)
                                        ->get()->getRowArray();
                                    ?>
                                    <?= esc($ASasaran['id_kp'] . '   Aktifitas' . '   termapping ke Sasaran RPJMD') ?><br>
                                    <?= esc($AUnggul['vol_target'] . '   Aktifitas' . '   termapping ke Program Unggulan') ?><br>
                                    <?= esc($ATematik['vol_target'] . '   Aktifitas' . '   termapping ke Program Tematik') ?>
                                </td>
                                <td></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    <!--end::Row-->
</div>
<!--end::Container-->
<?= $this->endSection() ?>