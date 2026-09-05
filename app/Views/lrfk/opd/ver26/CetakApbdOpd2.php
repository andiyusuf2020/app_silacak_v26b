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

    use App\Models\DataApbdModel\RealApbdModel;

    $this->realapbd = new RealApbdModel();

    ?>
    <i style="font-size:12px;text-align:center;">LAPORAN REALISASI FISIK ANGGARAN TA <?= esc($tahunaktif); ?></i>
    <br>
    <i style="font-size:12px;text-align:center;">Perangkat Daerah : <?= esc($datauser['sub_unit']); ?></i>
    <br>
    <?php
    // $this->realisasilrfkrinci = new TaRealisasiRinciModel();
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
        <tbody>
            <tr>
                <th rowspan="2" style="font-size:12px; text-align:center; width:34%;">Kode SubKegiatan/Nama Subkegiatan</th>
                <th colspan="3" style="font-size:12px; text-align:center; width:46%;">Anggaran</th>
                <th colspan="2" style="font-size:12px; text-align:center;width:20%;">
                    Capaian Kinerja belanja(output/fisik)
                </th>
            </tr>
            <tr>
                <th style="font-size:12px; text-align:center; width:14%;">Pagu Anggaran</th>
                <th style="font-size:12px; text-align:center;width:13%;">Pagu Realisasi (SIPD)</th>
                <th style="font-size:12px; text-align:center;width:13%;">Realisasi (SPJ)</th>
                <th style="font-size:12px; text-align:center; width:6%;">% </th>
                <th style="font-size:12px; text-align:center;width:10%;">Capaian %</th>
                <th style="font-size:12px; text-align:center;width:10%;">Kategori</th>
            </tr>
            <?php
            foreach ($program as $key => $value) { ?>
                <tr>
                    <td style="border: 1px solid #000000;font-size:9px; text-align:left;  width:34%; ">
                        <strong><?= esc($value['NAMA_PROGRAM']) ?></strong>
                    </td>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:right;  width:14%;  ">
                        <strong> <?= esc(number_format($value['anggaran'], 0, ',', '.')) ?></strong>
                    </td>

                    <td style="border: 1px solid #000000; font-size:9px; text-align:right;  width:13%;  ">
                        <strong> <?= esc(number_format($value['realisasi'], 0, ',', '.')) ?></strong>
                    </td>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:right;  width:13%;  ">
                        <strong> <?= esc(number_format($value['realisasi_spj'], 0, ',', '.')) ?></strong>
                    </td>
                    <td colspan="2" style="border: 1px solid #000000; font-size:9px; text-align:right;  width:6%;  ">
                        <?php
                        if ($value['anggaran'] == 0) {
                            $capaian = '0';
                        } else {
                            $capaian = ($value['realisasi'] / $value['anggaran']) * 100;
                        }
                        ?>
                        <strong><?= esc(number_format($capaian, 2)) ?></strong>
                    </td>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:center ; width:10%;  ">
                        <?php
                        $datarincireal = $this->realapbd
                            ->select('*')
                            //  ->select('(format((realisasi / pagu_rincian),2)) as isi')
                            // ->select('((realisasi / pagu_rincian)+1) as isi')
                            ->select('((TOTAL_REALISASI / TOTAL_ANGGARAN)) as isi')
                            ->where('TAHUN', $value['TAHUN'])
                            ->where('BULAN', $value['BULAN'])
                            ->where('NAMA_UNIT_SKPD', $value['NAMA_UNIT_SKPD'])
                            ->where('KODE_PROGRAM', $value['KODE_PROGRAM'])
                            ->where('CREATE_AT', $value['CREATE_AT'])
                            ->where('(format((TOTAL_REALISASI / TOTAL_ANGGARAN),2)) >', 0.01)
                            ->where('TOTAL_REALISASI<>', 0)
                            ->get()
                            ->getResultArray();
                        $da = $datarincireal;
                        $mul = 1;
                        foreach ($da as $i => $na)
                            $mul = $i == 0 ? $na['isi'] : $mul * $na['isi'];
                        if (count($da) == 0) {
                            // echo " 0  %";
                            $croopd = 0;
                        } else {
                            $croopd = (pow((float)$mul, 1 / count($da))) * 100;
                            // echo esc(number_format($croopd, 2, ".", ","));
                            if ($croopd < 50) { ?>
                                <strong><span class="badge text-bg-danger"><?= esc(number_format($croopd, 2)) ?>%</span></strong>
                            <?php
                            } elseif ($croopd >= 50 && $croopd < 75) { ?>
                                <strong><span class="badge text-bg-warning"><?= esc(number_format($croopd, 2)) ?>%</span></strong>
                            <?php
                            } elseif ($croopd >= 75 && $croopd < 90) { ?>
                                <strong><span class="badge text-bg-primary"><?= esc(number_format($croopd, 2)) ?>%</span></strong>
                            <?php
                            } else { ?>
                                <strong><span class="badge text-bg-success"><?= esc(number_format($croopd, 2)) ?>%</span></strong>
                        <?php }
                        }
                        ?>
                    </td>
                    <td style="border: 1px solid #000000; width:25%; font-size:9px; text-align:center ; width:10%;  ">
                    </td>
                </tr>
                <?php
                $kegiatan = $this->realapbd->liskegiatan($value['NAMA_UNIT_SKPD'], $value['TAHUN'], $tglaktif, $value['KODE_PROGRAM']);
                foreach ($kegiatan as $key => $valuekegiatan) { ?>
                    <tr>
                        <td style="border: 1px solid #000000;font-size:9px; text-align:left;  width:34%;  ">
                            <strong><?= esc($valuekegiatan['NAMA_GIAT']) ?></strong>
                        </td>
                        <td style="border: 1px solid #000000; font-size:9px; text-align:right;  width:14%; ">
                            <strong><?= esc(number_format($valuekegiatan['anggaran'], 0, ',', '.')) ?></strong>
                        </td>
                        <td style="border: 1px solid #000000; font-size:9px; text-align:right;  width:13%;  ">
                            <strong><?= esc(number_format($valuekegiatan['realisasi'], 0, ',', '.')) ?></strong>
                        </td>
                        <td style="border: 1px solid #000000; font-size:9px; text-align:right;  width:13%;  ">
                            <strong><?= esc(number_format($valuekegiatan['realisasi_spj'], 0, ',', '.')) ?></strong>
                        </td>
                        <td colspan="1" style="border: 1px solid #000000; font-size:9px; text-align:right;  width:6%;  ">
                            <?php
                            if ($valuekegiatan['anggaran'] == 0) {
                                $capaian = '0';
                            } else {
                                $capaian = ($valuekegiatan['realisasi'] / $valuekegiatan['anggaran']) * 100;
                            }
                            ?>
                            <strong><?= esc(number_format($capaian, 2)) ?></strong>
                        </td>
                        <td style="border: 1px solid #000000; font-size:9px; text-align:center ; width: 10%;  ">
                            <?php
                            $datarincireal = $this->realapbd
                                ->select('*')
                                //  ->select('(format((realisasi / pagu_rincian),2)) as isi')
                                // ->select('((realisasi / pagu_rincian)+1) as isi')
                                ->select('((TOTAL_REALISASI / TOTAL_ANGGARAN)) as isi')
                                ->where('TAHUN', $valuekegiatan['TAHUN'])
                                ->where('BULAN', $valuekegiatan['BULAN'])
                                ->where('NAMA_UNIT_SKPD', $valuekegiatan['NAMA_UNIT_SKPD'])
                                ->where('KODE_GIAT', $valuekegiatan['KODE_GIAT'])
                                ->where('CREATE_AT', $valuekegiatan['CREATE_AT'])
                                ->where('(format((TOTAL_REALISASI / TOTAL_ANGGARAN),2)) >', 0.01)
                                ->where('TOTAL_REALISASI<>', 0)
                                ->get()
                                ->getResultArray();
                            $da = $datarincireal;
                            $mul = 1;
                            foreach ($da as $i => $na)
                                $mul = $i == 0 ? $na['isi'] : $mul * $na['isi'];
                            if (count($da) == 0) {
                                // echo " 0  %";
                                $croopd = 0;
                            } else {
                                $croopd = (pow((float)$mul, 1 / count($da))) * 100;
                                // echo esc(number_format($croopd, 2, ".", ","));
                                if ($croopd < 50) { ?>
                                    <strong><span class="badge text-danger"><?= esc(number_format($croopd, 2)) ?>%</span></strong>
                                <?php
                                } elseif ($croopd >= 50 && $croopd < 75) { ?>
                                    <strong><span class="badge text-warning"><?= esc(number_format($croopd, 2)) ?>%</span></strong>
                                <?php
                                } elseif ($croopd >= 75 && $croopd < 90) { ?>
                                    <strong><span class="badge text-primary"><?= esc(number_format($croopd, 2)) ?>%</span></strong>
                                <?php
                                } else { ?>
                                    <strong><span class="badge text-success"><?= esc(number_format($croopd, 2)) ?>%</span></strong>
                            <?php }
                            }
                            ?>
                        </td>
                        <td style="border: 1px solid #000000; width:25%; font-size:9px; text-align:center ; width:10%;  ">
                        </td>
                    </tr>

                    <?php
                    $subgiatan = $this->realapbd->lissubkegiatan($valuekegiatan['NAMA_UNIT_SKPD'], $valuekegiatan['TAHUN'], $tglaktif, $valuekegiatan['KODE_PROGRAM'], $valuekegiatan['KODE_GIAT']);
                    foreach ($subgiatan as $key => $rowsubkegiatan) { ?>
                        <tr>
                            <td style="border: 1px solid #000000; 
                                                     font-size:9px; text-align:left;  width:34%;">
                                <?= esc($rowsubkegiatan['KODE_SUB_GIAT']) ?><br>
                                <?= esc($rowsubkegiatan['NAMA_SUB_GIAT']) ?>
                            </td>
                            <td style="border: 1px solid #000000;                    
                                font-size:9px; text-align:right;  width:14%;">
                                <?= esc(number_format($rowsubkegiatan['anggaran'], 0, ',', '.')) ?>
                            </td>
                            <td style="border: 1px solid #000000; font-size:9px;  text-align:right;  width:13%;">
                                <?= esc(number_format($rowsubkegiatan['realisasi'], 0, ',', '.')) ?>
                            </td>
                            <td style="border: 1px solid #000000; font-size:9px;  text-align:right;  width:13%;">
                                <?= esc(number_format($rowsubkegiatan['realisasi_spj'], 0, ',', '.')) ?>
                            </td>
                            <td colspan="2" style="border: 1px solid #000000; font-size:9px;  text-align:right;  width:6%;">
                                <?php
                                if ($rowsubkegiatan['anggaran'] == 0) {
                                    $capaian = '0';
                                } else {
                                    $capaian = ($rowsubkegiatan['realisasi'] / $rowsubkegiatan['anggaran']) * 100;
                                }
                                ?>
                                <?= esc(number_format($capaian, 2)) ?>
                            </td>

                            <td style="border: 1px solid #000000; font-size:9px; text-align:center ; width:10%">
                                <?php
                                $datarincireal = $this->realapbd
                                    ->select('*')
                                    //  ->select('(format((realisasi / pagu_rincian),2)) as isi')
                                    // ->select('((realisasi / pagu_rincian)+1) as isi')
                                    ->select('((TOTAL_REALISASI / TOTAL_ANGGARAN)) as isi')
                                    ->where('TAHUN', $rowsubkegiatan['TAHUN'])
                                    ->where('BULAN', $rowsubkegiatan['BULAN'])
                                    ->where('NAMA_UNIT_SKPD', $rowsubkegiatan['NAMA_UNIT_SKPD'])
                                    ->where('KODE_SUB_GIAT', $rowsubkegiatan['KODE_SUB_GIAT'])
                                    ->where('CREATE_AT', $rowsubkegiatan['CREATE_AT'])
                                    ->where('(format((TOTAL_REALISASI / TOTAL_ANGGARAN),2)) >', 0.01)
                                    ->where('TOTAL_REALISASI<>', 0)
                                    ->get()
                                    ->getResultArray();
                                $da = $datarincireal;
                                $mul = 1;
                                foreach ($da as $i => $na)
                                    $mul = $i == 0 ? $na['isi'] : $mul * $na['isi'];
                                if (count($da) == 0) {
                                    // echo " 0  %";
                                    $croopd = 0;
                                } else {
                                    $croopd = (pow((float)$mul, 1 / count($da))) * 100;
                                    // echo esc(number_format($croopd, 2, ".", ","));
                                    if ($croopd < 50) { ?>
                                        <span class="badge text-bg-danger"><?= esc(number_format($croopd, 2)) ?>%</span>
                                    <?php
                                    } elseif ($croopd >= 50 && $croopd < 75) { ?>
                                        <span class="badge text-bg-warning"><?= esc(number_format($croopd, 2)) ?>%</span>
                                    <?php
                                    } elseif ($croopd >= 75 && $croopd < 90) { ?>
                                        <span class="badge text-bg-primary"><?= esc(number_format($croopd, 2)) ?>%</span>
                                    <?php
                                    } else { ?>
                                        <span class="badge text-bg-success"><?= esc(number_format($croopd, 2)) ?>%</span>
                                <?php }
                                }

                                ?>

                            </td>
                            <td style="border: 1px solid #000000; width:25%; font-size:9px; text-align:center ; width:10%;">


                            </td>

                        </tr>
                    <?php }

                    ?>
                <?php } ?>
            <?php } ?>

            <tr>
                <td style="border: 1px solid #000000; font-size:9px; text-align:centre;  width:34%;">
                    <strong>
                        JUMLAH
                    </strong>
                </td>
                <td style="border: 1px solid #000000; font-size:9px; text-align:right;  width:14%;">
                    <strong>
                        <?php
                        $jumlahAnggaran = array_sum(array_column($subgiat, 'anggaran'));
                        echo esc(number_format($jumlahAnggaran, 0, ',', '.'));
                        ?>
                    </strong>
                </td>
                <td style="border: 1px solid #000000; font-size:9px; text-align:right;  width:13%;">
                    <strong>
                        <?php
                        $jumlahRealisasi = array_sum(array_column($subgiat, 'realisasi'));
                        echo esc(number_format($jumlahRealisasi, 0, ',', '.'));
                        ?>
                    </strong>
                </td>
                <td style="border: 1px solid #000000; font-size:9px; text-align:right;  width:13%;">
                    <strong>
                        <?php
                        $jumlahRealisasiSPJ = array_sum(array_column($subgiat, 'realisasi_spj'));
                        echo esc(number_format($jumlahRealisasiSPJ, 0, ',', '.'));
                        ?>
                    </strong>
                </td>
                <td style="border: 1px solid #000000; font-size:9px; text-align:right ;  width:6%;">
                    <strong>
                        <?php
                        $capaianTotal = ($jumlahRealisasi / $jumlahAnggaran) * 100;
                        echo esc(number_format($capaianTotal, 2));
                        ?>
                    </strong>
                </td>
                <td style="border: 1px solid #000000; font-size:9px; text-align:center ; width:10%">
                    <strong>
                        <?php
                        $datarincireal2 = $this->realapbd
                            ->select('*')
                            //  ->select('(format((realisasi / pagu_rincian),2)) as isi')
                            // ->select('((realisasi / pagu_rincian)+1) as isi')
                            ->select('((TOTAL_REALISASI / TOTAL_ANGGARAN)) as isi')
                            ->where('TAHUN', $rowsubkegiatan['TAHUN'])
                            ->where('BULAN', $rowsubkegiatan['BULAN'])
                            ->where('NAMA_UNIT_SKPD', $rowsubkegiatan['NAMA_UNIT_SKPD'])
                            ->where('CREATE_AT', $rowsubkegiatan['CREATE_AT'])
                            ->where('(format((TOTAL_REALISASI / TOTAL_ANGGARAN),2)) >', 0.01)
                            ->where('TOTAL_REALISASI<>', 0)
                            ->get()
                            ->getResultArray();
                        $da2 = $datarincireal2;
                        $mul2 = 1;
                        foreach ($da2 as $i => $na2)
                            $mul2 = $i == 0 ? $na2['isi'] : $mul2 * $na2['isi'];
                        if (count($da2) == 0) {
                            // echo " 0  %";
                            $croopd2 = 0;
                        } else {
                            $croopd2 = (pow((float)$mul2, 1 / count($da2))) * 100;
                            // echo esc(number_format($croopd, 2, ".", ","));
                            if ($croopd2 < 50) { ?>
                                <span class="badge text-bg-danger"><?= esc(number_format($croopd2, 2)) ?></span>
                            <?php
                            } elseif ($croopd2 >= 50 && $croopd2 < 75) { ?>
                                <span class="badge text-bg-warning"><?= esc(number_format($croopd2, 2)) ?></span>
                            <?php
                            } elseif ($croopd2 >= 75 && $croopd2 < 90) { ?>
                                <span class="badge text-bg-primary"><?= esc(number_format($croopd2, 2)) ?></span>
                            <?php
                            } else { ?>
                                <span class="badge text-bg-success"><?= esc(number_format($croopd2, 2)) ?></span>
                        <?php }
                        }
                        ?>

                    </strong>
                </td>
                <td style="border: 1px solid #000000; width:25%; font-size:9px; text-align:center ; width:10%;">
                </td>
            </tr>
        </tbody>
    </table>
    <br>
    <div style="font-size:13px;text-align:left;" width:20%>
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