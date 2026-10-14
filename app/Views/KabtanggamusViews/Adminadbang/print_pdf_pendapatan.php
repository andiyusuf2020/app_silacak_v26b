<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Cetak Laporan Realisasi APBD <?= esc($tahun) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 20px;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        h3,
        h4 {
            margin: 4px 0;
        }

        .chart-box {
            text-align: center;
            margin: 15px 0 25px 0;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
        }

        .chart-box img {
            max-width: 100%;
            height: auto;
            max-height: 280px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #777;
            padding: 5px 7px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
            text-align: center;
        }

        .bg-month {
            background-color: #e2e3e5;
            font-weight: bold;
        }

        .bg-subtotal {
            background-color: #f8f9fa;
            font-weight: bold;
        }
    </style>
</head>

<body onload="window.print()">

    <div class="text-center">
        <img src="<?= base_url() ?>cssportal/img_home/tanggamus.png" width="5%" alt="Logo Kabupaten/Kota" />
        <h3>SEKRETARIAT PEMERINTAH DAERAH <?= strtoupper(session()->get('namawilayah')) ?></h3>
        <h3>BAGIAN ADMINISTRASI PEMBANGUNAN</h3>
        <h4>LAPORAN REALISASI PENDAPATAN & BELANJA DAERAH</h4>
        <h4>TAHUN ANGGARAN <?= esc($tahun) ?></h4>
    </div>
    <hr>

    <!-- GRAFIK TERCETAK PADA AWAL HALAMAN -->
    <?php if (!empty($_POST['chart_image'])): ?>
        <div class="chart-box">
            <h5 style="margin-bottom:8px;">GRAFIK TREN PERSENTASE REALISASI PENDAPATAN VS BELANJA (%)</h5>
            <img src="<?= $_POST['chart_image'] ?>" alt="Grafik Realisasi APBD">
        </div>
    <?php endif; ?>

    <!-- TABEL LAPORAN DIKELOMPOKKAN PER BULAN -->
    <table>
        <thead>
            <tr>
                <th style="width: 35px;">No</th>
                <th style="width: 80px;">Jenis</th>
                <th style="width: 120px;">Kode Rekening</th>
                <th>Uraian Akun / Program</th>
                <th style="width: 130px;">Anggaran (Rp)</th>
                <th style="width: 130px;">Realisasi (Rp)</th>
                <th style="width: 60px;">%</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $months = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember'
            ];

            if (empty($groupedData)): ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data realisasi.</td>
                </tr>
                <?php else:
                foreach ($groupedData as $blnNum => $rows):
                    $subAnggaran = 0;
                    $subRealisasi = 0;
                ?>
                    <tr class="bg-month">
                        <td colspan="7">BULAN: <?= strtoupper($months[$blnNum] ?? "BULAN $blnNum") ?></td>
                    </tr>

                    <?php foreach ($rows as $idx => $r):
                        $pct = $r['anggaran'] > 0 ? ($r['realisasi'] / $r['anggaran']) * 100 : 0;
                        $subAnggaran += $r['anggaran'];
                        $subRealisasi += $r['realisasi'];
                    ?>
                        <tr>
                            <td class="text-center"><?= $idx + 1 ?></td>
                            <td class="text-center"><?= $r['jenis'] ?></td>
                            <td><?= $r['kode_rekening'] ?></td>
                            <td><?= $r['uraian'] ?></td>
                            <td class="text-end"><?= number_format($r['anggaran'], 0, ',', '.') ?></td>
                            <td class="text-end"><?= number_format($r['realisasi'], 0, ',', '.') ?></td>
                            <td class="text-center"><?= number_format($pct, 1, ',', '.') ?>%</td>
                        </tr>
                    <?php endforeach; ?>

                    <!-- SUBTOTAL PER BULAN -->
                    <?php $subPct = $subAnggaran > 0 ? ($subRealisasi / $subAnggaran) * 100 : 0; ?>
                    <tr class="bg-subtotal">
                        <td colspan="4" class="text-end fw-bold">SUBTOTAL BULAN <?= strtoupper($months[$blnNum]) ?>:</td>
                        <td class="text-end fw-bold"><?= number_format($subAnggaran, 0, ',', '.') ?></td>
                        <td class="text-end fw-bold"><?= number_format($subRealisasi, 0, ',', '.') ?></td>
                        <td class="text-center fw-bold"><?= number_format($subPct, 1, ',', '.') ?>%</td>
                    </tr>
            <?php endforeach;
            endif; ?>
        </tbody>
    </table>

</body>

</html>