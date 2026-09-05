<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>

<?php

use App\Models\RupModel\RealRupModel;

$this->realrup = new RealRupModel();

?>
<!--begin::Container-->
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Data Pengadaan Barang/Jasa (PBJ) <?= esc($datauser['sub_unit']) ?> </h3> <br>
                <a href="<?= hash_url('lrfkopd/apbdopd/', [
                                'hal' => 'cetakopd',
                                'action' => 'realisasirup',
                                // 'kdSU' => $value['kd_sub_unit'],
                                // 'kdU' => $value['kd_urusan']
                            ]);
                            ?>" target='blank'>
                    <button type="button" class="btn btn-outline-primary mb-2">Cetak Laporan PBJ</button>
                </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0" style="height: 600px;">
                <table class="table table-head-fixed table-success table-striped text-wrap">
                    <thead>
                        <tr class="align-middle">
                            <th style="width: 10px">#</th>
                            <th>Tahun</th>
                            <th>Jenis Pengadaan</th>
                            <th>Metode Pengadaan</th>
                            <th>Nama Paket</th>
                            <th>Nilai Paket</th>
                            <th>Status Paket(Progres)</th>
                            <th>Nama Penyedia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($realisasirup as $key => $value) { ?>
                            <tr class="align-middle">
                                <td><?= esc($key + 1) ?> </td>
                                <td>
                                    <?= esc($value['tahun']) ?>
                                </td>
                                <td>
                                    <?= esc($value['Jenis_Pengadaan']) ?>
                                </td>
                                <td>
                                    <?= esc($value['Metode_Pengadaan']) ?>
                                </td>
                                <td>
                                    <?= esc($value['Nama_Paket']) ?>
                                </td>
                                <td>
                                    <?= number_format(esc($value['Nilai_PDN']), 0, ',', '.') ?></td>
                                <td><?= esc($value['Status_Paket']) ?></td>
                                <td>
                                    <?= esc($value['Nama_Penyedia']) ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr class="align-middle">
                            <th style="width: 10px" colspan="5">JUMLAH</th>
                            <th>
                                <?php
                                $jumlahAnggaran = array_sum(array_column($realisasirup, 'Nilai_PDN'));
                                echo esc(number_format($jumlahAnggaran, 0, ',', '.'));
                                ?>
                            </th>
                            <th colspan="2"></th>
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