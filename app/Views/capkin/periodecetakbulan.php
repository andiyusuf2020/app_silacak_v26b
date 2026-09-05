<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<!--begin::Container-->
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
        <div class="col-12">
            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pilih Periode Laporan <?= esc($judulcetak) ?></h3>
                </div>
                <div class="alert alert-danger" role="alert">
                    <ul>
                        Jadwal data yang aktif saat ini adalah Bulan : <?= $bulan ?>
                    </ul>
                </div>
                <?php if ($action == 'sasaran') { ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <ul class="pagination pagination-month justify-content-center">
                                        <?php
                                        foreach ($datajadwal as $key => $data) { ?>
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="<?= hash_url('capkin', ['page' => 'cetak', 'action' => 'sasaran', 'periode' => $data['bulan']]);
                                                            ?>" target="_blank">
                                                    <p class="page-month"><?= esc($data['bulan']) ?></p>
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
                <?php  } ?>
                <?php if ($action == 'unggulan') { ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <ul class="pagination pagination-month justify-content-center">
                                        <?php
                                        foreach ($datajadwal as $key => $data) { ?>
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="<?= hash_url('capkin', ['page' => 'cetak', 'action' => 'unggulan', 'periode' => $data['bulan']]);
                                                            ?>" target="_blank">
                                                    <p class="page-month"><?= esc($data['bulan']) ?></p>

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
                <?php  } ?>
                <?php if ($action == 'tematik') { ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <ul class="pagination pagination-month justify-content-center">
                                        <?php
                                        foreach ($datajadwal as $key => $data) { ?>
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="<?= hash_url('capkin', ['page' => 'cetak', 'action' => 'tematik', 'periode' => $data['bulan']]);
                                                            ?>" target="_blank">
                                                    <p class="page-month"><?= esc($data['bulan']) ?></p>

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
                <?php  } ?>
                <!-- /.card-body -->
                <div class="card-footer">Footer</div>
                <!-- /.card-footer-->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!--end::Row-->
</div>
<!--end::Container-->

<?= $this->endSection() ?>