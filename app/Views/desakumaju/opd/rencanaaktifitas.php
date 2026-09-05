<?= $this->extend('template/layout') ?>

<?= $this->section('content') ?>
<!--begin::Container-->
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
        <div class="col-12">
            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= esc($titlepage) ?></h3><br>
                    <h3 class="card-title">Perangkat Daerah : <?= esc($nama_subunit) ?></h3>
                </div>
                <div class="card-footer">Form Pengisian Data Utama Dukungan Program Desaku Maju Perangkat Daerah</div>
                <div class="app-content">
                    <div class="container-fluid">
                        <div class="col-lg-12">
                            <div class="card card-info card-outline mb-4">
                                <?php if (session()->has('errors')): ?>
                                    <div class="alert alert-danger" role="alert">
                                        <ul>
                                            <?php foreach (session('errors') as $error): ?>
                                                <li><?= esc($error) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                                <div class="card-header">
                                    <div class="card-title">Keterlibatan Perangkat Daerah pada Program Desaku Maju</div>
                                </div>
                                <!-- <form class="needs-validation" novalidate> -->
                                <?= form_open_multipart('desakumaju/opd/simpanketerlibatan', 'class="needs-validation" '); ?>
                                <?= csrf_field(); ?>
                                <div class="card-body">
                                    <div class="row g-9">
                                        <div class="col-md-6">
                                            <label for="validationCustom01" class="form-label">Kelompok Kerja (Pokja) :</label>
                                            <select name="pokja" class="form-select" id="validationCustom01" required>
                                                <option selected disabled value="">Choose&hellip;</option>
                                                <option>Pokja 1</option>
                                                <option>Pokja 2</option>
                                                <option>Pokja 3</option>
                                            </select>
                                            <div class="invalid-feedback">Please select a valid state.</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="validationCustom02" class="form-label">Program Utama Desaku Maju :</label>
                                            <select name="program_utama" class="form-select" id="validationCustom02" required>
                                                <option selected disabled value="">Choose&hellip;</option>
                                                <option>Program Utama 1</option>
                                                <option>Program Utama 2</option>
                                                <option>Program Utama 3</option>
                                            </select>
                                            <div class="invalid-feedback">Please select a valid state.</div>
                                        </div>
                                        <!-- <div class="col-md-6">
                                                <label for="validationCustomUsername" class="form-label">Username</label>
                                                <div class="input-group has-validation">
                                                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        id="validationCustomUsername"
                                                        aria-describedby="inputGroupPrepend"
                                                        required />
                                                    <div class="invalid-feedback">Please choose a username.</div>
                                                </div>
                                            </div> -->
                                        <div class="col-md-6">
                                            <label for="validationCustom03" class="form-label">Program Desakumaju yang di Intervensi </label>
                                            <select name="program_intervensi" class="form-select" id="validationCustom03" required>
                                                <option selected disabled value="">Choose&hellip;</option>
                                                <option>Program Intervensi 1</option>
                                                <option>Program Intervensi 2</option>
                                                <option>Program Intervensi 3</option>
                                            </select>
                                            <div class="invalid-feedback">Please provide a valid program.</div>
                                        </div>
                                        <!-- <div class="col-md-6">
                                                <label for="validationCustom04" class="form-label">State</label>
                                                <select class="form-select" id="validationCustom04" required>
                                                    <option selected disabled value="">Choose&hellip;</option>
                                                    <option>California</option>
                                                    <option>Washington</option>
                                                    <option>Tennessee</option>
                                                </select>
                                                <div class="invalid-feedback">Please select a valid state.</div>
                                            </div> -->
                                        <div class="col-md-6">
                                            <label for="form-file-multi" class="form-label">Peraturan/SK/Dasar Pelaksanaan: </label>
                                            <input name="file_pdf" class="form-control" type="file" id="form-file-multi" multiple required uploaded />
                                            <div class="invalid-feedback">Please upload the required documents.</div>
                                        </div>
                                        <br>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    value=""
                                                    id="invalidCheck"
                                                    required />
                                                <label class="form-check-label" for="invalidCheck">
                                                    Agree to terms and conditions
                                                </label>
                                                <div class="invalid-feedback">You must agree before submitting.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-info" type="submit">Submit form</button>
                                </div>
                                <?= form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!--end::Row-->
</div>
<!--end::Container-->
<!-- <script>
    const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
    const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
    };
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);

        // Disable OverlayScrollbars on mobile devices to prevent touch interference
        const isMobile = window.innerWidth <= 992;

        if (
            sidebarWrapper &&
            OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined &&
            !isMobile
        ) {
            OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                scrollbars: {
                    theme: Default.scrollbarTheme,
                    autoHide: Default.scrollbarAutoHide,
                    clickScroll: Default.scrollbarClickScroll,
                },
            });
        }
    });
</script> -->
<!--end::OverlayScrollbars Configure-->
<!-- <script>
    // Enable Bootstrap-style validation for forms marked with .needs-validation
    // or .needs-validation-tooltip. Prevents submission if any field is invalid.
    (() => {
        'use strict';
        const selector = '.needs-validation, .needs-validation-tooltip';
        for (const form of document.querySelectorAll(selector)) {
            form.addEventListener(
                'submit',
                (event) => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                },
                false,
            );
        }
    })();
</script> -->
<!-- <script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('wizard-form');
        const steps = form.querySelectorAll('.wizard-step');
        const indicators = document.querySelectorAll('#wizard-steps li');
        const prevBtn = document.getElementById('wz-prev');
        const nextBtn = document.getElementById('wz-next');
        const submitBtn = document.getElementById('wz-submit');
        let current = 0;

        const show = (i) => {
            steps.forEach((s, idx) => s.classList.toggle('d-none', idx !== i));
            indicators.forEach((li, idx) => {
                li.classList.toggle('active', idx === i);
                li.classList.toggle('completed', idx < i);
            });
            prevBtn.disabled = i === 0;
            const last = i === steps.length - 1;
            nextBtn.classList.toggle('d-none', last);
            submitBtn.classList.toggle('d-none', !last);
            if (last) renderSummary();
        };

        const validateStep = (i) => {
            const step = steps[i];
            const fields = step.querySelectorAll('input, select, textarea');
            let valid = true;
            fields.forEach((field) => {
                field.classList.remove('is-invalid');
                if (!field.checkValidity()) {
                    field.classList.add('is-invalid');
                    valid = false;
                }
            });
            // Password match check on step 0
            // if (i === 0) {
            //     const p1 = document.getElementById('wz-password');
            //     const p2 = document.getElementById('wz-password2');
            //     if (p1.value !== p2.value) {
            //         p2.classList.add('is-invalid');
            //         valid = false;
            //     }
            // }
            return valid;
        };

        const renderSummary = () => {
            const summary = document.getElementById('wz-summary');
            const get = (id) => document.getElementById(id);
            const rows = [
                ['Email', get('wz-email').value],
                ['Username', get('wz-username').value],
                ['Name', `${get('wz-first').value} ${get('wz-last').value}`],
                ['Company', get('wz-company').value || '—'],
                ['Role', get('wz-role').value || '—'],
                ['Digest', get('wz-frequency').value],
            ];
            summary.innerHTML = rows
                .map(
                    ([k, v]) =>
                    `<dt class="col-sm-4 text-secondary fw-normal">${k}</dt><dd class="col-sm-8 fw-semibold">${v}</dd>`,
                )
                .join('');
        };

        nextBtn.addEventListener('click', () => {
            if (!validateStep(current)) return;
            if (current < steps.length - 1) {
                current++;
                show(current);
            }
        });

        prevBtn.addEventListener('click', () => {
            if (current > 0) {
                current--;
                show(current);
            }
        });

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            if (!validateStep(current)) return;
            alert('Wizard complete! Form would submit here.');
        });

        show(0);
    });
</script> -->
<!-- <script type="module" src="https://static.cloudflareinsights.com/beacon.min.js/v4513226cdae34746b4dedf0b4dfa099e1781791509496" integrity="sha512-ZE9pZaUXND66v380QUtch/5sE9tPFh2zg45pR2PB0CVkCtOREv2AJKkSidISWkysEuQ0EH8faUU5du78bx87UQ==" data-cf-beacon='{"version":"2024.11.0","token":"2437d112162f4ec4b63c3ca0eb38fb20","r":1}' crossorigin="anonymous"></script> -->
<?= $this->endSection() ?>