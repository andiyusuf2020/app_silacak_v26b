<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- SELECT2 EXAMPLE -->
    <div class="card card-default">
        <div class="card card-primary card-outline mb-4">
            <!--begin::Header-->
            <div class="card-header">
                <div class="card-title">Form Input Realisasi Aktifitas pada Subkegiatan bulan <?= esc($bulanaktif) ?></div>
            </div>
            <div class="card-header">
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
            <?= form_open_multipart('capkin2026/simpanpermasalahanaktifitas', 'id="myForm"'); ?>
            <?= csrf_field(); ?>
            <!--begin::Body-->
            <div class="card-body">
                <div id="input-container">
                    <div class="mb-3">
                        <label for="subkegiatan" class="form-label">Uraian Target Pelaksanaan Aktivitas/Kegiatan Subkegiatan Tahun <?= esc($tahunaktif) ?> </label>
                        <h2><?= esc($dataPkp['uraian_target'] . ' dengan target pelaksanaan  :' . $dataPkp['vol_target'] . '  ' . $dataPkp['sat_target']) ?></h2>
                    </div>

                    <div class="col-md">
                        <label for="hasil" class="form-label">Mendukung Sasaran RPJMD/Program Prioritas:</label>
                        <h3><?= esc($dataPkp['nm_progprioritas']) ?></h3>
                    </div>
                    <br>
                    <div class="mb-3">
                        <h2> <label for="kegpokok" class="form-label">Realisasi s.d Bulan <?= esc($bulanaktif) ?></label></h2>
                    </div>
                    <div class="input-group row g-1">
                        <!--begin::Col-->
                        <div class="col-md-12">
                            <label for="uraian" class="form-label">Uraian Permasalahan dan Solusi Pelaksanaan Aktivitas/Kegiatan
                            </label>
                            <!-- Place the first <script> tag in your HTML's <head> -->
                            <script src="https://cdn.tiny.cloud/1/gcefz8hm73j281pvzmdyhd2nhazcq5y80bwpbobx1q7u2tl7/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>

                            <!-- Place the following <script> and <textarea> tags your HTML's <body> -->
                            <script>
                                tinymce.init({
                                    selector: 'textarea',
                                    plugins: [
                                        // Core editing features
                                        'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
                                        // Premium features
                                        'checklist', 'mediaembed', 'casechange', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'advtemplate', 'tinymceai', 'uploadcare', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown', 'importword', 'exportword', 'exportpdf'
                                    ],
                                    toolbar: 'undo redo | tinymceai-chat tinymceai-quickactions tinymceai-review | blocks fontfamily fontsize | bold italic underline strikethrough | link media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography uploadcare | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                                    tinycomments_mode: 'embedded',
                                    tinycomments_author: 'Author name',
                                    mergetags_list: [{
                                            value: 'First.Name',
                                            title: 'First Name'
                                        },
                                        {
                                            value: 'Email',
                                            title: 'Email'
                                        },
                                    ],
                                    tinymceai_token_provider: async () => {
                                        await fetch(`https://demo.api.tiny.cloud/1/gcefz8hm73j281pvzmdyhd2nhazcq5y80bwpbobx1q7u2tl7/auth/random`, {
                                            method: "POST",
                                            credentials: "include"
                                        });
                                        return {
                                            token: await fetch(`https://demo.api.tiny.cloud/1/gcefz8hm73j281pvzmdyhd2nhazcq5y80bwpbobx1q7u2tl7/jwt/tinymceai`, {
                                                credentials: "include"
                                            }).then(r => r.text())
                                        };
                                    },
                                    uploadcare_public_key: '4aa895a185362f4d5d84',
                                });
                            </script>
                            <textarea value="<?= esc($dataPkp['permasalahan']) ?>" name="permasalahan" id="content" rows="20" cols="360">
                                <?= esc($dataPkp['permasalahan']) ?>
                            </textarea>

                            <?php
                            // $permasalahan = [
                            //     'type' => 'text',
                            //     'name' => 'permasalahan',
                            //     'value' => esc($dataPkp['permasalahan']),
                            //     'class' => 'form-control',
                            //     'placeholder' => 'isi penjelasan mengenai permasalahan dan solusi pelaksanaan aktifitas',
                            //     'oninput'  => "this.value = this.value.replace(/[^a-zA-Z0-9,.%()?/\s]/g, '')",
                            //     'required' => 'true'
                            // ];
                            // echo form_textarea($permasalahan);
                            ?>
                            <input type="hidden" name="idKP" value="<?= esc($dataPkp['id_kegpokok'])  ?>" ?>
                            <input type="hidden" name="id_Per" value="<?= esc($dataPkp['id_Pr'])  ?>" ?>
                            <input type="hidden" name="bulan" value="<?= esc($bulanaktif)  ?>" ?>

                        </div>
                    </div>
                </div>
                <!--end::Body-->
                <!--begin::Footer-->
                <div class="d-flex justify-content-between mt-4">
                    <?php if (!(session()->getFlashdata('message'))): ?>
                        <button type="submit" class="btn btn-primary">
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