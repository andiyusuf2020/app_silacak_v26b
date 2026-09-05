<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<!--begin::Container-->
<?php

use App\Models\LrfkProvModel\RealisasiSubKegModel;
use App\Models\LrfkProvModel\TaRealisasiRinciModel;

use App\Models\LrfkProvModel\SubKegModel; // as lrfkModel;
$this->subkegmodel = new SubKegModel();
$this->realisasilrfk = new RealisasiSubKegModel();
$this->realisasilrfkrinci = new TaRealisasiRinciModel();

?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Anggaran Pendapatan dan Belanja Tahun Anggaran <?= $tahun . '  Perangkat Daerah  ' . $datauser['sub_unit'] ?></h3>
            </div>
            <div class="alert alert-danger" role="alert">
                <ul>
                    Jadwal input data yang aktif saat ini adalah Bulan : <?= $jadwalaktif['bulan'] ?>
                </ul>
            </div>
            <?php
            foreach ($listprogram as $key => $value) { ?>
                <div class="card-header">
                    <h4 class="text-success"><?= esc($value['nm_urusan']) . '   ' ?></h4>
                    <br>
                    <h3 class="card-title"><?= '  Total Pagu Per-Urusan :: Rp ' . esc(number_format($value['pagu_rincian'], 2, ',', '.')) ?></h3>

                </div>
                <?php if (session()->getFlashdata('message')): ?>
                    <div class="alert alert-primary" role="alert">
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
                <?php
                // ->Data Program APBD
                $dataprogram = $this->subkegmodel->select('*')->selectSUM('pagu_rincian')->where('pagu_rincian<>', '0')
                    ->where('kd_sub_unit', $value['kd_sub_unit'])
                    ->where('kd_urusan', $value['kd_urusan'])
                    ->where('tahun', $tahun)
                    ->groupBy('kd_program')
                    // ->orderBy('kd_sub_unit', 'ASC')
                    ->get()
                    ->getResultArray();
                foreach ($dataprogram as $key => $rowprogram) { ?>
                    <div class="card-header">
                        <h4 class="text-success"><?= '  ' . esc($rowprogram['nm_program']) . '   ' ?></h4>
                        <br>
                        <h3 class="card-title"><?= '  Total Pagu Per-Program :: Rp ' . esc(number_format($rowprogram['pagu_rincian'], 2, ',', '.')) ?></h3>
                    </div> <?php
                            // ->Data Kegiatan APBD
                            $datakegiatan = $this->subkegmodel->select('*')->selectSUM('pagu_rincian')->where('pagu_rincian<>', '0')
                                ->where('kd_sub_unit', $value['kd_sub_unit'])
                                ->where('kd_urusan', $value['kd_urusan'])
                                ->where('kd_program', $rowprogram['kd_program'])
                                ->where('tahun', $tahun)
                                ->groupBy('kd_kegiatan')
                                // ->orderBy('kd_sub_unit', 'ASC')
                                ->get()
                                ->getResultArray();
                            ?><?php
                                foreach ($datakegiatan as $key => $rowkegiatan) { ?>
                    <div class="card-header">
                        <h4 class="text-success"><?= ' Kegiatan: ' . esc($rowkegiatan['nm_kegiatan']) . '   ' ?></h4>
                        <br>
                        <h3 class="card-title"><?= '  Total Pagu Per-Program :: Rp ' . esc(number_format($rowkegiatan['pagu_rincian'], 2, ',', '.')) ?></h3>
                    </div><?php
                                    // ->Data Kegiatan APBD
                                    $datasubkegiatan = $this->subkegmodel->select('*')
                                        ->selectSum('pagu_rincian')
                                        //   ->join()
                                        ->where('pagu_rincian<>', '0')
                                        ->where('kd_sub_unit', $value['kd_sub_unit'])
                                        ->where('kd_urusan', $value['kd_urusan'])
                                        ->where('kd_program', $rowprogram['kd_program'])
                                        ->where('kd_kegiatan', $rowkegiatan['kd_kegiatan'])
                                        ->where('tahun', $tahun)
                                        ->groupBy('kd_subkegiatan')
                                        // ->orderBy('kd_sub_unit', 'ASC')
                                        ->get()
                                        ->getResultArray();
                            ?>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-head-fixed text-wrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Sub Kegiatan</th>
                                    <th>Nama Sub Kegiatan</th>
                                    <th>Pagu</th>
                                    <th>Realisasi s/d <?= $jadwalaktif['bulan'];  ?></th>
                                    <th>%</th>
                                    <th>LRFK (Action)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ($datasubkegiatan as $key => $rowsubkegiatan) { ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td><?= esc($rowsubkegiatan['kd_subkegiatan']) ?></td>
                                        <td><?= esc($rowsubkegiatan['nm_subkegiatan']) ?></td>
                                        <td><?= 'Rp  ' . esc(number_format($rowsubkegiatan['pagu_rincian'], 2, ',', '.')) ?></td>
                                        <td><?php
                                            $this->realisasilrfk = new RealisasiSubKegModel();
                                            // ->Data Kegiatan APBD
                                            $datarealisasi = $this->realisasilrfkrinci->select('*')
                                                ->selectSum('realisasi')
                                                ->where('tahun', $tahun)
                                                ->where('bulan', $jadwalaktif['bulan'])
                                                ->where('kd_sub_unit', $rowsubkegiatan['kd_sub_unit'])
                                                ->where('kd_subkegiatan', $rowsubkegiatan['kd_subkegiatan'])
                                                ->where('tahun', $tahun)
                                                ->groupBy('kd_subkegiatan')
                                                ->where('delete_at=', 0)
                                                ->get()
                                                ->getRowArray();
                                            //echo dd($datarealisasi);
                                            $pagu = $rowsubkegiatan['pagu_rincian'];
                                            $real = $datarealisasi['realisasi'] ?? null;
                                            $persentase = ($real / $pagu) * 100 ?? NUll;

                                            //echo $pagu . '///' . $real;
                                            if ($real > $pagu) {
                                                echo "Realisasi bulan ini melebihi pagu";
                                            } else {
                                                echo 'Rp  ' . esc(number_format($real, 2, ',', '.'));
                                            } ?>
                                        </td>
                                        <td><?= esc(number_format($persentase, 3, ',', '.')); ?></td>
                                        <td><?php
                                            if (!$datarealisasi) { ?>
                                                <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                    <a href="<?= hash_url('lrfkopd/opd/', [
                                                                    'kd' => $rowsubkegiatan['id'],
                                                                    'kdSK' => $rowsubkegiatan['kd_subkegiatan'],
                                                                    'kdSU' => $rowsubkegiatan['kd_sub_unit'],
                                                                    'page' => 'progres',
                                                                    'action' => 'lapor'
                                                                ]);
                                                                ?>" <button type="button" class="btn btn-outline-primary">Input</button></a>
                                                </div>
                                                <div
                                                    class="btn-group mb-2"
                                                    role="group"
                                                    aria-label="Basic checkbox toggle button group">

                                                <?php } else { ?>
                                                    <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                        <a href="<?= hash_url('lrfkopd/opd/', [
                                                                        'kd' => $rowsubkegiatan['id'],
                                                                        'kdSK' => $rowsubkegiatan['kd_subkegiatan'],
                                                                        'kdSU' => $rowsubkegiatan['kd_sub_unit'],
                                                                        'page' => 'progres',
                                                                        'action' => 'lihat'
                                                                    ]);
                                                                    ?>"
                                                            <button type="button" class="btn btn-outline-primary">Ubah<br>/Lihat</button></a>
                                                    </div>
                                                    <div
                                                        class="btn-group mb-2"
                                                        role="group"
                                                        aria-label="Basic checkbox toggle button group">
                                                    <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div> <?php
                                }
                            }
                        } ?>

        <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
<!-- /.row -->
<!--end::Container-->

<?= $this->endSection() ?>