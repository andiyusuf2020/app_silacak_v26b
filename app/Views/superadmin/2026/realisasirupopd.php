<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>

<?php

use App\Models\RupModel\RealRupModel;

$this->realrup = new RealRupModel();

?>
<!--begin::Container-->
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Data Rekapitulasi Realisasi Pengadaan Barang/Jasa (PBJ) Provinsi Lampung Sampai Tanggal <?= esc($tglaktif) ?> </h3> <br>
                <a href="<?= hash_url('adminprov/apbdopd/', [
                                'hal' => 'cetakrekap',
                                'action' => 'realisasirup',
                                // 'kdSU' => $value['kd_sub_unit'],
                                // 'kdU' => $value['kd_urusan']
                            ]);
                            ?>" target='blank'>
                    <button type="button" class="btn btn-outline-primary mb-2">Laporan RekapPBJ</button>
                </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0" style="height: 600px;">
                <table class="table table-head-fixed table-success table-striped text-wrap">
                    <thead>
                        <tr class="align-middle">
                            <th style="width: 10px">#</th>
                            <th>Tahun</th>
                            <th>Metode Pengadaan</th>
                            <th>Jumlah Paket</th>
                            <th>Nilai Paket (Rp)</th>
                            <th>Jumlah Realisasi Paket (selesai)</th>
                            <th>Jumlah Realisasi Paket (proses)</th>
                            <th>Total Realisasi Paket</th>
                            <th>Nilai Realisasi Paket (Rp)</th>
                            <th>Capaian %</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dataruppermetode as $key => $value) { ?>
                            <tr class="align-middle">
                                <td><?= esc($key + 1) ?> </td>
                                <td><?= esc($value['tahun']) ?></td>
                                <td>
                                    <?php
                                    if ($value['Metode_Pengadaan'] == '-') {
                                        echo 'Swakelola';
                                    } else {
                                        echo esc($value['Metode_Pengadaan']);
                                    }
                                    ?>
                                </td>
                                <td style="text-align:right"><?= esc($value['jumlah_paket']) ?></td>
                                <td style="text-align:right"><?= esc(number_format($value['total_anggaran'], 2, ',', '.')) ?> </td>
                                <td style="text-align:right">
                                    <?php
                                    if ($value['Metode_Pengadaan'] == '-') {
                                        $realisasiSelesai = $this->realrup->getRealisasiRupByMetodePengadaanSelesai($value['tahun'], $bulan, null, '');
                                    } else {
                                        $realisasiSelesai = $this->realrup->getRealisasiRupByMetodePengadaanSelesai($value['tahun'], $bulan, null, $value['Metode_Pengadaan']);
                                    }
                                    // $realisasiSelesai = $this->realrup->getRealisasiRupByMetodePengadaanSelesai($value['tahun'], $bulan, null, $value['Metode_Pengadaan']);
                                    // echo esc($realisasiSelesai['total_anggaran'] ?? null);
                                    echo esc($realisasiSelesai['jumlah_paket'] ?? null);
                                    ?>
                                </td>
                                <td style="text-align:right">
                                    <?php
                                    if ($value['Metode_Pengadaan'] == '-') {
                                        $realisasiProses = $this->realrup->getRealisasiRupByMetodePengadaanProses($value['tahun'], $bulan, null, '');
                                    } else {
                                        $realisasiProses = $this->realrup->getRealisasiRupByMetodePengadaanProses($value['tahun'], $bulan, null, $value['Metode_Pengadaan']);
                                    }
                                    // $realisasiProses = $this->realrup->getRealisasiRupByMetodePengadaanProses($value['tahun'], $bulan, null, $value['Metode_Pengadaan']);
                                    echo esc($realisasiProses['jumlah_paket'] ?? null);
                                    ?>
                                </td>
                                <td style="text-align:right">
                                    <?php
                                    $totalRealisasi = ($realisasiSelesai['jumlah_paket'] ?? 0) + ($realisasiProses['jumlah_paket'] ?? 0);
                                    echo esc($totalRealisasi);
                                    ?>
                                </td>
                                <td style="text-align:right">
                                    <?php
                                    $totalRRealisasi = ($realisasiSelesai['total_pdn'] ?? 0) + ($realisasiProses['total_pdn'] ?? 0);
                                    echo esc($totalRRealisasi);
                                    // $realisasi = $this->realrup->getRealisasiRupByMetodePengadaan($value['tahun'], $bulan, null, $value['Metode_Pengadaan']);
                                    // echo esc(number_format($realisasi['total_pdn'] ?? null, 2, ',', '.'));
                                    ?>
                                </td>
                                <td style="text-align:right">
                                    <?php
                                    if ($value['Metode_Pengadaan'] == '-') {
                                        $realisasi = $this->realrup->getRealisasiRupByMetodePengadaan($value['tahun'], $bulan, null, '');
                                    } else {
                                        $realisasi = $this->realrup->getRealisasiRupByMetodePengadaan($value['tahun'], $bulan, null, $value['Metode_Pengadaan']);
                                    }
                                    // $realisasi = $this->realrup->getRealisasiRupByMetodePengadaan($value['tahun'], $bulan, null, $value['Metode_Pengadaan']);
                                    $capaian = ($realisasi['total_pdn'] ?? 0) / ($value['total_anggaran'] ?? 1) * 100;
                                    echo esc(number_format($capaian, 2, ',', '.')) . ' %';
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr class="align-middle">
                            <th colspan="3">TOTAL</th>
                            <th style="text-align:right"><?= esc(number_format(array_sum(array_column($dataruppermetode, 'jumlah_paket')), 0, ',', '.')) ?></th>
                            <th style="text-align:right"><?= esc(number_format(array_sum(array_column($dataruppermetode, 'total_anggaran')), 2, ',', '.')) ?></th>
                            <th style="text-align:right">
                                <?php
                                $totalSelesai = $this->realrup->getTotalSelesai($tahunaktif, $bulan, null);
                                echo esc(number_format($totalSelesai['jumlah_paket'], 0, ',', '.'));
                                ?>
                            </th>
                            <th style="text-align:right">
                                <?php
                                $totalproses = $this->realrup->getTotalProses($tahunaktif, null);
                                echo esc($totalproses['jumlah_paket']);
                                ?>
                            </th>
                            <th style="text-align:right">
                                <?php
                                $totalRealisasi = ($totalSelesai['jumlah_paket'] ?? 0) + ($totalproses['jumlah_paket'] ?? 0);
                                echo esc(number_format($totalRealisasi, 0, ',', '.'));
                                ?>
                            </th>
                            <th style="text-align:right">
                                <?= esc(number_format(array_sum(array_column($datarealpermetode, 'total_pdn')), 2, ',', '.')) ?>
                            </th>
                            <th style="text-align:right">><?= esc(number_format((array_sum(array_column($datarealpermetode, 'total_pdn')) / array_sum(array_column($dataruppermetode, 'total_anggaran'))) * 100, 2, ',', '.')) ?> %</th>
                        </tr>

                    </tfoot>
                </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <span class="text-muted">
                    <i class=" bi bi-info-circle-fill"></i>Data realisasi ini
                    berdasarkan https://data.inaproc.id/rup?jenis_klpd=3&instansi=D264. ** </span>
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>
<!--end::Container-->

<?= $this->endSection() ?>