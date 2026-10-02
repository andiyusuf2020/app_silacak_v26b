<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
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

<!-- Main Content -->
<div class="app-content">
    <div class="container-fluid">

        <!-- Flash Alert Success/Error -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i><?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i><?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('anggaran-kas/store') ?>" method="post" id="formAnggaranKas">
            <?= csrf_field() ?>

            <!-- Card Header Info -->
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
                                <option value="1" <?= old('perangkat_daerah_id') == 1 ? 'selected' : '' ?>>Dinas Pekerjaan Umum dan Penataan Ruang</option>
                                <option value="2" <?= old('perangkat_daerah_id') == 2 ? 'selected' : '' ?>>Dinas Pendidikan dan Kebudayaan</option>
                                <option value="3" <?= old('perangkat_daerah_id') == 3 ? 'selected' : '' ?>>Dinas Kesehatan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="tahun_anggaran" class="form-label fw-bold">Tahun Anggaran</label>
                            <input type="number" class="form-control" id="tahun_anggaran" name="tahun_anggaran" value="<?= old('tahun_anggaran', date('Y')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="sub_kegiatan" class="form-label fw-bold">Sub Kegiatan</label>
                            <input type="text" class="form-control" id="sub_kegiatan" name="sub_kegiatan" value="<?= old('sub_kegiatan') ?>" placeholder="Masukkan Kode / Nama Sub Kegiatan" required>
                        </div>
                        <div class="col-md-6">
                            <label for="pagu_anggaran" class="form-label fw-bold">Total Pagu DPA (Rp)</label>
                            <input type="text" class="form-control month-input fw-bold text-primary fs-6" id="pagu_anggaran" name="pagu_anggaran" value="<?= old('pagu_anggaran', '0') ?>" placeholder="0" required autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Form Anggaran Kas Bulanan -->
            <div class="card card-outline card-info shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Rincian Penarikan Kas Bulanan</h5>
                    <div class="card-tools">
                        <span class="badge bg-secondary" id="status-selisih">Pagu DPA Belum Diisi</span>
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
                                        $oldVal = old("anggaran_kas.{$keyBulan}", '0');
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
                                                    class="form-control month-input input-kas tw-<?= $tw ?>"
                                                    data-tw="<?= $tw ?>"
                                                    placeholder="0"
                                                    value="<?= $oldVal ?>"
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
                    <button type="reset" class="btn btn-secondary" id="btnReset"><i class="fa-solid fa-rotate-left me-1"></i> Reset</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpan"><i class="fa-solid fa-floppy-disk me-1"></i> Simpan Data</button>
                </div>
            </div>
        </form>

    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputsKas = document.querySelectorAll('.input-kas');
        const inputPagu = document.getElementById('pagu_anggaran');
        const grandTotalEl = document.getElementById('grand-total');
        const statusSelisihEl = document.getElementById('status-selisih');

        // 1. Fungsi Parser Murni: Konversi String Apa Pun Menjadi Integer Positif
        function parseNumber(val) {
            if (!val) return 0;
            // Hapus semua karakter non-digit (termasuk titik pemisah ribuan)
            let cleanVal = val.toString().replace(/[^0-9]/g, '');
            return parseInt(cleanVal, 10) || 0;
        }

        // 2. Fungsi Format Angka ke Tampilan Rupiah (id-ID)
        function formatRupiah(num) {
            if (isNaN(num) || num === 0) return '0';
            return num.toLocaleString('id-ID');
        }

        // 3. Mengatur Masking Input dan Listener Realtime
        function setupMaskingAndEvents(element) {
            // Format nilai awal saat pertama kali dimuat (mencegah bug dari nilai old())
            let initialNum = parseNumber(element.value);
            element.value = formatRupiah(initialNum);

            // Binding event terpadu (input, keyup, change)
            ['input', 'keyup', 'change'].forEach(eventType => {
                element.addEventListener(eventType, function() {
                    let currentNum = parseNumber(this.value);
                    this.value = formatRupiah(currentNum);
                    calculateTotals();
                });
            });
        }

        // Terapkan ke Pagu dan Seluruh Input Bulan
        setupMaskingAndEvents(inputPagu);
        inputsKas.forEach(input => setupMaskingAndEvents(input));

        // 4. Kalkulasi Real-time Matematika Murni
        function calculateTotals() {
            let grandTotal = 0;
            let paguTotal = parseNumber(inputPagu.value);

            // Hitung Subtotal per Triwulan (TW 1 - 4)
            for (let tw = 1; tw <= 4; tw++) {
                let twInputs = document.querySelectorAll(`.tw-${tw}`);
                let twTotal = 0;

                twInputs.forEach(input => {
                    twTotal += parseNumber(input.value);
                });

                let formattedSubtotal = 'Rp ' + formatRupiah(twTotal);

                let elSubtotalTW = document.getElementById(`subtotal-tw-${tw}`);
                let elCellTW = document.getElementById(`total-tw-cell-${tw}`);

                if (elSubtotalTW) elSubtotalTW.innerText = formattedSubtotal;
                if (elCellTW) elCellTW.innerText = formattedSubtotal;

                grandTotal += twTotal;
            }

            // Update Grand Total
            grandTotalEl.innerText = 'Rp ' + formatRupiah(grandTotal);

            // Validasi Keseimbangan (Selisih)
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

        // Jalankan kalkulasi awal saat halaman siap
        calculateTotals();

        // Event Handler untuk Tombol Reset
        document.getElementById('btnReset').addEventListener('click', function() {
            setTimeout(function() {
                inputPagu.value = '0';
                inputsKas.forEach(input => input.value = '0');
                calculateTotals();
            }, 50);
        });

        // Guard Submit Client-side
        document.getElementById('formAnggaranKas').addEventListener('submit', function(e) {
            let paguTotal = parseNumber(inputPagu.value);
            let grandTotal = parseNumber(grandTotalEl.innerText);

            if (paguTotal !== grandTotal) {
                e.preventDefault();
                alert(`Gagal Menyimpan!\n\n- Total Pagu DPA : Rp ${formatRupiah(paguTotal)}\n- Total Anggaran Kas : Rp ${formatRupiah(grandTotal)}\n\nPastikan status dalam kondisi 'Sesuai Pagu DPA (Balance)'.`);
            }
        });
    });
</script>
<?= $this->endSection() ?>