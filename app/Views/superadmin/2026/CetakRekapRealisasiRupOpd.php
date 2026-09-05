<!DOCTYPE html>
<html>

<head>
    <title>Cetak PDF</title>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        th {
            border: 1px solid #000000;
            text-align: center;
            height: 15px;
            margin: 1px;
        }
    </style>
</head>

<body>
    <?php

    use App\Models\RupModel\RealRupModel;

    $this->realrup = new RealRupModel();

    ?>

    <i style="font-size:12px;text-align:center;">REKAPITULASI REALISASI PENGADAAN BARANG/JASA TA <?= esc($tahunaktif); ?></i>
    <br>
    <i style="font-size:12px;text-align:center;"></i>
    <br>
    Sampai Dengan <?= esc($dataruppermetode[0]['create_at'] ?? null) ?>
    <br>
    <table>
        <tbody>
            <tr>
                <th style="font-size:10px; text-align:center; width:4%;">#</th>
                <th style="font-size:10px; text-align:center; width:6%;">Tahun</th>
                <th style="font-size:10px; text-align:center; width:15%;">Metode Pengadaan</th>
                <th style="font-size:10px; text-align:center; width:8%;">Jumlah Paket</th>
                <th style="font-size:10px; text-align:center; width:15%;">Nilai Paket (Rp)</th>
                <th style="font-size:10px; text-align:center; width:8%;">Jumlah Realisasi Paket (selesai)</th>
                <th style="font-size:10px; text-align:center; width:8%;">Jumlah Realisasi Paket (proses)</th>
                <th style="font-size:10px; text-align:center; width:15%;">Total Realisasi Paket</th>
                <th style="font-size:10px; text-align:center; width:15%;">Nilai Realisasi Paket (Rp)</th>
                <th style="font-size:10px; text-align:center; width:8%;">Capaian %</th>
            </tr>
            <?php foreach ($dataruppermetode as $key => $value) { ?>
                <tr>
                    <td style="border: 1px solid #000000;font-size:10px; text-align:center; width:4%;"><?= esc($key + 1) ?> </td>
                    <td style="border: 1px solid #000000;font-size:10px; text-align:center; width:6%;"><?= esc($value['tahun']) ?></td>
                    <td style="border: 1px solid #000000;font-size:10px; text-align:center; width:15%;"> <?= esc($value['Metode_Pengadaan']) ?> </td>
                    <td style="border: 1px solid #000000;font-size:10px; text-align:center; width:8%;"> <?= esc($value['jumlah_paket']) ?> </td>
                    <td style="border: 1px solid #000000;font-size:10px; text-align:right; width:15%;"> <?= esc(number_format($value['total_anggaran'], 2, ',', '.')) ?> </td>
                    <td style="border: 1px solid #000000;font-size:10px; text-align:center; width:8%;">
                        <?php
                        $realisasiSelesai = $this->realrup->getRealisasiRupByMetodePengadaanSelesai($value['tahun'], null, $value['Metode_Pengadaan'], $value['Status_Paket'] = 'SELESAI' and $value['Status_Paket'] = 'COMPLETED');
                        echo esc($realisasiSelesai['jumlah_paket'] ?? null);
                        ?>
                    </td>
                    <td style="border: 1px solid #000000;font-size:10px; text-align:center; width:8%;">
                        <?php
                        $realisasiProses = $this->realrup->getRealisasiRupByMetodePengadaanProses($value['tahun'], null, $value['Metode_Pengadaan'], $value['Status_Paket'] = 'PROSES' and $value['Status_Paket'] = 'ON PROGRESS');
                        echo esc($realisasiProses['jumlah_paket'] ?? null);
                        ?>
                    </td>
                    <td style="border: 1px solid #000000;font-size:10px; text-align:center; width:15%;">
                        <?php
                        $totalRealisasi = ($realisasiSelesai['jumlah_paket'] ?? 0) + ($realisasiProses['jumlah_paket'] ?? 0);
                        echo esc($totalRealisasi);
                        ?>
                    </td>
                    <td style="border: 1px solid #000000;font-size:10px; text-align:right; width:15%;">
                        <?php
                        $realisasi = $this->realrup->getRealisasiRupByMetodePengadaan($value['tahun'], null, $value['Metode_Pengadaan']);
                        echo esc(number_format($realisasi['total_pdn'] ?? null, 2, ',', '.'));
                        ?>
                    </td>
                    <td style="border: 1px solid #000000;font-size:10px; text-align:center; width:8%;">
                        <?php
                        $realisasi = $this->realrup->getRealisasiRupByMetodePengadaan($value['tahun'], null, $value['Metode_Pengadaan']);
                        $capaian = ($realisasi['total_pdn'] ?? 0) / ($value['total_anggaran'] ?? 1) * 100;
                        echo esc(number_format($capaian, 2, ',', '.')) . ' %';
                        ?>
                    </td>
                </tr>
            <?php } ?>

        </tbody>
    </table>
</body>

</html>