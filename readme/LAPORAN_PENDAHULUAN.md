# Laporan Pendahuluan
**Pekerjaan:** Pembuatan Aplikasi Antrian Berbasis Website – MPP Kabupaten Rembang  
**Program:** Pelayanan Penanaman Modal  
**Kegiatan:** Pelayanan Perizinan dan Non‑Perizinan Terpadu Satu Pintu di Bidang Penanaman Modal yang Menjadi Kewenangan Daerah Kabupaten/Kota  
**Satuan Kerja:** Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu (DPMPTSP) Kabupaten Rembang  
**Pelaksana:** CV. Pratama Mukti Consultant, Perumahan Bumi Wana Mukti H3 No.14, Kota Semarang  

---

## BAB I. PENDAHULUAN
### 1.1 Latar Belakang
Mal Pelayanan Publik (MPP) Kabupaten Rembang merupakan integrasi berbagai layanan publik dalam satu lokasi layanan. Integrasi tersebut membutuhkan tata kelola antrian yang tertib, transparan, dan mampu mengurangi kepadatan pemohon. Sistem antrian manual memiliki keterbatasan: pencatatan tidak konsisten, sulit dimonitor secara real‑time, pemohon tidak memiliki estimasi waktu pelayanan, dan petugas loket perlu melakukan administrasi antrian secara berulang.

Berdasarkan KAK dari DPMPTSP, pengembangan aplikasi antrian online dimaksudkan untuk memberikan kanal pengambilan nomor antrian secara daring dan menampilkan informasi posisi antrian secara real‑time. Sebagai konsultan, kami menyusun rencana aplikasi yang selaras dengan kebutuhan layanan MPP dan kondisi lapangan. Rencana ini juga menyesuaikan basis implementasi yang telah tersedia pada project di direktori `ptsp/` sebagai fondasi teknis pengembangan.

### 1.2 Maksud dan Tujuan
Maksud kegiatan ini adalah membangun aplikasi antrian berbasis web untuk MPP Kabupaten Rembang yang terintegrasi dengan modul administrasi layanan, front desk, dan display antrian. Tujuan teknisnya meliputi:

1. Menyediakan sistem pendaftaran antrian yang dapat diakses masyarakat secara online maupun melalui front desk.
2. Mengotomatiskan penomoran antrian, pemanggilan, serta pencatatan status layanan.
3. Menyediakan dashboard real‑time untuk petugas dan pengelola MPP.
4. Memastikan integrasi dengan perangkat pendukung (printer thermal, display antrian, dan perangkat operator).

### 1.3 Sasaran
Sasaran pengembangan meliputi:

1. **Masyarakat (pemohon layanan).** Masyarakat dapat mengambil nomor antrian tanpa harus menunggu lama di lokasi. Informasi estimasi layanan dapat diakses sebelum datang.
2. **Instansi yang tergabung dalam MPP.** Instansi memperoleh modul pemantauan antrian, statistik layanan, serta kontrol jadwal operasional.
3. **Petugas pelayanan.** Proses pemanggilan dan validasi kehadiran dilakukan melalui panel petugas yang tersinkronisasi.
4. **Pengelola MPP.** Sistem menyediakan pelaporan dan monitoring untuk evaluasi layanan dan mendukung SPBE.

### 1.4 Lokasi Kegiatan
Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu (DPMPTSP) Kabupaten Rembang.

### 1.5 Sumber Pendanaan
DBH Tahun Anggaran 2025 sebesar Rp 100.000.000,- (seratus juta rupiah).

---

## BAB II. GAMBARAN UMUM PROYEK
### 2.1 Deskripsi Singkat
Aplikasi antrian online MPP Kabupaten Rembang adalah sistem berbasis web yang memfasilitasi pengambilan nomor antrian, pengelolaan layanan, pemanggilan antrian, serta penyajian informasi real‑time kepada masyarakat dan petugas. Sistem dirancang dengan model multi‑instansi, sehingga satu platform dapat menangani berbagai layanan dari instansi yang berbeda.

### 2.2 Data Dasar Instansi/Layanan
Data dasar instansi yang dilayani meliputi: BPJS Kesehatan, BPJS Ketenagakerjaan, BRI, Bank Jateng, Polres, Kejari, ATR/BPN, Kemenag, Pengadilan Agama, KP2KP, PDAM, DPMPTSP, DLH, DPU TARU, BPPKAD, Disdukcapil, Dinkes, Dinperinnaker, Dinsosppkb, dan Taspen.

### 2.3 Kondisi Sistem (Basis Proyek)
Project yang tersedia di direktori `ptsp/` telah memiliki struktur aplikasi berbasis PHP dengan pola MVC. Dalam direktori tersebut terdapat modul pendaftaran antrian (`pendaftaran/manual`, `manual_v2`, `manual_v2_tab`), modul admin layanan, modul superadmin, serta display antrian. Struktur ini menjadi acuan pengembangan lanjutan dan pembenahan agar sesuai dengan KAK. Penggunaan database serta logika penomoran antrian telah ada sebagai baseline yang akan disempurnakan.

---

## BAB III. ANALISIS KEBUTUHAN SISTEM
### 3.1 Kebutuhan Fungsional
1. **Pendaftaran antrian.** Sistem menyediakan pengambilan nomor antrian online dan walk‑in front desk. Nomor antrian dihasilkan otomatis berdasarkan instansi/layanan.
2. **Manajemen layanan.** Admin dapat menambah, menonaktifkan, dan mengatur layanan per instansi.
3. **Pemanggilan antrian.** Petugas memanggil antrian, memperbarui status (terdaftar, hadir, selesai, batal).
4. **Monitoring antrian.** Dashboard menampilkan jumlah antrian, antrian aktif, dan status panggilan secara real‑time.
5. **Pelaporan.** Rekap antrian harian, mingguan, bulanan per instansi.
6. **Integrasi perangkat.** Sistem terhubung ke printer thermal untuk cetak tiket dan layar display untuk pemanggilan.

### 3.2 Kebutuhan Non‑Fungsional
1. **Ketersediaan.** Sistem dapat diakses selama jam operasional dengan downtime minimal.
2. **Keamanan.** Akses role‑based, validasi input, dan audit aktivitas petugas.
3. **Kinerja.** Respon cepat pada proses pengambilan nomor dan pemanggilan.
4. **Skalabilitas.** Dapat menambah instansi baru tanpa perubahan besar.

---

## BAB IV. DESAIN ARSITEKTUR SISTEM
### 4.1 Arsitektur Aplikasi
Arsitektur menggunakan pendekatan 3‑tier: frontend web (UI), backend aplikasi (controller/model), dan database. Komunikasi antar modul menggunakan endpoint internal berbasis HTTP/JSON.

### 4.2 Komponen Utama
1. **Frontend Masyarakat.** Antarmuka pengambilan nomor antrian online dan informasi status.
2. **Frontend Front Desk.** Antarmuka untuk pendaftaran manual, pemilihan instansi, layanan, serta cetak tiket.
3. **Backend Admin.** Pengelolaan user, instansi, layanan, dan laporan.
4. **Display Antrian.** Menampilkan nomor antrian yang sedang dipanggil dan antrian berikutnya.
5. **Printer Service.** Service lokal berbasis Python untuk cetak tiket thermal.

### 4.3 Alur Data Utama
1. Pemohon memilih instansi → layanan → sistem menghasilkan nomor antrian.
2. Data antrian disimpan di database.
3. Front desk mencetak tiket melalui printer service lokal.
4. Petugas memanggil nomor melalui panel admin.
5. Display antrian menampilkan nomor yang dipanggil.

---

## BAB V. DESAIN BASIS DATA
### 5.1 Struktur Data Inti
Struktur data mencakup tabel `instansi`, `jenis_layanan`, `antrian`, `users`, dan tabel pendukung lainnya. Setiap antrian mengacu pada layanan dan instansi untuk menjaga konsistensi nomor antrian.

### 5.2 Logika Penomoran
Nomor antrian dihasilkan berdasarkan kode huruf instansi/layanan dan urutan harian (misal A001, A002). Penomoran direset setiap hari sesuai tanggal pelayanan.

---

## BAB VI. DESAIN ANTARMUKA
### 6.1 Antarmuka Masyarakat
Halaman publik menampilkan daftar instansi dan layanan. Pemohon dapat mengambil nomor antrian online dan melihat status.

### 6.2 Antarmuka Front Desk
Antarmuka front desk pada project `manual_v2_tab` menampilkan kartu instansi, tombol layanan, dan aksi cetak tiket. Desain ini dioptimalkan untuk layar kiosk.

### 6.3 Antarmuka Admin
Panel admin menyediakan menu pengelolaan instansi, layanan, user, dan laporan. Role‑based access memastikan hak akses sesuai tugas.

---

## BAB VII. RENCANA IMPLEMENTASI TEKNIS
### 7.1 Tahapan Implementasi
1. **Analisis kebutuhan detail** berdasarkan instansi dan SOP layanan.
2. **Desain ulang UI** agar konsisten dan mudah digunakan.
3. **Pengembangan modul** yang belum tersedia (laporan lanjutan, monitoring real‑time).
4. **Integrasi printer thermal** dan display antrian.
5. **Uji fungsional** bersama user front desk dan admin layanan.

### 7.2 Infrastruktur
- Server aplikasi web + database.
- Jaringan internal MPP.
- Mini PC untuk front desk dan display.
- Printer thermal untuk cetak tiket.

---

## BAB VIII. RENCANA PENGUJIAN, PELATIHAN, SOSIALISASI, DAN PEMELIHARAAN
Rencana ini dirancang sebagai satu rangkaian kegiatan untuk memastikan aplikasi berjalan stabil, dapat diterima oleh pengguna, dan terpelihara secara berkelanjutan setelah implementasi. Pengujian dilakukan bertahap untuk memastikan kualitas sistem, diikuti pelatihan serta sosialisasi agar proses operasional berjalan konsisten di setiap loket, dan ditutup dengan skema pemeliharaan yang menjamin ketersediaan layanan.

### 8.1 Rencana Pengujian
Pengujian dilakukan dalam tiga tahap utama: uji unit, uji integrasi, dan uji penerimaan pengguna (UAT). Uji unit memeriksa setiap fungsi kritis seperti pembentukan nomor antrian, validasi input, dan pencatatan status. Uji integrasi memastikan alur data dari frontend ke backend hingga database berjalan konsisten, termasuk koneksi ke modul display dan printer thermal. UAT dilakukan bersama petugas dan admin untuk memvalidasi skenario bisnis nyata, seperti pengambilan nomor, pemanggilan berurutan, perubahan status hadir/selesai, serta pencetakan tiket yang akurat.

### 8.2 Rencana Pelatihan dan Sosialisasi
Pelatihan dirancang untuk tiga kelompok pengguna: admin sistem, petugas layanan, dan operator front desk. Materi pelatihan mencakup cara mengelola instansi dan layanan, menjalankan proses pendaftaran manual, melakukan pemanggilan antrian, serta memonitor antrean harian. Sosialisasi untuk masyarakat dilakukan melalui panduan tertulis, poster di area layanan, dan video singkat di display publik, agar pemohon memahami alur pengambilan nomor dan aturan layanan.

### 8.3 Rencana Pemeliharaan
Pemeliharaan mencakup monitoring performa aplikasi, backup data berkala, pembaruan keamanan, serta perbaikan bug jika ditemukan pada operasi harian. Selain itu disiapkan rencana pengembangan lanjutan seperti integrasi notifikasi WhatsApp, statistik layanan yang lebih detail, dan modul pelaporan lanjutan. Pemeliharaan dilakukan secara terjadwal agar layanan tetap tersedia dan dapat berkembang sesuai kebutuhan MPP.

---

## BAB XI. PENUTUP
Laporan pendahuluan ini menjadi acuan pelaksanaan pekerjaan dan garis besar desain teknis aplikasi antrian MPP Kabupaten Rembang. Rencana yang disusun menekankan konsistensi proses bisnis, integrasi antar‑modul, serta kesiapan operasional di lingkungan MPP. Seluruh tahapan akan divalidasi melalui pengujian bertahap dan pendampingan pengguna agar implementasi berjalan stabil dan terukur.\n\nPada tahap pelaksanaan, rincian teknis akan disesuaikan dengan kondisi lapangan, kebutuhan operasional loket, serta kesiapan infrastruktur. Perubahan minor pada alur maupun konfigurasi akan didokumentasikan agar tetap menjaga konsistensi pelayanan. Dengan demikian, aplikasi yang dibangun tidak hanya memenuhi kebutuhan antrian, tetapi juga menjadi fondasi pengelolaan layanan publik yang terstandar, transparan, dan mudah dikembangkan di masa mendatang.

---

# LAMPIRAN
## Lampiran A. Ringkasan Modul Aplikasi (Berdasarkan Project `ptsp/`)
1. **Pendaftaran**: `pendaftaran/manual`, `manual_v2`, `manual_v2_tab`.
2. **Admin & Superadmin**: manajemen user, instansi, layanan, dan laporan.
3. **Display Antrian**: modul tampil panggilan.
4. **Printer Service**: service Python untuk cetak tiket thermal.

## Lampiran B. Rencana Integrasi Printer Thermal
Printer thermal di front desk dihubungkan dengan mini PC Ubuntu melalui USB. Service lokal `thermal_server.py` menerima payload JSON dari frontend dan melakukan cetak tiket menggunakan ESC/POS.

## Lampiran C. Rencana Struktur API Internal
1. Endpoint pengambilan nomor.
2. Endpoint pemanggilan antrian.
3. Endpoint update status antrian.
4. Endpoint monitoring display.

## Lampiran D. Rencana Keamanan
1. Role‑based access control.
2. Validasi input dan sanitasi.
3. Logging aktivitas admin.

## Lampiran E. Rencana Pengembangan Lanjutan
1. Notifikasi WhatsApp.
2. Statistik layanan lanjutan.
3. Rekap pelayanan berbasis instansi.

---

**Lokasi Dokumen:** `ptsp/readme/LAPORAN_PENDAHULUAN.md`
