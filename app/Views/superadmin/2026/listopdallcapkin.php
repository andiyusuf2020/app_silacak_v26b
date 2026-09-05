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

                            <th>Jumlah Sasaran termapping</th>

                            <th>Jumlah Aktivitas Utama pengampu Sasaran</th>
                            <th>Jumlah Dokumentasi Aktivitas Utama</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dataopdadmin as $key => $value) {
                            $datasubgiatcapkinopd = $this->subkegcapkin2026model->DataPerSKPerSkpdpertahun($value['TAHUN'], $value['KODE_UNIT_SKPD']);
                            $jmlsubkegtermapping = count($datasubgiatcapkinopd);
                            // $datadokumentasirealisasiaktifitas = $this->rdkegpokokmodal->DataPerSasaranPerOPD($value['TAHUN'], $value['KODE_UNIT _SKPD'], $value['id_progprioritas']);
                            $mappingsasaran = $this->kegpokokmodal->DataPerSasaranPerOPD26($value['TAHUN'], $value['KODE_UNIT_SKPD']);
                            $dataaktifitas = $this->kegpokokmodal->DataPerPprioOPD26($value['TAHUN'], $value['KODE_UNIT_SKPD']);

                            $datadokumentasirealisasiaktifitas = $this->rdkegpokokmodal->getLokasi26($value['TAHUN'], $value['KODE_UNIT_SKPD']);

                        ?>
                            <tr class="align-middle">
                                <td><?= esc($key + 1) ?> </td>
                                <td>
                                    <?= esc($value['KODE_UNIT_SKPD']) ?>
                                </td>
                                <td>
                                    <?= esc($value['NAMA_UNIT_SKPD']) ?>
                                </td>
                                <?php
                                if ($jmlsubkegtermapping == 0) { ?>
                                    <td class="text-danger" color="red">
                                        <?= esc($jmlsubkegtermapping); ?>
                                    </td>

                                <?php } else { ?>
                                    <td class="text-success" color="green">
                                        <?= esc($jmlsubkegtermapping); ?>
                                    </td>
                                <?php } ?>
                                <td>
                                    <?php
                                    if (count($mappingsasaran) == 0) { ?>
                                        <span class="text-danger">Tidak ada sasaran yang termapping</span>
                                    <?php } else { ?>
                                        <?= esc(count($mappingsasaran)) ?>
                                    <?php } ?>
                                </td>

                                <td>
                                    <?php
                                    if (count($dataaktifitas) == 0) { ?>
                                        <span class="text-danger">Tidak ada aktivitas utama yang termapping</span>
                                    <?php } else { ?>
                                        <?= esc(count($dataaktifitas)) ?>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php
                                    if (count($datadokumentasirealisasiaktifitas) == 0) { ?>
                                        <span class="text-danger">Tidak ada dokumentasi aktivitas utama yang termapping</span>
                                    <?php } else { ?>
                                        <?= esc(count($datadokumentasirealisasiaktifitas)) ?>
                                    <?php } ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button
                                            type="button"
                                            class="btn btn dropdown-toggle"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="bi bi-menu-button-wide-fill"></i>MENU
                                            <a href="#" data-bs-toggle="tooltip"
                                                data-bs-title="Hai!! Admin Provinsi, klik menu ini untuk menampilkan 
                                                detail dokumentasi realisasi aktifitas kegiatan <?= esc($value['NAMA_UNIT_SKPD']) ?>.">
                                                <i class=" bi bi-emoji-sunglasses"></i></a>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item"
                                                    href="<?= hash_url('adminprov/capkin', [
                                                                'hal' => 'rekapcapkin',
                                                                'action' => 'Cetak',
                                                                'kdSU' => $value['KODE_UNIT_SKPD'],
                                                                'nmSU' => $value['NAMA_UNIT_SKPD'],
                                                            ]);
                                                            ?>" target="_blank">
                                                    <i class="bi bi-plus-circle"></i>Cetak Laporan</a>
                                            </li>
                                            <li><a class="dropdown-item"
                                                    href="<?= hash_url('adminprov/capkin', [
                                                                'hal' => 'rekapcapkin',
                                                                'action' => 'detaildokumentasi',
                                                                'kdSU' => $value['KODE_UNIT_SKPD'],
                                                                'nmSU' => $value['NAMA_UNIT_SKPD'],
                                                            ]);
                                                            ?>">
                                                    <i class="bi bi-plus-circle"></i>Detail Dokumentasi</a>
                                            </li>
                                        </ul>
                                    </div>
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