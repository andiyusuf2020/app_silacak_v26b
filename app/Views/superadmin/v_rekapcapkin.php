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
                            <th rowspan="2">No</th>
                            <th rowspan="2">Kode Perangkat Daerah</th>
                            <th rowspan="2">Nama Perangkat Daerah</th>
                            <th colspan="3">Satus Data Inputan</th>
                            <th rowspan="2">Action</th>
                        </tr>
                        <tr style="text-align: center; vertical-align: middle;">
                            <th>Mapping Subkegiatan</th>
                            <th>Mapping Aktifitas dan Realisasi</th>
                            <th>Mapping Dokumentasi Realisasi Aktifitas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listopd as $key => $opd) { ?>
                            <tr class="align-middle">
                                <td> <?= esc($key + 1) ?></td>
                                <td>
                                    <?= esc($opd['kd_sub_unit'])  ?>
                                </td>
                                <td><?= esc($opd['nm_sub_unit'])  ?></td>
                                <td>
                                    <?php
                                    $cekpamsubkeg = $this->targetsubkegmodel
                                        ->selectCount('kd_subkegiatan')->where('kd_subunit', $opd['kd_sub_unit'])
                                        ->groupBy('kd_subunit')->get()->getRowArray();
                                    if (empty($cekpamsubkeg)) {
                                    } else {
                                        echo esc('berproses dengan Sub Kegiatan yang termapping berjumlah: ');
                                        echo '<strong>';
                                        echo esc($cekpamsubkeg['kd_subkegiatan'] ?? null);
                                        echo '  Subkegiatan';
                                        echo '</strong>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $cekaktifitas = $this->kegpokokmodal
                                        ->selectCount('id_kp')->where('kd_subunit', $opd['kd_sub_unit'])
                                        ->groupBy('kd_subunit')->get()->getRowArray();
                                    if (empty($cekaktifitas)) {
                                    } else {
                                        echo esc('berproses dengan Aktifitas/Kegiatan Pokok yang termapping berjumlah: ');
                                        echo '<strong>';
                                        echo esc($cekaktifitas['id_kp'] ?? null);
                                        echo '  Aktifitas';
                                        echo '</strong>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $cekRaktifitas = $this->rdkegpokokmodal
                                        ->selectCount('gambar')->where('kd_subunit', $opd['kd_sub_unit'])
                                        ->where('bulan', $bulan)
                                        ->groupBy('kd_subunit')->get()->getRowArray();
                                    if (empty($cekRaktifitas)) {
                                        echo esc('tidak ada data');
                                    } else {
                                        echo esc('berproses dengan jumlah realisasi aktifitas  yang terdokumentasi berjumlah: ');
                                        echo '<strong>';
                                        echo esc($cekRaktifitas['gambar'] ?? null);
                                        echo '  Dokumentasi';
                                        echo '</strong>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a href="<?= hash_url('adminprov', [
                                                    'hal' => 'rekapcek',
                                                    'action' => 'pilihprogram',
                                                    'periode' => esc($bulan),
                                                    'kdSU' => $opd['kd_sub_unit']
                                                ]);
                                                ?>">
                                        <button
                                            type="button"
                                            class="btn btn-outline-primary"
                                            aria-expanded="false">
                                            <i class="bi bi-database-check"></i></button>
                                        </button>
                                    </a>
                                    <a href="<?= hash_url('adminprov', [
                                                    'hal' => 'rekapcek',
                                                    'action' => 'listsubkegiatan',
                                                    'periode' => esc($bulan),
                                                    'kdSU' => $opd['kd_sub_unit']
                                                ]);
                                                ?>" data-bs-toggle="tooltip" data-bs-title="Subkegiatan Termapping">
                                        <button
                                            type="button"
                                            class="btn btn-outline-primary"
                                            aria-expanded="false">
                                            <i class="bi bi-printer-fill"></i></button>
                                        </button>
                                    </a>
                                </td>
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