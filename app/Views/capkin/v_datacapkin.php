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

?>
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
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
                                    <?php if ($cekopdunggulan) { ?>
                                        <tr class="align-middle">
                                            <td>2.</td>
                                            <td>Program Unggulan</td>
                                            <td>
                                                <strong><?= esc($jmlmappingunggulan) ?> Aktifitas</strong>
                                                <strong><?= esc($jmlsubkegtermapping); ?> Subkegiatan </strong>
                                            </td>
                                            <td><strong><?= esc($unggulanterupdete) ?></strong>
                                            </td>
                                        </tr>
                                    <?php } else { ?>
                                    <?php } ?>
                                    <?php if ($cekopdtematik) { ?>
                                        <tr class="align-middle">
                                            <td>3.</td>
                                            <td>Program Tematik Pembangunan</td>
                                            <td>
                                                <strong><?= esc($jmlmappingtematik) ?> Aktifitas</strong></li>
                                                <strong><?= esc($jmlsubkegtermapping); ?> Subkegiatan </strong>
                                            </td>
                                            <td><strong><?= esc($tematikterupdete) ?></strong>
                                            </td>
                                        </tr>
                                    <?php } else { ?>
                                    <?php } ?>

                                </tbody>
                            </table>
                        </ul>
                    </div>
                </div>

                <div class="card-header">
                    <a
                        href="<?= hash_url('capkin', ['page' => 'data', 'action' => 'tambah']);
                                ?>" data-bs-toggle="tooltip" data-bs-title="Pilih SubKegiatan yang akan dimapping">
                        <button type="button" class="btn btn-outline-primary">
                            <i class="bi bi-plus-circle"></i>TAMBAH SUBKEGIATAN</button>

                    </a>
                </div>
                <!-- /.card-header -->
                <!-- <div class="card-body"> -->
                <div class="card-body table-responsive p-1" style="height: 700px;">
                    <table class="table table-bordered text-wrap" style="border: black;">
                        <!-- <table id="example1" class="table table-head-fixed table-bordered table-striped text-wrap" style="border: black;"> -->
                        <thead>
                            <tr style="text-align: center;">
                                <th rowspan="2" style="text-align: left;">Program/Kegiatan/SubKegiatan<br>Pagu Anggaran/Realisasi s.d <?= esc($bulan) ?></th>
                                <th colspan="4">Kegiatan/Aktifitas Pokok SubKegiatan</th>
                                <th rowspan="2">Dukungan Terhadap RPJMD</th>
                                <th colspan="2">Realisasi, Dokumentasi dan Lokasi Kegiatan/Aktifitas Pokok Subkegiatan s.d Bulan <?= esc($bulan) ?></th>

                            </tr>
                            <tr style="text-align: center;">
                                <th>Sasaran/Target <?= esc($tahun) ?></th>
                                <th>Uraian Target Pelaksanaan Aktivitas/Kegiatan <?= esc($tahun) ?>
                                </th>
                                <th>Hasil yang akan dicapai s.d akhir <?= esc($tahun) ?>
                                </th>
                                <th>#</th>
                                <th>Realisasi Target Pelaksanaan Aktivitas/Kegiatan s.d Bulan <?= esc($bulan) ?></th>
                                <th>Uraian Progres Pelaksanaan Realisasi Aktivitas/Kegiatan s.d Bulan <?= esc($bulan) ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($dataskcapkinopd as $key => $dataskcapkin) { ?>
                                <tr>
                                    <td colspan="8">
                                        <?= esc($dataskcapkin['kd_program'])  ?>/
                                        <?= esc($dataskcapkin['kd_kegiatan'])  ?>/
                                        <?= esc($dataskcapkin['kd_subkegiatan'])  ?>/
                                        <?= esc($dataskcapkin['nm_subkegiatan'])  ?>
                                        <!-- <?php // esc($dataskcapkin['id'])  
                                                ?> -->
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
                                                        href="<?= hash_url('capkin/kegpokok', [
                                                                    'page' => 'kegpokok',
                                                                    'action' => 'tambah',
                                                                    'idSK' => $dataskcapkin['id_sk']
                                                                ]);
                                                                ?>">
                                                        <i class="bi bi-plus-circle"></i>Tambah Keg.Pokok</a></li>

                                                <li><a class="dropdown-item"
                                                        href="<?= hash_url('capkin/kegpokok/', [
                                                                    'page' => 'kegpokok',
                                                                    'action' => 'ubahsubkeg',
                                                                    'idUbah' => $dataskcapkin['id_sk']
                                                                ]);
                                                                ?>">
                                                        <i class="bi bi-plus-circle"></i>Ubah SubKegiatan</a></li>
                                                <li><a class="dropdown-item"
                                                        href="<?= hash_url('capkin/kegpokok/', [
                                                                    'page' => 'kegpokok',
                                                                    'action' => 'hapussubkeg',
                                                                    'idHapus' => $dataskcapkin['id_sk']
                                                                ]);
                                                                ?>">
                                                        <i class="bi bi-trash"></i>Hapus SubKegiatan</a></li>
                                            </ul>
                                        </div>
                                        <table class="table table-bordered">
                                            <tr>
                                                <td style="width: 200px">Pagu Anggaran:</td>
                                                <td style="width: 100px"><?= number_format(esc($dataskcapkin['pagu']), 0, ',', '.')  ?></td>
                                                <td style="width: 200px">Realisasi s/d Bulan <?= esc($bulan) ?></td>
                                                <td>
                                                    <?php
                                                    $datarealisasiSK = $this->realisasilrfkrinci->select('*')
                                                        ->selectSum('realisasi')
                                                        //->selectSum('pagu_rincian')
                                                        ->where('tahun', $tahunaktif)
                                                        ->where('bulan', $bulan)
                                                        ->where('kd_sub_unit', $dataskcapkin['kd_subunit'])
                                                        // ->where('kd_urusan', $value['kd_urusan'])
                                                        ->where('kd_program', $dataskcapkin['kd_program'])
                                                        ->where('kd_kegiatan', $dataskcapkin['kd_kegiatan'])
                                                        ->where('kd_subkegiatan', $dataskcapkin['kd_subkegiatan'])
                                                        ->groupBy('kd_subkegiatan')
                                                        ->where('delete_at=', 0)
                                                        ->get()
                                                        ->getRowArray();
                                                    // echo dd($datarealisasiK);
                                                    if (!$datarealisasiSK) {
                                                        echo esc('Rp0');
                                                    } else {

                                                        $paguSK = $dataskcapkin['pagu'];
                                                        $realSK = $datarealisasiSK['realisasi'] ?? null;
                                                        $persentaseSK = ($realSK / $paguSK) * 100 ?? NUll;
                                                        if ($realSK > $paguSK) {
                                                            // echo $paguSK . '///' . $realSK;
                                                            echo "Realisasi bulan ini melebihi pagu";
                                                        } else {
                                                            echo esc(number_format($realSK, 0, ',', '.'));
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <?php
                                $datakegpokok = $this->kegpokokmodal
                                    ->select('ta_kegpokok_capkin_apbd2.*')
                                    ->select('ta_subkeg_capkin_apbd.nm_subkegiatan')
                                    ->select('ta_mprog_prioritas.nm_progprioritas')
                                    // ->select('ta_mprog_unggulan.nm_progunggulan')

                                    ->join('ta_subkeg_capkin_apbd', 'ta_subkeg_capkin_apbd.id_sk=ta_kegpokok_capkin_apbd2.id_targetsubkeg')
                                    ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_kegpokok_capkin_apbd2.id_progprioritas')
                                    // ->join('ta_mprog_unggulan', 'ta_mprog_unggulan.id_pung=ta_kegpokok_capkin_apbd2.id_progunggulan')

                                    ->where('ta_kegpokok_capkin_apbd2.id_targetsubkeg', $dataskcapkin['id_sk'])
                                    ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
                                    ->get()
                                    ->getResultArray();
                                foreach ($datakegpokok as $key => $datapokok) { ?>
                                    <?php
                                    $cekdatakp = $this->kegpokokmodal->DataPerIdKegPokok($datapokok['id_kp']);
                                    if ($cekdatakp['id_progunggulan'] == '0') {
                                        $nm_progunggulan = '';
                                    } else {
                                        $dataprogunggulan = $this->kegpokokmodal->DataPerIdKegPokokLengkap($datapokok['id_kp']);
                                        $nm_progunggulan = $dataprogunggulan['nm_progunggulan'];
                                    }
                                    if ($cekdatakp['id_progtematik'] == '0') {
                                        $nm_tematik = '';
                                    } else {
                                        $datatematik = $this->kegpokokmodal->DataPerIdKegPokokLengkap2($datapokok['id_kp']);
                                        $nm_tematik = $datatematik['nm_tematik'];
                                    }
                                    ?>
                                    <tr>
                                        <td></td>

                                        <td style="background-color: bisque;">
                                            <?= esc($datapokok['vol_target']) . '<br> ' . esc($datapokok['sat_target']); ?>
                                        </td>
                                        <td style="background-color: bisque;">
                                            <?= esc($datapokok['uraian_target']); ?>
                                        </td>
                                        <td style="background-color: bisque;">
                                            <?= esc($datapokok['hasil']); ?>
                                        </td>
                                        <td style="background-color: bisque;">
                                            <div class="btn-group">
                                                <button
                                                    type="button"
                                                    class="btn btn dropdown-toggle"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="bi bi-menu-button-wide-fill"></i>
                                                    <a href="#" data-bs-toggle="tooltip"
                                                        data-bs-title="menu ini utk edit,hapus dan tambah data aktivitas">
                                                        <i class=" bi bi-emoji-sunglasses"></i></a>
                                                </button>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item"
                                                            href="<?= hash_url('capkin/kegpokok', [
                                                                        'page' => 'kegpokok',
                                                                        'action' => 'editkegpokok',
                                                                        'idKP' => $datapokok['id_kp']
                                                                    ]);
                                                                    ?>">
                                                            <i class="bi bi-clipboard2-check"></i>Edit Keg.Pokok</a></li>

                                                    <li><a class="dropdown-item"
                                                            href="<?= hash_url('capkin/kegpokok', [
                                                                        'page' => 'kegpokok',
                                                                        'action' => 'hapuskegpokok',
                                                                        'idKP' => $datapokok['id_kp']
                                                                    ]);
                                                                    ?>">
                                                            <i class="bi bi-trash"></i>Hapus Keg.Pokok</a></li>
                                                    <li><a class="dropdown-item"
                                                            href="<?= hash_url('capkin/kegpokok', [
                                                                        'page' => 'kegpokok',
                                                                        'action' => 'tambah',
                                                                        'idSK' => $dataskcapkin['id_sk']
                                                                    ]);
                                                                    ?>">
                                                            <i class="bi bi-plus-circle"></i>Tambah Keg.Pokok</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td style="background-color: bisque;" colspan="3">
                                            <p style="color: blue;">
                                                Aktifitas ini mendukung Sasaran Pembangunan :
                                                <strong><?= esc($datapokok['nm_progprioritas']); ?></strong>
                                                <?php
                                                if ($nm_progunggulan) { ?>
                                                    <br>Aktifitas ini mendukung Program Unggulan :
                                                    <strong> <?= esc($nm_progunggulan); ?></strong>
                                                <?php }
                                                ?>
                                                <?php
                                                if ($nm_tematik) { ?>
                                                    <br>Aktifitas ini mendukung TEMATIK Program :
                                                    <strong> <?= esc($nm_tematik); ?></strong>
                                                <?php }
                                                ?>
                                            </p>
                                        </td>
                                    </tr>
                                    <?php
                                    $dataRKP = $this->rkegpokokmodal->DataPerKegPokok($datapokok['id_kp']);
                                    if (empty($dataRKP)) { ?>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td style="background-color: azure;" colspan="2">
                                                <div class="btn-group">
                                                    <button
                                                        type="button"
                                                        class="btn btn dropdown-toggle"
                                                        data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <i class="bi bi-menu-button-wide-fill"></i>Menu Input Realisasi Aktifitas
                                                        <a href="#" data-bs-toggle="tooltip"
                                                            data-bs-title="menu ini utk edit,hapus dan tambah data realisasi aktivitas">
                                                            <i class=" bi bi-emoji-sunglasses"></i></a>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item"
                                                                href="<?= hash_url('capkin/kegpokok', [
                                                                            'page' => 'realisasikegpokok',
                                                                            'action' => 'tambah',
                                                                            'idKP' => $datapokok['id_kp']
                                                                        ]);
                                                                        ?>">
                                                                <i class="bi bi-plus-circle"></i>Realisasi Keg.Pokok</a></li>

                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } else { ?>
                                        <?php
                                        foreach ($dataRKP as $key => $rperkp) { ?>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td style="background-color: azure;">
                                                    <?php
                                                    // echo esc($dataRKP['r_target']) . '<br>' . esc($dataRKP['sat_target'])
                                                    echo esc($rperkp['r_target']) . '<br>' . esc($rperkp['sat_target']);
                                                    // echo esc($rperkp['id_r']);
                                                    ?>
                                                </td>
                                                <?php
                                                // $dataRKP = $this->rkegpokokmodal->DataPerKegPokok($datapokok['id_kp']);
                                                if (empty($dataRKP)) { ?>

                                                <?php } else { ?>
                                                    <td style="background-color: azure; ">
                                                        <div class="btn-group">
                                                            <button
                                                                type="button"
                                                                class="btn btn dropdown-toggle"
                                                                data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="bi bi-menu-button-wide-fill"></i>
                                                                <a href="#" data-bs-toggle="tooltip"
                                                                    data-bs-title="menu ini utk edit,hapus dan tambah data realisasi aktivitas">
                                                                    <i class=" bi bi-emoji-sunglasses"></i></a>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item"
                                                                        href="<?= hash_url('capkin/kegpokok', [
                                                                                    'page' => 'realisasikegpokok',
                                                                                    'action' => 'tambah',
                                                                                    'idKP' => $datapokok['id_kp']
                                                                                ]);
                                                                                ?>">
                                                                        <i class="bi bi-plus-circle"></i>Tambah Realisasi Keg.Pokok</a></li>

                                                                <li><a class="dropdown-item"
                                                                        href="<?= hash_url('capkin/kegpokok', [
                                                                                    'page' => 'realisasikegpokok',
                                                                                    'action' => 'edit',
                                                                                    'idRKP' => $rperkp['id_r']
                                                                                ]);
                                                                                ?>">
                                                                        <i class="bi bi-clipboard2-check"></i>Edit Realisasi</a></li>
                                                                <li><a class="dropdown-item"
                                                                        href="<?= hash_url('capkin/kegpokok', [
                                                                                    'page' => 'realisasikegpokok',
                                                                                    'action' => 'hapus',
                                                                                    'idRKP' => $rperkp['id_r']
                                                                                ]);
                                                                                ?>">
                                                                        <i class="bi bi-trash"></i>Hapus Realisasi</a></li>

                                                            </ul>
                                                        </div>
                                                        <?= esc('Realisasi ini di Bulan ' . $rperkp['bulan']) ?>

                                                    <?php
                                                }
                                                    ?><br>
                                                    <?= esc($rperkp['r_uraian']) ?>


                                                    </td>

                                            </tr>
                                            <?php
                                            $dataDRKPX = $this->rdkegpokokmodal->DataPerRKegPokok($rperkp['id_r'] ?? null);
                                            if (empty($dataDRKPX)) { ?>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="background-color: azure;" colspan="2">
                                                        <div class="btn-group">
                                                            <button
                                                                type="button"
                                                                class="btn btn dropdown-toggle"
                                                                data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="bi bi-menu-button-wide-fill">Menu Input Dokumentasi AKtifitas</i>
                                                                <a href="#" data-bs-toggle="tooltip"
                                                                    data-bs-title="menu ini utk input,edit dan tambah Eviden Base aktifitas diatas">
                                                                    <i class=" bi bi-emoji-sunglasses"></i></a>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <?php if (empty($rperkp['id_r'])) { ?>
                                                                <?php } else { ?>
                                                                    <?php
                                                                    $dataDRKP = $this->rdkegpokokmodal->DataPerRKegPokok($rperkp['id_r']);
                                                                    if (empty($dataDRKP)) { ?>

                                                                        <li><a class="dropdown-item"
                                                                                href="<?= hash_url('capkin/kegpokok', [
                                                                                            'page' => 'rdokumentasi',
                                                                                            'action' => 'tambah',
                                                                                            'idRKP' => $rperkp['id_r'] ?? null
                                                                                        ]);
                                                                                        ?>">
                                                                                <i class="bi bi-plus-circle"></i>Tambah Dokumentasi</a></li>
                                                                <?php  }
                                                                } ?>
                                                            </ul>
                                                    </td>
                                                </tr>
                                            <?php }
                                            ?>
                                            <?php
                                            foreach ($dataDRKPX as $key => $value) { ?>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="background-color: azure;" colspan="2">
                                                        <div class="btn-group">
                                                            <button
                                                                type="button"
                                                                class="btn btn dropdown-toggle"
                                                                data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="bi bi-menu-button-wide-fill"></i>Menu
                                                                <a href="#" data-bs-toggle="tooltip"
                                                                    data-bs-title="menu ini utk edit,hapus dan tambah data dokumentasi realisasi aktivitas">
                                                                    <i class=" bi bi-emoji-sunglasses"></i>
                                                                    <i class="bi bi-exclamation-lg"></i>
                                                                </a>
                                                            </button>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <?php if (empty($rperkp['id_r'])) { ?>

                                                                <?php } else { ?>
                                                                    <?php
                                                                    $dataDRKP = $this->rdkegpokokmodal->DataPerRKegPokok($rperkp['id_r']);
                                                                    if (empty($dataDRKP)) { ?>

                                                                        <li><a class="dropdown-item"
                                                                                href="<?= hash_url('capkin/kegpokok', [
                                                                                            'page' => 'rdokumentasi',
                                                                                            'action' => 'tambah',
                                                                                            'idRKP' => $rperkp['id_r'] ?? null
                                                                                        ]);
                                                                                        ?>">
                                                                                <i class="bi bi-plus-circle"></i>Tambah Dokumentasi</a></li>
                                                                    <?php  } else { ?>
                                                                        <li><a class="dropdown-item"
                                                                                href="<?= hash_url('capkin/kegpokok/', [
                                                                                            'page' => 'rdokumentasi',
                                                                                            'action' => 'edit',
                                                                                            'idDR' => $value['id_dr'] ?? null
                                                                                        ]);
                                                                                        ?>">
                                                                                <i class="bi bi-plus-circle"></i>Edit Dokumentasi</a></li>
                                                                        <li><a class="dropdown-item"
                                                                                href="<?= hash_url('capkin/kegpokok/', [
                                                                                            'page' => 'rdokumentasi',
                                                                                            'action' => 'hapus',
                                                                                            'idDR' => $value['id_dr']
                                                                                        ]);
                                                                                        ?>">
                                                                                <i class="bi bi-trash"></i>Hapus Dokumentasi</a></li>
                                                                        <li><a class="dropdown-item"
                                                                                href="<?= hash_url('capkin/kegpokok', [
                                                                                            'page' => 'rdokumentasi',
                                                                                            'action' => 'tambah',
                                                                                            'idRKP' => $rperkp['id_r'] ?? null
                                                                                        ]);
                                                                                        ?>">
                                                                                <i class="bi bi-plus-circle"></i>Tambah Dokumentasi</a></li>

                                                                <?php  }
                                                                } ?>
                                                            </ul>
                                                        </div>
                                                        <?php // esc('Dokumentasi Aktivitas Sampai dgn Bulan ' . $rperkp['bulan']) 
                                                        ?>

                                                        <br>
                                                        <?php
                                                        $dataDRKP = $this->rdkegpokokmodal->DataPerRKegPokok($rperkp['id_r'] ?? null);
                                                        if (empty($dataDRKP)) { ?>
                                                        <?php } else { ?>
                                                            <a href="<?= hash_url('capkin', [
                                                                            'idD' => $value['id_dr'] ?? null
                                                                        ]);
                                                                        ?>">
                                                                <i class="bi bi-eye-fill">Detail</i></a><br>
                                                            <img src="uploads/dokumentasi/<?= esc($value['gambar'] ?? null) ?>" width="150"><br>
                                                            Lokasi Aktifitas <br>
                                                            <?= esc($value['kabupaten']) ?>,<?= esc($value['kecamatan']) ?>, <?= esc($value['desa']) ?><br>
                                                            Titik Koordinat:
                                                            <?= esc($value['latitude']) ?>,<?= esc($value['longitude']) ?>
                                                        <?php } ?>
                                                        <br>

                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>

                                    <?php }
                                    }
                                    ?>
                                    </tr>
                                <?php
                                }  ?>
                            <?php
                            }
                            ?>


                        </tbody>

                        <!-- <tfoot>
                            <tr>
                                <th>Rendering engine</th>
                                <th>Browser</th>
                                <th>Platform(s)</th>
                                <th>Engine version</th>
                                <th>CSS grade</th>
                                <th>Engine version</th>
                                <th>CSS grade</th>
                                <th>Engine version</th>
                            </tr>
                        </tfoot> -->
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
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