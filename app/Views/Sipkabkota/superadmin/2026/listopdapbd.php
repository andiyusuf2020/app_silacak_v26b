<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>

<?php

use App\Models\DataApbdModel\RealApbdModel;

$this->realapbd = new RealApbdModel();

?>
<!--begin::Container-->
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <a href="<?= hash_url('adminprov/apbdopd/', [
                                'hal' => 'cetakrekap',
                                'action' => 'all',
                                // 'kdSU' => $value['kd_sub_unit'],
                                // 'kdU' => $value['kd_urusan']
                            ]);
                            ?>" target='blank'>
                    <button type="button" class="btn btn-outline-primary mb-2">Cetak Rekap LRFK</button>
                </a><br>
                <h3>Data Realisasi Anggaran per-Perangkat Daerah : <?= esc($datauser['sub_unit']) ?> </h3>
                <br>
                <!-- <button onclick="history.back()" class="btn btn-secondary">Kembali</button> -->


            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0" style="height: 600px;">
                <table class="table table-head-fixed table-success table-striped text-wrap">
                    <thead>
                        <tr class="align-middle">
                            <th style="width: 10px">#</th>
                            <th>Kode Sub Unit/Nama Sub Unit</th>
                            <th>Pagu Anggaran<br>Rp.</th>
                            <th>Realisasi Anggaran (SIPD)<br>Rp.
                                <a href="#" data-bs-toggle="tooltip"
                                    data-bs-title="Data ini berdasarkan data Realisasi dari SIPD Penatausahan">
                                    <i class=" bi bi-emoji-sunglasses"></i></a>
                            </th>
                            <th>Capaian Realisasi Anggaran (SIPD)<br>%</th>
                            <th>Capaian Kinerja Anggaran</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dataopdadmin['data'] as $key => $value) { ?>
                            <tr>
                                <td><?= esc($key + 1) ?></td>
                                <td>
                                    <?= esc($value['JumlahBelanja']) ?><br>
                                    <?= esc($value['NAMA_UNIT_SKPD']) ?>
                                </td>
                                <td style="text-align: right;">
                                    <?= number_format(esc($value['TotalAnggaran']), 0, ',', '.') ?>
                                </td>
                                <td style="text-align: right;">
                                    <?= number_format(esc($value['TotalRealisasi']), 0, ',', '.') ?>
                                </td>
                                <td>
                                    <?= esc(number_format($value['PersentaseRealisasi'], 2, '.', ',')) ?>
                                </td>
                                <td>
                                    <?php
                                    $capaian_kinerja = $this->realapbd->getCapaianKinerjaperopd($tgldata, $value['KODE_UNIT_SKPD']);
                                    // echo esc(number_format($capaian_kinerja['geometric_mean'], 2, '.', ','));
                                    // echo esc(number_format($value['SkorCapaian'], 2, '.', ','));
                                    echo esc('')
                                    ?>
                                </td>
                                <td>

                                </td>

                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr class="align-middle">
                            <th style="width: 10px" colspan="2">JUMLAH</th>
                            <th>
                                <?php
                                echo esc(number_format($dataopdadmin['total_anggaran_keseluruhan'], 0, ',', '.'));
                                ?>
                            </th>
                            <th>
                                <?php

                                echo esc(number_format($dataopdadmin['total_realisasi_keseluruhan'], 0, ',', '.'));
                                ?>
                            </th>
                            <th>
                                <?= esc(number_format($dataopdadmin['persentase_realisasi_keseluruhan'], 2, '.', ',')) ?>

                            </th>
                            <th>
                                <?= esc(number_format($dataopdadmin['geometric_mean'], 2, '.', ',')) ?>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <span class="text-muted">
                    <i class=" bi bi-info-circle-fill"></i>Data realisasi anggaran per-subkegiatan ini
                    berdasarkan https://sipd-ri.kemendagri.go.id TA <?= esc($tahunaktif) ?>. per <?= esc($tgldata) ?>. ** </span>
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>
<!--end::Container-->

<?= $this->endSection() ?>