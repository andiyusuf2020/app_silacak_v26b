<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>

<?php

use App\Models\DataApbdModel\RealApbdModel;

use App\Models\DataApbdModel\AngkasModel;

$this->realapbd = new RealApbdModel();
$this->angkasmodel = new AngkasModel();
?>
<!--begin::Container-->
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Data Anggaran Kas Sub Kegiatan <?= esc($datauser['sub_unit']) ?> </h3> <br>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0" style="height: 600px;">
                <table class="table table align-middle table-head-fixed table-success table-striped table-bordered text-wrap">
                    <thead>
                        <tr class="align-bottom">
                            <th style="width: 10px">#</th>
                            <th>Program/Kegiatan/<br>
                                Kode Subkegiatan/Nama Sub Kegiatan</th>
                            <?php
                            foreach ($listbulan as $key => $value) { ?>
                                <th><?= esc($value['bulan']) ?></th>
                            <?php } ?>
                            <th>Jumlah Anggaran (Rp)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($program as $key => $valuep) { ?>
                            <tr class="align-bottom">
                                <td colspan="16"><strong><?= esc($valuep['NAMA_PROGRAM']) ?></strong></td>
                            </tr>
                            <?php
                            $kegiatan = $this->realapbd->liskegiatan($datauser['sub_unit'], $valuep['TAHUN'], $valuep['CREATE_AT'], $valuep['KODE_PROGRAM']);

                            foreach ($kegiatan as $key => $valuek) { ?>
                                <tr class="align-bottom">
                                    <td colspan="16"><strong>Kegiatan: <?= esc($valuek['NAMA_GIAT']) ?></strong></td>
                                </tr>
                                <?php
                                $subkegiatan = $this->realapbd->lissubkegiatan($datauser['sub_unit'], $valuek['TAHUN'], $valuek['CREATE_AT'], $valuek['KODE_PROGRAM'], $valuek['KODE_GIAT']);
                                foreach ($subkegiatan as $key => $valuesk) { ?>
                                    <tr class="align-buttom">
                                        <td><?= esc($key + 1) ?> </td>
                                        <td>
                                            <strong><?= esc($valuesk['KODE_SUB_GIAT']) ?><br>
                                                <?= esc($valuesk['NAMA_SUB_GIAT']) ?></strong>
                                        </td>
                                        <?php
                                        foreach ($listbulan as $key => $value) {
                                            $dataangkas = $this->angkasmodel->getDataAngkasSubGiatDetail($valuesk['TAHUN'], $value['bulan'], $valuesk['NAMA_UNIT_SKPD'], $valuesk['NAMA_SUB_GIAT']);
                                        ?>
                                            <td><?= esc(number_format($dataangkas['ANGKAS'], 0, ',', '.')) ?></td>
                                        <?php }

                                        ?>
                                        <td>
                                            <?php
                                            $totalangkas = $this->angkasmodel->getDataTotalAngkasSubGiat($valuesk['TAHUN'], $valuesk['NAMA_UNIT_SKPD'], $valuesk['NAMA_SUB_GIAT']);
                                            if ($totalangkas == null) {
                                                echo '-';
                                            } else {
                                                echo esc(number_format($totalangkas['total_angkas'], 0, ',', '.'));
                                            } ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button
                                                    type="button"
                                                    class="btn btn dropdown-toggle"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <?php
                                                    $dataangkasmenu = $this->angkasmodel->getDataAngkasSubGiatDetail($valuesk['TAHUN'], $value['bulan'], $valuesk['NAMA_UNIT_SKPD'], $valuesk['NAMA_SUB_GIAT']);
                                                    // echo dd($dataangkasmenu);
                                                    if ($dataangkasmenu['total_angkas'] == 0) { ?>
                                                        <li><a class="dropdown-item"
                                                                href="<?= hash_url(
                                                                            'lrfkopd/apbdopd/',
                                                                            [
                                                                                'hal' => 'dataangkasapbd',
                                                                                'action' => 'inputangkas',
                                                                                'tahun' => $valuesk['TAHUN'],
                                                                                'kdSU' => $valuesk['KODE_UNIT_SKPD'],
                                                                                'kdSK' => $valuesk['KODE_SUB_GIAT']
                                                                            ]
                                                                        );
                                                                        ?>">
                                                                <i class="bi bi-plus-circle"></i>Input Angkas</a></li>
                                                    <?php
                                                    } else { ?>
                                                        <li><a class="dropdown-item"
                                                                href="<?= hash_url(
                                                                            'lrfkopd/apbdopd/',
                                                                            [
                                                                                'hal' => 'dataangkasapbd',
                                                                                'action' => 'ubahangkas',
                                                                                'tahun' => $valuesk['TAHUN'],
                                                                                'kdSU' => $valuesk['KODE_UNIT_SKPD'],
                                                                                'kdSK' => $valuesk['KODE_SUB_GIAT']
                                                                            ]
                                                                        );
                                                                        ?>">
                                                                <i class="bi bi-plus-circle"></i>Ubah Data</a></li>
                                                    <?php } ?>

                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                        <?php }
                            }
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr class="align-bottom">
                            <th style="width: 10px" colspan="2">JUMLAH</th>
                            <th>
                                <?php
                                $jumlahAnggaran = array_sum(array_column($subkegiatan, 'anggaran'));
                                echo esc(number_format($jumlahAnggaran, 0, ',', '.'));
                                // $jumlahsubkegiatan = array_count_values($subkegiatan); //count(array_unique(array_column($subkegiatan, 'id')));
                                // echo esc(number_format($jumlahsubkegiatan, 0, ',', '.'));
                                ?>
                            </th>
                            <th>
                                <?php
                                $jumlahRealisasi = array_sum(array_column($subkegiatan, 'realisasi'));
                                echo esc(number_format($jumlahRealisasi, 0, ',', '.'));
                                ?>
                            </th>
                            <th>
                                <?php
                                $jumlahRealisasiSpj = array_sum(array_column($subkegiatan, 'realisasi_spj'));
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
                                    ->where('TAHUN', $valuesk['TAHUN'])
                                    ->where('BULAN', $valuesk['BULAN'])
                                    ->where('NAMA_UNIT_SKPD', $valuesk['NAMA_UNIT_SKPD'])
                                    ->where('CREATE_AT', $valuesk['CREATE_AT'])
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