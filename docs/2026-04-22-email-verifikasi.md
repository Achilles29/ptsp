# Modul Pengaturan Email Verifikasi

Tanggal: 2026-04-22

## Ringkasan

Perubahan ini memindahkan pengaturan email verifikasi dari hardcode file `application/config/email.php` ke modul superadmin.

Sekarang superadmin dapat mengatur:

- akun SMTP pengirim
- email dan nama pengirim
- enkripsi dan port SMTP
- cooldown kirim ulang verifikasi
- subjek email verifikasi
- isi pesan email verifikasi

## Lokasi Menu

- `Superadmin > Pengaturan Email`

## Placeholder Template

Placeholder berikut bisa dipakai di subjek dan isi email:

- `{nama_lengkap}`
- `{email}`
- `{verification_link}`
- `{app_name}`
- `{from_name}`

## Alur Baru

1. Pengguna daftar akun.
2. Sistem membuat token verifikasi.
3. Sistem mengirim email memakai konfigurasi dari tabel `email_settings`.
4. Waktu kirim terakhir dicatat pada kolom `users.verification_sent_at`.
5. Jika email belum diterima, pengguna bisa kirim ulang setelah cooldown yang diatur.

## Pengaturan Gmail Yang Disarankan

Jika memakai Gmail:

1. Aktifkan `2-Step Verification`.
2. Buat `App Password` dari akun Google.
3. Gunakan:
   - host: `smtp.gmail.com`
   - SSL + port `465`, atau
   - TLS + port `587`
4. Isi akun SMTP dengan alamat Gmail lengkap.
5. Masukkan App Password ke password SMTP.
6. Disarankan `from_email` sama dengan akun SMTP.

## Kenapa Email Bisa Terblokir

Penyebab paling umum:

- akun Gmail baru langsung mengirim terlalu banyak email
- isi email terlalu mirip dan berulang dalam waktu singkat
- email hanya berisi link tanpa penjelasan
- terlalu banyak pengiriman ke alamat salah atau tidak aktif
- alamat pengirim berbeda dengan akun SMTP sehingga terlihat mencurigakan
- penerima sering mengabaikan atau menandai sebagai spam

## Cara Mengurangi Risiko Spam / Blokir

- kirim bertahap, jangan langsung volume besar
- pakai nama pengirim yang jelas
- tambahkan isi email yang wajar, bukan hanya link
- gunakan subject yang natural
- gunakan alamat email pengirim yang konsisten
- minta pengguna cek folder spam lebih dulu
- bersihkan data email yang tidak valid
- jika trafik tinggi, pertimbangkan layanan transactional email atau Google Workspace

## Catatan Teknis

- file `application/config/email.php` sekarang hanya menjadi fallback kosong tanpa kredensial
- pengiriman verifikasi dan reset password memakai konfigurasi runtime dari database
- tombol kirim ulang verifikasi tersedia di halaman daftar dan login
