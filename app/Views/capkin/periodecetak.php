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
                <?php if ($action == 'sasaran') { ?>
                    <div class="card">
                        <button type="button" class="btn btn-outline-success mb-2">
                            <a href="
                        <?= hash_url('capkin', ['page' => 'cetak', 'action' => 'sasaran', 'periode' => 'tw1']);
                        ?>" class="nav-link" target="_blank">TRIWULAN I</a> </button>
                        <button type="button" class="btn btn-outline-info mb-2">
                            <a href="
                        <?= hash_url('capkin', ['page' => 'cetak', 'action' => 'sasaran', 'periode' => 'tw2']);
                        ?>" class="nav-link" target="_blank">TRIWULAN II</a></button>
                        <button type="button" class="btn btn-outline-primary mb-2">
                            <a href="
                        <?= hash_url('capkin', ['page' => 'cetak', 'action' => 'sasaran', 'periode' => 'tw3']);
                        ?>" class="nav-link" target="_blank">TRIWULAN III</a> </button>
                        <button type="button" class="btn btn-outline-warning mb-2">
                            <a href="
                        <?= hash_url('capkin', ['page' => 'cetak', 'action' => 'sasaran', 'periode' => 'tw4']);
                        ?>" class="nav-link" target="_blank">TRIWULAN IV</a> </button>
                    </div>

                <?php  } ?>
                <?php if ($action == 'unggulan') { ?>
                    <div class="card">
                        <button type="button" class="btn btn-outline-success mb-2">
                            <a href="
                        <?= hash_url('capkin', ['page' => 'cetak', 'action' => 'unggulan', 'periode' => 'tw1']);
                        ?>" class="nav-link" target="_blank">TRIWULAN I</a> </button>
                        <button type="button" class="btn btn-outline-info mb-2">
                            <a href="
                        <?= hash_url('capkin', ['page' => 'cetak', 'action' => 'unggulan', 'periode' => 'tw2']);
                        ?>" class="nav-link" target="_blank">TRIWULAN II</a></button>
                        <button type="button" class="btn btn-outline-primary mb-2">
                            <a href="
                        <?= hash_url('capkin', ['page' => 'cetak', 'action' => 'unggulan', 'periode' => 'tw3']);
                        ?>" class="nav-link" target="_blank">TRIWULAN III</a> </button>
                        <button type="button" class="btn btn-outline-warning mb-2">
                            <a href="
                        <?= hash_url('capkin', ['page' => 'cetak', 'action' => 'unggulan', 'periode' => 'tw4']);
                        ?>" class="nav-link" target="_blank">TRIWULAN IV</a> </button>
                    </div>

                <?php  } ?>
                <?php if ($action == 'tematik') { ?>
                    <div class="card">
                        <button type="button" class="btn btn-outline-success mb-2">
                            <a href="
                        <?= hash_url('capkin', ['page' => 'cetak', 'action' => 'tematik', 'periode' => 'tw1']);
                        ?>" class="nav-link" target="_blank">TRIWULAN I</a> </button>
                        <button type="button" class="btn btn-outline-info mb-2">
                            <a href="
                        <?= hash_url('capkin', ['page' => 'cetak', 'action' => 'tematik', 'periode' => 'tw2']);
                        ?>" class="nav-link" target="_blank">TRIWULAN II</a></button>
                        <button type="button" class="btn btn-outline-primary mb-2">
                            <a href="
                        <?= hash_url('capkin', ['page' => 'cetak', 'action' => 'tematik', 'periode' => 'tw3']);
                        ?>" class="nav-link" target="_blank">TRIWULAN III</a> </button>
                        <button type="button" class="btn btn-outline-warning mb-2">
                            <a href="
                        <?= hash_url('capkin', ['page' => 'cetak', 'action' => 'tematik', 'periode' => 'tw4']);
                        ?>" class="nav-link" target="_blank">TRIWULAN IV</a> </button>
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