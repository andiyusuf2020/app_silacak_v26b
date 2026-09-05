<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<?php

use App\Models\DataApbdModel\AngkasModel;
use App\Models\DataApbdModel\RealPendApbdModel;

$this->angkasmodel = new AngkasModel();
$this->realpendatanmodel = new RealPendApbdModel();
?>
<!--begin::Container-->
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
        <div class="col-6">
            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Ubah Data Anggaran Kas Sub Kegiatan : <?= esc($datauser['sub_unit']);  ?></h3>

                </div>
                <div class="card-body"> <!--begin::Body-->
                    <?= form_open_multipart('lrfkopd/simpanangkasapbd'); ?>
                    <?= csrf_field(); ?>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="mb-3">
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
                            <div class="mb-3">
                                <div class="input-group">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px">#</th>
                                                <th>
                                                    KODE SUBKEGIATAN/NAMA SUBKEGIATAN</th>
                                            </tr>
                                            <?php
                                            // foreach ($listbulan as $bulan) {
                                            // 
                                            ?>
                                            <!-- // <tr>
                                                // <th><?php //esc($bulan['bulan']) 
                                                        ?></th>
                                                // </tr> -->
                                            <?php
                                            // }
                                            ?>

                                        </thead>
                                        <tbody>
                                            <tr class="align-middle">
                                                <td>#</td>

                                                <td>
                                                    <?= esc($subkegperopd['NAMA_PROGRAM']) ?><br>
                                                    <?= esc($subkegperopd['NAMA_GIAT']) ?><br>
                                                    <?= esc($subkegperopd['KODE_SUB_GIAT']) ?>
                                                    <?= esc($subkegperopd['NAMA_SUB_GIAT']) ?><br>
                                                    <strong>Pagu Rp. <?= esc(number_format($subkegperopd['anggaran'], 0, ',', '.')) ?></strong>
                                                </td>
                                                <td> </td>
                                            </tr>
                                            <?php
                                            foreach ($dataangkasapbdpergiat as $key => $value) {
                                                // $dataangkas = $this->angkasmodel->getDataAngkasSubGiatDetail($tahunaktif, $bulan['bulan'], $subkegperopd['NAMA_UNIT_SKPD'], $subkegperopd['NAMA_SUB_GIAT']);
                                            ?>
                                                <tr>
                                                    <td>#</td>
                                                    <td><?= esc($value['BULAN_ANGKAS']) ?></td>
                                                    <td>
                                                        <?php
                                                        $realisasi = [
                                                            'type' => 'text',
                                                            'name' => 'dataangkas[' . $key . '][ANGKAS]',
                                                            'value' =>  esc(number_format($value['ANGKAS'] ?? 0, 0, ',', '.')),
                                                            //'class' => 'form-control form-control-sm',
                                                            'class' => 'form-control form-control-sm rupiah-input',
                                                            // 'placeholder' => 'isi perubahan realisasi disini',
                                                            'required' => 'true'
                                                        ];
                                                        echo form_input($realisasi);
                                                        echo form_hidden('dataangkas[' . $key . '][Id_angkas]', esc($value['Id_angkas']));
                                                        echo form_hidden('dataangkas[' . $key . '][TAHUN]', esc($value['TAHUN']));
                                                        echo form_hidden('dataangkas[' . $key . '][KODE_UNIT_SKPD]', esc($value['KODE_UNIT_SKPD']));
                                                        echo form_hidden('dataangkas[' . $key . '][NAMA_UNIT_SKPD]', esc($value['NAMA_UNIT_SKPD']));
                                                        echo form_hidden('dataangkas[' . $key . '][KODE_SUB_GIAT]', esc($value['KODE_SUB_GIAT']));
                                                        echo form_hidden('dataangkas[' . $key . '][NAMA_SUB_GIAT]', esc($value['NAMA_SUB_GIAT']));
                                                        echo form_hidden('dataangkas[' . $key . '][TOTAL_ANGGARAN]', esc($value['TOTAL_ANGGARAN']));
                                                        echo form_hidden('dataangkas[' . $key . '][BULAN_ANGKAS]', esc($value['BULAN_ANGKAS']));

                                                        // echo form_hidden('dataangkas[' . $key . '][BULAN_ANGKAS]', esc($bulanaktif));

                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php }  ?>



                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                        <!--end::Body-->

                    </div>
                    <!--begin::Footer-->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <button onclick="history.back()" class="btn btn-secondary">Kembali</button>

                    </div>
                    <!--end::Footer-->
                </div>
                <?= form_close(); ?>
                <!--end::Input Group-->
            </div>
            <!-- /.card -->
        </div>

    </div>
    <!--end::Row-->
</div>
<script src="<?php echo base_url(); ?>dist/js/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Format Rupiah saat input (mendukung desimal)
        $('.rupiah-input').on('keyup', function(e) {
            // Abaikan jika tombol navigasi (arrow keys, tab, dll)
            if ([37, 38, 39, 40, 9].includes(e.keyCode)) return;

            var nilai = $(this).val().replace(/[^0-9]/g, '');

            // Jika ada koma (desimal)
            if ($(this).val().includes(',')) {
                var parts = $(this).val().split(',');
                if (parts[1]) {
                    // Batasi 2 digit di belakang koma
                    parts[1] = parts[1].replace(/[^0-9]/g, '').substring(0, 2);
                    nilai = parts[0].replace(/[^0-9]/g, '') + '.' + parts[1];
                }
            }

            $(this).val(formatRupiah(nilai));
        });

        // Fungsi format Rupiah dengan desimal
        function formatRupiah(angka) {
            if (!angka) return '';

            // Pisahkan bagian desimal jika ada
            var angkaParts = angka.toString().split('.');
            var bilangan = angkaParts[0];
            var desimal = angkaParts.length > 1 ? ',' + angkaParts[1] : '';

            // Format bagian bilangan
            var number_string = bilangan.toString(),
                sisa = number_string.length % 3,
                rupiah = number_string.substr(0, sisa),
                ribuan = number_string.substr(sisa).match(/\d{3}/g);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            return 'Rp ' + rupiah + desimal;
        }

        // Hapus format Rupiah sebelum submit (konversi ke float)
        $('form').on('submit', function() {
            $('.rupiah-input').each(function() {
                var nilai = $(this).val()
                    .replace('Rp ', '')
                    .replace(/\./g, '')
                    .replace(',', '.');
                $(this).val(nilai);
            });
        });
    });
</script>


<!--end::Container-->
<?= $this->endSection() ?>