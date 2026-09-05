<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .pagination-container {
            margin-top: 20px;
        }

        .table-container {
            margin-top: 20px;
        }

        .info-text {
            margin-bottom: 15px;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-4"><?= $title ?></h2>

                <!-- Info Text -->
                <div class="info-text">
                    Menampilkan <?= count($users) ?> dari <?= $totalRecords ?> total data
                </div>

                <!-- Table -->
                <div class="table-container">
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Tanggal Dibuat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)): ?>
                                <?php
                                $startNumber = (($currentPage - 1) * $perPage) + 1;
                                foreach ($users as $index => $user):
                                ?>
                                    <tr>
                                        <td><?= $startNumber + $index ?></td>
                                        <td><?= esc($user['name']) ?></td>
                                        <td><?= esc($user['email']) ?></td>
                                        <td><?= date('d-m-Y H:i', strtotime($user['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data ditemukan</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-container">
                    <?= $pager ?>
                </div>

                <!-- Custom Pagination Info -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <small class="text-muted">
                            Halaman <?= $currentPage ?> dari <?= ceil($totalRecords / $perPage) ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>