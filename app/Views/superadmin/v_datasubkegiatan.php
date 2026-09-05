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
    <!-- /.card-header -->
    <?php
    if ($data == 'subkeg') {
    ?> <!--begin::Row-->
        <div class="row">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Daftar Sub Kegiatan <?= esc($nm_opd) ?> dalam APBD TA <?= esc($tahun) ?></h3>
                </div>
                <div class="card-header">
                    <button onclick="history.back()" class="btn btn-outline-primary mb-2">Kembali</button>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <div class="info-text">
                        Menampilkan <?= count($dataskopd) ?> dari <?= $totalRecords ?> total data
                    </div>
                    <div class="pagination-container">
                        <?= $pager ?>
                    </div>

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr style="text-align: center; vertical-align: middle;">
                                <th>No</th>
                                <th>Sub Kegiatan</th>
                                <th>Pagu/Anggaran (Rp)</th>
                                <th>Realisasi s.d Bulan <?= esc($periode) ?> (Rp)</th>
                                <th>% Realisasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($dataskopd as $key => $listsubkeg) { ?>
                                <tr>
                                    <td><?= esc($key + 1) ?></td>
                                    <td>
                                        <?= esc('Kode Sub Kegiatan:' . $listsubkeg['kd_subkegiatan']) ?><br>
                                        <?= esc($listsubkeg['nm_subkegiatan']) ?>
                                    </td>
                                    <td align="right">
                                        <?= esc(number_format($listsubkeg['pagu_rincian'], 0, '.', ',')) ?>
                                    </td>
                                    <td align="right">
                                        <?php
                                        // // ->Data Realisasi Kegiatan
                                        $datarealisasiSK = $this->realisasilrfkrinci->select('*')
                                            ->selectSum('realisasi')
                                            //->selectSum('pagu_rincian')
                                            ->where('tahun', $tahun)
                                            ->where('bulan', $periode)
                                            ->where('kd_sub_unit', $listsubkeg['kd_sub_unit'])
                                            ->where('kd_urusan', $listsubkeg['kd_urusan'])
                                            ->where('kd_program', $listsubkeg['kd_program'])
                                            ->where('kd_kegiatan', $listsubkeg['kd_kegiatan'])
                                            ->where('kd_subkegiatan', $listsubkeg['kd_subkegiatan'])
                                            ->groupBy('kd_subkegiatan')
                                            ->where('delete_at=', 0)
                                            ->get()
                                            ->getRowArray();
                                        // echo dd($datarealisasiK);
                                        if (!$datarealisasiSK) {
                                            echo esc('0');
                                        } else {

                                            $paguSK = $listsubkeg['pagu_rincian'];
                                            $realSK = $datarealisasiSK['realisasi'] ?? null;
                                            $persentaseSK = ($realSK / $paguSK) * 100 ?? NUll;
                                            //echo $pagu . '///' . $real;
                                            if ($realSK > $paguSK) {
                                                echo "Realisasi bulan ini melebihi pagu";
                                            } else {
                                                echo esc(number_format($realSK, 0, ',', '.'));
                                            }
                                            if ($persentaseSK >= 30.00 && $persentaseSK < 60.00) {
                                                $warna = 'bg-warning';
                                            } else {
                                                if ($persentaseSK >= 60.00 && $persentaseSK < 85.00) {
                                                    $warna = 'bg-info';
                                                } else {
                                                    $warna = 'bg-success';
                                                }
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (!$datarealisasiSK) {
                                            echo esc('0');
                                        } else {
                                            echo esc(number_format($persentaseSK, 2, ',', '.') . '%');
                                        ?>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped <?= esc($warna) ?>" role="progressbar" style="width: <?= esc(number_format($persentaseSK, 0, ',', '.') . '%') ?>" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        <?php }
                                        ?>

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

    <?php } ?>
    <?php
    if ($data == 'belanja') {
    ?>
        <!--begin::Row-->
        <div class="row">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Daftar Sub Kegiatan <?= esc($nm_opd) ?> dalam APBD TA <?= esc($tahun) ?></h3>
                </div>
                <div class="card-header">
                    <button onclick="history.back()" class="btn btn-outline-primary mb-2">Kembali</button>
                </div>
                <div class="card-body p-0">
                    <div class="info-text">
                        Menampilkan <?= count($dataskopd) ?> dari <?= $totalRecords ?> total data
                    </div>
                    <div class="pagination-container">
                        <?= $pager ?>
                    </div>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr style="text-align: center; vertical-align: middle;">
                                <th>No</th>
                                <th>Sub Kegiatan</th>
                                <th>Pagu</th>
                                <th>Jumlah Belanja</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($dataskopd as $key => $listsubkeg) { ?>
                                <tr>
                                    <td><?= esc($key + 1) ?></td>
                                    <td>
                                        <?= esc('Kode Sub Kegiatan:' . $listsubkeg['kd_subkegiatan']) ?><br>
                                        <?= esc($listsubkeg['nm_subkegiatan']) ?>
                                    </td>
                                    <td align="right">
                                        <?= esc('Rp ' . number_format($listsubkeg['pagu_rincian'], 0, '.', ',')) ?>
                                    </td>
                                    <td style="font-size: larger; text-align:center;">
                                        <?php
                                        $jBelOpd = $this->subkegmodel->TotbelOpd($listsubkeg['kd_sub_unit'], $listsubkeg['kd_subkegiatan']);
                                        $jB = count($jBelOpd);
                                        echo esc($jBelOpd['kd_rek_belanja']);
                                        ?>
                                        <a href="<?= hash_url('adminprov', [
                                                        'hal' => 'dataopd',
                                                        'action' => 'Rbelanja',
                                                        'periode' => $periode,
                                                        'kdSU' => $listsubkeg['kd_sub_unit'],
                                                        'kdSK' => $listsubkeg['kd_subkegiatan']
                                                    ]);
                                                    ?>">
                                            <!-- data-bs-toggle="modal" data-bs-target="#staticBackdrop"> -->
                                            <i class="bi bi-eye-fill"></i></a><br>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!--end::Row-->
    <?php } ?>
    <?php
    if ($data == 'Rbelanjasubkeg') {
    ?>
        <!--begin::Row-->
        <div class="row">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Daftar Belanja Sub Kegiatan <?= esc($nm_opd) ?> dalam APBD TA <?= esc($tahun) ?></h3>
                </div>
                <div class="card-header">
                    <button onclick="history.back()" class="btn btn-outline-primary mb-2">Kembali</button>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr style="text-align: center; vertical-align: middle;">
                                <th>No</th>
                                <th>Rekening Belanja Sub Kegiatan <?= esc($dBelOpd[0]['nm_subkegiatan']) ?></th>
                                <th>Pagu/Anggaran (Rp)</th>
                                <th>Realisasi s.d Bulan <?= esc($periode) ?> (Rp)</th>
                                <th>Capaian Realisasi Anggaran (%) </th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($dBelOpd as $key => $listsubkeg) { ?>
                                <tr>
                                    <td><?= esc($key + 1) ?></td>
                                    <td>
                                        <?= esc('Kode Rekening Belanja:' . $listsubkeg['kd_rek_belanja']) ?><br>
                                        <?= esc($listsubkeg['nm_rekening']) ?>
                                    </td>
                                    <td align="right">
                                        <?= esc('Rp ' . number_format($listsubkeg['pagu_rincian'], 0, '.', ',')) ?>
                                    </td>
                                    <td align="right">
                                        <?php
                                        // // ->Data Realisasi Kegiatan
                                        $datarealisasiB = $this->realisasilrfkrinci->select('*')
                                            ->selectSum('realisasi')
                                            //->selectSum('pagu_rincian')
                                            ->where('tahun', $tahun)
                                            ->where('bulan', $periode)
                                            ->where('kd_sub_unit', $listsubkeg['kd_sub_unit'])
                                            ->where('kd_urusan', $listsubkeg['kd_urusan'])
                                            ->where('kd_program', $listsubkeg['kd_program'])
                                            ->where('kd_kegiatan', $listsubkeg['kd_kegiatan'])
                                            ->where('kd_subkegiatan', $listsubkeg['kd_subkegiatan'])
                                            ->where('kd_rek_belanja', $listsubkeg['kd_rek_belanja'])
                                            // ->groupBy('kd_subkegiatan')
                                            ->where('delete_at=', 0)
                                            ->get()
                                            ->getRowArray();
                                        // echo dd($datarealisasiK);
                                        if (!$datarealisasiB) {
                                            echo esc('0');
                                        } else {

                                            $paguB = $listsubkeg['pagu_rincian'];
                                            $realB = $datarealisasiB['realisasi'] ?? null;
                                            $persentaseB = ($realB / $paguB) * 100 ?? NUll;
                                            //echo $pagu . '///' . $real;
                                            if ($realB > $paguB) {
                                                echo "Realisasi bulan ini melebihi pagu";
                                            } else {
                                                echo esc(number_format($realB, 0, ',', '.'));
                                            }
                                            if ($persentaseB >= 30.00 && $persentaseB < 60.00) {
                                                $warna = 'bg-warning';
                                            } else {
                                                if ($persentaseB >= 60.00 && $persentaseB < 85.00) {
                                                    $warna = 'bg-info';
                                                } else {
                                                    $warna = 'bg-success';
                                                }
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (!$datarealisasiB) {
                                            echo esc('0');
                                        } else {
                                            echo esc(number_format($persentaseB, 2, ',', '.') . '%');
                                        ?>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped <?= esc($warna) ?>" role="progressbar" style="width: <?= esc(number_format($persentaseB, 0, ',', '.') . '%') ?>" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>

                                        <?php }
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!--end::Row-->
    <?php } ?>
    <?php
    if ($data == 'DAktifitasopd') {
    ?>
        <!--begin::Row-->
        <div class="row">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Daftar Aktifitas Kegiatan <?= esc($nm_opd) ?> dalam APBD TA <?= esc($tahun) ?></h3>
                </div>
                <div class="card-header">
                    <button onclick="history.back()" class="btn btn-outline-primary mb-2">Kembali</button>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr style="text-align: center; vertical-align: middle;">
                                <th>No</th>
                                <th>Uraian Aktifitas</th>
                                <th>Hasil yang akan dicapai</th>
                                <th>Termapping pada :</th>
                                <th>Progres Pelaksanaan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($aktifitasopd as $key => $listdata) { ?>
                                <tr>
                                    <td><?= esc($key + 1) ?></td>
                                    <td>
                                        <?= esc($listdata['vol_target'] . '  ') ?> <?= esc($listdata['sat_target']) ?><br>
                                        <?= esc($listdata['uraian_target']) ?>
                                    </td>
                                    <td>
                                        <?= esc($listdata['hasil']) ?>
                                    </td>
                                    <td>
                                        <?php
                                        $qsasaran = $this->kegpokokmodal->DataPerIdKegPokok($listdata['id_kp']);
                                        echo esc('Sasaran RPJMD : ' . $qsasaran['nm_progprioritas']);
                                        ?>
                                        <?php
                                        if ($listdata['id_progunggulan'] == '0') {
                                            echo "<br>";
                                            // echo 'asas';
                                        } else {
                                            echo "<br>";
                                            $qunggulan = $this->kegpokokmodal->DataPerIdKegPokokUnggulan($listdata['id_kp']);
                                            echo esc('Program Unggulan RPJMD : ' . $qunggulan['nm_progunggulan']);
                                        }
                                        if ($listdata['id_progtematik'] == '0') {
                                            echo "<br>";
                                            // echo 'asas';
                                        } else {
                                            echo "<br>";
                                            $qtematik = $this->kegpokokmodal->DataPerIdKegPokokTematik($listdata['id_kp']);
                                            echo esc('Program Tematik RPJMD : ' . $qtematik['nm_tematik']);
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $qRAktifitas = count($this->rkegpokokmodal->DataPerKegPokok($listdata['id_kp'], $listdata['kd_subunit'], $listdata['kd_subkegiatan']));
                                        echo esc('Terealisasi  ' . $qRAktifitas . ' kali');
                                        ?>
                                        <br>
                                        <a href="<?= hash_url('adminprov', [
                                                        'hal' => 'dataopd',
                                                        'action' => 'RAktifitas',
                                                        // 'periode' => $periode,
                                                        'kdSU' => $listdata['kd_subunit'],
                                                        'kdSK' => $listdata['kd_subkegiatan'],
                                                        'kdA' => $listdata['id_kp'],
                                                    ]);
                                                    ?>">
                                            <!-- data-bs-toggle="modal" data-bs-target="#staticBackdrop"> -->
                                            <i class="bi bi-eye-fill">Detail</i></a><br>

                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!--end::Row-->
    <?php } ?>
</div>

<!--end::Container-->
<?= $this->endSection() ?>