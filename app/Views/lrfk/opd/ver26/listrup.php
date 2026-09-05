<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<?php

use App\Models\LrfkProvModel\TaRealisasiRinciModel;
use App\Models\LrfkProvModel\SubKegModel; // as lrfkModel;

$this->realisasilrfkrinci = new TaRealisasiRinciModel();
$this->subkegmodel = new SubKegModel();

?>
<!--begin::Container-->
<div class="container-fluid">
    <div class="card-header">
        <h3 class="card-title">Dashboard Data Rencana Umum Pengadaan : <?= esc($datauser['sub_unit']);  ?></h3>
    </div>
    <div class="row">
        <div class="col-12 col-sm-6 col-md-6">
            <div class="info-box">
                <span class="info-box-icon text-bg-success shadow-sm">
                    <i class="bi bi-cart-fill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Jumlah Paket RUP</span>
                    <span class="info-box-number"><?= esc($datajmlrupopd['jumlah_paket'] ?? 0) ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-6">
            <div class="info-box">
                <span class="info-box-icon text-bg-warning shadow-sm">
                    <i class="bi bi-people-fill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Jumlah Anggaran Paket</span>
                    <span class="info-box-number"><?= esc(number_format($datajmlrupopd['total_anggaran'] ?? 0, 0, ',', '.')) ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-primary shadow-sm">
                    <i class="bi bi-gear-fill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Jumlah Paket PDN</span>
                    <span class="info-box-number">
                        <?= esc($datajmlrupopdPDN['jumlah_paket'] ?? 0) ?>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-danger shadow-sm">
                    <i class="bi bi-hand-thumbs-up-fill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Jumlah Paket Non PDN</span>
                    <span class="info-box-number"><?= esc($datajmlrupopdNonPDN['jumlah_paket'] ?? 0) ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <!-- fix for small devices only -->
        <!-- <div class="clearfix hidden-md-up"></div> -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-success shadow-sm">
                    <i class="bi bi-cart-fill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Anggaran Paket PDN</span>
                    <span class="info-box-number"><?= esc(number_format($datajmlrupopdPDN['total_anggaran'] ?? 0, 0, ',', '.')) ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-warning shadow-sm">
                    <i class="bi bi-people-fill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Anggaran Paket Non PDN</span>
                    <span class="info-box-number"><?= esc(number_format($datajmlrupopdNonPDN['total_anggaran'] ?? 0, 0, ',', '.')) ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-primary shadow-sm">
                    <i class="bi bi-gear-fill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Jumlah Paket Penyedia</span>
                    <span class="info-box-number">
                        <?= esc($datajmlrupopdP['jumlah_paket'] ?? 0) ?>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-danger shadow-sm">
                    <i class="bi bi-hand-thumbs-up-fill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Jumlah Paket Swakelola</span>
                    <span class="info-box-number"><?= esc($datajmlrupopdS['jumlah_paket'] ?? 0) ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <!-- fix for small devices only -->
        <!-- <div class="clearfix hidden-md-up"></div> -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-success shadow-sm">
                    <i class="bi bi-cart-fill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Anggaran Paket Penyedia</span>
                    <span class="info-box-number"><?= esc(number_format($datajmlrupopdP['total_anggaran'] ?? 0, 0, ',', '.')) ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-warning shadow-sm">
                    <i class="bi bi-people-fill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Anggaran Paket Swakelola</span>
                    <span class="info-box-number"><?= esc(number_format($datajmlrupopdS['total_anggaran'] ?? 0, 0, ',', '.')) ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <div class="info-box-content">
                    <span class="info-box-text">Jumlah Paket E-PURCHASING</span>
                    <span class="info-box-number">
                        <?= esc($datajmlrupopdE['jumlah_paket'] ?? 0) ?>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <div class="info-box-content">
                    <span class="info-box-text">Paket Pengadaan Langsung</span>
                    <span class="info-box-number"><?= esc($datajmlrupopdPL['jumlah_paket'] ?? 0) ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <!-- fix for small devices only -->
        <!-- <div class="clearfix hidden-md-up"></div> -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">

                <div class="info-box-content">
                    <span class="info-box-text">Anggaran Paket E-PURCHASING</span>
                    <span class="info-box-number"><?= esc(number_format($datajmlrupopdE['total_anggaran'] ?? 0, 0, ',', '.')) ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">

                <div class="info-box-content">
                    <span class="info-box-text">Anggaran Pengadaan Langsung</span>
                    <span class="info-box-number"><?= esc(number_format($datajmlrupopdPL['total_anggaran'] ?? 0, 0, ',', '.')) ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <div class="info-box-content">
                    <span class="info-box-text">Paket Penunjukan Langsung</span>
                    <span class="info-box-number">
                        <?= esc($datajmlrupopdTunjuk['jumlah_paket'] ?? 0) ?>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <div class="info-box-content">
                    <span class="info-box-text">Paket Dikecualikan</span>
                    <span class="info-box-number"><?= esc($datajmlrupopdKecuali['jumlah_paket'] ?? 0) ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <!-- fix for small devices only -->
        <!-- <div class="clearfix hidden-md-up"></div> -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">

                <div class="info-box-content">
                    <span class="info-box-text">Anggaran Penunjukan Langsung</span>
                    <span class="info-box-number"><?= esc(number_format($datajmlrupopdTunjuk['total_anggaran'] ?? 0, 0, ',', '.')) ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">

                <div class="info-box-content">
                    <span class="info-box-text">Anggaran Paket Dikecualikan</span>
                    <span class="info-box-number"><?= esc(number_format($datajmlrupopdKecuali['total_anggaran'] ?? 0, 0, ',', '.')) ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <div class="info-box-content">
                    <span class="info-box-text">Jumlah Paket Tender</span>
                    <span class="info-box-number">
                        <?= esc($datajmlrupopdTender['jumlah_paket'] ?? 0) ?>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <div class="info-box-content">
                    <span class="info-box-text">Jumlah Paket Seleksi</span>
                    <span class="info-box-number"><?= esc($datajmlrupopdSeleksi['jumlah_paket'] ?? 0) ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <!-- fix for small devices only -->
        <!-- <div class="clearfix hidden-md-up"></div> -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">

                <div class="info-box-content">
                    <span class="info-box-text">Anggaran Paket Tender</span>
                    <span class="info-box-number"><?= esc(number_format($datajmlrupopdTender['total_anggaran'] ?? 0, 0, ',', '.')) ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">

                <div class="info-box-content">
                    <span class="info-box-text">Anggaran Paket Seleksi</span>
                    <span class="info-box-number"><?= esc(number_format($datajmlrupopdSeleksi['total_anggaran'] ?? 0, 0, ',', '.')) ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    <!-- /.col-md-6 -->
    <div class="row">
        <?php if (session()->getFlashdata('message')): ?>
            <div class="alert alert-primary" role="alert">
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
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Daftar Rencana Umum Pengadaan (RUP) dengan Cara Pengadaan Melalui PENYEDIA</h3>
                </div>
                <div class="card-body table-responsive p-0" style="height: 300px;">
                    <table class="table table-head-fixed text-wrap">
                        <thead>
                            <tr>
                                <th style="width: 5px">#</th>
                                <th>Kode RUP</th>
                                <th>Metode Pengadaan</th>
                                <th>Nama Paket</th>
                                <th>Anggaran Paket (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($datarupopdpenyedia as $key => $value) { ?>

                                <tr class="align-middle">
                                    <td><?= esc($key + 1)  ?></td>
                                    <td><?= esc($value['Kode_RUP'])  ?>
                                    </td>
                                    <td>
                                        <?= esc($value['Metode_Pengadaan'])  ?>
                                    </td>
                                    <td>
                                        <?= esc($value['Nama_Paket'])  ?>

                                    </td>
                                    <td>
                                        <?= esc(number_format($value['Total_Nilai'], 0, ',', '.'))  ?>


                                    </td>
                                </tr>
                            <?php } ?>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="width: 5px">#</th>
                                <th>Jumlah</th>
                                <th>
                                </th>
                                <th>
                                </th>

                                <th>
                                    <?php
                                    $jumlahAnggaran = array_sum(array_column($datarupopdpenyedia, 'Total_Nilai'));
                                    echo esc(number_format($jumlahAnggaran, 0, ',', '.'));
                                    ?>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-footer">
                    <span class="text-muted">
                        <i class=" bi bi-info-circle-fill"></i>Data RUP ini
                        berdasarkan https://data.inaproc.id TA <?= esc($tahunaktif) ?>.** </span>
                </div>
            </div>
        </div>
        <!--end::Row-->
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Daftar Rencana Umum Pengadaan (RUP) dengan Cara Pengadaan Melalui SWAKELOLA</h3>
                </div>
                <div class="card-body table-responsive p-0" style="height: 300px;">
                    <table class="table table-head-fixed text-wrap">
                        <thead>
                            <tr>
                                <th style="width: 5px">#</th>
                                <th>Kode RUP</th>
                                <th>Metode Pengadaan</th>
                                <th>Nama Paket</th>
                                <th>Anggaran Paket (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($datarupopdswakelola as $key => $valuesw) { ?>

                                <tr class="align-middle">
                                    <td><?= esc($key + 1)  ?></td>
                                    <td><?= esc($valuesw['Kode_RUP'])  ?>
                                    </td>
                                    <td>
                                        <?= esc($valuesw['Metode_Pengadaan'])  ?>
                                    </td>
                                    <td>
                                        <?= esc($valuesw['Nama_Paket'])  ?>

                                    </td>
                                    <td>
                                        <?= esc(number_format($valuesw['Total_Nilai'], 0, ',', '.'))  ?>


                                    </td>
                                </tr>
                            <?php } ?>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="width: 5px">#</th>
                                <th>Jumlah</th>
                                <th>
                                </th>
                                <th>
                                </th>

                                <th>
                                    <?php
                                    $jumlahAnggaransw = array_sum(array_column($datarupopdswakelola, 'Total_Nilai'));
                                    echo esc(number_format($jumlahAnggaransw, 0, ',', '.'));
                                    ?>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-footer">
                    <span class="text-muted">
                        <i class=" bi bi-info-circle-fill"></i>Data RUP ini
                        berdasarkan https://data.inaproc.id TA <?= esc($tahunaktif) ?>.** </span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">E-Purchasing</h3>
                </div>
                <div class="card-body table-responsive p-0" style="height: 300px;">
                    <table class="table table-head-fixed text-wrap">
                        <thead>
                            <tr>
                                <th style="width: 5px">#</th>
                                <th>Kode RUP</th>
                                <th>Metode Pengadaan</th>
                                <th>Nama Paket</th>
                                <th>Anggaran Paket (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($datarupopdkatalog as $key => $valueka) { ?>

                                <tr class="align-middle">
                                    <td><?= esc($key + 1)  ?></td>
                                    <td><?= esc($valueka['Kode_RUP'])  ?>
                                    </td>
                                    <td>
                                        <?= esc($valueka['Metode_Pengadaan'])  ?>
                                    </td>
                                    <td>
                                        <?= esc($valueka['Nama_Paket'])  ?>

                                    </td>
                                    <td>
                                        <?= esc(number_format($valueka['Total_Nilai'], 0, ',', '.'))  ?>


                                    </td>
                                </tr>
                            <?php } ?>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="width: 5px">#</th>
                                <th>Jumlah</th>
                                <th>
                                </th>
                                <th>
                                </th>

                                <th>
                                    <?php
                                    $jumlahAnggaranka = array_sum(array_column($datarupopdkatalog, 'Total_Nilai'));
                                    echo esc(number_format($jumlahAnggaranka, 0, ',', '.'));
                                    ?>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-footer">
                    <span class="text-muted">
                        <i class=" bi bi-info-circle-fill"></i>Data RUP ini
                        berdasarkan https://data.inaproc.id TA <?= esc($tahunaktif) ?>.** </span>
                </div>
            </div>
        </div>
        <!--end::Row-->
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Pengadaan Langsung</h3>
                </div>
                <div class="card-body table-responsive p-0" style="height: 300px;">
                    <table class="table table-head-fixed text-wrap">
                        <thead>
                            <tr>
                                <th style="width: 5px">#</th>
                                <th>Kode RUP</th>
                                <th>Metode Pengadaan</th>
                                <th>Nama Paket</th>
                                <th>Anggaran Paket (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($datarupopdpl as $key => $valuepl) { ?>

                                <tr class="align-middle">
                                    <td><?= esc($key + 1)  ?></td>
                                    <td><?= esc($valuepl['Kode_RUP'])  ?>
                                    </td>
                                    <td>
                                        <?= esc($valuepl['Metode_Pengadaan'])  ?>
                                    </td>
                                    <td>
                                        <?= esc($valuepl['Nama_Paket'])  ?>

                                    </td>
                                    <td>
                                        <?= esc(number_format($valuepl['Total_Nilai'], 0, ',', '.'))  ?>


                                    </td>
                                </tr>
                            <?php } ?>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="width: 5px">#</th>
                                <th>Jumlah</th>
                                <th>
                                </th>
                                <th>
                                </th>

                                <th>
                                    <?php
                                    $jumlahAnggaranspl = array_sum(array_column($datarupopdpl, 'Total_Nilai'));
                                    echo esc(number_format($jumlahAnggaranspl, 0, ',', '.'));
                                    ?>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-footer">
                    <span class="text-muted">
                        <i class=" bi bi-info-circle-fill"></i>Data RUP ini
                        berdasarkan https://data.inaproc.id TA <?= esc($tahunaktif) ?>.** </span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Penunjukan Langsung</h3>
                </div>
                <div class="card-body table-responsive p-0" style="height: 300px;">
                    <table class="table table-head-fixed text-wrap">
                        <thead>
                            <tr>
                                <th style="width: 5px">#</th>
                                <th>Kode RUP</th>
                                <th>Metode Pengadaan</th>
                                <th>Nama Paket</th>
                                <th>Anggaran Paket (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($datarupopdtunjuk as $key => $valuetunjuk) { ?>

                                <tr class="align-middle">
                                    <td><?= esc($key + 1)  ?></td>
                                    <td><?= esc($valuetunjuk['Kode_RUP'])  ?>
                                    </td>
                                    <td>
                                        <?= esc($valuetunjuk['Metode_Pengadaan'])  ?>
                                    </td>
                                    <td>
                                        <?= esc($valuetunjuk['Nama_Paket'])  ?>

                                    </td>
                                    <td>
                                        <?= esc(number_format($valuetunjuk['Total_Nilai'], 0, ',', '.'))  ?>


                                    </td>
                                </tr>
                            <?php } ?>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="width: 5px">#</th>
                                <th>Jumlah</th>
                                <th>
                                </th>
                                <th>
                                </th>

                                <th>
                                    <?php
                                    $jumlahAnggarantunjuk = array_sum(array_column($datarupopdtunjuk, 'Total_Nilai'));
                                    echo esc(number_format($jumlahAnggarantunjuk, 0, ',', '.'));
                                    ?>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-footer">
                    <span class="text-muted">
                        <i class=" bi bi-info-circle-fill"></i>Data RUP ini
                        berdasarkan https://data.inaproc.id TA <?= esc($tahunaktif) ?>.** </span>
                </div>
            </div>
        </div>
        <!--end::Row-->
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Dikecualikan</h3>
                </div>
                <div class="card-body table-responsive p-0" style="height: 300px;">
                    <table class="table table-head-fixed text-wrap">
                        <thead>
                            <tr>
                                <th style="width: 5px">#</th>
                                <th>Kode RUP</th>
                                <th>Metode Pengadaan</th>
                                <th>Nama Paket</th>
                                <th>Anggaran Paket (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($datarupopdkecuali as $key => $valuekecuali) { ?>

                                <tr class="align-middle">
                                    <td><?= esc($key + 1)  ?></td>
                                    <td><?= esc($valuekecuali['Kode_RUP'])  ?>
                                    </td>
                                    <td>
                                        <?= esc($valuekecuali['Metode_Pengadaan'])  ?>
                                    </td>
                                    <td>
                                        <?= esc($valuekecuali['Nama_Paket'])  ?>

                                    </td>
                                    <td>
                                        <?= esc(number_format($valuekecuali['Total_Nilai'], 0, ',', '.'))  ?>


                                    </td>
                                </tr>
                            <?php } ?>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="width: 5px">#</th>
                                <th>Jumlah</th>
                                <th>
                                </th>
                                <th>
                                </th>

                                <th>
                                    <?php
                                    $jumlahAnggaranskecuali = array_sum(array_column($datarupopdkecuali, 'Total_Nilai'));
                                    echo esc(number_format($jumlahAnggaranskecuali, 0, ',', '.'));
                                    ?>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-footer">
                    <span class="text-muted">
                        <i class=" bi bi-info-circle-fill"></i>Data RUP ini
                        berdasarkan https://data.inaproc.id TA <?= esc($tahunaktif) ?>.** </span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Tender</h3>
                </div>
                <div class="card-body table-responsive p-0" style="height: 300px;">
                    <table class="table table-head-fixed text-wrap">
                        <thead>
                            <tr>
                                <th style="width: 5px">#</th>
                                <th>Kode RUP</th>
                                <th>Metode Pengadaan</th>
                                <th>Nama Paket</th>
                                <th>Anggaran Paket (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($datarupopdtender as $key => $valuetender) { ?>

                                <tr class="align-middle">
                                    <td><?= esc($key + 1)  ?></td>
                                    <td><?= esc($valuetender['Kode_RUP'])  ?>
                                    </td>
                                    <td>
                                        <?= esc($valuetender['Metode_Pengadaan'])  ?>
                                    </td>
                                    <td>
                                        <?= esc($valuetender['Nama_Paket'])  ?>

                                    </td>
                                    <td>
                                        <?= esc(number_format($valuetender['Total_Nilai'], 0, ',', '.'))  ?>


                                    </td>
                                </tr>
                            <?php } ?>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="width: 5px">#</th>
                                <th>Jumlah</th>
                                <th>
                                </th>
                                <th>
                                </th>

                                <th>
                                    <?php
                                    $jumlahAnggarantender = array_sum(array_column($datarupopdtender, 'Total_Nilai'));
                                    echo esc(number_format($jumlahAnggarantender, 0, ',', '.'));
                                    ?>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-footer">
                    <span class="text-muted">
                        <i class=" bi bi-info-circle-fill"></i>Data RUP ini
                        berdasarkan https://data.inaproc.id TA <?= esc($tahunaktif) ?>.** </span>
                </div>
            </div>
        </div>
        <!--end::Row-->
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Seleksi</h3>
                </div>
                <div class="card-body table-responsive p-0" style="height: 300px;">
                    <table class="table table-head-fixed text-wrap">
                        <thead>
                            <tr>
                                <th style="width: 5px">#</th>
                                <th>Kode RUP</th>
                                <th>Metode Pengadaan</th>
                                <th>Nama Paket</th>
                                <th>Anggaran Paket (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($datarupopdseleksi as $key => $valueseleksi) { ?>

                                <tr class="align-middle">
                                    <td><?= esc($key + 1)  ?></td>
                                    <td><?= esc($valueseleksi['Kode_RUP'])  ?>
                                    </td>
                                    <td>
                                        <?= esc($valueseleksi['Metode_Pengadaan'])  ?>
                                    </td>
                                    <td>
                                        <?= esc($valueseleksi['Nama_Paket'])  ?>

                                    </td>
                                    <td>
                                        <?= esc(number_format($valueseleksi['Total_Nilai'], 0, ',', '.'))  ?>


                                    </td>
                                </tr>
                            <?php } ?>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="width: 5px">#</th>
                                <th>Jumlah</th>
                                <th>
                                </th>
                                <th>
                                </th>

                                <th>
                                    <?php
                                    $jumlahAnggaransseleksi = array_sum(array_column($datarupopdseleksi, 'Total_Nilai'));
                                    echo esc(number_format($jumlahAnggaransseleksi, 0, ',', '.'));
                                    ?>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-footer">
                    <span class="text-muted">
                        <i class=" bi bi-info-circle-fill"></i>Data RUP ini
                        berdasarkan https://data.inaproc.id TA <?= esc($tahunaktif) ?>.** </span>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>