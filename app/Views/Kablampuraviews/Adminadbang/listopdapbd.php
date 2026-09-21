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
                <h3>Data Realisasi Anggaran per-Perangkat Daerah : <?= esc($datauser) ?> </h3>
                <br>
                <!-- <button onclick="history.back()" class="btn btn-secondary">Kembali</button> -->


            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0" style="height: 600px;">
                <table class="table table-head-fixed table-success table-striped text-wrap">
                    <thead>
                        <tr class="align-middle">
                            <th style="width: 10px">#</th>
                            <th>Kode Perangkat Daerah/Nama Perangkat Daerah</th>
                            <th>Pagu Anggaran<br>Rp.</th>
                            <th>Realisasi Anggaran (SIPD)<br>Rp.
                            </th>
                            <th>Capaian Realisasi Anggaran (SIPD)<br>%</th>
                            <th>Jumlah Aktivitas Belanja</th>
                            <th>Capaian Kinerja Anggaran Perangkat Daerah</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dataopdadmin['data'] as $key => $value) { ?>
                            <tr>
                                <td><?= esc($key + 1) ?> </td>
                                <td>
                                    <?= esc($value['KODE_UNIT_SKPD']) ?><br>
                                    <?= esc($value['NAMA_UNIT_SKPD']) ?>
                                </td>
                                <td style="text-align: right;">
                                    <?= number_format(esc($value['TotalAnggaran']), 0, ',', '.') ?>
                                </td>
                                <td style="text-align: right;">
                                    <?= number_format(esc($value['TotalRealisasi']), 0, ',', '.') ?>
                                </td>
                                <td style="text-align: center;">
                                    <?= number_format(esc($value['PersentaseRealisasi']), 2, ',', '.') ?>
                                </td>
                                <td style="text-align: center;">
                                    <?= number_format(esc($value['JumlahBelanja']), 0, ',', '.') ?>
                                </td>
                                <td>*Kepgub Lampung Nomor G/755/B.06/HK/2023</td>
                                <td>
                                    <a href="<?= hash_url(' ' . $wilayah . '/' . $groupuser, [
                                                    'hal' => 'detail',
                                                    'action' => 'opd',
                                                    'kdSU' => $value['NAMA_UNIT_SKPD'],
                                                    'blndata' => $value['BULAN'],
                                                    'tgldataopd' => $value['CREATE_AT'],

                                                    // 'kdU' => $value['kd_urusan']
                                                ]);
                                                ?>">
                                        <button type="button" class="btn btn-outline-primary mb-2">Detail</button>

                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr class="align-middle">
                            <th style="width: 10px" colspan="2">JUMLAH</th>
                            <th>
                                <?= esc($dataopdadmin['total_anggaran_keseluruhan']) ?>
                            </th>
                            <th>
                                <?= esc($dataopdadmin['total_realisasi_keseluruhan']) ?>
                            </th>
                            <th>
                                <?= esc($dataopdadmin['persentase_realisasi_keseluruhan']) ?>
                            </th>

                        </tr>
                        <tr>
                            <td colspan="8">
                                <i class=" bi bi-info-circle-fill font-size-10"></i>Data realisasi anggaran Perangkat Daerah se-Provinsi Lampung ini
                                berdasarkan data dari <span class="text-primary">
                                    https://sipd-ri.kemendagri.go.id </span>

                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <span class="text-muted">
                    <i class=" bi bi-info-circle-fill"></i>Data realisasi anggaran per-subkegiatan ini
                    berdasarkan https://sipd-ri.kemendagri.go.id TA <?= esc($tahun) ?>. per <?= esc($tglaktif) ?>. ** </span>
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>
<!--end::Container-->

<?= $this->endSection() ?>