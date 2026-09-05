<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Jawaban - DeepSeek AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h1 class="h5 mb-0">Hasil Jawaban DeepSeek AI</h1>
                    </div>

                    <div class="card-body">
                        <!-- Pertanyaan -->
                        <div class="mb-4">
                            <h2 class="h6 text-muted">Pertanyaan Anda:</h2>
                            <div class="p-3 bg-light rounded">
                                <?= esc($question) ?>
                            </div>
                        </div>

                        <!-- Jawaban -->
                        <div class="mb-4">
                            <h2 class="h6 text-muted">Jawaban DeepSeek AI:</h2>
                            <div class="p-3 bg-light rounded">
                                <?= nl2br(esc($answer)) ?>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?= site_url('deepseek') ?>" class="btn btn-outline-primary">
                                Ajukan Pertanyaan Lain
                            </a>

                            <?php if (!empty($history)): ?>
                                <a href="<?= site_url('deepseek/clear-history') ?>" class="btn btn-outline-danger">
                                    Hapus Riwayat
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Percakapan -->
                <?php if (!empty($history)): ?>
                    <div class="card shadow mt-4">
                        <div class="card-header bg-light">
                            <h2 class="h6 mb-0">Riwayat Percakapan Terakhir</h2>
                        </div>
                        <div class="card-body">
                            <?php foreach ($history as $index => $item): ?>
                                <?php if ($index > 0): // Skip current question 
                                ?>
                                    <div class="mb-3">
                                        <div class="fw-bold">Anda:</div>
                                        <div class="ps-3 mb-2"><?= esc($item['question']) ?></div>

                                        <div class="fw-bold">DeepSeek AI:</div>
                                        <div class="ps-3"><?= nl2br(esc($item['answer'])) ?></div>

                                        <?php if ($index < count($history) - 1): ?>
                                            <hr class="my-3">
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>