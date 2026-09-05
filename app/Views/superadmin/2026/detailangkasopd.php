<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>

<?php

use App\Models\DataApbdModel\RealApbdModel;

use App\Models\DataApbdModel\AngkasModel;

$this->realapbd = new RealApbdModel();
$this->angkasmodel = new AngkasModel();
?>
<!--begin::Container-->
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Data Anggaran Kas <?= esc($dataopd[0]['NAMA_UNIT_SKPD']) ?> Provinsi Lampung </h3> <br>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0" style="height: 600px;">
                <table class="table table align-middle table-head-fixed table-success table-striped table-bordered text-wrap">
                    <thead>
                        <tr class="align-bottom">
                            <th style="width: 10px">#</th>
                            <th>Kode Sub Kegiatan/<br> Nama Sub Kegiatan </th>
                            <?php
                            foreach ($listbulan as $key => $value) { ?>
                                <th><?= esc($value['bulan']) ?></th>
                            <?php } ?>
                            <th>Jumlah Anggaran (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($dataopd as $key => $value) { ?>
                            <tr class="align-bottom">
                                <td><?= esc($key + 1) ?></td>
                                <td><strong><?= esc($value['KODE_SUB_GIAT']) ?></strong><br>
                                    <strong><?= esc($value['NAMA_SUB_GIAT']) ?></strong>
                                </td>
                                <?php
                                foreach ($listbulan as $key => $valuebln) {
                                    $angkasperbulan = $this->angkasmodel
                                        ->getDataAngkasSubGiatDetail($value['TAHUN'], $valuebln['bulan'], $value['NAMA_UNIT_SKPD'], $value['NAMA_SUB_GIAT']);
                                    // ->getDataAngkasPerGiat($value['TAHUN'], $value['KODE_UNIT_SKPD'], $value['KODE_SUB_GIAT']);
                                    // echo dd($angkasperbulan);
                                    // echo dd($value['TAHUN'] . '-' . $valuebln['bulan'] . '-' . $value['NAMA_UNIT_SKPD'] . '-' . $value['KODE_SUB_GIAT']);
                                    if ($angkasperbulan == null) {
                                        echo '<td>-</td>';
                                    } else {
                                        echo '<td>' . esc(number_format($angkasperbulan['total_angkas'], 0, ',', '.'))
                                            . '</td>';
                                    }
                                }
                                ?>
                                <td><strong>
                                        <?php
                                        $totalAngkasSubGiat = $this->angkasmodel->getDataTotalAngkasSubGiat($value['TAHUN'], $value['NAMA_UNIT_SKPD'], $value['NAMA_SUB_GIAT']);
                                        if ($totalAngkasSubGiat == null) {
                                            echo '-';
                                        } else {
                                            echo esc(number_format($totalAngkasSubGiat['total_angkas'], 0, ',', '.'));
                                        } ?>
                                    </strong>
                                    </strong>
                                </td>
                            </tr>
                        <?php }  ?>
                    </tbody>

                    <tfoot>
                        <tr class="align-bottom">
                            <th colspan="2">JUMLAH</th>
                            <?php
                            foreach ($listbulan as $key => $valuebln) {
                                $jumlahAnggaranPerBulan = $this->angkasmodel->getTotalDataAngkasPerBulanOPD($value['TAHUN'], $value['KODE_UNIT_SKPD'], $valuebln['bulan']);
                                if ($jumlahAnggaranPerBulan == null) {
                                    echo '<th>-</th>';
                                } else {
                                    echo '<th>' . esc(number_format($jumlahAnggaranPerBulan['total_angkas'], 0, ',', '.')) . '</th>';
                                }
                            }
                            ?>
                            <th><strong>
                                    <?php
                                    $totalJumlah = 0;
                                    foreach ($listbulan as $key => $valuebln) {
                                        $jumlahAnggaranPerBulan = $this->angkasmodel->getTotalAngkasPerBulanAll($value['TAHUN'], $valuebln['bulan']);
                                        if ($jumlahAnggaranPerBulan != null) {
                                            $totalJumlah += $jumlahAnggaranPerBulan['total_anggaran'];
                                        }
                                    }
                                    echo esc(number_format($totalJumlah, 0, ',', '.'));
                                    ?>
                                </strong></th>
                        </tr>
                    </tfoot>
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