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
    // use App\Models\LrfkProvModel\RealisasiSubKegModel;
    // use App\Models\LrfkProvModel\TotalRealisasiModel;
    // use App\Models\LrfkProvModel\TotalRealisasiBulanModel;
    use App\Models\LrfkProvModel\SubKegModel; // as lrfkModel;
    use App\Models\LrfkProvModel\TaRealisasiRinciModel;

    $this->subkegmodel = new SubKegModel();
    // $this->realisasilrfk = new RealisasiSubKegModel();
    // $this->totalrealisasiM = new TotalRealisasiModel();
    // $this->totalrealisasiblnM = new TotalRealisasiBulanModel();

    ?>
    <i style="font-size:12px;text-align:center;">LAPORAN REALISASI FISIK ANGGARAN TA <?= esc($tahunaktif); ?></i>
    <br>
    <i style="font-size:12px;text-align:center;">Perangkat Daerah : <?= esc($datauser['sub_unit']); ?></i>
    <br>
    <?php
    $this->realisasilrfkrinci = new TaRealisasiRinciModel();
    if ($bulanpilih == '') {
        $bulan = $jadwalaktif['bulan'];
    } else {
        $bulan = $bulanpilih;
        echo 'Realisasi Sampai dengan Bulan :' . esc($bulanpilih);
    }
    ?>
    <br>
    <br>
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="font-size:12px; text-align:center; width:35%;">Program/Kegiatan/Subkegiatan</th>
                <th colspan="3" style="font-size:12px; text-align:center; width:37%;">Anggaran</th>
                <th colspan="2" style="font-size:12px; text-align:center;width:28%;">Capaian Kinerja Anggaran Berdasarkan Belanja per-Sub Kegiatan</th>
            </tr>
            <tr>
                <th style="font-size:12px; text-align:center; width:15%;">Pagu Anggaran</th>
                <th style="font-size:12px; text-align:center;width:15%;">Pagu Realisasi</th>
                <th style="font-size:12px; text-align:center; width:7%;">% </th>
                <th style="font-size:12px; text-align:center;width:13%;">Capaian %</th>
                <th style="font-size:12px; text-align:center;width:15%;">Kategori Kinerja</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($listapbdopd as $key => $value) { ?>
                <tr>
                    <td style="border: 1px solid #000000; background-color: azure; 
                    font-size:9px; text-align:left;  width:35%;">
                        <?= esc($value['nm_urusan']) ?>
                    </td>
                    <td style="border: 1px solid #000000; 
                    background-color: azure; font-size:9px; text-align:right;  width:15%;">
                        <?= esc(number_format($value['pagu_rincian'], 0, ',', '.')) ?>
                    </td>
                    <td style="border: 1px solid #000000; font-size:9px; 
                   background-color: azure; text-align:right;  width:15%;">
                        <?php
                        // ->Data Kegiatan APBD
                        $datarealisasiU = $this->realisasilrfkrinci->select('*')
                            ->selectSum('realisasi')
                            ->selectSum('pagu_rincian')
                            ->where('tahun', $tahunaktif)
                            ->where('bulan', $bulan)
                            ->where('kd_sub_unit', $value['kd_sub_unit'])
                            ->where('kd_urusan', $value['kd_urusan'])
                            ->groupBy('kd_urusan')
                            ->where('delete_at=', 0)
                            ->get()
                            ->getRowArray();
                        //echo dd($datarealisasi);
                        $paguU = $value['pagu_rincian'];
                        $realU = $datarealisasiU['realisasi'] ?? null;
                        $persentaseU = ($realU / $paguU) * 100 ?? NUll;

                        //echo $pagu . '///' . $real;
                        if ($realU > $paguU) {
                            echo "Realisasi bulan ini melebihi pagu"; //. $real;
                        } else {
                            echo esc(number_format($realU, 2, ',', '.')); //$realU . '> ' . $paguU . 
                        } ?>
                    </td>
                    <td colspan="2" style="border: 1px solid #000000; font-size:9px; 
                   background-color: azure; text-align:right;  width:7%;">
                        <?php
                        if (!$datarealisasiU) {
                            echo esc('0');
                        } else {
                            echo esc(number_format($persentaseU, 3, ',', '.'));
                        }
                        ?>
                    </td>

                    <td style="background-color: azure; border: 1px solid #000000; font-size:9px; text-align:center ; width:13%">
                    </td>
                    <td style="background-color: azure; border: 1px solid #000000; width:15%; font-size:9px; text-align:center ; width:15%;">
                    </td>
                </tr>
                <?php
                $dataprogram = $this->subkegmodel->select('*')
                    ->selectSUM('pagu_rincian')->where('pagu_rincian<>', '0')
                    ->where('kd_sub_unit', $value['kd_sub_unit'])
                    ->where('kd_urusan', $value['kd_urusan'])
                    ->where('tahun', $tahunaktif)
                    ->groupBy('kd_program')
                    ->get()
                    ->getResultArray();
                // echo dd($dataprogram);
                foreach ($dataprogram as $key => $rowprogram) { ?>
                    <tr>
                        <td style="border: 1px solid #000000; background-color: bisque;                     font-size:9px; text-align:left;  width:35%;">
                            <?= esc($rowprogram['nm_program']) ?>
                        </td>
                        <td style="border: 1px solid #000000;                     background-color: bisque; font-size:9px; text-align:right;  width:15%;">
                            <?= esc(number_format($rowprogram['pagu_rincian'], 0, ',', '.')) ?>
                        </td>
                        <td style="border: 1px solid #000000; font-size:9px;                    background-color: bisque; text-align:right;  width:15%;">
                            <?php
                            // // ->Data Realisasi Program
                            $datarealisasiP = $this->realisasilrfkrinci->select('*')
                                ->selectSum('realisasi')
                                ->selectSum('pagu_rincian')
                                ->where('tahun', $tahunaktif)
                                ->where('bulan', $bulan)
                                ->where('kd_sub_unit', $value['kd_sub_unit'])
                                ->where('kd_urusan', $value['kd_urusan'])
                                ->where('kd_program', $rowprogram['kd_program'])
                                ->groupBy('kd_program')
                                // ->groupBy('kd_kegiatan')
                                ->where('delete_at=', 0)
                                ->get()
                                ->getRowArray();
                            // echo dd($datarealisasiP);
                            if (!$datarealisasiP) {
                                echo esc('Rp0');
                            } else {

                                $paguP = $rowprogram['pagu_rincian'];
                                $realP = $datarealisasiP['realisasi'] ?? null;
                                $persentaseP = ($realP / $paguP) * 100 ?? NUll;
                                //echo $pagu . '///' . $real;
                                if ($realP > $paguP) {
                                    echo "Realisasi bulan ini melebihi pagu//" . $real;
                                } else {
                                    echo  esc(number_format($realP, 0, ',', '.'));
                                }
                            }

                            ?>
                        </td>
                        <td colspan="2" style="border: 1px solid #000000; font-size:9px;                    
                        background-color: bisque; text-align:right;  width:7%;">
                            <?php
                            if (!$datarealisasiP) {
                                echo esc('0');
                            } else {
                                echo esc(number_format($persentaseP, 3, ',', '.'));
                            }
                            ?>
                        </td>

                        <td style="background-color: bisque; border: 1px solid #000000; font-size:9px; text-align:center ; width:13%">
                        </td>
                        <td style="background-color: bisque; border: 1px solid #000000; font-size:9px; text-align:center ; width:15%;">
                        </td>
                    </tr>
                    <?php
                    $datakegiatan = $this->subkegmodel->select('*')
                        ->selectSUM('pagu_rincian')->where('pagu_rincian<>', '0')
                        ->where('kd_sub_unit', $value['kd_sub_unit'])
                        ->where('kd_urusan', $value['kd_urusan'])
                        ->where('kd_program', $rowprogram['kd_program'])
                        ->where('tahun', $tahunaktif)
                        ->groupBy('kd_kegiatan')
                        ->get()
                        ->getResultArray();
                    // echo dd($datakegiatan);
                    foreach ($datakegiatan as $key => $rowkegiatan) { ?>
                        <tr>
                            <td style="border: 1px solid #000000; background-color: antiquewhite;                     font-size:9px; text-align:left;  width:35%;">
                                <?= esc($rowkegiatan['kd_kegiatan']) ?>
                                <?= esc($rowkegiatan['nm_kegiatan']) ?>
                            </td>
                            <td style="border: 1px solid #000000;                     background-color: antiquewhite; font-size:9px; text-align:right;  width:15%;">
                                <?= esc(number_format($rowkegiatan['pagu_rincian'], 0, ',', '.')) ?>
                            </td>
                            <td style="border: 1px solid #000000; font-size:9px;                    background-color: antiquewhite; text-align:right;  width:15%;">
                                <?php
                                // // ->Data Realisasi Kegiatan
                                $datarealisasiK = $this->realisasilrfkrinci->select('*')
                                    ->selectSum('realisasi')
                                    //->selectSum('pagu_rincian')
                                    ->where('tahun', $tahunaktif)
                                    ->where('bulan', $bulan)
                                    ->where('kd_sub_unit', $value['kd_sub_unit'])
                                    ->where('kd_urusan', $value['kd_urusan'])
                                    ->where('kd_program', $rowprogram['kd_program'])
                                    ->where('kd_kegiatan', $rowkegiatan['kd_kegiatan'])
                                    ->groupBy('kd_kegiatan')
                                    ->where('delete_at=', 0)
                                    ->get()
                                    ->getRowArray();
                                // echo dd($datarealisasiK);
                                if (!$datarealisasiK) {
                                    echo esc('Rp0');
                                } else {

                                    $paguK = $rowkegiatan['pagu_rincian'];
                                    $realK = $datarealisasiK['realisasi'] ?? null;
                                    $persentaseK = ($realK / $paguK) * 100 ?? NUll;
                                    //echo $pagu . '///' . $real;
                                    if ($realK > $paguK) {
                                        echo "Realisasi bulan ini melebihi pagu";
                                    } else {
                                        echo  esc(number_format($realK, 0, ',', '.'));
                                    }
                                }
                                ?>
                            </td>
                            <td colspan="2" style="border: 1px solid #000000; font-size:9px;                   
                             background-color: antiquewhite; text-align:right;  width:7%;">
                                <?php
                                if (!$datarealisasiK) {
                                    echo esc('0');
                                } else {
                                    echo esc(number_format($persentaseK, 3, ',', '.'));
                                }
                                ?> </td>

                            <td style="background-color: antiquewhite; border: 1px solid #000000; font-size:9px; text-align:center ; width:13%">
                            </td>
                            <td style="background-color: antiquewhite; border: 1px solid #000000; font-size:9px; text-align:center ; width:15%;">
                            </td>
                        </tr>
                        <?php
                        $datasubkegiatan = $this->subkegmodel->select('*')
                            ->selectSUM('pagu_rincian')->where('pagu_rincian<>', '0')
                            ->where('kd_sub_unit', $value['kd_sub_unit'])
                            ->where('kd_urusan', $value['kd_urusan'])
                            ->where('kd_program', $rowprogram['kd_program'])
                            ->where('kd_kegiatan', $rowkegiatan['kd_kegiatan'])
                            ->where('tahun', $tahunaktif)
                            ->groupBy('kd_subkegiatan')
                            ->get()
                            ->getResultArray();
                        // echo dd($datakegiatan);
                        foreach ($datasubkegiatan as $key => $rowsubkegiatan) { ?>
                            <tr>
                                <td style="border: 1px solid #000000; 
                                                     font-size:9px; text-align:left;  width:35%;">
                                    <?= esc($rowsubkegiatan['kd_subkegiatan']) ?>
                                    <?= esc($rowsubkegiatan['nm_subkegiatan']) ?>
                                </td>
                                <td style="border: 1px solid #000000;                    
                                font-size:9px; text-align:right;  width:15%;">
                                    <?= esc(number_format($rowsubkegiatan['pagu_rincian'], 0, ',', '.')) ?>
                                </td>
                                <td style="border: 1px solid #000000; font-size:9px;  text-align:right;  width:15%;">
                                    <?php
                                    // // ->Data Realisasi Kegiatan
                                    $datarealisasiSK = $this->realisasilrfkrinci->select('*')
                                        ->selectSum('realisasi')
                                        //->selectSum('pagu_rincian')
                                        ->where('tahun', $tahunaktif)
                                        ->where('bulan', $bulan)
                                        ->where('kd_sub_unit', $value['kd_sub_unit'])
                                        ->where('kd_urusan', $value['kd_urusan'])
                                        ->where('kd_program', $rowprogram['kd_program'])
                                        ->where('kd_kegiatan', $rowkegiatan['kd_kegiatan'])
                                        ->where('kd_subkegiatan', $rowsubkegiatan['kd_subkegiatan'])
                                        ->groupBy('kd_subkegiatan')
                                        ->where('delete_at=', 0)
                                        ->get()
                                        ->getRowArray();
                                    // echo dd($datarealisasiK);
                                    if (!$datarealisasiSK) {
                                        echo esc('Rp0');
                                    } else {

                                        $paguSK = $rowsubkegiatan['pagu_rincian'];
                                        $realSK = $datarealisasiSK['realisasi'] ?? null;
                                        $persentaseSK = ($realSK / $paguSK) * 100 ?? NUll;
                                        //echo $pagu . '///' . $real;
                                        if ($realSK > $paguSK) {
                                            echo "Realisasi bulan ini melebihi pagu";
                                        } else {
                                            echo esc(number_format($realSK, 0, ',', '.'));
                                        }
                                    }
                                    ?> </td>
                                <td colspan="2" style="border: 1px solid #000000; font-size:9px;  text-align:right;  width:7%;">
                                    <?php
                                    if (!$datarealisasiSK) {
                                        echo esc('0');
                                    } else {
                                        echo esc(number_format($persentaseSK, 2, ',', '.'));
                                    }
                                    ?>
                                </td>

                                <td style="border: 1px solid #000000; font-size:9px; text-align:center ; width:13%">
                                    <?php
                                    $datarincireal = $this->realisasilrfkrinci
                                        ->select('*')
                                        //  ->select('(format((realisasi / pagu_rincian),2)) as isi')
                                        ->select('((realisasi / pagu_rincian)) as isi')
                                        ->where('tahun', $tahunaktif)
                                        ->where('bulan', $bulan)
                                        ->where('kd_sub_unit', $value['kd_sub_unit'])
                                        ->where('kd_urusan', $value['kd_urusan'])
                                        ->where('kd_program', $rowprogram['kd_program'])
                                        ->where('kd_kegiatan', $rowkegiatan['kd_kegiatan'])
                                        ->where('kd_subkegiatan', $rowsubkegiatan['kd_subkegiatan'])
                                        ->where('(format((realisasi / pagu_rincian),2)) >', 0.01)
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
                                        echo " 0 "; ?>
                                    <?php } else {
                                        $croopd = (pow((float)$mul, 1 / count($da))) * 100;
                                        //$croopd = ((pow((float)$mul, 1 / count($da)))) * 100;
                                        echo esc(number_format($croopd, 2, ".", ","));
                                    }
                                    // foreach ($datarincireal as $key => $rowX) {
                                    //     // echo '//' . $rowX['isi'] . '//';
                                    // }
                                    ?>
                                </td>
                                <td style="border: 1px solid #000000; width:25%; font-size:9px; text-align:center ; width:15%;">
                                    <?php
                                    if (count($da) == 0) {
                                        echo esc('0');
                                    } else {
                                        echo esc('Berkinerja Baik');
                                    }
                                    ?>

                                </td>

                            </tr>
            <?php }
                    }
                }
            } ?>
            <tr>
                <td style="border: 1px solid #000000; font-size:9px; text-align:centre;  width:35%;">
                    JUMLAH
                </td>
                <td style="border: 1px solid #000000; font-size:9px; text-align:right;  width:15%;">
                    <strong><?= esc(number_format($totpaguopd, 0, ',', '.')) ?></strong>
                </td>
                <td style="border: 1px solid #000000; font-size:9px; text-align:right;  width:15%;">
                    <strong>
                        <?php
                        $datarealisasitotopd = $this->realisasilrfkrinci->selectSum('realisasi')
                            ->where('kd_sub_unit', $value['kd_sub_unit'])
                            ->where('tahun', $tahunaktif)
                            ->where('bulan', $bulan)
                            ->where('delete_at=', 0)
                            ->groupBy('kd_sub_unit')
                            ->get()
                            ->getRowArray();

                        if (!$datarealisasitotopd) {
                            echo esc('0');
                        } else {
                            $paguTot = $totpaguopd;
                            $realTot = $datarealisasitotopd['realisasi'] ?? null;
                            $persentaseTot = ($realTot / $paguTot) * 100 ?? NUll;
                            //echo $pagu . '///' . $real;
                            if ($realTot > $paguTot) {
                                echo "Realisasi bulan ini melebihi pagu";
                            } else {
                                echo esc(number_format($realTot, 0, ',', '.'));
                            }
                        }
                        ?>
                    </strong>
                </td>
                <td style="border: 1px solid #000000; font-size:9px; text-align:right ;  width:7%;">
                    <strong>
                        <?php
                        if (!$datarealisasitotopd) {
                            echo esc('0');
                        } else {
                            echo esc(number_format($persentaseTot, 3, ',', '.'));
                        } ?>%
                    </strong>
                </td>
                <td style="border: 1px solid #000000; font-size:9px; text-align:center ; width:13%">
                    <?php

                    $datarincirealBel = $this->realisasilrfkrinci
                        ->selectCount('kd_rek_belanja')
                        ->where('tahun', $tahunaktif)
                        ->where('bulan', $bulan)
                        ->where('kd_sub_unit', $value['kd_sub_unit'])
                        ->where('realisasi<>', 0)
                        ->get()
                        ->getRowArray();
                    // echo dd($datarincirealBel);
                    $belT = $totBelOpd;
                    $Rbel = $datarincirealBel['kd_rek_belanja'] ?? null;
                    $persentaseBel = ($Rbel / $belT) * 100 ?? NUll;
                    echo esc(number_format($persentaseBel, 3, ',', '.'));
                    ?>

                </td>
                <td style="border: 1px solid #000000; width:25%; font-size:9px; text-align:center ; width:15%;">
                </td>
            </tr>
        </tbody>
    </table>
    <p>
    <div style="font-size:13px;text-align:centre;" width:20%>
        <table width:20%>
            <tr>
                <td width:20%>Mengetahui</td>
            </tr>
            <tr>
                <td width:20%><?= esc($datauser['jabatan']) ?></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td><u><?= esc($datauser['nama']) ?></u></td>
            </tr>
            <tr>
                <td><?= esc($datauser['nip']) ?></td>
            </tr>
        </table>
    </div>
</body>

</html>