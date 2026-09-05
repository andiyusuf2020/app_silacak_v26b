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
                    <h3 class="card-title">Title</h3>
                    <div class="card-tools">
                        <button
                            type="button"
                            class="btn btn-tool"
                            data-lte-toggle="card-collapse"
                            title="Collapse">
                            <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                            <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                        </button>
                        <button
                            type="button"
                            class="btn btn-tool"
                            data-lte-toggle="card-remove"
                            title="Remove">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">Start creating your amazing application!</div>
                <!-- /.card-body -->
                <div class="card-footer">Footer</div>
                <!-- /.card-footer-->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <br>
    <br>
    <div class="card card-success card-outline mb-4">
        <!--begin::Header-->
        <div class="card-header">
            <div class="card-title">Pilih Data Indikator Kinerja yang akan diinput</div>
        </div>
        <!--end::Header-->
        <!--begin::Body-->
        <div class="card-body">
            <div class="alert alert-primary" role="alert">
                <a href="<?= hash_url('lrfkopd/kinerja/', ['page' => 'kinerja', 'action' => 'iku']);
                            ?>" class="alert-link">Input Target Indikator Capaian Per-Program Perangkat Daerah</a>
            </div>
            <div class="alert alert-secondary" role="alert">
                <a href="<?= hash_url('lrfkopd/kinerja/', ['page' => 'output', 'action' => 'indikator']);
                            ?>" class="alert-link">Input Target Indikator Kinerja Sub Kegiatan (Output SubKegiatan) Perangkat Daerah</a>
            </div>
        </div>
        <!--end::Body-->
    </div>
    <!--end::Row-->
</div>
<!--end::Container-->

<?= $this->endSection() ?>