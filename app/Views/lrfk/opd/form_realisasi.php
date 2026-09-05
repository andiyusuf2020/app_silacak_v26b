<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<?php

use App\Models\LrfkProvModel\RealisasiSubKegModel;

use App\Models\LrfkProvModel\SubKegModel; // as lrfkModel;
$this->subkegmodel = new SubKegModel();
$this->realisasilrfk = new RealisasiSubKegModel();


?>
<!--begin::Container-->
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
        <div class="col-6">
            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Data Realisasi dan Progres Pelaksanaan Sub Kegiatan Perangkat Daerah : <?= $datauser['sub_unit'];  ?></h3>
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
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            Jadwal input data yang aktif saat ini adalah Bulan : <?= $jadwalaktif['bulan'] ?>
                        </ul>
                    </div>
                </div>
                <div class="card-body"> <!--begin::Body-->
                    <?= form_open_multipart('lrfkopd/simpanprogres'); ?>
                    <?= csrf_field(); ?>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="mb-3">
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
                                <label for="basic-url" class="form-label"><?= esc($subkeg['nm_urusan']); ?></label>
                                <label for="basic-url" class="form-label"><?= esc($subkeg['nm_sub_unit']); ?></label>
                                <label for="basic-url" class="form-label"><?= esc($subkeg['nm_program']); ?></label>
                                <label for="basic-url" class="form-label"><?= 'Kegiatan ' . esc($subkeg['nm_kegiatan']); ?></label>
                                <label for="basic-url" class="form-label"><?= 'SubKegiatan ' . esc($subkeg['nm_subkegiatan']); ?></label>
                            </div>
                            <div class="mb-3">
                                <label for="basic-url" class="form-label">Akumuasi Realisasi Anggaran Sampai Bulan <?= $jadwalaktif['bulan']  ?></label>
                                <div class="input-group">
                                    <?php
                                    $pagu_realisasi_a = [
                                        'type' => 'text',
                                        'name' => 'pagu_realisasi_a',
                                        'id' => "nominal_tampil",
                                        'value' => " ",
                                        'class' => 'form-control',
                                        'placeholder' => 'isi pagu realisasi ',
                                        'required' => 'true'
                                    ];
                                    echo form_input($pagu_realisasi_a);
                                    ?>
                                    <input type="hidden" id="nominal_asli" name="pagu_realisasi" class="hidden-input">
                                </div>

                            </div>
                            <div class="mb-3">
                                <label for="basic-url" class="form-label">Uraian Progres Realisasi</label>
                                <div class="input-group">
                                    <?php
                                    $uraian_realisasi = [
                                        'type' => 'text',
                                        'name' => 'uraian_realisasi',
                                        'value' => old('uraian_realisasi'),
                                        'class' => 'form-control',
                                        'placeholder' => 'isi penjelasan mengenai progres pelaksanaan',
                                        'required' => 'true'
                                    ];
                                    echo form_textarea($uraian_realisasi);
                                    ?>
                                </div>
                                <div class="form-text" id="basic-addon4">
                                    Penjelasan Progres Pelaksanaan berupa realisasi output atau sasaran (yang dapat diukur),
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="basic-url" class="form-label">Dokumentasi Pelaksanaan SubKegiatan</label>
                                <div class="input-group">
                                    <input type="file" name="dokumentasi" class="form-control" />
                                </div>
                                <div class="form-text" id="basic-addon4">
                                    Kirim dokumentasi pelaksanaan kegiatan.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="basic-url" class="form-label">Dokumentasi Pelaksanaan SubKegiatan</label>
                                <div class="input-group">
                                    <?php
                                    $link_dokumentasi = [
                                        'type' => 'text',
                                        'name' => 'link_dokumentasi',
                                        'value' => old('link_dokumentasi'),
                                        'class' => 'form-control',
                                        'placeholder' => 'isi link Url Google Drive dokumentasi',
                                        // 'required' => 'true'
                                    ];
                                    echo form_input($link_dokumentasi);
                                    echo form_hidden('id_subkegiatan', $subkeg['id']);
                                    echo form_hidden('kd_subkegiatan', $subkeg['kd_subkegiatan']);
                                    echo form_hidden('kd_sub_unit', $subkeg['kd_sub_unit']);

                                    ?>
                                </div>
                                <div class="form-text" id="basic-addon4">
                                    Url dokumentasi pelaksanaan kegiatan lainnya.
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
        <!-- /.col-md-6 -->
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="alert alert-danger" role="alert">
                    <ul>
                        Jadwal input data yang aktif saat ini adalah Bulan : <?= $jadwalaktif['bulan'] ?><br>
                        PERHATIAN !!
                        pengisian data realisasi anggaran perBulan adalah Akumulasi bulan sebelumnya <br>
                        contoh : <br>jika realisasi anggaran sub kegiatan diawali bulan Maret maka data pada bulan April
                        minimal sama dengan realisasi bulan Maret tidak boleh kosong dan bulan-bulan seterusnya.
                    </ul>
                </div>
                <div class="card-header border-0">
                    <h3 class="card-title">Realisasi Anggara per-Bulan <?= 'SubKegiatan ' . esc($subkeg['nm_subkegiatan']); ?> </h3>
                    <br> Total Pagu Subkegiatan :: <?= 'Rp  ' . esc(number_format($subkeg['pagu_rincian'], 2, ',', '.')) ?>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th>Realisasi</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    Januari
                                </td>
                                <td><?php
                                    $this->realisasilrfk = new RealisasiSubKegModel();
                                    // ->Data Kegiatan APBD
                                    $datarealisasi1 = $this->realisasilrfk->select('ta_realisasi_lrfk.*,a.*')
                                        ->join('ta_apbd_persubkegiatan a', 'a.id = ta_realisasi_lrfk.id_subkegiatan')
                                        ->where('a.pagu_rincian<>', '0')
                                        ->where('ta_realisasi_lrfk.tahun', $jadwalaktif['tahun'])
                                        ->where('ta_realisasi_lrfk.bulan', 'Januari')
                                        ->where('ta_realisasi_lrfk.sub_unit', $subkeg['nm_sub_unit'])
                                        ->where('ta_realisasi_lrfk.nm_subkegiatan', $subkeg['nm_subkegiatan'])
                                        ->where('ta_realisasi_lrfk.delete_at=', 0)
                                        ->get()
                                        ->getRowArray();
                                    $pagu1 = $datarealisasi1['pagu_rincian'] ?? null;
                                    $real1 = $datarealisasi1['pagu_realisasi'] ?? null;

                                    if ($pagu1 == 0) {
                                        $persentase1 = '0';
                                    } else {
                                        $persentase1 = ($real1 / $pagu1) * 100 ?? NUll;
                                    }
                                    if ($datarealisasi1 == Null) {
                                    ?>
                                        <i class="text-danger">"Realisasi bulan ini Kosong"</i>
                                        <?php
                                    } else {
                                        if ($real1 > $pagu1) {
                                        ?>
                                            <i class="text-danger">"Realisasi bulan ini melebihi pagu"</i>
                                    <?php
                                        } else {
                                            echo 'Rp  ' . esc(number_format($real1, 3, ',', '.'));
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?= esc(number_format($persentase1, 3, ',', '.')); ?>
                                </td>
                                <td>
                                    <a href="#" class="text-secondary"> <i class="bi bi-search"></i> </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Februari
                                </td>
                                <td><?php
                                    // ->Data Kegiatan APBD
                                    $datarealisasi2 = $this->realisasilrfk->select('ta_realisasi_lrfk.*,a.*')
                                        ->join('ta_apbd_persubkegiatan a', 'a.id = ta_realisasi_lrfk.id_subkegiatan')
                                        ->where('a.pagu_rincian<>', '0')
                                        ->where('ta_realisasi_lrfk.tahun', $jadwalaktif['tahun'])
                                        ->where('ta_realisasi_lrfk.bulan', 'Februari')
                                        ->where('ta_realisasi_lrfk.sub_unit', $subkeg['nm_sub_unit'])
                                        ->where('ta_realisasi_lrfk.nm_subkegiatan', $subkeg['nm_subkegiatan'])
                                        ->where('ta_realisasi_lrfk.delete_at=', 0)

                                        ->get()
                                        ->getRowArray();
                                    $pagu2 = $datarealisasi2['pagu_rincian'] ?? null;
                                    $real2 = $datarealisasi2['pagu_realisasi'] ?? null;

                                    if ($pagu2 == 0) {
                                        $persentase2 = '0';
                                    } else {
                                        $persentase2 = ($real2 / $pagu2) * 100 ?? NUll;
                                    }
                                    if ($datarealisasi2 == Null) {
                                    ?>
                                        <i class="text-danger">"Realisasi bulan ini Kosong"</i>
                                        <?php
                                    } else {
                                        if ($real2 > $pagu2) {
                                        ?>
                                            <i class="text-danger">"Realisasi bulan ini melebihi pagu"</i>
                                    <?php
                                        } else {
                                            echo 'Rp  ' . esc(number_format($real2, 2, ',', '.'));
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?= esc(number_format($persentase2, 3, ',', '.')); ?>
                                </td>
                                <td>
                                    <a href="#" class="text-secondary"> <i class="bi bi-search"></i> </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Maret
                                </td>
                                <td><?php
                                    // ->Data Kegiatan APBD
                                    $datarealisasi3 = $this->realisasilrfk->select('ta_realisasi_lrfk.*,a.*')
                                        ->join('ta_apbd_persubkegiatan a', 'a.id = ta_realisasi_lrfk.id_subkegiatan')
                                        ->where('a.pagu_rincian<>', '0')
                                        ->where('ta_realisasi_lrfk.tahun', $jadwalaktif['tahun'])
                                        ->where('ta_realisasi_lrfk.bulan', 'Maret')
                                        ->where('ta_realisasi_lrfk.sub_unit', $subkeg['nm_sub_unit'])
                                        ->where('ta_realisasi_lrfk.nm_subkegiatan', $subkeg['nm_subkegiatan'])
                                        ->where('ta_realisasi_lrfk.delete_at=', 0)

                                        ->get()
                                        ->getRowArray();
                                    $pagu3 = $datarealisasi3['pagu_rincian'] ?? null;
                                    $real3 = $datarealisasi3['pagu_realisasi'] ?? null;
                                    if ($pagu3 == 0) {
                                        $persentase3 = '0';
                                    } else {
                                        $persentase3 = ($real3 / $pagu3) * 100 ?? NUll;
                                    }
                                    if ($datarealisasi3 == Null) {
                                    ?>
                                        <i class="text-danger">"Realisasi bulan ini Kosong"</i>
                                        <?php
                                    } else {
                                        if ($real3 > $pagu3) {
                                        ?>
                                            <i class="text-danger">"Realisasi bulan ini melebihi pagu"</i>
                                    <?php
                                        } else {
                                            echo 'Rp  ' . esc(number_format($real3, 2, ',', '.'));
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?= esc(number_format($persentase3, 3, ',', '.')) ?>
                                </td>
                                <td>
                                    <a href="#" class="text-secondary"> <i class="bi bi-search"></i> </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    April
                                </td>
                                <td><?php
                                    // ->Data Kegiatan APBD
                                    $datarealisasi4 = $this->realisasilrfk->select('ta_realisasi_lrfk.*,a.*')
                                        ->join('ta_apbd_persubkegiatan a', 'a.id = ta_realisasi_lrfk.id_subkegiatan')
                                        ->where('a.pagu_rincian<>', '0')

                                        ->where('ta_realisasi_lrfk.tahun', $jadwalaktif['tahun'])
                                        ->where('ta_realisasi_lrfk.bulan', 'April')
                                        ->where('ta_realisasi_lrfk.sub_unit', $subkeg['nm_sub_unit'])
                                        ->where('ta_realisasi_lrfk.nm_subkegiatan', $subkeg['nm_subkegiatan'])
                                        ->where('ta_realisasi_lrfk.delete_at=', 0)

                                        ->get()
                                        ->getRowArray();
                                    $pagu4 = $datarealisasi4['pagu_rincian'] ?? null;
                                    $real4 = $datarealisasi4['pagu_realisasi'] ?? null;

                                    if ($pagu4 == 0) {
                                        $persentase4 = '0';
                                    } else {
                                        $persentase4 = ($real4 / $pagu4) * 100 ?? NUll;
                                    }
                                    if ($datarealisasi4 == Null) { ?>
                                        <i class="text-danger">"Realisasi bulan ini Kosong"</i>
                                        <?php
                                    } else {
                                        if ($real4 > $pagu4) { ?>
                                            <i class="text-danger">"Realisasi bulan ini melebihi pagu"</i>
                                    <?php
                                        } else {
                                            echo 'Rp  ' . esc(number_format($real4, 2, ',', '.'));
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?= esc(number_format($persentase4, 3, ',', '.')); ?>
                                </td>
                                <td>
                                    <a href="#" class="text-secondary"> <i class="bi bi-search"></i> </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Mei
                                </td>
                                <td><?php
                                    // ->Data Kegiatan APBD
                                    $datarealisasi5 = $this->realisasilrfk->select('ta_realisasi_lrfk.*,a.*')
                                        ->join('ta_apbd_persubkegiatan a', 'a.id = ta_realisasi_lrfk.id_subkegiatan')

                                        ->where('a.pagu_rincian<>', '0')
                                        ->where('ta_realisasi_lrfk.tahun', $jadwalaktif['tahun'])
                                        ->where('ta_realisasi_lrfk.bulan', 'Mei')
                                        ->where('ta_realisasi_lrfk.sub_unit', $subkeg['nm_sub_unit'])
                                        ->where('ta_realisasi_lrfk.nm_subkegiatan', $subkeg['nm_subkegiatan'])
                                        ->where('ta_realisasi_lrfk.delete_at=', 0)

                                        ->get()
                                        ->getRowArray();
                                    $pagu5 = $datarealisasi5['pagu_rincian'] ?? null;
                                    $real5 = $datarealisasi5['pagu_realisasi'] ?? null;

                                    if ($pagu5 == 0) {
                                        $persentase5 = '0';
                                    } else {
                                        $persentase5 = ($real5 / $pagu5) * 100 ?? NUll;
                                    }
                                    if ($datarealisasi5 == Null) {
                                    ?>
                                        <i class="text-danger">"Realisasi bulan ini Kosong"</i>
                                        <?php
                                    } else {
                                        if ($real5 > $pagu5) {
                                        ?>
                                            <i class="text-danger">"Realisasi bulan ini melebihi pagu"</i>
                                    <?php
                                        } else {
                                            echo 'Rp  ' . esc(number_format($real5, 2, ',', '.'));
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?= esc(number_format($persentase5, 3, ',', '.')); ?>
                                </td>
                                <td>
                                    <a href="#" class="text-secondary"> <i class="bi bi-search"></i> </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Juni
                                </td>
                                <td><?php
                                    // ->Data Kegiatan APBD
                                    $datarealisasi6 = $this->realisasilrfk->select('ta_realisasi_lrfk.*,a.*')
                                        ->join('ta_apbd_persubkegiatan a', 'a.id = ta_realisasi_lrfk.id_subkegiatan')
                                        ->where('a.pagu_rincian<>', '0')

                                        ->where('ta_realisasi_lrfk.tahun', $jadwalaktif['tahun'])
                                        ->where('ta_realisasi_lrfk.bulan', 'Juni')
                                        ->where('ta_realisasi_lrfk.sub_unit', $subkeg['nm_sub_unit'])
                                        ->where('ta_realisasi_lrfk.nm_subkegiatan', $subkeg['nm_subkegiatan'])
                                        ->where('ta_realisasi_lrfk.delete_at=', 0)

                                        ->get()
                                        ->getRowArray();
                                    $pagu6 = $datarealisasi6['pagu_rincian'] ?? null;
                                    $real6 = $datarealisasi6['pagu_realisasi'] ?? null;

                                    if ($pagu6 == 0) {
                                        $persentase6 = '0';
                                    } else {
                                        $persentase6 = ($real6 / $pagu6) * 100 ?? NUll;
                                    }
                                    if ($datarealisasi6 == Null) {
                                    ?>
                                        <i class="text-danger">"Realisasi bulan ini Kosong"</i>
                                        <?php
                                    } else {
                                        if ($real6 > $pagu6) {
                                        ?>
                                            <i class="text-danger">"Realisasi bulan ini melebihi pagu"</i>
                                    <?php
                                        } else {
                                            echo 'Rp  ' . esc(number_format($real6, 2, ',', '.'));
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?= esc(number_format($persentase6, 3, ',', '.')); ?>
                                </td>
                                <td>
                                    <a href="#" class="text-secondary"> <i class="bi bi-search"></i> </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Juli
                                </td>
                                <td><?php
                                    // ->Data Kegiatan APBD
                                    $datarealisasi7 = $this->realisasilrfk->select('ta_realisasi_lrfk.*,a.*')
                                        ->join('ta_apbd_persubkegiatan a', 'a.id = ta_realisasi_lrfk.id_subkegiatan')
                                        ->where('ta_realisasi_lrfk.tahun', $jadwalaktif['tahun'])
                                        ->where('ta_realisasi_lrfk.bulan', 'Juli')
                                        ->where('ta_realisasi_lrfk.sub_unit', $subkeg['nm_sub_unit'])
                                        ->where('ta_realisasi_lrfk.nm_subkegiatan', $subkeg['nm_subkegiatan'])
                                        ->where('ta_realisasi_lrfk.delete_at=', 0)

                                        ->get()
                                        ->getRowArray();
                                    $pagu7 = $datarealisasi7['pagu_rincian'] ?? null;
                                    $real7 = $datarealisasi7['pagu_realisasi'] ?? null;

                                    if ($pagu7 == 0) {
                                        $persentase7 = '0';
                                    } else {
                                        $persentase7 = ($real7 / $pagu7) * 100 ?? NUll;
                                    }
                                    if ($datarealisasi7 == Null) {
                                    ?>
                                        <i class="text-danger">"Realisasi bulan ini Kosong"</i>
                                        <?php
                                    } else {
                                        if ($real7 > $pagu7) {
                                        ?>
                                            <i class="text-danger">"Realisasi bulan ini melebihi pagu"</i>
                                    <?php
                                        } else {
                                            echo 'Rp  ' . esc(number_format($real7, 2, ',', '.'));
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?= esc(number_format($persentase7, 3, ',', '.')); ?>
                                </td>
                                <td>
                                    <a href="#" class="text-secondary"> <i class="bi bi-search"></i> </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Agustus
                                </td>
                                <td><?php
                                    // ->Data Kegiatan APBD
                                    $datarealisasi8 = $this->realisasilrfk->select('ta_realisasi_lrfk.*,a.*')
                                        ->join('ta_apbd_persubkegiatan a', 'a.id = ta_realisasi_lrfk.id_subkegiatan')
                                        ->where('a.pagu_rincian<>', '0')

                                        ->where('ta_realisasi_lrfk.tahun', $jadwalaktif['tahun'])
                                        ->where('ta_realisasi_lrfk.bulan', 'Agustus')
                                        ->where('ta_realisasi_lrfk.sub_unit', $subkeg['nm_sub_unit'])
                                        ->where('ta_realisasi_lrfk.nm_subkegiatan', $subkeg['nm_subkegiatan'])
                                        ->where('ta_realisasi_lrfk.delete_at=', 0)

                                        ->get()
                                        ->getRowArray();
                                    $pagu8 = $datarealisasi8['pagu_rincian'] ?? null;
                                    $real8 = $datarealisasi8['pagu_realisasi'] ?? null;

                                    if ($pagu8 == 0) {
                                        $persentase8 = '0';
                                    } else {
                                        $persentase8 = ($real8 / $pagu8) * 100 ?? NUll;
                                    }
                                    if ($datarealisasi8 == Null) {
                                    ?>
                                        <i class="text-danger">"Realisasi bulan ini Kosong"</i>
                                        <?php
                                    } else {
                                        if ($real8 > $pagu8) {
                                        ?>
                                            <i class="text-danger">"Realisasi bulan ini melebihi pagu"</i>
                                    <?php
                                        } else {
                                            echo 'Rp  ' . esc(number_format($real8, 2, ',', '.'));
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?= esc(number_format($persentase8, 3, ',', '.')); ?>

                                </td>
                                <td>
                                    <a href="#" class="text-secondary"> <i class="bi bi-search"></i> </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    September
                                </td>
                                <td><?php
                                    // ->Data Kegiatan APBD
                                    $datarealisasi9 = $this->realisasilrfk->select('ta_realisasi_lrfk.*,a.*')
                                        ->join('ta_apbd_persubkegiatan a', 'a.id = ta_realisasi_lrfk.id_subkegiatan')
                                        ->where('a.pagu_rincian<>', '0')

                                        ->where('ta_realisasi_lrfk.tahun', $jadwalaktif['tahun'])
                                        ->where('ta_realisasi_lrfk.bulan', 'September')
                                        ->where('ta_realisasi_lrfk.sub_unit', $subkeg['nm_sub_unit'])
                                        ->where('ta_realisasi_lrfk.nm_subkegiatan', $subkeg['nm_subkegiatan'])
                                        ->where('ta_realisasi_lrfk.delete_at=', 0)

                                        ->get()
                                        ->getRowArray();
                                    $pagu9 = $datarealisasi9['pagu_rincian'] ?? null;
                                    $real9 = $datarealisasi9['pagu_realisasi'] ?? null;

                                    if ($pagu9 == 0) {
                                        $persentase9 = '0';
                                    } else {
                                        $persentase9 = ($real9 / $pagu9) * 100 ?? NUll;
                                    }
                                    if ($datarealisasi9 == Null) {
                                    ?>
                                        <i class="text-danger">"Realisasi bulan ini Kosong"</i>
                                        <?php
                                    } else {
                                        if ($real9 > $pagu9) {
                                        ?>
                                            <i class="text-danger">"Realisasi bulan ini melebihi pagu"</i>
                                    <?php
                                        } else {
                                            echo 'Rp  ' . esc(number_format($real9, 2, ',', '.'));
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?= esc(number_format($persentase9, 3, ',', '.')); ?>
                                </td>
                                <td>
                                    <a href="#" class="text-secondary"> <i class="bi bi-search"></i> </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Oktober
                                </td>
                                <td><?php
                                    // ->Data Kegiatan APBD
                                    $datarealisasi10 = $this->realisasilrfk->select('ta_realisasi_lrfk.*,a.*')
                                        ->join('ta_apbd_persubkegiatan a', 'a.id = ta_realisasi_lrfk.id_subkegiatan')

                                        ->where('a.pagu_rincian<>', '0')
                                        ->where('ta_realisasi_lrfk.tahun', $jadwalaktif['tahun'])
                                        ->where('ta_realisasi_lrfk.bulan', 'Oktober')
                                        ->where('ta_realisasi_lrfk.sub_unit', $subkeg['nm_sub_unit'])
                                        ->where('ta_realisasi_lrfk.nm_subkegiatan', $subkeg['nm_subkegiatan'])
                                        ->where('ta_realisasi_lrfk.delete_at=', 0)

                                        ->get()
                                        ->getRowArray();
                                    $pagu10 = $datarealisasi10['pagu_rincian'] ?? null;
                                    $real10 = $datarealisasi10['pagu_realisasi'] ?? null;

                                    if ($pagu10 == 0) {
                                        $persentase10 = '0';
                                    } else {
                                        $persentase10 = ($real10 / $pagu10) * 100 ?? NUll;
                                    }
                                    if ($datarealisasi10 == Null) {
                                    ?>
                                        <i class="text-danger">"Realisasi bulan ini Kosong"</i>
                                        <?php
                                    } else {
                                        if ($real10 > $pagu10) {
                                        ?>
                                            <i class="text-danger">"Realisasi bulan ini melebihi pagu"</i>
                                    <?php
                                        } else {
                                            echo 'Rp  ' . esc(number_format($real10, 2, ',', '.'));
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?= esc(number_format($persentase10, 3, ',', '.')); ?>
                                </td>
                                <td>
                                    <a href="#" class="text-secondary"> <i class="bi bi-search"></i> </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Nopember
                                </td>
                                <td><?php
                                    // ->Data Kegiatan APBD
                                    $datarealisasi11 = $this->realisasilrfk->select('ta_realisasi_lrfk.*,a.*')
                                        ->join('ta_apbd_persubkegiatan a', 'a.id = ta_realisasi_lrfk.id_subkegiatan')
                                        ->where('ta_realisasi_lrfk.tahun', $jadwalaktif['tahun'])
                                        ->where('ta_realisasi_lrfk.bulan', 'November')
                                        ->where('ta_realisasi_lrfk.sub_unit', $subkeg['nm_sub_unit'])
                                        ->where('ta_realisasi_lrfk.nm_subkegiatan', $subkeg['nm_subkegiatan'])
                                        ->where('ta_realisasi_lrfk.delete_at=', 0)

                                        ->get()
                                        ->getRowArray();
                                    $pagu11 = $datarealisasi11['pagu_rincian'] ?? null;
                                    $real11 = $datarealisasi11['pagu_realisasi'] ?? null;

                                    if ($pagu11 == 0) {
                                        $persentase11 = '0';
                                    } else {
                                        $persentase11 = ($real11 / $pagu11) * 100 ?? NUll;
                                    }
                                    if ($datarealisasi11 == Null) {
                                    ?>
                                        <i class="text-danger">"Realisasi bulan ini Kosong"</i>
                                        <?php
                                    } else {
                                        if ($real11 > $pagu11) {
                                        ?>
                                            <i class="text-danger">"Realisasi bulan ini melebihi pagu"</i>
                                    <?php
                                        } else {
                                            echo 'Rp  ' . esc(number_format($real11, 2, ',', '.'));
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?= esc(number_format($persentase11, 3, ',', '.')); ?>
                                </td>
                                <td>
                                    <a href="#" class="text-secondary"> <i class="bi bi-search"></i> </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Desember
                                </td>
                                <td><?php
                                    // ->Data Kegiatan APBD
                                    $datarealisasi12 = $this->realisasilrfk->select('ta_realisasi_lrfk.*,a.*')
                                        ->join('ta_apbd_persubkegiatan a', 'a.id = ta_realisasi_lrfk.id_subkegiatan')

                                        ->where('a.pagu_rincian<>', '0')
                                        ->where('ta_realisasi_lrfk.tahun', $jadwalaktif['tahun'])
                                        ->where('ta_realisasi_lrfk.bulan', 'Desember')
                                        ->where('ta_realisasi_lrfk.sub_unit', $subkeg['nm_sub_unit'])
                                        ->where('ta_realisasi_lrfk.nm_subkegiatan', $subkeg['nm_subkegiatan'])
                                        ->where('ta_realisasi_lrfk.delete_at=', 0)

                                        ->get()
                                        ->getRowArray();
                                    $pagu12 = $datarealisasi12['pagu_rincian'] ?? null;
                                    $real12 = $datarealisasi12['pagu_realisasi'] ?? null;

                                    if ($pagu12 == 0) {
                                        $persentase12 = '0';
                                    } else {
                                        $persentase12 = ($real12 / $pagu12) * 100 ?? NUll;
                                    }
                                    if ($datarealisasi12 == Null) {
                                    ?>
                                        <i class="text-danger">"Realisasi bulan ini Kosong"</i>
                                        <?php
                                    } else {
                                        if ($real12 > $pagu12) {
                                        ?>
                                            <i class="text-danger">"Realisasi bulan ini melebihi pagu"</i>
                                    <?php
                                        } else {
                                            echo 'Rp  ' . esc(number_format($real12, 2, ',', '.'));
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?= esc(number_format($persentase12, 3, ',', '.')); ?>
                                </td>
                                <td>
                                    <a href="#" class="text-secondary"> <i class="bi bi-search"></i> </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card -->
        </div>
        <!-- /.card -->
    </div>
    <!--end::Row-->
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Format otomatis saat input berubah
        $('#nominal_tampil').on('input', function() {
            formatRupiahInput(this);
        });

        // Format saat field kehilangan fokus
        $('#nominal_tampil').on('blur', function() {
            formatRupiahInput(this, true);
        });

        // Format saat halaman dimuat (jika ada nilai sebelumnya)
        if ($('#nominal_tampil').val() === '') {
            $('#nominal_tampil').val('Rp 0');
        } else {
            formatRupiahInput($('#nominal_tampil')[0], true);
        }

        // Format sebelum form disubmit
        $('form').on('submit', function() {
            let value = $('#nominal_tampil').val().replace(/[^0-9]/g, '');
            $('#nominal_asli').val(value);
            return true;
        });

        // Fungsi untuk memformat input Rupiah
        function formatRupiahInput(input, blur = false) {
            let value = input.value.replace(/[^0-9]/g, '');

            if (value === '') {
                input.value = 'Rp 0';
                return;
            }

            if (value.length > 15) {
                value = value.substring(0, 15);
            }

            let originalLength = value.length;

            if (blur) {
                // Jika blur dan nilai 0, kosongkan
                if (parseInt(value) === 0) {
                    input.value = 'Rp 0';
                    return;
                }
            }

            // Hilangkan leading zeros
            value = parseInt(value, 10).toString();

            // Format dengan titik sebagai pemisah ribuan
            let reverse = value.toString().split('').reverse().join(''),
                ribuan = reverse.match(/\d{1,3}/g);
            ribuan = ribuan.join('.').split('').reverse().join('');

            // Tambahkan 'Rp ' di depan
            input.value = 'Rp ' + ribuan;

            // Atur posisi kursor
            let newLength = input.value.length;
            let selectionStart = input.selectionStart;
            let selectionEnd = input.selectionEnd;

            // Jika pengguna mengetik (bukan memindahkan kursor)
            if (originalLength === selectionStart && originalLength === selectionEnd) {
                // Jika menambahkan karakter
                if (newLength > selectionStart) {
                    input.setSelectionRange(newLength, newLength);
                }
            }
        }

        // Fokus ke input nominal saat halaman dimuat
        $('#nominal_tampil').focus();
    });
</script>

<!--end::Container-->
<?= $this->endSection() ?>