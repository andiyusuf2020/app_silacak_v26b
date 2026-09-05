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
                <?php if (session()->getFlashdata('message')): ?>
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            <p><?= session()->getFlashdata('message') ?></p><br>
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
                        <h2><?= esc($datakegpokok['nm_subkegiatan'])  ?></h2>
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

                    </div>
                    <div class="input-group row g-3">
                        <div class="col-md-2">
                            <label for="target" class="form-label">Target/Sasaran</label>
                            <input type="number" value="<?= esc($datakegpokok['vol_target'])  ?>" name="data[0][vol_target]" class="form-control" placeholder="jumlah sasaran/target" required>
                            <input type="text" value="<?= esc($datakegpokok['sat_target'])  ?>" name="data[0][sat_target]" class="form-control"
                                oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')"
                                placeholder="satuan sasaran/target" required>
                            <!-- <input type="text" class="form-control" id="kegpokok" /> -->
                            <input type="hidden" name="data[0][id_kp]" value="<?= esc($datakegpokok['id_kp'])  ?>" ?>
                            <input type="hidden" name="data[0][id_targetsubkeg]" value="<?= esc($datakegpokok['id_targetsubkeg'])  ?>" ?>
                            <input type="hidden" name="data[0][tahun]" value="<?= esc($datakegpokok['tahun']) ?>" ?>
                            <input type="hidden" name="data[0][kd_subunit]" value="<?= esc($datakegpokok['kd_subunit']) ?>" ?>
                            <input type="hidden" name="data[0][kd_subkegiatan]" value="<?= esc($datakegpokok['kd_subkegiatan']) ?>" ?>
                            <input type="hidden" name="data[0][pagu]" value="<?= esc($datakegpokok['pagu']) ?>" ?>

                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-md-5">
                            <label for="uraian" class="form-label">Uraian</label>
                            <?php
                            $uraian_target = [
                                'type' => 'text',
                                'name' => 'data[0][uraian_target]',
                                'value' => esc($datakegpokok['uraian_target']),
                                'class' => 'form-control',
                                'placeholder' => 'isi penjelasan mengenai target atau sasaran',
                                'oninput'  => "this.value = this.value.replace(/[^a-zA-Z0-9,.%()?/\s]/g, '')",
                                'required' => 'true'
                            ];
                            echo form_textarea($uraian_target);
                            ?>
                        </div>
                        <div class="col-md-4">
                            <label for="hasil" class="form-label">Hasil yang akan dicapai</label>
                            <?php
                            $hasil = [
                                'type' => 'text',
                                'name' => 'data[0][hasil]',
                                'value' => esc($datakegpokok['hasil']),
                                'class' => 'form-control',
                                'placeholder' => 'isi penjelasan mengenai hasil diawali dengan Ter....',
                                'oninput'  => "this.value = this.value.replace(/[^a-zA-Z0-9,.%()?/\s]/g, '')",
                                'required' => 'true'
                            ];
                            echo form_textarea($hasil);
                            ?>
                        </div>
                    </div>
                </div>
                <br>
                <div class="col-md-4">
                    <label for="hasil" class="form-label">Mendukung Sasaran Pembangunan (pilih):</label>
                    <?= form_dropdown('data[0][id_progprioritas]', $dataprogprioritas, $datakegpokok['nm_progprioritas'], $inputs[0]['id_pprio'] ?? null, ['class' => 'form-select', 'required']); ?>
                </div>
                <?php if ($datakegpokok['id_progunggulan'] == '0') { ?>
                    <!-- <input type="hidden" name="data[0][id_progunggulan]" value="0" ?> -->
                    <?php if ($cekopdunggulan) { ?>
                        <div class="col-md-3">
                            <label for="hasil" class="form-label">Mendukung Program Unggulan Pembangunan (pilih):</label>
                            <?= form_dropdown('data[0][id_progunggulan]', $dataprogunggulanX, $inputs[0]['id_pung'] ?? null, ['class' => 'form-select', 'required']); ?>
                        </div>
                    <?php } else { ?>
                        <input type="hidden" name="data[0][id_progunggulan]" value="0" ?>
                    <?php } ?>
                <?php } else { ?>
                    <div class="col-md-3">
                        <label for="hasil" class="form-label">Mendukung Program Unggulan Pembangunan (pilih):</label>
                        <?= form_dropdown('data[0][id_progunggulan]', $dataprogunggulan, $nm_progunggulan, $inputs[0]['id_pung'] ?? null, ['class' => 'form-select', 'required']); ?>
                    </div>
                <?php } ?>
                <?php if ($datakegpokok['id_progtematik'] == '0') { ?>
                    <!-- <input type="hidden" name="data[0][id_progtematik]" value="0" ?> -->
                    <?php if ($cekopdtematik) { ?>
                        <div class="col-md-3">
                            <label for="hasil" class="form-label">Mendukung Program Tematik Pembangunan (pilih):</label>
                            <?= form_dropdown('data[0][id_progtematik]', $dataprogtematikX, $inputs[0]['id_tematik'] ?? null, ['class' => 'form-select', 'required']);
                            ?>
                        </div>
                    <?php } else { ?>
                        <input type="hidden" name="data[0][id_progtematik]" value="0" ?>
                    <?php } ?>
                <?php } else { ?>
                    <div class="col-md-3">
                        <label for="hasil" class="form-label">Mendukung Program Tematik Pembangunan (pilih):</label>
                        <?= form_dropdown('data[0][id_progtematik]', $dataprogtematik, $nm_progtematik, $inputs[0]['id_tematik'] ?? null, ['class' => 'form-select', 'required']);
                        ?>
                    </div>
                <?php } ?>
                <!--end::Body-->
                <!--begin::Footer-->
                <div class="d-flex justify-content-between mt-4">
                    <?php if (!(session()->getFlashdata('message'))): ?>
                        <button type="submit" class="btn btn-primary" value="Kirim">
                            <i class="bi bi-save"></i> Simpan
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
<?= $this->endSection() ?>
<!-- /.card -->