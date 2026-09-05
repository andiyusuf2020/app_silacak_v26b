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
    $this->subkegmodel = new SubKegModel();
    $this->realisasilrfk = new RealisasiSubKegModel();
    $this->totalrealisasiM = new TotalRealisasiModel();
    $this->totalrealisasiblnM = new TotalRealisasiBulanModel();
    ?>
    <?php
    foreach ($datajadwal as $key => $data) { ?>
        <?php esc($data['bulan']) ?>
        <?php esc($data['tahun']) ?>
    <?php
    } ?>
    <i style="font-size:12px;text-align:center;">LAPORAN REALISASI ANGGARAN TA <?= esc($data['tahun']); ?></i>
    <br>
    <i style="font-size:12px;text-align:center;">Perangkat Daerah : <?= esc($datauser['sub_unit']); ?></i>
    <br>
    <?= 'Realisasi Sampai dengan Bulan :' . esc($bulanpilih); ?>
    <?php
    foreach ($listprogram as $key => $value) {
        if ($bulanpilih == '') {
            $RUrusan = $this->totalrealisasiM->RUrusanOpd($data['tahun'], $value['kd_sub_unit'], $datauser['sub_unit'], $value['kd_urusan']);
            if (!$RUrusan) {
                $RealUrusan = 0;
            } else {
                $RealUrusan = $RUrusan['total_realisasi'];
            }
            echo 'Urusan :' . esc($value['nm_urusan']) ?> <br>
            <?= 'Pagu per-Urusan  Rp  ' . esc(number_format($value['pagu_rincian'], 2, ',', '.')) ?> <br>
            <?= 'Realisasi per-Urusan Rp  ' . esc(number_format($RUrusan['total_realisasi'], 2, ',', '.')) ?><br>

        <?php } else {
            $RUrusanBln = $this->totalrealisasiblnM->RperUrusanOpd($data['tahun'], $bulanpilih, $value['kd_sub_unit'], $datauser['sub_unit'], $value['kd_urusan']);
            //echo dd($RUrusanBln);

            if (!$RUrusanBln) {
                $RealUrusan = 0;
            } else {
                $RealUrusan =  $RUrusanBln['total_realisasi'];;
            }
        }

        ?>
        <br>
        <table>
            <thead>
                <tr>
                    <th style="font-size:12px; text-align:center; width:35%;">Program/Kegiatan/Subkegiatan</th>
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
                        <th style="font-size:12px; text-align:center;width:30%;">Label Sub Kegiatan pada SIPD Kemendagri</th>
                    <?php } else { ?>
                        <th style="font-size:12px; text-align:center;width:30%;">Dokumentasi Progres Realisasi Kegiatan</th>
                    <?php  }  ?>
                </tr>
            </thead>
            <tbody>
                <?php
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
                        <tr>
                            <td style="border: 1px solid #000000; font-size:9px; text-align:left; width:35%;"><?= esc($rowsubkegiatan['nm_subkegiatan']) ?></td>
                            <td style="border: 1px solid #000000; font-size:9px; text-align:right; width:15%;"><?= 'Rp  ' . esc(number_format($rowsubkegiatan['pagu_rincian'], 2, ',', '.')) ?></td>
                            <td style="border: 1px solid #000000; font-size:9px; text-align:right; width:15%;">
                                <?php
                                if ($bulanpilih == '') {
                                    $this->realisasilrfk = new RealisasiSubKegModel();
                                    // ->Data Kegiatan APBD
                                    $R1 = $this->realisasilrfk->select('*')
                                        ->where('tahun', $jadwalaktif['tahun'])
                                        ->where('bulan', 'Januari')
                                        ->where('delete_at=', 0)
                                        ->where('sub_unit', $rowsubkegiatan['nm_sub_unit'])
                                        ->where('kd_subkegiatan', $rowsubkegiatan['kd_subkegiatan'])
                                        ->get()
                                        ->getRowArray();
                                    $R2 = $this->realisasilrfk->select('*')
                                        ->where('tahun', $jadwalaktif['tahun'])
                                        ->where('bulan', 'Februari')
                                        ->where('delete_at=', 0)
                                        ->where('sub_unit', $rowsubkegiatan['nm_sub_unit'])
                                        ->where('nm_subkegiatan', $rowsubkegiatan['nm_subkegiatan'])
                                        ->get()
                                        ->getRowArray();
                                    $R3 = $this->realisasilrfk->select('*')
                                        ->where('tahun', $jadwalaktif['tahun'])
                                        ->where('bulan', 'Maret')
                                        ->where('delete_at=', 0)
                                        ->where('sub_unit', $rowsubkegiatan['nm_sub_unit'])
                                        ->where('nm_subkegiatan', $rowsubkegiatan['nm_subkegiatan'])
                                        ->get()
                                        ->getRowArray();
                                    $R4 = $this->realisasilrfk->select('*')
                                        ->where('tahun', $jadwalaktif['tahun'])
                                        ->where('bulan', 'April')
                                        ->where('delete_at=', 0)
                                        ->where('sub_unit', $rowsubkegiatan['nm_sub_unit'])
                                        ->where('nm_subkegiatan', $rowsubkegiatan['nm_subkegiatan'])
                                        ->get()
                                        ->getRowArray();
                                    $R5 = $this->realisasilrfk->select('*')
                                        ->where('tahun', $jadwalaktif['tahun'])
                                        ->where('bulan', 'Mei')
                                        ->where('delete_at=', 0)
                                        ->where('sub_unit', $rowsubkegiatan['nm_sub_unit'])
                                        ->where('nm_subkegiatan', $rowsubkegiatan['nm_subkegiatan'])
                                        ->get()
                                        ->getRowArray();
                                    $R6 = $this->realisasilrfk->select('*')
                                        ->where('tahun', $jadwalaktif['tahun'])
                                        ->where('bulan', 'Juni')
                                        ->where('delete_at=', 0)
                                        ->where('sub_unit', $rowsubkegiatan['nm_sub_unit'])
                                        ->where('nm_subkegiatan', $rowsubkegiatan['nm_subkegiatan'])
                                        ->get()
                                        ->getRowArray();
                                    $R7 = $this->realisasilrfk->select('*')
                                        ->where('tahun', $jadwalaktif['tahun'])
                                        ->where('bulan', 'Juli')
                                        ->where('delete_at=', 0)
                                        ->where('sub_unit', $rowsubkegiatan['nm_sub_unit'])
                                        ->where('nm_subkegiatan', $rowsubkegiatan['nm_subkegiatan'])
                                        ->get()
                                        ->getRowArray();
                                    $R8 = $this->realisasilrfk->select('*')
                                        ->where('tahun', $jadwalaktif['tahun'])
                                        ->where('bulan', 'Agustus')
                                        ->where('delete_at=', 0)
                                        ->where('sub_unit', $rowsubkegiatan['nm_sub_unit'])
                                        ->where('nm_subkegiatan', $rowsubkegiatan['nm_subkegiatan'])
                                        ->get()
                                        ->getRowArray();
                                    $R9 = $this->realisasilrfk->select('*')
                                        ->where('tahun', $jadwalaktif['tahun'])
                                        ->where('bulan', 'September')
                                        ->where('delete_at=', 0)
                                        ->where('sub_unit', $rowsubkegiatan['nm_sub_unit'])
                                        ->where('nm_subkegiatan', $rowsubkegiatan['nm_subkegiatan'])
                                        ->get()
                                        ->getRowArray();
                                    $R10 = $this->realisasilrfk->select('*')
                                        ->where('tahun', $jadwalaktif['tahun'])
                                        ->where('bulan', 'Oktober')
                                        ->where('delete_at=', 0)
                                        ->where('sub_unit', $rowsubkegiatan['nm_sub_unit'])
                                        ->where('nm_subkegiatan', $rowsubkegiatan['nm_subkegiatan'])
                                        ->get()
                                        ->getRowArray();
                                    $R11 = $this->realisasilrfk->select('*')
                                        ->where('tahun', $jadwalaktif['tahun'])
                                        ->where('bulan', 'November')
                                        ->where('delete_at=', 0)
                                        ->where('sub_unit', $rowsubkegiatan['nm_sub_unit'])
                                        ->where('nm_subkegiatan', $rowsubkegiatan['nm_subkegiatan'])
                                        ->get()
                                        ->getRowArray();
                                    $R12 = $this->realisasilrfk->select('*')
                                        ->where('tahun', $jadwalaktif['tahun'])
                                        ->where('bulan', 'Desember')
                                        ->where('delete_at=', 0)
                                        ->where('sub_unit', $rowsubkegiatan['nm_sub_unit'])
                                        ->where('nm_subkegiatan', $rowsubkegiatan['nm_subkegiatan'])
                                        ->get()
                                        ->getRowArray();
                                    $Jan = $R1['pagu_realisasi'] ?? null;
                                    $Feb = $R2['pagu_realisasi'] ?? null;
                                    $Mart = $R3['pagu_realisasi'] ?? null;
                                    $Aprl = $R4['pagu_realisasi'] ?? null;
                                    $Mei = $R5['pagu_realisasi'] ?? null;
                                    $Jun = $R6['pagu_realisasi'] ?? null;
                                    $Jul = $R7['pagu_realisasi'] ?? null;
                                    $Ags = $R8['pagu_realisasi'] ?? null;
                                    $Sept = $R9['pagu_realisasi'] ?? null;
                                    $Okt = $R10['pagu_realisasi'] ?? null;
                                    $Nop = $R11['pagu_realisasi'] ?? null;
                                    $Des = $R12['pagu_realisasi'] ?? null;
                                    $J1 = $Jan;
                                    $J2 = $Feb - $Jan;
                                    if ($J2 <= 0) {
                                        $J2a = $J1;
                                    } else {
                                        $J2a = $J2;
                                    }
                                    $J3 = $Mart - $Feb;
                                    if ($J3 <= 0) {
                                        $J3a = 0;
                                    } else {
                                        $J3a = $J3;
                                    }
                                    $J4 = $Aprl - $Mart;
                                    if ($J4 <= 0) {
                                        $J4a = 0;
                                    } else {
                                        $J4a = $J4;
                                    }
                                    $J5 = $Mei - $Aprl;
                                    if ($J5 <= 0) {
                                        $J5a = 0;
                                    } else {
                                        $J5a = $J5;
                                    }
                                    $J6 = $Jun - $Mei;
                                    if ($J6 <= 0) {
                                        $J6a = 0;
                                    } else {
                                        $J6a = $J6;
                                    }
                                    $J7 = $Jul - $Jun;
                                    if ($J7 <= 0) {
                                        $J7a = 0;
                                    } else {
                                        $J7a = $J7;
                                    }
                                    $J8 = $Ags - $Jul;
                                    if ($J8 <= 0) {
                                        $J8a = 0;
                                    } else {
                                        $J8a = $J8;
                                    }
                                    $J9 = $Sept - $Ags;
                                    if ($J9 <= 0) {
                                        $J9a = 0;
                                    } else {
                                        $J9a = $J9;
                                    }
                                    $J10 = $Okt - $Sept;
                                    if ($J10 <= 0) {
                                        $J10a = 0;
                                    } else {
                                        $J10a = $J10;
                                    }
                                    $J11 = $Nop - $Okt;
                                    if ($J11 <= 0) {
                                        $J11a = 0;
                                    } else {
                                        $J11a = $J11;
                                    }
                                    $J12 = $Des - $Nop;
                                    if ($J12 <= 0) {
                                        $J12a = 0;
                                    } else {
                                        $J12a = $J12;
                                    }
                                    /*
                                        echo "Jumlah" . $J1 . '/' . $J2a . '/' . $J3a . '/'
                                            . $J4a . '/' . $J5a . '/' . $J6a . '/' . $J7a . '/'
                                            . $J8a . '/' . $J9a . '/' . $J10a . '/' . $J11a . '/' . $J12a;
                                        // echo dd($R1);
                                       
                                        echo ' ' .
                                            $Jan . '/' .
                                            $Feb . '/' .
                                            $Mart . '/' .
                                            $Aprl . '/' .
                                            $Mei . '/' .
                                            $Jun . '/' .
                                            $Jul . '/' .
                                            $Ags . '/' .
                                            $Sept . '/' .
                                            $Okt . '/' .
                                            $Nop . '/' .
                                            $Des;
                                            */
                                    $jumlahRperSK =  $J1 + $J2a + $J3a +
                                        $J4a + $J5a + $J6a + $J7a +
                                        $J8a + $J9a + $J10a + $J11a + $J12a;

                                    $jumlahTotalRTotalperSK =
                                        $Jan + $Feb + $Mart +  $Aprl +
                                        $Mei + $Jun + $Jul + $Ags +  $Sept +
                                        $Okt + $Nop +  $Des;
                                    $bln1 = 'Januari';
                                    $isiRbulan1 =  $this->totalrealisasiblnM
                                        ->isiRealisasiperBulan(
                                            $jadwalaktif['tahun'],
                                            $rowsubkegiatan['kd_sub_unit'],
                                            $rowsubkegiatan['nm_sub_unit'],
                                            $rowsubkegiatan['kd_subkegiatan'],
                                            $rowsubkegiatan['nm_subkegiatan'],
                                            $Jan,
                                            'Januari'
                                        );
                                    $isiRbulan2 =  $this->totalrealisasiblnM
                                        ->isiRealisasiperBulan(
                                            $jadwalaktif['tahun'],
                                            $rowsubkegiatan['kd_sub_unit'],
                                            $rowsubkegiatan['nm_sub_unit'],
                                            $rowsubkegiatan['kd_subkegiatan'],
                                            $rowsubkegiatan['nm_subkegiatan'],
                                            $Feb, //$J2a,
                                            'Februari'
                                        );
                                    $isiRbulan3 =  $this->totalrealisasiblnM
                                        ->isiRealisasiperBulan(
                                            $jadwalaktif['tahun'],
                                            $rowsubkegiatan['kd_sub_unit'],
                                            $rowsubkegiatan['nm_sub_unit'],
                                            $rowsubkegiatan['kd_subkegiatan'],
                                            $rowsubkegiatan['nm_subkegiatan'],
                                            $Mart, //$J3a,
                                            'Maret'
                                        );
                                    $isiRbulan4 =  $this->totalrealisasiblnM
                                        ->isiRealisasiperBulan(
                                            $jadwalaktif['tahun'],
                                            $rowsubkegiatan['kd_sub_unit'],
                                            $rowsubkegiatan['nm_sub_unit'],
                                            $rowsubkegiatan['kd_subkegiatan'],
                                            $rowsubkegiatan['nm_subkegiatan'],
                                            $Aprl, //$J2a,
                                            'April'
                                        );
                                    $isiRbulan5 =  $this->totalrealisasiblnM
                                        ->isiRealisasiperBulan(
                                            $jadwalaktif['tahun'],
                                            $rowsubkegiatan['kd_sub_unit'],
                                            $rowsubkegiatan['nm_sub_unit'],
                                            $rowsubkegiatan['kd_subkegiatan'],
                                            $rowsubkegiatan['nm_subkegiatan'],
                                            $Mei, //$J5a,
                                            'Mei'
                                        );
                                    $isiRbulan6 =  $this->totalrealisasiblnM
                                        ->isiRealisasiperBulan(
                                            $jadwalaktif['tahun'],
                                            $rowsubkegiatan['kd_sub_unit'],
                                            $rowsubkegiatan['nm_sub_unit'],
                                            $rowsubkegiatan['kd_subkegiatan'],
                                            $rowsubkegiatan['nm_subkegiatan'],
                                            $Jun, //$J6a,
                                            'Juni'
                                        );
                                    $isiRbulan7 =  $this->totalrealisasiblnM
                                        ->isiRealisasiperBulan(
                                            $jadwalaktif['tahun'],
                                            $rowsubkegiatan['kd_sub_unit'],
                                            $rowsubkegiatan['nm_sub_unit'],
                                            $rowsubkegiatan['kd_subkegiatan'],
                                            $rowsubkegiatan['nm_subkegiatan'],
                                            $Jul, //$J7a,
                                            'Juli'
                                        );
                                    $isiRbulan8 =  $this->totalrealisasiblnM
                                        ->isiRealisasiperBulan(
                                            $jadwalaktif['tahun'],
                                            $rowsubkegiatan['kd_sub_unit'],
                                            $rowsubkegiatan['nm_sub_unit'],
                                            $rowsubkegiatan['kd_subkegiatan'],
                                            $rowsubkegiatan['nm_subkegiatan'],
                                            $Ags, // $J8a,
                                            'Agustus'
                                        );
                                    $isiRbulan9 =  $this->totalrealisasiblnM
                                        ->isiRealisasiperBulan(
                                            $jadwalaktif['tahun'],
                                            $rowsubkegiatan['kd_sub_unit'],
                                            $rowsubkegiatan['nm_sub_unit'],
                                            $rowsubkegiatan['kd_subkegiatan'],
                                            $rowsubkegiatan['nm_subkegiatan'],
                                            $Sept, //$J9a,
                                            'September'
                                        );
                                    $isiRbulan10 =  $this->totalrealisasiblnM
                                        ->isiRealisasiperBulan(
                                            $jadwalaktif['tahun'],
                                            $rowsubkegiatan['kd_sub_unit'],
                                            $rowsubkegiatan['nm_sub_unit'],
                                            $rowsubkegiatan['kd_subkegiatan'],
                                            $rowsubkegiatan['nm_subkegiatan'],
                                            $Okt, // $J10a,
                                            'Oktober'
                                        );
                                    $isiRbulan11 =  $this->totalrealisasiblnM
                                        ->isiRealisasiperBulan(
                                            $jadwalaktif['tahun'],
                                            $rowsubkegiatan['kd_sub_unit'],
                                            $rowsubkegiatan['nm_sub_unit'],
                                            $rowsubkegiatan['kd_subkegiatan'],
                                            $rowsubkegiatan['nm_subkegiatan'],
                                            $Nop, // $J11a,
                                            'November'
                                        );
                                    $isiRbulan12 =  $this->totalrealisasiblnM
                                        ->isiRealisasiperBulan(
                                            $jadwalaktif['tahun'],
                                            $rowsubkegiatan['kd_sub_unit'],
                                            $rowsubkegiatan['nm_sub_unit'],
                                            $rowsubkegiatan['kd_subkegiatan'],
                                            $rowsubkegiatan['nm_subkegiatan'],
                                            $Des, //$J12a,
                                            'Desember'
                                        );
                                    $cektotal =  $this->totalrealisasiM->cek($jadwalaktif['tahun'], $rowsubkegiatan['kd_sub_unit'], $rowsubkegiatan['nm_sub_unit'], $rowsubkegiatan['kd_subkegiatan']);
                                    // echo dd($jadwalaktif['tahun'], $rowsubkegiatan['kd_sub_unit'], $rowsubkegiatan['nm_sub_unit'], $rowsubkegiatan['kd_subkegiatan']);

                                    if ($cektotal == null) {
                                        $dataTR = [
                                            'tahun' => $jadwalaktif['tahun'],
                                            'kd_sub_unit' => $rowsubkegiatan['kd_sub_unit'],
                                            'sub_unit' => $rowsubkegiatan['nm_sub_unit'],
                                            'kd_subkegiatan' => $rowsubkegiatan['kd_subkegiatan'],
                                            'nm_subkegiatan' => $rowsubkegiatan['nm_subkegiatan'],
                                            'total_realisasi' => $jumlahTotalRTotalperSK,
                                        ];

                                        // echo dd($dataTR);
                                        $this->totalrealisasiM->save($dataTR);
                                    } else {
                                        $id = $cektotal['id'];
                                        $dataTR = [
                                            'id' => $id,
                                            'tahun' => $jadwalaktif['tahun'],
                                            'kd_sub_unit' => $rowsubkegiatan['kd_sub_unit'],
                                            'sub_unit' => $rowsubkegiatan['nm_sub_unit'],
                                            'kd_subkegiatan' => $rowsubkegiatan['kd_subkegiatan'],
                                            'nm_subkegiatan' => $rowsubkegiatan['nm_subkegiatan'],
                                            'total_realisasi' => $jumlahTotalRTotalperSK ?? null,
                                        ];
                                        //  echo dd($dataTR);
                                        // echo dd("update");
                                        $this->totalrealisasiM->save($dataTR);
                                    }

                                    echo 'Rp  ' . esc(number_format($jumlahTotalRTotalperSK, 2, ',', '.'));
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
                                    $jumlahRperSK =  $J1 + $J2a + $J3a +
                                        $J4a + $J5a + $J6a + $J7a +
                                        $J8a + $J9a + $J10a + $J11a + $J12a;
                                    $pagu = $rowsubkegiatan['pagu_rincian'];

                                    $persentase = ($jumlahRperSK / $pagu) * 100 ?? NUll;
                                    if ($jumlahRperSK > $pagu) {
                                        $selisih = $jumlahRperSK - $pagu; ?>
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
                                <td style="border: 1px solid #000000; font-size:9px; text-align:left; width:30%;"><?= esc($rowsubkegiatan['label_subkegiatan']); ?></td>
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
                } ?>
        <tr>
            <?php
            if ($bulanpilih == '') {
                $totalReal =  $this->totalrealisasiM->total($jadwalaktif['tahun'], $value['kd_sub_unit'], $rowsubkegiatan['nm_sub_unit']);
                //echo dd($totalReal);
                if ($totalReal == Null) {
            ?>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;">Jumlah Total</td>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;"><?= 'Rp ' . esc(number_format($value['pagu_rincian'], 2, ',', '.')) ?></td>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;"><?= 'Rp ' . esc(number_format(0, 2, ',', '.')) ?></td>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;"></td>

                <?php
                } else {
                ?>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;">Jumlah Total</td>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;"><?= 'Rp ' . esc(number_format($value['pagu_rincian'], 2, ',', '.')) ?></td>

                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;"><?= 'Rp ' . esc(number_format($totalReal['total_realisasi'], 2, ',', '.')) ?></td>
                    <?php
                    $persentase = ($totalReal['total_realisasi'] / $value['pagu_rincian']) * 100 ?? NUll;
                    if ($totalReal['total_realisasi'] > $value['pagu_rincian']) {
                        $selisih = $totalReal['total_realisasi'] - $value['pagu_rincian']; ?>
                        <td> <i class="fas fa-angle-left right text-danger">Total Realisasi melebihi pagu<br> sebesar:<br>
                                <?= esc(number_format($selisih, 2, ',', '.')) ?></i></td>
                        <td style="border: 1px solid #000000; font-size:9px; text-align:left;"></td>

                    <?php } else { ?>
                        <td style="border: 1px solid #000000; font-size:9px; text-align:left;">
                            <?=
                            esc(number_format($persentase, 2, ',', '.')); ?> </td>
                        <td style="border: 1px solid #000000; font-size:9px; text-align:left;"></td>

                    <?php   }
                }
            } else {

                $datarealisasi = $this->realisasilrfk->totalRperBlnOPD($jadwalaktif['tahun'], $bulanpilih, $rowsubkegiatan['nm_sub_unit']);
                //   echo dd($datarealisasi);
                if ($datarealisasi == null) {
                    ?>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;">Jumlah Total</td>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;"><?= 'Rp ' . esc(number_format($value['pagu_rincian'], 2, ',', '.')) ?></td>

                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;"><?= 'Rp ' . esc(number_format(0, 2, ',', '.'))  ?></td>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;">
                        <?=
                        esc(number_format(0, 2, ',', '.')); ?></td>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;"></td>

                <?php
                } else {

                ?>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;">Jumlah Total</td>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;"><?= 'Rp ' . esc(number_format($value['pagu_rincian'], 2, ',', '.')) ?></td>

                    <td style="border: 1px solid #000000; font-size:9px; text-align:left;"><?= 'Rp ' . esc(number_format($datarealisasi['pagu_realisasi'], 2, ',', '.'))  ?></td>
                    <?php

                    $persentase = ($datarealisasi['pagu_realisasi']  / $value['pagu_rincian']) * 100 ?? NUll;
                    if ($datarealisasi['pagu_realisasi']  > $value['pagu_rincian']) {
                        $selisih = $datarealisasi['pagu_realisasi']  - $value['pagu_rincian']; ?>
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