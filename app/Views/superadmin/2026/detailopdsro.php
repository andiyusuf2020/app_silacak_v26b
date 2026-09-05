<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>

<?php

use App\Models\DataApbdModel\RealApbdModel;

$this->realapbd = new RealApbdModel();

?>
<!--begin::Container-->
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <a href="<?= hash_url('adminprov/apbdopd/', [
                                'hal' => 'cetakrekap',
                                'action' => 'all',
                                // 'kdSU' => $value['kd_sub_unit'],
                                // 'kdU' => $value['kd_urusan']
                            ]);
                            ?>" target='blank'>
                    <button type="button" class="btn btn-outline-primary mb-2">Cetak Rekap LRFK</button>
                </a>
                <br>
                <h3>Data Realisasi Anggaran per-Sub Rincian Objek Belanja Perangkat Daerah : <?= esc($nama_opd) ?> </h3>
                <br>
                <?= esc($dataopd[0]['NAMA_PROGRAM']) ?> <br>
                Kegiatan : <?= esc($dataopd[0]['NAMA_GIAT']) ?> <br>
                Sub Kegiatan: <?= esc($dataopd[0]['NAMA_GIAT']) ?>
                <br>
                <button onclick="history.back()" class="btn btn-secondary">Kembali</button>

            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0" style="height: 600px;">
                <table class="table table-head-fixed table-success table-striped text-wrap">
                    <thead>
                        <tr class="align-middle">
                            <th style="width: 10px">#</th>
                            <th>Kode Sub Rincian Objek Belanja /Nama Rincian Objek</th>
                            <th>Pagu Anggaran<br>Rp.</th>
                            <th>Realisasi Rencana Anggaran<br>Rp.
                                <a href="#" data-bs-toggle="tooltip"
                                    data-bs-title="Data ini berdasarkan data Realisasi Rencana dari SIPD Penatausahan">
                                    <i class=" bi bi-emoji-sunglasses"></i></a>
                            </th>
                            <th>Realisasi Rill(SPJ)<br>Rp.
                                <a href="#" data-bs-toggle="tooltip"
                                    data-bs-title="Data ini berdasarkan data Realisasi Rill dari SPJ yang diinput oleh OPD">
                                    <i class=" bi bi-emoji-sunglasses"></i></a>
                            </th>
                            <th>Capaian Renc.Realisasi Anggaran<br>%</th>
                            <th>Capaian Realisasi Rill Anggaran<br>%</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dataopd as $key => $value) { ?>
                            <tr class="align-middle">
                                <td><?= esc($key + 1) ?> </td>
                                <td>
                                    <?= esc($value['KODE_SRO']) ?><br>
                                    <?= esc($value['NAMA_SRO']) ?>
                                </td>
                                <td><?= number_format(esc($value['TOTAL_ANGGARAN']), 0, ',', '.') ?></td>
                                <td><?= number_format(esc($value['TOTAL_REALISASI']), 0, ',', '.') ?></td>
                                <td>
                                    <?= number_format(esc($value['REALISASI_SPJ']), 0, ',', '.') ?>
                                </td>
                                <td>
                                    <?php
                                    if ($value['TOTAL_ANGGARAN'] == 0) {
                                        $capaian = '0';
                                    } else {
                                        $capaian = ($value['TOTAL_REALISASI'] / $value['TOTAL_ANGGARAN']) * 100;
                                    } ?>
                                    <?= esc(number_format($capaian, 2, '.', ',')) ?>
                                </td>
                                <td>
                                    <?php
                                    if ($value['TOTAL_ANGGARAN'] == 0) {
                                        $capaian_spj = '0';
                                    } else {
                                        $capaian_spj = ($value['REALISASI_SPJ'] / $value['TOTAL_ANGGARAN']) * 100;
                                    } ?>
                                    <?= esc(number_format($capaian_spj, 2, '.', ',')) ?>
                                </td>
                                <td>
                                    <?php
                                    if ($capaian < 50) { ?>
                                        <div class="progress progress-xs">
                                            <div class="progress-bar text-bg-danger" style="width: <?= esc(number_format($capaian, 2)) ?>%"></div>
                                        </div>
                                    <?php
                                    } elseif ($capaian >= 50 && $capaian < 75) { ?>
                                        <div class="progress progress-xs">
                                            <div class="progress-bar text-bg-warning" style="width: <?= esc(number_format($capaian, 2)) ?>%"></div>
                                        </div>
                                    <?php
                                    } elseif ($capaian >= 75 && $capaian < 90) { ?>
                                        <div class="progress progress-xs progress-striped active">
                                            <div class="progress-bar text-bg-primary" style="width: <?= esc(number_format($capaian, 2)) ?>%"></div>
                                        </div>
                                    <?php
                                    } else { ?>
                                        <div class="progress progress-xs progress-striped active">
                                            <div class="progress-bar text-bg-success" style="width: <?= esc(number_format($capaian, 2))  ?>%"></div>
                                        </div>
                                    <?php } ?><br>
                                    <a href="<?= hash_url('adminprov/apbdopd/', [
                                                    'hal' => 'detail',
                                                    'action' => 'opd',
                                                    'kdSU' => $value['NAMA_UNIT_SKPD'],
                                                    // 'kdU' => $value['kd_urusan']
                                                ]);
                                                ?>">
                                        <button type="button" class="btn btn-outline-primary mb-2">Detail</button>
                                    </a>
                                </td>

                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr class="align-middle">
                            <th style="width: 10px" colspan="2">JUMLAH</th>
                            <th>
                                <?php
                                $jumlahAnggaran = array_sum(array_column($dataopd, 'TOTAL_ANGGARAN'));
                                echo esc(number_format($jumlahAnggaran, 0, ',', '.'));
                                ?>
                            </th>
                            <th>
                                <?php
                                $jumlahRealisasi = array_sum(array_column($dataopd, 'TOTAL_REALISASI'));
                                echo esc(number_format($jumlahRealisasi, 0, ',', '.'));
                                ?>
                            </th>
                            <th>
                                <?php
                                $jumlahRealisasiSpj = array_sum(array_column($dataopd, 'REALISASI_SPJ'));
                                echo esc(number_format($jumlahRealisasiSpj, 0, ',', '.'));
                                ?>
                            </th>
                            <th>
                                <?php
                                if ($jumlahAnggaran == 0) {
                                    $capaianTotal = '0';
                                } else {
                                    $capaianTotal = ($jumlahRealisasi / $jumlahAnggaran) * 100;
                                }
                                // $capaianTotal = ($jumlahRealisasi / $jumlahAnggaran) * 100;
                                echo esc(number_format($capaianTotal, 2)) . '%';
                                ?>
                            </th>
                            <th>
                                <?php
                                if ($jumlahAnggaran == 0) {
                                    $capaianTotalSpj = '0';
                                } else {
                                    $capaianTotalSpj = ($jumlahRealisasiSpj / $jumlahAnggaran) * 100;
                                }
                                echo esc(number_format($capaianTotalSpj, 2)) . '%';
                                ?>
                            </th>

                            <th>

                                <?php
                                if ($capaianTotal < 50) { ?>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar text-bg-danger" style="width: <?= esc(number_format($capaianTotal, 2)) ?>%"></div>
                                    </div>
                                <?php
                                } elseif ($capaianTotal >= 50 && $capaianTotal < 75) { ?>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar text-bg-warning" style="width: <?= esc(number_format($capaianTotal, 2)) ?>%"></div>
                                    </div>
                                <?php
                                } elseif ($capaianTotal >= 75 && $capaianTotal < 90) { ?>
                                    <div class="progress progress-xs progress-striped active">
                                        <div class="progress-bar text-bg-primary" style="width: <?= esc(number_format($capaianTotal, 2)) ?>%"></div>
                                    </div>
                                <?php
                                } else { ?>
                                    <div class="progress progress-xs progress-striped active">
                                        <div class="progress-bar text-bg-success" style="width: <?= esc(number_format($capaianTotal, 2))  ?>%"></div>
                                    </div>
                                <?php } ?>
                            </th>

                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <span class="text-muted">
                    <i class=" bi bi-info-circle-fill"></i>Data realisasi anggaran per-subkegiatan ini
                    berdasarkan https://sipd-ri.kemendagri.go.id TA <?= esc($tahunaktif) ?>. per <?= esc($tglaktif) ?>. ** </span>
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>
<!--end::Container-->

<?= $this->endSection() ?>