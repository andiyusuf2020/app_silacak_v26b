<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<?php

use App\Models\LrfkProvModel\RealisasiSubKegModel;

use App\Models\LrfkProvModel\SubKegModel; // as lrfkModel;
use App\Models\LrfkProvModel\TaRealisasiRinciModel;

$this->subkegmodel = new SubKegModel();
$this->realisasilrfk = new RealisasiSubKegModel();
$this->realisasilrfkrinci = new TaRealisasiRinciModel();


?>
<!--begin::Container-->
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">

        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="alert alert-danger" role="alert">
                    <ul>
                        Jadwal input data yang aktif saat ini adalah Bulan : <?= $jadwalaktif['bulan'] ?><br>
                        PERHATIAN !!
                        pengisian data realisasi anggaran perBulan adalah Akumulasi bulan sebelumnya <br>
                        contoh : <br>jika realisasi anggaran sub kegiatan diawali bulan Maret maka data pada bulan April
                        minimal sama dengan realisasi bulan Maret tidak boleh kosong dan bulan-bulan seterusnya.
                    </ul>
                </div>

                <div class="card-header border-0">
                    <h3 class="card-title">Realisasi Anggara per-Bulan <?= 'SubKegiatan ' . esc($subkeg['nm_subkegiatan']); ?> </h3>
                    <br> Total Pagu Subkegiatan :: <?= 'Rp  ' . esc(number_format($subkeg['pagu_rincian'], 2, ',', '.')) ?>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th>Realisasi</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($jadwal as $key => $rowjadwal) { ?>
                                <tr>
                                    <td>
                                        <?= esc($rowjadwal['bulan']) ?>
                                    </td>
                                    <td><?php
                                        $this->realisasilrfk = new RealisasiSubKegModel();
                                        // ->Data Kegiatan APBD
                                        $datarealisasi1 = $this->realisasilrfkrinci
                                            // ->select('*')
                                            ->selectSum('pagu_rincian')
                                            ->selectSum('realisasi')
                                            ->where('tahun', $rowjadwal['tahun'])
                                            ->where('bulan', $rowjadwal['bulan'])
                                            ->where('kd_sub_unit', $subkeg['kd_sub_unit'])
                                            ->where('kd_subkegiatan', $subkeg['kd_subkegiatan'])
                                            ->where('delete_at=', 0)
                                            ->groupBy('kd_subkegiatan')
                                            ->get()
                                            ->getRowArray();
                                        $pagu1 = $datarealisasi1['pagu_rincian'] ?? null;
                                        $real1 = $datarealisasi1['realisasi'] ?? null;

                                        if ($pagu1 == 0) {
                                            $persentase1 = '0';
                                        } else {
                                            $persentase1 = ($real1 / $pagu1) * 100 ?? NUll;
                                        }
                                        if ($datarealisasi1 == Null) {
                                        ?>
                                            <i class="text-danger">"Realisasi bulan ini Kosong"</i>
                                            <?php
                                        } else {
                                            if ($real1 > $pagu1) {
                                            ?>
                                                <i class="text-danger">"Realisasi bulan ini melebihi pagu"</i>
                                        <?php
                                            } else {
                                                echo 'Rp  ' . esc(number_format($real1, 2, ',', '.'));
                                                // echo $real1; //$datarealisasi1['pagu_rincian'];
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?= esc(number_format($persentase1, 2, ',', '.')); ?>
                                    </td>
                                    <td>
                                        <a href="#" class="text-secondary"> <i class="bi bi-search"></i> </a>
                                    </td>
                                </tr>
                            <?php }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card -->
        </div>
        <!-- /.card -->
    </div>
    <!--end::Row-->
</div>

<!--end::Container-->
<?= $this->endSection() ?>