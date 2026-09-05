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
                                'action' => 'opd',
                                'kdSU' => $nama_opd,
                                // 'kdU' => $value['kd_urusan']
                            ]);
                            ?>" target='blank'>
                    <button type="button" class="btn btn-outline-primary mb-2">Cetak LRFK <?= esc($nama_opd) ?></button>
                </a><br>
                <h3>Data Realisasi Anggaran per-Sub Kegiatan Perangkat Daerah : <?= esc($nama_opd) ?> </h3>
                <br>
                <button onclick="history.back()" class="btn btn-secondary">Kembali</button>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0" style="height: 600px;">
                <table class="table table-head-fixed table-success table-striped text-wrap">
                    <thead>
                        <tr class="align-middle">
                            <th style="width: 10px">#</th>
                            <th style="width: 15px">Tahun</th>
                            <th>Kode Subkegiatan/Nama Sub Kegiatan</th>
                            <th>Pagu Anggaran<br>Rp.</th>
                            <th>Realisasi Anggaran (SIPD)<br>Rp.
                                <a href="#" data-bs-toggle="tooltip"
                                    data-bs-title="Data ini berdasarkan data Realisasi dari SIPD Penatausahan">
                                    <i class=" bi bi-emoji-sunglasses"></i></a>
                            </th>
                            <th>Realisasi Rill(SPJ)<br>Rp.
                                <a href="#" data-bs-toggle="tooltip"
                                    data-bs-title="Data ini berdasarkan data Realisasi Rill dari SPJ yang diinput oleh OPD">
                                    <i class=" bi bi-emoji-sunglasses"></i></a>
                            </th>
                            <th>Capaian Realisasi Anggaran <br>%</th>
                            <th>Capaian Realisasi Rill Anggaran<br>%</th>
                            <th>Capaian Kinerja belanja(output/fisik)</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dataopd as $key => $value) { ?>
                            <tr>
                                <td><?= esc($key + 1) ?> </td>
                                <td><?= esc($value['TAHUN']) ?></td>
                                <td>
                                    <?= esc($value['KODE_SUB_GIAT']) ?><br>
                                    <?= esc($value['NAMA_SUB_GIAT']) ?>
                                </td>
                                <td style="text-align: right;">
                                    <?= number_format(esc($value['anggaran']), 0, ',', '.') ?>
                                </td>
                                <td style="text-align: right;">
                                    <?= number_format(esc($value['realisasi']), 0, ',', '.') ?>
                                </td>
                                <td style="text-align: right;">
                                    <?= number_format(esc($value['realisasi_spj']), 0, ',', '.') ?>
                                </td>
                                <td>
                                    <?php
                                    if ($value['anggaran'] == 0) {
                                        $capaian = '0';
                                    } else {
                                        $capaian = ($value['realisasi'] / $value['anggaran']) * 100;
                                    } ?>
                                    <?= esc(number_format($capaian, 2, '.', ',')) ?>
                                </td>
                                <td>
                                    <?php
                                    if ($value['anggaran'] == 0) {
                                        $capaian_spj = '0';
                                    } else {
                                        $capaian_spj = ($value['realisasi_spj'] / $value['anggaran']) * 100;
                                    } ?>
                                    <?= esc(number_format($capaian_spj, 2, '.', ',')) ?>
                                </td>
                                <td>
                                    <?php
                                    $datageomean = $this->realapbd
                                        ->select('EXP(AVG(LOG(1 + (TOTAL_REALISASI) / (TOTAL_ANGGARAN)))) - 1 as geomean')
                                        ->where('TAHUN', $value['TAHUN'])
                                        ->where('BULAN', $value['BULAN'])
                                        ->where('NAMA_UNIT_SKPD', $value['NAMA_UNIT_SKPD'])
                                        ->where('KODE_SUB_GIAT', $value['KODE_SUB_GIAT'])
                                        ->where('CREATE_AT', $value['CREATE_AT'])
                                        ->where('(format((TOTAL_REALISASI / TOTAL_ANGGARAN),2)) >', 0.01)
                                        ->where('TOTAL_REALISASI<>', 0)
                                        ->get()
                                        ->getResultArray();
                                    foreach ($datageomean as $i => $row) {
                                        $dtA = ($row['geomean'] * 100);
                                        if ($dtA < 50) { ?>
                                            <span class="badge text-bg-danger"><?= esc(number_format($dtA, 2)) ?>%</span>
                                        <?php
                                        } elseif ($dtA >= 50 && $dtA < 75) { ?>
                                            <span class="badge text-bg-warning"><?= esc(number_format($dtA, 2)) ?>%</span>
                                        <?php
                                        } elseif ($dtA >= 75 && $dtA < 90) { ?>
                                            <span class="badge text-bg-primary"><?= esc(number_format($dtA, 2)) ?>%</span>
                                        <?php
                                        } else { ?>
                                            <span class="badge text-bg-success"><?= esc(number_format($dtA, 2)) ?>%</span>
                                        <?php }
                                    }


                                    $datarincireal = $this->realapbd
                                        ->select('*')
                                        //  ->select('(format((realisasi / pagu_rincian),2)) as isi')
                                        // ->select('((realisasi / pagu_rincian)+1) as isi')
                                        ->select('((TOTAL_REALISASI / TOTAL_ANGGARAN)) as isi')
                                        ->where('TAHUN', $value['TAHUN'])
                                        ->where('BULAN', $value['BULAN'])
                                        ->where('NAMA_UNIT_SKPD', $value['NAMA_UNIT_SKPD'])
                                        ->where('KODE_SUB_GIAT', $value['KODE_SUB_GIAT'])
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
                                                    'action' => 'detailopd',
                                                    'kdSU' => $nama_opd,
                                                    'kdSK' => $value['KODE_SUB_GIAT'],
                                                    'tgldataopd' => $value['CREATE_AT'],
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
                        <tr>
                            <th style="width: 10px" colspan="3">JUMLAH</th>
                            <th style="text-align: right;">
                                <?php
                                $jumlahAnggaran = array_sum(array_column($dataopd, 'anggaran'));
                                echo esc(number_format($jumlahAnggaran, 0, ',', '.'));
                                ?>
                            </th>
                            <th style="text-align: right;">
                                <?php
                                $jumlahRealisasi = array_sum(array_column($dataopd, 'realisasi'));
                                echo esc(number_format($jumlahRealisasi, 0, ',', '.'));
                                ?>
                            </th>
                            <th style="text-align: right;">
                                <?php
                                $jumlahRealisasiSpj = array_sum(array_column($dataopd, 'realisasi_spj'));
                                echo esc(number_format($jumlahRealisasiSpj, 0, ',', '.'));
                                ?>
                            </th>
                            <th>
                                <?php
                                $capaianTotal = ($jumlahRealisasi / $jumlahAnggaran) * 100;
                                echo esc(number_format($capaianTotal, 2)) . '%';
                                ?>
                            </th>
                            <th>
                                <?php
                                $capaianTotalSpj = ($jumlahRealisasiSpj / $jumlahAnggaran) * 100;
                                echo esc(number_format($capaianTotalSpj, 3)) . '%';
                                ?>
                            </th>

                            <th>

                                <?php
                                $datarincireal2 = $this->realapbd
                                    ->select('*')
                                    //  ->select('(format((realisasi / pagu_rincian),2)) as isi')
                                    // ->select('((realisasi / pagu_rincian)+1) as isi')
                                    ->select('((TOTAL_REALISASI / TOTAL_ANGGARAN)) as isi')
                                    ->where('TAHUN', $value['TAHUN'])
                                    ->where('BULAN', $value['BULAN'])
                                    ->where('NAMA_UNIT_SKPD', $value['NAMA_UNIT_SKPD'])
                                    ->where('CREATE_AT', $value['CREATE_AT'])
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
                                        <span class="badge text-bg-danger"><?= esc(number_format($croopd2, 2)) ?>%</span>
                                    <?php
                                    } elseif ($croopd2 >= 50 && $croopd2 < 75) { ?>
                                        <span class="badge text-bg-warning"><?= esc(number_format($croopd2, 2)) ?>%</span>
                                    <?php
                                    } elseif ($croopd2 >= 75 && $croopd2 < 90) { ?>
                                        <span class="badge text-bg-primary"><?= esc(number_format($croopd2, 2)) ?>%</span>
                                    <?php
                                    } else { ?>
                                        <span class="badge text-bg-success"><?= esc(number_format($croopd2, 2)) ?>%</span>
                                <?php }
                                }
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