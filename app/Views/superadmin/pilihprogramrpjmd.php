<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<!--begin::Container-->
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Program Pembangunan dalam RPJMD yang diampu oleh : <?= esc($dataopd['nm_sub_unit']) ?></h3>
                <br><button onclick="history.back()" class="btn btn-outline-primary mb-2">Kembali</button>

            </div>

            <!-- /.card-header -->
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Program Pembangunan yang diampu</th>
                            <th>Jumlah Aktifitas yang termapping</th>
                            <th>Tanggal Update Terakhir</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="align-middle">
                            <td>1.</td>
                            <td>Sasaran Program Prioritas</td>
                            <td><strong><?= esc($jmlmappingprio) ?> Aktifitas</strong>
                            </td>
                            <td><strong><?= esc($sasaranterupdete) ?></strong>
                            </td>

                            <td>
                                <div class="btn-group">
                                    <button
                                        type="button"
                                        class="btn btn dropdown-toggle btn-outline-primary"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="bi bi-menu-button-wide-fill"></i>MENU</button>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item"
                                                href="<?= hash_url('adminprov', [
                                                            'hal' => 'rekapcek',
                                                            'action' => 'listsasaran',
                                                            'periode' => $bulan,
                                                            'kdSU' => $dataopd['kd_sub_unit']
                                                        ]);
                                                        ?>">
                                                <i class="bi bi-eye"></i>sasaran termapping</a></li>
                                        <!-- 
                                        <li><a class="dropdown-item"
                                                href="<?= hash_url('adminprov', [
                                                            'hal' => 'rekapcek',
                                                            'action' => 'sasaran',
                                                            'kdSU' => $dataopd['kd_sub_unit']
                                                        ]);
                                                        ?>">
                                                <i class="bi bi-card-checklist"></i>semua data</a></li>
                                        <li><a class="dropdown-item"
                                                href="<?= hash_url('adminprov', [
                                                            'hal' => 'rekapcek',
                                                            'action' => 'sasaran',
                                                            'kdSU' => $dataopd['kd_sub_unit']
                                                        ]);
                                                        ?>">
                                                <i class="bi bi-printer-fill"></i>Cetak</a></li> -->

                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php if ($cekopdunggulan) { ?>
                            <tr class="align-middle">
                                <td>2.</td>
                                <td>Program Unggulan</td>
                                <td>
                                    <strong><?= esc($jmlmappingunggulan) ?> Aktifitas</strong>
                                </td>
                                <td><strong><?= esc($unggulanterupdete) ?></strong>
                                </td>

                                <td>
                                    <div class="btn-group">
                                        <button
                                            type="button"
                                            class="btn btn dropdown-toggle btn-outline-primary"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="bi bi-menu-button-wide-fill"></i>MENU</button>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item"
                                                    href="<?= hash_url('adminprov', [
                                                                'hal' => 'rekapcek',
                                                                'action' => 'listunggulan',
                                                                'kdSU' => $dataopd['kd_sub_unit']
                                                            ]);
                                                            ?>">
                                                    <i class="bi bi-eye"></i>P.Unggulan termapping</a></li>

                                            <!-- <li><a class="dropdown-item"
                                                    href="<?= hash_url('adminprov', [
                                                                'hal' => 'rekapcek',
                                                                'action' => 'unggulan',
                                                                'kdSU' => $dataopd['kd_sub_unit']
                                                            ]);
                                                            ?>">
                                                    <i class="bi bi-card-checklist"></i>semua data</a></li>
                                            <li><a class="dropdown-item"
                                                    href="<?= hash_url('adminprov', [
                                                                'hal' => 'rekapcek',
                                                                'action' => 'unggulan',
                                                                'kdSU' => $dataopd['kd_sub_unit']
                                                            ]);
                                                            ?>">
                                                    <i class="bi bi-printer-fill"></i>Cetak</a></li> -->

                                        </ul>
                                    </div>

                                </td>
                            </tr>
                        <?php } else { ?>
                        <?php } ?>
                        <?php if ($cekopdtematik) { ?>
                            <tr class="align-middle">
                                <td>3.</td>
                                <td>Program Tematik Pembangunan</td>
                                <td>
                                    <strong><?= esc($jmlmappingtematik) ?> Aktifitas</strong></li>
                                </td>
                                <td><strong><?= esc($tematikterupdete) ?></strong>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button
                                            type="button"
                                            class="btn btn dropdown-toggle btn-outline-primary"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="bi bi-menu-button-wide-fill"></i>MENU</button>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item"
                                                    href="<?= hash_url('adminprov', [
                                                                'hal' => 'rekapcek',
                                                                'action' => 'listtematik',
                                                                'kdSU' => $dataopd['kd_sub_unit']
                                                            ]);
                                                            ?>">
                                                    <i class="bi bi-eye"></i>P.Tematik termapping</a></li>
                                            <!-- <li><a class="dropdown-item"
                                                    href="<?= hash_url('adminprov', [
                                                                'hal' => 'rekapcek',
                                                                'action' => 'tematik',
                                                                'kdSU' => $dataopd['kd_sub_unit']
                                                            ]);
                                                            ?>">
                                                    <i class="bi bi-card-checklist"></i>semua data</a></li>
                                            <li><a class="dropdown-item"
                                                    href="<?= hash_url('adminprov', [
                                                                'hal' => 'rekapcek',
                                                                'action' => 'tematik',
                                                                'kdSU' => $dataopd['kd_sub_unit']
                                                            ]);
                                                            ?>">
                                                    <i class="bi bi-printer-fill"></i>Cetak</a></li> -->
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php } else { ?>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>