# Inventaris File dan Database

Tanggal: 2026-04-22

## File Baru

- `application/hooks/Operational_hours_hook.php`
- `application/models/Email_setting_model.php`
- `application/models/Operasional_model.php`
- `application/views/superadmin/pengaturan_email.php`
- `sql/2026-04-22_operasional_antrian.sql`
- `sql/2026-04-22_email_verification_settings.sql`
- `docs/README.md`
- `docs/2026-04-22-operasional-antrian.md`
- `docs/2026-04-22-file-database-changes.md`
- `docs/2026-04-22-email-verifikasi.md`

## File Yang Berubah

- `application/config/config.php`
- `application/config/email.php`
- `application/config/hooks.php`
- `application/controllers/Auth.php`
- `application/controllers/Admin_layanan.php`
- `application/controllers/Masyarakat.php`
- `application/controllers/Pendaftaran.php`
- `application/controllers/Superadmin.php`
- `application/models/Instansi_model.php`
- `application/models/Masyarakat_model.php`
- `application/models/User_model.php`
- `application/views/auth/login.php`
- `application/views/auth/register.php`
- `application/views/admin_layanan/kelola_layanan.php`
- `application/views/masyarakat/daftar_antrian.php`
- `application/views/pendaftaran/manual.php`
- `application/views/pendaftaran/manual_v2.php`
- `application/views/pendaftaran/manual_v2_tab.php`
- `application/views/pendaftaran/manual_v2_x.php`
- `application/views/superadmin/instansi.php`
- `application/views/superadmin/kelola_layanan.php`
- `application/views/templates/_sidebar.php`
- `ptsp.sql`

## Ringkasan Per File

### Konfigurasi

- `application/config/config.php`
  Mengaktifkan hooks CodeIgniter.

- `application/config/hooks.php`
  Mendaftarkan hook sinkronisasi operasional.

### Hook dan model baru

- `application/models/Email_setting_model.php`
  Menyimpan konfigurasi SMTP, template email verifikasi, cooldown resend, dan pengiriman email runtime.

- `application/hooks/Operational_hours_hook.php`
  Menjalankan sinkronisasi operasional otomatis setiap request.

- `application/models/Operasional_model.php`
  Pusat logika jam operasional, validasi online/offline, sinkronisasi status layanan, dan pembatalan otomatis antrian aktif.

### Controller

- `application/controllers/Auth.php`
  Mengubah pengiriman verifikasi dan reset password menjadi berbasis pengaturan database, serta menambahkan fitur kirim ulang verifikasi publik.

- `application/controllers/Masyarakat.php`
  Menambahkan validasi pendaftaran online berbasis jam dan mempersempit definisi antrian aktif ke tanggal hari ini dan ke depan.

- `application/controllers/Pendaftaran.php`
  Menambahkan validasi operasional untuk pendaftaran offline/frontdesk.

- `application/controllers/Admin_layanan.php`
  Mengubah pengelolaan status layanan menjadi mode `otomatis/buka/tutup`.

- `application/controllers/Superadmin.php`
  Menyimpan jam operasional instansi, mode status layanan, dan pengaturan email verifikasi melalui UI superadmin.

### Model yang diperbarui

- `application/models/Instansi_model.php`
  Menyesuaikan update status dan pembatalan antrian aktif hari ini.

- `application/models/Masyarakat_model.php`
  Mengoreksi query antrian aktif agar tidak lagi menghitung antrian yang sudah lewat hari.

- `application/models/User_model.php`
  Menambahkan helper token verifikasi, email lookup, dan timestamp kirim verifikasi.

### View

- `application/views/superadmin/pengaturan_email.php`
  Form pengaturan SMTP, template email, dan panduan Gmail.

- `application/views/auth/login.php`
- `application/views/auth/register.php`
  Menambahkan form kirim ulang verifikasi dengan cooldown.

- `application/views/admin_layanan/kelola_layanan.php`
  Menampilkan jam operasional dan mode status layanan.

- `application/views/superadmin/kelola_layanan.php`
  Mengubah opsi status menjadi mode operasional.

- `application/views/superadmin/instansi.php`
  Menambahkan field jam operasional dan mode status pada form tambah/edit instansi.

- `application/views/masyarakat/daftar_antrian.php`
  Menambahkan hint batas pendaftaran online dan penyesuaian otomatis tanggal.

- `application/views/pendaftaran/manual.php`
- `application/views/pendaftaran/manual_v2.php`
- `application/views/pendaftaran/manual_v2_tab.php`
- `application/views/pendaftaran/manual_v2_x.php`
  Menampilkan pesan error backend yang lebih spesifik saat pendaftaran offline ditolak.

- `application/views/templates/_sidebar.php`
  Menambahkan menu `Pengaturan Email` pada area superadmin.

- `application/config/email.php`
  Menghapus kredensial hardcode dan menjadikannya fallback aman.

## Database Yang Berubah

## Tabel yang berubah struktur

### `instansi`

Kolom baru:
- `jam_tutup_pendaftaran` TIME NOT NULL DEFAULT `15:30:00`
- `jam_layanan_mulai` TIME NOT NULL DEFAULT `08:30:00`
- `jam_layanan_selesai` TIME NOT NULL DEFAULT `16:00:00`
- `jam_tutup_kantor` TIME NOT NULL DEFAULT `16:30:00`
- `status_layanan_mode` ENUM(`otomatis`,`buka`,`tutup`) NOT NULL DEFAULT `otomatis`

Kolom lama yang tetap dipakai:
- `status_layanan`

Catatan:
- `status_layanan` sekarang menjadi status efektif yang bisa dihitung otomatis
- `status_layanan_mode` menjadi sumber aturan manual/otomatis

### `antrian`

Tidak ada perubahan struktur tabel.

Namun ada perubahan perilaku data:
- antrian aktif dari hari sebelumnya akan diupdate menjadi `batal`
- antrian aktif hari ini akan diupdate menjadi `batal` saat melewati `jam_tutup_kantor`

### `users`

Kolom baru:
- `verification_sent_at` DATETIME NULL

Fungsi:
- mencatat waktu terakhir email verifikasi dikirim
- dipakai untuk membatasi kirim ulang verifikasi publik

### `email_settings`

Tabel baru:
- menyimpan konfigurasi SMTP
- menyimpan identitas pengirim
- menyimpan subjek dan isi email verifikasi
- menyimpan jeda cooldown kirim ulang

## Tabel baru

- `email_settings`

## File SQL Yang Diperbarui

- `ptsp.sql`
- `sql/2026-04-22_operasional_antrian.sql`
- `sql/2026-04-22_email_verification_settings.sql`

Perubahan pada file ini:
- struktur tabel `instansi` diperbarui
- struktur tabel `users` diperluas dengan `verification_sent_at`
- tabel `email_settings` ditambahkan
- data seed `instansi` diperluas agar memuat nilai default jam operasional dan mode status layanan
- tersedia script migrasi terpisah untuk dijalankan manual di server lain
