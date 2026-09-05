<!DOCTYPE html>
<html>

<head>
    <title>Cetak PDF</title>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        th {
            border: 1px solid #000000;
            text-align: center;
            height: 15px;
            margin: 1px;
        }
    </style>
</head>

<body>
    <?php

    use App\Models\LrfkProvModel\RealisasiSubKegModel;
    use App\Models\LrfkProvModel\TotalRealisasiModel;
    use App\Models\LrfkProvModel\TotalRealisasiBulanModel;
    use App\Models\LrfkProvModel\SubKegModel; // as lrfkModel;
    use App\Models\LrfkProvModel\TaRealisasiRinciModel;

    $this->subkegmodel = new SubKegModel();
    $this->realisasilrfk = new RealisasiSubKegModel();
    $this->totalrealisasiM = new TotalRealisasiModel();
    $this->totalrealisasiblnM = new TotalRealisasiBulanModel();
    $this->realisasilrfkrinci = new TaRealisasiRinciModel();

    ?>
    <?php
    foreach ($datajadwal as $key => $data) { ?>
        <?php esc($data['bulan']) ?>
        <?php esc($data['tahun']) ?>
    <?php
    } ?>
    <i style="font-size:12px;text-align:center;">LAPORAN REALISASI FISIK ANGGARAN TA <?= esc($data['tahun']); ?></i>
    <br>
    <i style="font-size:12px;text-align:center;">Perangkat Daerah : <?= esc($datauser['sub_unit']); ?></i>
    <br>
    <?php
    if ($bulanpilih == '') {
        echo 'Realisasi Tahun Anggaran 2025';
    } else {
        echo 'Realisasi Sampai dengan Bulan :' . esc($bulanpilih);
    }
    ?>
    <?php
    foreach ($listprogram as $key => $value) {
        if ($bulanpilih == '') {
            $RUrusan = $this->totalrealisasiM->RUrusanOpd($data['tahun'], $value['kd_sub_unit'], $datauser['sub_unit'], $value['kd_urusan']);
            if (!$RUrusan) {
                $RealUrusan = 0;
            } else {
                $RealUrusan = $RUrusan['total_realisasi'];
            }
        } else {
            $RUrusanBln = $this->totalrealisasiblnM->RperUrusanOpd($data['tahun'], $bulanpilih, $value['kd_sub_unit'], $value['kd_urusan']);
            //echo dd($data['tahun'], $bulanpilih, $value['kd_sub_unit'], $value['kd_urusan']);
            //  echo dd($RUrusanBln);

            if (!$RUrusanBln) {
                $RealUrusan = 0;
            } else {
                $RealUrusan =  $RUrusanBln['total_realisasi'];;
            }
        }
    } ?><br>
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="font-size:12px; text-align:center; width:35%;">Program/Kegiatan/Subkegiatan</th>
                <th colspan="3" style="font-size:12px; text-align:center; width:35%;">Anggaran</th>

                <?php if ($bulanpilih == '') { ?>
                    <th colspan="2" style="font-size:12px; text-align:center;width:30%;">Capaian Fisik Keuangan per-Sub Kegiatan</th>
                <?php } else { ?>
                    <th rowspan="2" style="font-size:12px; text-align:center;width:30%;">Dokumentasi Progres Realisasi Kegiatan</th>
                <?php  }  ?>
            </tr>
            <tr>

                <th style="font-size:12px; text-align:center; width:15%;">Pagu Anggaran</th>
                <?php if ($bulanpilih == '') { ?>
                    <th style="font-size:12px; text-align:center;width:15%;">Pagu Realisasi</th>
                <?php } else { ?>
                    <th style="font-size:12px; text-align:center;width:15%;">Pagu Realisasi
                        <?= "Bulan  " . $bulanpilih; ?>
                    </th>
                <?php  }  ?>
                <th style="font-size:12px; text-align:center; width:5%;">% </th>
                <?php if ($bulanpilih == '') { ?>
                    <th style="font-size:12px; text-align:center;width:15%;">Capaian %</th>
                    <th style="font-size:12px; text-align:center;width:15%;">Kinerja</th>
                <?php } else { ?>
                <?php  }  ?>

            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($listprogram as $key => $value) {

                // ->Data Program APBD
                $dataprogram = $this->subkegmodel->select('*')->selectSUM('pagu_rincian')->where('pagu_rincian<>', '0')
                    ->where('kd_sub_unit', $value['kd_sub_unit'])
                    ->where('kd_urusan', $value['kd_urusan'])
                    ->where('pagu_rincian<>', '0')
                    ->groupBy('kd_program')
                    // ->orderBy('kd_sub_unit', 'ASC')
                    ->get()
                    ->getResultArray();
                foreach ($dataprogram as $key => $rowprogram) {
            ?>
                    <tr>
                        <td style="border: 1px solid #000000; font-size:9px; text-align:left; width:35%;"><?= esc($rowprogram['nm_program']) ?></td>
                        <td style="border: 1px solid #000000; font-size:9px; text-align:right;  width:15%;"><?= 'Rp  ' . esc(number_format($rowprogram['pagu_rincian'], 2, ',', '.')) ?></td>
                        <?php if ($bulanpilih == '') {
                            $RProgram = $this->totalrealisasiM->RUrusanProgramOpd($data['tahun'], $value['kd_sub_unit'], $datauser['sub_unit'], $value['kd_urusan'], $rowprogram['kd_program']);
                            if (!$RProgram) {
                                $RealProgram = 0;
                            } else {
                                $RealProgram = $RProgram['total_realisasi'];
                            } ?>
                            <td style="border: 1px solid #000000; font-size:9px; text-align:right;  width:15%;"><?= 'Rp  ' . esc(number_format($RealProgram, 2, ',', '.')) ?></td>
                        <?php } else {
                            // $RProgramPerBln = $this->totalrealisasiblnM->RUrusanProgramOpd($data['tahun'], $bulanpilih, $rowprogram['kd_sub_unit'], $datauser['sub_unit'], $rowprogram['kd_urusan'], $rowprogram['kd_program']);
                            $RProgramPerBln = $this->totalrealisasiblnM->RUrusanProgramOpd($data['tahun'], $bulanpilih, $value['kd_sub_unit'], $datauser['sub_unit'], $value['kd_urusan'], $rowprogram['kd_program']);

                        ?>
                            <td style="border: 1px solid #000000; font-size:9px; text-align:right ;  width:15%;"><?= 'Rp  ' . esc(number_format($RProgramPerBln['total_realisasi'], 2, ',', '.')) ?></td>
                        <?php } ?>
                        <td style="border: 1px solid #000000; font-size:9px; text-align:center ; width:5%"></td>
                        <td style="border: 1px solid #000000; width:25%; font-size:9px; text-align:center ; width:30%;"></td>
                    </tr>
                    <?php
                    // ->Data Kegiatan APBD
                    $datakegiatan = $this->subkegmodel->select('*')->selectSUM('pagu_rincian')->where('pagu_rincian<>', '0')
                        ->where('kd_sub_unit', $value['kd_sub_unit'])
                        ->where('kd_urusan', $value['kd_urusan'])
                        ->where('kd_program', $rowprogram['kd_program'])
                        ->groupBy('kd_kegiatan')
                        ->where('pagu_rincian<>', '0')
                        ->get()
                        ->getResultArray();
                    foreach ($datakegiatan as $key => $rowkegiatan) {
                        $RKegiatan = $this->totalrealisasiM->RUrusanProgramKegiatanOpd($data['tahun'], $value['kd_sub_unit'], $datauser['sub_unit'], $value['kd_urusan'], $rowprogram['kd_program'], $rowkegiatan['kd_kegiatan']);
                    ?>
                        <tr>
                            <td style="border: 1px solid #000000; font-size:9px; text-align:left; width:35%;"><?= 'Kegiatan ' . esc($rowkegiatan['nm_kegiatan']) ?></td>
                            <td style="border: 1px solid #000000; font-size:9px; text-align:right; width:15%;"><?= 'Rp  ' . esc(number_format($rowkegiatan['pagu_rincian'], 2, ',', '.')) ?></td>
                            <?php if ($bulanpilih == '') {
                                if (!$RKegiatan) {
                                    $RealKegiatan = 0;
                                } else {
                                    $RealKegiatan = $RKegiatan['total_realisasi'];
                                } ?>
                                <td style="border: 1px solid #000000; font-size:9px; text-align:right; width:15%;"><?= 'Rp  ' . esc(number_format($RealKegiatan, 2, ',', '.')) ?></td>
                            <?php } else {
                                $RKegiatanPerBln = $this->totalrealisasiblnM->RUrusanProgramKegiatanOpd($data['tahun'], $bulanpilih, $value['kd_sub_unit'], $datauser['sub_unit'], $value['kd_urusan'], $rowprogram['kd_program'], $rowkegiatan['kd_kegiatan']);
                                //  $RKegiatanPerBln = $this->totalrealisasiblnM->RUrusanProgramKegiatanOpd($data['tahun'], $bulanpilih, $value['kd_sub_unit'], $datauser['sub_unit'], $value['kd_urusan'], $rowprogram['kd_program'], $rowkegiatan['kd_kegiatan']);

                            ?>
                                <td style="border: 1px solid #000000; font-size:9px; text-align:right; width:15%;"><?= 'Rp  ' . esc(number_format($RKegiatanPerBln['total_realisasi'], 2, ',', '.')) ?></td>
                            <?php } ?>
                            <td style="border: 1px solid #000000; font-size:9px; text-align:right; width:5%;"></td>
                            <td style="border: 1px solid #000000; font-size:9px; text-align:left; width:30%;"></td>
                        </tr>
                        <?php
                        // ->Data Kegiatan APBD
                        $datasubkegiatan = $this->subkegmodel->select('*')
                            ->selectSum('pagu_rincian')
                            ->where('pagu_rincian<>', '0')
                            ->where('kd_sub_unit', $value['kd_sub_unit'])
                            ->where('kd_urusan', $value['kd_urusan'])
                            ->where('kd_program', $rowprogram['kd_program'])
                            ->where('kd_kegiatan', $rowkegiatan['kd_kegiatan'])
                            ->where('pagu_rincian<>', '0')
                            ->groupBy('kd_subkegiatan')
                            ->get()
                            ->getResultArray();
                        ?><?php
                            foreach ($datasubkegiatan as $key => $rowsubkegiatan) { ?>
                        <tr style="background-color:rgb(224, 234, 139);">
                            <td style="border: 1px solid #000000; font-size:9px; text-align:left; width:35%;"><?= esc($rowsubkegiatan['nm_subkegiatan']) ?></td>
                            <td style="border: 1px solid #000000; font-size:9px; text-align:right; width:15%;"><?= 'Rp  ' . esc(number_format($rowsubkegiatan['pagu_rincian'], 2, ',', '.')) ?></td>
                            <td style="border: 1px solid #000000; font-size:9px; text-align:right; width:15%;">
                                <?php
                                if ($bulanpilih == '') {
                                    $this->realisasilrfk = new RealisasiSubKegModel();
                                    // ->Data Kegiatan APBD
                                    $datarealisasi = $this->realisasilrfk->select('*')
                                        ->where('tahun', $jadwalaktif['tahun'])
                                        ->where('bulan', $jadwalaktif['bulan'])
                                        // ->where('bulan', $bulanpilih)
                                        ->where('sub_unit', $rowsubkegiatan['nm_sub_unit'])
                                        ->where('nm_subkegiatan', $rowsubkegiatan['nm_subkegiatan'])
                                        ->get()
                                        ->getRowArray();
                                    $pagu = $rowsubkegiatan['pagu_rincian'];
                                    $real = $datarealisasi['pagu_realisasi'] ?? null;
                                    $persentase = ($real / $pagu) * 100 ?? NUll;
                                    if ($real > $pagu) {
                                        echo "Realisasi bulan ini melebihi pagu";
                                    } else {
                                        echo 'Rp  ' . esc(number_format($real, 2, ',', '.'));
                                    }
                                    // echo 'Rp  ' . esc(number_format($jumlahRperSK, 2, ',', '.'));
                                    //  echo $jadwalaktif['tahun'] . '/' . $rowsubkegiatan['nm_subkegiatan'] . '/' . $rowsubkegiatan['nm_sub_unit'] .
                                    //$jumlahRperSK;

                                    $persentase = "0";
                                    $datarealisasi = null;
                                } else {
                                    $this->realisasilrfk = new RealisasiSubKegModel();
                                    // ->Data Kegiatan APBD
                                    $datarealisasi = $this->realisasilrfk->select('*')
                                        ->where('tahun', $jadwalaktif['tahun'])
                                        //->where('bulan', $jadwalaktif['bulan'])
                                        ->where('bulan', $bulanpilih)
                                        ->where('sub_unit', $rowsubkegiatan['nm_sub_unit'])
                                        ->where('nm_subkegiatan', $rowsubkegiatan['nm_subkegiatan'])
                                        ->get()
                                        ->getRowArray();
                                    $pagu = $rowsubkegiatan['pagu_rincian'];
                                    $real = $datarealisasi['pagu_realisasi'] ?? null;
                                    $persentase = ($real / $pagu) * 100 ?? NUll;
                                    if ($real > $pagu) {
                                        echo "Realisasi bulan ini melebihi pagu";
                                    } else {
                                        echo 'Rp  ' . esc(number_format($real, 2, ',', '.'));
                                    }
                                } ?>
                            </td>
                            <td style="border: 1px solid #000000; font-size:9px; text-align:right; width:5%;">
                                <?php
                                if ($bulanpilih == '') {
                                    // $jumlahRperSK =  $J1 + $J2a + $J3a +
                                    //   $J4a + $J5a + $J6a + $J7a +
                                    //  $J8a + $J9a + $J10a + $J11a + $J12a;
                                    $this->realisasilrfk = new RealisasiSubKegModel();
                                    // ->Data Kegiatan APBD
                                    $datarealisasi = $this->realisasilrfk->select('*')
                                        ->where('tahun', $jadwalaktif['tahun'])
                                        ->where('bulan', $jadwalaktif['bulan'])
                                        // ->where('bulan', $bulanpilih)
                                        ->where('sub_unit', $rowsubkegiatan['nm_sub_unit'])
                                        ->where('nm_subkegiatan', $rowsubkegiatan['nm_subkegiatan'])
                                        ->get()
                                        ->getRowArray();
                                    $pagu = $rowsubkegiatan['pagu_rincian'];
                                    $real = $datarealisasi['pagu_realisasi'] ?? null;
                                    $persentase = ($real / $pagu) * 100 ?? NUll;
                                    if ($real > $pagu) {
                                        $selisih = $real - $pagu; ?>
                                        <i class="fas fa-angle-left right text-danger">Total Realisasi melebihi pagu<br> sebesar:<br>
                                            <?= esc(number_format($selisih, 2, ',', '.')) ?></i>
                                    <?php   } else {
                                        echo esc(number_format($persentase, 2, ',', '.'));
                                    }
                                } else {
                                    // ->Data Kegiatan APBD
                                    $datarealisasi = $this->realisasilrfk->select('*')
                                        ->where('tahun', $jadwalaktif['tahun'])
                                        //->where('bulan', $jadwalaktif['bulan'])
                                        ->where('bulan', $bulanpilih)
                                        ->where('sub_unit', $rowsubkegiatan['nm_sub_unit'])
                                        ->where('nm_subkegiatan', $rowsubkegiatan['nm_subkegiatan'])
                                        ->get()
                                        ->getRowArray();
                                    $pagu = $rowsubkegiatan['pagu_rincian'];
                                    $real = $datarealisasi['pagu_realisasi'] ?? null;
                                    $persentase = ($real / $pagu) * 100 ?? NUll;
                                    if ($real > $pagu) {
                                        $selisih = $real - $pagu; ?>
                                        <i class="fas fa-angle-left right text-danger">Total Realisasi melebihi pagu<br> sebesar:<br>
                                            <?= esc(number_format($selisih, 2, ',', '.')) ?></i>
                                <?php } else {
                                        echo esc(number_format($persentase, 2, ',', '.'));
                                    }
                                } ?>
                            </td>
                            <?php if ($bulanpilih == '') { ?>
                                <td style="border: 1px solid #000000; font-size:9px; text-align:right; width:15%;">
                                    <?php
                                    $datarincireal = $this->realisasilrfkrinci
                                        ->select('*')
                                        //  ->select('(format((realisasi / pagu_rincian),2)) as isi')
                                        ->select('((realisasi / pagu_rincian)+1) as isi')
                                        ->where('kd_sub_unit', $value['kd_sub_unit'])
                                        ->where('tahun', $data['tahun'])
                                        ->where('bulan', $jadwalaktif['bulan'])
                                        ->where('kd_subkegiatan', $rowsubkegiatan['kd_subkegiatan'])
                                        ->where('(format((realisasi / pagu_rincian),2)) >', 0.05)
                                        ->where('realisasi<>', 0)
                                        ->get()
                                        ->getResultArray();
                                    $da = $datarincireal;
                                    $mul = 1;
                                    foreach ($da as $i => $na)
                                        // $d = 1 + $na['isi'];
                                        $mul = $i == 0 ? $na['isi'] : $mul * $na['isi'];
                                    // $mul = $i == 0 ? $d : $mul * $d;
                                    if (count($da) == 0) {
                                        echo " 0  %";
                                    } else {
                                        $croopd = (pow((float)$mul, 1 / count($da)) - 1) * 100;
                                        //$croopd = ((pow((float)$mul, 1 / count($da)))) * 100;
                                        echo esc(number_format($croopd, 2, ".", ","));
                                    }
                                    foreach ($datarincireal as $key => $rowX) {
                                        // echo '//' . $rowX['isi'] . '//';
                                    }
                                    ?>
                                </td>
                                <td style="border: 1px solid #000000; font-size:9px; text-align:right; width:15%;">Berkinerja 'BAIK'</td>
                            <?php } else { ?>
                                <?php
                                    if (!$datarealisasi) { ?>
                                    <td style="border: 1px solid #000000; font-size:9px; text-align:right; width:30%;"></td>
                                <?php } else {
                                ?>
                                    <td style="border: 1px solid #000000; font-size:9px; text-align:right; width:30%;">
                                        <img src="uploads/lrfkopd/<?= esc($datarealisasi['sub_unit'] ?? null)  . '/' . esc($datarealisasi['dokumentasi'] ?? null) ?>" width="150">
                                        <br><?= esc($datarealisasi['uraian_realisasi'] ?? null); ?>
                                    </td>
                                <?php } ?>
                            <?php  }  ?>
                        </tr>

        <?php }
                        }
                    }
                }
        ?>
        <tr>
            <?php
            if ($bulanpilih == '') {
                $totalReal =  $this->totalrealisasiM->total($jadwalaktif['tahun'], $value['kd_sub_unit'], $rowsubkegiatan['nm_sub_unit']);
                $jmltotpagu = $this->subkegmodel->totpaguopd($value['kd_sub_unit']);
                if ($totalReal == Null) {
            ?>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;">Jumlah Total</td>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;"><?= 'Rp ' . esc(number_format($jmltotpagu['pagu_rincian'], 2, ',', '.')) ?></td>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;"><?= 'Rp ' . esc(number_format(0, 2, ',', '.')) ?></td>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;"></td>

                <?php
                } else {
                ?>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;">Jumlah Total</td>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;"><?= 'Rp ' . esc(number_format($jmltotpagu['pagu_rincian'], 2, ',', '.')) ?></td>

                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;"><?= 'Rp ' . esc(number_format($totalReal['total_realisasi'], 2, ',', '.')) ?></td>
                    <?php
                    $persentase = ($totalReal['total_realisasi'] / $jmltotpagu['pagu_rincian']) * 100 ?? NUll;
                    if ($totalReal['total_realisasi'] > $jmltotpagu['pagu_rincian']) {
                        $selisih = $totalReal['total_realisasi'] - $jmltotpagu['pagu_rincian']; ?>
                        <td> <i class="fas fa-angle-left right text-danger">Total Realisasi melebihi pagu<br> sebesar:<br>
                                <?= esc(number_format($selisih, 2, ',', '.')) ?></i></td>
                        <td style="border: 1px solid #000000; font-size:9px; text-align:left;">tes1</td>
                        <td style="border: 1px solid #000000; font-size:9px; text-align:left;">tes1</td>


                    <?php } else { ?>
                        <td style="border: 1px solid #000000; font-size:9px; text-align:left;">
                            <?=
                            esc(number_format($persentase, 2, ',', '.')); ?> </td>
                        <?php
                        if ($bulanpilih == '') {
                            $datarealsubkeg = $this->totalrealisasiM
                                //            ->select('*')
                                //  ->select('(format((realisasi / pagu_rincian),2)) as isi')
                                ->select('(total_realisasi / pagu_subkeg)+1 as isi')
                                ->where('kd_sub_unit', $value['kd_sub_unit'])
                                ->where('tahun', $data['tahun'])
                                //  ->where('kd_subkegiatan', $rowsubkegiatan['kd_subkegiatan'])
                                // ->where('(format((total_realisasi / pagu_subkeg),2)) >', 0.05)
                                // ->where('total_realisasi<>', 0)
                                ->get()
                                ->getResultArray();
                            $da = $datarealsubkeg;
                            $mul = 1;
                            foreach ($da as $i => $na)
                                // $d = 1 + $na['isi'];
                                $mul = $i == 0 ? $na['isi'] : $mul * $na['isi'];
                            // $mul = $i == 0 ? $d : $mul * $d;
                            if (count($da) == 0) {
                                echo " 0  %";
                            } else {
                                $croopd = (pow((float)$mul, 1 / count($da)) - 1) * 100; ?>
                                <td style="border: 1px solid #000000; font-size:9px; text-align:right;">
                                    <?= esc(number_format($croopd, 2, ".", ",")); ?>
                                </td>
                                <td style="border: 1px solid #000000; font-size:9px; text-align:right;">Berkinerja BAIK</td>
                            <?php  }
                            //echo dd($datarealsubkeg);
                            ?>
                        <?php  } else {
                        }
                        ?>
                    <?php   }
                }
            } else {

                $jmltotpagu = $this->subkegmodel->totpaguopd($value['kd_sub_unit']);

                $datarealisasi = $this->realisasilrfk->totalRperBlnOPD($jadwalaktif['tahun'], $bulanpilih, $rowsubkegiatan['nm_sub_unit']);
                if ($datarealisasi == null) {
                    ?>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;">Jumlah Total</td>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;"><?= 'Rp ' . esc(number_format($jmltotpagu['pagu_rincian'], 2, ',', '.')) ?></td>

                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;"><?= 'Rp ' . esc(number_format(0, 2, ',', '.'))  ?></td>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;">
                        <?=
                        esc(number_format(0, 2, ',', '.')); ?></td>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;">tes3</td>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;">tes3</td>

                <?php
                } else {

                ?>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;">Jumlah Total</td>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;"><?= 'Rp ' . esc(number_format($jmltotpagu['pagu_rincian'], 2, ',', '.')) ?></td>

                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;"><?= 'Rp ' . esc(number_format($datarealisasi['pagu_realisasi'], 2, ',', '.'))  ?></td>
                    <?php

                    $persentase = ($datarealisasi['pagu_realisasi']  / $jmltotpagu['pagu_rincian']) * 100 ?? NUll;
                    if ($datarealisasi['pagu_realisasi']  > $jmltotpagu['pagu_rincian']) {
                        $selisih = $datarealisasi['pagu_realisasi']  - $jmltotpagu['pagu_rincian']; ?>
                        <td style="border: 1px solid #000000; font-size:9px; text-align:left;"> <i class="fas fa-angle-left right text-danger">Total Realisasi melebihi pagu<br> sebesar:<br>
                                <?= esc(number_format($selisih, 2, ',', '.')) ?></i></td>
                    <?php } else { ?>
                        <td style="border: 1px solid #000000; font-size:9px; text-align:left;">
                            <?=
                            esc(number_format($persentase, 2, ',', '.')); ?> </td>
                        <td style="border: 1px solid #000000; font-size:9px; text-align:left;"></td>
            <?php  }
                }
            }
            ?>

        </tr>
        </tbody>
    </table>
</body>

</html>