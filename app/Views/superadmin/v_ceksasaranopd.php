<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<!--begin::Container-->
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
        <div class="col-12">
            <!-- Default box -->
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
            <div class="card-header">
                <p style="text-align:center;">LAPORAN KINERJA PROGRAM PRIORITAS PENDUKUNG SASARAN PEMBANGUNAN TA
                    <?= esc($tahun) ?> <?= esc($dataopd['nm_sub_unit']) ?></p>
            </div>
            <div class="card-header">
                <button onclick="history.back()" class="btn btn-outline-primary mb-2">Kembali</button>
            </div>


            <!-- /.card-header -->
            <!-- <div class="card-body"> -->
            <div class="card-body table-responsive p-1" style="height: 700px;">

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2"><strong>#</strong></th>
                            <th rowspan="2"><strong>SASARAN PROGRAM PRIORITAS PEMBANGUNAN YANG DIDUKUNG</strong></th>
                            <th rowspan="2"><strong>PROGRAM/ KEGIATAN/ SUBKEGIATAN dan <br> ANGGARAN <br>(pagu,realisasi) </strong></th>
                            <th colspan="3"><strong>CAPAIAN KINERJA</strong></th>
                            <th rowspan="2"><strong>URAIAN TARGET PELAKSANAAN AKTIVITAS/KEGIATAN dan HASIL YANG AKAN DICAPAI TAHUN <?= esc($tahun) ?></strong></th>
                            <th rowspan="2"><strong>DESKRIPSI PROGRES REALISASI AKTIVITAS/KEGIATAN POKOK dan LOKASI KEGIATAN</strong></th>
                        </tr>
                        <tr>
                            <th><strong>Target Aktivitas/ Kegiatan Pokok Tahun <?= esc($tahun) ?></strong></th>
                            <th><strong>Realisasi Aktivitas/Kegiatan </strong></th>
                            <th><strong>Capaian (%)</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($progprio as $key => $dataprogprio) { ?>
                            <tr>
                                <td>-</td>
                                <td colspan="7">
                                    <strong><?= esc('Sasaran : ' . $dataprogprio['nm_progprioritas']) ?></strong>
                                    <i>
                                        <?= esc(',dengan dukungan Program,Kegiatan,Subkegiatan dan Kegiatan Pokok/Aktifitas') ?>
                                        <?= esc(' yang dilaksanakan sebagai berikut:') ?></i>
                                </td>
                            </tr>
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
                                <tr>
                                    <td>-</td>
                                    <td></td>
                                    <td colspan="6">
                                        <?= esc($subkeg['nm_program']) ?> /
                                        <?= esc($subkeg['nm_kegiatan']) ?><br>
                                        <?= esc($subkeg['nm_subkegiatan']) ?>//
                                        <br>
                                        <table class="table table-bordered">
                                            <tr>
                                                <td>Pagu Subkegiatan:</td>
                                                <td>Rp
                                                    <?= esc(number_format($subkeg['pagu'], 0, '.', ',')) ?>
                                                </td>
                                                <td>Realisasi Anggaran </td>
                                                <td>
                                                    <?php
                                                    // echo dd($subkeg);
                                                    $rsk = $this->realisasilrfkrinci->select('*')
                                                        ->selectSum('realisasi')
                                                        //->selectSum('pagu_rincian')
                                                        ->where('tahun', $tahunaktif)
                                                        ->where('bulan', $bulan)
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
                                                <td>
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
                                        </table>
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
                                    ->where('ta_kegpokok_capkin_apbd2.id_progprioritas', $dataprogprio['id_progprioritas'])
                                    ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
                                    ->get()
                                    ->getResultArray();
                                // echo dd($datakegpokok);
                                foreach ($datakegpokok as $key => $kegpokok) {
                                    $rkegpokok = $this->rkegpokokmodal
                                        ->select('*')->where('id_kegpokok', $kegpokok['id_kp'])
                                        ->where('delete_at=', 0)
                                        // ->where('bulan', $bulanpilih)
                                        ->get()->getRowArray();

                                    // echo dd($rkegpokok);
                                ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <?= esc($kegpokok['vol_target']) ?>
                                            <?= esc($kegpokok['sat_target']) ?>
                                        </td>
                                        <td>
                                            <?php if (empty($rkegpokok)) {
                                                echo '-'; // echo esc($rkegpokok['r_target']);
                                            } else {
                                                echo esc($rkegpokok['r_target']) . '  ' . esc($rkegpokok['sat_target']);
                                            } ?>
                                        </td>
                                        <td>

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
                                        <td colspan="2">

                                            <strong>URAIAN/DESKRIPSI TARGET:</strong>
                                            <?= esc($kegpokok['uraian_target']) ?><br>
                                            <strong>HASIL YANG INGIN DICAPAI:</strong>
                                            <?= esc($kegpokok['hasil']) ?>

                                        </td>
                                    </tr>
                                    <?php
                                    $dataDRKP = $this->rdkegpokokmodal->DataPerRKegPokok($rkegpokok['id_r'] ?? null, $tahunaktif); ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td colspan="4">

                                            <strong>Realisasi di Bulan <?= esc($rkegpokok['bulan'] ?? $bulanpilih) ?></strong><br>
                                            <strong><?= esc($rkegpokok['r_uraian'] ?? null) ?></strong>

                                        </td>
                                    </tr>
                                    <?php
                                    foreach ($dataDRKP as $key => $value) { ?>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td colspan="4">
                                                <a href="<?= hash_url('adminprov', [
                                                                'kdSU' => $value['kd_subunit'],
                                                                'idD' => $value['id_dr'],
                                                            ]);
                                                            ?>">
                                                    <i class="bi bi-eye-fill">Detail</i></a><br>

                                                Uraian Realisasai AKtifitas/Keg.Pokok:<br>

                                                <br><img src="uploads/dokumentasi/<?= esc($value['gambar'] ?? null) ?>" width="250"><br>
                                                <?= esc($value['deskripsi']) ?>
                                                Lokasi Aktifitas:
                                                <?= esc($value['kabupaten']) ?>,<?= esc($value['kecamatan']) ?>, <?= esc($value['desa']) ?><br>
                                                Titik Koordinat:
                                                <?= esc($value['latitude']) ?>,<?= esc($value['longitude']) ?>
                                            </td>
                                        </tr>

                            <?php  }
                                }
                            } ?>
                        <?php } ?>


                    </tbody>
                </table>

                <!-- /.card -->
            </div>
        </div>
        <!--end::Row-->
    </div>
</div>
<!--end::Container-->

<?= $this->endSection() ?>