<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc('$title') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>

<body>
    <div class="container mt-5">
        <?php if (session()->has('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>

        <?php if (session()->has('error')): ?>
            <div class="alert alert-danger">
                <?= session('error') ?>
            </div>
        <?php endif ?>

        <form method="post" action="<?= site_url('dataarray/store') ?>">
            <?= csrf_field() ?>

            <div id="input-container">
                <div class="mb-3">
                    <label for="kegpokok" class="form-label">KEGIATAN POKOK</label>
                    <button type="button" class="btn btn-secondary" id="tambah-input">
                        <i class="bi bi-plus-circle"></i> Tambah Data
                    </button>
                </div>
                <div class="input-group row g-3">
                    <div class="col-md-2">
                        <label for="target" class="form-label">Target/Sasaran</label>
                        <input type="text" name="data[0][vol]" class="form-control" placeholder="jumlah sasaran/target" required>
                        <input type="text" name="data[0][sat]" class="form-control" placeholder="satuan sasaran/target" required>
                        <!-- <input type="text" class="form-control" id="kegpokok" /> -->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-md-6">
                        <label for="validationCustom02" class="form-label">Uraian</label>
                        <textarea name="data[0][uraian]" class="form-control" id="uraian" required
                            placeholder="uraian/penjelasan mengenai sasaran" rows="5"></textarea>
                    </div>
                    <div class="col-md-3">
                        <label for="target" class="form-label">Hasil yang akan dicapai</label>
                        <textarea name="data[0][hasil]" class="form-control" id="hasil" required
                            placeholder="diawali dengan Ter...." rows="5"></textarea>
                    </div>
                    <div class="col-md-1">

                        <button type="button" class="btn btn-danger remove-input" disabled>
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-4">
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Semua
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let counter = 1;
            const inputContainer = document.getElementById('input-container');

            // Tambah input baru
            document.getElementById('tambah-input').addEventListener('click', function() {
                const newInputGroup = document.createElement('div');
                newInputGroup.className = 'input-group row g-3';

                newInputGroup.innerHTML = `
                    <div class="col-md-2">
                        <label for="target" class="form-label">Target/Sasaran</label>
                        <input type="text" name="data[${counter}][vol]" class="form-control" placeholder="jumlah sasaran/target" required>
                        <input type="text" name="data[${counter}][sat]" class="form-control" placeholder="satuan sasaran/target" required>
                        <!-- <input type="text" class="form-control" id="kegpokok" /> -->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-md-6">
                        <label for="validationCustom02" class="form-label">Uraian</label>
                        <textarea name="data[${counter}][uraian]" class="form-control" id="uraian" required
                            placeholder="uraian/penjelasan mengenai sasaran" rows="5"></textarea>
                    </div>
                    <div class="col-md-3">
                        <label for="target" class="form-label">Hasil yang akan dicapai</label>
                        <textarea name="data[${counter}][hasil]" class="form-control" id="hasil" required
                            placeholder="diawali dengan Ter...." rows="5"></textarea>
                    </div>
                    <div class="col-md-1">

                        <button type="button" class="btn btn-danger remove-input" disabled>
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
                </div>`;


                inputContainer.appendChild(newInputGroup);
                counter++;

                // Aktifkan tombol hapus pada semua input kecuali yang pertama
                document.querySelectorAll('.remove-input').forEach((btn, index) => {
                    btn.disabled = index === 0;
                });
            });

            // Hapus input
            inputContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-input') || e.target.closest('.remove-input')) {
                    const inputGroup = e.target.closest('.input-group');
                    if (inputContainer.children.length > 1) {
                        inputGroup.remove();

                        // Perbarui nama input untuk memastikan array tetap berurutan
                        document.querySelectorAll('#input-container .input-group').forEach((group, index) => {
                            const inputs = group.querySelectorAll('input');
                            inputs[0].name = `data[${index}][vol]`;
                            inputs[1].name = `data[${index}][sat]`;
                            inputs[2].name = `data[${index}][uraian]`;
                            inputs[3].name = `data[${index}][hasil]`;

                            // Nonaktifkan tombol hapus jika hanya tersisa satu input
                            const removeBtn = group.querySelector('.remove-input');
                            removeBtn.disabled = inputContainer.children.length === 1;
                        });
                    }
                }
            });
        });
    </script>
</body>

</html>