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
                        <th style="width: 300px">SubKegiatan</th>
                        <th colspan="2" style="text-align: center;">Indikator Output Sub Kegiatan</th>
                        <th>URAIAN</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                    <?= form_open_multipart('lrfkopd/simpanindisubkeg'); ?>
                    <?= csrf_field(); ?> <tr class="align-middle">
                        <?php

                        //   echo dd($listIndikator);
                        if (!$listIndikatorSK) {
                            $VolIndi = old('vol_indi');
                            $SatIndi = old('satuan_indi');
                            $ValUrai = old('uraian');
                        } else {
                            $ValUrai = $listIndikatorSK['uraian'];
                            $VolIndi = $listIndikatorSK['vol_indi'];
                            $SatIndi = $listIndikatorSK['satuan_indi'];
                            echo form_hidden('id', $listIndikatorSK['id']);
                        }
                        ?>
                    <tr>
                        <td></td>
                        <td><?= esc($listsubkegiatan['nm_subkegiatan']) ?></td>
                        <td>
                            <?php
                            $vol_indi = [
                                'type' => 'text',
                                'name' => 'vol_indi',
                                'value' => $VolIndi,
                                'class' => 'form-control',
                                'placeholder' => 'isi volume indikator Output Sub Kegiatan',
                                'required' => 'true'
                            ];
                            echo form_input($vol_indi);
                            ?>
                        </td>
                        <td>
                            <?php
                            $satuan_indi = [
                                'type' => 'text',
                                'name' => 'satuan_indi',
                                'value' => $SatIndi,
                                'class' => 'form-control',
                                'placeholder' => 'isi satuan indikator Output Sub Kegiatan',
                                'required' => 'true'
                            ];
                            echo form_input($satuan_indi);
                            ?>
                        </td>
                        <td>
                            <?php
                            $uraian = [
                                'type' => 'text',
                                'name' => 'uraian',
                                'value' => $ValUrai,
                                'class' => 'form-control',
                                'placeholder' => 'isi penjelasan mengenai indikator Output (harus menjelaskan target)',
                                'required' => 'true'
                            ];
                            echo form_textarea($uraian);
                            echo form_hidden('kd_sub_unit', $listsubkegiatan['kd_sub_unit']);
                            echo form_hidden('kd_subkegiatan', $listsubkegiatan['kd_subkegiatan']);
                            echo form_hidden('nm_subkegiatan', $listsubkegiatan['nm_subkegiatan']);
                            echo form_hidden('sub_unit', $listsubkegiatan['nm_sub_unit']);
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