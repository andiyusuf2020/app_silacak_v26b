<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<!--begin::Container-->
<div class="container-fluid">

    <div class="card card-success card-outline mb-4">
        <!--begin::Header-->
        <div class="card-header">
            <div class="card-title"><?= esc($subtitlepage . $datauser['sub_unit']) ?></div>
        </div>
        <div class="alert alert-danger" role="alert">
            <ul>
                Jadwal input data yang aktif saat ini adalah Bulan : <?= $jadwalaktif['bulan'] ?>
            </ul>
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
                        <th>Rekening Pendapatan</th>
                        <th>Target/Pagu Pendapatan</th>
                        <th>Realisasi Pendapatan s/d Bulan <?= esc($jadwalaktif['bulan']) ?></th>

                        <th style="width: 40px">Label</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="align-middle">
                        <td></td>
                        <td><?= esc($KodeRek . $nmRek) ?></td>
                        <td>
                            <?= esc('Rp ' . number_format($pagupad, 2, ',', '.')) ?>
                        </td>
                        <?= form_open_multipart('lrfkopd/simpanpendapatan'); ?>
                        <?= csrf_field(); ?>
                        <td>
                            <?php
                            if (!$dRealisasi) {
                                $realisasi = [
                                    'type' => 'text',
                                    'name' => 'realisasi',
                                    'value' =>  old('realisasi'),
                                    'class' => 'form-control form-control-sm rupiah-input',
                                    'placeholder' => 'isi pagu realisasi ',
                                    'required' => 'true'
                                ];
                                //echo $idR;
                            } else {
                                $realisasi = [
                                    'type' => 'text',
                                    'name' => 'realisasi',
                                    'value' =>  esc(number_format($dRealisasi['realisasi'], 0, ',', '.')),
                                    'class' => 'form-control form-control-sm rupiah-input',
                                    'placeholder' => 'isi pagu realisasi ',
                                    'required' => 'true'
                                ];
                                echo form_hidden('id', $idR);
                                // echo $dRealisasi['realisasi'];
                            }
                            echo form_input($realisasi);
                            echo form_hidden('tahun', esc($tahunaktif));
                            echo form_hidden('bulan', esc($jadwalaktif['bulan']));
                            echo form_hidden('kd_skpd', esc($kd_skpd));
                            echo form_hidden('sub_unit', esc($datauser['sub_unit']));
                            echo form_hidden('kd_akun', esc($KodeRek));
                            echo form_hidden('nm_rekening', esc($nmRek));
                            echo form_hidden('pagu', $pagupad);
                            ?>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </td>
                        <?= form_close(); ?>
                    </tr>
                </tbody>
            </table>
        </div>
        <!--end::Body-->
    </div>
    <!--end::Row-->
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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