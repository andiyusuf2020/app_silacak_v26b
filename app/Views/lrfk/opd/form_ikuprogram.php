<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
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
            <div class="card-title">Form input Indikator/Sasaran Program yang akan diinput</div>
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
                        <th style="width: 100px">PROGRAM</th>
                        <th>Indikator Capaian Program</th>
                        <th>URAIAN</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                    <?= form_open_multipart('lrfkopd/simpaniku'); ?>
                    <?= csrf_field(); ?> <tr class="align-middle">
                        <?php

                        //   echo dd($listIndikator);
                        if (!$listIndikator) {
                            $ValIndi = old('indikator');
                            $ValUrai = old('uraian');
                        } else {
                            $ValUrai = $listIndikator['uraian'];
                            $ValIndi = $listIndikator['indikator'];
                            echo form_hidden('id', $listIndikator['id']);
                        }
                        ?>
                    <tr>
                        <td></td>
                        <td><?= esc($listprogram['nm_program']) ?></td>
                        <td>
                            <?php
                            $indikator = [
                                'type' => 'text',
                                'name' => 'indikator',
                                'value' => $ValIndi,
                                'class' => 'form-control',
                                'placeholder' => 'isi indikator capaian',
                                'required' => 'true'
                            ];
                            echo form_input($indikator);
                            ?>
                        </td>
                        <td>
                            <?php
                            $uraian = [
                                'type' => 'text',
                                'name' => 'uraian',
                                'value' => $ValUrai,
                                'class' => 'form-control',
                                'placeholder' => 'isi penjelasan mengenai indikator capaian(harus menjelaskan target)',
                                'required' => 'true'
                            ];
                            echo form_textarea($uraian);
                            echo form_hidden('kd_sub_unit', $listprogram['kd_sub_unit']);
                            echo form_hidden('kd_program', $listprogram['kd_program']);
                            echo form_hidden('nm_program', $listprogram['nm_program']);
                            echo form_hidden('sub_unit', $listprogram['nm_sub_unit']);
                            ?>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </td>
                    </tr>
                    <?= form_close(); ?>
                </tbody>
            </table>
        </div>
        <!--end::Body-->
    </div>
    <!--end::Row-->
</div>
<!--end::Container-->

<?= $this->endSection() ?>