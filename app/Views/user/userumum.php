<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<!--begin::Container-->
<!-- Default box -->

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informasi</h3>
        </div>
        <div class="card-body">
            Status User terdaftar anda adalah User Umum,.silakan menghubungi Admin SiTAPIS jika anda User Perangkat Daerah Provinsi Lampung
            <br>
            Admin Biro Administrasi Pembangunan Setda Provinsi Lampung
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            Footer
        </div>
        <!-- /.card-footer-->
    </div>
    <!-- /.card -->

</div>
<!--end::Container-->

<?= $this->endSection() ?>