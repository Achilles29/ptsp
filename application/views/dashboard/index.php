<div class="container mt-4">
    <h3>Selamat datang, <?= $user['nama_lengkap'] ?>!</h3>

    <?php if ($user['role_id'] == 1): ?>
        <div class="alert alert-info mt-3">Anda login sebagai <strong>Superadmin</strong>.</div>
    <?php elseif ($user['role_id'] == 2): ?>
        <div class="alert alert-info mt-3">Anda login sebagai <strong>Admin Layanan</strong>.</div>
    <?php elseif ($user['role_id'] == 3): ?>
        <div class="alert alert-info mt-3">Anda login sebagai <strong>Customer Service</strong>.</div>
    <?php else: ?>
        <div class="alert alert-info mt-3">Anda login sebagai <strong>Masyarakat</strong>.</div>
    <?php endif; ?>
</div>