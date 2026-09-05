<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<?php

use App\Models\DataApbdModel\RealPendApbdModel;

$this->realpendatanmodel = new RealPendApbdModel();

?>
<!--begin::Container-->
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
        <div class="col-12">
            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Data Realisasi Pendapatan APBD : <?= esc($datauser['sub_unit']);  ?></h3>
                    <br><br>
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            Perangkat Daerah anda mengelola PENDAPATAN DAERAH, wajib mengisi data realisasi pendapatan APBD sesuai dengan jadwal yang telah ditentukan,
                            sebelum mengisi data realisasi Belanja.
                        </ul>
                        <ul>
                            Jadwal input data yang aktif saat ini adalah Bulan : <?= esc($jadwalaktif['bulan']) ?>
                        </ul>
                        <button onclick="history.back()" class="btn btn-secondary">Kembali</button>

                    </div>
                </div>
                <div class="card-body"> <!--begin::Body-->
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
                            <?php if (!session()->getFlashdata('message')): ?>
                                <div class="mb-3">
                                    <div class="input-group">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th style="width: 10px">#</th>
                                                    <th>KODE AKUN</th>
                                                    <th>KODE OPD</th>
                                                    <th>NAMA OPD</th>
                                                    <th>TOTAL TARGET PENDAPATAN </th>
                                                    <th>REALISASI PENDAPATAN</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($cekdatapendapatan as $key => $value) {

                                                ?>
                                                    <tr class="align-middle">
                                                        <td><?= esc($key + 1)  ?></td>
                                                        <td><?= esc($value['KODE_AKUN']) ?></td>
                                                        <td><?= esc($value['KODE_OPD']) ?></td>
                                                        <td><?= esc($value['NAMA_OPD']) ?></td>
                                                        <td><?= esc(number_format($value['total_pagu'], 0, ',', '.')) ?></td>
                                                        <?= form_open_multipart('lrfkopd/simpanpendapatanapbd'); ?>
                                                        <?= csrf_field(); ?>

                                                        <td>
                                                            <?php
                                                            $datarealpendapatan = $this->realpendatanmodel->getDataRPendapatan($tahunaktif, $bulanaktif, $value['NAMA_OPD']);
                                                            if ($datarealpendapatan) {
                                                                $realisasipendapatan = $datarealpendapatan['REALISASI_PEND'];
                                                                $idrealpendapatan = $datarealpendapatan['id'];
                                                            } else {
                                                                $realisasipendapatan = 0;
                                                                $idrealpendapatan = 0;
                                                            }
                                                            // echo dd($realisasipendapatan);
                                                            $realisasi = [
                                                                'type' => 'text',
                                                                'name' => 'datarealp[' . $key . '][REALISASI_PEND]',
                                                                'value' =>  esc(number_format($realisasipendapatan ?? null, 0, ',', '.')),
                                                                //'class' => 'form-control form-control-sm',
                                                                'class' => 'form-control form-control-sm rupiah-input',
                                                                // 'placeholder' => 'isi perubahan realisasi disini',
                                                                'required' => 'true'
                                                            ];
                                                            echo form_input($realisasi);
                                                            // echo form_hidden('datarealp[' . $key . '][id]',  esc($idrealpendapatan));
                                                            echo form_hidden('datarealp[' . $key . '][KODE_OPD]', esc($value['KODE_OPD']));
                                                            echo form_hidden('datarealp[' . $key . '][NAMA_OPD]', esc($value['NAMA_OPD']));
                                                            echo form_hidden('datarealp[' . $key . '][PAGU]', esc($value['total_pagu']));
                                                            echo form_hidden('datarealp[' . $key . '][TAHUN]', esc($value['TAHUN']));
                                                            echo form_hidden('datarealp[' . $key . '][BULAN]', esc($bulanaktif)); ?>


                                                            <div class="card-footer">
                                                                <button type="submit" class="btn btn-success">Submit</button>
                                                            </div>
                                                            <!--end::Footer-->
                                                        </td>
                                                        <?= form_close(); ?>
                                                        <td>
                                                            <br>
                                                            <button class="btn btn-primary mt-3" id="showModalBtn">
                                                                Lihat Realisasi Pendapatan per Bulan
                                                            </button>
                                                            <!-- Modal Structure -->
                                                            <div class="modal fade" id="welcomeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
                                                                <div class="modal-dialog modal-dialog-centered custom-modal">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-primary text-white">
                                                                            <h5 class="modal-title">Realisasi Pendapatan per Bulan Tahun: <?php echo esc($tahunaktif); ?> </h5>
                                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="text-center">
                                                                                <?= esc($datapendapatan['NAMA_OPD']) ?> <table class="table table-bordered">
                                                                                    <tr>
                                                                                        <th>Nama Bulan</th>
                                                                                        <th>Realisasi Pendapatan</th>
                                                                                    </tr>
                                                                                    <?php
                                                                                    foreach ($listbulan as $key => $bulan) {
                                                                                        $datarealpendapatanperbln = $this->realpendatanmodel->getDataRPendapatan($tahunaktif, $bulan['bulan'], $datapendapatan['NAMA_OPD']);
                                                                                        if ($datarealpendapatanperbln) {
                                                                                            $nama_bulan = $bulan['bulan'];
                                                                                            $realisasipendapatanperbln = $datarealpendapatanperbln['REALISASI_PEND'];
                                                                                        } else {
                                                                                            $nama_bulan = $bulan['bulan'];
                                                                                            $realisasipendapatanperbln = 0;
                                                                                        } ?>
                                                                                        <tr>
                                                                                            <td><?= esc($nama_bulan) ?></td>
                                                                                            <td><?= esc(number_format($realisasipendapatanperbln, 0, ',', '.')) ?></td>
                                                                                        </tr>
                                                                                    <?php } ?>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <script>
                                                                // Show modal manually with button
                                                                document.getElementById('showModalBtn').addEventListener('click', function() {
                                                                    var myModal = new bootstrap.Modal(document.getElementById('welcomeModal'));
                                                                    myModal.show();
                                                                });
                                                            </script>
                                                            <!--begin::Footer-->
                                                            <br>
                                                        </td>
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
                </div>
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