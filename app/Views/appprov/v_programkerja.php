<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<!--begin::Container-->
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-9">
                <div class="card-header">
                    <h3 class="card-title">Misi Ke : <?= $datamisi['id']; ?> :: <?= $datamisi['judul_misi']; ?></h3>
                </div>
                <div class="card-footer">
                    <div class="col-12">
                        <div class="callout callout-info">
                            <a
                                href="<?= hash_url('adbang/program_kerja/', ['id' => $datamisi['id'], 'action' => 'tambah']);
                                        ?>"
                                rel="noopener noreferrer"
                                class="callout-link">
                                TAMBAH PROGRAM KERJA
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0" style="height: 500px;">
                    <table class="table table-head-fixed text-wrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>PROGRAM KERJA</th>
                                <th>DESKRIPSI PROGRAM</th>
                                <th>ARAH KEBIJAKAN</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($dataprogkerja as $key => $value) { ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td><?= $value['judul_programkerja'] ?></td>
                                    <td>
                                        <?php if ($value['gambar'] == '') {
                                        } else { ?>
                                            <img src="../../uploads/<?= esc($value['gambar']) ?>" width="300">
                                            <p></p>
                                        <?php } ?>
                                        <?= $value['indikator'] ?>
                                    </td>
                                    <td> <a
                                            href="<?= hash_url('adbang/arah_kebijakan/', ['misiid' => $datamisi['id'], 'idpk' => $value['id'], 'action' => 'list']);
                                                    ?>"
                                            rel="noopener noreferrer"
                                            class="callout-link">
                                            Data Arah Kebijakan dalam melaksanakan Program Kerja Ini
                                        </a></td>
                                    <td>
                                        <button type="button" class="btn btn-outline-primary mb-2">
                                            <a
                                                href="<?= hash_url('adbang/program_kerja/', ['misiid' => $datamisi['id'], 'id' => $value['id'], 'action' => 'edit']);
                                                        ?>">
                                                EDIT
                                            </a>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger mb-2"> <a
                                                href="<?= hash_url('adbang/program_kerja/', ['misiid' => $datamisi['id'], 'id' => $value['id'], 'action' => 'hapus']);
                                                        ?> ">
                                                HAPUS
                                            </a></button>
                                    </td>

                                </tr>
                            <?php }
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-end">
                        <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                    </ul>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>


<?= $this->endSection() ?>