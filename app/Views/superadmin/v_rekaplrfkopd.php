<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<!--begin::Container-->
<?php

use App\Models\LrfkProvModel\SubKegModel; // as lrfkModel;
use App\Models\LrfkProvModel\TaRealisasiRinciModel;

$this->subkegmodel = new SubKegModel();
$this->realisasilrfkrinci = new TaRealisasiRinciModel();

?>
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">

        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Daftar Perangkat Daerah yang berproses input data LRFK Bulan <?= esc($bulan) ?></h3>
            </div>
            <div class="card-header">
                <a href="<?= hash_url('adminprov', ['hal' => 'cetakrekaplrfk', 'action' => 'all', 'periode' => $bulan]);
                            ?>" target='blank'>
                    <button type="button" class="btn btn-outline-primary mb-2"><i class="bi bi-printer-fill"></i> Cetak Laporan LRFK</button>
                </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr style="text-align: center; vertical-align: middle;">
                            <th rowspan="2">No</th>
                            <th rowspan="2">Kode Perangkat Daerah</th>
                            <th rowspan="2">Nama Perangkat Daerah</th>
                            <th colspan="3">ANGGARAN</th>
                            <th colspan="2">Capaian Kinerja Anggaran berdasarkan belanja ditiap Sub Kegiatan</th>
                        </tr>
                        <tr style="text-align: center; vertical-align: middle;">
                            <th>Pagu (Rp)</th>
                            <th>Realisasi (Rp)</th>
                            <th>%</th>
                            <th>Capaian (%)</th>
                            <th>Kinerja</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datarekaplrfk as $key => $datalrfk) { ?>
                            <tr class="align-middle">
                                <td> <?= esc($key + 1) ?></td>
                                <td><?= esc($datalrfk['kd_sub_unit'])  ?></td>
                                <td><?= esc($datalrfk['nm_sub_unit'])  ?></td>
                                <td style="text-align: right;">
                                    <?php
                                    $totpagu = $this->subkegmodel->totpaguopd2($datalrfk['nm_sub_unit']);
                                    echo esc(number_format($totpagu['pagu_rincian'], 0, ',', '.'));
                                    ?>
                                </td>
                                <td style="text-align: right;">
                                    <?= esc(number_format($datalrfk['realisasi'], 0, ',', '.')) ?></td>
                                <td style="text-align: right;">
                                    <?php
                                    $persen = $datalrfk['realisasi'] / $totpagu['pagu_rincian'] * 100;
                                    echo esc(number_format($persen, 2, ',', '.'));
                                    ?>
                                </td>
                                <td style="text-align: right;">
                                    <?php
                                    $datarincireal = $this->realisasilrfkrinci
                                        ->select('*')
                                        //  ->select('(format((realisasi / pagu_rincian),2)) as isi')
                                        ->select('((realisasi / pagu_rincian)) as isi')
                                        ->where('tahun', $datalrfk['tahun'])
                                        ->where('bulan', $datalrfk['bulan'])
                                        ->where('kd_sub_unit', $datalrfk['kd_sub_unit'])
                                        ->where('(format((realisasi / pagu_rincian),2)) >', 0.01)
                                        ->where('realisasi<>', 0)
                                        ->get()
                                        ->getResultArray();
                                    $da = $datarincireal;
                                    $mul = 1;
                                    foreach ($da as $i => $na)
                                        // $d = 1 + $na['isi'];
                                        $mul = $i == 0 ? $na['isi'] : $mul * $na['isi'];
                                    // $mul = $i == 0 ? $d : $mul * $d;
                                    if (count($da) == 0) {
                                        echo " 0 "; ?>
                                    <?php } else {
                                        $croopd = (pow((float)$mul, 1 / count($da))) * 100;
                                        //$croopd = ((pow((float)$mul, 1 / count($da)))) * 100;
                                        echo esc(number_format($croopd, 2, ".", ","));
                                    }
                                    // foreach ($datarincireal as $key => $rowX) {
                                    //     // echo '//' . $rowX['isi'] . '//';
                                    // }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if ($croopd < $persen) {
                                        echo esc('Berkinerja Baik');
                                    }
                                    if ($croopd > $persen) {
                                        echo esc('Berkinerja Sangat Baik');
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"><strong>Jumlah Total</strong></td>
                            <td>
                                <strong><?= esc(number_format($totapbd['pagu_rincian'], 0, ',', '.')) ?></strong>
                            </td>
                            <td><strong><?= esc(number_format($datatotalpagu['realisasi'], 0, ',', '.')) ?></strong></td>
                            <td><strong>
                                    <?php
                                    $persentotal = $datatotalpagu['realisasi'] / $totapbd['pagu_rincian'] * 100;
                                    ?>
                                    <?= esc(number_format($persentotal, 2, ',', '.') . '%') ?>
                                </strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    <!--end::Row-->
</div>
<!--end::Container-->

<?= $this->endSection() ?>