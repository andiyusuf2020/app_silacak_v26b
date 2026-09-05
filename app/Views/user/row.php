<tr>
    <td><?= $row['id']; ?></td>
    <td><?= $row['username']; ?></td>
    <td><?= empty($group) ? '' : $group[0]['name']; ?></td>
    <td><?= $row['email']; ?></td>
    <td><?= $row['sub_unit']; ?></td>
    <td align="center">
        <a href="<?= hash_url('lrfkadmin/manajemenuser/', ['idUser' => $row['id'], 'action' => 'akftivasi', 'Status' => $row['active'] == 1 ? 1 : 0]);
                    ?>" class="btn btn-sm btn-circle btn-active-users" title="Klik untuk Mengaktifkan atau Menonaktifkan">
            <?= $row['active'] == 1 ? 'Aktif' : 'Non Aktif'; ?>
        </a>
    </td>
    <td align="center">
        <a href="<?= hash_url('lrfkadmin/manajemenuser/', ['idUser' => $row['id'], 'action' => 'pass']);
                    ?>"
            <i class="nav-icon bi bi-star-half">Ubah Password</i>
        </a><br>
        <a href="<?= hash_url('lrfkadmin/manajemenuser/', ['idUser' => $row['id'], 'action' => 'ubahgroup']);
                    ?>" class="btn btn-success btn-circle btn-sm btn-change-group">
            <i class="mdi mdi-google-circles-group">Ubah Rule User</i>
        </a><br>
        <a href="<?= hash_url('lrfkadmin/manajemenuser/', ['idUser' => $row['id'], 'action' => 'ubahperangkatdaerah']);
                    ?>" class="btn btn-success btn-circle btn-sm btn-change-group">
            <i class="mdi mdi-google-circles-group">Ubah Perangkat Daerah (Prov)</i>
        </a>
    </td>
</tr>