<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header Halaman -->
    <div class="row mb-3">
        <div class="col-sm-6">
            <h3 class="m-0 font-weight-bold text-dark">Form Pengisian Data Anggaran Kas</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Anggaran Kas</li>
            </ol>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card card-primary card-outline shadow-sm">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">
                <i class="bi bi-pencil-square me-2"></i>Form Pengisian Data Anggaran Kas Perangkat Daerah XX Kabupaten XX
            </h3>
        </div>

        <!-- Form Start -->
        <form action="<?= base_url('anggaran-kas/simpan') ?>" method="post" id="formAnggaranKas">
            <?= csrf_field() ?>

            <div class="card-body">
                <!-- Informasi Umum -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="tahun_anggaran" class="form-label font-weight-bold">Tahun Anggaran</label>
                        <select class="form-select" id="tahun_anggaran" name="tahun_anggaran" required>
                            <option value="" selected disabled>-- Pilih Tahun --</option>
                            <?php
                            $tahunSekarang = date('Y');
                            for ($t = $tahunSekarang; $t <= $tahunSekarang + 2; $t++) : ?>
                                <option value="<?= $t ?>"><?= $t ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="kode_rekening" class="form-label font-weight-bold">Program / Kegiatan / Sub-Kegiatan</label>
                        <input type="text" class="form-control" id="kode_rekening" name="kode_rekening" placeholder="Masukkan Kode / Nama Kegiatan" required>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="mb-3 text-primary"><i class="bi bi-calendar3 me-2"></i>Rincian Anggaran Kas Bulanan (Rp)</h5>

                <!-- Input Per Bulan (Januari - Desember) -->
                <?php
                $bulanList = [
                    1 => 'Januari',
                    2 => 'Februari',
                    3 => 'Maret',
                    4 => 'April',
                    5 => 'Mei',
                    6 => 'Juni',
                    7 => 'Juli',
                    8 => 'Agustus',
                    9 => 'September',
                    10 => 'Oktober',
                    11 => 'November',
                    12 => 'Desember'
                ];
                ?>

                <div class="row g-3">
                    <?php foreach ($bulanList as $num => $namaBulan) : ?>
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="bulan_<?= $num ?>" class="form-label font-weight-bold"><?= $namaBulan ?></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text"
                                        class="form-control input-bulan"
                                        id="bulan_<?= $num ?>"
                                        name="anggaran[<?= $num ?>]"
                                        placeholder="0"
                                        value="0"
                                        inputmode="numeric"
                                        required>
                                </div>
                                <div class="invalid-feedback" id="feedback_<?= $num ?>">
                                    Input hanya boleh berisi angka!
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <hr class="my-4">

                <!-- Total Anggaran Real-time -->
                <div class="row justify-content-end">
                    <div class="col-md-5">
                        <div class="card bg-light border-primary">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 font-weight-bold text-dark">Total Anggaran Kas:</h5>
                                    <h4 class="mb-0 font-weight-bold text-primary" id="totalAnggaran">Rp 0</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Footer -->
            <div class="card-footer text-end">
                <button type="button" class="btn btn-secondary me-2" id="btnReset">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<!-- Section Script JavaScript -->
<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputBulan = document.querySelectorAll('.input-bulan');
        const totalDisplay = document.getElementById('totalAnggaran');
        const btnReset = document.getElementById('btnReset');
        const form = document.getElementById('formAnggaranKas');

        // Fungsi Format Angka ke Rupiah (Tanpa 'Rp')
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        // Fungsi Menghitung Total Anggaran Real-Time
        function hitungTotal() {
            let total = 0;
            inputBulan.forEach(input => {
                // Menghapus karakter non-digit untuk mengambil nilai murni angka
                let rawValue = input.value.replace(/[^0-9]/g, '');
                let numericValue = parseInt(rawValue, 10);

                if (!isNaN(numericValue)) {
                    total += numericValue;
                }
            });

            // Tampilkan total yang diformat
            totalDisplay.textContent = 'Rp ' + formatRupiah(total);
        }

        // Event listener pada setiap field input bulan
        inputBulan.forEach(input => {
            // 1. Validasi saat diketik (input/keydown): Cegah karakter selain angka
            input.addEventListener('input', function() {
                // Bersihkan karakter selain angka
                let sanitizedValue = this.value.replace(/[^0-9]/g, '');

                if (sanitizedValue === '') {
                    this.value = '';
                } else {
                    // Format tampilan dengan ribuan (titik)
                    let numericVal = parseInt(sanitizedValue, 10);
                    this.value = formatRupiah(numericVal);
                }

                // Hitung total secara Real-Time
                hitungTotal();
            });

            // 2. Cegah input karakter non-numeric melalui keyboard secara langsung
            input.addEventListener('keydown', function(e) {
                // Tombol yang diizinkan: Backspace, Delete, Tab, Escape, Enter, Arrow keys, Ctrl/Cmd shortcuts
                const allowedKeys = [
                    'Backspace', 'Delete', 'Tab', 'Escape', 'Enter',
                    'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Home', 'End'
                ];

                if (
                    allowedKeys.includes(e.key) ||
                    (e.ctrlKey === true || e.metaKey === true) // Izinkan Ctrl+A, Ctrl+C, Ctrl+V, dsb.
                ) {
                    return; // Izinkan aksi default
                }

                // Jika tombol bukan angka (0-9), cegah pengetikan (preventDefault)
                if (!/^[0-9]$/.test(e.key)) {
                    e.preventDefault();
                }
            });

            // 3. UX saat fokus (Focus & Blur)
            input.addEventListener('focus', function() {
                if (this.value === '0') {
                    this.value = '';
                }
            });

            input.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.value = '0';
                    hitungTotal();
                }
            });
        });

        // Handler untuk tombol Reset
        btnReset.addEventListener('click', function() {
            form.reset();
            inputBulan.forEach(input => {
                input.value = '0';
            });
            hitungTotal();
        });

        // Jalankan perhitungan awal saat halaman dimuat
        hitungTotal(); <
        FollowUp label = "Mau saya buatkan Controller & Model CodeIgniter 4 untuk memproses form ini?"
        query = "Buatkan Controller dan Model CodeIgniter 4 untuk memproses simpan data dari form anggaran kas tersebut." / >
    });
</script>
<?= $this->endSection() ?>