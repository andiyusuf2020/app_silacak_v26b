<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<?php

use App\Models\LrfkProvModel\TaRealisasiRinciModel;
use App\Models\LrfkProvModel\SubKegModel; // as lrfkModel;

$this->realisasilrfkrinci = new TaRealisasiRinciModel();
$this->subkegmodel = new SubKegModel();

?>
<!--begin::Container-->
<div class="container-fluid">
    <!-- /.col-md-6 -->
    <div class="row">
        <?php if (session()->getFlashdata('message')): ?>
            <div class="alert alert-primary" role="alert">
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
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Capaian Realisasi Anggaran per-Subkegiatan sampai dengan Bulan <?= esc($bulan) ?></h3>
                </div>
                <div class="card-body table-responsive p-0" style="height: 300px;">
                    <table class="table table-head-fixed text-wrap">
                        <thead>
                            <tr>
                                <th style="width: 5px">#</th>
                                <th style="width: 750px">Sub Kegiatan</th>
                                <th style="width: 500px" colspan="2">Capaian Realisasi Anggaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($datarealisasi as $key => $value) { ?>
                                <?php
                                // $realisasi = $this->subkegmodel
                                //     ->selectSUM('pagu_rincian')
                                //     ->where('kd_sub_unit', $value['kd_sub_unit'])
                                //     ->where('tahun', $tahunaktif)
                                //     ->where('kd_subkegiatan', $value['kd_subkegiatan'])
                                //     ->groupBy('kd_subkegiatan')
                                //     ->get()
                                //     ->getRowArray();
                                // // if (!$realisasi) {
                                // //     $vpersen = '0';
                                // } else {
                                if ($value['realisasi'] == 0) {
                                    $vpersen = '0';
                                } else {
                                    $persen = $value['realisasi'] / $value['anggaran'] * 100;
                                    $vpersen = number_format($persen, 2, '.');
                                }
                                // }
                                if ($vpersen < 30.00) {
                                    $warna = 'bg-danger';
                                    $Xwarna = 'text-bg-danger';
                                } else {
                                    $warna = '';
                                    $Xwarna = 'text-bg-success';
                                }

                                ?>
                                <tr class="align-middle">
                                    <td><?= esc($key + 1)  ?></td>
                                    <td><?= esc($value['NAMA_SUB_GIAT'])  ?>

                                    </td>
                                    <td style="width: 300px">
                                        <!-- <div class="progress progress-xs progress-striped active">
                                        <div class="progress-bar text-bg-success" style="width: 90%"></div>
                                    </div> -->
                                        <div class="progress">
                                            <div class="progress-bar <?= esc($warna) ?>" role="progressbar" style="width: <?= esc($vpersen . '%') ?>;" aria-valuenow="<?= esc($vpersen) ?>" aria-valuemin="0" aria-valuemax="100"><?= esc($vpersen . '%') ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge <?= esc($Xwarna) ?>"><?= esc($vpersen . '%') ?></span>
                                    </td>
                                </tr>
                            <?php } ?>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="width: 5px">#</th>
                                <th style="width: 750px">Jumlah</th>
                                <th style="width: 250px">
                                    <?php
                                    if ($totrealisasi == 0) {
                                        $totpersen = '0';
                                    } else {
                                        $persentot = $totrealisasi / $totanggaran * 100;
                                        $totpersen = number_format($persentot, 2, '.');
                                    }
                                    // }
                                    if ($totpersen < 30.00) {
                                        $warna = 'bg-danger';
                                        $Xwarna = 'text-bg-danger';
                                    } else {
                                        $warna = '';
                                        $Xwarna = 'text-bg-success';
                                    }

                                    ?>
                                    <div class="progress">
                                        <div class="progress-bar <?= esc($warna) ?>" role="progressbar" style="width: <?= esc($totpersen . '%') ?>;" aria-valuenow="<?= esc($totpersen) ?>" aria-valuemin="0" aria-valuemax="100"><?= esc($totpersen . '%') ?></div>
                                    </div>
                                </th>
                                <th>
                                    <span class="badge <?= esc($Xwarna) ?>"><?= esc($totpersen . '%') ?></span>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-footer">
                    <span class="text-muted">
                        <i class=" bi bi-info-circle-fill"></i>Data realisasi anggaran per-subkegiatan ini
                        berdasarkan https://sipd-ri.kemendagri.go.id TA <?= esc($tahunaktif) ?>. per <?= esc($tglaktif) ?>. ** </span>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Capaian Realisasi Anggaran per-Bulan TA :<?= esc($tahunaktif) ?> </h3>
                </div>
                <div class="card-body table-responsive p-0" style="height: 300px;">
                    <div id="rchart"></div>
                </div>
                <div class="card-footer">
                    <span class="text-muted">
                        <i class=" bi bi-info-circle-fill"></i>Data realisasi anggaran per-Bulan ini
                        berdasarkan https://sipd-ri.kemendagri.go.id TA <?= esc($tahunaktif) ?> </span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Komposisi Belanja Kegiatan Pendukung</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-9">
                            <div id="chartjs-bar"></div>

                            <div id="pie-chart"></div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!--end::Row-->
                </div>
                <!-- /.card-body -->
                <div class="card-footer p-0">
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                Total Anggaran : <?= $nm_opd ?>

                                <span class="float-end text-danger">
                                    <?= 'Rp. ' . number_format(esc($totanggaran), 2, '.', ','); ?>
                                </span>

                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                Belanja Alat Tulis Kantor
                                <span class="float-end text-danger">
                                    <i class="bi bi-arrow-down fs-7"></i>
                                    <?= number_format(esc($atk), 2, '.', ',') . ' %'; ?>
                                </span>
                                <span class="float-end text-danger">
                                    <?= 'Rp. ' . number_format(esc($rpatk), 2, '.', ','); ?>
                                </span>

                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                Belanja Cetak
                                <span class="float-end text-success">
                                    <i class="bi bi-arrow-up fs-7"></i>
                                    <?= number_format(esc($cetak), 2, '.', ',') . ' %'; ?>
                                </span>
                                <span class="float-end text-danger">
                                    <?= 'Rp. ' . number_format(esc($rpcetak), 2, '.', ','); ?>
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                Belanja Perjalanan Dinas
                                <span class="float-end text-info">
                                    <i class="bi bi-arrow-left fs-7"></i>
                                    <?= number_format(esc($sppd), 2, '.', ',') . ' %'; ?>
                                </span>
                                <span class="float-end text-danger">
                                    <?= 'Rp. ' . number_format(esc($rpsppd), 2, '.', ','); ?>
                                </span>

                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Anggaran Tahun <?= esc($tahunaktif) ?> per-Belanja</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 5px">#</th>
                                <th>Uraian Belanja</th>
                                <th>Pagu (Rp)</th>
                                <th>Realisasi (Rp)</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="align-middle">
                                <td>-</td>
                                <td>5.1 / BELANJA OPERASI</td>
                                <td>
                                    <?= number_format(esc($bOperasi['anggaran']), 0, ',', '.') ?>
                                </td>
                                <td>
                                    <?= number_format(esc($bOperasi['realisasi']), 0, ',', '.') ?>
                                </td>
                                <td>
                                    <?php
                                    if ($bOperasi['anggaran'] == 0) {
                                        $persenbOperasi = 0;
                                    } else {
                                        $persenbOperasi = $bOperasi['realisasi'] / $bOperasi['anggaran'] * 100;
                                    }
                                    //$persenbOperasi = $bOperasi['realisasi'] / $bOperasi['anggaran'] * 100;
                                    echo number_format(esc($persenbOperasi), 2, ',', '.');
                                    ?>%

                                </td>
                            </tr>
                            <tr class="align-middle">
                                <td>-</td>
                                <td>5.1.01 / Belanja Pegawai</td>
                                <td>
                                    <?= number_format(esc($bPegawai['anggaran']), 0, ',', '.') ?>
                                </td>
                                <td>
                                    <?= number_format(esc($bPegawai['realisasi']), 0, ',', '.') ?>
                                </td>
                                <td>
                                    <?php
                                    if ($bPegawai['anggaran'] == 0) {
                                        $persenbPegawai = 0;
                                    } else {
                                        $persenbPegawai = $bPegawai['realisasi'] / $bPegawai['anggaran'] * 100;
                                    }
                                    // $persenbPegawai = $bPegawai['realisasi'] / $bPegawai['anggaran'] * 100;
                                    echo number_format(esc($persenbPegawai), 2, ',', '.'); ?>%
                                </td>
                            </tr>
                            <tr class="align-middle">
                                <td>-</td>
                                <td>5.1.02 / Belanja Barang dan Jasa</td>
                                <td>
                                    <?= number_format(esc($bBarjas['anggaran']), 0, ',', '.') ?>
                                </td>
                                <td>
                                    <?= number_format(esc($bBarjas['realisasi']), 0, ',', '.') ?>
                                </td>
                                <td>
                                    <?php
                                    if ($bBarjas['anggaran'] == 0) {
                                        $persenbBarjas = 0;
                                    } else {
                                        $persenbBarjas = $bBarjas['realisasi'] / $bBarjas['anggaran'] * 100;
                                    }
                                    // $persenbBarjas = $bBarjas['realisasi'] / $bBarjas['anggaran'] * 100;
                                    echo number_format(esc($persenbBarjas), 2, ',', '.'); ?>%
                                </td>
                            </tr>
                            <tr class="align-middle">
                                <td>-</td>
                                <td>5.2 / Belanja Modal</td>
                                <td>
                                    <?= number_format(esc($bModal['anggaran']), 0, ',', '.') ?>
                                </td>
                                <td>
                                    <?= number_format(esc($bModal['realisasi']), 0, ',', '.') ?>
                                </td>
                                <td>
                                    <?php
                                    if ($bModal['anggaran'] == 0) {
                                        $persenbModal = 0;
                                    } else {
                                        $persenbModal = $bModal['realisasi'] / $bModal['anggaran'] * 100;
                                    }
                                    // $persenbModal = $bModal['realisasi'] / $bModal['anggaran'] * 100;
                                    echo number_format(esc($persenbModal), 2, ',', '.'); ?>%
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>

    </div>
    <!--end::Row-->
</div>
<style>
    /* CSS untuk memastikan ukuran carousel tetap */
    .fixed-size-carousel {
        width: 1200px;
        /* Sesuaikan dengan lebar yang diinginkan */
        height: 450px;
        /* Sesuaikan dengan tinggi yang diinginkan */
        margin: 0 auto;
    }

    .fixed-size-carousel .carousel-inner,
    .fixed-size-carousel .carousel-item {
        width: 100%;
        height: 100%;
    }

    .fixed-size-carousel .carousel-item img {
        width: 150%;
        height: 150%;
        object-fit: cover;
        /* Memastikan gambar menutupi area tanpa kehilangan proporsi */
    }
</style>

<script src="<?= base_url() ?>vendors/echarts/dist/echarts.min.js"></script>

<script type="text/javascript">
    var dom = document.getElementById("container");
    var myChart = echarts.init(dom);
    var app = {};

    var option;

    option = {
        title: {
            text: 'Rainfall vs Evaporation',
            subtext: 'Fake Data'
        },
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data: ['Rainfall', 'Evaporation']
        },
        toolbox: {
            show: true,
            feature: {
                dataView: {
                    show: true,
                    readOnly: false
                },
                magicType: {
                    show: true,
                    type: ['line', 'bar']
                },
                restore: {
                    show: true
                },
                saveAsImage: {
                    show: true
                }
            }
        },
        calculable: true,
        xAxis: [{
            type: 'category',
            // prettier-ignore
            data: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']

        }],
        yAxis: [{
            type: 'value'
        }],
        series: [{
                name: 'Rainfall',
                type: 'bar',
                data: [
                    2.0, 4.9, 7.0, 23.2, 25.6, 76.7, 135.6, 162.2, 32.6, 20.0, 6.4, 3.3
                ],
                markPoint: {
                    data: [{
                            type: 'max',
                            name: 'Max'
                        },
                        {
                            type: 'min',
                            name: 'Min'
                        }
                    ]
                },
                markLine: {
                    data: [{
                        type: 'average',
                        name: 'Avg'
                    }]
                }
            },
            {
                name: 'Evaporation',
                type: 'bar',
                data: <?php
                        // function convertStringsToNumbers($value)
                        // {
                        //     if (is_array($value)) {
                        //         return array_map('convertStringsToNumbers', $value);
                        //     }
                        //     if (is_string($value)) {
                        //         if (is_numeric($value)) {
                        //             return strpos($value, '.') === false ? (int)$value : (float)$value;
                        //         }
                        //     }
                        //     return $value;
                        // }
                        // $convertedData = convertStringsToNumbers($datarealpertahun);
                        // $json = json_encode($convertedData);
                        // echo '[' . $json . ']';
                        ?>[25, 30, 20, 15, 10, 5, 8, 12, 18, 22, 28, 32],
                markPoint: {
                    data: [{
                            name: 'Max',
                            value: 182.2,
                            xAxis: 7,
                            yAxis: 183
                        },
                        {
                            name: 'Min',
                            value: 2.3,
                            xAxis: 11,
                            yAxis: 3
                        }
                    ]
                },
                markLine: {
                    data: [{
                        type: 'average',
                        name: 'Avg'
                    }]
                }
            }
        ]
    };

    if (option && typeof option === 'object') {
        myChart.setOption(option);
    }
</script>

<script type="text/javascript">
    var dom = document.getElementById("container2");
    var myChart = echarts.init(dom);
    var app = {};

    var option;



    option = {
        title: {
            text: 'Nightingale Chart',
            subtext: 'Fake Data',
            left: 'center'
        },
        tooltip: {
            trigger: 'item',
            formatter: '{a} <br/>{b} : {c} ({d}%)'
        },
        legend: {
            left: 'center',
            top: 'bottom',
            data: [
                'rose1',
                'rose2',
                'rose3',
                'rose4',
                'rose5',
                'rose6',
                'rose7',
                'rose8'
            ]
        },
        toolbox: {
            show: true,
            feature: {
                mark: {
                    show: true
                },
                dataView: {
                    show: true,
                    readOnly: false
                },
                restore: {
                    show: true
                },
                saveAsImage: {
                    show: true
                }
            }
        },
        series: [{
            name: 'Area Mode',
            type: 'pie',
            radius: [20, 140],
            center: ['50%', '50%'],
            roseType: 'area',
            itemStyle: {
                borderRadius: 5
            },
            data: [{
                    value: 30,
                    name: 'rose 1'
                },
                {
                    value: 28,
                    name: 'rose 2'
                },
                {
                    value: 26,
                    name: 'rose 3'
                },
                {
                    value: 24,
                    name: 'rose 4'
                },
                {
                    value: 22,
                    name: 'rose 5'
                },
                {
                    value: 20,
                    name: 'rose 6'
                },
                {
                    value: 18,
                    name: 'rose 7'
                },
                {
                    value: 16,
                    name: 'rose 8'
                }
            ]
        }]
    };

    if (option && typeof option === 'object') {
        myChart.setOption(option);
    }
</script>
<script>
    var chartDom = document.getElementById('rsubkeg');
    var myChart = echarts.init(chartDom);
    var option;

    option = {
        title: {
            text: 'World Population'
        },
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'shadow'
            }
        },
        legend: {},
        xAxis: {
            type: 'value',
            boundaryGap: [0, 1]
        },
        yAxis: {
            type: 'category',
            // data: ['Br a zil', 'Indon esia', 'U SA', 'In dia', 'Ch  ina', 'Wo r l d']
            // data: [
            //     'Penyusunan Dokumen Perencanaan Perangkat Daerah',
            //     'Pelaksanaan Penatausahaan dan Pengujian/Verifikasi Keuangan SKPD',
            //     'Penatausahaan Barang Milik Daerah pada SKPD',
            //     'Pendidikan dan Pelatihan Pegawai Berdasarkan Tugas dan Fungsi',
            //     'Penyediaan Peralatan dan Perlengkapan Kantor',
            //     'Penyelenggaraan Rapat Koordinasi dan Konsultasi SKPD',
            //     'Pengendalian Administrasi Pelaksanaan Pembangunan APBD',
            //     'Pengendalian Administrasi Pelaksanaan Pembangunan APBN',
            //     'Pengendalian Administrasi Pelaksanaan Pembangunan Wilayah',
            //     'Analisis Capaian Kinerja Pembangunan Daerah',
            //     'Pelaporan Pelaksanaan Pembangunan Daerah',
            //     'Fasilitasi Perumusan Kebijakan Teknis Pembangunan Daerah',
            // ]
            data: [
                <?php
                foreach ($dataSKOpd as $key => $value) {
                    echo $value['nm_subkegiatan'] .
                        ",";
                } ?>
            ]

        },
        series: [{
            name: '2012',
            type: 'bar',
            data: [19325, 23438, 31000, 121594, 134141, 681807]
        }]
    };

    option && myChart.setOption(option);
</script>
<!--end::Container-->
<!-- OPTIONAL SCRIPTS -->
<!-- apexcharts -->
<script
    src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
    integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
    crossorigin="anonymous">
</script>
<script>
    // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
    // IT'S ALL JUST JUNK FOR DEMO
    // ++++++++++++++++++++++++++++++++++++++++++

    const visitors_chart_options = {
        series: [{
                name: 'High - 2023',
                data: [100, 120, 170, 167, 180, 177, 160],
            },
            {
                name: 'Low - 2023',
                data: [60, 80, 70, 67, 80, 77, 100],
            },
        ],
        chart: {
            height: 200,
            type: 'line',
            toolbar: {
                show: false,
            },
        },
        colors: ['#0d6efd', '#adb5bd'],
        stroke: {
            curve: 'smooth',
        },
        grid: {
            borderColor: '#e7e7e7',
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5,
            },
        },
        legend: {
            show: false,
        },
        markers: {
            size: 1,
        },
        xaxis: {
            categories: ['22th', '23th', '24th', '25th', '26th', '27th', '28th'],
        },
    };

    const visitors_chart = new ApexCharts(
        document.querySelector('#visitors-chart'),
        visitors_chart_options,
    );
    visitors_chart.render();
</script>
<script>
    const r_chart_options = {
        series: [{
                name: 'Realisasi',
                data: [
                    <?php
                    foreach ($dataROpdPerBln as $key => $value) {
                        echo $value['persen'] .
                            ",";
                    } ?>
                    // 44, 55, 57, 56, 61, 58, 63, 60, 66
                ],
            },
            // {
            //     name: 'Revenue',
            //     data: [76, 85, 101, 98, 87, 105, 91, 114, 94],
            // },
            // {
            //     name: 'Free Cash Flow',
            //     data: [35, 41, 36, 26, 45, 48, 52, 53, 41],
            // },
        ],
        chart: {
            type: 'bar',
            height: 200,
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                endingShape: 'rounded',
            },
        },
        legend: {
            show: true,
        },
        colors: [
            '#0d6efd',
            //  '#20c997', 
            //  '#ffc107'
        ],
        dataLabels: {
            enabled: false,
        },
        stroke: {
            show: true,
            width: 5,
            colors: ['transparent'],
        },
        xaxis: {
            categories:

                [
                    <?php
                    foreach ($dataROpdPerBln as $key => $value) {
                        echo "'" . $value['BULAN'] . " / per-" . $value['CREATE_AT'] . "'" .
                            ",";
                    } ?>
                    // 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'
                ],
        },
        fill: {
            opacity: 1,
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return '>' + val + ' %';
                },
            },
        },
    };

    const r_chart = new ApexCharts(
        document.querySelector('#rchart'),
        r_chart_options,
    );
    r_chart.render();
</script>
<script>
    //-------------
    // - PIE CHART -
    //-------------

    const pie_chart_options = {
        series: [<?= number_format(esc($atk), 2, '.', ','); ?>,
            <?= number_format(esc($cetak), 2, '.', ','); ?>,
            <?= number_format(esc($sppd), 2, '.', ','); ?>
        ],
        chart: {
            type: 'donut',
        },
        labels: ['Bel.ATK', 'Bel.Cetak', 'Bel.PerDin'],
        //labels: ['Bel.ATK', 'Bel.Cetak', 'Bel.PerDin', 'Safari', 'Opera', 'IE'],

        dataLabels: {
            enabled: false,
        },
        // colors: ['#0d6efd', '#20c997', '#ffc107', '#d63384', '#6f42c1', '#adb5bd'],
        colors: ['#0d6efd', '#20c997', '#ffc107'],
    };

    const pie_chart = new ApexCharts(document.querySelector('#pie-chart'), pie_chart_options);
    pie_chart.render();

    //-----------------
    // - END PIE CHART -
    //-----------------
</script>

<?= $this->endSection() ?>