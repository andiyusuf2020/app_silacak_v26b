<?= $this->extend('template/layout') ?>
<!--begin::Theme Init (prevents flash of incorrect theme on load, #6043)-->
<style>
    .wizard-steps {
        counter-reset: step;
        list-style: none;
        padding: 0;
        display: flex;
        justify-content: space-between;
        position: relative;
    }

    .wizard-steps::before {
        content: '';
        position: absolute;
        top: 1rem;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--bs-border-color);
        z-index: 0;
    }

    .wizard-steps li {
        position: relative;
        z-index: 1;
        background: var(--bs-body-bg);
        padding: 0 0.75rem;
        text-align: center;
        color: var(--bs-secondary-color);
        font-size: 0.875rem;
    }

    .wizard-steps li::before {
        counter-increment: step;
        content: counter(step);
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        margin: 0 auto 0.5rem;
        border-radius: 50%;
        background: var(--bs-body-tertiary-bg);
        border: 2px solid var(--bs-border-color);
        color: var(--bs-secondary-color);
        font-weight: 600;
    }

    .wizard-steps li.active {
        color: var(--bs-primary);
        font-weight: 600;
    }

    .wizard-steps li.active::before {
        background: var(--bs-primary);
        border-color: var(--bs-primary);
        color: #fff;
    }

    .wizard-steps li.completed::before {
        background: var(--bs-success);
        border-color: var(--bs-success);
        color: #fff;
        content: '\f633';
        font-family: 'bootstrap-icons';
    }
</style>
<script>
    (() => {
        'use strict';
        const STORAGE_KEY = 'lte-theme';
        let stored = null;
        try {
            stored = localStorage.getItem(STORAGE_KEY);
        } catch {
            // localStorage may be unavailable (private mode, sandboxed iframe).
        }
        const prefersDark = globalThis.matchMedia('(prefers-color-scheme: dark)').matches;
        // Mirror the resolution in _scripts.astro: explicit "dark"/"light" win,
        // otherwise ("auto" or unset) fall back to the OS preference.
        let resolved = 'light';
        if (stored === 'dark' || stored === 'light') {
            resolved = stored;
        } else if (prefersDark) {
            resolved = 'dark';
        }
        document.documentElement.setAttribute('data-bs-theme', resolved);
        document.documentElement.style.colorScheme = resolved;
    })();
</script>
<!--end::Theme Init-->
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
                        <div class="row justify-content-center">
                            <div class="col-lg-20 col-xl-10">
                                <div class="card">
                                    <div class="card-body p-4">
                                        <!-- Step indicators -->
                                        <ol class="wizard-steps mb-4" id="wizard-steps">
                                            <li class="active" data-step="0">Data Utama</li>
                                            <li data-step="1">Profile</li>
                                            <li data-step="2">Preferences</li>
                                            <li data-step="3">Review</li>
                                        </ol>
                                        <!-- Form -->
                                        <form id="wizard-form" novalidate>
                                            <!-- Step 1 -->
                                            <fieldset class="wizard-step" data-step="0">
                                                <h2 class="h5 mb-3">Keterlibatan Perangkat Daerah pada Program Desaku Maju</h2>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="wz-first">Kelompok Kerja (Pokja)</label>
                                                        <input name="pokja" type="text" class="form-control" id="wz-first" required />
                                                        <div class="invalid-feedback">Kelompok Kerja (Pokja) harus diisi.</div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="wz-last">Dukungan Program</label>
                                                        <input name="dukungan_program" type="text" class="form-control" id="wz-last" required />
                                                        <div class="invalid-feedback">Dukungan Program harus diisi.</div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="wz-company">Program/Kegiatan yang diintervensi</label>
                                                        <input type="text" class="form-control" id="wz-company" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="wz-role"> Role </label>
                                                        <select class="form-select" id="wz-role" required>
                                                            <option value="">Choose&hellip;</option>
                                                            <option>Founder / CEO</option>
                                                            <option>Engineering</option>
                                                            <option>Design</option>
                                                            <option>Marketing</option>
                                                            <option>Other</option>
                                                        </select>
                                                        <div class="invalid-feedback">Please select a role.</div>
                                                    </div>
                                                </div>

                                            </fieldset>

                                            <!-- Step 2 -->
                                            <fieldset class="wizard-step d-none" data-step="1">
                                                <h2 class="h5 mb-3">Tell us about yourself</h2>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="wz-first"> First name </label>
                                                        <input type="text" class="form-control" id="wz-first" required />
                                                        <div class="invalid-feedback">First name is required.</div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="wz-last"> Last name </label>
                                                        <input type="text" class="form-control" id="wz-last" required />
                                                        <div class="invalid-feedback">Last name is required.</div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="wz-company"> Company </label>
                                                        <input type="text" class="form-control" id="wz-company" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="wz-role"> Role </label>
                                                        <select class="form-select" id="wz-role" required>
                                                            <option value="">Choose&hellip;</option>
                                                            <option>Founder / CEO</option>
                                                            <option>Engineering</option>
                                                            <option>Design</option>
                                                            <option>Marketing</option>
                                                            <option>Other</option>
                                                        </select>
                                                        <div class="invalid-feedback">Please select a role.</div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <!-- Step 3 -->
                                            <fieldset class="wizard-step d-none" data-step="2">
                                                <h2 class="h5 mb-3">Notification preferences</h2>
                                                <div class="form-check form-switch mb-2">
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        id="wz-notif-product"
                                                        role="switch"
                                                        checked />
                                                    <label class="form-check-label" for="wz-notif-product">
                                                        Product updates &amp; releases
                                                    </label>
                                                </div>
                                                <div class="form-check form-switch mb-2">
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        id="wz-notif-security"
                                                        role="switch"
                                                        checked />
                                                    <label class="form-check-label" for="wz-notif-security">
                                                        Security alerts
                                                    </label>
                                                </div>
                                                <div class="form-check form-switch mb-3">
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        id="wz-notif-marketing"
                                                        role="switch" />
                                                    <label class="form-check-label" for="wz-notif-marketing">
                                                        Marketing &amp; tips
                                                    </label>
                                                </div>
                                                <label class="form-label" for="wz-frequency"> Digest frequency </label>
                                                <select class="form-select" id="wz-frequency">
                                                    <option>Real time</option>
                                                    <option selected>Daily</option>
                                                    <option>Weekly</option>
                                                    <option>Never</option>
                                                </select>
                                            </fieldset>
                                            <!-- Step 4 -->
                                            <fieldset class="wizard-step d-none" data-step="3">
                                                <h2 class="h5 mb-3">Review &amp; confirm</h2>
                                                <dl class="row mb-3" id="wz-summary"></dl>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="wz-terms" required />
                                                    <label class="form-check-label" for="wz-terms">
                                                        I agree to the <a href="#">terms of service</a>.
                                                    </label>
                                                    <div class="invalid-feedback">You must accept the terms to continue.</div>
                                                </div>
                                            </fieldset>
                                            <!-- Navigation -->
                                            <div class="d-flex justify-content-between mt-4">
                                                <button
                                                    type="button"
                                                    class="btn btn-outline-secondary"
                                                    id="wz-prev"
                                                    disabled>
                                                    <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>
                                                    Previous
                                                </button>
                                                <button type="button" class="btn btn-primary" id="wz-next">
                                                    Next
                                                    <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
                                                </button>
                                                <button type="submit" class="btn btn-success d-none" id="wz-submit">
                                                    <i class="bi bi-check-lg me-1" aria-hidden="true"></i>
                                                    Submit
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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
<script>
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
</script>
<!--end::OverlayScrollbars Configure-->
<script>
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
</script>
<script type="module" src="https://static.cloudflareinsights.com/beacon.min.js/v4513226cdae34746b4dedf0b4dfa099e1781791509496" integrity="sha512-ZE9pZaUXND66v380QUtch/5sE9tPFh2zg45pR2PB0CVkCtOREv2AJKkSidISWkysEuQ0EH8faUU5du78bx87UQ==" data-cf-beacon='{"version":"2024.11.0","token":"2437d112162f4ec4b63c3ca0eb38fb20","r":1}' crossorigin="anonymous"></script>
<?= $this->endSection() ?>