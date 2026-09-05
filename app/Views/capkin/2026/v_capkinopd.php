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

use App\Models\CapkinModel\TaMProgPrioritasModel;


$this->progprioritas = new TaMProgPrioritasModel();

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
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Program Pembangunan dalam RPJMD yang diampu oleh : <?= esc($datauser['sub_unit']) ?></h3>
                </div>
                <div class="alert alert-danger" role="alert">
                    <div class="card-body table-responsive p-1" style="height: 180px;">
                        <ul>
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Program Pembangunan yang diampu</th>
                                        <th>Jumlah Subkegiatan dan Aktifitas/Kegiatan Pokok yang telah termapping <br>sampai <?= esc(date('d-M-Y')) ?></th>
                                        <th>Tanggal Update Terakhir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="align-middle">
                                        <td>1.</td>
                                        <td>Sasaran Program Prioritas</td>
                                        <td><strong><?= esc($jmlmappingprio) ?> Aktifitas</strong><br>
                                            <strong><?= esc($jmlsubkegtermapping); ?> Subkegiatan </strong>
                                        </td>
                                        <td><strong><?= esc($sasaranterupdete) ?></strong>
                                        </td>
                                    </tr>
                            </table>
                        </ul>
                    </div>
                </div>
                <div class="card-header">
                    <a
                        href="<?= hash_url('capkin2026', ['hal' => 'mappingsubkeg', 'action' => 'tambah']);
                                ?>" data-bs-toggle="tooltip" data-bs-title="Pilih SubKegiatan yang akan dimapping">
                        <button type="button" class="btn btn-outline-primary">
                            <i class="bi bi-plus-circle"></i>TAMBAH SUBKEGIATAN</button>
                    </a>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!-- /.row -->
    <!--begin::Row-->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Sub Kegiatan yang termapping pada Program Sasaran dalam RPJMD : <?= esc($datauser['sub_unit']) ?></h3>
                </div>
                <div class="card-body table-responsive p-0" style="height: 600px;">
                    <table class="table table align-middle table-head-fixed table-success table-striped text-wrap">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th colspan="5">Program/Kegiatan/Sub Kegiatan|Indikator Kinerja Sub Kegiatan | Definisi Operasional<br> Total Anggaran</th>
                                <!-- <th colspan="4">
                                    Komposisi Rincian Objek Belanja |
                                    Pagu Rincian Objek (RO) Belanja |
                                    Realisasi RO Belanja(SIPD) |
                                    % Realisasi
                                </th> -->
                                <th style="width: 20px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // $totalanggaran = array_sum(array_column($subgiat ?? [], 'TOTAL_ANGGARAN'));
                            foreach ($datasubgiatcapkinopd as $key => $subkeg) { ?>
                                <tr class="align-middle">
                                    <td><?= esc($key + 1) ?></td>
                                    <td colspan="5"><strong><?= esc($subkeg['nm_program']) ?></strong><br>
                                        <strong><?= esc($subkeg['nm_kegiatan']) ?></strong><br>
                                        <?= esc($subkeg['nm_sub_giat']) ?><br>
                                        <?php
                                        $dataindikator = $this->tanomenklaturmodel->DataIndikatorSubKeg($subkeg['tahun'], $subkeg['kd_sub_giat']);
                                        if (!empty($dataindikator)) {
                                            foreach ($dataindikator as $key => $indikator) { ?>
                                                Indikator Kinerja : <?= esc($indikator['INDIKATOR']) ?><br>
                                                Definisi Operasional : <?= esc($indikator['DEFINISI_OPERASIONAL']) ?><br>
                                            <?php }
                                        } else { ?>
                                        <?php } ?>
                                        <strong><?= esc('Rp ' . number_format($subkeg['total_anggaran'], 0, ',', '.')) ?></strong>
                                    </td>
                                    <!-- <td colspan="4"> -->
                                    <?php
                                    // $RBelanja = $this->SubKegCapkinModel
                                    //     ->DataBelanjaPerSKPerSkpd2($subkeg['tahun'], $subkeg['bulan'], $subkeg['tgldata'], $subkeg['kd_sub_skpd'], $subkeg['kd_sub_giat']);
                                    // echo dd($RBelanja);
                                    // // foreach ($RBelanja as $key => $belanja) { 
                                    ?>
                                    <!-- //     <table class="table table align-middle table-head-fixed table-success table-striped text-wrap"> -->
                                    <!-- //         <tr class="align-top"> -->
                                    <!-- //             <td><?php // echo esc($belanja['NAMA_RINCIAN_OBJEK']) 
                                                            ?></td> -->
                                    <!-- //             <td> -->
                                    <!-- //                 <?php // echo esc(number_format($belanja['TOTAL_ANGGARAN'], 0, ',', '.')) 
                                                            ?> -->
                                    <!-- //             </td> -->
                                    <!-- //             <td><?php // echo esc(number_format($belanja['TOTAL_REALISASI'], 0, ',', '.')) 
                                                            ?></td> -->
                                    <!-- //             <td> -->
                                    <!-- //                 <?php //if ($belanja['TOTAL_ANGGARAN'] == 0) { -->
                                                            // <!-- //                   //  echo "0%"; -->
                                                            // <!-- //               //  } else { -->
                                                            // <!-- //                     <?php   // echo esc(number_format(($belanja['TOTAL_REALISASI'] / $belanja['TOTAL_ANGGARAN']) * 100, 2)) 
                                                            ?>% -->
                                    <!-- //                 <?php // } 
                                                            ?> -->
                                    <!-- //             </td> -->
                                    <!-- //         </tr> -->
                                    <!-- //     </table> -->
                                    <?php
                                    // }
                                    ?>
                                    <!-- </td> -->
                                    <td>
                                        <a href="<?= hash_url('capkin2026', ['hal' => 'rencana', 'action' => 'inputrencanaaktifitas', 'id_subgiatmapping' => $subkeg['id_skcapkin']]);
                                                    ?>" class="btn btn-outline-success" data-bs-toggle="tooltip"
                                            data-bs-title="Input Rencana Aktifitas/Kegiatan Pokok yang akan dilaksanakan 
                                            pada Sub Kegiatan <?= esc($subkeg['nm_sub_giat']) ?>">
                                            <i class="bi bi-pencil-square"></i> Input Rencana Aktifitas</a><br><br>
                                        <a href="<?= hash_url('capkin2026', ['hal' => 'rencana', 'action' => 'hapussubkegtermapping', 'id_subgiatmapping' => $subkeg['id_skcapkin']]);
                                                    ?>" class="btn btn-outline-success" data-bs-toggle="tooltip"
                                            data-bs-title="Hapus Sub Kegiatan: <?= esc($subkeg['nm_sub_giat']) ?>;yang termapping dalam Aktifitas/Kegiatan Pokok yang akan dilaksanakan 
                                            ">
                                            <i class="bi bi-trash"></i>Hapus SubKegiatan termapping</a>
                                    </td>
                                </tr>
                                <?php
                                $dataaktifitas = $this->kegpokokmodal->DataPerIdSK26($subkeg['id_skcapkin'], $tahunaktif);
                                if (empty($dataaktifitas)) { ?>
                                    <tr>
                                        <td colspan="7" style="color: red;">
                                            Belum ada Rencana Aktifitas pada Sub Kegiatan <strong><?= esc($subkeg['nm_sub_giat']) ?></strong>
                                        </td>
                                    </tr>
                                <?php } else { ?>
                                    <tr>
                                        <td></td>
                                        <td colspan="6">
                                            Rencana Aktifitas yang
                                            dilaksanakan untuk mendukung Sasaran Prioritas
                                            RPJMD yang diampu oleh <?= esc($datauser['sub_unit']) ?> dengan Sub Kegiatan <strong><?= esc($subkeg['nm_sub_giat']) ?></strong> :
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong></strong></td>
                                        <td colspan="3"><strong>Uraian Aktifitas/Kegiatan Pokok</strong></td>
                                        <td><strong>Jumlah Target Pelaksanaan/Sasaran</strong></td>
                                        <!-- <td colspan="3"><strong>Outcome/Benefit sesuai Output Aktifitas yang dihasilkan sampai akhir Tahun Anggaran <?= esc($tahunaktif) ?></strong></td> -->
                                        <td colspan="2"><strong>Rencana Lokasi Pelaksanaan</strong></td>
                                    </tr>

                                    <?php
                                    // echo dd($dataaktifitas);
                                    foreach ($dataaktifitas as $key => $aktifitas) { ?>
                                        <tr>
                                            <td></td>
                                            <td colspan="6" style="color: blue;">
                                                <?php
                                                $dataprio = $this->progprioritas->DataPerId($aktifitas['id_progprioritas']);
                                                $dataprioritas = $dataprio ? $dataprio['nm_progprioritas'] : 'Data Program Prioritas tidak ditemukan';
                                                ?>
                                                Aktifitas Pendukung Sasaran Prioritas RPJMD:
                                                <strong><?= esc($dataprioritas) ?>
                                                </strong>
                                            </td>
                                        </tr>
                                        <?php
                                        $dataaktifitaspersasaran = $this->kegpokokmodal->DataPerSasaranPerIdSK($subkeg['id_skcapkin'], $aktifitas['id_progprioritas']);
                                        foreach ($dataaktifitaspersasaran as $key => $aktifitaspersasaran) { ?>
                                            <tr class="align-top">
                                                <td>-</td>
                                                <td colspan="3"><?= esc($aktifitaspersasaran['kelompok']) ?><br>
                                                    <?= esc($aktifitaspersasaran['uraian_target']) ?>
                                                </td>
                                                <td><?= esc($aktifitaspersasaran['vol_target'] . '   ' . $aktifitaspersasaran['sat_target']) ?></td>
                                                <td><?= esc($aktifitaspersasaran['lokasi']) ?></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button
                                                            type="button"
                                                            class="btn btn dropdown-toggle"
                                                            data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <i class="bi bi-menu-button-wide-fill"></i>MENU
                                                            <a href="#" data-bs-toggle="tooltip"
                                                                data-bs-title="ini utk isi data aktivitas, ubah dan hapus subkegiatan">
                                                                <i class=" bi bi-emoji-sunglasses"></i></a>
                                                        </button>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item"
                                                                    href="<?= hash_url('capkin2026', [
                                                                                'hal' => 'rencana',
                                                                                'action' => 'inputrencanaaktifitas',
                                                                                'id_subgiatmapping' => $subkeg['id_skcapkin']
                                                                            ]);
                                                                            ?>">
                                                                    <i class="bi bi-plus-circle"></i>Tambah Aktifitas</a></li>
                                                            <li><a class="dropdown-item"
                                                                    href="<?= hash_url('capkin2026', [
                                                                                'hal' => 'rencana',
                                                                                'action' => 'ubahrencanaaktifitas',
                                                                                'idKP' => $aktifitaspersasaran['id_kp']
                                                                            ]);
                                                                            ?>">
                                                                    <i class="bi bi-plus-circle"></i>Ubah Aktifitas</a></li>
                                                            <li><a class="dropdown-item"
                                                                    href="<?= hash_url('capkin2026', [
                                                                                'hal' => 'rencana',
                                                                                'action' => 'hapusrencanaaktifitas',
                                                                                'idKP' => $aktifitaspersasaran['id_kp']
                                                                            ]);
                                                                            ?>">
                                                                    <i class="bi bi-trash"></i>Hapus SubKegiatan</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>

                                    <?php } ?>
                            <?php }
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