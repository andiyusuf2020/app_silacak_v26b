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
                    <h3 class="card-title"><?= esc($titlepage) ?></h3><br>
                    <h3 class="card-title">Perangkat Daerah : <?= esc($nama_subunit) ?></h3>
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
                <div class="stat-card">
                    <div id="carouselExample" class="carousel slide">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="<?= base_url() ?>cssportal/img_home/desakumaju1.jpg" class="d-block w-100" alt="...">
                            </div>
                            <div class="carousel-item">
                                <img src="<?= base_url() ?>cssportal/img_home/desakumaju2.jpg" class="d-block w-100" alt="...">
                            </div>

                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
                <div class="card-footer">Info Grafis Skema Implementasi dan Tata Kelola Program Desaku Maju</div>
                <!-- /.card-footer-->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!--end::Row-->
</div>
<!--end::Container-->

<?= $this->endSection() ?>