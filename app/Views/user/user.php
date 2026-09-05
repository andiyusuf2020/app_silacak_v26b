<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<?php

use App\Models\UserModel\TaUserModel;

$this->tausermodel = new TaUserModel();
?>
<div class="card shadow mb-12">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar User</h6>
    </div>
    <div class="card-body">
        <h1 class="mb-4">Data User SiTAPIS </h1>

        <!-- Form Pencarian -->
        <div class="form-group">
            <input type="text" class="form-control" id="searchInput" placeholder="Ketik untuk mencari Data User...">
            <div id="loading" class="loading">Mencari...</div>
        </div>
        <!-- Tabel Produk -->
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Grup</th>
                    <th>Email</th>
                    <th>SatKer</th>
                    <th>Aktif</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="resultsTable">
                <!-- Hasil pencarian akan ditampilkan di sini -->
                <tr>
                    <td colspan="7" class="text-center">Silakan ketik untuk mencari User</td>
                </tr>
            </tbody>
        </table>
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Grup</th>
                        <th>Email</th>
                        <th>SatKer</th>
                        <th>Aktif</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($listuser as $rw) {
                        $row = "row" . $rw['id'];
                        echo $$row;
                    }
                    ?>
                </tbody>

            </table>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        let searchTimer;
        const searchInput = $('#searchInput');
        const resultsTable = $('#resultsTable');
        const loading = $('#loading');

        // Fungsi untuk melakukan pencarian
        function doSearch() {
            const keyword = searchInput.val().trim();

            if (keyword.length === 0) {
                resultsTable.html('<tr><td colspan="7" class="text-center">Silakan ketik untuk mencari User</td></tr>');
                return;
            }

            loading.show();

            $.ajax({
                url: '<?= base_url('lrfkadmin/usersitapis') ?>',
                type: 'GET',
                data: {
                    keyword: keyword
                },
                dataType: 'json',
                success: function(response) {
                    loading.hide();

                    if (response.length === 0) {
                        resultsTable.html('<tr><td colspan="7" class="text-center">Tidak ditemukan User yang sesuai</td></tr>');
                        return;
                    }

                    let html = '';
                    response.forEach((item, index) => {
                        if (item.active == 1) {
                            var status = 'Aktif'
                        }
                        if (item.active == 0) {
                            var status = 'Non Aktif'
                        }
                        html += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${highlightText(item.username, keyword)}</td>
                                    <td>${highlightText(item.name, keyword)}</td>
                                    <td>${highlightText(item.email, keyword)}</td>
                                    <td>${highlightText(item.sub_unit, keyword)}</td>
                                    <td>
                                    <?php
                                    $user = $this->tausermodel->getDataUser($rw['id'])->getRowArray();
                                    // echo dd($user);
                                    ?>
                                        <a href="<?= hash_url(
                                                        'lrfkadmin/manajemenuser/',
                                                        [
                                                            'idUser' => $rw['id'],
                                                            'action' => 'akftivasi',
                                                            'Status' => $rw['active'] == 1 ? 1 : 0
                                                        ]
                                                    );
                                                    ?>" class="btn btn-sm btn-circle btn-active-users" title="Klik untuk Mengaktifkan atau Menonaktifkan">
                                        ${status}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?= hash_url('lrfkadmin/manajemenuser/', ['idUser' => $rw['id'], 'action' => 'pass']);
                                                    ?>"
                                            <i class="nav-icon bi bi-star-half">Ubah Password</i>
                                        </a><br>
                                        <a href="<?= hash_url('lrfkadmin/manajemenuser/', ['idUser' => $rw['id'], 'action' => 'ubahgroup']);
                                                    ?>" class="btn btn-success btn-circle btn-sm btn-change-group">
                                            <i class="mdi mdi-google-circles-group">Ubah Rule User</i>
                                        </a><br>
                                        <a href="<?= hash_url('lrfkadmin/manajemenuser/', ['idUser' => $rw['id'], 'action' => 'ubahperangkatdaerah']);
                                                    ?>" class="btn btn-success btn-circle btn-sm btn-change-group">
                                            <i class="mdi mdi-google-circles-group">Ubah Perangkat Daerah (Prov)</i>
                                        </a>
                                    </td>
                                    <td></td>
                                </tr>
                            `;
                    });

                    resultsTable.html(html);
                },
                error: function(xhr, status, error) {
                    loading.hide();
                    resultsTable.html('<tr><td colspan="7" class="text-center text-danger">Terjadi kesalahan saat mencari</td></tr>');
                    console.error(error);
                }
            });
        }

        // Fungsi untuk menyorot teks yang cocok
        function highlightText(text, keyword) {
            if (!keyword) return text;
            const regex = new RegExp(keyword, 'gi');
            return text.replace(regex, match => `<span class="highlight">${match}</span>`);
        }

        // Fungsi untuk format mata uang
        function formatCurrency(amount) {
            return 'Rp ' + parseFloat(amount).toLocaleString('id-ID');
        }

        // Event listener untuk input pencarian
        searchInput.on('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(doSearch, 300); // Delay 300ms setelah berhenti mengetik
        });

        // Trigger pencarian saat halaman dimuat jika ada isi di input
        if (searchInput.val().trim().length > 0) {
            doSearch();
        }
    });
</script>
<?= $this->endSection() ?>
<?= $this->section('div-modal') ?>

<?php $this->endSection() ?>

<?php $this->section('script-js') ?>

<?php $this->endSection() ?>