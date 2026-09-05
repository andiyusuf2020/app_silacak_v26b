<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<!--begin::Container-->
<div class="container-fluid">

    <div class="card card-success card-outline mb-4">
        <!--begin::Header-->
        <div class="card-header">
            <div class="card-title"><?= esc($subtitlepage . $datauser['sub_unit']) ?></div>
        </div>
        <div class="alert alert-danger" role="alert">
            <ul>
                Jadwal input data yang aktif saat ini adalah Bulan : <?= $jadwalaktif['bulan'] ?>
            </ul>
        </div>
        <?php if (session()->getFlashdata('message')): ?>
            <div class="alert alert-primary" role="alert">
                <ul>
                    <p><?= session()->getFlashdata('message') ?></p>
                </ul>
            </div>
        <?php endif; ?>
        <?php if (session()->has('errors')): ?>
            <div class="alert alert-danger" role="alert">
                <ul>
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <!--end::Header-->
        <!--begin::Body-->
        <div class="card-body">
            <?php
            if (!$datapendapatanopd) { ?>
                <div class="alert alert-danger" role="alert">
                    <ul>
                        <?= esc('Perangkat Daerah anda tidak Mengelola Pendapatan Daerah') ?>
                    </ul>
                </div>
            <?php } else { ?>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pilih Bulan Data atau
                            <a href="<?= base_url('lrfkopd/pendapatan?page=index');
                                        ?>">
                                Total Realisasi
                            </a>
                        </h3>
                    </div>
                    <div class="card-body">
                        <ul class="pagination pagination-month justify-content-center">
                            <li class="page-item"><a class="page-link" href="#">«</a></li>
                            <?php
                            foreach ($datajadwal as $key => $data) { ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= hash_url('lrfkopd/pendapatan/', ['page' => 'datapendapatan', 'action' => 'cek', 'bulan' => $data['bulan']]);
                                                                ?>">
                                        <p class="page-month"><?= esc($data['bulan']) ?></p>
                                        <p class="page-year"><?= esc($tahunaktif) ?></p>
                                    </a>
                                </li>
                            <?php
                            }
                            if (!$bulanpilih) {
                                $blncetak = $jadwalaktif['bulan'];
                            } else {
                                $blncetak = $bulanpilih;
                            }

                            ?>
                            <li class="page-item"><a class="page-link" href="#">»</a></li>
                        </ul>
                    </div>
                </div>
                <br>
                <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                'page' => 'datapendapatan',
                                'action' => 'cetak',
                                'bulan' => $blncetak,
                                'kdU' => $kd_skpd
                            ]);
                            ?>" target='blank'>
                    <button type="button" class="btn btn-outline-primary mb-2">Cetak Realisasi Pendapatan</button>
                </a>
                <br>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Rekening Pendapatan</th>
                            <th>Target/Pagu Pendapatan</th>
                            <?php if ($bulanpilih) { ?>
                                <th>Realisasi Pendapatan s/d Bulan <?= esc($bulanpilih) ?></th>
                            <?php } else { ?>
                                <th>Realisasi Pendapatan s/d Bulan <?= esc($jadwalaktif['bulan']) ?></th>
                            <?php } ?>
                            <th style="width: 40px">% Realisasi</th>
                            <th style="width: 40px">Label</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="align-middle">
                            <td></td>
                            <td><?= esc($kdPajak . $pajak) ?></td>
                            <td>
                                <?php if (!$opdPajak) { ?>
                                <?php } else { ?>
                                    <?= esc('Rp ' . number_format($opdPajak, 2, ',', '.')) ?>
                                <?php } ?>
                            </td>
                            <?php if (!$opdPajak) { ?>
                                <td></td>
                                <td></td>
                            <?php } else { ?>
                                <?php if ($bulanpilih) { ?>
                                    <?php if (!$RPendapatanBlnPajakAktif) { ?>
                                        <td>
                                            <?= esc('Rp ' . number_format('0', 2, ',', '.')) ?>
                                        </td>
                                        <td>
                                            <?= esc(number_format('0', 2, ',', '.')) ?>
                                        </td>
                                    <?php } else { ?>
                                        <td> <?= esc('Rp ' . number_format($RPendapatanBlnPajakAktif['realisasi'], 2, ',', '.')) ?>
                                        </td>
                                        <td>
                                            <?php $persentase =  $RPendapatanBlnPajakAktif['realisasi'] / $opdPajak * 100;
                                            echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                                        </td>
                                    <?php }
                                } else { ?>
                                    <?php if (!$RPendapatanBlnPajakAktif) { ?>
                                        <td></td>
                                        <td></td>
                                    <?php } else { ?>
                                        <td>
                                            <?= esc('Rp ' . number_format($RPendapatanBlnPajakAktif['realisasi'], 2, ',', '.')) ?>
                                        </td>
                                        <td>
                                            <?php $persentase =  $RPendapatanBlnPajakAktif['realisasi'] / $opdPajak * 100;
                                            echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                                        </td>
                                <?php }
                                }
                                ?>
                            <?php } ?>
                            <td>
                                <?php if (!$opdPajak) { ?>
                                <?php } else { ?>
                                    <?php if (!$bulanpilih) { ?>
                                        <?php if (!$RPendapatanBlnPajakAktif) { ?>
                                            <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                'page' => 'datapendapatan',
                                                                'action' => 'inputpajak',
                                                                'kdRek' => $kdPajak,
                                                                'kdU' => $kd_skpd
                                                            ]);
                                                            ?>" <button type="button" class="btn btn-outline-primary">Input</button></a>
                                            </div>
                                        <?php } else { ?>
                                            <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                'page' => 'datapendapatan',
                                                                'action' => 'ubahpajak',
                                                                'kdRek' => $kdPajak,
                                                                'id' => $RPendapatanBlnPajakAktif['id']
                                                            ]);
                                                            ?>"
                                                    <button type="button" class="btn btn-outline-primary">Ubah<br>/Lihat</button></a>
                                                <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                'page' => 'datapendapatan',
                                                                'action' => 'hapuspajak',
                                                                'kdRek' => $kdPajak,
                                                                'id' => $RPendapatanBlnPajakAktif['id']
                                                            ]);
                                                            ?>"
                                                    <button type="button" class="btn btn-outline-primary">Hapus</button></a>
                                            </div>
                                            <div
                                                class="btn-group mb-2"
                                                role="group"
                                                aria-label="Basic checkbox toggle button group">
                                            <?php }
                                    } else { ?>
                                            <?php if ($bulanpilih == $jadwalaktif['bulan']) { ?>
                                                <?php if (!$RPendapatanBlnPajakAktif) { ?>
                                                    <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                        <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                        'page' => 'datapendapatan',
                                                                        'action' => 'inputpajak',
                                                                        'kdRek' => $kdPajak,
                                                                        'kdU' => $kd_skpd
                                                                    ]);
                                                                    ?>" <button type="button" class="btn btn-outline-primary">Input</button></a>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                        <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                        'page' => 'datapendapatan',
                                                                        'action' => 'ubahpajak',
                                                                        'kdRek' => $kdPajak,
                                                                        'id' => $RPendapatanBlnPajakAktif['id']
                                                                    ]);
                                                                    ?>"
                                                            <button type="button" class="btn btn-outline-primary">Ubah<br>/Lihat</button></a>
                                                        <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                        'page' => 'datapendapatan',
                                                                        'action' => 'hapuspajak',
                                                                        'kdRek' => $kdPajak,
                                                                        'id' => $RPendapatanBlnPajakAktif['id']
                                                                    ]);
                                                                    ?>"
                                                            <button type="button" class="btn btn-outline-primary">Hapus</button></a>
                                                    </div>
                                                    <div
                                                        class="btn-group mb-2"
                                                        role="group"
                                                        aria-label="Basic checkbox toggle button group">
                                            <?php  }
                                            }
                                        } ?>
                                        <?php } ?>
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <td></td>
                            <td><?= esc($kdRetribusi . $retribusi) ?></td>
                            <td>
                                <?php if (!$opdRetribusi) { ?>
                                <?php } else { ?>
                                    <?= esc('Rp ' . number_format($opdRetribusi, 2, ',', '.')) ?> <?php } ?>
                            </td>
                            <?php if (!$opdRetribusi) { ?>
                                <td></td>
                                <td></td>
                            <?php } else { ?>
                                <?php if ($bulanpilih) { ?>
                                    <?php if (!$RPendapatanBlnRetribusiAktif) { ?>
                                        <td>
                                            <?= esc('Rp ' . number_format('0', 2, ',', '.')) ?>
                                        </td>
                                        <td>
                                            <?= esc(number_format('0', 2, ',', '.')) ?>
                                        </td>
                                    <?php } else { ?>
                                        <td> <?= esc('Rp ' . number_format($RPendapatanBlnRetribusiAktif['realisasi'], 2, ',', '.')) ?>
                                        </td>
                                        <td>
                                            <?php $persentase =  $RPendapatanBlnRetribusiAktif['realisasi'] / $opdRetribusi * 100;
                                            echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                                        </td>
                                    <?php }
                                } else { ?>
                                    <?php if (!$RPendapatanBlnRetribusiAktif) { ?>
                                        <td></td>
                                        <td></td>
                                    <?php } else { ?>
                                        <td>
                                            <?= esc('Rp ' . number_format($RPendapatanBlnRetribusiAktif['realisasi'], 2, ',', '.')) ?>
                                        </td>
                                        <td>
                                            <?php
                                            $persentase =  $RPendapatanBlnRetribusiAktif['realisasi'] / $opdRetribusi * 100;
                                            echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                                        </td>
                                <?php }
                                }
                                ?>
                            <?php } ?>
                            <td>
                                <?php if (!$opdRetribusi) { ?>
                                <?php } else { ?>
                                    <?php if (!$bulanpilih) { ?>
                                        <?php if (!$RPendapatanBlnRetribusiAktif) { ?>
                                            <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                'page' => 'datapendapatan',
                                                                'action' => 'inputpajak',
                                                                'kdRek' => $kdRetribusi,
                                                                'kdU' => $kd_skpd
                                                            ]);
                                                            ?>" <button type="button" class="btn btn-outline-primary">Input</button></a>
                                            </div>
                                        <?php } else { ?>
                                            <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                'page' => 'datapendapatan',
                                                                'action' => 'ubahpajak',
                                                                'kdRek' => $kdRetribusi,
                                                                'id' => $RPendapatanBlnRetribusiAktif['id']
                                                            ]);
                                                            ?>"
                                                    <button type="button" class="btn btn-outline-primary">Ubah<br>/Lihat</button></a>
                                                <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                'page' => 'datapendapatan',
                                                                'action' => 'hapuspajak',
                                                                'kdRek' => $kdRetribusi,
                                                                'id' => $RPendapatanBlnRetribusiAktif['id']
                                                            ]);
                                                            ?>"
                                                    <button type="button" class="btn btn-outline-primary">Hapus</button></a>
                                            </div>
                                            <div
                                                class="btn-group mb-2"
                                                role="group"
                                                aria-label="Basic checkbox toggle button group">
                                            <?php }
                                    } else { ?>
                                            <?php if ($bulanpilih == $jadwalaktif['bulan']) { ?>
                                                <?php if (!$RPendapatanBlnRetribusiAktif) { ?>
                                                    <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                        <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                        'page' => 'datapendapatan',
                                                                        'action' => 'inputpajak',
                                                                        'kdRek' => $kdRetribusi,
                                                                        'kdU' => $kd_skpd
                                                                    ]);
                                                                    ?>" <button type="button" class="btn btn-outline-primary">Input</button></a>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                        <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                        'page' => 'datapendapatan',
                                                                        'action' => 'ubahpajak',
                                                                        'kdRek' => $kdRetribusi,
                                                                        'id' => $RPendapatanBlnRetribusiAktif['id']
                                                                    ]);
                                                                    ?>"
                                                            <button type="button" class="btn btn-outline-primary">Ubah<br>/Lihat</button></a>
                                                        <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                        'page' => 'datapendapatan',
                                                                        'action' => 'hapuspajak',
                                                                        'kdRek' => $kdRetribusi,
                                                                        'id' => $RPendapatanBlnRetribusiAktif['id']
                                                                    ]);
                                                                    ?>"
                                                            <button type="button" class="btn btn-outline-primary">Hapus</button></a>
                                                    </div>
                                                    <div
                                                        class="btn-group mb-2"
                                                        role="group"
                                                        aria-label="Basic checkbox toggle button group">
                                            <?php  }
                                            }
                                        } ?>
                                        <?php } ?>
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <td></td>
                            <td><?= esc($kdKekayaan . $kekayaan) ?></td>
                            <td>
                                <?php if (!$opdKekayaan) { ?>
                                <?php } else { ?>
                                    <?= esc('Rp ' . number_format($opdKekayaan, 2, ',', '.')) ?> <?php } ?>
                            </td>
                            <?php if (!$opdKekayaan) { ?>
                                <td></td>
                                <td></td>
                            <?php } else { ?>
                                <?php if ($bulanpilih) { ?>
                                    <?php if (!$RPendapatanBlnKekayaanAktif) { ?>
                                        <td>
                                            <?= esc('Rp ' . number_format('0', 2, ',', '.')) ?>
                                        </td>
                                        <td>
                                            <?= esc(number_format('0', 2, ',', '.')) ?>
                                        </td>
                                    <?php } else { ?>
                                        <td> <?= esc('Rp ' . number_format($RPendapatanBlnKekayaanAktif['realisasi'], 2, ',', '.')) ?>
                                        </td>
                                        <td>
                                            <?php $persentase =  $RPendapatanBlnKekayaanAktif['realisasi'] / $opdKekayaan * 100;
                                            echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                                        </td>
                                    <?php }
                                } else { ?>
                                    <?php if (!$RPendapatanBlnKekayaanAktif) { ?>
                                        <td></td>
                                        <td></td>
                                    <?php } else { ?>
                                        <td>
                                            <?= esc('Rp ' . number_format($RPendapatanBlnKekayaanAktif['realisasi'], 2, ',', '.')) ?>
                                        </td>
                                        <td>
                                            <?php $persentase =  $RPendapatanBlnKekayaanAktif['realisasi'] / $opdKekayaan * 100;
                                            echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                                        </td>
                                <?php }
                                }
                                ?>
                            <?php } ?>
                            <td>
                                <?php if (!$opdKekayaan) { ?>
                                <?php } else { ?>
                                    <?php if (!$bulanpilih) { ?>
                                        <?php if (!$RPendapatanBlnKekayaanAktif) { ?>
                                            <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                'page' => 'datapendapatan',
                                                                'action' => 'inputpajak',
                                                                'kdRek' => $kdKekayaan,
                                                                'kdU' => $kd_skpd
                                                            ]);
                                                            ?>" <button type="button" class="btn btn-outline-primary">Input</button></a>
                                            </div>
                                        <?php } else { ?>
                                            <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                'page' => 'datapendapatan',
                                                                'action' => 'ubahpajak',
                                                                'kdRek' => $kdKekayaan,
                                                                'id' => $RPendapatanBlnKekayaanAktif['id']
                                                            ]);
                                                            ?>"
                                                    <button type="button" class="btn btn-outline-primary">Ubah<br>/Lihat</button></a>
                                                <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                'page' => 'datapendapatan',
                                                                'action' => 'hapuspajak',
                                                                'kdRek' => $kdKekayaan,
                                                                'id' => $RPendapatanBlnKekayaanAktif['id']
                                                            ]);
                                                            ?>"
                                                    <button type="button" class="btn btn-outline-primary">Hapus</button></a>
                                            </div>
                                            <div
                                                class="btn-group mb-2"
                                                role="group"
                                                aria-label="Basic checkbox toggle button group">
                                            <?php }
                                    } else { ?>
                                            <?php if ($bulanpilih == $jadwalaktif['bulan']) { ?>
                                                <?php if (!$RPendapatanBlnKekayaanAktif) { ?>
                                                    <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                        <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                        'page' => 'datapendapatan',
                                                                        'action' => 'inputpajak',
                                                                        'kdRek' => $kdKekayaan,
                                                                        'kdU' => $kd_skpd
                                                                    ]);
                                                                    ?>" <button type="button" class="btn btn-outline-primary">Input</button></a>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                        <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                        'page' => 'datapendapatan',
                                                                        'action' => 'ubahpajak',
                                                                        'kdRek' => $kdKekayaan,
                                                                        'id' => $RPendapatanBlnKekayaanAktif['id']
                                                                    ]);
                                                                    ?>"
                                                            <button type="button" class="btn btn-outline-primary">Ubah<br>/Lihat</button></a>
                                                        <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                        'page' => 'datapendapatan',
                                                                        'action' => 'hapuspajak',
                                                                        'kdRek' => $kdKekayaan,
                                                                        'id' => $RPendapatanBlnKekayaanAktif['id']
                                                                    ]);
                                                                    ?>"
                                                            <button type="button" class="btn btn-outline-primary">Hapus</button></a>
                                                    </div>
                                                    <div
                                                        class="btn-group mb-2"
                                                        role="group"
                                                        aria-label="Basic checkbox toggle button group">
                                            <?php  }
                                            }
                                        } ?>
                                        <?php } ?>
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <td></td>
                            <td><?= esc($kdLainlain . $lainlain) ?></td>
                            <td>
                                <?php if (!$opdLainlain) { ?>
                                <?php } else { ?>
                                    <?= esc('Rp ' . number_format($opdLainlain, 2, ',', '.')) ?> <?php } ?>

                            </td>
                            <?php if (!$opdLainlain) { ?>
                                <td></td>
                                <td></td>
                            <?php } else { ?>
                                <?php if ($bulanpilih) { ?>
                                    <?php if (!$RPendapatanBlnLainlainAktif) { ?>
                                        <td>
                                            <?= esc('Rp ' . number_format('0', 2, ',', '.')) ?>
                                        </td>
                                        <td>
                                            <?= esc(number_format('0', 2, ',', '.')) ?>
                                        </td>
                                    <?php } else { ?>
                                        <td> <?= esc('Rp ' . number_format($RPendapatanBlnLainlainAktif['realisasi'], 2, ',', '.')) ?>
                                        </td>
                                        <td>
                                            <?php $persentase =  $RPendapatanBlnLainlainAktif['realisasi'] / $opdLainlain * 100;
                                            echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                                        </td>
                                    <?php }
                                } else { ?>
                                    <?php if (!$RPendapatanBlnLainlainAktif) { ?>
                                        <td></td>
                                        <td></td>
                                    <?php } else { ?>
                                        <td>
                                            <?= esc('Rp ' . number_format($RPendapatanBlnLainlainAktif['realisasi'], 2, ',', '.')) ?>
                                        </td>
                                        <td>
                                            <?php $persentase =  $RPendapatanBlnLainlainAktif['realisasi'] / $opdLainlain * 100;
                                            echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                                        </td>
                                <?php }
                                }
                                ?>
                            <?php } ?>
                            <td>
                                <?php if (!$opdLainlain) { ?>
                                <?php } else { ?>
                                    <?php if (!$bulanpilih) { ?>
                                        <?php if (!$RPendapatanBlnLainlainAktif) { ?>
                                            <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                'page' => 'datapendapatan',
                                                                'action' => 'inputpajak',
                                                                'kdRek' => $kdLainlain,
                                                                'kdU' => $kd_skpd
                                                            ]);
                                                            ?>" <button type="button" class="btn btn-outline-primary">Input</button></a>
                                            </div>
                                        <?php } else { ?>
                                            <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                'page' => 'datapendapatan',
                                                                'action' => 'ubahpajak',
                                                                'kdRek' => $kdLainlain,
                                                                'id' => $RPendapatanBlnLainlainAktif['id']
                                                            ]);
                                                            ?>"
                                                    <button type="button" class="btn btn-outline-primary">Ubah<br>/Lihat</button></a>
                                                <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                'page' => 'datapendapatan',
                                                                'action' => 'hapuspajak',
                                                                'kdRek' => $kdLainlain,
                                                                'id' => $RPendapatanBlnLainlainAktif['id']
                                                            ]);
                                                            ?>"
                                                    <button type="button" class="btn btn-outline-primary">Hapus</button></a>
                                            </div>
                                            <div
                                                class="btn-group mb-2"
                                                role="group"
                                                aria-label="Basic checkbox toggle button group">
                                            <?php }
                                    } else { ?>
                                            <?php if ($bulanpilih == $jadwalaktif['bulan']) { ?>
                                                <?php if (!$RPendapatanBlnLainlainAktif) { ?>
                                                    <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                        <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                        'page' => 'datapendapatan',
                                                                        'action' => 'inputpajak',
                                                                        'kdRek' => $kdLainlain,
                                                                        'kdU' => $kd_skpd
                                                                    ]);
                                                                    ?>" <button type="button" class="btn btn-outline-primary">Input</button></a>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                        <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                        'page' => 'datapendapatan',
                                                                        'action' => 'ubahpajak',
                                                                        'kdRek' => $kdLainlain,
                                                                        'id' => $RPendapatanBlnLainlainAktif['id']
                                                                    ]);
                                                                    ?>"
                                                            <button type="button" class="btn btn-outline-primary">Ubah<br>/Lihat</button></a>
                                                        <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                        'page' => 'datapendapatan',
                                                                        'action' => 'hapuspajak',
                                                                        'kdRek' => $kdLainlain,
                                                                        'id' => $RPendapatanBlnLainlainAktif['id']
                                                                    ]);
                                                                    ?>"
                                                            <button type="button" class="btn btn-outline-primary">Hapus</button></a>
                                                    </div>
                                                    <div
                                                        class="btn-group mb-2"
                                                        role="group"
                                                        aria-label="Basic checkbox toggle button group">
                                            <?php  }
                                            }
                                        } ?>
                                        <?php } ?>
                            </td>

                        </tr>
                        <tr class="align-middle">
                            <td></td>
                            <td><?= esc($kdTfPusat . $tfpusat) ?></td>
                            <td>
                                <?php if (!$opdtfpusat) { ?>
                                <?php } else { ?>
                                    <?= esc('Rp ' . number_format($opdtfpusat, 2, ',', '.')) ?> <?php } ?>

                            </td>
                            <?php if (!$opdtfpusat) { ?>
                                <td></td>
                                <td></td>
                            <?php } else { ?>
                                <?php if ($bulanpilih) { ?>
                                    <?php if (!$RPendapatanBlntfpusatAktif) { ?>
                                        <td>
                                            <?= esc('Rp ' . number_format('0', 2, ',', '.')) ?>
                                        </td>
                                        <td>
                                            <?= esc(number_format('0', 2, ',', '.')) ?>
                                        </td>
                                    <?php } else { ?>
                                        <td> <?= esc('Rp ' . number_format($RPendapatanBlntfpusatAktif['realisasi'], 2, ',', '.')) ?>
                                        </td>
                                        <td>
                                            <?php $persentase =  $RPendapatanBlntfpusatAktif['realisasi'] / $opdtfpusat * 100;
                                            echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                                        </td>
                                    <?php }
                                } else { ?>
                                    <?php if (!$RPendapatanBlntfpusatAktif) { ?>
                                        <td></td>
                                        <td></td>
                                    <?php } else { ?>
                                        <td>
                                            <?= esc('Rp ' . number_format($RPendapatanBlntfpusatAktif['realisasi'], 2, ',', '.')) ?>
                                        </td>
                                        <td>
                                            <?php $persentase =  $RPendapatanBlntfpusatAktif['realisasi'] / $opdtfpusat * 100;
                                            echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                                        </td>
                                <?php }
                                }
                                ?>
                            <?php } ?>
                            <td>
                                <?php if (!$opdtfpusat) { ?>
                                <?php } else { ?>
                                    <?php if (!$bulanpilih) { ?>
                                        <?php if (!$RPendapatanBlntfpusatAktif) { ?>
                                            <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                'page' => 'datapendapatan',
                                                                'action' => 'inputpajak',
                                                                'kdRek' => $kdTfPusat,
                                                                'kdU' => $kd_skpd
                                                            ]);
                                                            ?>" <button type="button" class="btn btn-outline-primary">Input</button></a>
                                            </div>
                                        <?php } else { ?>
                                            <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                'page' => 'datapendapatan',
                                                                'action' => 'ubahpajak',
                                                                'kdRek' => $kdTfPusat,
                                                                'id' => $RPendapatanBlntfpusatAktif['id']
                                                            ]);
                                                            ?>"
                                                    <button type="button" class="btn btn-outline-primary">Ubah<br>/Lihat</button></a>
                                                <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                'page' => 'datapendapatan',
                                                                'action' => 'hapuspajak',
                                                                'kdRek' => $kdTfPusat,
                                                                'id' => $RPendapatanBlntfpusatAktif['id']
                                                            ]);
                                                            ?>"
                                                    <button type="button" class="btn btn-outline-primary">Hapus</button></a>
                                            </div>
                                            <div
                                                class="btn-group mb-2"
                                                role="group"
                                                aria-label="Basic checkbox toggle button group">
                                            <?php }
                                    } else { ?>
                                            <?php if ($bulanpilih == $jadwalaktif['bulan']) { ?>
                                                <?php if (!$RPendapatanBlntfpusatAktif) { ?>
                                                    <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                        <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                        'page' => 'datapendapatan',
                                                                        'action' => 'inputpajak',
                                                                        'kdRek' => $kdTfPusat,
                                                                        'kdU' => $kd_skpd
                                                                    ]);
                                                                    ?>" <button type="button" class="btn btn-outline-primary">Input</button></a>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                        <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                        'page' => 'datapendapatan',
                                                                        'action' => 'ubahpajak',
                                                                        'kdRek' => $kdTfPusat,
                                                                        'id' => $RPendapatanBlntfpusatAktif['id']
                                                                    ]);
                                                                    ?>"
                                                            <button type="button" class="btn btn-outline-primary">Ubah<br>/Lihat</button></a>
                                                        <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                        'page' => 'datapendapatan',
                                                                        'action' => 'hapuspajak',
                                                                        'kdRek' => $kdTfPusat,
                                                                        'id' => $RPendapatanBlntfpusatAktif['id']
                                                                    ]);
                                                                    ?>"
                                                            <button type="button" class="btn btn-outline-primary">Hapus</button></a>
                                                    </div>
                                                    <div
                                                        class="btn-group mb-2"
                                                        role="group"
                                                        aria-label="Basic checkbox toggle button group">
                                            <?php  }
                                            }
                                        } ?>
                                        <?php } ?>
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <td></td>
                            <td><?= esc($kdTfDaerah . $tfdaerah) ?></td>
                            <td>
                                <?php if (!$opdtfdaerah) { ?>
                                <?php } else { ?>
                                    <?= esc('Rp ' . number_format($opdtfdaerah, 2, ',', '.')) ?> <?php } ?>

                            </td>
                            <?php if (!$opdtfdaerah) { ?>
                                <td></td>
                                <td></td>
                            <?php } else { ?>
                                <?php if ($bulanpilih) { ?>
                                    <?php if (!$RPendapatanBlntfdaerahAktif) { ?>
                                        <td>
                                            <?= esc('Rp ' . number_format('0', 2, ',', '.')) ?>
                                        </td>
                                        <td>
                                            <?= esc(number_format('0', 2, ',', '.')) ?>
                                        </td>
                                    <?php } else { ?>
                                        <td> <?= esc('Rp ' . number_format($RPendapatanBlntfdaerahAktif['realisasi'], 2, ',', '.')) ?>
                                        </td>
                                        <td>
                                            <?php $persentase =  $RPendapatanBlntfdaerahAktif['realisasi'] / $opdtfdaerah * 100;
                                            echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                                        </td>
                                    <?php }
                                } else { ?>
                                    <?php if (!$RPendapatanBlntfdaerahAktif) { ?>
                                    <?php } else { ?>
                                        <td>
                                            <?= esc('Rp ' . number_format($RPendapatanBlntfdaerahAktif['realisasi'], 2, ',', '.')) ?>
                                        </td>
                                        <td>
                                            <?php $persentase =  $RPendapatanBlntfdaerahAktif['realisasi'] / $opdtfdaerah * 100;
                                            echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                                        </td>
                                <?php }
                                }
                                ?>
                            <?php } ?>
                            <td>
                                <?php if (!$opdtfdaerah) { ?>
                                <?php } else { ?>
                                    <?php if (!$bulanpilih) { ?>
                                        <?php if (!$RPendapatanBlntfdaerahAktif) { ?>
                                            <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                'page' => 'datapendapatan',
                                                                'action' => 'inputpajak',
                                                                'kdRek' => $kdTfDaerah,
                                                                'kdU' => $kd_skpd
                                                            ]);
                                                            ?>" <button type="button" class="btn btn-outline-primary">Input</button></a>
                                            </div>
                                        <?php } else { ?>
                                            <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                'page' => 'datapendapatan',
                                                                'action' => 'ubahpajak',
                                                                'kdRek' => $kdTfDaerah,
                                                                'id' => $RPendapatanBlntfdaerahAktif['id']
                                                            ]);
                                                            ?>"
                                                    <button type="button" class="btn btn-outline-primary">Ubah<br>/Lihat</button></a>
                                                <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                'page' => 'datapendapatan',
                                                                'action' => 'hapuspajak',
                                                                'kdRek' => $kdTfDaerah,
                                                                'id' => $RPendapatanBlntfdaerahAktif['id']
                                                            ]);
                                                            ?>"
                                                    <button type="button" class="btn btn-outline-primary">Hapus</button></a>
                                            </div>
                                            <div
                                                class="btn-group mb-2"
                                                role="group"
                                                aria-label="Basic checkbox toggle button group">
                                            <?php }
                                    } else { ?>
                                            <?php if ($bulanpilih == $jadwalaktif['bulan']) { ?>
                                                <?php if (!$RPendapatanBlntfdaerahAktif) { ?>
                                                    <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                        <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                        'page' => 'datapendapatan',
                                                                        'action' => 'inputpajak',
                                                                        'kdRek' => $kdTfDaerah,
                                                                        'kdU' => $kd_skpd
                                                                    ]);
                                                                    ?>" <button type="button" class="btn btn-outline-primary">Input</button></a>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                        <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                        'page' => 'datapendapatan',
                                                                        'action' => 'ubahpajak',
                                                                        'kdRek' => $kdTfDaerah,
                                                                        'id' => $RPendapatanBlntfdaerahAktif['id']
                                                                    ]);
                                                                    ?>"
                                                            <button type="button" class="btn btn-outline-primary">Ubah<br>/Lihat</button></a>
                                                        <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                        'page' => 'datapendapatan',
                                                                        'action' => 'hapuspajak',
                                                                        'kdRek' => $kdTfDaerah,
                                                                        'id' => $RPendapatanBlntfdaerahAktif['id']
                                                                    ]);
                                                                    ?>"
                                                            <button type="button" class="btn btn-outline-primary">Hapus</button></a>
                                                    </div>
                                                    <div
                                                        class="btn-group mb-2"
                                                        role="group"
                                                        aria-label="Basic checkbox toggle button group">
                                            <?php  }
                                            }
                                        } ?>
                                        <?php } ?>
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <td></td>
                            <td><?= esc($kdHibah . $hibah) ?></td>
                            <td>
                                <?php if (!$opdhibah) { ?>
                                <?php } else { ?>
                                    <?= esc('Rp ' . number_format($opdhibah, 2, ',', '.')) ?> <?php } ?>

                            </td>
                            <?php if (!$opdhibah) { ?>
                                <td></td>
                                <td></td>
                            <?php } else { ?>
                                <?php if ($bulanpilih) { ?>
                                    <?php if (!$RPendapatanBlnhibahAktif) { ?>
                                        <td>
                                            <?= esc('Rp ' . number_format('0', 2, ',', '.')) ?>
                                        </td>
                                        <td>
                                            <?= esc(number_format('0', 2, ',', '.')) ?>
                                        </td>
                                    <?php } else { ?>
                                        <td> <?= esc('Rp ' . number_format($RPendapatanBlnhibahAktif['realisasi'], 2, ',', '.')) ?>
                                        </td>
                                        <td>
                                            <?php $persentase =  $RPendapatanBlnhibahAktif['realisasi'] / $opdhibah * 100;
                                            echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                                        </td>
                                    <?php }
                                } else { ?>
                                    <?php if (!$RPendapatanBlntfdaerahAktif) { ?>
                                        <td></td>
                                        <td></td>
                                    <?php } else { ?>
                                        <td>
                                            <?= esc('Rp ' . number_format($RPendapatanBlnhibahAktif['realisasi'], 2, ',', '.')) ?>
                                        </td>
                                        <td>
                                            <?php $persentase =  $RPendapatanBlnhibahAktif['realisasi'] / $opdhibah * 100;
                                            echo  esc(number_format($persentase, 2, ',', '.')) . ' %' ?>
                                        </td>
                                <?php }
                                }
                                ?>
                            <?php } ?>
                            <td>
                                <?php if (!$opdhibah) { ?>
                                <?php } else { ?>
                                    <?php if (!$bulanpilih) { ?>
                                        <?php if (!$RPendapatanBlnhibahAktif) { ?>
                                            <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                'page' => 'datapendapatan',
                                                                'action' => 'inputpajak',
                                                                'kdRek' => $kdHibah,
                                                                'kdU' => $kd_skpd
                                                            ]);
                                                            ?>" <button type="button" class="btn btn-outline-primary">Input</button></a>
                                            </div>
                                        <?php } else { ?>
                                            <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                'page' => 'datapendapatan',
                                                                'action' => 'ubahpajak',
                                                                'kdRek' => $kdHibah,
                                                                'id' => $RPendapatanBlnhibahAktif['id']
                                                            ]);
                                                            ?>"
                                                    <button type="button" class="btn btn-outline-primary">Ubah<br>/Lihat</button></a>
                                                <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                'page' => 'datapendapatan',
                                                                'action' => 'hapuspajak',
                                                                'kdRek' => $kdHibah,
                                                                'id' => $RPendapatanBlnhibahAktif['id']
                                                            ]);
                                                            ?>"
                                                    <button type="button" class="btn btn-outline-primary">Hapus</button></a>
                                            </div>
                                            <div
                                                class="btn-group mb-2"
                                                role="group"
                                                aria-label="Basic checkbox toggle button group">
                                            <?php }
                                    } else { ?>
                                            <?php if ($bulanpilih == $jadwalaktif['bulan']) { ?>
                                                <?php if (!$RPendapatanBlntfdaerahAktif) { ?>
                                                    <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                        <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                        'page' => 'datapendapatan',
                                                                        'action' => 'inputpajak',
                                                                        'kdRek' => $kdHibah,
                                                                        'kdU' => $kd_skpd
                                                                    ]);
                                                                    ?>" <button type="button" class="btn btn-outline-primary">Input</button></a>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="btn-group mb-2" role="group" aria-label="Basic outlined example">
                                                        <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                        'page' => 'datapendapatan',
                                                                        'action' => 'ubahpajak',
                                                                        'kdRek' => $kdHibah,
                                                                        'id' => $RPendapatanBlnhibahAktif['id']
                                                                    ]);
                                                                    ?>"
                                                            <button type="button" class="btn btn-outline-primary">Ubah<br>/Lihat</button></a>
                                                        <a href="<?= hash_url('lrfkopd/pendapatan/', [
                                                                        'page' => 'datapendapatan',
                                                                        'action' => 'hapuspajak',
                                                                        'kdRek' => $kdHibah,
                                                                        'id' => $RPendapatanBlnhibahAktif['id']
                                                                    ]);
                                                                    ?>"
                                                            <button type="button" class="btn btn-outline-primary">Hapus</button></a>
                                                    </div>
                                                    <div
                                                        class="btn-group mb-2"
                                                        role="group"
                                                        aria-label="Basic checkbox toggle button group">
                                            <?php  }
                                            }
                                        } ?>
                                        <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>JUMLAH</td>
                            <td><?= esc('Rp ' . number_format($datapendapatanopd, 2, ',', '.')) ?></td>
                            <td>
                                <?php if ($totrealisasi == '') { ?>
                                <?php } else { ?>
                                    <?= esc('Rp ' . number_format($totrealisasi, 2, ',', '.')) ?>
                                <?php } ?>

                            </td>
                            <td>

                                <?php

                                if ($totrealisasi == '') {
                                    $persentaseT =  0;
                                } else {
                                    $persentaseT = $totrealisasi / $datapendapatanopd * 100;
                                }
                                //$persentaseT =  $totrealisasi / $datapendapatanopd;
                                echo  esc(number_format($persentaseT, 2, ',', '.')) . ' %';
                                ?>

                            </td>
                            <td></td>

                        </tr>
                    </tbody>
                </table> <?php } ?>

        </div>
        <!--end::Body-->
    </div>
    <!--end::Row-->
</div>
<!--end::Container-->

<?= $this->endSection() ?>