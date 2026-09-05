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
            height: 10px;
            margin: 0.5px;
        }

        /* td {
            border: 1px solid #000000;
            text-align: left;
            height: 15px;
            margin: 0.5px;
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

    use App\Models\DataApbdModel\RealApbdModel;

    $this->realapbd = new RealApbdModel();
    ?>
    <h3 style="font-size:12px; text-align:center;">LAPORAN KINERJA KEGIATAN PENDUKUNG SASARAN PEMBANGUNAN <br>TA <?= esc($tahun) ?><br>
        <?= esc($datauser['sub_unit']) ?>
    </h3>
    <table cellpadding="3" style="text-align:justify; font-size:10px; width:100%;">
        <thead>
        </thead>
        <tbody>
            <!-- <tr>
                <th style="font-size:9px; text-align:center; width:4%" rowspan="2"><strong>#</strong></th>
                <th style="font-size:9px; text-align:center; width:10%" rowspan="2"><strong>SASARAN RPJMD YANG DIDUKUNG</strong></th>
                <th style="font-size:9px; text-align:left; width:12%" rowspan="2"><strong>PROGRAM/ KEGIATAN/ SUBKEGIATAN dan <br> ANGGARAN <br>(pagu,realisasi) </strong></th>
                <th style="font-size:9px; text-align:center; width:26%" colspan="3"><strong>CAPAIAN KINERJA</strong></th>
                <th style="font-size:9px; text-align:center; width:22%" rowspan="2"><strong>URAIAN TARGET PELAKSANAAN AKTIVITAS/KEGIATAN dan HASIL YANG AKAN DICAPAI TAHUN <?= esc($tahun) ?></strong></th>
                <th style="font-size:9px; text-align:center; width:26%" rowspan="2"><strong>DESKRIPSI PROGRES REALISASI AKTIVITAS/KEGIATAN POKOK dan LOKASI KEGIATAN s.d <?= esc($bulan) ?></strong></th>
            </tr>
            <tr>
                <th style="font-size:9px; text-align:center; width:10%"><strong>Target Aktivitas/ Kegiatan Pokok Tahun <?= esc($tahun) ?></strong></th>
                <th style="font-size:9px; text-align:center; width:10%"><strong>Realisasi Aktivitas/Kegiatan s.d <?= esc($bulan) ?> </strong></th>
                <th style="font-size:9px; text-align:center; width:6%"><strong>Capaian (%)</strong></th>
            </tr> -->
            <?php
            foreach ($sasaranopd as $key => $dataprogprio) { ?>
                <tr>
                    <!-- <td style=" font-size:9px; text-align:right; width:4%;">-</td> -->
                    <td style=" font-size:10px; text-align:justify; width:100%;">
                        <strong><?= esc('Sasaran RPJMD yang diampu : ' . $dataprogprio['nm_progprioritas']) . ',' ?></strong>
                        <!-- <i style="font-size: 12px;"> -->
                        <?= esc('dengan dukungan Program, Kegiatan, Subkegiatan dan Kegiatan Pokok/Aktifitas') ?>
                        <?= esc(' yang dilaksanakan sebagai berikut:') ?>
                        <!-- </i> -->
                    </td>
                </tr>
                <?php
                $datasubkeg = $this->kegpokokmodal
                    ->select('ta_kegpokok_capkin_apbd2.*')
                    ->select('ta_subkeg_mappingcapkin.nm_program,nm_kegiatan,nm_sub_giat')
                    ->join('ta_subkeg_mappingcapkin', 'ta_subkeg_mappingcapkin.id_skcapkin=ta_kegpokok_capkin_apbd2.id_targetsubkeg')
                    ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_kegpokok_capkin_apbd2.id_progprioritas')
                    ->where('ta_kegpokok_capkin_apbd2.kd_subunit', $dataprogprio['kd_subunit'])
                    ->where('ta_kegpokok_capkin_apbd2.tahun', $tahun)
                    ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
                    ->where('ta_subkeg_mappingcapkin.delete_at=', 0)
                    ->where('ta_kegpokok_capkin_apbd2.id_progprioritas', $dataprogprio['id_progprioritas'])
                    ->groupBy('ta_kegpokok_capkin_apbd2.kd_subkegiatan')
                    ->get()
                    ->getResultArray();
                $aktifitasperprogram = $this->kegpokokmodal
                    ->select('ta_kegpokok_capkin_apbd2.*')
                    ->select('ta_subkeg_mappingcapkin.nm_program,nm_kegiatan,nm_sub_giat')
                    ->join('ta_subkeg_mappingcapkin', 'ta_subkeg_mappingcapkin.id_skcapkin=ta_kegpokok_capkin_apbd2.id_targetsubkeg')
                    ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_kegpokok_capkin_apbd2.id_progprioritas')
                    ->where('ta_kegpokok_capkin_apbd2.kd_subunit', $dataprogprio['kd_subunit'])
                    ->where('ta_kegpokok_capkin_apbd2.tahun', $tahun)
                    ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
                    ->where('ta_subkeg_mappingcapkin.delete_at=', 0)
                    ->where('ta_kegpokok_capkin_apbd2.id_progprioritas', $dataprogprio['id_progprioritas'])
                    ->groupBy('ta_subkeg_mappingcapkin.nm_program')
                    ->get()
                    ->getResultArray();
                foreach ($aktifitasperprogram as $key => $program) { ?>
                    <tr>
                        <td style=" font-size:9px; text-align:left; width:100%;">
                            <strong><?= esc($program['nm_program']) ?></strong>
                        </td>
                    </tr>
                    <?php
                    $aktifitasperkegiatan = $this->kegpokokmodal
                        ->select('ta_kegpokok_capkin_apbd2.*')
                        ->select('ta_subkeg_mappingcapkin.nm_program,nm_kegiatan,nm_sub_giat')
                        ->join('ta_subkeg_mappingcapkin', 'ta_subkeg_mappingcapkin.id_skcapkin=ta_kegpokok_capkin_apbd2.id_targetsubkeg')
                        ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_kegpokok_capkin_apbd2.id_progprioritas')
                        ->where('ta_kegpokok_capkin_apbd2.kd_subunit', $dataprogprio['kd_subunit'])
                        ->where('ta_kegpokok_capkin_apbd2.tahun', $tahun)
                        ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
                        ->where('ta_subkeg_mappingcapkin.delete_at=', 0)
                        ->where('ta_kegpokok_capkin_apbd2.id_progprioritas', $dataprogprio['id_progprioritas'])
                        ->where('ta_subkeg_mappingcapkin.nm_program', $program['nm_program'])
                        ->groupBy('ta_subkeg_mappingcapkin.nm_kegiatan')
                        ->get()
                        ->getResultArray();
                    foreach ($aktifitasperkegiatan as $key => $kegiatan) {
                        $aktifitaspersubkegiatan = $this->kegpokokmodal
                            ->select('ta_kegpokok_capkin_apbd2.*')
                            ->select('ta_subkeg_mappingcapkin.kd_program,nm_program,kd_kegiatan,nm_kegiatan,kd_sub_giat,nm_sub_giat,total_anggaran,total_realisasi,total_realisasi_spj,')
                            ->join('ta_subkeg_mappingcapkin', 'ta_subkeg_mappingcapkin.id_skcapkin=ta_kegpokok_capkin_apbd2.id_targetsubkeg')
                            ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_kegpokok_capkin_apbd2.id_progprioritas')
                            ->where('ta_kegpokok_capkin_apbd2.kd_subunit', $dataprogprio['kd_subunit'])
                            ->where('ta_kegpokok_capkin_apbd2.tahun', $tahun)
                            ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
                            ->where('ta_kegpokok_capkin_apbd2.id_progprioritas', $dataprogprio['id_progprioritas'])
                            ->where('ta_subkeg_mappingcapkin.nm_program', $program['nm_program'])
                            ->where('ta_subkeg_mappingcapkin.nm_kegiatan', $kegiatan['nm_kegiatan'])
                            ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
                            ->where('ta_subkeg_mappingcapkin.delete_at=', 0)
                            ->groupBy('ta_subkeg_mappingcapkin.nm_sub_giat')
                            ->get()
                            ->getResultArray();
                    ?>
                        <tr>
                            <td style=" font-size:9px; text-align:left; width:100%;">
                                <strong><?= esc('Kegiatan : ' . $kegiatan['nm_kegiatan']) ?></strong>
                            </td>
                        </tr>
                        <?php
                        // echo dd($aktifitaspersubkegiatan);
                        foreach ($aktifitaspersubkegiatan as $key => $subkeg) { ?>
                            <tr>
                                <td style=" font-size:9px; text-align:left; width:100%;">
                                    <strong><?= esc('Sub Kegiatan : ' . $subkeg['nm_sub_giat']) ?></strong>
                                    <br>
                                    <table cellpadding="4">
                                        <tr>
                                            <td style="border: 0px solid #ffffffff; font-size:9px; text-align:left; width:25%;">Anggaran Subkegiatan:</td>
                                            <td style="border: 0px solid #ffffffff; font-size:9px; text-align:left; width:25%;">Rp
                                                <?= esc(number_format($subkeg['total_anggaran'], 0, ',', '.')) ?>
                                            </td>
                                            <td style="border: 0px solid #ffffffff; font-size:9px; text-align:left; width:25%;">Realisasi Anggaran</td>
                                            <td style="border: 0px solid #ffffffff; font-size:9px; text-align:left; width:25%;">
                                                <?php
                                                // $datapersubkegiatan = $this->realapbd->lissubkegiatan(
                                                //     $dataprogprio['kd_subunit'],
                                                //     $dataprogprio['tahun'],
                                                //     $tglaktif,
                                                //     $subkeg['kd_program'],
                                                //     $subkeg['kd_kegiatan'],
                                                // );
                                                ?>
                                                <?= esc('Rp ' . number_format($subkeg['total_realisasi'], 0, ',', '.')) ?>
                                            </td>

                                        </tr>
                                    </table><br>
                                    Aktifitas yang dilaksanakan sebagai berikut:
                                </td>
                            </tr>
                            <?php
                            $kelompokaktifitas = $this->kegpokokmodal
                                ->select('kelompok')
                                ->where('kd_subunit', $subkeg['kd_subunit'])
                                ->where('kd_subkegiatan', $subkeg['kd_subkegiatan'])
                                ->where('id_progprioritas', $dataprogprio['id_progprioritas'])
                                ->where('tahun', $tahun)
                                ->where('delete_at=', 0)
                                ->groupBy('kelompok')
                                ->get()
                                ->getResultArray();
                            // echo dd($kelompokaktifitas);
                            foreach ($kelompokaktifitas as $key => $kelompok) {
                                $datakegpokok = $this->kegpokokmodal
                                    ->select('ta_kegpokok_capkin_apbd2.*')
                                    // ->select('ta_subkeg_capkin_apbd.nm_subkegiatan')
                                    // ->select('ta_mprog_prioritas.nm_progprioritas')
                                    // ->join('ta_subkeg_capkin_apbd', 'ta_subkeg_capkin_apbd.id_sk=ta_kegpokok_capkin_apbd2.id_targetsubkeg')
                                    // ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_kegpokok_capkin_apbd2.id_progprioritas')
                                    ->where('ta_kegpokok_capkin_apbd2.tahun', $tahun)
                                    ->where('ta_kegpokok_capkin_apbd2.kd_subunit', $subkeg['kd_subunit'])
                                    ->where('ta_kegpokok_capkin_apbd2.kd_subkegiatan', $subkeg['kd_subkegiatan'])
                                    ->where('ta_kegpokok_capkin_apbd2.id_progprioritas', $dataprogprio['id_progprioritas'])
                                    ->where('ta_kegpokok_capkin_apbd2.kelompok', $kelompok['kelompok'])
                                    ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
                                    ->get()
                                    ->getResultArray();
                            ?>
                                <tr>
                                    <td style=" font-size:9px; text-align:left; width:100%;">
                                        <?= esc('Kelompok : ') . esc($kelompok['kelompok']) ?>
                                    </td>
                                </tr>
                                <?php
                                // echo dd($datakegpokok);
                                foreach ($datakegpokok as $key => $kegpokok) {
                                    $rkegpokok = $this->rkegpokokmodal
                                        ->select('*')->where('id_kegpokok', $kegpokok['id_kp'])
                                        ->where('delete_at=', 0)
                                        ->where('tahun', $tahun)
                                        ->where('bulan', $bulan)
                                        ->get()->getRowArray();
                                    // echo dd($rkegpokok);
                                ?>
                                    <tr>
                                        <td style=" font-size:9px; text-align:right; width:4%;"><?= esc($key + 1) ?></td>
                                        <td style=" font-size:9px; text-align:left; width:96%;">
                                            <?= esc($kegpokok['uraian_target']) ?>
                                            <?= esc('dengan target ') . esc($kegpokok['vol_target']) . ' ' . esc($kegpokok['sat_target']) ?>
                                            <?php if (empty($rkegpokok)) {
                                                echo '<strong>' . esc('Aktifitas ini belum ada realisasi') . '</strong>';
                                            } else {
                                                echo '<strong>' . esc('Aktifitas ini telah terealisasi sebagai berikut :') . '</strong>';
                                            } ?>
                                        </td>
                                    </tr>
                                    <?php if (empty($rkegpokok)) {
                                    } else { ?>
                                        <tr>
                                            <td style=" font-size:9px; text-align:right; width:4%;"></td>
                                            <td style=" font-size:9px; text-align:left; width:96%;">
                                                <?= esc('Realisasi Pelaksanaan Aktifitas/Keg.Pokok s.d Bulan ' . $rkegpokok['bulan'] . ' Tahun ' . $tahunaktif) ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style=" font-size:9px; text-align:right; width:4%;"></td>
                                            <td style=" font-size:9px; text-align:left; width:96%;">
                                                <?= esc($kegpokok['uraian_target']) . ' dengan realisasi :' ?>
                                                <?= esc($rkegpokok['r_target']) . ' ' . esc($rkegpokok['sat_target']) ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $dataDRKP = $this->rdkegpokokmodal->DataPerRKegPokok($rkegpokok['id_r'] ?? null, $tahun);
                                        foreach ($dataDRKP as $key => $value) { ?>
                                            <tr>
                                                <td style=" font-size:9px; text-align:right; width:4%;"></td>
                                                <td style="font-size:9px; text-align:right; width:4%;">-</td>
                                                <td style=" font-size:9px; text-align:left; width:92%;">
                                                    <br><strong><?= esc($rkegpokok['r_uraian']) . ' dengan realisasi :' ?>
                                                        <?= esc($rkegpokok['r_target']) . ' ' . esc($rkegpokok['sat_target']) ?>
                                                    </strong>
                                                    <br><img src="uploads/dokumentasi/<?= esc($value['gambar'] ?? null) ?>" width="200"><br>
                                                    <?= esc($value['deskripsi']) . ', ' ?>
                                                    Lokasi dan Koordinat Realisasi:
                                                    <?= esc($value['kabupaten']) ?>,<?= esc($value['kecamatan']) ?>, <?= esc($value['desa']) ?><br>
                                                    Titik Koordinat:
                                                    <?= esc($value['latitude']) ?>,<?= esc($value['longitude']) ?>
                                                </td>
                                            </tr>

                                        <?php  }  ?>

                        <?php  }
                                }
                            }
                        } ?>
            <?php }
                }
            } ?>
        </tbody>
    </table>
    <br>
    <br>
</body>

</html>