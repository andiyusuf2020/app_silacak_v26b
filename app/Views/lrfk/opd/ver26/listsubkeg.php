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
                <h3 class="card-title">Data Sub Kegiatan <?= esc($datauser['sub_unit']) ?> </h3> <br>
                <a href="<?= hash_url('lrfkopd/apbdopd/', [
                                'hal' => 'cetakopd',
                                'action' => 'lrfk',
                                // 'kdSU' => $value['kd_sub_unit'],
                                // 'kdU' => $value['kd_urusan']
                            ]);
                            ?>" target='blank'>
                    <button type="button" class="btn btn-outline-primary mb-2">Cetak LRFK</button>
                </a>
            </div>
            <?php if (session()->getFlashdata('message')): ?>
                <div class="alert alert-danger" role="alert">
                    <ul>
                        <p><?= session()->getFlashdata('message') ?></p>
                    </ul>
                </div>
            <?php endif; ?>
            <?php if (session()->has('errors')): ?>
                <div class="alert alert-danger" role="alert">
                    <ul>
                        <?php foreach (session('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <!-- /.card-header -->


            <div class="card-body table-responsive p-0" style="height: 600px;">
                <table class="table table align-middle table-head-fixed table-success table-striped text-wrap">
                    <thead>
                        <tr class="align-bottom">
                            <th style="width: 10px">#</th>
                            <th>Program/Kegiatan/<br>
                                Kode Subkegiatan/Nama Sub Kegiatan</th>
                            <th>Anggaran SubKegiatan<br>Rp.</th>
                            <th>Realisasi Anggaran (SIPD)<br>Rp.
                                <a href="#" data-bs-toggle="tooltip"
                                    data-bs-title="Data ini berdasarkan data Realisasi Rencana dari SIPD Penatausahan">
                                    <i class=" bi bi-emoji-sunglasses"></i></a>
                            </th>
                            <?php if ($tahunaktif == 2025) {
                            } else { ?>
                                <th>Realisasi Rill(SPJ) Bulan <?= esc($bulanaktif) ?><br>s/d <?= esc(date('d-m-Y', strtotime($tglaktif))) ?><br>Rp.
                                    <a href="#" data-bs-toggle="tooltip"
                                        data-bs-title="Data ini berdasarkan data Realisasi Rill dari SPJ yang diinput oleh OPD">
                                        <i class=" bi bi-emoji-sunglasses"></i></a>
                                </th>
                            <?php } ?>
                            <th>Capaian Renc.Realisasi Anggaran<br>%</th>
                            <?php if ($tahunaktif == 2025) {
                            } else { ?>
                                <th>Capaian Realisasi Rill Anggaran<br>%</th>
                            <?php } ?>
                            <th>Capaian Kinerja belanja(output/fisik)</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($program as $key => $valuep) { ?>
                            <tr class="align-bottom">
                                <td colspan="10"><strong><?= esc($valuep['NAMA_PROGRAM']) ?></strong></td>
                            </tr>
                            <?php
                            $kegiatan = $this->realapbd->liskegiatan($datauser['sub_unit'], $valuep['TAHUN'], $valuep['CREATE_AT'], $valuep['KODE_PROGRAM']);

                            foreach ($kegiatan as $key => $valuek) { ?>
                                <tr class="align-bottom">
                                    <td colspan="10"><strong>Kegiatan: <?= esc($valuek['NAMA_GIAT']) ?></strong></td>
                                </tr>
                                <?php
                                $subkegiatan = $this->realapbd->lissubkegiatan($datauser['sub_unit'], $valuek['TAHUN'], $valuek['CREATE_AT'], $valuek['KODE_PROGRAM'], $valuek['KODE_GIAT']);
                                foreach ($subkegiatan as $key => $valuesk) { ?>
                                    <tr class="align-buttom">
                                        <td><?= esc($key + 1) ?> </td>
                                        <td>
                                            <strong>
                                                <?= esc($valuesk['KODE_SUB_GIAT']) ?><br>
                                                <?= esc($valuesk['NAMA_SUB_GIAT']) ?>
                                            </strong>
                                        </td>
                                        <td class="align-buttom"><?= number_format(esc($valuesk['anggaran']), 0, ',', '.') ?></td>
                                        <td class="align-buttom"><?= number_format(esc($valuesk['realisasi']), 0, ',', '.') ?>
                                            <?php if ($tahunaktif == 2025) {
                                            } else { ?>
                                                <div class="btn-group">
                                                    <button
                                                        type="button"
                                                        class="btn btn dropdown-toggle"
                                                        data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <i class="bi bi-menu-button-wide-fill"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#welcomeModal<?= esc($valuesk['KODE_SUB_GIAT']) ?>">
                                                                <i class="bi bi-plus-circle"></i>
                                                                Lihat Realisasi per-Bulan
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <!-- Modal Structure -->
                                                <div class="modal fade" id="welcomeModal<?= esc($valuesk['KODE_SUB_GIAT']) ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
                                                    <div class="modal-dialog modal-dialog-centered custom-modal">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-primary text-white">
                                                                <h5 class="modal-title">Realisasi Anggaran per Bulan Tahun: <?php echo esc($tahunaktif); ?> </h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="text-center">
                                                                    <strong><?= esc($valuesk['KODE_SUB_GIAT']) ?><br>
                                                                        <?= esc($valuesk['NAMA_SUB_GIAT']) ?><br>
                                                                        <?= esc($tahunaktif) ?>
                                                                    </strong>
                                                                    <table class="table table-bordered">
                                                                        <tr>
                                                                            <th>Bulan</th>
                                                                            <th>Realisasi Anggaran</th>
                                                                            <th>per-Tanggal</th>
                                                                        </tr>
                                                                        <?php
                                                                        $datarealapbdperbln = $this->realapbd->rperskperopdperbulan($valuesk['KODE_UNIT_SKPD'], $valuesk['KODE_SUB_GIAT'], $valuesk['TAHUN']);
                                                                        foreach ($datarealapbdperbln as  $dataperbln) {
                                                                        ?>
                                                                            <tr>
                                                                                <td><?= esc($dataperbln['BULAN']) ?></td>
                                                                                <td>
                                                                                    <?php
                                                                                    if ($dataperbln) {
                                                                                        $realisasiapbdperbln = $dataperbln['realisasi'];
                                                                                    } else {
                                                                                        $realisasiapbdperbln = 0;
                                                                                    }
                                                                                    echo esc(number_format($realisasiapbdperbln, 0, ',', '.')) . '<br>';
                                                                                    ?>
                                                                                </td>
                                                                                <td><?= esc(date('d-m-Y', strtotime($dataperbln['CREATE_AT']))) ?></td>
                                                                            </tr>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </td>
                                        <td class="align-buttom"> <?= number_format(esc($valuesk['realisasi_spj']), 0, ',', '.') ?>
                                            <?php if ($tahunaktif == 2025) {
                                            } else { ?>
                                                <div class="btn-group">
                                                    <button
                                                        type="button"
                                                        class="btn btn dropdown-toggle"
                                                        data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <i class="bi bi-menu-button-wide-fill"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <?php
                                                        if ($valuesk['realisasi_spj'] == 0) { ?>
                                                            <li><a class="dropdown-item"
                                                                    href="<?= hash_url(
                                                                                'lrfkopd/apbdopd/',
                                                                                [
                                                                                    'hal' => 'dataapbd',
                                                                                    'action' => 'ubahreal',
                                                                                    'tahun' => $valuesk['TAHUN'],
                                                                                    'tgldata' => $valuesk['CREATE_AT'],
                                                                                    'kdSU' => $valuesk['KODE_UNIT_SKPD'],
                                                                                    'kdSK' => $valuesk['KODE_SUB_GIAT'],
                                                                                    'rSK' => $valuesk['realisasi']
                                                                                ]
                                                                            );
                                                                            ?>">
                                                                    <i class="bi bi-plus-circle"></i>Input SPJ</a></li>
                                                        <?php
                                                        } else { ?>
                                                            <li><a class="dropdown-item"
                                                                    href="<?= hash_url(
                                                                                'lrfkopd/apbdopd/',
                                                                                [
                                                                                    'hal' => 'dataapbd',
                                                                                    'action' => 'ubahreal',
                                                                                    'tahun' => $valuesk['TAHUN'],
                                                                                    'tgldata' => $valuesk['CREATE_AT'],
                                                                                    'kdSU' => $valuesk['KODE_UNIT_SKPD'],
                                                                                    'kdSK' => $valuesk['KODE_SUB_GIAT'],
                                                                                    'rSK' => $valuesk['realisasi']
                                                                                ]
                                                                            );
                                                                            ?>">
                                                                    <i class="bi bi-plus-circle"></i>Ubah Data</a></li>
                                                        <?php } ?>
                                                        <li>
                                                            <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#ModalRealSPJ<?= esc($valuesk['KODE_SUB_GIAT']) ?>">
                                                                <i class="bi bi-plus-circle"></i>
                                                                Lihat Realisasi per-Bulan </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <!-- Modal Structure -->
                                                <div class="modal fade" id="ModalRealSPJ<?= esc($valuesk['KODE_SUB_GIAT']) ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
                                                    <div class="modal-dialog modal-dialog-centered custom-modal">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-primary text-white">
                                                                <h5 class="modal-title">Realisasi SPJ per Bulan Tahun: <?php echo esc($tahunaktif); ?> </h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="text-center">
                                                                    <strong><?= esc($valuesk['KODE_SUB_GIAT']) ?><br>
                                                                        <?= esc($valuesk['NAMA_SUB_GIAT']) ?><br>
                                                                        <?= esc($tahunaktif) ?>
                                                                    </strong>
                                                                    <table class="table table-bordered">
                                                                        <tr>
                                                                            <th>Bulan</th>
                                                                            <th>Realisasi Anggaran SIPD</th>
                                                                            <th>Realisasi SPJ</th>
                                                                            <th>Tanggal Update</th>
                                                                        </tr>
                                                                        <?php
                                                                        $datarealapbdperbln = $this->realapbd->rperskperopdperbulan($valuesk['KODE_UNIT_SKPD'], $valuesk['KODE_SUB_GIAT'], $valuesk['TAHUN']);
                                                                        foreach ($datarealapbdperbln as  $dataperbln) {
                                                                        ?>
                                                                            <tr>
                                                                                <td><?= esc($dataperbln['BULAN']) ?></td>
                                                                                <?php
                                                                                if ($dataperbln) {
                                                                                    $realisasiapbdperblnspj = $dataperbln['realisasi_spj'];
                                                                                    $realisasiapbdperblnsipd = $dataperbln['realisasi'];
                                                                                } else {
                                                                                    $realisasiapbdperblnsipd = 0;
                                                                                    $realisasiapbdperblnspj = 0;
                                                                                }

                                                                                ?>
                                                                                <td>
                                                                                    <?= esc(number_format($realisasiapbdperblnsipd, 0, ',', '.')) ?>
                                                                                </td>
                                                                                <td>
                                                                                    <?= esc(number_format($realisasiapbdperblnspj, 0, ',', '.')) ?>
                                                                                </td>
                                                                                <td><?= esc(date('d-m-Y', strtotime($dataperbln['UPDATE_AT']))) ?></td>
                                                                            </tr>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($valuesk['anggaran'] == 0) {
                                                $capaian = '0';
                                            } else {
                                                $capaian = ($valuesk['realisasi'] / $valuesk['anggaran']) * 100;
                                            } ?>
                                            <?= esc(number_format($capaian, 2, '.', ',')) ?>
                                        </td>
                                        <?php if ($tahunaktif == 2025) {
                                        } else { ?>

                                            <td>
                                                <?php
                                                if ($valuesk['anggaran'] == 0) {
                                                    $capaian_spj = '0';
                                                } else {
                                                    $capaian_spj = ($valuesk['realisasi_spj'] / $valuesk['anggaran']) * 100;
                                                } ?>
                                                <?= esc(number_format($capaian_spj, 2, '.', ',')) ?>
                                            </td>
                                        <?php } ?>
                                        <td>
                                            <?php
                                            $datarincireal = $this->realapbd
                                                ->select('*')
                                                //  ->select('(format((realisasi / pagu_rincian),2)) as isi')
                                                // ->select('((realisasi / pagu_rincian)+1) as isi')
                                                ->select('((TOTAL_REALISASI / TOTAL_ANGGARAN)) as isi')
                                                ->where('TAHUN', $valuesk['TAHUN'])
                                                ->where('BULAN', $valuesk['BULAN'])
                                                ->where('NAMA_UNIT_SKPD', $valuesk['NAMA_UNIT_SKPD'])
                                                ->where('KODE_SUB_GIAT', $valuesk['KODE_SUB_GIAT'])
                                                ->where('CREATE_AT', $valuesk['CREATE_AT'])
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
                                            <?php } ?>
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
                                $jumlahAnggaran = array_sum(array_column($program, 'anggaran'));
                                echo esc(number_format($jumlahAnggaran, 0, ',', '.'));
                                // $jumlahsubkegiatan = array_count_values($subkegiatan); //count(array_unique(array_column($subkegiatan, 'id')));
                                // echo esc(number_format($jumlahsubkegiatan, 0, ',', '.'));
                                ?>
                            </th>
                            <th>
                                <?php
                                $jumlahRealisasi = array_sum(array_column($program, 'realisasi'));
                                echo esc(number_format($jumlahRealisasi, 0, ',', '.'));
                                ?>
                            </th>
                            <?php if ($tahunaktif == 2025) {
                            } else { ?>
                                <th>
                                    <?php
                                    $jumlahRealisasiSpj = array_sum(array_column($program, 'realisasi_spj'));
                                    echo esc(number_format($jumlahRealisasiSpj, 0, ',', '.'));
                                    ?>
                                </th>
                            <?php } ?>
                            <th>
                                <?php
                                $capaianTotal = ($jumlahRealisasi / $jumlahAnggaran) * 100;
                                echo esc(number_format($capaianTotal, 2)) . '%';
                                ?>
                            </th>
                            <?php if ($tahunaktif == 2025) {
                            } else { ?>

                                <th>
                                    <?php
                                    $capaianTotalSpj = ($jumlahRealisasiSpj / $jumlahAnggaran) * 100;
                                    echo esc(number_format($capaianTotalSpj, 3)) . '%';
                                    ?>
                                </th>
                            <?php } ?>
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