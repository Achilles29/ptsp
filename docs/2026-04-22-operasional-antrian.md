# Perubahan Operasional Antrian

Tanggal: 2026-04-22

## Latar Belakang

Sebelumnya antrian aktif masih bisa dianggap aktif walaupun hari layanan sudah lewat. Status layanan juga hanya bergantung pada aksi manual admin atau superadmin.

Perubahan ini menambahkan aturan operasional berbasis jam agar perilaku antrian lebih sesuai dengan operasional kantor.

## Fungsi Yang Ditambahkan

### 1. Pembersihan antrian aktif dari hari sebelumnya

Sistem sekarang otomatis membatalkan antrian dengan status aktif (`terdaftar`, `menunggu`, `dipanggil`) jika `tanggal < hari ini`.

Tujuan:
- antrian yang lewat hari tidak lagi muncul sebagai antrian aktif
- dashboard masyarakat dan admin menjadi lebih akurat

### 2. Jam tutup pendaftaran online

Ditambahkan field:
- `jam_tutup_pendaftaran`

Aturan:
- masyarakat masih bisa mendaftar online untuk hari ini hanya sampai jam ini
- jika sudah melewati jam tersebut, pendaftaran online untuk hari ini ditolak
- masyarakat diarahkan untuk memilih tanggal besok
- pendaftaran offline/frontdesk tetap bisa dilakukan selama layanan masih berjalan dan belum melewati jam tutup kantor

### 3. Jam layanan

Ditambahkan field:
- `jam_layanan_mulai`
- `jam_layanan_selesai`

Aturan:
- bila mode status layanan = `otomatis`, maka:
  - saat waktu sekarang berada di antara `jam_layanan_mulai` dan `jam_layanan_selesai`, status layanan menjadi `buka`
  - di luar rentang itu, status layanan menjadi `tutup`

### 4. Jam tutup kantor

Ditambahkan field:
- `jam_tutup_kantor`

Aturan:
- saat waktu sekarang sudah melewati `jam_tutup_kantor`, seluruh antrian aktif untuk hari itu otomatis dibatalkan
- yang dibatalkan adalah status:
  - `terdaftar`
  - `menunggu`
  - `dipanggil`

### 5. Mode status layanan

Ditambahkan field:
- `status_layanan_mode`

Nilai yang tersedia:
- `otomatis`
- `buka`
- `tutup`

Aturan:
- `otomatis`: status layanan mengikuti jam layanan
- `buka`: layanan dipaksa tetap buka
- `tutup`: layanan dipaksa tutup

Catatan:
- jika mode diubah ke `tutup`, antrian aktif hari itu langsung dibatalkan

## Cara Kerja Teknis

Sinkronisasi operasional dijalankan melalui hook CodeIgniter:
- file: `application/hooks/Operational_hours_hook.php`

Hook memanggil:
- `application/models/Operasional_model.php`

Proses sinkronisasi yang dijalankan:
- memastikan kolom operasional di tabel `instansi` tersedia
- membatalkan antrian aktif dari hari sebelumnya
- menghitung ulang status layanan efektif berdasarkan mode dan jam
- membatalkan sisa antrian aktif hari ini jika sudah melewati jam tutup kantor

## Penting

Pembatalan otomatis saat jam tutup kantor sekarang **sudah ada**, tetapi mekanismenya berjalan saat ada request ke aplikasi.

Artinya:
- jika setelah jam tutup kantor ada akses ke aplikasi, sistem akan langsung menjalankan sinkronisasi dan membatalkan antrian aktif hari itu
- jika tidak ada request sama sekali setelah jam tutup kantor, pembatalan akan terjadi pada request berikutnya

Jika ingin benar-benar tepat waktu tanpa menunggu request, langkah lanjutan yang disarankan adalah membuat cron job/server scheduler yang memanggil proses sinkronisasi secara berkala.

## Dampak Ke Halaman

### Masyarakat

- form pendaftaran online menampilkan informasi batas pendaftaran
- jika layanan dipaksa tutup, tombol daftar otomatis dinonaktifkan
- validasi backend tetap berjalan walaupun frontend di-bypass

### Frontdesk

- pendaftaran walk-in sekarang ditolak bila instansi sedang di luar jam operasional atau dipaksa tutup
- pesan error ditampilkan ke petugas

### Admin Layanan / Superadmin

- tersedia mode status layanan: otomatis, paksa buka, paksa tutup
- pengaturan instansi sekarang menyimpan jam operasional lengkap

## Nilai Default Yang Dipakai

Untuk data instansi yang lama, default yang dipakai:
- `jam_tutup_pendaftaran` = `15:30:00`
- `jam_layanan_mulai` = `08:30:00`
- `jam_layanan_selesai` = `16:00:00`
- `jam_tutup_kantor` = `16:30:00`
- `status_layanan_mode` = `otomatis`
