<html>

<head>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        th {
            border: 1px solid #000000;
            text-align: center;

            /* align: center; */
            /* height: 15px; */
            /* margin: 1px; */
        }

        td {
            border: 1px solid #000000;

        }
    </style>
</head>

<body>
    <i style="font-size:12px;text-align:center;">LAPORAN REALISASI FISIK ANGGARAN TA <?= esc($tahun); ?></i>
    <br>
    <i style="font-size:12px;text-align:center;">Sampai dengan Bulan : <?= esc($bulan); ?></i>
    <br>
    <br>

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="font-size:12px; width:3%;">No</th>
                <th rowspan="2" style="font-size:12px; width:15%;">Kode Perangkat Daerah</th>
                <th rowspan="2" style="font-size:12px; width:30%;">Nama Perangkat Daerah</th>
                <th colspan="3" style="font-size:12px; width:37%;">Anggaran (Rp)</th>
                <th colspan="2" style="font-size:12px; width:15%;">Capaian Kinerja Anggaran berdasarkan belanja</th>
            </tr>
            <tr>
                <th style="font-size:12px; width:15%; ">Pagu Anggaran</th>
                <th style="font-size:12px; width:15%;">Pagu Realisasi</th>
                <th style="font-size:12px; width:7%;">% </th>
                <!-- <th style="font-size:12px; text-align:center;width:10%;">Capaian %</th> -->
                <th style="font-size:12px; width:15%;">%</th>
            </tr>

        </thead>
        <?php

        use App\Models\LrfkProvModel\SubKegModel; // as lrfkModel;
        use App\Models\LrfkProvModel\TaRealisasiRinciModel;
        use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;
        use PHPUnit\Framework\Constraint\Count;

        $this->subkegmodel = new SubKegModel();
        $this->realisasilrfkrinci = new TaRealisasiRinciModel();
        ?>
        <tbody>
            <?php foreach ($datarekaplrfk as $key => $datalrfk) { ?>
                <tr>
                    <td style="font-size:9px; text-align:center; width:3%;"><?= esc($key + 1) ?></td>
                    <td style="font-size:9px; text-align:left; width:15%;"><?= esc($datalrfk['kd_sub_unit'])  ?></td>
                    <td style="font-size:9px; text-align:left; width:30%;"><?= esc($datalrfk['nm_sub_unit'])  ?></td>
                    <td style="font-size:9px; text-align:right; width:15%;">
                        <?php
                        $totpagu = $this->subkegmodel->totpaguopd2($datalrfk['nm_sub_unit']);
                        echo esc(number_format($totpagu['pagu_rincian'], 0, ',', '.'));
                        ?>
                    </td>
                    <td style="font-size:9px; text-align:right;width:15%;">
                        <?= esc(number_format($datalrfk['realisasi'], 0, ',', '.')) ?>

                    </td>
                    <td style="font-size:9px; text-align:center; width:7%;">
                        <?php
                        $persen = $datalrfk['realisasi'] / $totpagu['pagu_rincian'] * 100;
                        echo esc(number_format($persen, 2, ',', '.'));
                        ?>
                    </td>
                    <td style="font-size:9px; text-align:center;width:15%;">
                        <?php
                        $datarincireal = $this->realisasilrfkrinci
                            ->select('*')
                            //  ->select('(format((realisasi / pagu_rincian),2)) as isi')
                            ->select('((realisasi / pagu_rincian)) as isi')
                            ->where('tahun', $datalrfk['tahun'])
                            ->where('bulan', $datalrfk['bulan'])
                            ->where('kd_sub_unit', $datalrfk['kd_sub_unit'])
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
                    <!-- <td style="font-size:9px; text-align:center;width:15%;">
                        <?php
                        if ($croopd < $persen) {
                            // echo esc('Berkinerja Baik');
                        }
                        if ($croopd > $persen) {
                            // echo esc('Berkinerja Sangat Baik');
                        }
                        ?>
                    </td> -->

                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="font-size:12px; text-align:center;width:48%;"><strong>Jumlah Total</strong></td>
                <td style="font-size:12px; text-align:center;width:15%;">
                    <strong><?= esc(number_format($totapbd['pagu_rincian'], 0, ',', '.')) ?></strong>
                </td>
                <td style="font-size:12px; text-align:center;width:15%;"><strong><?= esc(number_format($datatotalpagu['realisasi'], 0, ',', '.')) ?></strong></td>
                <td style="font-size:12px; text-align:center;width:7%;"><strong>
                        <?php
                        $persentotal = $datatotalpagu['realisasi'] / $totapbd['pagu_rincian'] * 100;
                        ?>
                        <?= esc(number_format($persentotal, 2, ',', '.')) ?>
                    </strong>
                </td>
                <!-- <td style="font-size:12px; text-align:center;width:10%;"></td> -->
                <td style="font-size:12px; text-align:center;width:15%;">
                    <?php
                    $Tdatarincireal = $this->realisasilrfkrinci
                        // ->select('*')
                        //  ->select('(format((realisasi / pagu_rincian),2)) as isi')
                        ->select('((realisasi / pagu_rincian)) as isi')
                        ->where('tahun', $datalrfk['tahun'])
                        ->where('bulan', $datalrfk['bulan'])
                        // ->where('kd_sub_unit', $datalrfk['kd_sub_unit'])
                        ->where('(format((realisasi / pagu_rincian),2)) >', 0.01)
                        ->where('realisasi<>', 0)
                        ->get()
                        ->getResultArray();
                    echo dd($croopd);

                    $Tda = $Tdatarincireal;
                    $Tmul = 1;
                    foreach ($Tda as $i => $Tna)
                        // $d = 1 + $na['isi'];
                        $Tmul = $i == 0 ? $Tna['isi'] : $Tmul * $Tna['isi'];
                    // $mul = $i == 0 ? $d : $mul * $d;
                    if (count($Tda) == 0) {
                        echo " 0 "; ?>
                    <?php } else {
                        $Tcroopd = (pow((float)$Tmul, 1 / count($Tda))) * 100;
                        //$croopd = ((pow((float)$mul, 1 / count($da)))) * 100;
                        echo esc(number_format($Tcroopd, 2, ".", ","));
                    }

                    ?>
                </td>
            </tr>
        </tfoot>
    </table>
</body>

</html>