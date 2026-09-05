  <?= $this->extend('template/layout') ?>
  <?= $this->section('content') ?>
  <!--begin::Container-->
  <?php

    use App\Models\LrfkProvModel\RealisasiSubKegModel;
    use App\Models\LrfkProvModel\TotalRealisasiModel;
    use App\Models\LrfkProvModel\TotalRealisasiBulanModel;
    use App\Models\LrfkProvModel\TaRealisasiRinciModel;


    use App\Models\LrfkProvModel\SubKegModel; // as lrfkModel;
    use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;
    use SebastianBergmann\Type\NullType;

    $this->subkegmodel = new SubKegModel();
    $this->realisasilrfk = new RealisasiSubKegModel();
    $this->totalrealisasiM = new TotalRealisasiModel();
    $this->totalrealisasiblnM = new TotalRealisasiBulanModel();
    $this->realisasilrfkrinci = new TaRealisasiRinciModel();

    if ($bulanpilih == '') {
        $bulan = $jadwalaktif['bulan'];
    } else {
        $bulan = $bulanpilih;
    }
    ?>
  <div class="row">
      <div class="col-12">
          <div class="card">
              <div class="card-header">
                  <h3 class="card-title">Data Anggaran Pendapatan dan Belanja Tahun Anggaran <?= $tahunaktif . '  Perangkat Daerah  ' . $datauser['sub_unit'] ?></h3>
              </div>
              <div class="alert alert-danger" role="alert">
                  <ul>
                      Jadwal input data yang aktif saat ini adalah Bulan : <?= $jadwalaktif['bulan'] ?>
                  </ul>
              </div>
              <div class="row">
                  <div class="col-12">
                      <div class="card">
                          <div class="card-header">
                              <h3 class="card-title">Pilih Bulan Data atau
                                  <a href="<?= hash_url('capkin/', ['page' => 'dashboard', 'action' => 'progreslrfk']);
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
                                          <a class="page-link" href="<?= hash_url('capkin/', ['page' => 'dashboard', 'action' => 'progreslrfk', 'bulan' => $data['bulan']]);
                                                                        ?>">
                                              <p class="page-month"><?= esc($data['bulan']) ?></p>
                                              <p class="page-year"><?= esc($tahunaktif) ?></p>
                                          </a>
                                      </li>
                                  <?php
                                    } ?>
                                  <li class="page-item"><a class="page-link" href="#">»</a></li>
                              </ul>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="card-body table-responsive p-0">
                  <table class="table table-head-fixed text-wrap">
                      <thead>
                          <tr>
                              <th>-</th>
                              <th>Urusan/Program/Kegiatan/Subkegiatan</th>
                              <th>Pagu (Rp.)</th>
                              <?php if ($bulanpilih == '') { ?>
                                  <th>Total Realisasi s/d Bulan <?= esc($jadwalaktif['bulan']) ?> (Rp.)</th>
                              <?php } else { ?>
                                  <th>Total Realisasi <?= esc($bulanpilih) ?> Rp
                                  </th>
                              <?php  }  ?>
                              <th>% Realisasi</th>
                              <?php if ($bulanpilih == '') { ?>
                                  <th>Capaian Kinerja Fisik Anggaran</th>
                              <?php } else { ?>
                                  <th>Capaian Kinerja Fisik Anggaran</th>
                              <?php  }  ?>
                              <th>Data diubah pada</th>

                          </tr>
                      </thead>
                      <tbody>
                          <?php
                            foreach ($listapbdopd as $key => $value) { ?>
                              <tr>
                                  <td></td>
                                  <td style="background-color: azure;"><?= esc($value['nm_urusan']) ?></td>
                                  <td style="background-color: azure;"><?= esc(number_format($value['pagu_rincian'], 0, ',', '.')) ?></td>
                                  <td style="background-color: azure;">
                                      <?php
                                        // ->Data Kegiatan APBD
                                        $datarealisasiU = $this->realisasilrfkrinci->select('*')
                                            ->selectSum('realisasi')
                                            ->selectSum('pagu_rincian')
                                            ->where('tahun', $tahunaktif)
                                            ->where('bulan', $bulan)
                                            ->where('kd_sub_unit', $value['kd_sub_unit'])
                                            ->where('kd_urusan', $value['kd_urusan'])
                                            ->groupBy('kd_urusan')
                                            ->where('delete_at=', 0)
                                            ->get()
                                            ->getRowArray();
                                        //echo dd($datarealisasi);
                                        $paguU = $value['pagu_rincian'];
                                        $realU = $datarealisasiU['realisasi'] ?? null;
                                        $persentaseU = ($realU / $paguU) * 100 ?? NUll;

                                        //echo $pagu . '///' . $real;
                                        if ($realU > $paguU) {
                                            echo "Realisasi bulan ini melebihi pagu"; //. $real;
                                        } else {
                                            echo esc(number_format($realU, 2, ',', '.')); //$realU . '> ' . $paguU . 
                                        } ?>
                                  </td>
                                  <td colspan="4" style="background-color: azure;">
                                      <?php
                                        if (!$datarealisasiU) {
                                            echo esc('0');
                                        } else {
                                            echo esc(number_format($persentaseU, 3, ',', '.'));
                                        }
                                        ?>%
                                  </td>
                              </tr>
                              <?php
                                $dataprogram = $this->subkegmodel->select('*')
                                    ->selectSUM('pagu_rincian')->where('pagu_rincian<>', '0')
                                    ->where('kd_sub_unit', $value['kd_sub_unit'])
                                    ->where('kd_urusan', $value['kd_urusan'])
                                    ->groupBy('kd_program')
                                    ->get()
                                    ->getResultArray();
                                // echo dd($dataprogram);
                                foreach ($dataprogram as $key => $rowprogram) { ?>
                                  <tr>
                                      <td></td>
                                      <td style="background-color: bisque;"><?= esc($rowprogram['nm_program']) ?></td>
                                      <td style="background-color: bisque;"><?= esc(number_format($rowprogram['pagu_rincian'], 0, ',', '.')) ?></td>
                                      <td style="background-color: bisque;">
                                          <?php
                                            // // ->Data Realisasi Program
                                            $datarealisasiP = $this->realisasilrfkrinci->select('*')
                                                ->selectSum('realisasi')
                                                ->selectSum('pagu_rincian')
                                                ->where('tahun', $tahunaktif)
                                                ->where('bulan', $bulan)
                                                ->where('kd_sub_unit', $value['kd_sub_unit'])
                                                ->where('kd_urusan', $value['kd_urusan'])
                                                ->where('kd_program', $rowprogram['kd_program'])
                                                ->groupBy('kd_program')
                                                // ->groupBy('kd_kegiatan')
                                                ->where('delete_at=', 0)
                                                ->get()
                                                ->getRowArray();
                                            // echo dd($datarealisasiP);
                                            if (!$datarealisasiP) {
                                                echo esc('Rp0');
                                            } else {

                                                $paguP = $rowprogram['pagu_rincian'];
                                                $realP = $datarealisasiP['realisasi'] ?? null;
                                                $persentaseP = ($realP / $paguP) * 100 ?? NUll;
                                                //echo $pagu . '///' . $real;
                                                if ($realP > $paguP) {
                                                    echo "Realisasi bulan ini melebihi pagu//" . $real;
                                                } else {
                                                    echo  esc(number_format($realP, 0, ',', '.'));
                                                }
                                            }

                                            ?>
                                      </td>
                                      <td colspan="4" style="background-color: bisque;">
                                          <?php
                                            if (!$datarealisasiP) {
                                                echo esc('0');
                                            } else {
                                                echo esc(number_format($persentaseP, 3, ',', '.'));
                                            }
                                            ?> %
                                      </td>
                                  </tr>
                                  <?php
                                    $datakegiatan = $this->subkegmodel->select('*')
                                        ->selectSUM('pagu_rincian')->where('pagu_rincian<>', '0')
                                        ->where('kd_sub_unit', $value['kd_sub_unit'])
                                        ->where('kd_urusan', $value['kd_urusan'])
                                        ->where('kd_program', $rowprogram['kd_program'])
                                        ->groupBy('kd_kegiatan')
                                        ->get()
                                        ->getResultArray();
                                    // echo dd($datakegiatan);
                                    foreach ($datakegiatan as $key => $rowkegiatan) { ?>
                                      <tr>
                                          <td></td>
                                          <td style="background-color: antiquewhite;">
                                              <?= esc($rowkegiatan['kd_kegiatan']) ?>
                                              <?= esc($rowkegiatan['nm_kegiatan']) ?>
                                          </td>
                                          <td style="background-color: antiquewhite;"><?= esc(number_format($rowkegiatan['pagu_rincian'], 0, ',', '.')) ?></td>
                                          <td style="background-color: antiquewhite;">
                                              <?php
                                                // // ->Data Realisasi Kegiatan
                                                $datarealisasiK = $this->realisasilrfkrinci->select('*')
                                                    ->selectSum('realisasi')
                                                    //->selectSum('pagu_rincian')
                                                    ->where('tahun', $tahunaktif)
                                                    ->where('bulan', $bulan)
                                                    ->where('kd_sub_unit', $value['kd_sub_unit'])
                                                    ->where('kd_urusan', $value['kd_urusan'])
                                                    ->where('kd_program', $rowprogram['kd_program'])
                                                    ->where('kd_kegiatan', $rowkegiatan['kd_kegiatan'])
                                                    ->groupBy('kd_kegiatan')
                                                    ->where('delete_at=', 0)
                                                    ->get()
                                                    ->getRowArray();
                                                // echo dd($datarealisasiK);
                                                if (!$datarealisasiK) {
                                                    echo esc('Rp0');
                                                } else {

                                                    $paguK = $rowkegiatan['pagu_rincian'];
                                                    $realK = $datarealisasiK['realisasi'] ?? null;
                                                    $persentaseK = ($realK / $paguK) * 100 ?? NUll;
                                                    //echo $pagu . '///' . $real;
                                                    if ($realK > $paguK) {
                                                        echo "Realisasi bulan ini melebihi pagu";
                                                    } else {
                                                        echo  esc(number_format($realK, 0, ',', '.'));
                                                    }
                                                }
                                                ?>
                                          </td>
                                          <td colspan="4" style="background-color: antiquewhite;">
                                              <?php
                                                if (!$datarealisasiK) {
                                                    echo esc('0');
                                                } else {
                                                    echo esc(number_format($persentaseK, 3, ',', '.'));
                                                }
                                                ?>% </td>
                                      </tr>
                                      <?php
                                        $datasubkegiatan = $this->subkegmodel->select('*')
                                            ->selectSUM('pagu_rincian')->where('pagu_rincian<>', '0')
                                            ->where('kd_sub_unit', $value['kd_sub_unit'])
                                            ->where('kd_urusan', $value['kd_urusan'])
                                            ->where('kd_program', $rowprogram['kd_program'])
                                            ->where('kd_kegiatan', $rowkegiatan['kd_kegiatan'])
                                            ->groupBy('kd_subkegiatan')
                                            ->get()
                                            ->getResultArray();
                                        // echo dd($datakegiatan);
                                        foreach ($datasubkegiatan as $key => $rowsubkegiatan) { ?>
                                          <tr>
                                              <td></td>
                                              <td>
                                                  <?= esc($rowsubkegiatan['kd_subkegiatan']) ?>
                                                  <?= esc($rowsubkegiatan['nm_subkegiatan']) ?>
                                              </td>
                                              <td><?= esc(number_format($rowsubkegiatan['pagu_rincian'], 0, ',', '.')) ?></td>
                                              <td>
                                                  <?php
                                                    // // ->Data Realisasi Kegiatan
                                                    $datarealisasiSK = $this->realisasilrfkrinci->select('*')
                                                        ->selectSum('realisasi')
                                                        //->selectSum('pagu_rincian')
                                                        ->where('tahun', $tahunaktif)
                                                        ->where('bulan', $bulan)
                                                        ->where('kd_sub_unit', $value['kd_sub_unit'])
                                                        ->where('kd_urusan', $value['kd_urusan'])
                                                        ->where('kd_program', $rowprogram['kd_program'])
                                                        ->where('kd_kegiatan', $rowkegiatan['kd_kegiatan'])
                                                        ->where('kd_subkegiatan', $rowsubkegiatan['kd_subkegiatan'])
                                                        ->groupBy('kd_subkegiatan')
                                                        ->where('delete_at=', 0)
                                                        ->get()
                                                        ->getRowArray();
                                                    // echo dd($datarealisasiK);
                                                    if (!$datarealisasiSK) {
                                                        echo esc('Rp0');
                                                    } else {

                                                        $paguSK = $rowsubkegiatan['pagu_rincian'];
                                                        $realSK = $datarealisasiSK['realisasi'] ?? null;
                                                        $persentaseSK = ($realSK / $paguSK) * 100 ?? NUll;
                                                        //echo $pagu . '///' . $real;
                                                        if ($realSK > $paguSK) {
                                                            echo "Realisasi bulan ini melebihi pagu";
                                                        } else {
                                                            echo esc(number_format($realSK, 0, ',', '.'));
                                                        }
                                                    }
                                                    ?>
                                              </td>
                                              <td>
                                                  <?php
                                                    if (!$datarealisasiSK) {
                                                        echo esc('0');
                                                    } else {
                                                        echo esc(number_format($persentaseSK, 3, ',', '.'));
                                                    }
                                                    ?>%
                                              </td>
                                              <td>
                                                  <?php
                                                    $datarincireal = $this->realisasilrfkrinci
                                                        ->select('*')
                                                        //  ->select('(format((realisasi / pagu_rincian),2)) as isi')
                                                        // ->select('((realisasi / pagu_rincian)+1) as isi')
                                                        ->select('((realisasi / pagu_rincian)) as isi')

                                                        ->where('tahun', $tahunaktif)
                                                        ->where('bulan', $bulan)
                                                        ->where('kd_sub_unit', $value['kd_sub_unit'])
                                                        ->where('kd_urusan', $value['kd_urusan'])
                                                        ->where('kd_program', $rowprogram['kd_program'])
                                                        ->where('kd_kegiatan', $rowkegiatan['kd_kegiatan'])
                                                        ->where('kd_subkegiatan', $rowsubkegiatan['kd_subkegiatan'])
                                                        ->where('(format((realisasi / pagu_rincian),2)) >', 0.01)
                                                        ->where('realisasi<>', 0)
                                                        ->get()
                                                        ->getResultArray();
                                                    $da = $datarincireal;
                                                    $mul = 1;
                                                    foreach ($da as $i => $na)
                                                        // $d = 1 + $na['isi'];
                                                        $mul = $i == 0 ? $na['isi'] : $mul * $na['isi'];
                                                    // $mul = $i == 0 ? $d : $mul * $d;
                                                    if (count($da) == 0) {
                                                        echo " 0  %"; ?>
                                                      <i class="fas fa-angle-left right text-danger">Rincian Realisasi Bulan <?= esc($jadwalaktif['bulan']) ?> masih kosong</i>

                                                  <?php } else {
                                                        // $croopd = (pow((float)$mul, 1 / count($da)) - 1) * 100;
                                                        $croopd = (pow((float)$mul, 1 / count($da))) * 100;
                                                        echo esc(number_format($croopd, 2, ".", ","));
                                                    }

                                                    ?>

                                              </td>
                                              <td>
                                                  <div class="accordion" id="accordionExample">
                                                      <div class="accordion-item">
                                                          <button
                                                              class="accordion-button collapsed"
                                                              type="button"
                                                              data-bs-toggle="collapse"
                                                              data-bs-target="#collapseTwo"
                                                              aria-expanded="false"
                                                              aria-controls="collapseTwo">
                                                              rincian
                                                          </button>
                                                      </div>
                                              </td>
                                          </tr>
                                          <tr>
                                              <td></td>
                                              <td colspan="7">
                                                  <div
                                                      id="collapseTwo"
                                                      class="accordion-collapse collapse"
                                                      data-bs-parent="#accordionExample">
                                                      <table class="table table-bordered">
                                                          <?php
                                                            $datarincireal2 = $this->realisasilrfkrinci
                                                                ->select('*')
                                                                ->selectSum('realisasi')
                                                                ->where('tahun', $tahunaktif)
                                                                ->where('bulan', $bulan)
                                                                ->where('kd_sub_unit', $value['kd_sub_unit'])
                                                                ->where('kd_urusan', $value['kd_urusan'])
                                                                ->where('kd_program', $rowprogram['kd_program'])
                                                                ->where('kd_kegiatan', $rowkegiatan['kd_kegiatan'])
                                                                ->where('kd_subkegiatan', $rowsubkegiatan['kd_subkegiatan'])
                                                                ->groupBy('kd_rek_belanja')
                                                                //->where('(format((realisasi / pagu_rincian),2)) >', 0.1)
                                                                //->where('realisasi<>', 0)
                                                                ->get()
                                                                ->getResultArray();
                                                            if ($datarincireal2 == null) {
                                                            } else { ?>
                                                              <thead>
                                                                  <tr>
                                                                      <th style="width: 10px">#</th>
                                                                      <th>Rekening Belanja</th>
                                                                      <th>Pagu</th>
                                                                      <th>Realisasi</th>
                                                                      <th>%</th>
                                                                  </tr>
                                                              </thead>
                                                              <tbody>
                                                              <?php }
                                                                ?>

                                                              <?php foreach ($datarincireal2 as $key => $rowbelanja2) {
                                                                    $isi = $rowbelanja2['realisasi'] / $rowbelanja2['pagu_rincian'] * 100;
                                                                ?>
                                                                  <tr class="align-middle">
                                                                      <td></td>
                                                                      <td><?= '<code>' . esc($rowbelanja2['nm_rekening']) . '</code><br>' ?></td>
                                                                      <td><?= '<code>' . number_format(esc($rowbelanja2['pagu_rincian']), 2, ',', '.') . '</code><br>' ?>
                                                                      </td>
                                                                      <td><?= '<code>' . number_format(esc($rowbelanja2['realisasi']), 2, ',', '.') . '</code><br>' ?></td>
                                                                      <td><?= '<code>' . number_format(esc($isi), 2, ',', '.') . ' </code><br>' ?></td>
                                                                  </tr>

                                                              <?php
                                                                    //  echo $rowbelanja2['realisasi'];
                                                                }
                                                                if ($datarincireal2 == null) {
                                                                } else { ?>
                                                                  <tr>
                                                                      <td></td>
                                                                      <td colspan="4">
                                                                          <code> Capaian Fisik Keuangan dihitung menggunakan formulasi rata-rata geometri
                                                                              dari kelompok data persentase realisasi perbelanja pada sub kegiatan

                                                                          </code>
                                                                      </td>
                                                                  </tr>
                                                              <?php }
                                                                ?>
                                                              </tbody>
                                                      </table>
                                                  </div>
                                              </td>
                                          </tr>

                          <?php }
                                    }
                                }
                            }
                            ?>
                          <tr>

                              <td></td>

                              <td><strong>JUMLAH</strong></td>
                              <td><strong><?= esc(number_format($totpaguopd, 0, ',', '.')) ?></strong></td>
                              <td><strong>
                                      <?php
                                        $datarealisasitotopd = $this->realisasilrfkrinci->selectSum('realisasi')
                                            ->where('kd_sub_unit', $value['kd_sub_unit'])
                                            ->where('tahun', $tahunaktif)
                                            ->where('bulan', $bulan)
                                            ->where('delete_at=', 0)
                                            ->groupBy('kd_sub_unit')
                                            ->get()
                                            ->getRowArray();

                                        if (!$datarealisasitotopd) {
                                            echo esc('0');
                                        } else {
                                            $paguTot = $totpaguopd;
                                            $realTot = $datarealisasitotopd['realisasi'] ?? null;
                                            $persentaseTot = ($realTot / $paguTot) * 100 ?? NUll;
                                            //echo $pagu . '///' . $real;
                                            if ($realTot > $paguTot) {
                                                echo "Realisasi bulan ini melebihi pagu";
                                            } else {
                                                echo esc(number_format($realTot, 0, ',', '.'));
                                            }
                                        }
                                        ?>
                                  </strong>
                              </td>
                              <td><strong>
                                      <?php
                                        if (!$datarealisasitotopd) {
                                            echo esc('0');
                                        } else {
                                            echo esc(number_format($persentaseTot, 3, ',', '.'));
                                        } ?>%
                                  </strong>
                              </td>
                          </tr>
                      </tbody>
                  </table>
              </div>
          </div>
          <!-- /.card-body -->
      </div>
      <!-- /.card -->
  </div>
  </div>
  <!-- /.row -->
  <!--end::Container-->

  <?= $this->endSection() ?>