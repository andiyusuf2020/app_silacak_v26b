<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    .month-input {
        text-align: right;
        font-family: monospace;
        font-size: 0.95rem;
    }

    .bg-subtotal {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    .bg-grandtotal {
        background-color: #e9ecef;
        font-weight: bold;
        font-size: 1.05rem;
    }
</style>

<div class="app-content">
    <div class="container-fluid">

        <form id="formAnggaranKas">
            <!-- CSRF Token Input Dynamic -->
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf_input">

            <div class="card card-primary card-outline mb-4 shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Sub Kegiatan & Pagu</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="perangkat_daerah" class="form-label fw-bold">Perangkat Daerah (SKPD)</label>
                            <select class="form-select" id="perangkat_daerah" name="perangkat_daerah_id" required>
                                <option value="">-- Pilih Perangkat Daerah --</option>
                                <option value="1">Dinas Pekerjaan Umum dan Penataan Ruang</option>
                                <option value="2">Dinas Pendidikan dan Kebudayaan</option>
                                <option value="3">Dinas Kesehatan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="tahun_anggaran" class="form-label fw-bold">Tahun Anggaran</label>
                            <input type="number" class="form-control" id="tahun_anggaran" name="tahun_anggaran" value="<?= date('Y') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="sub_kegiatan" class="form-label fw-bold">Sub Kegiatan</label>
                            <input type="text" class="form-control" id="sub_kegiatan" name="sub_kegiatan" placeholder="Masukkan Kode / Nama Sub Kegiatan" required>
                        </div>
                        <div class="col-md-6">
                            <label for="pagu_anggaran" class="form-label fw-bold">Total Pagu DPA (Rp)</label>
                            <input type="text" class="form-control month-input fw-bold text-primary fs-6" id="pagu_anggaran" name="pagu_anggaran" value="0" placeholder="0" required autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-info shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="card-title mb-0">Rincian Penarikan Kas Bulanan</h5>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-outline-success" id="btnBagiRata">
                            <i class="fa-solid fa-divide me-1"></i> Bagi Rata 12 Bulan
                        </button>
                        <span class="badge bg-secondary fs-6" id="status-selisih">Pagu DPA Belum Diisi</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0" id="tableAnggaranKas">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th style="width: 20%;">Triwulan</th>
                                    <th style="width: 20%;">Bulan</th>
                                    <th style="width: 35%;">Nilai Anggaran Kas (Rp)</th>
                                    <th style="width: 25%;">Subtotal Triwulan (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $months = [
                                    1 => ['Januari', 'Februari', 'Maret'],
                                    2 => ['April', 'Mei', 'Juni'],
                                    3 => ['Juli', 'Agustus', 'September'],
                                    4 => ['Oktober', 'November', 'Desember']
                                ];
                                foreach ($months as $tw => $bulanList):
                                    foreach ($bulanList as $index => $bulan):
                                        $keyBulan = strtolower($bulan);
                                ?>
                                        <tr>
                                            <?php if ($index === 0): ?>
                                                <td rowspan="4" class="text-center fw-bold align-middle bg-light">
                                                    TRIWULAN <?= $tw ?>
                                                </td>
                                            <?php endif; ?>
                                            <td class="fw-semibold"><?= $bulan ?></td>
                                            <td>
                                                <input type="text"
                                                    name="anggaran_kas[<?= $keyBulan ?>]"
                                                    id="kas_<?= $keyBulan ?>"
                                                    class="form-control month-input input-kas tw-<?= $tw ?>"
                                                    data-tw="<?= $tw ?>"
                                                    placeholder="0"
                                                    value="0"
                                                    autocomplete="off">
                                            </td>
                                            <?php if ($index === 0): ?>
                                                <td rowspan="3" class="text-end fw-bold align-middle bg-subtotal text-success" id="subtotal-tw-<?= $tw ?>">
                                                    Rp 0
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="bg-subtotal">
                                        <td colspan="2" class="text-end text-uppercase">Total Triwulan <?= $tw ?></td>
                                        <td class="text-end text-success fw-bold" id="total-tw-cell-<?= $tw ?>">Rp 0</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="bg-grandtotal">
                                    <th colspan="2" class="text-end">TOTAL ANGGARAN KAS (TAHUNAN)</th>
                                    <th class="text-end text-primary fs-5" id="grand-total">Rp 0</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary" id="btnReset"><i class="fa-solid fa-rotate-left me-1"></i> Reset</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpan">
                        <span class="spinner-border spinner-border-sm me-1 d-none" id="btnSpinner" role="status"></span>
                        <i class="fa-solid fa-floppy-disk me-1" id="btnIcon"></i> Simpan Data
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>
</div>

<!-- <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta2/dist/js/adminlte.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputsKas = document.querySelectorAll('.input-kas');
        const inputPagu = document.getElementById('pagu_anggaran');
        const grandTotalEl = document.getElementById('grand-total');
        const statusSelisihEl = document.getElementById('status-selisih');
        const form = document.getElementById('formAnggaranKas');
        const btnSimpan = document.getElementById('btnSimpan');
        const btnSpinner = document.getElementById('btnSpinner');
        const btnIcon = document.getElementById('btnIcon');
        const csrfInput = document.getElementById('csrf_input');

        function parseNumber(val) {
            if (!val) return 0;
            let cleanVal = val.toString().replace(/[^0-9]/g, '');
            return parseInt(cleanVal, 10) || 0;
        }

        function formatRupiah(num) {
            if (isNaN(num) || num === 0) return '0';
            return num.toLocaleString('id-ID');
        }

        function setupMaskingAndEvents(element) {
            element.value = formatRupiah(parseNumber(element.value));
            ['input', 'keyup', 'change'].forEach(eventType => {
                element.addEventListener(eventType, function() {
                    this.value = formatRupiah(parseNumber(this.value));
                    calculateTotals();
                });
            });
        }

        setupMaskingAndEvents(inputPagu);
        inputsKas.forEach(input => setupMaskingAndEvents(input));

        function calculateTotals() {
            let grandTotal = 0;
            let paguTotal = parseNumber(inputPagu.value);

            for (let tw = 1; tw <= 4; tw++) {
                let twInputs = document.querySelectorAll(`.tw-${tw}`);
                let twTotal = 0;

                twInputs.forEach(input => {
                    twTotal += parseNumber(input.value);
                });

                let formattedSubtotal = 'Rp ' + formatRupiah(twTotal);
                document.getElementById(`subtotal-tw-${tw}`).innerText = formattedSubtotal;
                document.getElementById(`total-tw-cell-${tw}`).innerText = formattedSubtotal;

                grandTotal += twTotal;
            }

            grandTotalEl.innerText = 'Rp ' + formatRupiah(grandTotal);

            let selisih = paguTotal - grandTotal;
            if (paguTotal > 0) {
                if (selisih === 0) {
                    statusSelisihEl.className = 'badge bg-success fs-6';
                    statusSelisihEl.innerText = 'Sesuai Pagu DPA (Balance)';
                } else if (selisih > 0) {
                    statusSelisihEl.className = 'badge bg-warning text-dark fs-6';
                    statusSelisihEl.innerText = `Belum Dialokasikan: Rp ${formatRupiah(selisih)}`;
                } else {
                    statusSelisihEl.className = 'badge bg-danger fs-6';
                    statusSelisihEl.innerText = `Melebihi Pagu DPA: Rp ${formatRupiah(Math.abs(selisih))}`;
                }
            } else {
                statusSelisihEl.className = 'badge bg-secondary fs-6';
                statusSelisihEl.innerText = 'Pagu DPA Belum Diisi';
            }
        }

        // Fitur Bagi Rata Pagu
        document.getElementById('btnBagiRata').addEventListener('click', function() {
            let paguTotal = parseNumber(inputPagu.value);

            if (paguTotal <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pagu Kosong',
                    text: 'Silakan isi Total Pagu DPA terlebih dahulu.',
                    confirmButtonColor: '#0d6efd'
                });
                return;
            }

            let nilaiBiasa = Math.floor(paguTotal / 12);
            let sisa = paguTotal - (nilaiBiasa * 11);

            inputsKas.forEach((input, index) => {
                if (index === 11) {
                    input.value = formatRupiah(sisa);
                } else {
                    input.value = formatRupiah(nilaiBiasa);
                }
            });

            calculateTotals();

            Swal.fire({
                icon: 'success',
                title: 'Berhasil Dibagi Rata',
                text: 'Pagu DPA berhasil dialokasikan rata ke 12 bulan.',
                timer: 1500,
                showConfirmButton: false
            });
        });

        document.getElementById('btnReset').addEventListener('click', function() {
            form.reset();
            setTimeout(() => {
                inputPagu.value = '0';
                inputsKas.forEach(input => input.value = '0');
                calculateTotals();
            }, 50);
        });

        // AJAX Form Submit
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            let paguTotal = parseNumber(inputPagu.value);
            let grandTotal = parseNumber(grandTotalEl.innerText);

            if (paguTotal <= 0) {
                Swal.fire('Perhatian', 'Pagu DPA tidak boleh 0!', 'warning');
                return;
            }

            if (paguTotal !== grandTotal) {
                Swal.fire({
                    icon: 'error',
                    title: 'Anggaran Tidak Balance!',
                    html: `Total Pagu DPA: <b>Rp ${formatRupiah(paguTotal)}</b><br>Total Anggaran Kas: <b>Rp ${formatRupiah(grandTotal)}</b><br><br>Silakan sesuaikan alokasi hingga selisih Rp 0.`,
                    confirmButtonColor: '#d33'
                });
                return;
            }

            btnSimpan.disabled = true;
            btnSpinner.classList.remove('d-none');
            btnIcon.classList.add('d-none');

            try {
                let formData = new FormData(form);

                let response = await fetch("<?= site_url('anggaran-kas/store') ?>", {
                    method: "POST",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: formData
                });

                let rawText = await response.text();
                let result;

                try {
                    result = JSON.parse(rawText);
                } catch (jsonErr) {
                    console.error("Server Raw Response:", rawText);
                    throw new Error("Respon Server BUKAN JSON: " + rawText.substring(0, 150));
                }

                if (result.csrf_token) {
                    csrfInput.value = result.csrf_token;
                }

                if (response.ok && result.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil Disimpan!',
                        text: result.message,
                        confirmButtonColor: '#0d6efd'
                    }).then(() => {
                        form.reset();
                        inputPagu.value = '0';
                        inputsKas.forEach(input => input.value = '0');
                        calculateTotals();
                    });
                } else {
                    let errorMessage = result.message || 'Terjadi kesalahan sistem saat menyimpan data.';
                    if (result.errors) {
                        if (typeof result.errors === 'object') {
                            errorMessage = Object.values(result.errors).join('<br>');
                        } else {
                            errorMessage = result.errors;
                        }
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menyimpan',
                        html: errorMessage,
                        confirmButtonColor: '#d33'
                    });
                }
            } catch (error) {
                console.error('AJAX Catch Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memproses Data',
                    html: `<div style="text-align:left; font-size: 0.85rem;"><p><b>Detail Error:</b></p><code>${error.message}</code></div>`,
                    confirmButtonColor: '#d33'
                });
            } finally {
                btnSimpan.disabled = false;
                btnSpinner.classList.add('d-none');
                btnIcon.classList.remove('d-none');
            }
        });

        calculateTotals();
    });
</script>
<?= $this->endSection() ?>