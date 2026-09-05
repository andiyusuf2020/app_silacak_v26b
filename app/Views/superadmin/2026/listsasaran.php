<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>

<?php

use App\Models\DataApbdModel\RealApbdModel;

use App\Models\DataApbdModel\AngkasModel;

$this->realapbd = new RealApbdModel();
$this->angkasmodel = new AngkasModel();
?>
<!--begin::Container-->
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Data Anggaran Kas Perangkat Daerah Provinsi Lampung </h3> <br>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0" style="height: 600px;">
                <table class="table table align-middle table-head-fixed table-success table-striped table-bordered text-wrap">
                    <thead>
                        <tr class="align-bottom">
                            <th style="width: 10px">#</th>
                            <th>Misi Gubernur dan Wakil Gubernur Lampung</th>
                            <th>Nama Sasaran RPJMD</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($dataprogprioritas as $key => $value) { ?>
                            <tr class="align-bottom">
                                <td><?= esc($key + 1) ?></td>
                                <td><strong><?= esc($value['misi']) ?></strong>
                                <td>
                                    <strong><?= esc($value['nm_progprioritas']) ?></strong>
                                </td>
                                </td>
                                <td>
                                    <a href="<?= hash_url('adminprov/capkin/', [
                                                    'hal' => 'rekapcapkin',
                                                    'action' => 'persasaran',
                                                    'idSasaran' => $value['id_pprio'],
                                                    // 'kdSK' => $value['KODE_SUB_GIAT']
                                                    // 'kdU' => $value['kd_urusan']
                                                ]);
                                                ?>">
                                        <button type="button" class="btn btn-outline-primary mb-2">Details</button>
                                    </a>
                                </td>
                            </tr>
                        <?php }  ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>
<!--end::Container-->

<?= $this->endSection() ?>