<?= $this->extend('template/layout') ?>

<?= $this->section('content') ?>
<!--begin::Container-->
<?php

use App\Models\CapkinModel\TaKegPokokCapkinModel;

$this->kegpokokmodal = new TaKegPokokCapkinModel();

use App\Models\CapkinModel\TaRealisasiKegPokokModel;

$this->rkegpokokmodal = new TaRealisasiKegPokokModel();

use App\Models\CapkinModel\TaRKegPokokCapkinModel;

$this->rdkegpokokmodal = new TaRKegPokokCapkinModel();

use App\Models\LrfkProvModel\TaRealisasiRinciModel;

$this->realisasilrfkrinci = new TaRealisasiRinciModel();

use App\Models\CapkinModel\TaSubKegCapkin2026;

$this->SubKegCapkinModel = new TaSubKegCapkin2026();

use App\Models\CapkinModel\TaPermasalahanAktifitas;

$this->permasalahanaktifitas = new TaPermasalahanAktifitas();

use App\Models\CapkinModel\TaNomenklaturModel;

$this->tanomenklaturmodel = new TaNomenklaturModel();
?>
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
        <div class="card-header">
            <?php if (session()->getFlashdata('message')): ?>
                <div class="alert alert-danger" role="alert">
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
        </div>
    </div>
    <!-- /.row -->
    <!--begin::Row-->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Rencana Aktifitas yang termapping pada Sub Kegiatan dalam RPJMD : <?= esc($datauser['sub_unit']) ?></h3>
                </div>
                <div class="card-body table-responsive p-0" style="height: 600px;">
                    <table class="table table align-top table-head-fixed text-wrap  table-hover">
                        <tbody>
                            <strong>
                                <tr>
                                    <td style="width: 10px;background-color: azure;">#</td>
                                    <td colspan="7" style="background-color: azure;">
                                        <h3>Program/Kegiatan/Sub Kegiatan | Indikator Kinerja Subkegiatan dan Defisinis Operasional<br> Total Anggaran</h3>
                                    </td>
                                    <td style="background-color: azure;">
                                        <h3>Anggaran (Rp)</h3>
                                    </td>
                                    <td style="background-color: azure;">
                                    </td>
                                    <!-- <td style="background-color: azure;">
                                    </td> -->
                                </tr>
                            </strong>
                            <?php
                            foreach ($datasubgiatcapkinopd as $key => $subkeg) {
                                $totalanggaran = $subkeg['total_anggaran']; ?>
                                <tr class="align-middle">
                                    <td><?= esc($key + 1) ?></td>
                                    <td colspan="7"><strong><?= esc($subkeg['nm_program']) ?></strong>/
                                        <strong><?= esc($subkeg['nm_kegiatan']) ?></strong>
                                        <?= esc($subkeg['nm_sub_giat']) ?><br>
                                        <?php
                                        $dataindikator = $this->tanomenklaturmodel->DataIndikatorSubKeg($subkeg['tahun'], $subkeg['kd_sub_giat']);
                                        if (!empty($dataindikator)) {
                                            foreach ($dataindikator as $key => $indikator) { ?>
                                                <strong>Indikator Kinerja : <?= esc($indikator['INDIKATOR']) ?></strong><br>
                                                <strong>Definisi Operasional : <?= esc($indikator['DEFINISI_OPERASIONAL']) ?></strong><br>
                                        <?php }
                                        } else {
                                        } ?>
                                    </td>
                                    <td>
                                        <strong><?= esc(number_format($totalanggaran, 0, ',', '.')) ?>

                                        </strong>

                                        <?php
                                        // $totalrealisasi = array_sum(array_column($JmlRealROBelanja ?? [], 'TOTAL_REALISASI'));

                                        // echo esc(number_format($totalrealisasi, 0, ',', '.'));
                                        // // echo esc('Rp. ' . number_format($totalrealisasi, 0, ',', '.'));
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        // $persenrealisasi = ($totalanggaran > 0) ? ($totalrealisasi / $totalanggaran) * 100 : 0;
                                        // echo esc(number_format($persenrealisasi, 2, ',', '.')) . ' %';
                                        ?>
                                    </td>

                                </tr>
                                <?php
                                $dataaktifitas = $this->kegpokokmodal->DataPerIdSK26($subkeg['id_skcapkin'], $tahunaktif);
                                if (empty($dataaktifitas)) { ?>
                                    <tr>
                                        <td colspan="9" style="color: red;">
                                            Belum ada Rencana Aktifitas pada Sub Kegiatan <strong><?= esc($subkeg['nm_sub_giat']) ?></strong> diatas, silakan
                                            <a href="<?= hash_url('capkin2026', ['hal' => 'rencana', 'action' => 'inputrencanaaktifitas', 'id_subgiatmapping' => $subkeg['id_skcapkin']]);
                                                        ?>" class="btn btn-outline-success" data-bs-toggle="tooltip"
                                                data-bs-title="Input Rencana Aktifitas/Kegiatan Pokok yang akan dilaksanakan pada Sub Kegiatan <?= esc($subkeg['nm_sub_giat']) ?>">
                                                <i class="bi bi-pencil-square"></i> Input Rencana Aktifitas</a> untuk melengkapi

                                        </td>

                                    </tr>
                                <?php } else { ?>
                                    <tr style="color: chartreuse;">
                                        <td></td>
                                        <td colspan="9">
                                            Rencana Aktifitas yang
                                            dilaksanakan untuk mendukung Sasaran Prioritas
                                            RPJMD yang diampu oleh <?= esc($datauser['sub_unit']) ?> dengan Sub Kegiatan <strong><?= esc($subkeg['nm_sub_giat']) ?></strong> :
                                        </td>
                                    </tr>
                                    <?php
                                    $char = range('a', 'z');
                                    // $dataaktifitas = $this->kegpokokmodal->DataPerIdSK($subkeg['id_skcapkin']);
                                    $dataaktifitas = $this->kegpokokmodal->DataPerIdSK26($subkeg['id_skcapkin'], $tahunaktif);

                                    // echo dd($dataaktifitas);
                                    foreach ($dataaktifitas as $key => $aktifitas) {
                                        $dataaktifitaspersasaran = $this->kegpokokmodal->DataPerSasaranPerIdSK($subkeg['id_skcapkin'], $aktifitas['id_progprioritas']); ?>
                                        <tr>
                                            <td></td>
                                            <td colspan="9" style="color: green;">
                                                Aktifitas dan Realisasl Pelaksanaan mendukung Sasaran RPJMD : <strong><?= esc($aktifitas['nm_progprioritas']) ?></strong></td>
                                        </tr>
                                        <?php
                                        foreach ($dataaktifitaspersasaran as $key => $aktifitaspersasaran) { ?>
                                            <tr>
                                                <td style="background-color: azure;"><strong></strong></td>
                                                <td colspan="5" style="background-color: azure;"><strong>Aktifitas/Kegiatan Pokok</strong></td>
                                                <td style="background-color: azure;"><strong>Jumlah Target Pelaksanaan/Sasaran</strong></td>
                                                <td style="background-color: azure;"><strong>Rencana Lokasi Pelaksanaan</strong></td>
                                                <td colspan="2" style="background-color: azure;"><strong>tgl update</strong></td>
                                            </tr>

                                            <tr class="align-top">
                                                <td style="background-color: azure;"><?= $char[$key] ?></td>
                                                <td colspan="5" style="background-color: azure;"><?= esc($aktifitaspersasaran['uraian_target']) ?></td>
                                                <td style="background-color: azure;"><?= esc($aktifitaspersasaran['vol_target'] . '   ' . $aktifitaspersasaran['sat_target']) ?></td>
                                                <td style="background-color: azure;"><?= esc($aktifitaspersasaran['lokasi']) ?></td>
                                                <td style="background-color: azure;"><?= esc($aktifitaspersasaran['update_at']) ?></td>
                                                <td style="background-color: azure;"></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td colspan="9" style="color: red;">
                                                    TAMBAH REALISASI AKTIFITAS BULAN <?= esc(strtoupper($bulanaktif)) ?>
                                                    <div class="btn-group">
                                                        <button
                                                            type="button"
                                                            class="btn btn dropdown-toggle"
                                                            data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <i class="bi bi-menu-button-wide-fill"></i>MENU
                                                            <a href="#" data-bs-toggle="tooltip"
                                                                data-bs-title="ini utk isi data realisasi aktivitas">
                                                                <i class=" bi bi-emoji-sunglasses"></i></a>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item"
                                                                    href="<?= hash_url('capkin2026', [
                                                                                'hal' => 'realisasi',
                                                                                'action' => 'inputrealisasiaktifitas',
                                                                                'idKP' => $aktifitaspersasaran['id_kp']
                                                                            ]);
                                                                            ?>">
                                                                    <i class="bi bi-plus-circle"></i>Tambah Realisasi</a></li>
                                                            <?php
                                                            $datapermasalahanaktifitas = $this->permasalahanaktifitas->DataPerKegPokok26($aktifitaspersasaran['id_kp']);
                                                            if ($datapermasalahanaktifitas) {
                                                            } else { ?>
                                                                <li><a class="dropdown-item"
                                                                        href="<?= hash_url('capkin2026', [
                                                                                    'hal' => 'realisasi',
                                                                                    'action' => 'permasalahanaktifitas',
                                                                                    'idKP' => $aktifitaspersasaran['id_kp']
                                                                                ]);
                                                                                ?>">
                                                                        <i class="bi bi-plus-circle"></i>Tambah Permasalahan&Solusi</a></li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td colspan="9" style="color: red;">
                                                    <i style="color: red;">Permasalahan dan Solusi Pelaksanaan Program dan Kegiatan atau Aktifitas Utama di <?= esc($datauser['sub_unit']) ?></i>
                                                </td>
                                            </tr>
                                            <?php
                                            $datapermasalahanaktifitas = $this->permasalahanaktifitas->DataPerKegPokok26($aktifitaspersasaran['id_kp']);
                                            // echo dd($datapermasalahanaktifitas);
                                            if ($datapermasalahanaktifitas) {
                                                // echo dd($datapermasalahanaktifitas);
                                                foreach ($datapermasalahanaktifitas as $rowdatapermasalahanaktifitas) {
                                            ?>
                                                    <tr>
                                                        <td></td>
                                                        <td colspan="8">
                                                            <?= $rowdatapermasalahanaktifitas['permasalahan'] ?>
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
                                                                        data-bs-title="ini utk ubah dan hapus permasalahan dan solusi pelaksanaan aktifitas">
                                                                        <i class=" bi bi-emoji-sunglasses"></i></a>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li><a class="dropdown-item"
                                                                            href="<?= hash_url('capkin2026', [
                                                                                        'hal' => 'realisasi',
                                                                                        'action' => 'ubahpermasalahanaktifitas',
                                                                                        'idPR' => $rowdatapermasalahanaktifitas['id_Pr']
                                                                                    ]);
                                                                                    ?>">
                                                                            <i class="bi bi-plus-circle"></i>Ubah Permasalahan&Solusi</a></li>
                                                                    <li><a class="dropdown-item"
                                                                            href="<?= hash_url('capkin2026', [
                                                                                        'hal' => 'realisasi',
                                                                                        'action' => 'hapuspermasalahanaktifitas',
                                                                                        'idPR' => $rowdatapermasalahanaktifitas['id_Pr']
                                                                                    ]);
                                                                                    ?>">
                                                                            <i class="bi bi-trash"></i>Hapus Permasalahan&Solusi</a></li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                            } else { ?>
                                                <tr>
                                                    <td></td>
                                                    <td colspan="9" style="color: red;">
                                                        <i>Permasalahan dan Solusi Pelaksanaan Aktifitas belum diinput.</i>
                                                    </td>
                                                </tr>
                                                <?php }
                                            $datarkp = $this->rkegpokokmodal->DataPerKegPokok26($aktifitaspersasaran['id_kp']);
                                            if ($datarkp) {
                                                // echo dd($datarkp);
                                                foreach ($datarkp as $rowdatarkp) {
                                                ?>
                                                    <tr>
                                                        <td></td>
                                                        <!-- <td></td> -->
                                                        <td colspan="6" style="color: green;">
                                                            REALISASI AKTIFITAS BULAN <?= esc(strtoupper($rowdatarkp['bulan'])) ?>
                                                        </td>
                                                        <td>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="background-color: azure;"></td>
                                                        <!-- <td style="background-color: azure;"></td> -->
                                                        <td colspan="6" style="background-color: azure;"><strong>
                                                                Realisasi Pelaksanaan Aktivitas/Kegiatan Subkegiatan Bulan
                                                                <?= esc(strtoupper($rowdatarkp['bulan'])) ?> Tahun <?= esc($tahunaktif) ?></strong></td>
                                                        <td style="background-color: azure;"><strong>Jumlah Realisasi</strong></td>
                                                        <td style="background-color: azure;"><strong>tgl update</strong></td>
                                                        <td style="background-color: azure;"><strong>Menu Realisasi</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <!-- <td></td> -->
                                                        <td colspan="6"><?= esc($rowdatarkp['r_uraian']) ?></td>
                                                        <td><?= esc($rowdatarkp['r_target'] . '   ' . $rowdatarkp['sat_target']) ?></td>
                                                        <td><?= esc($rowdatarkp['update_at']) ?></td>
                                                        <td>

                                                            <div class="btn-group">
                                                                <button
                                                                    type="button"
                                                                    class="btn btn dropdown-toggle"
                                                                    data-bs-toggle="dropdown"
                                                                    aria-expanded="false">
                                                                    <i class="bi bi-menu-button-wide-fill"></i>MENU
                                                                    <a href="#" data-bs-toggle="tooltip"
                                                                        data-bs-title="ini utk isi data dokumentasi aktivitas, ubah dan hapus dokumentasi aktivitas">
                                                                        <i class=" bi bi-emoji-sunglasses"></i></a>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li><a class="dropdown-item"
                                                                            href="<?= hash_url('capkin2026', [
                                                                                        'hal' => 'realisasi',
                                                                                        'action' => 'ubahrealisasiaktifitas',
                                                                                        'idRKP' => $rowdatarkp['id_r']
                                                                                    ]);
                                                                                    ?>">
                                                                            <i class="bi bi-plus-circle"></i>Ubah Realisasi</a></li>
                                                                    <li><a class="dropdown-item"
                                                                            href="<?= hash_url('capkin2026', [
                                                                                        'hal' => 'realisasi',
                                                                                        'action' => 'hapusrealisasiaktifitas',
                                                                                        'idRKP' => $rowdatarkp['id_r']
                                                                                    ]);
                                                                                    ?>">
                                                                            <i class="bi bi-trash"></i>Hapus Realisasi</a></li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $datadokumentasirealisasiaktifitas = $this->rdkegpokokmodal->DataPerRKegPokok26($rowdatarkp['id_r'] ?? null);
                                                    if (!$datadokumentasirealisasiaktifitas) { ?>
                                                        <tr>
                                                            <td></td>
                                                            <!-- <td></td> -->
                                                            <td colspan="9"><i style="color: red;">Dokumentasi aktivitas ini <strong>belum diinput.</strong></i>

                                                                <div class="btn-group">
                                                                    <button
                                                                        type="button"
                                                                        class="btn btn dropdown-toggle"
                                                                        data-bs-toggle="dropdown"
                                                                        aria-expanded="false">
                                                                        <i class="bi bi-menu-button-wide-fill"></i>MENU
                                                                        <a href="#" data-bs-toggle="tooltip"
                                                                            data-bs-title="ini utk isi data dokumentasi aktivitas, ubah dan hapus dokumentasi aktivitas">
                                                                            <i class=" bi bi-emoji-sunglasses"></i></a>
                                                                    </button>
                                                                    </button>
                                                                    <ul class="dropdown-menu">
                                                                        <li><a class="dropdown-item"
                                                                                href="<?= hash_url('capkin2026', [
                                                                                            'hal' => 'dokumenrealisasi',
                                                                                            'action' => 'inputdokumenrealisasiaktifitas',
                                                                                            'idRKP' => $rowdatarkp['id_r']
                                                                                        ]);
                                                                                        ?>">
                                                                                <i class="bi bi-plus-circle"></i>Tambah Dokumentasi</a>
                                                                        </li>

                                                                    </ul>
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    <?php
                                                    } else { ?>
                                                        <tr>
                                                            <!-- <td></td> -->
                                                            <td></td>
                                                            <!-- <td colspan="9" style="color: red;"> -->
                                                            <td colspan="9"><strong>
                                                                    Dokumentasi Pelaksanaan Aktivitas/Kegiatan ini:</strong>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        foreach ($datadokumentasirealisasiaktifitas as $key => $rdoktaktifitas) { ?>

                                                            <tr>
                                                                <td></td>
                                                                <td></td>

                                                                <td colspan="4">
                                                                    <img src="uploads/dokumentasi/<?= esc($rdoktaktifitas['gambar'] ?? null) ?>" width="350" height="150" alt="Gambar Dokumentasi" class="img-thumbnail"><br>
                                                                </td>
                                                                <td colspan="2">
                                                                    <?= esc($rdoktaktifitas['deskripsi'] ?? null) ?> - Lokasi Aktifitas
                                                                    <?= esc($rdoktaktifitas['kabupaten']) ?>,<?= esc($rdoktaktifitas['kecamatan']) ?>, <?= esc($rdoktaktifitas['desa']) ?><br>
                                                                    Titik Koordinat:
                                                                    <?= esc($rdoktaktifitas['latitude']) ?>,<?= esc($rdoktaktifitas['longitude']) ?><br>
                                                                    <a href="<?= hash_url('capkin', [
                                                                                    'idD' => $rdoktaktifitas['id_dr'] ?? null
                                                                                ]);
                                                                                ?>">
                                                                        <i class="bi bi-eye-fill">Detail</i></a>
                                                                </td>
                                                                <td>
                                                                    <?= esc($rdoktaktifitas['update_at']) ?>
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
                                                                                data-bs-title="ini utk ubah dan hapus dokumentasi aktivitas">
                                                                                <i class=" bi bi-emoji-sunglasses"></i></a>
                                                                        </button>
                                                                        </button>
                                                                        <ul class="dropdown-menu">
                                                                            <li><a class="dropdown-item"
                                                                                    href="<?= hash_url('capkin2026', [
                                                                                                'hal' => 'dokumenrealisasi',
                                                                                                'action' => 'inputdokumenrealisasiaktifitas',
                                                                                                'idRKP' => $rowdatarkp['id_r']
                                                                                            ]);
                                                                                            ?>">
                                                                                    <i class="bi bi-plus-circle"></i>Tambah Dokumentasi</a>
                                                                            </li>
                                                                            <li><a class="dropdown-item"
                                                                                    href="<?= hash_url('capkin2026', [
                                                                                                'hal' => 'dokumenrealisasi',
                                                                                                'action' => 'ubahdokumentasirealisasiaktifitas',
                                                                                                'idDRKP' => $rdoktaktifitas['id_dr']
                                                                                            ]);
                                                                                            ?>">
                                                                                    <i class="bi bi-plus-circle"></i>Ubah Dokumentasi Realisasi</a></li>
                                                                            <li><a class="dropdown-item"
                                                                                    href="<?= hash_url('capkin2026', [
                                                                                                'hal' => 'dokumenrealisasi',
                                                                                                'action' => 'hapusdokumentasirealisasiaktifitas',
                                                                                                'idDRKP' => $rdoktaktifitas['id_dr']
                                                                                            ]);
                                                                                            ?>">
                                                                                    <i class="bi bi-trash"></i>Hapus Dokumentasi Realisasi</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                            <?php
                                                        }
                                                    }
                                                }
                                            } ?>
                                        <?php } ?>
                            <?php }
                                }
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
</div>
<style>
    .tooltip-custom {
        position: relative;
        display: inline-block;
        border-bottom: 1px dotted #007bff;
        cursor: pointer;
        margin: 20px;
    }

    .tooltip-custom .tooltiptext {
        visibility: hidden;
        width: 200px;
        background-color: #333;
        color: #fff;
        text-align: center;
        border-radius: 3px;
        padding: 5px;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        margin-left: -100px;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .tooltip-custom .tooltiptext::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #333 transparent transparent transparent;
    }

    .tooltip-custom:hover .tooltiptext {
        visibility: visible;
        opacity: 1;
    }

    .tooltip-bootstrap {
        margin: 10px;
    }
</style>


<?= $this->endSection() ?>