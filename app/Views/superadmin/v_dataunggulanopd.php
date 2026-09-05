<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<!--begin::Container-->
<?php

use App\Models\CapkinModel\TaKegPokokCapkinModel;

$this->kegpokokmodal = new TaKegPokokCapkinModel();

use App\Models\CapkinModel\TaRealisasiKegPokokModel;

$this->rkegpokokmodal = new TaRealisasiKegPokokModel();

use App\Models\CapkinModel\TaRKegPokokCapkinModel;

$this->rdkegpokokmodal = new TaRKegPokokCapkinModel();

use App\Models\LrfkProvModel\TaRealisasiRinciModel;

$this->realisasilrfkrinci = new TaRealisasiRinciModel();
?>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Program Unggulan Pembangunan dalam RPJMD yang termapping oleh : <?= esc($dataopd['nm_sub_unit']) ?></h3>
                <br><button onclick="history.back()" class="btn btn-outline-primary mb-2">Kembali</button>

            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Program Unggulan Pembangunan</th>
                            <th>Program termapping</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($progunggulan as $key => $dataprogunggul) { ?>
                            <tr class="align-middle">
                                <td><?= esc($key + 1) ?></td>
                                <td><?= esc($dataprogunggul['nm_progunggulan']) ?></td>
                                <td>
                                    <?php
                                    $datasubkeg = $this->kegpokokmodal
                                        ->select('ta_kegpokok_capkin_apbd2.*')
                                        ->select('ta_subkeg_capkin_apbd.nm_subkegiatan,nm_kegiatan,nm_program')
                                        ->join('ta_subkeg_capkin_apbd', 'ta_subkeg_capkin_apbd.id_sk=ta_kegpokok_capkin_apbd2.id_targetsubkeg')
                                        ->join('ta_mprog_unggulan', 'ta_mprog_unggulan.id_pung=ta_kegpokok_capkin_apbd2.id_progunggulan')
                                        ->where('ta_kegpokok_capkin_apbd2.kd_subunit', $dataprogunggul['kd_subunit'])
                                        ->where('ta_kegpokok_capkin_apbd2.tahun', $dataprogunggul['tahun'])
                                        ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
                                        ->where('ta_kegpokok_capkin_apbd2.id_progunggulan', $dataprogunggul['id_progunggulan'])
                                        ->groupBy('ta_subkeg_capkin_apbd.nm_program')
                                        ->get()
                                        ->getResultArray();
                                    foreach ($datasubkeg as $key => $subkeg) {
                                        echo '<li>';
                                        echo esc($subkeg['nm_program']);
                                        echo '<br>';
                                    } ?>


                                </td>
                                <td>
                                    <a class="page-link"
                                        href="<?= hash_url('adminprov', ['hal' => 'rekapcek', 'action' => 'sasaran', 'kdSU' => $dataopd['kd_sub_unit']]);
                                                ?>">
                                        <button type="button" class="btn btn-outline-primary mb-2"><i class="bi bi-card-checklist"></i></button>
                                    </a>
                                </td>
                            </tr>

                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>