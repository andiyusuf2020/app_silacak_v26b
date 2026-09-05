<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<!--begin::Container-->
<?php
foreach ($datamisi as $key => $value) { ?>
    <!-- /.card -->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-12">
                <!-- Default box -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <h3><?= $key + 1 . '.' . $value['judul_misi']; ?></h3>
                        </h3>
                        <div class="card-tools">
                            <button
                                type="button"
                                class="btn btn-tool"
                                data-lte-toggle="card-collapse"
                                title="Collapse">
                                <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                                <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                            </button>

                        </div>
                    </div>
                    <div class="card-body"><?= $value['ket']; ?></div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <div class="col-12">
                            <div class="callout callout-info">

                                <a
                                    href="<?= hash_url('adbang/program_kerja/', ['id' => $key + 1, 'action' => 'list']); ?>"
                                    rel="noopener noreferrer"
                                    class="callout-link">
                                    PROGRAM KERJA PER-MISI :<?= $key + 1; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-footer-->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->

<?php }
?>

<?= $this->endSection() ?>