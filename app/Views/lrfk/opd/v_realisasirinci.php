<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<!--begin::Container-->
<?php

use App\Models\LrfkProvModel\RealisasiSubKegModel;
use App\Models\LrfkProvModel\TaRealisasiRinciModel;

use App\Models\LrfkProvModel\SubKegModel; // as lrfkModel;
$this->subkegmodel = new SubKegModel();
$this->realisasilrfk = new RealisasiSubKegModel();
$this->realisasilrfkrinci = new TaRealisasiRinciModel();

?>
<div class="app-content">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-lg-6">
                <!-- Default box -->
                <div class="card mb-4">
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
                        </div>
                        <div class="alert alert-danger" role="alert">
                            <ul>
                                Jadwal input data yang aktif saat ini adalah Bulan : <?= $jadwalaktif['bulan'] ?>
                            </ul>
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
                                        <label for="basic-url" class="form-label">Akumuasi Realisasi Anggaran Sampai Bulan <?= $jadwalaktif['bulan']  ?></label>
                                        <br>
                                        <label for="basic-url" class="form-label">
                                            <?php // 'Rp  ' . esc(number_format($datarealisasirinci['realisasi'], 2, ',', '.'));  
                                            ?></label>
                                        <div class="input-group">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 10px">#</th>
                                                        <th style="width: 80px">Jenis Belanja</th>
                                                        <th>Pagu</th>
                                                        <th>Realisasi s/d <?= esc($jadwalaktif['bulan']) ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($subkegbelanja as $key => $databelanja) {
                                                    }
                                                    foreach ($datarealisasirinci as $key => $rowbelanja) {

                                                        echo form_hidden('datareal[' . $key . '][id]', esc($rowbelanja['id']));
                                                        echo form_hidden('datareal[' . $key . '][kd_urusan]', esc($rowbelanja['kd_urusan']));
                                                        echo form_hidden('datareal[' . $key . '][nm_urusan]', esc($rowbelanja['nm_urusan']));
                                                        echo form_hidden('datareal[' . $key . '][kd_sub_unit]', esc($rowbelanja['kd_sub_unit']));
                                                        echo form_hidden('datareal[' . $key . '][nm_sub_unit]', esc($rowbelanja['nm_sub_unit']));
                                                        echo form_hidden('datareal[' . $key . '][tahun]', esc($tahunaktif));
                                                        echo form_hidden('datareal[' . $key . '][bulan]', esc($jadwalaktif['bulan']));
                                                        echo form_hidden('datareal[' . $key . '][kd_program]', esc($rowbelanja['kd_program']));
                                                        echo form_hidden('datareal[' . $key . '][nm_program]', esc($rowbelanja['nm_program']));
                                                        echo form_hidden('datareal[' . $key . '][kd_kegiatan]', esc($rowbelanja['kd_kegiatan']));
                                                        echo form_hidden('datareal[' . $key . '][nm_kegiatan]', esc($rowbelanja['nm_kegiatan']));
                                                        echo form_hidden('datareal[' . $key . '][kd_subkegiatan]', esc($rowbelanja['kd_subkegiatan']));
                                                        echo form_hidden('datareal[' . $key . '][nm_subkegiatan]', esc($rowbelanja['nm_subkegiatan']));
                                                        echo form_hidden('datareal[' . $key . '][kd_rek_belanja]', esc($rowbelanja['kd_rek_belanja']));
                                                        echo form_hidden('datareal[' . $key . '][nm_rekening]', esc($rowbelanja['nm_rekening']));
                                                    ?>
                                                        <tr class="align-middle">
                                                            <td><?= esc($key + 1)  ?></td>
                                                            <td><?= esc($rowbelanja['nm_rekening']) ?></td>
                                                            <td>
                                                                <?php
                                                                $pagu_perbel = $this->subkegmodel->dataperbelanjapersk(
                                                                    $rowbelanja['kd_sub_unit'],
                                                                    $rowbelanja['kd_subkegiatan'],
                                                                    $rowbelanja['kd_rek_belanja']
                                                                );
                                                                echo form_hidden('datareal[' . $key . '][pagu_rincian]', esc($rowbelanja['pagu_rincian']));
                                                                ?>
                                                                <?= esc(number_format($rowbelanja['pagu_rincian'], 2, ',', '.')) ?></td>
                                                            <td>
                                                                <label for="basic-url" class="form-label">
                                                                    <?= 'Rp  ' . esc(number_format($rowbelanja['realisasi'], 2, ',', '.'));  ?></label>

                                                                <?php
                                                                $realisasi = [
                                                                    'type' => 'text',
                                                                    'name' => 'datareal[' . $key . '][realisasi]',
                                                                    'value' =>  esc(number_format($rowbelanja['realisasi'], 0, ',', '.')),
                                                                    //'class' => 'form-control form-control-sm',
                                                                    'class' => 'form-control form-control-sm rupiah-input',
                                                                    // 'placeholder' => 'isi perubahan realisasi disini',
                                                                    'required' => 'true'
                                                                ];
                                                                echo form_input($realisasi);
                                                                ?>
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
                                    <div class="mb-3">
                                        <!-- <label for="basic-url" class="form-label">Uraian Progres Realisasi</label> -->
                                        <div class="input-group">
                                            <?php
                                            // $uraian_realisasi = [
                                            //     'type' => 'text',
                                            //     'name' => 'uraian_realisasi',
                                            //     'value' => esc($datarealisasi['uraian_realisasi']),
                                            //     'class' => 'form-control',
                                            //     'placeholder' => 'isi penjelasan mengenai progres pelaksanaan',
                                            //     'required' => 'true'
                                            // ];
                                            // echo form_textarea($uraian_realisasi);
                                            ?>
                                        </div>
                                        <!-- <div class="form-text" id="basic-addon4">
                                            Penjelasan Progres Pelaksanaan berupa realisasi output atau sasaran (yang dapat diukur),
                                        </div> -->
                                    </div>
                                    <div class="mb-3">
                                        <!-- <label for="basic-url" class="form-label">Dokumentasi Pelaksanaan SubKegiatan</label> -->
                                        <?php
                                        //if ($datarealisasi['dokumentasi'] == '') { 
                                        ?>
                                        <!-- <i class="text-danger">"Dokumentasi Realisasi kosong"</i> -->
                                        <?php  // } else { 
                                        ?>
                                        <!-- <img src="../../uploads/lrfkopd/<?php // esc($datarealisasi['sub_unit']) . '/' . esc($datarealisasi['dokumentasi']) 
                                                                                ?>" width="300"> -->
                                        <?php
                                        //}
                                        ?>
                                        <!-- <div class="input-group">
                                            <input type="file" name="dokumentasi" class="form-control" />
                                        </div> -->
                                        <!-- <div class="form-text" id="basic-addon4">
                                            Kirim dokumentasi pelaksanaan kegiatan.
                                        </div> -->
                                    </div>
                                    <div class="mb-3">
                                        <!-- <label for="basic-url" class="form-label">Dokumentasi Pelaksanaan SubKegiatan</label>
                                        <label for="basic-url" class="form-label">Link Dokumentasi lainnya ::
                                            <a href="<?php // esc($datarealisasi['link_dokumentasi']);
                                                        ?>" target="_blank"><?php // esc($datarealisasi['link_dokumentasi']);
                                                                            ?></a>
                                        </label> -->
                                        <div class="input-group">
                                            <?php
                                            // $link_dokumentasi = [
                                            //     'type' => 'text',
                                            //     'name' => 'link_dokumentasi',
                                            //     'value' => old('link_dokumentasi'),
                                            //     'class' => 'form-control',
                                            //     'placeholder' => 'isi link Url Google Drive dokumentasi jika ada perubahan',
                                            //     // 'required' => 'true'
                                            // ];
                                            // // echo form_input($link_dokumentasi);
                                            // echo form_hidden('id', $datarealisasi['id']);
                                            // echo form_hidden('id_subkegiatan', $datarealisasi['id_subkegiatan']);
                                            // echo form_hidden('kd_subkegiatan', esc($datarealisasi['kd_subkegiatan']));
                                            // echo form_hidden('kd_sub_unit', esc($datarealisasi['kd_sub_unit']));
                                            // // echo form_hidden('id_subkegiatan', $subkeg['id']);
                                            // echo form_hidden('totalpagusubkeg', esc($datarealisasi['pagu_subkeg']));
                                            // // echo form_hidden('kd_sub_unit', esc($rowbelanja['kd_sub_unit']));
                                            // echo form_hidden('sub_unit', esc($datarealisasi['nm_sub_unit']));
                                            // echo form_hidden('tahun', esc($tahunaktif));
                                            // echo form_hidden('bulan', esc($jadwalaktif['bulan']));
                                            // echo form_hidden('nm_subkegiatan', esc($datarealisasi['nm_subkegiatan']));

                                            ?>
                                        </div>
                                        <!-- <div class="form-text" id="basic-addon4">
                                            Url dokumentasi pelaksanaan kegiatan lainnya.
                                        </div> -->
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
                <!--end::Row-->
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
                        <?php
                        $datarealisasi0 =  $this->realisasilrfkrinci
                            ->select('*')
                            ->selectSum('pagu_rincian')
                            ->selectSum('realisasi')
                            ->where('tahun', $tahunaktif)
                            ->where('bulan', $jadwalaktif['bulan'])
                            ->where('nm_sub_unit', $subkeg['nm_sub_unit'])
                            ->where('kd_subkegiatan', $subkeg['kd_subkegiatan'])
                            ->where('delete_at=', 0)
                            ->groupBy('nm_subkegiatan')
                            ->get()
                            ->getRowArray();
                        $pagu1 = $datarealisasi0['pagu_rincian'] ?? null;
                        $real1 = $datarealisasi0['realisasi'] ?? null;

                        // echo dd($datarealisasi0);
                        ?>
                        <h3 class="card-title">Realisasi Anggara per-Bulan <?= 'SubKegiatan ' . esc($datarealisasi0['nm_subkegiatan']);
                                                                            ?> </h3>
                        <br> Total Pagu Subkegiatan :: <?= 'Rp  ' . esc(number_format($datarealisasi0['pagu_rincian'], 2, ',', '.'))
                                                        ?>
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
                                        $datarealisasi1 =  $this->realisasilrfkrinci
                                            ->select('*')
                                            ->selectSum('pagu_rincian')
                                            ->selectSum('realisasi')
                                            ->where('tahun', $tahunaktif)
                                            ->where('bulan', 'Januari')
                                            ->where('nm_sub_unit', $subkeg['nm_sub_unit'])
                                            ->where('kd_subkegiatan', $subkeg['kd_subkegiatan'])
                                            ->where('delete_at=', 0)
                                            ->groupBy('nm_subkegiatan')
                                            ->get()
                                            ->getRowArray();
                                        $pagu1 = $datarealisasi1['pagu_rincian'] ?? null;
                                        $real1 = $datarealisasi1['realisasi'] ?? null;

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
                                        $datarealisasi2 = $this->realisasilrfkrinci
                                            ->select('*')
                                            ->selectSum('pagu_rincian')
                                            ->selectSum('realisasi')
                                            ->where('tahun', $tahunaktif)
                                            ->where('bulan', 'Februari')
                                            ->where('nm_sub_unit', $subkeg['nm_sub_unit'])
                                            ->where('kd_subkegiatan', $subkeg['kd_subkegiatan'])
                                            ->where('delete_at=', 0)
                                            ->groupBy('nm_subkegiatan')
                                            ->get()
                                            ->getRowArray();
                                        $pagu2 = $datarealisasi2['pagu_rincian'] ?? null;
                                        $real2 = $datarealisasi2['realisasi'] ?? null;

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
                                        <?php
                                        if ($persentase2 < $persentase1) { ?>
                                            <i class="text-danger">"Realisasi bulan ini kurang dari realisasi bulan sebelumnya "</i>
                                        <?php } else {
                                            echo esc(number_format($persentase2, 3, ',', '.'));
                                        }
                                        ?>
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
                                        $datarealisasi3 = $this->realisasilrfkrinci
                                            ->select('*')
                                            ->selectSum('pagu_rincian')
                                            ->selectSum('realisasi')
                                            ->where('tahun', $tahunaktif)
                                            ->where('bulan', 'Maret')
                                            ->where('nm_sub_unit', $subkeg['nm_sub_unit'])
                                            ->where('kd_subkegiatan', $subkeg['kd_subkegiatan'])
                                            ->where('delete_at=', 0)
                                            ->groupBy('nm_subkegiatan')
                                            ->get()
                                            ->getRowArray();
                                        $pagu3 = $datarealisasi3['pagu_rincian'] ?? null;
                                        $real3 = $datarealisasi3['realisasi'] ?? null;
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
                                        <?php
                                        if ($persentase3 < $persentase2) { ?>
                                            <i class="text-danger">"Realisasi bulan ini kurang dari realisasi bulan sebelumnya "</i>
                                        <?php } else {
                                            echo esc(number_format($persentase3, 3, ',', '.'));
                                        }

                                        ?>
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
                                        $datarealisasi4 = $this->realisasilrfkrinci
                                            ->select('*')
                                            ->selectSum('pagu_rincian')
                                            ->selectSum('realisasi')
                                            ->where('tahun', $tahunaktif)
                                            ->where('bulan', 'April')
                                            ->where('nm_sub_unit', $subkeg['nm_sub_unit'])
                                            ->where('kd_subkegiatan', $subkeg['kd_subkegiatan'])
                                            ->where('delete_at=', 0)
                                            ->groupBy('nm_subkegiatan')
                                            ->get()
                                            ->getRowArray();
                                        $pagu4 = $datarealisasi4['pagu_rincian'] ?? null;
                                        $real4 = $datarealisasi4['realisasi'] ?? null;

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
                                        <?php
                                        if ($persentase4 < $persentase3) { ?>
                                            <i class="text-danger">"Realisasi bulan ini kurang dari realisasi bulan sebelumnya "</i>
                                        <?php } else {
                                            echo esc(number_format($persentase4, 3, ',', '.'));
                                        }
                                        ?>
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
                                        $datarealisasi5 = $this->realisasilrfkrinci
                                            ->select('*')
                                            ->selectSum('pagu_rincian')
                                            ->selectSum('realisasi')
                                            ->where('tahun', $tahunaktif)
                                            ->where('bulan', 'Mei')
                                            ->where('nm_sub_unit', $subkeg['nm_sub_unit'])
                                            ->where('kd_subkegiatan', $subkeg['kd_subkegiatan'])
                                            ->where('delete_at=', 0)
                                            ->groupBy('nm_subkegiatan')
                                            ->get()
                                            ->getRowArray();
                                        $pagu5 = $datarealisasi5['pagu_rincian'] ?? null;
                                        $real5 = $datarealisasi5['realisasi'] ?? null;

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
                                        <?php
                                        if ($persentase5 < $persentase4) { ?>
                                            <i class="text-danger">"Realisasi bulan ini kurang dari realisasi bulan sebelumnya"</i>
                                        <?php } else {
                                            echo esc(number_format($persentase5, 3, ',', '.'));
                                        }
                                        ?>
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
                                        $datarealisasi6 = $this->realisasilrfkrinci
                                            ->select('*')
                                            ->selectSum('pagu_rincian')
                                            ->selectSum('realisasi')
                                            ->where('tahun', $tahunaktif)
                                            ->where('bulan', 'Juni')
                                            ->where('nm_sub_unit', $subkeg['nm_sub_unit'])
                                            ->where('kd_subkegiatan', $subkeg['kd_subkegiatan'])
                                            ->where('delete_at=', 0)
                                            ->groupBy('nm_subkegiatan')
                                            ->get()
                                            ->getRowArray();
                                        $pagu6 = $datarealisasi6['pagu_rincian'] ?? null;
                                        $real6 = $datarealisasi6['realisasi'] ?? null;

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
                                        <?php
                                        if ($persentase6 < $persentase5) { ?>
                                            <i class="text-danger">"Realisasi bulan ini kurang dari realisasi bulan sebelumnya "</i>
                                        <?php } else {
                                            echo esc(number_format($persentase6, 3, ',', '.'));
                                        }
                                        ?>
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
                                        $datarealisasi7 = $this->realisasilrfkrinci
                                            ->select('*')
                                            ->selectSum('pagu_rincian')
                                            ->selectSum('realisasi')
                                            ->where('tahun', $tahunaktif)
                                            ->where('bulan', 'Juli')
                                            ->where('nm_sub_unit', $subkeg['nm_sub_unit'])
                                            ->where('kd_subkegiatan', $subkeg['kd_subkegiatan'])
                                            ->where('delete_at=', 0)
                                            ->groupBy('nm_subkegiatan')
                                            ->get()
                                            ->getRowArray();
                                        $pagu7 = $datarealisasi7['pagu_rincian'] ?? null;
                                        $real7 = $datarealisasi7['realisasi'] ?? null;

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
                                        <?php
                                        if ($persentase7 < $persentase6) { ?>
                                            <i class="text-danger">"Realisasi bulan ini kurang dari realisasi bulan sebelumnya "</i>
                                        <?php } else {
                                            echo esc(number_format($persentase7, 3, ',', '.'));
                                        }
                                        ?>
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
                                        $datarealisasi8 = $this->realisasilrfkrinci
                                            ->select('*')
                                            ->selectSum('pagu_rincian')
                                            ->selectSum('realisasi')
                                            ->where('tahun', $tahunaktif)
                                            ->where('bulan', 'Agustus')
                                            ->where('nm_sub_unit', $subkeg['nm_sub_unit'])
                                            ->where('kd_subkegiatan', $subkeg['kd_subkegiatan'])
                                            ->where('delete_at=', 0)
                                            ->groupBy('nm_subkegiatan')
                                            ->get()
                                            ->getRowArray();
                                        $pagu8 = $datarealisasi8['pagu_rincian'] ?? null;
                                        $real8 = $datarealisasi8['realisasi'] ?? null;

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
                                        <?php
                                        if ($persentase8 < $persentase7) { ?>
                                            <i class="text-danger">"Realisasi bulan ini kurang dari realisasi bulan sebelumnya "</i>
                                        <?php } else {
                                            echo esc(number_format($persentase8, 3, ',', '.'));
                                        }
                                        ?>
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
                                        $datarealisasi9 = $this->realisasilrfkrinci
                                            ->select('*')
                                            ->selectSum('pagu_rincian')
                                            ->selectSum('realisasi')
                                            ->where('tahun', $tahunaktif)
                                            ->where('bulan', 'September')
                                            ->where('nm_sub_unit', $subkeg['nm_sub_unit'])
                                            ->where('kd_subkegiatan', $subkeg['kd_subkegiatan'])
                                            ->where('delete_at=', 0)
                                            ->groupBy('nm_subkegiatan')
                                            ->get()
                                            ->getRowArray();
                                        $pagu9 = $datarealisasi9['pagu_rincian'] ?? null;
                                        $real9 = $datarealisasi9['realisasi'] ?? null;

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
                                        <?php
                                        if ($persentase9 < $persentase8) { ?>
                                            <i class="text-danger">"Realisasi bulan ini kurang dari realisasi bulan sebelumnya "</i>
                                        <?php } else {
                                            echo esc(number_format($persentase9, 3, ',', '.'));
                                        }
                                        ?>
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
                                        $datarealisasi10 = $this->realisasilrfkrinci
                                            ->select('*')
                                            ->selectSum('pagu_rincian')
                                            ->selectSum('realisasi')
                                            ->where('tahun', $tahunaktif)
                                            ->where('bulan', 'Oktober')
                                            ->where('nm_sub_unit', $subkeg['nm_sub_unit'])
                                            ->where('kd_subkegiatan', $subkeg['kd_subkegiatan'])
                                            ->where('delete_at=', 0)
                                            ->groupBy('nm_subkegiatan')
                                            ->get()
                                            ->getRowArray();
                                        $pagu10 = $datarealisasi10['pagu_rincian'] ?? null;
                                        $real10 = $datarealisasi10['realisasi'] ?? null;

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
                                        <?php
                                        if ($persentase10 < $persentase9) { ?>
                                            <i class="text-danger">"Realisasi bulan ini kurang dari realisasi bulan sebelumnya "</i>
                                        <?php } else {
                                            echo esc(number_format($persentase10, 3, ',', '.'));
                                        }
                                        ?>
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
                                        $datarealisasi11 = $this->realisasilrfkrinci
                                            ->select('*')
                                            ->selectSum('pagu_rincian')
                                            ->selectSum('realisasi')
                                            ->where('tahun', $tahunaktif)
                                            ->where('bulan', 'November')
                                            ->where('nm_sub_unit', $subkeg['nm_sub_unit'])
                                            ->where('kd_subkegiatan', $subkeg['kd_subkegiatan'])
                                            ->where('delete_at=', 0)
                                            ->groupBy('nm_subkegiatan')
                                            ->get()
                                            ->getRowArray();
                                        $pagu11 = $datarealisasi11['pagu_rincian'] ?? null;
                                        $real11 = $datarealisasi11['realisasi'] ?? null;

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
                                        <?php
                                        if ($persentase11 < $persentase10) { ?>
                                            <i class="text-danger">"Realisasi bulan ini kurang dari realisasi bulan sebelumnya "</i>
                                        <?php } else {
                                            echo esc(number_format($persentase11, 3, ',', '.'));
                                        }
                                        ?>
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
                                        $datarealisasi12 = $this->realisasilrfkrinci
                                            ->select('*')
                                            ->selectSum('pagu_rincian')
                                            ->selectSum('realisasi')
                                            ->where('tahun', $tahunaktif)
                                            ->where('bulan', 'Desember')
                                            ->where('nm_sub_unit', $subkeg['nm_sub_unit'])
                                            ->where('kd_subkegiatan', $subkeg['kd_subkegiatan'])
                                            ->where('delete_at=', 0)
                                            ->groupBy('nm_subkegiatan')
                                            ->get()
                                            ->getRowArray();
                                        $pagu12 = $datarealisasi12['pagu_rincian'] ?? null;
                                        $real12 = $datarealisasi12['realisasi'] ?? null;

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
                                        <?php
                                        if ($persentase12 < $persentase11) { ?>
                                            <i class="text-danger">"Realisasi bulan ini kurang dari realisasi bulan sebelumnya "</i>
                                        <?php } else {
                                            echo esc(number_format($persentase12, 3, ',', '.'));
                                        }
                                        ?>
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
        <!-- /.col-md-6 -->
    </div>
    <!--end::Row-->
</div>
<!--end::Container-->
</div>
<!--end::App Content-->

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