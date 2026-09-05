<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<!--begin::Input Group-->
<div class="card card-success card-outline mb-3">
    <!--begin::Header-->
    <div class="card-header">
        <div class="card-title">FORM DATA PROGRAM KERJA
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
        </div>
    </div>
    <!--end::Header-->
    <?php if ($halaman == "tambah") { ?>
        <!--begin::Header-->
        <div class="card-header">
            <div class="card-title">Misi ke :: <?= $datamisi['id'] . $datamisi['judul_misi']  ?></div>
        </div>
        <!--end::Header-->

        <!--begin::Body-->
        <?= form_open_multipart('adbang/program_kerja/simpan'); ?>
        <?= csrf_field(); ?>
        <div class="card-body">
            <div class="mb-3">

                <div class="mb-3">
                    <label for="basic-url" class="form-label">Program Kerja</label>
                    <div class="input-group">
                        <?php
                        $judul_programkerja = [
                            'type' => 'text',
                            'name' => 'judul_programkerja',
                            'value' => old('judul_programkerja'),
                            'placeholder' => 'isi nama program kerja',
                            'class' => 'form-control',
                            'required' => 'true'
                        ];
                        echo form_input($judul_programkerja);
                        ?>
                    </div>
                    <div class="form-text" id="basic-addon4">
                        Nama Program Kerja yang diisi sesuai dengan data Program Kerja Gubernur dan Wakil Gubernur Lampung 2025-2030.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="basic-url" class="form-label">Indikator Sasaran/Dampak</label>
                    <div class="input-group">
                        <?php
                        $indikator = [
                            'type' => 'text',
                            'name' => 'indikator',
                            'value' => old('indikator'),
                            'class' => 'form-control',
                            'placeholder' => 'isi uraian',
                            'required' => 'true'
                        ];
                        echo form_input($indikator);
                        ?>
                    </div>
                    <div class="form-text" id="basic-addon4">
                        Narasikan Dampak/Sasaran dari Program Kerja.
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="file" name="gambar" class="form-control" />
                </div>
                <div class="mb-3">
                    <label for="basic-url" class="form-label">Keterangan, berisi narasi tentang Program Kerja secara umum ataupun secara khusus</label>
                    <div class="input-group">
                        <?php
                        $ket = [
                            'type' => 'textarea',
                            'name' => 'ket',
                            'value' => old('ket'),
                            'class' => 'form-control',
                            'placeholder' => 'isi uraian',
                            'required' => 'true'
                        ];
                        echo form_textarea($ket);
                        echo form_hidden('id_misi', $datamisi['id']);
                        ?>
                    </div>
                </div>

            </div>
            <!--end::Body-->
            <!--begin::Footer-->
            <div class="card-footer">
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
            <!--end::Footer-->
        </div>
        <?= form_close(); ?>
        <!--end::Input Group-->
    <?php } ?>
    <!--end::Header-->
    <?php if ($halaman == "edit") { ?>
        <!--begin::Body-->
        <?= form_open_multipart('adbang/program_kerja/simpan'); ?>
        <?= csrf_field(); ?>
        <div class="card-body">
            <div class="mb-3">

                <div class="mb-3">
                    <label for="basic-url" class="form-label">Program Kerja</label>
                    <div class="input-group">
                        <?php
                        $judul_programkerja = [
                            'type' => 'text',
                            'name' => 'judul_programkerja',
                            'value' =>  esc($itemprogkerja['judul_programkerja']),
                            'placeholder' => 'isi nama program kerja',
                            'class' => 'form-control',
                            'required' => 'true'
                        ];
                        echo form_input($judul_programkerja);
                        ?>
                    </div>
                    <div class="form-text" id="basic-addon4">
                        Nama Program Kerja yang diisi sesuai dengan data Program Kerja Gubernur dan Wakil Gubernur Lampung 2025-2030.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="basic-url" class="form-label">Indikator Sasaran/Dampak</label>
                    <div class="input-group">
                        <?php
                        $indikator = [
                            'type' => 'text',
                            'name' => 'indikator',
                            'value' =>  esc($itemprogkerja['indikator']),
                            'class' => 'form-control',
                            'placeholder' => 'isi uraian',
                            'required' => 'true'
                        ];
                        echo form_input($indikator);
                        ?>
                    </div>
                    <div class="form-text" id="basic-addon4">
                        Narasikan Dampak/Sasaran dari Program Kerja.
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="file" name="gambar" class="form-control" />
                </div>
                <div class="mb-3">
                    <label for="basic-url" class="form-label">Keterangan, berisi narasi tentang Program Kerja secara umum ataupun secara khusus</label>
                    <div class="input-group">
                        <?php
                        $ket = [
                            'type' => 'textarea',
                            'name' => 'ket',
                            'value' =>  esc($itemprogkerja['ket']),
                            'class' => 'form-control',
                            'placeholder' => 'isi uraian',
                            'required' => 'true'
                        ];
                        echo form_textarea($ket);
                        echo form_hidden('id_misi', esc($datamisi['id']));
                        echo form_hidden('id', esc($itemprogkerja['id']));

                        ?>
                    </div>
                </div>

            </div>
            <!--end::Body-->
            <!--begin::Footer-->
            <div class="card-footer">
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
            <!--end::Footer-->
        </div>
        <?= form_close(); ?>
        <!--end::Input Group-->
    <?php } ?>
    <?= $this->endSection() ?>