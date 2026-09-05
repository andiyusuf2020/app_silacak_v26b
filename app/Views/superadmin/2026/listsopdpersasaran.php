<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>

<?php

use App\Models\DataApbdModel\PendApbdModel;
use App\Models\DataApbdModel\RealPendApbdModel;

$this->pendapbd = new PendApbdModel();
$this->realpendapbd = new RealPendApbdModel();

use App\Models\CapkinModel\TaSubKegCapkin2026;

$this->subkegcapkin2026model = new TaSubKegCapkin2026();

use App\Models\CapkinModel\TaKegPokokCapkinModel;

$this->kegpokokmodal = new TaKegPokokCapkinModel();

use App\Models\CapkinModel\TaRKegPokokCapkinModel;

$this->rdkegpokokmodal = new TaRKegPokokCapkinModel();
?>
<!--begin::Container-->
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Data Perangkat Daerah Pengampu Sasaran RJPMD : <br>
                    <br>
                    <?= esc($opdperprioritas[0]['nm_progprioritas'] ?? null) ?>
                </h3> <br>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0" style="height: 600px;">
                <table class="table table-head-fixed table-success table-striped text-wrap">
                    <thead>
                        <tr class="align-middle">
                            <th style="width: 10px">#</th>
                            <th>Kode Sub Unit</th>
                            <th>Perangkat Daerah</th>
                            <th>Jumlah Subkegiatan termapping</th>
                            <th>Jumlah Aktivitas Utama pengampu Sasaran</th>
                            <th>Jumlah Dokumentasi Aktivitas Utama</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($opdperprioritas as $key => $value) {
                            $datasubgiatcapkinopd = $this->subkegcapkin2026model->DataPerSKPerSkpdpertahun($value['tahun'], $value['kd_subunit']);
                            $jmlsubkegtermapping = count($datasubgiatcapkinopd);
                            $datadokumentasirealisasiaktifitas = $this->rdkegpokokmodal->DataPerSasaranPerOPD($value['tahun'], $value['kd_subunit'], $value['id_progprioritas']);
                            // $mappingsasaran = $this->kegpokokmodal->DataPerPprioOPD26($value['tahun'], $value['kd_subunit'], $value['id_progprioritas']);
                            $jmldokumentasirealisasiaktifitas = count($datadokumentasirealisasiaktifitas);
                        ?>
                            <tr class="align-middle">
                                <td><?= esc($key + 1) ?> </td>
                                <td>
                                    <?= esc($value['kd_subunit']) ?>
                                </td>
                                <td>
                                    <?= esc($value['nm_sub_skpd']) ?>
                                </td>
                                <td>
                                    <?= esc($jmlsubkegtermapping) ?>
                                </td>
                                <td>
                                    <?= esc($value['jmlaktivitasutama']) ?>
                                </td>
                                <td>
                                    <?= esc($jmldokumentasirealisasiaktifitas) ?>
                                </td>
                                <td>
                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>

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