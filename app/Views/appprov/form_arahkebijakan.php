<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<!--begin::Input Group-->
<div class="card card-success card-outline mb-4">
    <!--begin::Header-->
    <div class="card-header">
        <div class="card-title">FORM DATA PROGRAM KERJA</div>
        <div class="card-title">
            <label for="basic-url"></label>
            <?php if (session()->getFlashdata('message')): ?>
                <div class="alert alert-primary" role="alert">
                    <ul>
                        <p><?= session()->getFlashdata('message') ?></p>
                    </ul>
                    <button onclick="history.back()" class="btn btn-secondary">Kembali</button>

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
        <!--begin::Body-->
        <?= form_open_multipart('adbang/arah_kebijakan/simpan'); ?>
        <?= csrf_field(); ?>
        <div class="card-header">
            <h3 class="card-title">Misi Ke : <?= $datamisi['id']; ?> :: <?= $datamisi['judul_misi']; ?></h3>
        </div>
        <div class="card-header">
            <h3 class="card-title">Program Kerja : <?= $dataprogkerja['judul_programkerja']; ?> </h3>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <div class="mb-3">
                    <label for="basic-url" class="form-label">Arah Kebijakan</label>
                    <div class="input-group">
                        <?php
                        $judul_kebijakan = [
                            'type' => 'text',
                            'name' => 'judul_kebijakan',
                            'value' => old('judul_kebijakan'),
                            'placeholder' => 'isi arah kebijakan',
                            'class' => 'form-control',
                            'required' => 'true'
                        ];
                        echo form_input($judul_kebijakan);
                        ?>
                    </div>
                    <div class="form-text" id="basic-addon4">
                        Arah Kebijakan yang diisi sesuai dengan data Program Kerja Gubernur dan Wakil Gubernur Lampung 2025-2030.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="basic-url" class="form-label">Keterangan / Deskripsi Arah Kebijakan</label>
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
                        //   echo form_input($ket);
                        echo form_textarea($ket);
                        echo form_hidden('id_misi', $datamisi['id']);
                        echo form_hidden('id_progkerja', $dataprogkerja['id']);
                        ?>
                    </div>
                    <div class="form-text" id="basic-addon4">
                        Narasikan Arah Kebijakan dari Program Kerja.
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="file" name="gambar" class="form-control" />
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
        <?= form_open_multipart('adbang/arah_kebijakan/simpan'); ?>
        <?= csrf_field(); ?>
        <div class="card-header">
            <h3 class="card-title">Misi Ke : <?= $datamisi['id']; ?> :: <?= $datamisi['judul_misi']; ?></h3>
        </div>
        <div class="card-header">
            <h3 class="card-title">Program Kerja : <?= $dataprogkerja['judul_programkerja']; ?> </h3>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <div class="mb-3">
                    <label for="basic-url" class="form-label">Arah Kebijakan</label>
                    <div class="input-group">
                        <?php
                        $judul_kebijakan = [
                            'type' => 'text',
                            'name' => 'judul_kebijakan',
                            'value' => esc($dataarahkeb['judul_kebijakan']),
                            'placeholder' => 'isi arah kebijakan',
                            'class' => 'form-control',
                            'required' => 'true'
                        ];
                        echo form_input($judul_kebijakan);
                        ?>
                    </div>
                    <div class="form-text" id="basic-addon4">
                        Arah Kebijakan yang diisi sesuai dengan data Program Kerja Gubernur dan Wakil Gubernur Lampung 2025-2030.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="basic-url" class="form-label">Keterangan / Deskripsi Arah Kebijakan</label>
                    <div class="input-group">
                        <?php
                        $ket = [
                            'type' => 'textarea',
                            'name' => 'ket',
                            'value' => esc($dataarahkeb['ket']),
                            'class' => 'form-control',
                            'placeholder' => 'isi uraian',
                            'required' => 'true'
                        ];
                        //   echo form_input($ket);
                        echo form_textarea($ket);
                        echo form_hidden('id_misi', esc($datamisi['id']));
                        echo form_hidden('id_progkerja', esc($dataprogkerja['id']));
                        echo form_hidden('id', esc($dataarahkeb['id']));

                        ?>
                    </div>
                    <div class="form-text" id="basic-addon4">
                        Narasikan Arah Kebijakan dari Program Kerja.
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="file" name="gambar" class="form-control" />
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
        <!--end::Input Group--> <?php } ?>
    <?= $this->endSection() ?>