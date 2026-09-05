<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<?php

use App\Models\LrfkProvModel\TaIndikatorProgModal;
use SebastianBergmann\Type\NullType;

$this->taindikator = new TaIndikatorProgModal();


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
            <div class="card-title">Pilih Data Program yang akan diinput</div>
        </div>
        <!--end::Header-->
        <!--begin::Body-->
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>PROGRAM</th>
                        <th>Indikator Capaian Program</th>
                        <th>URAIAN</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listprogram as $key => $rowprogram) { ?>
                        <tr class="align-middle">
                            <td><?= $key + 1 ?></td>
                            <td><?= $rowprogram['nm_program'] ?></td>
                            <?php $rowindi = $this->taindikator->listIndiPerProgperOpd(
                                $rowprogram['kd_program'],
                                $rowprogram['kd_sub_unit']
                            );
                            if (!$rowindi) { ?>
                                <td></td>
                                <td></td>

                            <?php } else { ?>
                                <td><?= esc($rowindi['indikator']) ?></td>
                                <td><?= esc($rowindi['uraian']) ?></td>
                            <?php }
                            if (!$rowindi) { ?>
                                <td>
                                    <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                        <a href="<?= hash_url('lrfkopd/kinerja/', [
                                                        'page' => 'kinerja',
                                                        'action' => 'input',
                                                        'kdP' => $rowprogram['kd_program'],
                                                        'kdSU' => $rowprogram['kd_sub_unit']
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
                                                        'page' => 'kinerja',
                                                        'action' => 'ubah',
                                                        'kdI' => $rowindi['id'],
                                                        'kdP' => $rowprogram['kd_program'],
                                                        'kdSU' => $rowindi['kd_sub_unit']
                                                    ]);
                                                    ?>"
                                            <button type="button" class="btn btn-outline-primary">Ubah</button></a>
                                        <a href="<?= hash_url('lrfkopd/kinerja/', [
                                                        'page' => 'kinerja',
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