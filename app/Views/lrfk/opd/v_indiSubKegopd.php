<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<?php

use App\Models\LrfkProvModel\TaIndikatorSubKegModal;
use SebastianBergmann\Type\NullType;

$this->taindisubkeg = new TaIndikatorSubKegModal();


?>
<!--begin::Container-->
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
        <div class="col-12">
            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Title</h3>
                    <div class="card-tools">
                        <button
                            type="button"
                            class="btn btn-tool"
                            data-lte-toggle="card-collapse"
                            title="Collapse">
                            <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                            <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                        </button>
                        <button
                            type="button"
                            class="btn btn-tool"
                            data-lte-toggle="card-remove"
                            title="Remove">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">Start creating your amazing application!</div>
                <!-- /.card-body -->
                <div class="card-footer">Footer</div>
                <!-- /.card-footer-->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <br>
    <br>
    <div class="card card-success card-outline mb-4">
        <!--begin::Header-->
        <div class="card-header">
            <div class="card-title">Pilih Data Sub Kegiatan yang akan diinput</div>
        </div>
        <?php if (session()->getFlashdata('message')): ?>
            <div class="alert alert-primary" role="alert">
                <ul>
                    <p><?= session()->getFlashdata('message') ?></p>

                    <button onclick="history.back()" class="btn btn-secondary">Kembali</button>
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

        <!--end::Header-->
        <!--begin::Body-->
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Nama SubKegiatan</th>
                        <th>Indikator Output Sub Kegiatan</th>
                        <th>URAIAN</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listsubkegiatan as $key => $rowsubkeg) { ?>
                        <tr class="align-middle">
                            <td><?= $key + 1 ?></td>
                            <td><?= $rowsubkeg['nm_subkegiatan'] ?></td>
                            <?php $rowindi = $this->taindisubkeg->listIndiPerProgperOpd(
                                $rowsubkeg['kd_subkegiatan'],
                                $rowsubkeg['kd_sub_unit']
                            );
                            if (!$rowindi) { ?>
                                <td></td>
                                <td></td>

                            <?php } else { ?>
                                <td><?= esc($rowindi['vol_indi'] . '  ' . $rowindi['satuan_indi']) ?></td>
                                <td><?= esc($rowindi['uraian']) ?></td>
                            <?php }
                            if (!$rowindi) { ?>
                                <td>
                                    <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                        <a href="<?= hash_url('lrfkopd/kinerja/', [
                                                        'page' => 'output',
                                                        'action' => 'input',
                                                        'kdSK' => $rowsubkeg['kd_subkegiatan'],
                                                        'kdSU' => $rowsubkeg['kd_sub_unit']
                                                    ]);
                                                    ?>" <button type="button" class="btn btn-outline-primary">Input</button></a>
                                    </div>
                                    <div
                                        class="btn-group mb-2"
                                        role="group"
                                        aria-label="Basic checkbox toggle button group">
                                </td> <?php } else { ?>
                                <td>
                                    <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                        <a href="<?= hash_url('lrfkopd/kinerja/', [
                                                        'page' => 'output',
                                                        'action' => 'ubah',
                                                        'kdI' => $rowindi['id'],
                                                        'kdSK' => $rowsubkeg['kd_subkegiatan'],
                                                        'kdSU' => $rowindi['kd_sub_unit']
                                                    ]);
                                                    ?>"
                                            <button type="button" class="btn btn-outline-primary">Ubah</button></a>
                                        <a href="<?= hash_url('lrfkopd/kinerja/', [
                                                        'page' => 'output',
                                                        'action' => 'hapus',
                                                        'kdI' => $rowindi['id'],
                                                    ]);
                                                    ?>"
                                            <button type="button" class="btn btn-outline-primary">Hapus</button></a>
                                    </div>
                                    <div
                                        class="btn-group mb-2"
                                        role="group"
                                        aria-label="Basic checkbox toggle button group">
                                </td>
                            <?php } ?>



                        </tr>
                    <?php   }  ?>
                </tbody>
            </table>
        </div>
        <!--end::Body-->
    </div>
    <!--end::Row-->
</div>
<!--end::Container-->

<?= $this->endSection() ?>