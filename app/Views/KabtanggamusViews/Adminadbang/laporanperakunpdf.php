<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Realisasi APBD</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #333;
        }

        h2,
        h4 {
            text-align: center;
            margin: 0;
        }

        .header {
            margin-bottom: 15px;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        /* Styling untuk penempatan Grafik di PDF */
        .chart-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .chart-container img {
            max-width: 90%;
            height: auto;
            border: 1px solid #ccc;
            padding: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            page-break-inside: auto;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>PEMERINTAH DAERAH</h2>
        <h4>LAPORAN REKAPITULASI REALISASI ANGGARAN APBD</h4>
        <p class="text-center">Periode Bulan: <strong><?= $bulan ?></strong> | Berdasarkan: <strong><?= ucwords(str_replace('_', ' ', $akun)) ?></strong></p>
    </div>

    <!-- Tampilkan Grafik jika data base64 tersedia -->
    <?php if (!empty($chartImage)): ?>
        <div class="chart-container">
            <img src="<?= $chartImage ?>" alt="Grafik Realisasi Anggaran">
        </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th><?= ucwords(str_replace('_', ' ', $akun)) ?></th>
                <th>Total Anggaran (Rp)</th>
                <th>Total Realisasi (Rp)</th>
                <th>Capaian (%)</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($rekap as $row): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= $row[$akun] ?></td>
                    <td class="text-end"><?= number_format($row['jumlah_total_anggaran'], 0, ',', '.') ?></td>
                    <td class="text-end"><?= number_format($row['total_realisasi'], 0, ',', '.') ?></td>
                    <td class="text-center"><?= number_format($row['capaian_realisasi'], 2) ?>%</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>