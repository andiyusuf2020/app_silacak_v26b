<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Anggaran Kas Perangkat Daerah</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Anggaran Kas</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <!-- Flash Message -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Anggaran Kas & Rincian Per Bulan</h3>
                        <div class="card-tools">
                            <a href="<?= hash_url('' . $wilayah . '/' . $groupuser . '', ['hal' => 'tambahdataangkasopd']) ?>" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-lg"></i> Buat Angkas Baru
                            </a>
                            <a href="<?= base_url('' . $wilayah . '/' . $groupuser . '/anggaran-kas/export-pdf-admin') ?>" target="_blank" class="btn btn-danger btn-sm">
                                <i class="bi bi-file-earmark-pdf"></i> Cetak PDF
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle text-nowrap" id="tableAnggaran">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Sub Unit / Kegiatan</th>
                                        <th>Tahun</th>
                                        <th>Pagu Anggaran</th>
                                        <th>Januari</th>
                                        <th>Februari</th>
                                        <th>Maret</th>
                                        <th>April</th>
                                        <th>Mei</th>
                                        <th>Juni</th>
                                        <th>Juli</th>
                                        <th>Agustus</th>
                                        <th>September</th>
                                        <th>Oktober</th>
                                        <th>November</th>
                                        <th>Desember</th>
                                        <th>Total Kas</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($anggaran)): ?>
                                        <?php $no = 1;
                                        foreach ($anggaran as $row): ?>
                                            <tr>
                                                <td class="text-center"><?= $no++ ?></td>
                                                <td>
                                                    <strong><?= esc($row['sub_unit']) ?></strong><br>
                                                    <small class="text-muted">Kegiatan: <?= esc($row['sub_kegiatan']) ?></small>
                                                </td>
                                                <td class="text-center"><?= esc($row['tahun_anggaran']) ?></td>
                                                <td class="text-end fw-bold text-primary">Rp <?= number_format($row['pagu_anggaran'], 2, ',', '.') ?></td>
                                                <td class="text-end">Rp <?= number_format($row['januari'], 2, ',', '.') ?></td>
                                                <td class="text-end">Rp <?= number_format($row['februari'], 2, ',', '.') ?></td>
                                                <td class="text-end">Rp <?= number_format($row['maret'], 2, ',', '.') ?></td>
                                                <td class="text-end">Rp <?= number_format($row['april'], 2, ',', '.') ?></td>
                                                <td class="text-end">Rp <?= number_format($row['mei'], 2, ',', '.') ?></td>
                                                <td class="text-end">Rp <?= number_format($row['juni'], 2, ',', '.') ?></td>
                                                <td class="text-end">Rp <?= number_format($row['juli'], 2, ',', '.') ?></td>
                                                <td class="text-end">Rp <?= number_format($row['agustus'], 2, ',', '.') ?></td>
                                                <td class="text-end">Rp <?= number_format($row['september'], 2, ',', '.') ?></td>
                                                <td class="text-end">Rp <?= number_format($row['oktober'], 2, ',', '.') ?></td>
                                                <td class="text-end">Rp <?= number_format($row['november'], 2, ',', '.') ?></td>
                                                <td class="text-end">Rp <?= number_format($row['desember'], 2, ',', '.') ?></td>
                                                <td class="text-end fw-bold text-success">Rp <?= number_format($row['total_anggaran_kas'], 2, ',', '.') ?></td>
                                                <td class="text-center">
                                                    <a href="<?= base_url('' . $wilayah . '/' . $groupuser . '/dataperopd?token=' . $row['hash']) ?>" class="btn btn-warning btn-sm" title="Edit">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <a href="<?= base_url('' . $wilayah . '/' . $groupuser . '/dataperopd?token=' . $row['hashidhapusdata']) ?>" class="btn btn-warning btn-sm" title="Edit">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="18" class="text-center">Belum ada data anggaran kas.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>