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
                <h3 class="card-title">Data Anggaran Kas Perangkat Daerah Provinsi Lampung </h3> <br>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0" style="height: 600px;">
                <table class="table table align-middle table-head-fixed table-success table-striped table-bordered text-wrap">
                    <thead>
                        <tr class="align-bottom">
                            <th style="width: 10px">#</th>
                            <th>Kode Sub Unit/<br>Nama Perangkat Daerah </th>
                            <?php
                            foreach ($listbulan as $key => $value) { ?>
                                <th><?= esc($value['bulan']) ?></th>
                            <?php } ?>
                            <th>Jumlah Anggaran (Rp)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($listopdangkasapbd as $key => $value) { ?>
                            <tr class="align-bottom">
                                <td><?= esc($key + 1) ?></td>
                                <td><strong><?= esc($value['KODE_UNIT_SKPD']) ?></strong><br>
                                    <strong><?= esc($value['NAMA_UNIT_SKPD']) ?></strong>
                                </td>
                                <?php
                                foreach ($listbulan as $key => $valuebln) {
                                    $angkasperbulan = $this->angkasmodel->getTotalDataAngkasPerBulanOPD($value['TAHUN'], $value['KODE_UNIT_SKPD'], $valuebln['bulan']);
                                    if ($angkasperbulan == null) {
                                        echo '<td>-</td>';
                                    } else {
                                        echo '<td>' . esc(number_format($angkasperbulan['total_angkas'], 0, ',', '.'))
                                            . '</td>';
                                    }
                                }
                                ?>
                                <td><strong><?= esc(number_format($value['total_angkas'], 0, ',', '.')) ?></strong>
                                </td>
                                <td>
                                    <a href="<?= hash_url('adminprov/angkasapbd/', [
                                                    'hal' => 'angkasopd',
                                                    'action' => 'detail',
                                                    'kdSU' => $value['NAMA_UNIT_SKPD'],
                                                    // 'kdSK' => $value['KODE_SUB_GIAT']
                                                    // 'kdU' => $value['kd_urusan']
                                                ]);
                                                ?>">
                                        <button type="button" class="btn btn-outline-primary mb-2">Details</button>
                                    </a>
                                </td>
                            </tr>
                        <?php }  ?>
                    </tbody>

                    <tfoot>
                        <tr class="align-bottom">
                            <th colspan="2">JUMLAH</th>
                            <?php
                            foreach ($listbulan as $key => $valuebln) {
                                $jumlahAnggaranPerBulan = $this->angkasmodel->getTotalAngkasPerBulanAll($value['TAHUN'], $valuebln['bulan']);
                                if ($jumlahAnggaranPerBulan == null) {
                                    echo '<th>-</th>';
                                } else {
                                    echo '<th>' . esc(number_format($jumlahAnggaranPerBulan['total_anggaran'], 0, ',', '.')) . '</th>';
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
                            <th></th>
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