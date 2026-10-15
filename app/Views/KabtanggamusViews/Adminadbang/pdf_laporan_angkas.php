<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            margin: 20px;
            color: #000;
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

        /* Kop Dokumen */
        .kop-header {
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .kop-header h3 {
            margin: 0;
            font-size: 14pt;
            text-transform: uppercase;
        }

        .kop-header h4 {
            margin: 2px 0;
            font-size: 12pt;
            text-transform: uppercase;
        }

        /* Table Detail */
        .info-table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 4px;
            vertical-align: top;
        }

        /* Table Utama Anggaran Kas */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 10pt;
        }

        .data-table th {
            background-color: #f2f2f2;
            text-transform: uppercase;
        }

        .bg-subtotal {
            background-color: #fafafa;
            font-weight: bold;
        }

        /* Tanda Tangan */
        .ttd-container {
            width: 100%;
            margin-top: 30px;
            page-break-inside: avoid;
        }

        .ttd-box {
            width: 40%;
            float: right;
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- Kop Header -->
    <div class="kop-header text-center">
        <h3>PEMERINTAH PROVINSI</h3>
        <h4>ANGGARAN KAS PERANGKAT DAERAH</h4>
        <p style="margin: 0; font-size: 10pt;">TAHUN ANGGARAN <?= $tahun_anggaran ?></p>
    </div>

    <!-- Informasi SKPD & Sub Kegiatan -->
    <table class="info-table">
        <tr>
            <td style="width: 20%;" class="fw-bold">Perangkat Daerah</td>
            <td style="width: 2%;">:</td>
            <td><?= $perangkat_daerah ?></td>
        </tr>
        <tr>
            <td class="fw-bold">Sub Kegiatan</td>
            <td>:</td>
            <td><?= $sub_kegiatan ?></td>
        </tr>
        <tr>
            <td class="fw-bold">Total Pagu DPA</td>
            <td>:</td>
            <td class="fw-bold">Rp <?= number_format($pagu_anggaran, 0, ',', '.') ?></td>
        </tr>
    </table>

    <!-- Tabel Rincian Anggaran Kas -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 18%;">Triwulan</th>
                <th style="width: 22%;">Bulan</th>
                <th style="width: 30%;">Alokasi Kas (Rp)</th>
                <th style="width: 30%;">Subtotal Triwulan (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $months = [
                1 => ['januari' => 'Januari', 'februari' => 'Februari', 'maret' => 'Maret'],
                2 => ['april' => 'April', 'mei' => 'Mei', 'juni' => 'Juni'],
                3 => ['juli' => 'Juli', 'agustus' => 'Agustus', 'september' => 'September'],
                4 => ['oktober' => 'Oktober', 'november' => 'November', 'desember' => 'Desember']
            ];

            foreach ($months as $tw => $bulanList):
                $subtotalTW = 0;
                $index = 0;
                foreach ($bulanList as $key => $namaBulan):
                    $nilai = $data[$key] ?? 0;
                    $subtotalTW += $nilai;
            ?>
                    <tr>
                        <?php if ($index === 0): ?>
                            <td rowspan="4" class="text-center fw-bold" style="vertical-align: middle;">TRIWULAN <?= $tw ?></td>
                        <?php endif; ?>
                        <td><?= $namaBulan ?></td>
                        <td class="text-end">Rp <?= number_format($nilai, 0, ',', '.') ?></td>
                        <?php if ($index === 0): ?>
                            <td rowspan="3" class="text-end fw-bold" style="vertical-align: middle; background-color: #fafafa;" id="tw-<?= $tw ?>">
                                Rp <?= number_format(
                                        ($data[array_keys($bulanList)[0]] ?? 0) +
                                            ($data[array_keys($bulanList)[1]] ?? 0) +
                                            ($data[array_keys($bulanList)[2]] ?? 0),
                                        0,
                                        ',',
                                        '.'
                                    ) ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php
                    $index++;
                endforeach;
                ?>
                <tr class="bg-subtotal">
                    <td colspan="2" class="text-end fw-bold">TOTAL TRIWULAN <?= $tw ?></td>
                    <td class="text-end fw-bold">Rp <?= number_format($subtotalTW, 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr style="background-color: #e6e6e6; font-weight: bold;">
                <td colspan="2" class="text-center">TOTAL ANGGARAN KAS (TAHUNAN)</td>
                <td class="text-end" colspan="2" style="font-size: 11pt;">
                    Rp <?= number_format($data['total_anggaran_kas'] ?? 0, 0, ',', '.') ?>
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- Tanda Tangan Pejabat -->
    <div class="ttd-container">
        <div class="ttd-box">
            <p><?= date('d F Y') ?></p>
            <p class="fw-bold">Pengguna Anggaran / Pejabat Pembuat Komitmen</p>
            <br><br><br><br>
            <p class="fw-bold" style="text-decoration: underline;">( .................................................... )</p>
            <p>NIP. ....................................................</p>
        </div>
        <div style="clear: both;"></div>
    </div>

</body>

</html>