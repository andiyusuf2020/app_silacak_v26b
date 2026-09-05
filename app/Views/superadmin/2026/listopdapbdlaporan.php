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
                <a href="<?= hash_url('adminprov/laporanapbdopd/', [
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
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0" style="height: 600px;">
                <table class="table table-head-fixed table-success table-striped text-wrap">
                    <thead>
                        <tr class="align-middle">
                            <th style="width: 10px">#</th>
                            <th>Kode Subkegiatan/Nama Sub Kegiatan</th>
                            <th>Pagu Anggaran<br>Rp.</th>
                            <th>Realisasi Anggaran (SIPD)<br>Rp.
                                <a href="#" data-bs-toggle="tooltip"
                                    data-bs-title="Data ini berdasarkan data Realisasi dari SIPD Penatausahan">
                                    <i class=" bi bi-emoji-sunglasses"></i></a>
                            </th>
                            <th>Capaian Realisasi Anggaran (SIPD)<br>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dataopdadmin as $key => $value) { ?>
                            <tr>
                                <td><?= esc($key + 1) ?> </td>
                                <td>
                                    <?= esc($value['KODE_UNIT_SKPD']) ?><br>
                                    <?= esc($value['NAMA_UNIT_SKPD']) ?>
                                </td>
                                <td style="text-align: right;">
                                    <?= number_format(esc($value['anggaran']), 0, ',', '.') ?>
                                </td>
                                <td style="text-align: right;">
                                    <?= number_format(esc($value['realisasi']), 0, ',', '.') ?>
                                </td>
                                <td>
                                    <?php
                                    if ($value['anggaran'] == 0) {
                                        $capaian = '0';
                                    } else {
                                        $capaian = ($value['realisasi'] / $value['anggaran']) * 100;
                                    } ?>
                                    <?= esc(number_format($capaian, 2, '.', ',')) ?>
                                </td>

                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr class="align-middle">
                            <th style="width: 10px" colspan="2">JUMLAH</th>
                            <th>
                                <?php
                                $jumlahAnggaran = array_sum(array_column($dataopdadmin, 'anggaran'));
                                echo esc(number_format($jumlahAnggaran, 0, ',', '.'));
                                ?>
                            </th>
                            <th>
                                <?php
                                $jumlahRealisasi = array_sum(array_column($dataopdadmin, 'realisasi'));
                                echo esc(number_format($jumlahRealisasi, 0, ',', '.'));
                                ?>
                            </th>
                            <th>
                                <?php
                                $capaianTotal = ($jumlahRealisasi / $jumlahAnggaran) * 100;
                                echo esc(number_format($capaianTotal, 2)) . '%';
                                ?>
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