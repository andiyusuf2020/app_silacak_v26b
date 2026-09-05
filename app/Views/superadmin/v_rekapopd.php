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
            <div class="card-header">
                <button onclick="history.back()" class="btn btn-outline-primary mb-2">Kembali</button>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <div class="info-text">
                    Menampilkan <?= count($listopd) ?> dari <?= $totalRecords ?> total data
                </div>
                <div class="pagination-container">
                    <?= $pager ?>
                </div>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr style="text-align: center; vertical-align: middle;">
                            <th rowspan="2">No</th>
                            <th rowspan="2">Kode Perangkat Daerah</th>
                            <th rowspan="2">Nama Perangkat Daerah</th>
                            <th rowspan="2">PAGU APBD</th>
                            <th rowspan="2">Jml Item SubKegiatan dalam APBD TA <?= esc($tahun) ?></th>
                            <th rowspan="2">Jml Item Belanja dalam APBD</th>
                            <th rowspan="2">Jumlah Aktifitas Kegiatan yang direncanakan</th>
                            <th colspan="4">CAPAIAN % </th>
                            <th rowspan="2">Keterangan</th>
                        </tr>
                        <tr style="text-align: center; vertical-align: middle;">
                            <th>Realisasi Anggaran s.d <?= esc($bulan) ?></th>
                            <th>Kinerja PD Anggaran berdasarkan Belanja</th>
                            <th>Kinerja PD Berdasarkan Progres Pelaksanaan Aktifitas Kegiatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($listopd as $key => $opd) { ?>
                            <tr>
                                <td><?= esc($key + 1) ?></td>
                                <td><?= esc($opd['kd_sub_unit']) ?></td>
                                <td><?= esc($opd['nm_sub_unit']) ?></td>
                                <td style="text-align:right;"><?= esc(number_format($opd['pagu_rincian'], 0, '.', '.')) ?></td>
                                <td style="text-align:right;">
                                    <?php
                                    $jSubKeg = $this->subkegmodel->subkegiatanperopd($opd['kd_sub_unit']);
                                    $j = count($jSubKeg);
                                    echo esc($j);
                                    ?>
                                    <!-- <span class="badge rounded-pill text-bg-primary"></span> -->
                                    <!-- <button type="button" class="btn btn-outline-primary mb-2"><i class="bi bi-eye-fill"></i></button> -->
                                    <a href="<?= hash_url('adminprov', [
                                                    'hal' => 'dataopd',
                                                    'action' => 'subkeg',
                                                    'periode' => $bulan,
                                                    'kdSU' => $opd['kd_sub_unit']
                                                ]);
                                                ?>">
                                        <i class="bi bi-eye-fill"></i></a><br>
                                </td>
                                <td>
                                    <?php
                                    $jBelOpd = $this->subkegmodel->TotbelOpd($opd['kd_sub_unit']);
                                    $jB = count($jBelOpd);
                                    echo esc($jBelOpd['kd_rek_belanja']);
                                    ?>
                                    <a href="<?= hash_url('adminprov', [
                                                    'hal' => 'dataopd',
                                                    'action' => 'belanja',
                                                    'periode' => $bulan,
                                                    'kdSU' => $opd['kd_sub_unit']
                                                ]);
                                                ?>">
                                        <i class="bi bi-eye-fill"></i></a><br>
                                </td>
                                <td style="text-align:right;">
                                    <?php
                                    $qjaktifitas = $this->kegpokokmodal->selectCount('vol_target')
                                        ->select('id_kp')
                                        ->where('tahun', $opd['tahun'])
                                        ->where('kd_subunit', $opd['kd_sub_unit'])
                                        ->groupBy('kd_subunit')
                                        ->get()
                                        ->getRowArray();
                                    echo esc($qjaktifitas['vol_target'] ?? null);
                                    ?>
                                    <a href="<?= hash_url('adminprov', [
                                                    'hal' => 'dataopd',
                                                    'action' => 'aktifitas',
                                                    'periode' => $bulan,
                                                    'kdSU' => $opd['kd_sub_unit']
                                                ]);
                                                ?>">
                                        <i class="bi bi-eye-fill"></i></a><br>

                                </td>
                                <td>
                                    <?php

                                    $jROpd = $this->realisasilrfkrinci->totalrealperOpd($opd['kd_sub_unit'], $tahun, $bulan);
                                    if (!$jROpd) {
                                        $persenRA = 0;
                                    } else {
                                        $persenRA = $jROpd['realisasi'] / $opd['pagu_rincian'] * 100;
                                    }
                                    echo esc(number_format($persenRA, 2, ','));

                                    if ($persenRA >= 30.00 && $persenRA < 60.00) {
                                        $warna = 'bg-warning';
                                    } else {
                                        if ($persenRA >= 60.00 && $persenRA < 85.00) {
                                            $warna = 'bg-info';
                                        } else {
                                            $warna = 'bg-success';
                                        }
                                    }
                                    ?>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped <?= esc($warna) ?>" role="progressbar" style="width: <?= esc(number_format($persenRA, 0, ',', '.') . '%') ?>" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>


                                </td>

                                <td>

                                    <?php
                                    $datarincireal = $this->realisasilrfkrinci
                                        ->select('*')
                                        //  ->select('(format((realisasi / pagu_rincian),2)) as isi')
                                        ->select('((realisasi / pagu_rincian)) as isi')
                                        ->where('tahun', $tahun)
                                        ->where('bulan', $bulan)
                                        ->where('kd_sub_unit', $opd['kd_sub_unit'])
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
                                        echo " 0 ";
                                        $croopd = 0;
                                    ?>
                                    <?php } else {
                                        $croopd = (pow((float)$mul, 1 / count($da))) * 100;
                                        //$croopd = ((pow((float)$mul, 1 / count($da)))) * 100;
                                        echo esc(number_format($croopd, 2, ".", ","));
                                    }
                                    if ($croopd >= 30.00 && $croopd < 60.00) {
                                        $warna = 'bg-warning';
                                    } else {
                                        if ($croopd >= 60.00 && $croopd < 85.00) {
                                            $warna = 'bg-info';
                                        } else {
                                            $warna = 'bg-success';
                                        }
                                    }
                                    ?>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped <?= esc($warna) ?>" role="progressbar" style="width: <?= esc(number_format($croopd, 0, ',', '.') . '%') ?>" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>

                                </td>
                                <td>
                                    <?php
                                    $qJmlRAktifitas = $this->rkegpokokmodal->selectCount('id_r')
                                        ->where('tahun', $opd['tahun'])
                                        ->where('kd_subunit', $opd['kd_sub_unit'])
                                        // ->where('id_kegpokok', $qjaktifitas['id_kp'] ?? null)
                                        ->groupBy('kd_subunit')
                                        ->get()
                                        ->getRowArray();
                                    if (!$qJmlRAktifitas) {
                                        $persenPA = 0;
                                    } else {
                                        $persenPA = $qJmlRAktifitas['id_r'] / $qjaktifitas['vol_target'] * 100;
                                    }
                                    echo esc(number_format($persenPA, 2, ','));
                                    if ($persenPA >= 30.00 && $persenPA < 60.00) {
                                        $warnaPA = 'bg-warning';
                                    } else {
                                        if ($persenPA >= 60.00 && $persenPA < 85.00) {
                                            $warnaPA = 'bg-info';
                                        } else {
                                            $warnaPA = 'bg-success';
                                        }
                                    }
                                    //echo esc(number_format($persenPA, 2, ','));
                                    ?>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped <?= esc($warnaPA) ?>" role="progressbar" style="width: <?= esc(number_format($persenPA, 0, ',', '.') . '%') ?>" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>

                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            <!-- Pagination -->

        </div>
    </div>
    <!--end::Row-->
</div>
<!--end::Container-->
<?= $this->endSection() ?>