<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Rincian Anggaran Kas Per Bulan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 9px;
            color: #333;
        }

        h3,
        h4 {
            text-align: center;
            margin: 0;
        }

        .header {
            margin-bottom: 15px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
            padding: 4px;
        }

        th {
            background-color: #f4f4f4;
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h3>PEMERINTAH KABUPATEN TANGGAMUS</h3>
        <h4>LAPORAN RINCIAN ANGGARAN KAS PERANGKAT DAERAH BULANAN</h4>
        <hr>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Sub Unit / Kegiatan</th>
                <th>Tahun</th>
                <th>Pagu</th>
                <th>Jan</th>
                <th>Feb</th>
                <th>Mar</th>
                <th>Apr</th>
                <th>Mei</th>
                <th>Jun</th>
                <th>Jul</th>
                <th>Agt</th>
                <th>Sep</th>
                <th>Okt</th>
                <th>Nov</th>
                <th>Des</th>
                <th>Total Kas</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($anggaran as $row): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td>
                        <b><?= $row['sub_unit'] ?></b><br>
                        <small>Kegiatan: <?= $row['sub_kegiatan'] ?></small>
                    </td>
                    <td class="text-center"><?= $row['tahun_anggaran'] ?></td>
                    <td class="text-end">Rp <?= number_format($row['pagu_anggaran'], 0, ',', '.') ?></td>
                    <td class="text-end">Rp <?= number_format($row['januari'], 0, ',', '.') ?></td>
                    <td class="text-end">Rp <?= number_format($row['februari'], 0, ',', '.') ?></td>
                    <td class="text-end">Rp <?= number_format($row['maret'], 0, ',', '.') ?></td>
                    <td class="text-end">Rp <?= number_format($row['april'], 0, ',', '.') ?></td>
                    <td class="text-end">Rp <?= number_format($row['mei'], 0, ',', '.') ?></td>
                    <td class="text-end">Rp <?= number_format($row['juni'], 0, ',', '.') ?></td>
                    <td class="text-end">Rp <?= number_format($row['juli'], 0, ',', '.') ?></td>
                    <td class="text-end">Rp <?= number_format($row['agustus'], 0, ',', '.') ?></td>
                    <td class="text-end">Rp <?= number_format($row['september'], 0, ',', '.') ?></td>
                    <td class="text-end">Rp <?= number_format($row['oktober'], 0, ',', '.') ?></td>
                    <td class="text-end">Rp <?= number_format($row['november'], 0, ',', '.') ?></td>
                    <td class="text-end">Rp <?= number_format($row['desember'], 0, ',', '.') ?></td>
                    <td class="text-end"><b>Rp <?= number_format($row['total_anggaran_kas'], 0, ',', '.') ?></b></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>