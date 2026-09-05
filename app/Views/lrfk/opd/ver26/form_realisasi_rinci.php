<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<?php

use App\Models\LrfkProvModel\RealisasiSubKegModel;

use App\Models\LrfkProvModel\SubKegModel; // as lrfkModel;
use App\Models\LrfkProvModel\TaRealisasiRinciModel;

$this->subkegmodel = new SubKegModel();
$this->realisasilrfk = new RealisasiSubKegModel();
$this->realisasilrfkrinci = new TaRealisasiRinciModel();


?>
<!--begin::Container-->
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
        <div class="col-12">
            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Data Realisasi dan Progres Pelaksanaan Sub Kegiatan Perangkat Daerah : <?= esc($datauser['sub_unit']);  ?></h3>
                    <br><br>
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            Jadwal input data yang aktif saat ini adalah Bulan : <?= esc($jadwalaktif['bulan']) ?>
                        </ul>
                    </div>
                </div>
                <div class="card-body"> <!--begin::Body-->
                    <?= form_open_multipart('lrfkopd/simpanperubahan'); ?>
                    <?= csrf_field(); ?>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="mb-3">
                                <?php if (session()->getFlashdata('message')): ?>
                                    <div class="alert alert-danger" role="alert">
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
                            <?php if (!session()->getFlashdata('message')): ?>
                                <div class="mb-3">
                                    <label for="basic-url" class="form-label"><?= esc($datasrobelanja['NAMA_URUSAN']); ?></label><br>
                                    <label for="basic-url" class="form-label"><?= esc($datasrobelanja['NAMA_UNIT_SKPD']); ?></label><br>
                                    <label for="basic-url" class="form-label"><?= esc($datasrobelanja['NAMA_PROGRAM']); ?></label><br>
                                    <label for="basic-url" class="form-label"><?= 'Kegiatan ' . esc($datasrobelanja['NAMA_GIAT']); ?></label><br>
                                    <label for="basic-url" class="form-label"><?= 'SubKegiatan ' . esc($datasrobelanja['NAMA_SUB_GIAT']); ?></label>
                                    <br>
                                    <label for="basic-url" class="form-label">Akumuasi Realisasi Anggaran Sampai Bulan <?= $jadwalaktif['bulan']  ?></label>
                                    <div class="input-group">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th style="width: 10px">#</th>
                                                    <th>Sub Rincian Objek Belanja</th>
                                                    <th>Pagu</th>
                                                    <th>Realisasi s/d <?= esc($jadwalaktif['bulan']) ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($srobelanja as $key => $rowbelanja) {

                                                ?>
                                                    <tr class="align-middle">
                                                        <td><?= esc($key + 1)  ?></td>
                                                        <td><?= esc($rowbelanja['NAMA_SRO']) ?></td>
                                                        <td><?= esc(number_format($rowbelanja['TOTAL_ANGGARAN'], 0, ',', '.')) ?></td>
                                                        <!-- <?php //if ($rowbelanja['TOTAL_REALISASI'] <> 0.00) { 
                                                                ?>
                                                            <td>
                                                                <a href="#" data-bs-toggle="tooltip"
                                                                    data-bs-title="Data Realisasi Anggaran Sub Rincian Objek Belanja dari 
                                                                SIPD tidak ada. Realisasi SPJ tidak dapat diinput.">
                                                                    <i class=" bi bi-emoji-sunglasses"></i></a>
                                                                <?php
                                                                // echo form_hidden('datareal[' . $key . '][NO]', esc($rowbelanja['NO']));
                                                                // echo form_hidden('datareal[' . $key . '][NAMA_SRO]', esc($rowbelanja['NAMA_SRO']));
                                                                // echo form_hidden('datareal[' . $key . '][TOTAL_ANGGARAN]', esc($rowbelanja['TOTAL_ANGGARAN']));
                                                                // echo form_hidden('datareal[' . $key . '][TOTAL_REALISASI]', esc($rowbelanja['TOTAL_REALISASI']));
                                                                // echo form_hidden('datareal[' . $key . '][REALISASI_SPJ]', esc($rowbelanja['REALISASI_SPJ']));
                                                                ?>
                                                            </td>
                                                        <?php // } else { 
                                                        ?> -->
                                                        <td>
                                                            <?= 'Rp  ' . esc(number_format($rowbelanja['TOTAL_REALISASI'], 0, ',', '.'));  ?>
                                                            <a href="#" data-bs-toggle="tooltip"
                                                                data-bs-title="Data ini Rencana Realisasi dari SIPD, jika ada perubahan realisasi silahkan input perubahan realisasi di kolom inputan dibawah ini. Jika tidak ada perubahan realisasi silahkan input data ini di kolom inputan.">
                                                                <i class=" bi bi-emoji-sunglasses"></i></a>

                                                            <?php
                                                            if ($rowbelanja['REALISASI_SPJ'] == 0.00) {
                                                                // $rowbelanja['REALISASI_SPJ'] = $rowbelanja['TOTAL_REALISASI'];
                                                                $real = $rowbelanja['TOTAL_REALISASI'];
                                                            } else {
                                                                $real = $rowbelanja['REALISASI_SPJ'];
                                                            }
                                                            $realisasi = [
                                                                'type' => 'text',
                                                                'name' => 'datareal[' . $key . '][REALISASI_SPJ]',
                                                                'value' =>  esc(number_format($real, 0, ',', '.')), //esc(number_format($rowbelanja['REALISASI_SPJ'], 0, ',', ',')),
                                                                //'class' => 'form-control form-control-sm',
                                                                'class' => 'form-control form-control-sm rupiah-input',
                                                                // 'placeholder' => 'isi perubahan realisasi disini',
                                                                'required' => 'true'
                                                            ];
                                                            echo form_input($realisasi);
                                                            echo form_hidden('datareal[' . $key . '][NO]',  esc($rowbelanja['NO']));
                                                            echo form_hidden('datareal[' . $key . '][NAMA_SRO]', esc($rowbelanja['NAMA_SRO']));
                                                            echo form_hidden('datareal[' . $key . '][TOTAL_ANGGARAN]', esc($rowbelanja['TOTAL_ANGGARAN']));
                                                            echo form_hidden('datareal[' . $key . '][TOTAL_REALISASI]', esc($rowbelanja['TOTAL_REALISASI']));
                                                            // echo form_hidden('datareal[' . $key . '][REALISASI_SPJ]', esc($rowbelanja['REALISASI_SPJ']));

                                                            ?>
                                                        </td>
                                                        <?php // } 
                                                        ?>
                                                    </tr>
                                                <?php
                                                }
                                                // $totalpagusubkeg = array_sum($subkegbelanja['pagu_rincian']);
                                                ?>

                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            <?php endif; ?>

                        </div>
                        <!--end::Body-->

                    </div>
                    <?php if (!session()->getFlashdata('message')): ?>
                        <!--begin::Footer-->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">Submit</button>
                            <button onclick="history.back()" class="btn btn-secondary">Kembali</button>
                        </div>
                        <!--end::Footer-->
                    <?php endif; ?>
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