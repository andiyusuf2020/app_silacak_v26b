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
    <i style="font-size:12px;text-align:center;">LAPORAN REALISASI PENGADAAN BARANG/JASA (PBJ) TA <?= esc($tahunaktif); ?></i>
    <br>
    <i style="font-size:12px;text-align:center;">Perangkat Daerah : <?= esc($datauser['sub_unit']); ?></i>
    <br>
    <?php
    // $this->realisasilrfkrinci = new TaRealisasiRinciModel();
    if ($bulanpilih == '') {
        $bulan = $jadwalaktif['bulan'];
    } else {
        $bulan = $bulanpilih;
        echo 'Realisasi Sampai dengan Bulan :' . esc($bulanpilih);
    }
    ?>
    <br>
    <br>
    <table>
        <tbody>
            <tr>
                <th style="font-size:12px; text-align:center; width:5%;">#</th>
                <th style="font-size:12px; text-align:center; width:15%;">Jenis Pengadaan</th>
                <th style="font-size:12px; text-align:center; width:15%;">Metode Pengadaan</th>
                <th style="font-size:12px; text-align:center; width:30%;">Nama Paket</th>
                <th style="font-size:12px; text-align:center; width:15%;">Nilai Paket</th>
                <th style="font-size:12px; text-align:center; width:10%;">Status Paket(Progres)</th>
                <th style="font-size:12px; text-align:center; width:10%;">Nama Penyedia</th>
            </tr>
            <?php
            foreach ($realisasirup as $key => $value) { ?>
                <tr>
                    <td style="border: 1px solid #000000;font-size:9px; text-align:left; width:5%;">
                        <?= esc($key + 1) ?>
                    </td>
                    <td style="border: 1px solid #000000;font-size:9px; text-align:left; width:15%;">
                        <?= esc($value['Jenis_Pengadaan']) ?>
                    </td>
                    <td style="border: 1px solid #000000;font-size:9px; text-align:left; width:15%;">
                        <?= esc($value['Metode_Pengadaan']) ?>
                    </td>
                    <td style="border: 1px solid #000000;font-size:9px; text-align:left; width:30%;">
                        <?= esc($value['Nama_Paket']) ?>
                    </td>
                    <td style="border: 1px solid #000000;font-size:9px; text-align:right; width:15%;">
                        <?= esc(number_format($value['Nilai_PDN'], 0, ',', '.')) ?>
                    </td>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:left; width:10%;">
                        <?= esc($value['Status_Paket']) ?>
                    </td>
                    <td style="border: 1px solid #000000; font-size:9px; text-align:left; width:10%;">
                        <?= esc($value['Nama_Penyedia']) ?>
                    </td>
                </tr>
            <?php }

            ?>
            <tr>
                <td style="border: 1px solid #000000;font-size:9px; text-align:left; width:5%;">
                    #
                </td>
                <td style="border: 1px solid #000000;font-size:9px; text-align:left; width:60%;" colspan="4">
                    JUMLAH
                </td>
                <td style="border: 1px solid #000000;font-size:9px; text-align:right; width:15%;">
                    <strong>
                        <?php
                        $jumlahAnggaran = array_sum(array_column($realisasirup, 'Nilai_PDN'));
                        echo esc(number_format($jumlahAnggaran, 0, ',', '.'));
                        ?>
                    </strong>

                </td>
                <td style="border: 1px solid #000000; font-size:9px; text-align:right; width:20%;">
                </td>
            </tr>
        </tbody>
    </table>
    <p>
    <div style="font-size:13px;text-align:left;" width:20%>
        <table width:20%>
            <tr>
                <td width:20%>Mengetahui</td>
            </tr>
            <tr>
                <td width:20%><?= esc($datauser['jabatan']) ?></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td><u><?= esc($datauser['nama']) ?></u></td>
            </tr>
            <tr>
                <td><?= esc($datauser['nip']) ?></td>
            </tr>
        </table>
    </div>
</body>

</html>