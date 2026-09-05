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
            height: 15px;
            margin: 1px;
        }

        /* td {
            border: 1px solid #000000;
            text-align: left;
            height: 15px;
            margin: 1px;
        } */
    </style>
</head>

<body>
    <?php

    use App\Models\CapkinModel\TaKegPokokCapkinModel;

    $this->kegpokokmodal = new TaKegPokokCapkinModel();

    use App\Models\CapkinModel\TaRealisasiKegPokokModel;

    $this->rkegpokokmodal = new TaRealisasiKegPokokModel();

    use App\Models\CapkinModel\TaRKegPokokCapkinModel;

    $this->rdkegpokokmodal = new TaRKegPokokCapkinModel();

    use App\Models\LrfkProvModel\TaRealisasiRinciModel;

    $this->realisasilrfkrinci = new TaRealisasiRinciModel();
    ?>
    <h2 style="font-size:12px; text-align:center;">LAPORAN KINERJA PROGRAM UNGGULAN PEMBANGUNAN DAERAH TA
        <?= esc($tahun) ?> <?= esc($twaktif['tw']) ?> <br><?= esc($datauser['sub_unit']) ?></h2><br>

    <br>
    <table cellpadding="4" style="text-align:center">
        <thead>

        </thead>
        <tbody>
            <tr>
                <th style="font-size:9px; text-align:center; width:4%" rowspan="2"><strong>#</strong></th>
                <th style="font-size:9px; text-align:center; width:10%" rowspan="2"><strong>
                        PROGRAM UNGGULAN PEMBANGUNAN YANG DIDUKUNG
                    </strong></th>
                <th style="font-size:9px; text-align:left; width:12%" rowspan="2"><strong>PROGRAM/ KEGIATAN/ SUBKEGIATAN dan <br> ANGGARAN <br>(pagu,realisasi) </strong></th>
                <th style="font-size:9px; text-align:center; width:26%" colspan="3"><strong>CAPAIAN KINERJA</strong></th>
                <th style="font-size:9px; text-align:center; width:22%" rowspan="2"><strong>URAIAN TARGET PELAKSANAAN AKTIVITAS/KEGIATAN dan HASIL YANG AKAN DICAPAI TAHUN <?= esc($tahun) ?></strong></th>
                <th style="font-size:9px; text-align:center; width:26%" rowspan="2"><strong>DESKRIPSI PROGRES REALISASI AKTIVITAS/KEGIATAN POKOK dan LOKASI KEGIATAN s.d <?= esc($bulantw) ?></strong></th>
            </tr>
            <tr>
                <th style="font-size:9px; text-align:center; width:10%"><strong>Target Aktivitas/ Kegiatan Pokok Tahun <?= esc($tahun) ?></strong></th>
                <th style="font-size:9px; text-align:center; width:10%"><strong>Realisasi Aktivitas/Kegiatan s.d <?= esc($bulantw) ?> </strong></th>
                <th style="font-size:9px; text-align:center; width:6%"><strong>Capaian (%)</strong></th>
            </tr>

            <?php
            foreach ($progunggulan as $key => $dataprogunggul) { ?>
                <tr>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:right; width:4%;">-</td>
                    <td style="border: 1px solid #000000; font-size:12px; text-align:left; width:96%;">
                        <strong><?= esc('Program Unggulan : ' . $dataprogunggul['nm_progunggulan']) ?></strong>
                        <i style="font-size: 9px;">
                            <?= esc(',dengan dukungan Program,Kegiatan,Subkegiatan dan Kegiatan Pokok/Aktifitas') ?>
                            <?= esc(' yang dilaksanakan sebagai berikut:') ?></i>
                    </td>
                </tr>
                <?php
                $datasubkeg = $this->kegpokokmodal
                    ->select('ta_kegpokok_capkin_apbd2.*')
                    ->select('ta_subkeg_capkin_apbd.nm_subkegiatan,nm_kegiatan,nm_program')
                    ->join('ta_subkeg_capkin_apbd', 'ta_subkeg_capkin_apbd.id_sk=ta_kegpokok_capkin_apbd2.id_targetsubkeg')
                    ->join('ta_mprog_unggulan', 'ta_mprog_unggulan.id_pung=ta_kegpokok_capkin_apbd2.id_progunggulan')
                    ->where('ta_kegpokok_capkin_apbd2.kd_subunit', $dataprogunggul['kd_subunit'])
                    ->where('ta_kegpokok_capkin_apbd2.tahun', $dataprogunggul['tahun'])
                    ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
                    ->where('ta_kegpokok_capkin_apbd2.id_progunggulan', $dataprogunggul['id_progunggulan'])
                    ->groupBy('ta_kegpokok_capkin_apbd2.kd_subkegiatan')
                    ->get()
                    ->getResultArray();
                // echo dd($datasubkeg);
                foreach ($datasubkeg as $key => $subkeg) { ?>
                    <tr>
                        <td style="border: 1px solid #000000; font-size:9px; text-align:right; width:4%;">-</td>
                        <td style="border: 1px solid #000000; font-size:9px; text-align:right; width:10%;"></td>
                        <td style="border: 1px solid #000000; font-size:9px; text-align:left; width:86%;">
                            <?= esc($subkeg['nm_program']) ?> /
                            <?= esc($subkeg['nm_kegiatan']) ?><br>
                            <?= esc($subkeg['nm_subkegiatan']) ?>//

                            <br>
                            <table cellpadding="4">
                                <tr>
                                    <td style="border: 0px solid #ffffffff; font-size:9px; text-align:left; width:17%;">Pagu Subkegiatan:</td>
                                    <td style="border: 0px solid #ffffffff; font-size:9px; text-align:left; width:25%;">Rp
                                        <?= esc(number_format($subkeg['pagu'], 0, '.', ',')) ?>
                                    </td>
                                    <td style="border: 0px solid #ffffffff; font-size:9px; text-align:left; width:10%;">Realisasi </td>
                                    <td style="border: 0px solid #ffffffff; font-size:9px; text-align:left; width:18%;">
                                        <?php
                                        // echo dd($subkeg);
                                        $rsk = $this->realisasilrfkrinci->select('*')
                                            ->selectSum('realisasi')
                                            //->selectSum('pagu_rincian')
                                            ->where('tahun', $tahunaktif)
                                            ->where('bulan', $bulantw)
                                            ->where('kd_sub_unit', $subkeg['kd_subunit'])
                                            // ->where('kd_urusan', $value['kd_urusan'])
                                            // ->where('kd_program', $subkeg['kd_program'])
                                            // ->where('kd_kegiatan', $subkeg['kd_kegiatan'])
                                            ->where('kd_subkegiatan', $subkeg['kd_subkegiatan'])
                                            ->groupBy('kd_subkegiatan')
                                            ->where('delete_at=', 0)
                                            ->get()
                                            ->getRowArray();
                                        // echo dd($rsk);
                                        if (!$rsk) {
                                            echo esc('Rp0');
                                        } else {

                                            $paguSK = $subkeg['pagu'];
                                            $realSK = $rsk['realisasi'] ?? null;
                                            $persensk = ($realSK / $paguSK) * 100 ?? NUll;
                                            //echo $pagu . '///' . $real;
                                            if ($realSK > $paguSK) {
                                                echo "Realisasi bulan ini melebihi pagu";
                                            } else {
                                                echo 'Rp ' . esc(number_format($realSK, 0, ',', '.'));
                                            }
                                        }
                                        ?>

                                    </td>
                                    <td style="border: 0px solid #ffffffff; font-size:9px; text-align:left; width:10%;">
                                        <?php
                                        if (!$rsk) {
                                            echo esc('0');
                                        } else {
                                            $paguSK = $subkeg['pagu'];
                                            $realSK = $rsk['realisasi'] ?? null;
                                            $persensk = ($realSK / $paguSK) * 100 ?? NUll;
                                            //echo $pagu . '///' . $real;
                                            if ($realSK > $paguSK) {
                                                echo "Realisasi bulan ini melebihi pagu";
                                            } else {
                                                echo esc(number_format($persensk, 2, ',', '.')) . '%';
                                            }
                                        }

                                        ?>
                                    </td>
                                </tr>
                            </table><br>
                            Kegiatan Pokok/Aktifitas yang dilaksanakan adalah sebagai berikut:
                        </td>
                    </tr>

                    <?php
                    $datakegpokok = $this->kegpokokmodal
                        ->select('ta_kegpokok_capkin_apbd2.*')
                        // ->select('ta_subkeg_capkin_apbd.nm_subkegiatan')
                        // ->select('ta_mprog_prioritas.nm_progprioritas')
                        // ->join('ta_subkeg_capkin_apbd', 'ta_subkeg_capkin_apbd.id_sk=ta_kegpokok_capkin_apbd2.id_targetsubkeg')
                        // ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_kegpokok_capkin_apbd2.id_progprioritas')
                        ->where('ta_kegpokok_capkin_apbd2.kd_subunit', $subkeg['kd_subunit'])

                        ->where('ta_kegpokok_capkin_apbd2.kd_subkegiatan', $subkeg['kd_subkegiatan'])
                        ->where('ta_kegpokok_capkin_apbd2.id_progunggulan', $dataprogunggul['id_progunggulan'])
                        ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
                        ->get()
                        ->getResultArray();
                    // echo dd($datakegpokok);
                    foreach ($datakegpokok as $key => $kegpokok) {
                        $rkegpokok = $this->rkegpokokmodal
                            ->select('*')->where('id_kegpokok', $kegpokok['id_kp'])
                            ->where('bulan', $bulantw)
                            ->get()->getRowArray();

                        // echo dd($rkegpokok);
                    ?>
                        <tr>
                            <td style="border: 1px solid #000000; font-size:9px; text-align:right; width:4%;">-</td>
                            <td style="border: 1px solid #000000; font-size:9px; text-align:right; width:10%;"></td>
                            <td style="border: 1px solid #000000; font-size:9px; text-align:right; width:12%;"></td>
                            <td style="border: 1px solid #000000; font-size:9px; text-align:left; width:10%;">
                                <?= esc($kegpokok['vol_target']) ?>
                                <?= esc($kegpokok['sat_target']) ?>

                            </td>
                            <td style="border: 1px solid #000000; font-size:9px; text-align:right; width:10%;">
                                <?php if (empty($rkegpokok)) {
                                    echo '-'; // echo esc($rkegpokok['r_target']);
                                } else {
                                    echo esc($rkegpokok['r_target']) . '  ' . esc($rkegpokok['sat_target']);
                                } ?>
                            </td>
                            <td style="border: 1px solid #000000; font-size:9px; text-align:right; width:6%;">

                                <?php if (empty($rkegpokok)) {
                                    echo '0'; // echo esc($rkegpokok['r_target']);
                                } else {
                                    if ($rkegpokok['r_target'] == 0) {
                                        $persen = '0';
                                    } else {
                                        $persen = $rkegpokok['r_target'] / $kegpokok['vol_target'] * 100;
                                    }
                                    if ($persen == null) {
                                        echo 'realisasi melebihi target';
                                    } else {
                                        echo number_format(esc($persen), 2, '.', ',') . '%';
                                    }
                                } ?>
                            </td>
                            <td style="border: 1px solid #000000; font-size:9px; text-align:left; width:48%;" colspan="2">
                                <strong>URAIAN/DESKRIPSI TARGET:</strong>
                                <?= esc($kegpokok['uraian_target']) ?><br>
                                <strong>HASIL YANG INGIN DICAPAI:</strong>
                                <?= esc($kegpokok['hasil']) ?>
                            </td>

                        </tr>
                        <?php
                        $dataDRKP = $this->rdkegpokokmodal->DataPerRKegPokok($rkegpokok['id_r'] ?? null);
                        foreach ($dataDRKP as $key => $value) { ?>
                            <tr>
                                <td style=" border: 1px solid #000000; font-size:9px; text-align:right; width:4%;">-</td>
                                <td style="border: 1px solid #000000; font-size:9px; text-align:right; width:10%;"></td>
                                <td style="border: 1px solid #000000; font-size:9px; text-align:right; width:12%;"></td>
                                <td style="border: 1px solid #000000; font-size:9px; text-align:left; width:10%;"></td>
                                <td style="border: 1px solid #000000; font-size:9px; text-align:right; width:10%;"></td>
                                <td style="border: 1px solid #000000; font-size:9px; text-align:right; width:6%;"></td>
                                <td style="border: 1px solid #000000; font-size:9px; text-align:left; width:22%;"></td>
                                <td style="border: 1px solid #000000; font-size:9px; text-align:left; width:26%;">
                                    <img src="uploads/dokumentasi/<?= esc($value['gambar'] ?? null) ?>" width="100"><br>
                                    <?= esc($value['deskripsi']) ?>
                                    Lokasi Aktifitas:
                                    <?= esc($value['kabupaten']) ?>,<?= esc($value['kecamatan']) ?>, <?= esc($value['desa']) ?><br>
                                    Titik Koordinat:
                                    <?= esc($value['latitude']) ?>,<?= esc($value['longitude']) ?>
                                </td>
                            </tr>

                <?php }
                    }
                } ?>

            <?php } ?>


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