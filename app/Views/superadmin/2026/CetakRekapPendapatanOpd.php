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
    <i style="font-size:12px;text-align:center;">Perangkat Daerah di Lingkup Pemerintah Provinsi Lampung</i>
    <br>
    Sampai Dengan <?= esc($tglaktif) ?>
    <br>
    <table>
        <tbody>
            <tr>
                <th rowspan="2" style="font-size:12px; text-align:center; width:35%;">Kode SubKegiatan/Nama Subkegiatan</th>
                <th colspan="3" style="font-size:12px; text-align:center; width:37%;">Anggaran</th>
                <th colspan="2" style="font-size:12px; text-align:center;width:28%;">
                    Capaian Kinerja belanja(output/fisik)
                </th>
            </tr>
            <tr>
                <th style="font-size:12px; text-align:center; width:15%;">Pagu Anggaran</th>
                <th style="font-size:12px; text-align:center;width:15%;">Pagu Realisasi</th>
                <th style="font-size:12px; text-align:center; width:7%;">% </th>
                <th style="font-size:12px; text-align:center;width:13%;">Capaian %</th>
                <th style="font-size:12px; text-align:center;width:15%;">Kategori</th>
            </tr>
            <?php
            foreach ($dataopd as $key => $rowsubkegiatan) { ?>
                <tr>
                    <td style="border: 1px solid #000000; 
                                                     font-size:9px; text-align:left;  width:35%;">
                        <?= esc($rowsubkegiatan['KODE_UNIT_SKPD']) ?><br>
                        <?= esc($rowsubkegiatan['NAMA_UNIT_SKPD']) ?>
                    </td>
                    <td style="border: 1px solid #000000;                    
                                font-size:9px; text-align:right;  width:15%;">
                        <?= esc(number_format($rowsubkegiatan['anggaran'], 0, ',', '.')) ?>
                    </td>
                    <td style="border: 1px solid #000000; font-size:9px;  text-align:right;  width:15%;">
                        <?= esc(number_format($rowsubkegiatan['realisasi'], 0, ',', '.')) ?>
                    </td>
                    <td colspan="2" style="border: 1px solid #000000; font-size:9px;  text-align:right;  width:7%;">
                        <?php
                        if ($rowsubkegiatan['anggaran'] == 0) {
                            $capaian = '0';
                        } else {
                            $capaian = ($rowsubkegiatan['realisasi'] / $rowsubkegiatan['anggaran']) * 100;
                        }
                        ?>
                        <?= esc(number_format($capaian, 2)) ?>
                    </td>

                    <td style="border: 1px solid #000000; font-size:9px; text-align:center ; width:13%">
                        <?php
                        $datarincireal = $this->realapbd
                            ->select('*')
                            //  ->select('(format((realisasi / pagu_rincian),2)) as isi')
                            // ->select('((realisasi / pagu_rincian)+1) as isi')
                            ->select('((TOTAL_REALISASI / TOTAL_ANGGARAN)) as isi')
                            ->where('TAHUN', $rowsubkegiatan['TAHUN'])
                            ->where('BULAN', $rowsubkegiatan['BULAN'])
                            ->where('NAMA_UNIT_SKPD', $rowsubkegiatan['NAMA_UNIT_SKPD'])
                            // ->where('KODE_SUB_GIAT', $rowsubkegiatan['KODE_SUB_GIAT'])
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
                    <td style="border: 1px solid #000000; width:25%; font-size:9px; text-align:center ; width:15%;">


                    </td>

                </tr>
            <?php }

            ?>
            <tr>
                <td style="border: 1px solid #000000; font-size:9px; text-align:centre;  width:35%;">
                    <strong>
                        JUMLAH
                    </strong>
                </td>
                <td style="border: 1px solid #000000; font-size:9px; text-align:right;  width:15%;">
                    <strong>
                        <?php
                        $jumlahAnggaran = array_sum(array_column($dataopd, 'anggaran'));
                        echo esc(number_format($jumlahAnggaran, 0, ',', '.'));
                        ?>
                    </strong>
                </td>
                <td style="border: 1px solid #000000; font-size:9px; text-align:right;  width:15%;">
                    <strong>
                        <?php
                        $jumlahRealisasi = array_sum(array_column($dataopd, 'realisasi'));
                        echo esc(number_format($jumlahRealisasi, 0, ',', '.'));
                        ?>
                    </strong>
                </td>
                <td style="border: 1px solid #000000; font-size:9px; text-align:right ;  width:7%;">
                    <strong>
                        <?php
                        $capaianTotal = ($jumlahRealisasi / $jumlahAnggaran) * 100;
                        echo esc(number_format($capaianTotal, 2)) . '%';
                        ?>
                    </strong>
                </td>
                <td style="border: 1px solid #000000; font-size:9px; text-align:center ; width:13%">
                    <strong>
                        <?php
                        $datarincireal2 = $this->realapbd
                            ->select('*')
                            //  ->select('(format((realisasi / pagu_rincian),2)) as isi')
                            // ->select('((realisasi / pagu_rincian)+1) as isi')
                            ->select('((TOTAL_REALISASI / TOTAL_ANGGARAN)) as isi')
                            ->where('TAHUN', $rowsubkegiatan['TAHUN'])
                            ->where('BULAN', $rowsubkegiatan['BULAN'])
                            // ->where('NAMA_UNIT_SKPD', $rowsubkegiatan['NAMA_UNIT_SKPD'])
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
                <td style="border: 1px solid #000000; width:25%; font-size:9px; text-align:center ; width:15%;">
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>