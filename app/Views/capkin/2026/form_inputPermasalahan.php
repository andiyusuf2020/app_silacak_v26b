<?= $this->extend('template/layout') ?>
<!-- <script src="https://cdn.tiny.cloud/1/gcefz8hm73j281pvzmdyhd2nhazcq5y80bwpbobx1q7u2tl7/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script> -->
<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- SELECT2 EXAMPLE -->
    <div class="card card-default">
        <div class="card card-primary card-outline mb-4">
            <!--begin::Header-->
            <div class="card-header">
                <div class="card-title">Form Input Permasalah dan Solusi Realisasi Aktifitas pada Subkegiatan bulan <?= esc($bulanaktif) ?></div>
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
            <?php if (!session()->getFlashdata('message')): ?>

                <!--begin::Form-->
                <?= form_open_multipart('capkin2026/simpanpermasalahanaktifitas', 'id="myForm"'); ?>
                <?= csrf_field(); ?>
                <!--begin::Body-->
                <div class="card-body">
                    <div id="input-container">

                        <div class="col-md">
                            <label for="hasil" class="form-label">Program/Kegiatan/Sub Kegiatan:</label>
                            <h3><?= esc($datakp['nm_program']) ?> / <?= esc($datakp['nm_kegiatan']) ?> / <?= esc($datakp['nm_sub_giat']) ?></h3>
                        </div>
                        <div class="col-md">
                            <label for="hasil" class="form-label">Mendukung Sasaran RPJMD/Program Prioritas:</label>
                            <h3><?= esc($datakp['nm_progprioritas']) ?></h3>
                        </div>

                        <div class="mb-3">
                            <label for="subkegiatan" class="form-label">Uraian Target Pelaksanaan Aktivitas/Kegiatan Subkegiatan Tahun <?= esc($tahunaktif) ?> </label>
                            <h2><?= esc($datakp['uraian_target'] . ' dengan target pelaksanaan  :' . $datakp['vol_target'] . '  ' . $datakp['sat_target']) ?></h2>
                        </div>
                        <br>
                        <div class="mb-3">
                            <h2> <label for="kegpokok" class="form-label">Permasalahan dan Solusi Pelaksanaan Aktifitas</label></h2>
                        </div>
                        <div class="input-group row g-1">
                            <div class="col-md-12">
                                <label for="uraian" class="form-label">Uraian Permasalahan dan Solusi Pelaksanaan Aktivitas/Kegiatan
                                </label>
                                <!-- Place the first <script> tag in your HTML's <head> -->
                                <!-- <script src="https://cdn.tiny.cloud/1/gcefz8hm73j281pvzmdyhd2nhazcq5y80bwpbobx1q7u2tl7/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script> -->
                                <!-- <script src="https://cdn.tiny.cloud/1/79watyks2vzwryv2v1y0iicwthr3hsywtpshkqe18g6ubzzb/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script> -->
                                <!-- Place the following <script> and <textarea> tags your HTML's <body> -->
                                <!-- <script>
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
                                </script> -->
                                <!-- Place the first <script> tag in your HTML's <head> -->
                                <script src="https://cdn.tiny.cloud/1/79watyks2vzwryv2v1y0iicwthr3hsywtpshkqe18g6ubzzb/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>

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
                                            await fetch(`https://demo.api.tiny.cloud/1/79watyks2vzwryv2v1y0iicwthr3hsywtpshkqe18g6ubzzb/auth/random`, {
                                                method: "POST",
                                                credentials: "include"
                                            });
                                            return {
                                                token: await fetch(`https://demo.api.tiny.cloud/1/79watyks2vzwryv2v1y0iicwthr3hsywtpshkqe18g6ubzzb/jwt/tinymceai`, {
                                                    credentials: "include"
                                                }).then(r => r.text())
                                            };
                                        },
                                        uploadcare_public_key: '8a62f7011fef46c05971',
                                    });
                                </script>
                                <textarea name="permasalahan" id="content" rows="20" cols="360"></textarea>
                                <?php
                                // $permasalahan = [
                                //     'type' => 'text',
                                //     'name' => 'permasalahan',
                                //     'value' => '',
                                //     'class' => 'form-control',
                                //     'placeholder' => 'isi penjelasan mengenai permasalahan dan solusi pelaksanaan aktifitas',
                                //     'oninput'  => "this.value = this.value.replace(/[^a-zA-Z0-9,.%()?/\s]/g, '')",
                                //     'required' => 'true'
                                // ];
                                // echo form_textarea($permasalahan);
                                ?>
                                <input type="hidden" name="idKP" value="<?= esc($datakp['id_kp'])  ?>" ?>
                                <input type="hidden" name="bulan" value="<?php echo esc($bulanaktif)
                                                                            ?>" ?>
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
            <?php endif; ?>
        </div>
        <!--end::Quick Example-->
    </div>
</div>
<?= $this->endSection() ?>
<!-- <script>
    tinymce.init({
        selector: '#content',
        height: 500,
        menubar: true,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | ' +
            'bold italic backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | help | fullscreen',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
        setup: function(editor) {
            editor.on('change', function() {
                tinymce.triggerSave();
            });
        }
    });
</script> -->
<!-- /.card -->