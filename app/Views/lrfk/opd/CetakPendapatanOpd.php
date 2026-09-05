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
    <i style="font-size:12px;text-align:center;">
        <br> LAPORAN REALISASI PENDAPATAN PERANGKAT DAERAH <br>
        PENGELOLA PENDAPATAN APBD TA <?= esc($jadwalaktif['tahun']) ?>
        <br>
        s.d Bulan <?= esc($jadwalaktif['bulan']) ?>
    </i>
    <br>
    <?= esc('Nama Perangkat Daerah: ' . $nm_opd) ?>
    <br><br>
    <table cellpadding="5" style="text-align:center">
        <thead>
            <tr>
                <th style="font-size:12px; text-align:center; width:4%">#</th>
                <th style="font-size:12px; text-align:center; width:40%">JENIS PENDAPATAN</th>
                <th style="font-size:12px; text-align:center; width:22%">TARGET TAHUN <?= esc($jadwalaktif['tahun']) ?></th>
                <th style="font-size:12px; text-align:center; width:26%">REALISASI PENDAPATAN s.d BULAN <?= esc($jadwalaktif['bulan']) ?></th>
                <th style="font-size:12px; text-align:center; width:10%">% REALISASI</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border: 1px solid #000000; font-size:12px; text-align:left; width:4%"></td>
                <td style="border: 1px solid #000000; font-size:12px; text-align:left; width:40%">
                    <?= esc($kdPajak . $pajak) ?></td>
                <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:22%">
                    <?php if (!$opdPajak) { ?>
                    <?php } else { ?>
                        <?= esc('Rp ' . number_format($opdPajak, 2, ',', '.')) ?>
                    <?php } ?>
                </td>
                <?php if (!$opdPajak) { ?>
                    <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:26%">
                    </td>
                    <td style="border: 1px solid #000000; font-size:12px; text-align:center; width:10%">
                    </td>
                <?php } else { ?>
                    <?php if ($bulanpilih) { ?>
                        <?php if (!$RPendapatanBlnPajakAktif) { ?>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:26%">
                                <?= esc('Rp ' . number_format('0', 2, ',', '.')) ?>
                            </td>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:center; width:10%">
                                <?= esc(number_format('0', 2, ',', '.')) ?>
                            </td>
                        <?php } else { ?>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:26%">

                                <?= esc('Rp ' . number_format($RPendapatanBlnPajakAktif['realisasi'], 2, ',', '.')) ?>
                            </td>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:center; width:10%">

                                <?php $persentase =  $RPendapatanBlnPajakAktif['realisasi'] / $opdPajak * 100;
                                echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                            </td>
                        <?php }
                    } else { ?>
                        <?php if (!$RPendapatanBlnPajakAktif) { ?>

                        <?php } else { ?>
                            <?= esc('Rp ' . number_format($RPendapatanBlnPajakAktif['realisasi'], 2, ',', '.')) ?>
                            <?php $persentase =  $RPendapatanBlnPajakAktif['realisasi'] / $opdPajak * 100;
                            echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                    <?php }
                    }
                    ?>
                <?php } ?>

            </tr>
            <tr>
                <td style="border: 1px solid #000000; font-size:12px; text-align:left; width:4%"></td>
                <td style="border: 1px solid #000000; font-size:12px; text-align:left; width:40%"><?= esc($kdRetribusi . $retribusi) ?></td>
                <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:22%">
                    <?php if (!$opdRetribusi) { ?>
                    <?php } else { ?>
                        <?= esc('Rp ' . number_format($opdRetribusi, 2, ',', '.')) ?>
                    <?php } ?>
                </td>
                <?php if (!$opdRetribusi) { ?>
                    <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:26%">
                    </td>
                    <td style="border: 1px solid #000000; font-size:12px; text-align:center; width:10%">
                    </td>

                <?php } else { ?>
                    <?php if ($bulanpilih) { ?>
                        <?php if (!$RPendapatanBlnRetribusiAktif) { ?>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:26%">

                                <?= esc('Rp ' . number_format('0', 2, ',', '.')) ?>
                            </td>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:center; width:10%">

                                <?= esc(number_format('0', 2, ',', '.')) ?>
                            </td>

                        <?php } else { ?>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:26%">

                                <?= esc('Rp ' . number_format($RPendapatanBlnRetribusiAktif['realisasi'], 2, ',', '.')) ?>
                            </td>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:center; width:10%">

                                <?php $persentase =  $RPendapatanBlnRetribusiAktif['realisasi'] / $opdRetribusi * 100;
                                echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                            </td>
                        <?php }
                    } else { ?>
                        <?php if (!$RPendapatanBlnRetribusiAktif) { ?>
                        <?php } else { ?>
                            <?= esc('Rp ' . number_format($RPendapatanBlnRetribusiAktif['realisasi'], 2, ',', '.')) ?>
                            <?php $persentase =  $RPendapatanBlnRetribusiAktif['realisasi'] / $opdRetribusi * 100;
                            echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                    <?php }
                    }
                    ?>
                <?php } ?>

            </tr>
            <tr>
                <td style="border: 1px solid #000000; font-size:12px; text-align:left; width:4%"></td>
                <td style="border: 1px solid #000000; font-size:12px; text-align:left; width:40%">
                    <?= esc($kdKekayaan . $kekayaan) ?></td>
                <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:22%">
                    <?php if (!$opdKekayaan) { ?>
                    <?php } else { ?>
                        <?= esc('Rp ' . number_format($opdKekayaan, 2, ',', '.')) ?>
                    <?php } ?>
                </td>
                <?php if (!$opdKekayaan) { ?>
                    <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:26%">
                    </td>
                    <td style="border: 1px solid #000000; font-size:12px; text-align:center; width:10%">
                    </td>
                <?php } else { ?>
                    <?php if ($bulanpilih) { ?>
                        <?php if (!$RPendapatanBlnKekayaanAktif) { ?>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:26%">

                                <?= esc('Rp ' . number_format('0', 2, ',', '.')) ?>
                            </td>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:center; width:10%">

                                <?= esc(number_format('0', 2, ',', '.')) ?>
                            </td>

                        <?php } else { ?>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:26%">
                                <?= esc('Rp ' . number_format($RPendapatanBlnKekayaanAktif['realisasi'], 2, ',', '.')) ?>
                            </td>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:center; width:10%">
                                <?php $persentase =  $RPendapatanBlnKekayaanAktif['realisasi'] / $opdKekayaan * 100;
                                echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                            </td>
                        <?php }
                    } else { ?>
                        <?php if (!$RPendapatanBlnKekayaanAktif) { ?>
                        <?php } else { ?>
                            <?= esc('Rp ' . number_format($RPendapatanBlnKekayaanAktif['realisasi'], 2, ',', '.')) ?>
                            <?php $persentase =  $RPendapatanBlnKekayaanAktif['realisasi'] / $opdKekayaan * 100;
                            echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                    <?php }
                    }
                    ?>
                <?php } ?>

            </tr>
            <tr>
                <td style="border: 1px solid #000000; font-size:12px; text-align:left; width:4%"></td>
                <td style="border: 1px solid #000000; font-size:12px; text-align:left; width:40%">
                    <?= esc($kdLainlain . $lainlain) ?>
                </td>
                <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:22%">
                    <?php if (!$opdLainlain) { ?>
                    <?php } else { ?>
                        <?= esc('Rp ' . number_format($opdLainlain, 2, ',', '.')) ?>
                    <?php } ?>
                </td>
                <?php if (!$opdLainlain) { ?>
                    <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:26%">
                    </td>
                    <td style="border: 1px solid #000000; font-size:12px; text-align:center; width:10%">
                    </td>
                <?php } else { ?>
                    <?php if ($bulanpilih) { ?>
                        <?php if (!$RPendapatanBlnLainlainAktif) { ?>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:26%">

                                <?= esc('Rp ' . number_format('0', 2, ',', '.')) ?>
                            </td>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:center; width:10%">

                                <?= esc(number_format('0', 2, ',', '.')) ?>
                            </td>

                        <?php } else { ?>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:26%">
                                <?= esc('Rp ' . number_format($RPendapatanBlnLainlainAktif['realisasi'], 2, ',', '.')) ?>
                            </td>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:center; width:10%">
                                <?php $persentase =  $RPendapatanBlnLainlainAktif['realisasi'] / $opdLainlain * 100;
                                echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                            </td>
                        <?php }
                    } else { ?>
                        <?php if (!$RPendapatanBlnLainlainAktif) { ?>
                        <?php } else { ?>
                            <?= esc('Rp ' . number_format($RPendapatanBlnLainlainAktif['realisasi'], 2, ',', '.')) ?>
                            <?php $persentase =  $RPendapatanBlnLainlainAktif['realisasi'] / $opdLainlain * 100;
                            echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                    <?php }
                    }
                    ?>
                <?php } ?>

            </tr>
            <tr>
                <td style="border: 1px solid #000000; font-size:12px; text-align:left; width:4%"></td>
                <td style="border: 1px solid #000000; font-size:12px; text-align:left; width:40%">
                    <?= esc($kdTfPusat . $tfpusat) ?>
                </td>
                <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:22%">
                    <?php if (!$opdtfpusat) { ?>
                    <?php } else { ?>
                        <?= esc('Rp ' . number_format($opdtfpusat, 2, ',', '.')) ?>
                    <?php } ?>
                </td>
                <?php if (!$opdtfpusat) { ?>
                    <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:26%">
                    </td>
                    <td style="border: 1px solid #000000; font-size:12px; text-align:center; width:10%">
                    </td>
                <?php } else { ?>
                    <?php if ($bulanpilih) { ?>
                        <?php if (!$RPendapatanBlntfpusatAktif) { ?>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:26%">

                                <?= esc('Rp ' . number_format('0', 2, ',', '.')) ?>
                            </td>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:center; width:10%">

                                <?= esc(number_format('0', 2, ',', '.')) ?>
                            </td>

                        <?php } else { ?>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:26%">
                                <?= esc('Rp ' . number_format($RPendapatanBlntfpusatAktif['realisasi'], 2, ',', '.')) ?>
                            </td>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:center; width:10%">
                                <?php $persentase =  $RPendapatanBlntfpusatAktif['realisasi'] / $opdtfpusat * 100;
                                echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                            </td>
                        <?php }
                    } else { ?>
                        <?php if (!$RPendapatanBlntfpusatAktif) { ?>
                        <?php } else { ?>
                            <?= esc('Rp ' . number_format($RPendapatanBlntfpusatAktif['realisasi'], 2, ',', '.')) ?>
                            <?php $persentase =  $RPendapatanBlntfpusatAktif['realisasi'] / $opdtfpusat * 100;
                            echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                    <?php }
                    }
                    ?>
                <?php } ?>

            </tr>
            <tr>
                <td style="border: 1px solid #000000; font-size:12px; text-align:left; width:4%"></td>
                <td style="border: 1px solid #000000; font-size:12px; text-align:left; width:40%">
                    <?= esc($kdTfDaerah . $tfdaerah) ?>
                </td>
                <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:22%">
                    <?php if (!$opdtfdaerah) { ?>
                    <?php } else { ?>
                        <?= esc('Rp ' . number_format($opdtfpusat, 2, ',', '.')) ?>
                    <?php } ?>
                </td>
                <?php if (!$opdtfdaerah) { ?>
                    <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:26%">
                    </td>
                    <td style="border: 1px solid #000000; font-size:12px; text-align:center; width:10%">
                    </td>
                <?php } else { ?>
                    <?php if ($bulanpilih) { ?>
                        <?php if (!$RPendapatanBlntfdaerahAktif) { ?>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:26%">

                                <?= esc('Rp ' . number_format('0', 2, ',', '.')) ?>
                            </td>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:center; width:10%">

                                <?= esc(number_format('0', 2, ',', '.')) ?>
                            </td>

                        <?php } else { ?>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:26%">
                                <?= esc('Rp ' . number_format($RPendapatanBlntfdaerahAktif['realisasi'], 2, ',', '.')) ?>
                            </td>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:center; width:10%">
                                <?php $persentase =  $RPendapatanBlntfdaerahAktif['realisasi'] / $opdtfdaerah * 100;
                                echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                            </td>
                        <?php }
                    } else { ?>
                        <?php if (!$RPendapatanBlntfdaerahAktif) { ?>
                        <?php } else { ?>
                            <?= esc('Rp ' . number_format($RPendapatanBlntfdaerahAktif['realisasi'], 2, ',', '.')) ?>
                            <?php $persentase =  $RPendapatanBlntfdaerahAktif['realisasi'] / $opdtfdaerah * 100;
                            echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                    <?php }
                    }
                    ?>
                <?php } ?>

            </tr>
            <tr>
                <td style="border: 1px solid #000000; font-size:12px; text-align:left; width:4%"></td>
                <td style="border: 1px solid #000000; font-size:12px; text-align:left; width:40%">
                    <?= esc($kdHibah . $hibah) ?>
                </td>
                <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:22%">
                    <?php if (!$opdhibah) { ?>
                    <?php } else { ?>
                        <?= esc('Rp ' . number_format($opdhibah, 2, ',', '.')) ?>
                    <?php } ?>
                </td>
                <?php if (!$opdhibah) { ?>
                    <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:26%">
                    </td>
                    <td style="border: 1px solid #000000; font-size:12px; text-align:center; width:10%">
                    </td>
                <?php } else { ?>
                    <?php if ($bulanpilih) { ?>
                        <?php if (!$RPendapatanBlnhibahAktif) { ?>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:26%">

                                <?= esc('Rp ' . number_format('0', 2, ',', '.')) ?>
                            </td>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:center; width:10%">

                                <?= esc(number_format('0', 2, ',', '.')) ?>
                            </td>

                        <?php } else { ?>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:right; width:26%">
                                <?= esc('Rp ' . number_format($RPendapatanBlnhibahAktif['realisasi'], 2, ',', '.')) ?>
                            </td>
                            <td style="border: 1px solid #000000; font-size:12px; text-align:center; width:10%">
                                <?php $persentase =  $RPendapatanBlnhibahAktif['realisasi'] / $opdhibah * 100;
                                echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                            </td>
                        <?php }
                    } else { ?>
                        <?php if (!$RPendapatanBlnhibahAktif) { ?>
                        <?php } else { ?>
                            <?= esc('Rp ' . number_format($RPendapatanBlnhibahAktif['realisasi'], 2, ',', '.')) ?>
                            <?php $persentase =  $RPendapatanBlnhibahAktif['realisasi'] / $opdhibah * 100;
                            echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                    <?php }
                    }
                    ?>
                <?php } ?>

            </tr>
        </tbody>
    </table>
    <br>
    <div style="font-size:13px;text-align:centre;" width:20%>
        <table width:20%>
            <tr>
                <td width:20%>Mengetahui</td>
            </tr>
            <tr>
                <td width:20%>Kepala <?= esc($nm_opd) ?></td>
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