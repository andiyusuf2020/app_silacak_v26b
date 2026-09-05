<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<!--begin::Container-->
<?php

use App\Models\LrfkProvModel\SubKegModel; // as lrfkModel;
use App\Models\LrfkProvModel\TaRealisasiRinciModel;
use App\Models\CapkinModel\TaSubKegCapkinModel;
use App\Models\CapkinModel\TaKegPokokCapkinModel;
use App\Models\CapkinModel\TaRealisasiKegPokokModel;
use App\Models\CapkinModel\TaRKegPokokCapkinModel;

$this->subkegmodel = new SubKegModel();
$this->realisasilrfkrinci = new TaRealisasiRinciModel();
$this->targetsubkegmodel = new TaSubKegCapkinModel();
$this->kegpokokmodal = new TaKegPokokCapkinModel();
$this->rkegpokokmodal = new TaRealisasiKegPokokModel();
$this->rdkegpokokmodal = new TaRKegPokokCapkinModel();

// Fungsi untuk membuat link Google Maps
function createGoogleMapsLink($coords, $name = '')
{
    $coords = trim($coords);
    $name = urlencode($name);
    return "https://www.google.com/maps?q={$coords}&z=15&t=m";
}
?>
<div class="container-fluid">
    <?php
    if ($data == 'RDAktifitasopd') {
    ?>
        <!--begin::Row-->
        <div class="row">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Data Realisasi Aktifitas Kegiatan <?= esc($nm_opd) ?> dalam APBD TA <?= esc($tahun) ?></h3>
                </div>
                <div class="card-header">
                    <button onclick="history.back()" class="btn btn-outline-primary mb-2">Kembali</button>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr style="text-align: center; vertical-align: middle;">
                                <th>No</th>
                                <th colspan="2">Uraian Realisasi</th>
                                <th>Tanggal Input Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($raktifitasopd as $key => $listdata) { ?>
                                <tr>
                                    <td colspan="4"><?= esc('Realisasi Bulan : ' . $listdata['bulan'] . ' ' . $tahun) ?></td>
                                </tr>
                                <tr>
                                    <td><?= esc($key + 1) ?></td>
                                    <td colspan="2">
                                        <?= esc($listdata['r_target'] . '  ') ?> <?= esc($listdata['sat_target']) ?><br>
                                        <?= esc($listdata['r_uraian']) ?>
                                    </td>
                                    <td>
                                        <?= esc($listdata['update_at']) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="3">Lokasi dan Dokumentasi Realisasi Aktifitas Sebagai berikut :</td>
                                </tr>
                                <?php
                                $Daktifitas = $this->rdkegpokokmodal->DataPerRKegPokok($listdata['id_r']);
                                if ($Daktifitas) {
                                    foreach ($Daktifitas as $keyX => $dataDR) { ?>
                                        <tr>
                                            <td></td>
                                            <td width="50%">
                                                <?= esc($dataDR['nm_program']) ?><br>
                                                <?= esc($dataDR['nm_kegiatan']) ?><br>
                                                <?= esc($dataDR['nm_subkegiatan']) ?><br>
                                                <?= esc('Lokasi Kegiatan :' . $dataDR['kabupaten'] . ', ' . $dataDR['kecamatan'] . ', ' . $dataDR['desa']) . '<br>' ?>
                                                <img src="uploads/dokumentasi/<?= esc($dataDR['gambar'] ?? null) ?>" width="250"><br>
                                                <?= esc($dataDR['deskripsi']) ?><br>

                                            </td>
                                            <td colspan="2">
                                                <div class="card card-success">
                                                    <div class="card-header">
                                                        <h1 class="card-title">Koordinat Lokasi Kegiatan</h1>
                                                        <div class="card-tools">
                                                            <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                                                                <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                                                                <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                                                            </button>
                                                        </div>
                                                        <!-- /.card-tools -->
                                                    </div>
                                                    <!-- /.card-header -->
                                                    <div class="card-body">
                                                        <div id="<?= esc($listdata['id_r'] . ($keyX + 1)) ?>" style="height: 220px"></div>
                                                    </div> <!-- /.card-body -->
                                                </div>
                                                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                                                <script>
                                                    const map<?= esc($listdata['id_r'] . ($keyX + 1)) ?> = L.map(`<?= esc($listdata['id_r'] . ($keyX + 1)) ?>`).setView(
                                                        [<?= esc($dataDR['latitude'] . $listdata['id_r'] . ($keyX + 1)) ?>, <?= esc($dataDR['longitude'] . $listdata['id_r'] . ($keyX + 1)) ?>],
                                                        15
                                                    );
                                                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                                        attribution: 'SiTAPIS : Sistem Data Pengendalian dan Informasi'
                                                    }).addTo(map<?= esc($listdata['id_r'] . ($keyX + 1)) ?>);
                                                    L.marker([<?= esc($dataDR['latitude'] . $listdata['id_r'] . ($keyX + 1)) ?>, <?= esc($dataDR['longitude'] . $listdata['id_r'] . ($keyX + 1)) ?>])
                                                        .addTo(map<?= esc($listdata['id_r'] . ($keyX + 1)) ?>)
                                                        .bindPopup(`
                                                                            <b><?= esc($dataDR['kegiatan']) ?></b><br>
                                                                            <b>
                                                                            <i class="bi bi-geo-alt"></i>
                                                                            <?= esc($dataDR['latitude'])  ?>,
                                                                            <?= esc($dataDR['longitude']) ?>

                                                                        `)
                                                        .openPopup();
                                                    L.circle([<?= esc($dataDR['latitude'] . $listdata['id_r'] . ($keyX + 1)) ?>, <?= esc($dataDR['longitude'] . $listdata['id_r'] . ($keyX + 1)) ?>], {
                                                        color: 'blue',
                                                        fillColor: '#1e90ff',
                                                        fillOpacity: 0.2,
                                                        radius: 50
                                                    }).addTo(map<?= esc($listdata['id_r'] . ($keyX + 1)) ?>);
                                                </script>
                                                <!-- <div id="map-detail" style="width: 100%; height: 300px;"></div> -->
                                                <?php
                                                $coords = $dataDR['latitude'] . ',' . $dataDR['longitude'];
                                                ?>
                                                <a class="coord-link"
                                                    href="<?php echo createGoogleMapsLink($coords, 'alamat'); ?>"
                                                    target="_blank">
                                                    <i class="bi bi-geo-alt"></i>Titik Koordinat di Google Map
                                                </a>
                                            </td>
                                        </tr>
                                <?php }
                                }
                                ?>

                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!--end::Row-->
    <?php } ?>

</div>
<!--end::Container-->
<?= $this->endSection() ?>