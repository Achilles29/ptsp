<input type="text" name="nama_lengkap" class="form-control mb-2" placeholder="Nama Lengkap" required>
<input type="text" name="username" class="form-control mb-2" placeholder="Username" required>
<input type="password" name="password" class="form-control mb-2" placeholder="Password (kosongkan jika tidak diubah)">
<input type="text" name="nik" class="form-control mb-2" placeholder="NIK">
<textarea name="alamat" class="form-control mb-2" placeholder="Alamat"></textarea>
<input type="email" name="email" class="form-control mb-2" placeholder="Email">
<input type="text" name="no_hp" class="form-control mb-2" placeholder="No HP">
<select name="role_id" class="form-control mb-2 role-select" required>
    <option value="">-- Pilih Role --</option>
    <option value="1">Superadmin</option>
    <option value="2">Admin Layanan</option>
    <option value="4">Masyarakat</option>
</select>

<div class="instansi-dropdown d-none">
    <select name="instansi_id" class="form-control mb-2 instansi-select">
        <option value="">-- Pilih Instansi --</option>
        <?php foreach ($this->db->order_by('nama_instansi')->get('instansi')->result() as $inst): ?>
            <option value="<?= $inst->id ?>"><?= $inst->nama_instansi ?></option>
        <?php endforeach; ?>
    </select>

    <div class="layanan-dropdown d-none">
        <label class="form-label mb-1">Kode Layanan (opsional)</label>
        <div class="kode-layanan-checklist border rounded p-2 mb-1" style="max-height:180px; overflow:auto;"></div>
        <small class="text-muted">Jika tidak dipilih, user akan mengakses semua layanan pada instansi tersebut.</small>
    </div>
</div>

<select name="is_active" class="form-control mb-2" required>
    <option value="1">Aktif</option>
    <option value="0">Nonaktif</option>
</select>
