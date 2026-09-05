<html>

<head>
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
    <i style="font-size:12px;text-align:center;">LAPORAN TRIWULAN <?php //echo ":" . $tw; 
                                                                    ?> MAPPING SUBKEGIATAN PERANGKAT DAERAH
        TERHADAP 33 AGENDA KERJA UTAMA GUBERNUR/WAKIL GUBERNUR LAMPUNG TA <?php //echo $tahun; 
                                                                            ?> <?php //echo ":" . $nmopd; 
                                                                                                    ?></i>
    <p></p>
    <table cellpadding="4" style="text-align:center">
        <thead>

            <tr>
                <th style="font-size:9px; text-align:center; width:4%" rowspan="2"><strong>NO</strong></th>
                <th style="font-size:9px; text-align:center; width:10%" rowspan="2"><strong>Nama Agenda Kerja Utama</strong></th>
                <th style="font-size:9px; text-align:center; width:22%" rowspan="2"><strong>Program/Kegiatan/Sub Kegiatan</strong></th>
                <th style="font-size:9px; text-align:center; width:26%" colspan="3"><strong>ANGGARAN</strong></th>
                <th style="font-size:9px; text-align:center; width:26%" colspan="4"><strong>INDIKATOR KINERJA</strong></th>

                <th style="font-size:9px; text-align:center; width:12%" rowspan="2"><strong>Keterangan</strong></th>

            </tr>
            <tr>
                <th style="font-size:9px; text-align:center; width:9%"><strong>Anggaran Pendukung</strong></th>
                <th style="font-size:9px; text-align:center; width:9%"><strong>Realisasi Anggaran</strong></th>
                <th style="font-size:9px; text-align:center; width:8%"><strong>% Realisasi Anggaran</strong></th>
                <th style="font-size:9px; text-align:center; width:7%"><strong>Target Indikator</strong></th>
                <th style="font-size:9px; text-align:center; width:7%"><strong>Realisasi Indikator</strong></th>
                <th style="font-size:9px; text-align:center; width:6%"><strong>Satuan Indikator</strong></th>
                <th style="font-size:9px; text-align:center; width:6%"><strong>% Capaian</strong></th>

            </tr>
        </thead>
    </table>
    <p>
    <div style="font-size:13px;text-align:centre;" width:20%>
        <table width:20%>
            <tr>
                <td width:20%>Mengetahui dan Menyetujui</td>
            </tr>
            <tr>
                <td width:20%>Kepala <?php //echo $nmopd; 
                                        ?></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td><u><?php // echo $kaopd; 
                        ?></u></td>
            </tr>
            <tr>
                <td><?php // echo "NIP  :" . $nipkaopd; 
                    ?></td>
            </tr>
        </table>
    </div>
</body>

</html>