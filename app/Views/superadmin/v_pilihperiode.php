<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<!--begin::Container-->
<div class="container-fluid">
    <?php
    if ($hal == 'rekap') { ?>
        <!--begin::Row-->
        <div class="row">
            <div class="col-12">
                <!-- Default box -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pilih Periode Rekapitulasi LRFK Perangkat Daerah</h3>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <ul class="pagination pagination-month justify-content-center">
                                        <?php
                                        foreach ($datajadwal as $key => $data) { ?>
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="<?= hash_url('adminprov', ['hal' => 'rekap', 'action' => 'all', 'periode' => $data['bulan']]);
                                                            ?>">
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

                    <!-- /.card-body -->
                    <div class="card-footer">Footer</div>
                    <!-- /.card-footer-->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!--end::Row-->
    <?php }
    ?>
    <?php
    if ($hal == 'rekapcapkin') { ?>
        <div class="row">
            <div class="col-12">
                <!-- Default box -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pilih Periode Rekapitulasi LRFK Perangkat Daerah</h3>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <ul class="pagination pagination-month justify-content-center">
                                        <?php
                                        foreach ($datajadwal as $key => $data) { ?>
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="<?= hash_url('adminprov', ['hal' => 'rekapcapkin', 'action' => 'all', 'periode' => $data['bulan']]);
                                                            ?>">
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

                    <!-- /.card-body -->
                    <div class="card-footer">Footer</div>
                    <!-- /.card-footer-->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!--end::Row-->
    <?php } ?>
    <?php
    if ($hal == 'rekapperopd') { ?>
        <div class="row">
            <div class="col-12">
                <!-- Default box -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pilih Periode Rekapitulasi LRFK Perangkat Daerah</h3>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <ul class="pagination pagination-month justify-content-center">
                                        <?php
                                        foreach ($datajadwal as $key => $data) { ?>
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="<?= hash_url('adminprov', ['hal' => 'rekapperopd', 'action' => 'allopd', 'periode' => $data['bulan']]);
                                                            ?>">
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

                    <!-- /.card-body -->
                    <div class="card-footer">Footer</div>
                    <!-- /.card-footer-->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!--end::Row-->
    <?php } ?>

</div>
<!--end::Container-->

<?= $this->endSection() ?>