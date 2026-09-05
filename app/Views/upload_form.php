<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<!--begin::Container-->
<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><?= esc($title) ?></h4>
        </div>
        <div class="card-body">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (isset($errors) && !empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif; ?>
            <?php
            if ($status == 'apbd') { ?>
                <form action="<?= base_url('adminprov/uploadapbd/upload') ?>" method="post" enctype="multipart/form-data">
                <?php   }
            if ($status == 'realrup') { ?>
                    <form action="<?= base_url('adminprov/uploadrealrup/upload') ?>" method="post" enctype="multipart/form-data">
                    <?php   }
                if ($status == 'rup') { ?>
                        <form action="<?= base_url('adminprov/uploadrup/upload') ?>" method="post" enctype="multipart/form-data">
                        <?php   }
                    if ($status == 'pendapatan') { ?>
                            <form action="<?= base_url('adminprov/uploadpendapatan/upload') ?>" method="post" enctype="multipart/form-data">
                            <?php   }
                        if ($status == 'RSIPD') { ?>
                                <form action="<?= base_url('adminprov/uploadrsipd/upload') ?>" method="post" enctype="multipart/form-data">
                                <?php   } ?>
                                <?= csrf_field() ?>

                                <div class="mb-3">
                                    <label for="excel_file" class="form-label">Pilih File Excel (.xls, .xlsx)</label>
                                    <input class="form-control" type="file" id="excel_file" name="excel_file" accept=".xls,.xlsx" required>
                                    <div class="form-text">Maksimal ukuran file: 20MB.</div>
                                </div>
                                <?php if (session()->getFlashdata('success')) { ?>
                                <?php } else { ?>
                                    <button type="submit" class="btn btn-primary">Upload</button>
                                <?php } ?>
                                </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>