<div class="container mt-4">
    <h4><?= $title ?></h4>
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="ri-user-add-line"></i> Tambah User
            </button>
            <input type="text" id="searchUser" class="form-control form-control-sm" placeholder="Cari user..." style="width:220px;">
        </div>

        <form method="get" class="d-flex align-items-center">
            <label class="me-2 mb-0">Tampilkan:</label>
            <select name="limit" onchange="this.form.submit()" class="form-select form-select-sm w-auto">
                <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100</option>
                <option value="<?= $total_rows ?>" <?= $limit == $total_rows ? 'selected' : '' ?>>Semua</option>
            </select>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle" id="userTable">
            <thead class="table-light">
                <tr class="text-center">
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
            <tbody id="userTableBody">
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted py-3">Tidak ada data pengguna.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $i => $u): ?>
                        <tr>
                            <td><?= $start + $i + 1 ?></td>
                            <td><?= htmlspecialchars($u->nama_lengkap ?? '') ?></td>
                            <td><?= htmlspecialchars($u->username ?? '') ?></td>
                            <td><?= htmlspecialchars($u->nik ?? '') ?></td>
                            <td><?= htmlspecialchars($u->alamat ?? '') ?></td>
                            <td><?= htmlspecialchars($u->no_hp ?? '') ?></td> <!-- Tambahan kolom -->
                            <td><?= htmlspecialchars($u->email ?? '') ?></td>
                            <td class="text-center"><?= $u->nama_role ?? '-' ?></td>

                            <td>
                                <span class="badge bg-<?= $u->is_active ? 'success' : 'secondary' ?>">
                                    <?= $u->is_active ? 'Aktif' : 'Nonaktif' ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning btn-edit"
                                    data-id="<?= $u->id ?>"
                                    data-nama="<?= htmlspecialchars($u->nama_lengkap ?? '') ?>"
                                    data-username="<?= htmlspecialchars($u->username ?? '') ?>"
                                    data-nik="<?= htmlspecialchars($u->nik ?? '') ?>"
                                    data-alamat="<?= htmlspecialchars($u->alamat ?? '') ?>"
                                    data-email="<?= htmlspecialchars($u->email ?? '') ?>"
                                    data-no_hp="<?= htmlspecialchars($u->no_hp ?? '') ?>"
                                    data-role="<?= $u->role_id ?>"
                                    data-layanan="<?= $u->instansi_id ?>"
                                    data-active="<?= $u->is_active ?>"
                                    data-bs-toggle="modal" data-bs-target="#editUserModal">
                                    <i class="ri-edit-line"></i> Edit
                                </button>
                                <a href="<?= base_url('superadmin/delete_user/' . $u->id) ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Hapus user ini?')">
                                    <i class="ri-delete-bin-line"></i> Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
        <small class="text-muted">Menampilkan <?= count($users) ?> dari <?= $total_rows ?> data</small>
        <div id="paginationContainer">
            <?= $pagination ?>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="post" action="<?= base_url('superadmin/add_user') ?>">
            <div class="modal-header">
                <h5>Tambah User</h5>
            </div>
            <div class="modal-body">
                <?php include 'user_form_fields.php'; ?>
            </div>
            <div class="modal-footer"><button class="btn btn-primary">Simpan</button></div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="post" action="" id="editUserForm">
            <div class="modal-header">
                <h5>Edit User</h5>
            </div>
            <div class="modal-body">
                <?php include 'user_form_fields.php'; ?>
            </div>
            <div class="modal-footer"><button class="btn btn-primary">Update</button></div>
        </form>
    </div>
</div>

<script>
    function toggleInstansiDropdown() {
        const role = document.getElementById('role_id')?.value;
        const instansiDiv = document.getElementById('instansiDropdown');
        if (role === '2') {
            instansiDiv.style.display = 'block';
        } else {
            instansiDiv.style.display = 'none';
            document.getElementById('instansi_id').value = '';
        }
    }

    // Auto panggil fungsi ini saat edit modal terbuka
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const f = document.forms['editUserForm'];
            f.action = '<?= base_url('superadmin/edit_user/') ?>' + this.dataset.id;
            f.nama_lengkap.value = this.dataset.nama;
            f.username.value = this.dataset.username;
            f.nik.value = this.dataset.nik;
            f.alamat.value = this.dataset.alamat;
            f.email.value = this.dataset.email;
            f.no_hp.value = this.dataset.no_hp;
            f.role_id.value = this.dataset.role;
            f.is_active.value = this.dataset.active;

            // set instansi_id jika admin layanan
            if (this.dataset.role == '2') {
                toggleInstansiDropdown();
                document.getElementById('instansi_id').value = this.dataset.instansi || '';
            } else {
                toggleInstansiDropdown();
            }
        });
    });
</script>



<script>
    document.getElementById('searchUser').addEventListener('keyup', function() {
        const keyword = this.value.trim();
        const paginationContainer = document.getElementById('paginationContainer');

        if (keyword.length < 2 && keyword !== '') return; // minimal 2 huruf

        fetch(`<?= base_url('superadmin/search_users_ajax?keyword=') ?>${encodeURIComponent(keyword)}`)
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('userTableBody');
                tbody.innerHTML = '';

                // Sembunyikan pagination saat pencarian aktif
                paginationContainer.style.display = 'none';

                if (data.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="9" class="text-center text-muted py-3">Tidak ada data ditemukan.</td></tr>`;
                    return;
                }

                data.forEach((u, i) => {
                    tbody.innerHTML += `
                <tr>
                    <td>${i + 1}</td>
                    <td>${u.nama_lengkap ?? ''}</td>
                    <td>${u.username ?? ''}</td>
                    <td>${u.nik ?? ''}</td>
                    <td>${u.alamat ?? ''}</td>
                    <td>${u.no_hp ?? ''}</td>
                    <td>${u.email ?? ''}</td>
                    <td>${u.role_id}</td>
                    <td><span class="badge bg-${u.is_active == 1 ? 'success' : 'secondary'}">
                        ${u.is_active == 1 ? 'Aktif' : 'Nonaktif'}</span></td>
                    <td>
                        <button class="btn btn-sm btn-warning"><i class="ri-edit-line"></i> Edit</button>
                        <a href="<?= base_url('superadmin/delete_user/') ?>${u.id}" class="btn btn-sm btn-danger"><i class="ri-delete-bin-line"></i> Hapus</a>
                    </td>
                </tr>`;
                });
            });
        if (keyword === '') {
            location.reload(); // atau: paginationContainer.style.display = 'block';
            return;
        }

    });
</script>