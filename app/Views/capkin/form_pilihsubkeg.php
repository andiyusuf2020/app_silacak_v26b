<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- SELECT2 EXAMPLE -->
    <div class="card card-default">
        <div class="card card-primary card-outline mb-4">
            <!--begin::Header-->
            <div class="card-header">
                <div class="card-title">
                    <?php if (!$idUbah) { ?>
                        Form Input Target Sasaran Kegiatan Pokok
                    <?php } ?>
                    <?php if ($idUbah) { ?>
                        Form Ubah SubKegiatan Kegiatan Pokok
                    <?php } ?>
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
            <?= form_open_multipart('capkin/simpan'); ?>
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
                        <input class="form-control" list="datalistsubkeg" name="kd_subkegiatan" id="kd_subkegiatan" value="<?= old('nm_subkegiatan') ?>" required
                            placeholder="Type to search...">
                        <datalist id="datalistsubkeg">
                            <?php foreach ($sknoadum as $key => $subkeg) { ?>
                                <option value="<?= esc($subkeg['kd_subkegiatan']) ?>"><?= esc($subkeg['nm_subkegiatan']) ?></option>
                            <?php }  ?>
                        </datalist>
                        <?= form_hidden('kd_subunit', $kd_subunit); ?>
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