<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Rekapitulasi Realisasi Anggaran</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 15px;
        }

        /* Header Kop Surat */
        .header-kop {
            width: 100%;
            border-bottom: 3px double #333;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }

        .header-kop table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-kop td {
            border: none;
            vertical-align: middle;
        }

        .logo-pemda {
            width: 70px;
            height: auto;
        }

        .instansi-title {
            text-align: center;
        }

        .instansi-title h4 {
            margin: 0;
            font-size: 14px;
            text-transform: uppercase;
            font-weight: bold;
            color: #2c3e50;
        }

        .instansi-title h2 {
            margin: 3px 0;
            font-size: 16px;
            text-transform: uppercase;
            font-weight: bold;
            color: #111;
        }

        .instansi-title p {
            margin: 0;
            font-size: 10px;
            color: #555;
            font-style: italic;
        }

        /* Informasi Metadata Filter */
        .meta-info {
            margin-bottom: 15px;
            font-size: 11px;
            background: #f9f9f9;
            padding: 8px 10px;
            border-radius: 4px;
            border: 1px solid #eaeaea;
        }

        .meta-info table {
            width: 100%;
            border: none;
        }

        .meta-info td {
            border: none;
            padding: 2px 0;
        }

        /* Grafik Container */
        .chart-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .chart-container img {
            max-width: 90%;
            height: auto;
            max-height: 240px;
            border: 1px solid #ddd;
            padding: 4px;
            background: #fff;
        }

        /* Tabel Data */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #bdc3c7;
            padding: 5px 7px;
        }

        table.data-table th {
            background-color: #f2f4f4;
            color: #2c3e50;
            text-align: center;
            font-size: 10px;
            text-transform: uppercase;
        }

        table.data-table td.text-end {
            text-align: right;
        }

        table.data-table td.text-center {
            text-align: center;
        }

        .footer-note {
            margin-top: 25px;
            font-size: 9px;
            color: #7f8c8d;
            text-align: right;
            font-style: italic;
        }
    </style>
</head>

<body>

    <!-- Kop Surat Resmi Pemda Tanggamus -->
    <div class="header-kop">
        <table>
            <tr>
                <td style="width: 15%; text-align: center;">
                    <?php if (!empty($logoBase64)): ?>
                        <!-- Menggunakan string Base64 secara langsung -->
                        <img src="<?= $logoBase64 ?>" class="logo-pemda" alt="Logo Tanggamus">
                    <?php endif; ?>
                </td>
                <td style="width: 85%;" class="instansi-title">
                    <h4>Pemerintah Kabupaten Tanggamus</h4>
                    <h2>Rencana Anggaran dan Pendapatan Belanja Daerah (APBD)</h2>
                    <p>Laporan Rekapitulasi Realisasi Anggaran Berdasarkan Klasifikasi Akun</p>
                    <p><?= $nama_opd ?>
                </td>
            </tr>
        </table>
    </div>

    <!-- Informasi Filter Periode -->
    <div class="meta-info">
        <table>
            <tr>
                <td style="width: 15%;"><strong>Periode Bulan</strong></td>
                <td style="width: 35%;">: <?= !empty($bulan) ? esc($bulan) : 'Semua Bulan' ?></td>
                <td style="width: 15%;"><strong>Tanggal Cetak</strong></td>
                <td style="width: 35%;">: <?= date('d-m-Y H:i:s') ?> WIB</td>
            </tr>
            <tr>
                <td><strong>Klasifikasi</strong></td>
                <td>: <?= esc(strtoupper($akun ?? 'nama_akun')) ?></td>
                <td><strong>Status Dokumen</strong></td>
                <td>: Resmi / Komputerisasi (SiTAPIS-KAB)</td>
            </tr>
        </table>
    </div>

    <!-- Grafik Visualisasi (Chart.js Base64) -->
    <?php if (!empty($chartImage)): ?>
        <div class="chart-container">
            <img src="<?= $chartImage ?>" alt="Grafik Capaian Realisasi">
        </div>
    <?php endif; ?>

    <!-- Tabel Data Rekapitulasi -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 41%;">Klasifikasi</th>
                <th style="width: 18%;" class="text-end">Total Anggaran</th>
                <th style="width: 18%;" class="text-end">Total Realisasi</th>
                <th style="width: 18%;" class="text-center">Capaian (%)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($rekapData)): ?>
                <?php
                $no = 1;
                $grandTotalAnggaran = 0;
                $grandTotalRealisasi = 0;
                foreach ($rekapData as $row):
                    $dataviewKey = $akun ?? 'nama_akun';
                    $label = $row[$dataviewKey] ?? '-';
                    $anggaran = $row['jumlah_total_anggaran'] ?? 0;
                    $realisasi = $row['total_realisasi'] ?? 0;
                    $capaian = $row['capaian_realisasi'] ?? 0;

                    $grandTotalAnggaran += $anggaran;
                    $grandTotalRealisasi += $realisasi;
                ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= esc($label) ?></td>
                        <td class="text-end">Rp <?= number_format($anggaran, 0, ',', '.') ?></td>
                        <td class="text-end">Rp <?= number_format($realisasi, 0, ',', '.') ?></td>
                        <td class="text-center"><?= number_format($capaian, 2, ',', '.') ?>%</td>
                    </tr>
                <?php endforeach; ?>

                <!-- Baris Total Keseluruhan -->
                <?php
                $grandCapaian = ($grandTotalAnggaran > 0) ? ($grandTotalRealisasi / $grandTotalAnggaran) * 100 : 0;
                ?>
                <tr style="background-color: #f2f4f4; font-weight: bold;">
                    <td colspan="2" class="text-end">TOTAL KESELURUHAN:</td>
                    <td class="text-end">Rp <?= number_format($grandTotalAnggaran, 0, ',', '.') ?></td>
                    <td class="text-end">Rp <?= number_format($grandTotalRealisasi, 0, ',', '.') ?></td>
                    <td class="text-center"><?= number_format($grandCapaian, 2, ',', '.') ?>%</td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data rekapitulasi untuk periode ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer-note">
        Dokumen ini dicetak otomatis dari sistem pelaporan SiTAPIS-KAB Kabupaten Tanggamus.
    </div>

</body>

</html>