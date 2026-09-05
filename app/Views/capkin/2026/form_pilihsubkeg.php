<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- SELECT2 EXAMPLE -->
    <div class="card card-default">
        <div class="card card-primary card-outline mb-4">
            <!--begin::Header-->
            <div class="card-header">
                <div class="card-title">
                    Form Mapping SubKegiatan Aktifitas Utama Kegiatan
                </div>
            </div>
            <div class="card-header">
                <?php if (session()->getFlashdata('message')): ?>
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            <p><?= session()->getFlashdata('message') ?></p>
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
            <?= form_open_multipart('capkin2026/simpansubkeg'); ?>
            <?= csrf_field(); ?>
            <!--begin::Body-->
            <div class="card-body">
                <div id="input-container">
                    <div class="mb-3">
                        <label for="subkegiatan" class="form-label">SUBKEGIATAN</label>
                        <?php
                        //  form_dropdown('kd_subkegiatan', $sknoadum, $inputs['kd_subkegiatan'] ?? null, ['class' => 'form-select']);
                        //form_dropdown('sub_keg', $sknoadum); 
                        ?>
                        <select name="KODE_SUB_GIAT" class="form-select" required>
                            <option value="">Pilih Kegiatan</option>
                            <?php foreach ($subgiat as $subkeg): ?>
                                <option required value="<?= $subkeg['KODE_SUB_GIAT'] ?>" <?= isset($kode_sub_giat) && $kode_sub_giat == $subkeg['KODE_SUB_GIAT'] ? 'selected' : '' ?>>
                                    <?= $subkeg['NAMA_SUB_GIAT']  ?>
                                </option>
                            <?php endforeach ?>
                        </select>

                        <?= form_hidden('KODE_UNIT_SKPD', $subkeg['KODE_UNIT_SKPD']); ?>
                        <?= form_hidden('NAMA_UNIT_SKPD', $subkeg['NAMA_UNIT_SKPD']); ?>
                        <?php if ($idUbah) {
                            echo form_hidden('idUbah', $idUbah);
                        } ?>
                    </div>
                    <br>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mt-4">
                            <div>
                                <?php if (!$idUbah) { ?>
                                    <?php if (!(session()->getFlashdata('message'))): ?>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-plus-circle"></i> SIMPAN SUBKEGIATAN
                                        </button>
                                        <button onclick="history.back()" class="btn btn-secondary">Kembali</button>

                                    <?php endif; ?>
                                    <?php if ((session()->getFlashdata('message'))): ?>
                                        <button onclick="history.back()" class="btn btn-secondary">Kembali</button>
                                    <?php endif; ?>
                                <?php } ?>
                                <?php if ($idUbah) { ?>
                                    <?php if (!(session()->getFlashdata('message'))): ?>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-edit-circle"></i> SIMPAN PERUBAHAN
                                        </button>
                                        <button onclick="history.back()" class="btn btn-secondary">Kembali</button>

                                    <?php endif; ?>
                                    <?php if ((session()->getFlashdata('message'))): ?>
                                        <button onclick="history.back()" class="btn btn-secondary">Kembali</button>
                                    <?php endif; ?>
                                <?php } ?>

                            </div>
                        </div>
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