<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>

<?php

use App\Models\DataApbdModel\PendApbdModel;
use App\Models\DataApbdModel\RealPendApbdModel;

$this->pendapbd = new PendApbdModel();
$this->realpendapbd = new RealPendApbdModel();

?>
<!--begin::Container-->
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Data Perangkat Daerah Pengelola Pendapatan APBD TA <?= esc($tahunaktif) ?> </h3> <br>
                <a href="<?= hash_url('adminprov/apbdopd/', [
                                'hal' => 'cetakrekap',
                                'action' => 'pendapatan',
                                // 'kdSU' => $value['kd_sub_unit'],
                                // 'kdU' => $value['kd_urusan']
                            ]);
                            ?>" target='blank'>
                    <button type="button" class="btn btn-outline-primary mb-2">Cetak Rekap Pendapatan OPD</button>
                </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0" style="height: 600px;">
                <table class="table table-head-fixed table-success table-striped text-wrap">
                    <thead>
                        <tr class="align-middle">
                            <th style="width: 10px">#</th>
                            <th>Kode Sub Unit</th>
                            <th>Perangkat Daerah</th>
                            <th>Target Pagu Pendapatan<br>Rp.</th>
                            <th>Realisasi Pendapatan<br>Rp.
                            </th>
                            <th>Capaian Realisasi Pendapatan<br>%</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($opdpendapatan as $key => $value) { ?>
                            <tr class="align-middle">
                                <td><?= esc($key + 1) ?> </td>
                                <td>
                                    <?= esc($value['KODE_OPD']) ?>
                                </td>
                                <td>
                                    <?= esc($value['NAMA_OPD']) ?>
                                </td>
                                <td>
                                    <?= number_format(esc($value['total_pagu']), 0, ',', '.') ?>
                                </td>
                                <td>
                                    <?php
                                    $datarealisasi = $this->realpendapbd->getDataRealisasiPendapatan($tahunaktif, $bulan, $value['NAMA_OPD']);
                                    if ($datarealisasi == null) {
                                        // $datarealisasi['total_realisasi_pend'] = 0;
                                        echo '0';
                                    } else {
                                        echo number_format(esc($datarealisasi['total_realisasi_pend']), 0, ',', '.');
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if ($datarealisasi == null || $value['total_pagu'] == 0) {
                                        echo '0';
                                        // $datarealisasi['total_realisasi_pend'] = 0;
                                    } else {
                                        $capaian = ($datarealisasi['total_realisasi_pend'] / $value['total_pagu']) * 100;
                                        echo number_format($capaian, 2, ',', '.') . '%';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <div class="progress progress-xs">
                                        <?php
                                        if ($datarealisasi == null || $value['total_pagu'] == 0) {
                                            $capaian = 0;
                                        } else {
                                            $capaian = ($datarealisasi['total_realisasi_pend'] / $value['total_pagu']) * 100;
                                        }
                                        ?>
                                        <div class="progress-bar bg-success" style="width: <?= esc($capaian) ?>%"></div>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>
                    <tfoot>
                        <tr class="align-middle">
                            <th colspan="3">Total</th>
                            <?php
                            // $totalpagu = array_sum(array_column($value, 'total_pagu'));
                            // $totalrealisasi = array_sum(array_column($datarealisasi ?? [], 'total_realisasi_pend'));
                            // echo dd($totalrealisasi);
                            //    echo dd($value['total_pagu']);
                            //     echo dd($datarealisasi['total_realisasi_pend']);
                            $totalpagu = 0;
                            $totalrealisasi = 0;
                            foreach ($opdpendapatan as $item) {
                                $totalpagu += $item['total_pagu'];
                                $datarealisasi = $this->realpendapbd->getDataRealisasiPendapatan($tahunaktif, $bulan, $item['NAMA_OPD']);
                                if ($datarealisasi != null) {
                                    $totalrealisasi += $datarealisasi['total_realisasi_pend'];
                                }
                            }
                            ?>

                            <th><?php echo number_format(esc($totalpagu), 0, ',', '.') ?></th>
                            <th><?php echo number_format(esc($totalrealisasi), 0, ',', '.') ?></th>
                            <th>
                                <?php
                                if ($totalpagu == 0) {
                                    echo '0';
                                } else {
                                    $totalcapaian = ($totalrealisasi / $totalpagu) * 100;
                                    echo number_format($totalcapaian, 2, ',', '.') . '%';
                                }
                                ?>
                            </th>
                            <th>
                                <div class="progress progress-xs">
                                    <?php
                                    if ($totalpagu == 0) {
                                        $totalcapaian = 0;
                                    } else {
                                        $totalcapaian = ($totalrealisasi / $totalpagu) * 100;
                                    }
                                    ?>
                                    <div class="progress-bar bg-success" style="width: <?= esc($totalcapaian) ?>%"></div>
                                </div>
                            </th>
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