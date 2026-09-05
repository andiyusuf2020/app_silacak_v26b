<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- SELECT2 EXAMPLE -->
    <div class="card card-default">
        <div class="card card-primary card-outline mb-4">
            <!--begin::Header-->
            <div class="card-header">
                <div class="card-title">Form Input Target Sasaran Kegiatan Pokok</div>
            </div>
            <div class="card-header">
                <?php if (session()->getFlashdata('peringatan')): ?>
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            <p><?= session()->getFlashdata('peringatan') ?></p>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('message')): ?>
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            <p><?= session()->getFlashdata('message') ?></p>
                            <button onclick="history.back()" class="btn btn-secondary">Kembali</button>

                        </ul>
                    </div>
                <?php endif; ?>
                <?php if (session()->has('errors')): ?>
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            <?php foreach (session('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
            <!--end::Header-->
            <!--begin::Form-->
            <?= form_open_multipart('capkin/simpankegpokok', 'id="myForm"'); ?>
            <?= csrf_field(); ?>
            <!--begin::Body-->
            <div class="card-body">
                <div id="input-container">
                    <div class="mb-3">
                        <label for="subkegiatan" class="form-label">SUBKEGIATAN</label>
                        <h2><?= esc($subkegiatan)  ?></h2>
                        <!-- <a href="
                          <?php
                            // hash_url('capkin/kegpokok/', [
                            //     'page' => 'kegpokok',
                            //     'action' => 'ubahsubkeg',
                            //     'idUbah' => $id_targetsubkeg,
                            // ]);
                            ?> " <button type="button" class="btn btn-outline-primary">
                            <i class="bi bi-database-add"></i>UBAH SUBKEGIATAN
                            </button></a> -->
                    </div>
                    <br>
                    <div class="mb-3">
                        <label for="kegpokok" class="form-label">KEGIATAN POKOK</label>
                        <button type="button" class="btn btn-secondary" id="tambah-input">
                            <i class="bi bi-plus-circle"></i> Tambah Data
                        </button>
                    </div>
                    <div class="input-group row g-3">
                        <div class="col-md-2">
                            <label for="target" class="form-label">Target/Sasaran</label>
                            <input type="number" name="data[0][vol_target]" class="form-control"
                                oninput="this.value = this.value.replace(/[^0-9\s]/g, '')"
                                placeholder="jumlah sasaran/target" required>
                            <input type="text" name="data[0][sat_target]" class="form-control"
                                oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')"
                                placeholder="satuan sasaran/target" required>
                            <?php if ($id == '') {
                            } else { ?>
                                <input type="hidden" name="data[0][id]" value="<?php esc($id) ?>" ?>
                            <?php } ?>
                            <input type="hidden" name="data[0][id_targetsubkeg]" value="<?= esc($id_targetsubkeg) ?>" ?>
                            <input type="hidden" name="data[0][tahun]" value="<?= esc($tahundt) ?>" ?>
                            <input type="hidden" name="data[0][kd_subunit]" value="<?= esc($kd_subunit) ?>" ?>
                            <input type="hidden" name="data[0][kd_subkegiatan]" value="<?= esc($kd_subkegiatan) ?>" ?>
                            <input type="hidden" name="data[0][pagu]" value="<?= esc($pagu) ?>" ?>

                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-md-5">
                            <label for="uraian" class="form-label">Uraian Target Pelaksanaan Aktivitas/Kegiatan Subkegiatan Tahun <?= esc($tahun) ?></label>
                            <textarea id="uraian" name="data[0][uraian_target]" class="form-control" required
                                oninput="this.value = this.value.replace(/[^a-zA-Z0-9,.%()?/\s]/g, '')"
                                placeholder="uraian/penjelasan mengenai sasaran" rows="5"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="hasil" class="form-label">Hasil yang akan dicapai Tahun <?= esc($tahun) ?></label>
                            <textarea name="data[0][hasil]" class="form-control" id="hasil" required
                                oninput="this.value = this.value.replace(/[^a-zA-Z0-9,.%()?/\s]/g, '')"
                                placeholder="diawali dengan Ter...." rows="5"></textarea>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger remove-input" disabled>
                                <i class="bi bi-trash"></i>
                            </button>
                        </div><br>
                        <div class="col-md-3">
                            <label for="hasil" class="form-label">Mendukung Sasaran Pembangunan (pilih):</label>
                            <?= form_dropdown('data[0][id_progprioritas]', $dataprogprioritas, $inputs[0]['id_pprio'] ?? null, ['class' => 'form-select', 'required']); ?>
                        </div>
                        <?php if ($cekopdunggulan) { ?>
                            <div class="col-md-3">
                                <label for="hasil" class="form-label">Mendukung Program Unggulan Pembangunan (pilih):</label>
                                <?= form_dropdown('data[0][id_progunggulan]', $dataprogunggulan, $inputs[0]['id_pung'] ?? null, ['class' => 'form-select', 'required']); ?>
                            </div>
                        <?php } else { ?>
                            <input type="hidden" name="data[0][id_progunggulan]" value="0" ?>
                        <?php } ?>
                        <?php if ($cekopdtematik) { ?>
                            <div class="col-md-3">
                                <label for="hasil" class="form-label">Mendukung Program Tematik Pembangunan (pilih):</label>
                                <?= form_dropdown('data[0][id_progtematik]', $dataprogtematik, $inputs[0]['id_tematik'] ?? null, ['class' => 'form-select', 'required']);
                                ?>
                            </div>
                        <?php } else { ?>
                            <input type="hidden" name="data[0][id_progtematik]" value="0" ?>
                        <?php } ?>


                    </div>
                </div>
                <hr>
                <!--end::Body-->
                <!--begin::Footer-->
                <div class="d-flex justify-content-between mt-4">
                    <?php if (!(session()->getFlashdata('message'))): ?>
                        <button type="submit" class="btn btn-primary" value="Kirim">
                            <i class="bi bi-save"></i> Simpan Semua
                        </button>
                    <?php endif; ?>
                    <?php if ((session()->getFlashdata('message'))): ?>
                    <?php endif; ?>
                </div>
            </div>
            <!--end::Footer-->
            <?= form_close(); ?>
            <!--end::Form-->
        </div>
        <!--end::Quick Example-->
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        let counter = 1;
        const inputContainer = document.getElementById('input-container');

        // Tambah input baru
        document.getElementById('tambah-input').addEventListener('click', function() {
            const newInputGroup = document.createElement('div');
            newInputGroup.className = 'input-group row g-3';

            newInputGroup.innerHTML = `
            <hr>
                    <div class="col-md-2">
                        <label for="target" class="form-label">Target/Sasaran</label>
                        <input type="number" name="data[${counter}][vol_target]" class="form-control"
                         oninput="this.value = this.value.replace(/[^0-9\s]/g, '')"
                        placeholder="jumlah sasaran/target" required>
                        <input type="text" name="data[${counter}][sat_target]" class="form-control" 
                                oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')"
                        placeholder="satuan sasaran/target" required>
                            <input type="hidden" name="data[${counter}][id_targetsubkeg]" value="<?= esc($id_targetsubkeg) ?>" ?>
                            <input type="hidden" name="data[${counter}][tahun]" value="<?= esc($tahundt) ?>" ?>
                            <input type="hidden" name="data[${counter}][kd_subunit]" value="<?= esc($kd_subunit) ?>" ?>
                            <input type="hidden" name="data[${counter}][kd_subkegiatan]" value="<?= esc($kd_subkegiatan) ?>" ?>
                            <input type="hidden" name="data[${counter}][pagu]" value="<?= esc($pagu) ?>" ?>

                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                        <div class="col-md-5">
                            <label for="uraian" class="form-label">Uraian Target Pelaksanaan Aktivitas/Kegiatan Subkegiatan Tahun ="<?= esc($tahun) ?></label>
                            <textarea id="uraian" name="data[${counter}][uraian_target]" class="form-control" required
                                placeholder="uraian/penjelasan mengenai sasaran" rows="5"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="hasil" class="form-label">Hasil yang akan dicapai Tahun ="<?= esc($tahun) ?></label>
                            <textarea name="data[${counter}][hasil]" class="form-control" id="hasil" required
                                placeholder="diawali dengan Ter...." rows="5"></textarea>
                        </div>
                    <div class="col-md-1">

                        <button type="button" class="btn btn-danger remove-input" disabled>
                            <i class="bi bi-trash"></i>
                        </button>
                        </div><br>
                        <div class="col-md-4">
                            <label for="hasil" class="form-label">Kegiatan/Aktifitas Pokok Ini Mendukung Sasaran RPJMD/Program Prioritas (pilih):</label>
                            <?= form_dropdown('data[${counter}][id_progprioritas]', $dataprogprioritas, $inputs['${counter}']['id_pprio'] ?? null, ['class' => 'form-select']); ?>
                        </div>
                        <?php if ($cekopdunggulan) { ?>
                            <div class="col-md-3">
                                <label for="hasil" class="form-label">Mendukung Program Unggulan Pembangunan (pilih):</label>
                                <?= form_dropdown('data[${counter}][id_progunggulan]', $dataprogunggulan, $inputs['${counter}']['id_pung'] ?? null, ['class' => 'form-select', 'required']); ?>
                            </div>
                        <?php } else { ?>
                            <input type="hidden" name="data[${counter}][id_progunggulan]" value="0" ?>
                        <?php } ?>
                        <?php if ($cekopdtematik) { ?>
                            <div class="col-md-3">
                                <label for="hasil" class="form-label">Mendukung Program Tematik Pembangunan (pilih):</label>
                                <?= form_dropdown('data[${counter}][id_progtematik]', $dataprogtematik, $inputs['${counter}']['id_tematik'] ?? null, ['class' => 'form-select', 'required']);
                                ?>
                            </div>
                        <?php } else { ?>
                            <input type="hidden" name="data[${counter}][id_progtematik]" value="0" ?>
                        <?php } ?>
                    </div>
                </div>
                <hr>`;
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
                        inputs[0].name = `data[${index}][vol_target]`;
                        inputs[1].name = `data[${index}][sat_target]`;
                        inputs[2].name = `data[${index}][id_targetsubkeg]`;
                        inputs[3].name = `data[${index}][tahun]`;
                        inputs[4].name = `data[${index}][kd_subunit]`;
                        inputs[5].name = `data[${index}][kd_subkegiatan]`;
                        inputs[6].name = `data[${index}][pagu]`;
                        inputs[7].name = `data[${index}][uraian_target]`;
                        inputs[8].name = `data[${index}][hasil_target]`;
                        inputs[9].name = `data[${index}][id_progprioritas]`;
                        inputs[10].name = `data[${index}][id_progunggulan]`;
                        inputs[11].name = `data[${index}][id_progtematik]`;


                        // Nonaktifkan tombol hapus jika hanya tersisa satu input
                        const removeBtn = group.querySelector('.remove-input');
                        removeBtn.disabled = inputContainer.children.length === 1;
                    });
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>
<!-- /.card -->