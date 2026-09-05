<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard SiTAPIS - Provinsi Lampung</title>
    <link rel="stylesheet" href="<?php echo base_url('cssportal/templatemo-graph-page.css'); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
        crossorigin="anonymous" />
</head>

<body>
    <!-- Navigation -->
    <nav id="navbar">
        <div class="nav-container">
            <a href="<?= base_url() ?>" class="logo">
                <div class="logo-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M3 13h2v8H3zm4-8h2v13H7zm4-2h2v15h-2zm4 4h2v11h-2zm4-2h2v13h-2z" />

                    </svg>
                </div>
                <span class="logo-text">Dashboard SiTAPIS</span>
            </a>
            <ul class="nav-links">
                <li><a href="<?= base_url() ?>" class="active">Home</a></li>
                <li><a href="#dashboard">Dashboard Realisasi Anggaran</a></li>
                <li><a href="#reports">Aktifitas Kegiatan</a></li>

            </ul>
            <!-- <a href="https://www.google.com/search" target="_blank" rel="noopener" title="Search">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
            </a> -->
            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <ul class="nav-links-mobile" id="navLinksMobile">
            <li><a href="#home" class="active">Home</a></li>
            <li><a href="#dashboard">Dashboard Realisasi Anggaran</a></li>
            <li><a href="#reports">Aktifitas Kegiatan</a></li>
        </ul>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-bg"></div>
        <div class="geometric-shapes">
            <div class="shape shape1"></div>
            <div class="shape shape2"></div>
            <div class="shape shape3"></div>
            <div class="shape shape4"></div>
            <div class="shape shape5"></div>
            <div class="shape shape6"></div>
        </div>
        <div class="hero-content">
            <div class="hero-text">
                <img src="<?= base_url() ?>cssportal/img_home/lampung.png" width="20%" />
                <h1>Data Analytics<br>Executive Dashboard</h1>
                <p>Penyampaian Resume Data Pembangunan Provinsi Lampung dari Sistem Data Pengadalian dan Informasi (SiTAPIS)</p>
                <a href="<?= base_url('eksekutif') ?>" class="cta-button">Get Started</a>

            </div>
            <div class="hero-visual">
                <div class="city-container">
                    <div class="building building1">
                        <div class="building-fill"></div>
                        <div class="building-windows"></div>
                    </div>
                    <div class="building building2">
                        <div class="building-fill"></div>
                        <div class="building-windows"></div>
                    </div>
                    <div class="building building3">
                        <div class="building-fill"></div>
                        <div class="building-windows"></div>
                    </div>
                    <div class="building building4">
                        <div class="building-fill"></div>
                        <div class="building-windows"></div>
                    </div>
                    <div class="neon-line neon-line1"></div>
                    <div class="neon-line neon-line2"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dashboard Section -->
    <section class="dashboard-section" id="dashboard">
        <div class="dashboard-container">
            <h2 class="section-title">Dashboard Realisasi Anggaran</h2>
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">📊</div>
                        <div class="stat-title">Sasaran Pembangunan</div>
                    </div>
                    <?php

                    use App\Models\DataApbdModel\RealApbdModel;

                    $this->realapbd = new RealApbdModel();

                    use App\Models\DataApbdModel\PendApbdModel;
                    use App\Models\DataApbdModel\RealPendApbdModel;

                    $this->pendapbd = new PendApbdModel();
                    $this->realpendapbd = new RealPendApbdModel();

                    use App\Models\CapkinModel\TaSubKegCapkin2026;

                    $this->subkegcapkin2026model = new TaSubKegCapkin2026();

                    use App\Models\CapkinModel\TaKegPokokCapkinModel;

                    $this->kegpokokmodal = new TaKegPokokCapkinModel();

                    use App\Models\CapkinModel\TaRKegPokokCapkinModel;

                    $this->rdkegpokokmodal = new TaRKegPokokCapkinModel();
                    ?>
                    <div class="card-body table-responsive p-0">
                        <table class="table align-middle table-head-fixed table-success table-striped text-wrap " border="1">
                            <thead>
                                <tr class="align-middle">
                                    <th style="width: 10px">#</th>
                                    <th>Kode Perangkat Daerah/Nama Perangkat Daerah</th>
                                    <th>Pagu Anggaran<br>Rp.</th>
                                    <th>Realisasi Anggaran (SIPD)<br>Rp.
                                    </th>
                                    <th>Realisasi Rill(SPJ)<br>Rp.
                                    </th>
                                    <th>Capaian Realisasi Anggaran (SIPD)<br>%</th>
                                    <th>Capaian Realisasi Rill Anggaran<br>%</th>
                                    <th>Capaian Kinerja belanja(output/fisik)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dataopdadmin as $key => $value) { ?>
                                    <tr align="center">
                                        <td><?= esc($key + 1) ?> </td>
                                        <td>
                                            <?= esc($value['KODE_UNIT_SKPD']) ?><br>
                                            <?= esc($value['NAMA_UNIT_SKPD']) ?>
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
                                                ->where('CREATE_AT', $value['CREATE_AT'])
                                                ->where('(format(((TOTAL_REALISASI) / (TOTAL_ANGGARAN)),2)) >', 0.001)
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
                                            ?>
                                        </td>


                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr class="align-middle">
                                    <th style="width: 10px" colspan="2">JUMLAH</th>
                                    <th>
                                        <?php
                                        $jumlahAnggaran = array_sum(array_column($dataopdadmin, 'anggaran'));
                                        echo esc(number_format($jumlahAnggaran, 0, ',', '.'));
                                        ?>
                                    </th>
                                    <th>
                                        <?php
                                        $jumlahRealisasi = array_sum(array_column($dataopdadmin, 'realisasi'));
                                        echo esc(number_format($jumlahRealisasi, 0, ',', '.'));
                                        ?>
                                    </th>
                                    <th>
                                        <?php
                                        $jumlahRealisasiSpj = array_sum(array_column($dataopdadmin, 'realisasi_spj'));
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
                                            ->select('EXP(AVG(LOG(1 + (TOTAL_REALISASI) / (TOTAL_ANGGARAN)))) - 1 as isi')
                                            //  ->select('(format((realisasi / pagu_rincian),2)) as isi')
                                            // ->select('((realisasi / pagu_rincian)+1) as isi')
                                            // ->select('(((TOTAL_REALISASI) / (TOTAL_ANGGARAN))) as isi')
                                            ->where('TAHUN', $value['TAHUN'])
                                            ->where('BULAN', $value['BULAN'])
                                            // ->where('NAMA_UNIT_SKPD', $value['NAMA_UNIT_SKPD'])
                                            ->where('CREATE_AT', $value['CREATE_AT'])
                                            // ->where('(format((TOTAL_REALISASI / TOTAL_ANGGARAN)*100,2)) >', 0.001)
                                            ->where('TOTAL_REALISASI<>', 0)
                                            ->where('TOTAL_ANGGARAN<>', 0)

                                            ->get()
                                            ->getResultArray();
                                        $da2 = $datarincireal2;
                                        foreach ($da2 as $i => $row) {
                                            $dt = ($row['isi'] * 100);
                                            if ($dt < 50) { ?>
                                                <span class="badge text-bg-danger"><?= esc(number_format($dt, 2)) ?>%</span>
                                            <?php
                                            } elseif ($dt >= 50 && $dt < 75) { ?>
                                                <span class="badge text-bg-warning"><?= esc(number_format($dt, 2)) ?>%</span>
                                            <?php
                                            } elseif ($dt >= 75 && $dt < 90) { ?>
                                                <span class="badge text-bg-primary"><?= esc(number_format($dt, 2)) ?>%</span>
                                            <?php
                                            } else { ?>
                                                <span class="badge text-bg-success"><?= esc(number_format($dt, 2)) ?>%</span>
                                        <?php }
                                        }
                                        // $result = geometricMeanLog($da2);
                                        // echo $result; //"Rata-rata geometri dari " . implode(", ", $da2) . " = " . round($result, 4) . "\n";
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
                                <tr>
                                    <td colspan="8">
                                        <i class=" bi bi-info-circle-fill font-size-10"></i>Data realisasi anggaran Perangkat Daerah se-Provinsi Lampung ini
                                        berdasarkan <br>https://adbang.lampungprov.go.id/e-tapis/lrfkadmin/ dan <br>
                                        https://sipd-ri.kemendagri.go.id <br>TA <?= esc($value['TAHUN']) ?>. per <?= esc($value['CREATE_AT']) ?>. ** </span>

                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Reports Section -->
    <section class="reports-section" id="reports">
        <div class="dashboard-container">
            <h2 class="section-title">Aktifitas Kegiatan Perangkat Daerah</h2>
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-icon">💼</div>
                    <h3 class="section-title">Aktifitas Utama Perangkat Daerah Provinsi Lampung yang Mendukung Sasaran RPJMD 2026-2030</h3>
                    <div class="card-body table-responsive p-0">
                        <table class="table table align-middle table-head-fixed table-success table-striped text-wrap" border="1">
                            <thead>
                                <tr class="align-middle">
                                    <th style="width: 10px">#</th>
                                    <th>Kode Sub Unit</th>
                                    <th>Perangkat Daerah</th>
                                    <th>Jumlah Subkegiatan termapping</th>

                                    <th>Jumlah Sasaran termapping</th>

                                    <th>Jumlah Aktivitas Utama pengampu Sasaran</th>
                                    <th>Jumlah Dokumentasi Aktivitas Utama</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dataopdadmin as $key => $value) {
                                    $datasubgiatcapkinopd = $this->subkegcapkin2026model->DataPerSKPerSkpdpertahun($value['TAHUN'], $value['KODE_UNIT_SKPD']);
                                    $jmlsubkegtermapping = count($datasubgiatcapkinopd);
                                    // $datadokumentasirealisasiaktifitas = $this->rdkegpokokmodal->DataPerSasaranPerOPD($value['TAHUN'], $value['KODE_UNIT _SKPD'], $value['id_progprioritas']);
                                    $mappingsasaran = $this->kegpokokmodal->DataPerSasaranPerOPD26($value['TAHUN'], $value['KODE_UNIT_SKPD']);
                                    $dataaktifitas = $this->kegpokokmodal->DataPerPprioOPD26($value['TAHUN'], $value['KODE_UNIT_SKPD']);

                                    $datadokumentasirealisasiaktifitas = $this->rdkegpokokmodal->getLokasi26($value['TAHUN'], $value['KODE_UNIT_SKPD']);

                                ?>
                                    <tr class="align-middle">
                                        <td><?= esc($key + 1) ?> </td>
                                        <td>
                                            <?= esc($value['KODE_UNIT_SKPD']) ?>
                                        </td>
                                        <td align="left">
                                            <?= esc($value['NAMA_UNIT_SKPD']) ?>
                                        </td>
                                        <?php
                                        if ($jmlsubkegtermapping == 0) { ?>
                                            <td class="text-danger" color="red">
                                                <?= esc($jmlsubkegtermapping); ?>
                                            </td>

                                        <?php } else { ?>
                                            <td class="text-success" color="green">
                                                <?= esc($jmlsubkegtermapping); ?>
                                            </td>
                                        <?php } ?>
                                        <td>
                                            <?php
                                            if (count($mappingsasaran) == 0) { ?>
                                                <span class="text-danger">0</span>
                                            <?php } else { ?>
                                                <?= esc(count($mappingsasaran)) ?>
                                            <?php } ?>
                                        </td>

                                        <td>
                                            <?php
                                            if (count($dataaktifitas) == 0) { ?>
                                                <span class="text-danger">0</span>
                                            <?php } else { ?>
                                                <?= esc(count($dataaktifitas)) ?>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php
                                            if (count($datadokumentasirealisasiaktifitas) == 0) { ?>
                                                <span class="text-danger">0</span>
                                            <?php } else { ?>
                                                <?= esc(count($datadokumentasirealisasiaktifitas)) ?>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item"
                                                            href="<?= hash_url('eksekutif/capkin', [
                                                                        'action' => 'detaildokumentasi',
                                                                        'kdSU' => $value['KODE_UNIT_SKPD'],
                                                                        'nmSU' => $value['NAMA_UNIT_SKPD'],
                                                                    ]);
                                                                    ?>">
                                                            <i class="bi bi-plus-circle"></i>Detail</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p class="copyright">© <?= date('Y') ?>
                <a href="https://adbang.lampungprov.go.id/e-tapis" rel="nofollow noopener" target="_blank">SiTAPIS</a>
            </p>
        </div>
    </footer>

    <script src="<?php echo base_url('cssportal/templatemo-graph-script.js'); ?>"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>

</body>

</html>