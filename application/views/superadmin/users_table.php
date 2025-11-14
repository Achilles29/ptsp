<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Username</th>
            <th>NIK</th>
            <th>Alamat</th>
            <th>No HP</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $i => $u): ?>
            <tr>
                <td><?= $start + $i + 1 ?></td>
                <td><?= $u->nama_lengkap ?></td>
                <td><?= $u->username ?></td>
                <td><?= $u->nik ?></td>
                <td><?= $u->alamat ?></td>
                <td><?= htmlspecialchars($u->no_hp ?? '') ?></td>
                <td><?= $u->email ?></td>
                <td><?= $u->role_id ?></td>
                <td><?= $u->is_active ? 'Aktif' : 'Nonaktif' ?></td>
                <td>
                    <button class="btn btn-sm btn-warning btn-edit"
                        data-id="<?= $u->id ?>"
                        data-nama="<?= $u->nama_lengkap ?>"
                        data-username="<?= $u->username ?>"
                        data-nik="<?= htmlspecialchars($u->nik ?? '') ?>"
                        data-alamat="<?= htmlspecialchars($u->alamat ?? '') ?>"
                        data-no_hp="<?= $u->no_hp ?>"
                        data-email="<?= htmlspecialchars($u->email ?? '') ?>"
                        data-role="<?= $u->role_id ?>"
                        data-layanan="<?= $u->layanan_id ?>"
                        data-active="<?= $u->is_active ?>"
                        data-bs-toggle="modal" data-bs-target="#editUserModal">Edit</button>
                    <a href="<?= base_url('superadmin/delete_user/' . $u->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus user ini?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>