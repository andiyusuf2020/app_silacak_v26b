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
<html>

<head>
    <!-- <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        th {
            border: 1px solid #000000;
            text-align: center;
            height: 10px;
            margin: 1px;
        }

        /* td {
            border: 1px solid #000000;
            text-align: left;
            height: 15px;
            margin: 1px;
        } */
    </style> -->
</head>

<body style="text-align: justify;">

    <b style="font-size:12px; text-align:center;">LAPORAN KINERJA PROGRAM PRIORITAS PENDUKUNG SASARAN PEMBANGUNAN TA
        <?= esc($tahun) ?> Bulan <?= esc($bulantw) ?> <br><?= esc($datauser['sub_unit']) ?></b>

    <hr>

    <br>Dalam RPJMD 2025-2030 Provinsi Lampung <?= esc($datauser['sub_unit']) ?> Mendukung Sasaran Pembangunan, sebagai berikut: <br>

    <?php
    foreach ($progprio as $key => $dataprogprio) { ?>
        <br><?= esc($key + 1) ?>. Sasaran Pembangunan yang diampu adalah:<?= esc($dataprogprio['nm_progprioritas']) ?>
        <br><br>Program Kegiatan dan Subkegiatan serta Aktifitas/Kegiatan Pokok Prioritas pendukung sasaran tersebut :
        <?php
        $datasubkeg = $this->kegpokokmodal
            ->select('ta_kegpokok_capkin_apbd2.*')
            ->select('ta_subkeg_capkin_apbd.nm_subkegiatan,nm_kegiatan,nm_program')
            ->join('ta_subkeg_capkin_apbd', 'ta_subkeg_capkin_apbd.id_sk=ta_kegpokok_capkin_apbd2.id_targetsubkeg')
            ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_kegpokok_capkin_apbd2.id_progprioritas')
            ->where('ta_kegpokok_capkin_apbd2.kd_subunit', $dataprogprio['kd_subunit'])
            ->where('ta_kegpokok_capkin_apbd2.tahun', $dataprogprio['tahun'])
            ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
            ->where('ta_kegpokok_capkin_apbd2.id_progprioritas', $dataprogprio['id_progprioritas'])
            ->groupBy('ta_kegpokok_capkin_apbd2.kd_subkegiatan')
            ->get()
            ->getResultArray();
        foreach ($datasubkeg as $key => $subkeg) { ?>
            <br><br><?= esc($subkeg['nm_program']) ?>
            <br>Kegiatan <?= esc($subkeg['nm_kegiatan']) ?> <br>Subkegiatan <?= esc($subkeg['nm_subkegiatan']) ?>, dengan pagu subkegiatan Rp
            <?= esc(number_format($subkeg['pagu'], 0, '.', ',')) ?> yang telah terealisasi sebesar
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
                echo esc('Rp 0');
            } else {
                $paguSK = $subkeg['pagu'];
                $realSK = $rsk['realisasi'] ?? null;
                $persensk = ($realSK / $paguSK) * 100 ?? NUll;
                echo 'Rp ' . esc(number_format($realSK, 0, ',', '.'));
            }
            ?>
            Aktifitas/Kegiatan Pokok pada subkegiatan ini sebagai berikut:
            <!-- <br><strong>Target Aktifitas/Kegiatan Pokok</strong> -->
            <?php
            $datakegpokok = $this->kegpokokmodal
                ->select('ta_kegpokok_capkin_apbd2.*')
                ->where('ta_kegpokok_capkin_apbd2.kd_subunit', $subkeg['kd_subunit'])
                ->where('ta_kegpokok_capkin_apbd2.kd_subkegiatan', $subkeg['kd_subkegiatan'])
                ->where('ta_kegpokok_capkin_apbd2.id_progprioritas', $dataprogprio['id_progprioritas'])
                ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
                ->get()
                ->getResultArray();
            foreach ($datakegpokok as $key => $kegpokok) {
                $rkegpokok = $this->rkegpokokmodal
                    ->select('*')->where('id_kegpokok', $kegpokok['id_kp'])
                    ->where('bulan', $bulantw)
                    ->get()->getRowArray(); ?>
                <br>
                <table>
                    <tr>
                        <td style="font-size:11px; text-align:left; width:5%;"></td>
                        <td style="font-size:11px; text-align:left; width:95%;"></td>
                    </tr>
                    <tr>
                        <td style="font-size:11px; text-align:left; width:5%;">
                            <?= esc($key + 1) ?>.
                        </td>
                        <td style="font-size:11px; text-align:left; width:95%;">
                            <?= esc($kegpokok['uraian_target']) ?> target pelaksanaan tahun <?= esc($tahun) ?>
                            adalah <?= esc($kegpokok['vol_target']) ?>
                            <?= esc($kegpokok['sat_target']) ?>, hasil yang ingin dicapai dalam aktifitas ini adalah
                            <?= esc($kegpokok['hasil']); ?>. Uraian Pelaksanaan dan Realisasi sampai dengan saat ini:
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:11px; text-align:left; width:5%;"></td>
                        <td style="font-size:11px; text-align:left; width:95%;">
                            jumlah sasaran/target yang terealisasi
                            <?php if (empty($rkegpokok)) { ?>
                                saat ini belum terealisasi
                            <?php } else { ?>
                                <?= esc($rkegpokok['r_target']) . ' ' . esc($rkegpokok['sat_target']); ?>
                                <?= esc($rkegpokok['r_uraian']) ?>
                        </td>
                    </tr>
                    <?php
                                $dataDRKP = $this->rdkegpokokmodal->DataPerRKegPokok($rkegpokok['id_r'] ?? null);
                                foreach ($dataDRKP as $key => $value) { ?>
                        <tr>
                            <td style="font-size:11px; text-align:left; width:5%;"></td>
                            <td style="font-size:11px; text-align:left; width:95%;">
                                <img src="uploads/dokumentasi/<?= esc($value['gambar'] ?? null) ?>" width="200"><br>
                                <?= esc($value['deskripsi']) ?>
                                Lokasi Pelaksanan Aktifitas/Kegiatan:
                                <?= esc($value['kabupaten']) ?>,<?= esc($value['kecamatan']) ?>, <?= esc($value['desa']) ?>
                                Titik Koordinat:
                                <?= esc($value['latitude']) ?>,<?= esc($value['longitude']) ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>

                </table>
            <?php }
            ?>



        <?php } ?>


    <?php } ?>

</body>