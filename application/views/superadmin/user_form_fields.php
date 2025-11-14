<input type="text" name="nama_lengkap" class="form-control mb-2" placeholder="Nama Lengkap" required>
<input type="text" name="username" class="form-control mb-2" placeholder="Username" required>
<input type="password" name="password" class="form-control mb-2" placeholder="Password (kosongkan jika tidak diubah)">
<input type="text" name="nik" class="form-control mb-2" placeholder="NIK">
<textarea name="alamat" class="form-control mb-2" placeholder="Alamat"></textarea>
<input type="email" name="email" class="form-control mb-2" placeholder="Email">
<input type="text" name="no_hp" class="form-control mb-2" placeholder="No HP">
<select name="role_id" id="role_id" class="form-control mb-2" required onchange="toggleInstansiDropdown()">
    <option value="">-- Pilih Role --</option>
    <option value="1">Superadmin</option>
    <option value="2">Admin Layanan</option>
    <option value="3">CS</option>
    <option value="4">Masyarakat</option>
</select>

<!-- Dropdown Instansi (hanya untuk Admin Layanan) -->
<div id="instansiDropdown" style="display: none;">
    <select name="instansi_id" id="instansi_id" class="form-control mb-2">
        <option value="">-- Pilih Instansi --</option>
        <?php foreach ($this->db->order_by('nama_instansi')->get('instansi')->result() as $inst): ?>
            <option value="<?= $inst->id ?>"><?= $inst->nama_instansi ?></option>
        <?php endforeach; ?>
    </select>
</div>

<select name="is_active" class="form-control mb-2" required>
    <option value="1">Aktif</option>
    <option value="0">Nonaktif</option>
</select>