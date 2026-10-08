<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
        }

        h2,
        h4 {
            text-align: center;
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 5px;
        }

        th {
            background-color: #e2e8f0;
            text-transform: uppercase;
            font-size: 9px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .bg-highlight {
            background-color: #fef08a;
        }

        .inefisien {
            color: #dc2626;
            font-weight: bold;
        }

        .efisien {
            color: #16a34a;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <h2>REKAPITULASI CAPAIAN DAN EFISIENSI PER SKPD</h2>
    <h4>BULAN: <?= esc($bulan) ?></h4>
    <p style="font-style: italic; font-size: 9px;">*Data diurutkan berdasarkan Capaian SRO Terendah</p>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="10%">Kode</th>
                <th>Nama SKPD</th>
                <th width="12%">Anggaran</th>
                <th width="12%">Realisasi</th>
                <th width="5%">SRO</th>
                <th width="6%">SRO Nol</th>
                <th width="10%">Capaian Realisasi</th>
                <th width="10%">Capaian SRO</th>
                <th width="8%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($dataRekap)): ?>
                <tr>
                    <td colspan="10" class="text-center">Data tidak ditemukan.</td>
                </tr>
            <?php else: ?>
                <?php $no = 1;
                foreach ($dataRekap as $row): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="text-center"><?= esc($row['kode']) ?></td>
                        <td><?= esc($row['nama']) ?></td>
                        <td class="text-right">Rp <?= number_format($row['anggaran'], 0, ',', '.') ?></td>
                        <td class="text-right">Rp <?= number_format($row['realisasi'], 0, ',', '.') ?></td>
                        <td class="text-center"><?= number_format($row['sro']) ?></td>
                        <td class="text-center"><?= number_format($row['sro_nol']) ?></td>
                        <td class="text-center"><?= number_format($row['capaian_realisasi'], 2, ',', '.') ?>%</td>
                        <td class="text-center bg-highlight"><?= number_format($row['capaian_sro'], 2, ',', '.') ?>%</td>
                        <td class="text-center <?= $row['status_efisiensi'] == 'Inefisien' ? 'inefisien' : 'efisien' ?>">
                            <?= esc($row['status_efisiensi']) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</body>

</html>