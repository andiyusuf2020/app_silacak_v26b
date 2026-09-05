<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DeepSeek AI dengan CodeIgniter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .chat-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .message-box {
            max-height: 500px;
            overflow-y: auto;
        }

        .typing-indicator {
            display: none;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="chat-container">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h1 class="h5 mb-0">DeepSeek AI Chat</h1>
                </div>

                <div class="card-body">
                    <!-- Notifikasi -->
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('errors')): ?>
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <?= $error ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('message')): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= session()->getFlashdata('message') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Form Pertanyaan -->
                    <form id="questionForm" method="post" action="<?= site_url('deepseek/ask') ?>">
                        <?= csrf_field(); ?>
                        <div class="mb-3">
                            <label for="question" class="form-label">Apa yang ingin Anda tanyakan?</label>
                            <textarea class="form-control" id="question" name="question" rows="3"
                                placeholder="Tulis pertanyaan Anda di sini..." required><?= old('question') ?></textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <span id="submitText">Kirim Pertanyaan</span>
                                <span id="loadingIndicator" class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>

                            <?php if (!empty($history)): ?>
                                <a href="<?= site_url('deepseek/clear-history') ?>" class="btn btn-outline-danger">
                                    Hapus Riwayat
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>

                    <!-- AJAX Response Area -->
                    <div id="ajaxResponse" class="mt-4 d-none"></div>
                </div>
            </div>

            <!-- Riwayat Percakapan -->
            <?php if (!empty($history)): ?>
                <div class="card shadow mt-4">
                    <div class="card-header bg-light">
                        <h2 class="h6 mb-0">Riwayat Percakapan</h2>
                    </div>
                    <div class="card-body message-box">
                        <?php foreach ($history as $index => $item): ?>
                            <div class="mb-3">
                                <div class="fw-bold">Anda:</div>
                                <div class="ps-3 mb-2"><?= esc($item['question']) ?></div>

                                <div class="fw-bold">DeepSeek AI:</div>
                                <div class="ps-3"><?= nl2br(esc($item['answer'])) ?></div>

                                <?php if ($index < count($history) - 1): ?>
                                    <hr class="my-3">
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // AJAX Form Submission
        document.getElementById('questionForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const submitBtn = form.querySelector('button[type="submit"]');
            const submitText = document.getElementById('submitText');
            const loadingIndicator = document.getElementById('loadingIndicator');
            const ajaxResponse = document.getElementById('ajaxResponse');

            // Show loading state
            submitText.textContent = 'Memproses...';
            loadingIndicator.classList.remove('d-none');
            submitBtn.disabled = true;

            // Hide previous errors
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => alert.classList.add('d-none'));

            // AJAX request
            fetch('<?= site_url('deepseek/api/ask') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams(new FormData(form))
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw err;
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        // Show response
                        ajaxResponse.innerHTML = `
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                Jawaban DeepSeek AI
                            </div>
                            <div class="card-body">
                                ${data.answer.replace(/\n/g, '<br>')}
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="<?= site_url('deepseek') ?>" class="btn btn-secondary">
                                Kembali ke Form
                            </a>
                        </div>
                    `;
                        ajaxResponse.classList.remove('d-none');

                        // Reset form
                        form.reset();
                    } else {
                        throw data;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);

                    // Show error message
                    const errorMsg = error.message || 'Terjadi kesalahan saat memproses permintaan';
                    ajaxResponse.innerHTML = `
                    <div class="alert alert-danger">
                        ${errorMsg}
                    </div>
                `;
                    ajaxResponse.classList.remove('d-none');
                })
                .finally(() => {
                    // Reset button state
                    submitText.textContent = 'Kirim Pertanyaan';
                    loadingIndicator.classList.add('d-none');
                    submitBtn.disabled = false;
                });
        });
    </script>
</body>

</html>