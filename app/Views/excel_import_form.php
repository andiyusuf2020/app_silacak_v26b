<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Excel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Excel Importer</a>
            <div class="navbar-nav">
                <span class="nav-link">Halo, <?= session()->get('username') ?></span>
                <a class="nav-link" href="<?= base_url('/logout') ?>">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Import Data dari Excel</h3>
                    </div>
                    <div class="card-body">
                        <?php if (session()->getFlashdata('message')) : ?>
                            <div class="alert alert-success">
                                <?= session()->getFlashdata('message') ?>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('error')) : ?>
                            <div class="alert alert-danger">
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($errors)) : ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php foreach ($errors as $error) : ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach ?>
                                </ul>
                            </div>
                        <?php endif ?>

                        <form action="<?= base_url('/excel-import/import') ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>

                            <div class="form-group mb-3">
                                <label for="excel_file" class="form-label">File Excel</label>
                                <input type="file" class="form-control" id="excel_file" name="excel_file" required>
                                <small class="form-text text-muted">Hanya file Excel (.xls, .xlsx) dengan ukuran maksimal 2MB</small>
                            </div>

                            <button type="submit" class="btn btn-primary">Import Data</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>