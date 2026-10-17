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

        .logo-container {
            width: 80px;
            text-align: center;
        }

        .logo-container img {
            width: 70px;
            height: auto;
        }
    </style>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th class="logo-container" colspan="2">
                    <?php if (!empty($logo)): ?>
                        <img src="<?= $logo; ?>" alt="Logo Pemda">
                    <?php endif; ?>
                </th>
                <th class="header-text" colspan="8">
                    <h2>PEMERINTAH <?= strtoupper(session()->get('namawilayah')) ?></h2>
                    <h2>BAGIAN ADMINISTRASI PEMBANGUNAN SEKRETARIAT DAERAH <?= strtoupper(session()->get('namawilayah')) ?></h2>
                    <h2>REKAPITULASI CAPAIAN KINERJA DAN EFISIENSI ANGGARAN PER SKPD</h2>
                    <h4>SAMPAI BULAN: <?= strtoupper($bulan_nama) ?></h4>
                    <!-- <p style="font-style: italic; font-size: 9px;">*Data diurutkan berdasarkan Capaian Kinerja Output Belanja Tertinggi</p> -->
                </th>
            </tr>
            <tr>
                <th width="3%">NO</th>
                <th width="10%">KODE</th>
                <th>NAMA SKPD</th>
                <th width="12%">ANGGARAN (Rp)</th>
                <th width="12%">REALISASI (Rp)</th>
                <th width="5%">JUMLAH OUTPUT<br> SUB-RINCIAN-OBJEK<br> BELANJA</th>
                <th width="6%">JUMLAH OUTPUT <br>SUB-RINCIAN-OBJEK <br>BELANJA YANG <br>TERLAKSANA</th>
                <th width="10%">CAPAIAN <br>REALISASI <br>ANGGARAN (%)</th>
                <th width="10%">CAPAIAN <br>KINERJA <br>OUTPUT BELANJA</th>
                <th width="8%">KATEGORI EFISIENSI</th>
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
    <p style="font-style: italic; font-size: 9px;">*Data diurutkan berdasarkan Capaian Kinerja Output Belanja Tertinggi</p>

</body>

</html>