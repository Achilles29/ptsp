<div class="container-fluid px-4 mt-4">
    <style>
        #userTable th { white-space: nowrap; }
        .cell-wrap { max-width: 220px; word-break: break-word; }
        .akses-wrap .badge { margin: 0 4px 4px 0; }
    </style>
    <div class="portal-page">
        <section class="portal-page-intro">
            <div>
                <div class="portal-page-eyebrow"><i class="ri ri-user-settings-line"></i> Manajemen Akun</div>
                <h1 class="portal-page-title"><?= html_escape($title) ?></h1>
                <p class="portal-page-subtitle">
                    Kelola akun superadmin, admin layanan, dan masyarakat. Opsi akun CS sudah disembunyikan dari UI agar tidak dipakai lagi dalam operasional harian.
                </p>
            </div>
            <div class="portal-inline-metrics">
                <div class="portal-inline-metric">
                    <small>Total data</small>
                    <strong><?= (int) $total_rows ?></strong>
                </div>
            </div>
        </section>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
        <?php endif; ?>

        <section class="portal-filter-card">
            <div class="portal-toolbar">
                <div class="portal-toolbar-actions">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="ri-user-add-line me-1"></i> Tambah User
                    </button>
                    <input type="text" id="searchUser" class="form-control" placeholder="Cari user..." style="width:min(100%, 260px);">
                </div>

                <form method="get" class="portal-toolbar-actions">
                    <label class="text-muted mb-0">Tampilkan</label>
                    <select name="limit" onchange="this.form.submit()" class="form-select w-auto">
                        <option value="25" <?= $limit == 25 ? 'selected' : '' ?>>25</option>
                        <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                        <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100</option>
                        <option value="<?= $total_rows ?>" <?= $limit == $total_rows ? 'selected' : '' ?>>Semua</option>
                    </select>
                </form>
            </div>
        </section>

        <section class="card portal-section-card portal-table-card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="portal-section-title">Daftar Pengguna</h5>
                    <div class="portal-card-note">Role CS tidak lagi ditampilkan pada daftar maupun form pembuatan akun.</div>
                </div>
                <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle"><?= count($users) ?> baris</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-scroll-x" id="userTable">
                        <thead>
                            <tr class="text-center">
                                <th class="col-nowrap">#</th>
                                <th class="col-name-min">Nama</th>
                                <th class="d-mobile-none col-name-min">Username</th>
                                <th class="col-nowrap">Role</th>
                                <th class="d-mobile-none col-name-min">Instansi</th>
                                <th class="col-nowrap">Status</th>
                                <th class="col-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
                            <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-3">Tidak ada data pengguna.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($users as $i => $u): ?>
                                    <?php
                                        $instansi_label = '<span class="text-muted">-</span>';
                                        if (!empty($u->kode_instansi)) {
                                            $instansi_label = '<span class="badge bg-dark-subtle text-dark border">' . htmlspecialchars($u->kode_instansi) . '</span>';
                                        }

                                        $akses_layanan = '<span class="text-muted">-</span>';
                                        if ((int) $u->role_id === 2 && !empty($u->instansi_id)) {
                                            if (!empty($u->kode_layanan)) {
                                                $badges = '';
                                                foreach (explode(',', $u->kode_layanan) as $kode) {
                                                    $kode = trim($kode);
                                                    if ($kode !== '') {
                                                        $badges .= '<span class="badge bg-info text-dark">' . htmlspecialchars($kode) . '</span>';
                                                    }
                                                }
                                                $akses_layanan = $badges ?: '<span class="text-muted">-</span>';
                                            } else {
                                                $akses_layanan = '<span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">Semua layanan</span>';
                                            }
                                        }
                                    ?>
                                    <tr>
                                        <td><?= $start + $i + 1 ?></td>
                                        <td class="cell-wrap fw-semibold"><?= htmlspecialchars($u->nama_lengkap ?? '') ?></td>
                                        <td class="d-mobile-none"><?= htmlspecialchars($u->username ?? '') ?></td>
                                        <td class="text-center"><span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle"><?= htmlspecialchars($u->nama_role ?? '-') ?></span></td>
                                        <td class="cell-wrap d-mobile-none"><?= $instansi_label ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-<?= $u->is_active ? 'success' : 'secondary' ?>">
                                                <?= $u->is_active ? 'Aktif' : 'Nonaktif' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="table-action-group">
                                            <button class="btn btn-sm btn-info text-white btn-detail"
                                                data-nama="<?= htmlspecialchars($u->nama_lengkap ?? '') ?>"
                                                data-username="<?= htmlspecialchars($u->username ?? '') ?>"
                                                data-nik="<?= htmlspecialchars($u->nik ?? '') ?>"
                                                data-alamat="<?= htmlspecialchars($u->alamat ?? '') ?>"
                                                data-email="<?= htmlspecialchars($u->email ?? '') ?>"
                                                data-no-hp="<?= htmlspecialchars($u->no_hp ?? '') ?>"
                                                data-role-name="<?= htmlspecialchars($u->nama_role ?? '-') ?>"
                                                data-instansi-name="<?= htmlspecialchars($u->nama_instansi ?? '-') ?>"
                                                data-kode-instansi="<?= htmlspecialchars($u->kode_instansi ?? '-') ?>"
                                                data-akses-layanan="<?= htmlspecialchars(($u->kode_layanan ?? '') !== '' ? $u->kode_layanan : ((int) $u->role_id === 2 ? 'Semua layanan' : '-')) ?>"
                                                data-status="<?= $u->is_active ? 'Aktif' : 'Nonaktif' ?>"
                                                data-bs-toggle="modal" data-bs-target="#detailUserModal">
                                                <i class="ri-eye-line"></i> <span class="btn-label">Detail</span>
                                            </button>
                                            <button class="btn btn-sm btn-warning btn-edit"
                                                data-id="<?= $u->id ?>"
                                                data-nama="<?= htmlspecialchars($u->nama_lengkap ?? '') ?>"
                                                data-username="<?= htmlspecialchars($u->username ?? '') ?>"
                                                data-nik="<?= htmlspecialchars($u->nik ?? '') ?>"
                                                data-alamat="<?= htmlspecialchars($u->alamat ?? '') ?>"
                                                data-email="<?= htmlspecialchars($u->email ?? '') ?>"
                                                data-no-hp="<?= htmlspecialchars($u->no_hp ?? '') ?>"
                                                data-role="<?= $u->role_id ?>"
                                                data-instansi="<?= $u->instansi_id ?>"
                                                data-kode-layanan="<?= htmlspecialchars($u->kode_layanan ?? '') ?>"
                                                data-active="<?= $u->is_active ?>"
                                                data-bs-toggle="modal" data-bs-target="#editUserModal">
                                                <i class="ri-edit-line"></i> <span class="btn-label">Edit</span>
                                            </button>
                                            <a href="<?= base_url('superadmin/delete_user/' . $u->id) ?>"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Hapus user ini?')">
                                                <i class="ri-delete-bin-line"></i> <span class="btn-label">Hapus</span>
                                            </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                    <small class="text-muted">Menampilkan <?= count($users) ?> dari <?= $total_rows ?> data</small>
                    <div id="paginationContainer">
                        <?= $pagination ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="post" action="<?= base_url('superadmin/add_user') ?>" id="addUserForm">
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

<div class="modal fade" id="detailUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><small class="text-muted d-block">Nama</small><strong id="d_nama">-</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">Username</small><strong id="d_username">-</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">Role</small><strong id="d_role">-</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">Status</small><strong id="d_status">-</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">Instansi</small><strong id="d_instansi">-</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">Kode Instansi</small><strong id="d_kode_instansi">-</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">Akses Layanan</small><strong id="d_akses_layanan">-</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">NIK</small><strong id="d_nik">-</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">No HP</small><strong id="d_no_hp">-</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">Email</small><strong id="d_email">-</strong></div>
                    <div class="col-12"><small class="text-muted d-block">Alamat</small><strong id="d_alamat">-</strong></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(() => {
    const addForm = document.getElementById('addUserForm');
    const editForm = document.getElementById('editUserForm');

    function splitKodeLayanan(value) {
        if (!value) return [];
        return String(value).split(',').map(v => v.trim()).filter(Boolean);
    }

    function escapeHtml(str) {
        return String(str ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function toDomId(str) {
        return String(str ?? '').replace(/[^a-zA-Z0-9_-]/g, '_');
    }

    function getFormNodes(form) {
        return {
            roleSelect: form.querySelector('.role-select'),
            instansiWrap: form.querySelector('.instansi-dropdown'),
            instansiSelect: form.querySelector('.instansi-select'),
            layananWrap: form.querySelector('.layanan-dropdown'),
            layananChecklist: form.querySelector('.kode-layanan-checklist')
        };
    }

    async function loadLayanan(form, selectedKode = []) {
        const n = getFormNodes(form);
        const instansiId = n.instansiSelect.value;

        n.layananChecklist.innerHTML = '';
        if (!instansiId) {
            n.layananWrap.classList.add('d-none');
            return;
        }

        n.layananWrap.classList.remove('d-none');
        const url = `<?= base_url('superadmin/get_layanan_by_instansi_ajax?instansi_id=') ?>${encodeURIComponent(instansiId)}`;

        try {
            const res = await fetch(url);
            const data = await res.json();
            n.layananChecklist.innerHTML = '';

            if (!Array.isArray(data) || data.length === 0) {
                n.layananChecklist.innerHTML = '<small class="text-muted">Tidak ada layanan.</small>';
                return;
            }

            data.forEach(item => {
                const kode = String(item.kode || '').trim();
                const nama = String(item.nama_layanan || '').trim();
                const checked = selectedKode.includes(kode) ? 'checked' : '';
                const inputId = `kode_${toDomId(kode)}_${toDomId(form.id)}`;

                n.layananChecklist.innerHTML += `
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="checkbox" name="kode_layanan[]" value="${escapeHtml(kode)}" id="${inputId}" ${checked}>
                        <label class="form-check-label" for="${inputId}">
                            ${escapeHtml(kode)} - ${escapeHtml(nama)}
                        </label>
                    </div>
                `;
            });
        } catch (e) {
            n.layananChecklist.innerHTML = '<small class="text-danger">Gagal memuat layanan.</small>';
        }
    }

    async function toggleScope(form, selectedKode = []) {
        const n = getFormNodes(form);
        if (n.roleSelect.value === '2') {
            n.instansiWrap.classList.remove('d-none');
            await loadLayanan(form, selectedKode);
        } else {
            n.instansiWrap.classList.add('d-none');
            n.instansiSelect.value = '';
            n.layananWrap.classList.add('d-none');
            n.layananChecklist.innerHTML = '';
        }
    }

    [addForm, editForm].forEach(form => {
        if (!form) return;
        const n = getFormNodes(form);

        n.roleSelect.addEventListener('change', () => toggleScope(form));
        n.instansiSelect.addEventListener('change', () => loadLayanan(form));

        toggleScope(form);
    });

    function bindEditButtons() {
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', async function() {
                editForm.action = '<?= base_url('superadmin/edit_user/') ?>' + this.dataset.id;
                editForm.nama_lengkap.value = this.dataset.nama || '';
                editForm.username.value = this.dataset.username || '';
                editForm.password.value = '';
                editForm.nik.value = this.dataset.nik || '';
                editForm.alamat.value = this.dataset.alamat || '';
                editForm.email.value = this.dataset.email || '';
                editForm.no_hp.value = this.dataset.noHp || '';
                editForm.role_id.value = this.dataset.role || '';
                editForm.is_active.value = this.dataset.active || '1';

                const n = getFormNodes(editForm);
                n.instansiSelect.value = this.dataset.instansi || '';
                await toggleScope(editForm, splitKodeLayanan(this.dataset.kodeLayanan || ''));
            });
        });
    }

    function bindDetailButtons() {
        const setText = (id, value) => {
            const el = document.getElementById(id);
            if (el) el.textContent = value && String(value).trim() !== '' ? value : '-';
        };

        document.querySelectorAll('.btn-detail').forEach(btn => {
            btn.addEventListener('click', function() {
                setText('d_nama', this.dataset.nama);
                setText('d_username', this.dataset.username);
                setText('d_role', this.dataset.roleName);
                setText('d_status', this.dataset.status);
                setText('d_instansi', this.dataset.instansiName);
                setText('d_kode_instansi', this.dataset.kodeInstansi);
                setText('d_akses_layanan', this.dataset.aksesLayanan);
                setText('d_nik', this.dataset.nik);
                setText('d_no_hp', this.dataset.noHp);
                setText('d_email', this.dataset.email);
                setText('d_alamat', this.dataset.alamat);
            });
        });
    }

    bindEditButtons();
    bindDetailButtons();

    document.getElementById('searchUser').addEventListener('keyup', function() {
        const keyword = this.value.trim();
        const paginationContainer = document.getElementById('paginationContainer');
        if (keyword.length < 2 && keyword !== '') return;

        fetch(`<?= base_url('superadmin/search_users_ajax?keyword=') ?>${encodeURIComponent(keyword)}`)
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('userTableBody');
                tbody.innerHTML = '';
                paginationContainer.style.display = 'none';

                if (data.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-3">Tidak ada data ditemukan.</td></tr>`;
                    return;
                }

                data.forEach((u, i) => {
                    let instansiHtml = '<span class="text-muted">-</span>';
                    if (u.kode_instansi) {
                        instansiHtml = `<span class="badge bg-dark-subtle text-dark border">${escapeHtml(u.kode_instansi)}</span>`;
                    }

                    let aksesLayanan = '<span class="text-muted">-</span>';
                    if (String(u.role_id) === '2' && u.instansi_id) {
                        if (u.kode_layanan) {
                            aksesLayanan = u.kode_layanan
                                .split(',')
                                .map(k => k.trim())
                                .filter(Boolean)
                                .map(k => `<span class="badge bg-info text-dark">${escapeHtml(k)}</span>`)
                                .join(' ');
                        } else {
                            aksesLayanan = '<span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">Semua layanan</span>';
                        }
                    }

                    tbody.innerHTML += `
<tr>
    <td>${i + 1}</td>
    <td class="cell-wrap fw-semibold">${escapeHtml(u.nama_lengkap)}</td>
    <td>${escapeHtml(u.username)}</td>
    <td class="text-center"><span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle">${escapeHtml(u.nama_role ?? '-')}</span></td>
    <td class="cell-wrap">${instansiHtml}</td>
    <td class="text-center">
        <span class="badge bg-${u.is_active == 1 ? 'success' : 'secondary'}">
            ${u.is_active == 1 ? 'Aktif' : 'Nonaktif'}
        </span>
    </td>
    <td class="text-nowrap">
        <button class="btn btn-sm btn-info text-white btn-detail"
            data-nama="${escapeHtml(u.nama_lengkap ?? '')}"
            data-username="${escapeHtml(u.username ?? '')}"
            data-nik="${escapeHtml(u.nik ?? '')}"
            data-alamat="${escapeHtml(u.alamat ?? '')}"
            data-email="${escapeHtml(u.email ?? '')}"
            data-no-hp="${escapeHtml(u.no_hp ?? '')}"
            data-role-name="${escapeHtml(u.nama_role ?? '-')}"
            data-instansi-name="${escapeHtml(u.nama_instansi ?? '-')}"
            data-kode-instansi="${escapeHtml(u.kode_instansi ?? '-')}"
            data-akses-layanan="${escapeHtml((u.kode_layanan && String(u.kode_layanan).trim() !== '') ? u.kode_layanan : (String(u.role_id) === '2' ? 'Semua layanan' : '-'))}"
            data-status="${u.is_active == 1 ? 'Aktif' : 'Nonaktif'}"
            data-bs-toggle="modal"
            data-bs-target="#detailUserModal">
            <i class="ri-eye-line"></i> Detail
        </button>
        <button class="btn btn-sm btn-warning btn-edit"
            data-id="${u.id}"
            data-nama="${escapeHtml(u.nama_lengkap)}"
            data-username="${escapeHtml(u.username)}"
            data-nik="${escapeHtml(u.nik)}"
            data-alamat="${escapeHtml(u.alamat)}"
            data-email="${escapeHtml(u.email)}"
            data-no-hp="${escapeHtml(u.no_hp)}"
            data-role="${u.role_id}"
            data-instansi="${u.instansi_id ?? ''}"
            data-kode-layanan="${escapeHtml(u.kode_layanan ?? '')}"
            data-active="${u.is_active}"
            data-bs-toggle="modal"
            data-bs-target="#editUserModal">
            <i class="ri-edit-line"></i> Edit
        </button>
        <a href="<?= base_url('superadmin/delete_user/') ?>${u.id}"
           class="btn btn-sm btn-danger"
           onclick="return confirm('Hapus user ini?')">
           <i class="ri-delete-bin-line"></i> Hapus
        </a>
    </td>
</tr>`;
                });

                bindEditButtons();
                bindDetailButtons();
            });

        if (keyword === '') {
            location.reload();
        }
    });
})();
</script>
