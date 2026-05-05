# Kata Pengantar
Puji syukur kami panjatkan ke hadirat Tuhan Yang Maha Esa, karena atas rahmat dan karunia‑Nya Laporan Akhir Pembuatan Aplikasi Antrian Berbasis Website untuk MPP Kabupaten Rembang ini dapat diselesaikan. Laporan ini disusun sebagai bentuk pertanggungjawaban pelaksanaan pekerjaan sekaligus dokumentasi teknis hasil implementasi sistem.\n
Dalam proses penyusunan dan pelaksanaan, berbagai pihak telah memberikan dukungan dan masukan yang sangat berarti. Kami mengucapkan terima kasih kepada seluruh pihak yang terlibat dalam koordinasi, pengujian, serta pendampingan operasional sehingga aplikasi dapat berjalan sesuai kebutuhan layanan publik.\n
Kami menyadari laporan ini masih memiliki kekurangan. Oleh karena itu, saran dan masukan dari semua pihak sangat kami harapkan untuk penyempurnaan di masa mendatang. Semoga laporan ini bermanfaat sebagai acuan operasional dan pengembangan lebih lanjut bagi pengelolaan layanan MPP Kabupaten Rembang.\n
\n
Rembang, ____________________\n
\n
Penyusun\n
\n
---\n
\n
# Laporan Akhir
**Pekerjaan:** Pembuatan Aplikasi Antrian Berbasis Website – MPP Kabupaten Rembang  
**Program:** Pelayanan Penanaman Modal  
**Kegiatan:** Pelayanan Perizinan dan Non‑Perizinan Terpadu Satu Pintu di Bidang Penanaman Modal yang Menjadi Kewenangan Daerah Kabupaten/Kota  
**Satuan Kerja:** Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu (DPMPTSP) Kabupaten Rembang  
**Pelaksana:** CV. Pratama Mukti Consultant, Perumahan Bumi Wana Mukti H3 No.14, Kota Semarang  

---

## BAB I. PENDAHULUAN
### 1.1 Ringkasan Pelaksanaan
Laporan akhir ini merangkum hasil implementasi aplikasi antrian berbasis web untuk MPP Kabupaten Rembang. Sistem dibangun untuk menata alur pelayanan agar tertib, transparan, dan terukur, sekaligus memberikan pengalaman layanan yang lebih baik bagi masyarakat. Implementasi tidak hanya berfokus pada pendaftaran antrian, tetapi juga pada keseluruhan siklus layanan: mulai dari pembentukan nomor antrian, pencatatan status, pemanggilan loket, tampilan display antrian, hingga pencetakan tiket melalui printer thermal.

Pada tahap implementasi, seluruh modul diselaraskan agar bekerja dalam satu alur yang konsisten. Modul front desk dirancang responsif untuk layanan walk‑in, modul admin layanan mendukung pemanggilan dan pengelolaan antrian, serta modul display memastikan informasi panggilan dapat ditampilkan real‑time. Dengan demikian, aplikasi berfungsi sebagai sistem terpadu yang menekan beban administratif dan meningkatkan akurasi pelayanan.

### 1.2 Tujuan Laporan Akhir
Laporan akhir disusun untuk:\n
1. Menjelaskan hasil pengembangan dan implementasi sistem secara menyeluruh.\n
2. Menyajikan arsitektur aplikasi, teknologi yang digunakan, dan komponen utama yang dihasilkan.\n
3. Menguraikan hasil pengujian, tingkat kesiapan operasional, serta capaian fungsional.\n
4. Menyajikan rekomendasi pemeliharaan dan pengembangan lanjutan sesuai kebutuhan layanan MPP.\n

### 1.3 Latar Belakang Teknis
MPP Kabupaten Rembang melayani berbagai instansi yang membutuhkan pengelolaan antrian yang rapi dan terstandardisasi. Sistem manual menimbulkan risiko duplikasi nomor, ketidakkonsistenan status, serta keterlambatan pemanggilan. Aplikasi antrian berbasis web dirancang untuk menjawab kebutuhan tersebut dengan menyediakan mekanisme penomoran otomatis, integrasi data antrian lintas instansi, serta tampilan informasi yang dapat dipantau secara langsung oleh petugas maupun pengelola.

### 1.4 Ruang Lingkup Implementasi
Ruang lingkup implementasi meliputi:\n
1. Pengembangan modul pendaftaran antrian online dan front desk.\n
2. Modul pemanggilan antrian dan pengelolaan status layanan.\n
3. Modul display antrian berbasis web.\n
4. Integrasi printer thermal untuk tiket antrian.\n
5. Manajemen user dan pengaturan instansi/layanan untuk operasional harian.\n

### 1.5 Manfaat Implementasi
Dengan sistem yang terintegrasi, proses pelayanan menjadi lebih efisien, transparan, dan mudah dipantau. Pemohon mendapatkan informasi yang jelas, petugas memiliki kontrol penuh terhadap pemanggilan dan status antrian, sementara pengelola memperoleh data yang dapat digunakan untuk evaluasi dan peningkatan layanan.

---

## BAB II. GAMBARAN SISTEM YANG DIBANGUN
### 2.1 Deskripsi Sistem
Aplikasi antrian MPP merupakan sistem berbasis web yang mengatur proses pelayanan sejak pemohon memilih instansi/layanan hingga antrian diselesaikan di loket. Sistem ini bekerja dengan konsep multi‑instansi sehingga seluruh unit layanan di lingkungan MPP dapat dikelola dalam satu platform terpadu. Setiap layanan dapat memiliki konfigurasi khusus seperti kode huruf, jam operasional, dan aturan penomoran, namun tetap mengikuti alur proses yang sama agar konsisten di seluruh instansi.

Fungsi inti sistem mencakup pembentukan nomor antrian otomatis, pencatatan status antrian (terdaftar, hadir, dipanggil, selesai, batal), serta penyajian informasi real‑time kepada petugas dan display publik. Implementasi ini memastikan konsistensi data, mengurangi kesalahan manual, dan menyediakan jejak layanan yang dapat diaudit.

Selain itu, aplikasi dirancang untuk mendukung kebutuhan operasional harian, seperti pengaturan layanan aktif, pembagian loket, serta pencetakan tiket dengan format standar. Seluruh informasi tersimpan dalam database terpusat sehingga pengelola dapat melakukan monitoring dan evaluasi layanan per instansi maupun keseluruhan MPP.

### 2.2 Cakupan Modul
Sistem dibangun dengan modul‑modul utama yang saling terhubung. Modul masyarakat memfasilitasi pemohon untuk memilih instansi dan layanan, mengambil nomor antrian secara online, serta melihat informasi status antrian. Modul front desk digunakan petugas untuk pendaftaran walk‑in atau bantuan pemohon yang datang langsung, sekaligus memicu pencetakan tiket melalui printer thermal. Modul admin layanan mendukung proses pemanggilan antrian, pembaruan status hadir/selesai/batal, dan pengelolaan layanan harian agar nomor dipanggil sesuai urutan. Modul superadmin menyediakan fungsi manajemen user, instansi, layanan, serta kontrol konfigurasi global dan monitoring agregat. Modul display antrian menampilkan nomor yang sedang dipanggil dan antrian berikutnya secara real‑time, sedangkan printer service menjalankan layanan lokal berbasis Python untuk memastikan tiket tercetak cepat dan konsisten.

Modul‑modul tersebut terintegrasi melalui logika aplikasi dan database terpusat, sehingga perubahan status di satu modul langsung tercermin di modul lainnya. Integrasi ini menjadi kunci agar alur layanan tetap sinkron, mengurangi potensi perbedaan data antara loket, front desk, dan display.

---

## BAB III. ARSITEKTUR APLIKASI
### 3.1 Arsitektur Teknis
Arsitektur aplikasi dirancang menggunakan pendekatan tiga lapis (3‑tier) agar pemisahan tanggung jawab jelas dan mudah dikembangkan. Lapisan presentasi menyediakan antarmuka web untuk masyarakat, front desk, admin layanan, superadmin, serta display antrian. Seluruh antarmuka ini mengakses logika aplikasi melalui request HTTP dan menerima respons dalam format terstruktur agar konsisten di berbagai perangkat.\n
Lapisan aplikasi berisi controller dan service yang menangani logika bisnis inti, seperti pembentukan nomor antrian, validasi status layanan, check‑in online, pemanggilan antrian, dan pembentukan laporan. Di lapisan ini juga dilakukan validasi hak akses berbasis role sehingga setiap pengguna hanya dapat mengakses fitur sesuai kewenangannya. Modul aplikasi mengatur sinkronisasi status antrian agar perubahan yang terjadi di front desk, admin layanan, atau display selalu konsisten.\n
Lapisan data menggunakan database relasional untuk memastikan integritas dan konsistensi data. Setiap transaksi antrian disimpan dengan relasi yang jelas terhadap instansi dan jenis layanan, sehingga audit dan pelaporan dapat dilakukan dengan akurat. Indeks pada kolom tanggal dan layanan diterapkan untuk mempercepat query harian. Dengan struktur ini, performa tetap terjaga meskipun terjadi lonjakan antrian.\n
Secara integrasi perangkat, arsitektur dilengkapi service lokal printer thermal di mini PC. Service ini berkomunikasi dengan lapisan aplikasi melalui endpoint lokal `/print`, sehingga proses cetak tidak membebani server utama dan tetap berjalan walaupun koneksi eksternal tidak stabil. Display antrian juga menarik data dari lapisan aplikasi secara berkala agar informasi panggilan selalu real‑time.\n
Arsitektur ini memungkinkan pengembangan bertahap tanpa mengganggu modul lain, karena setiap lapisan bersifat modular dan dapat diperluas sesuai kebutuhan MPP di masa mendatang.

### 3.2 Alur Proses Utama
Alur proses utama terdiri dari beberapa tahapan yang saling terhubung. Proses dimulai ketika pemohon memilih instansi dan layanan melalui modul masyarakat atau melalui pendaftaran walk‑in di front desk. Sistem memvalidasi bahwa layanan masih aktif dan jadwal layanan tersedia, kemudian membentuk nomor antrian berdasarkan kode huruf dan urutan harian. Nomor tersebut disimpan ke database lengkap dengan status awal **Terdaftar** serta timestamp pendaftaran.

Jika pendaftaran dilakukan di front desk, sistem langsung memicu service printer thermal untuk mencetak tiket fisik. Jika pendaftaran dilakukan online, pemohon dapat melakukan check‑in pada hari kunjungan melalui QR atau check‑in manual di front desk. Setelah check‑in, status antrian diperbarui menjadi **Hadir** agar petugas mengetahui pemohon sudah berada di lokasi. Pada fase ini, front desk juga dapat melakukan koreksi data jika terdapat kesalahan layanan atau pemohon perlu dialihkan ke layanan lain, dengan tetap menjaga jejak perubahan.

Pada tahap pelayanan, petugas loket memanggil antrian melalui panel admin. Sistem menandai status menjadi **Dipanggil** dan menampilkan nomor panggilan pada display antrian secara real‑time. Apabila pemohon tidak merespons dalam batas waktu tertentu, petugas dapat mengubah status menjadi **Batal/Tidak Hadir** sehingga nomor berikutnya dapat dipanggil. Setelah layanan selesai, status diperbarui menjadi **Selesai** disertai timestamp mulai dan selesai layanan untuk keperluan analisis durasi.

Sepanjang alur ini, sistem juga menghasilkan data monitoring: jumlah antrian terdaftar, jumlah yang hadir, yang dipanggil, yang selesai, dan yang batal. Data tersebut langsung terakumulasi pada laporan harian dan rekap instansi. Dengan alur ini, setiap modul selalu membaca sumber data yang sama, sehingga informasi yang ditampilkan pada front desk, admin, dan display konsisten dan terhindar dari perbedaan data.

---

## BAB IV. TEKNOLOGI DAN BAHASA PEMROGRAMAN
### 4.1 Teknologi yang Digunakan
Pengembangan sistem menggunakan kombinasi teknologi yang sudah teruji dalam implementasi layanan publik dan sesuai dengan lingkungan infrastruktur yang tersedia. Pada sisi backend, aplikasi dibangun menggunakan PHP dengan pola MVC untuk menjaga struktur kode tetap rapi, terpisah antara logika bisnis, data, dan tampilan. Pola ini memudahkan pengembangan modul baru serta perawatan ketika ada perubahan kebijakan layanan. Backend juga menangani autentikasi berbasis role dan proses transaksi yang membutuhkan konsistensi data.

Pada sisi frontend, antarmuka pengguna dibangun dengan HTML, CSS, dan JavaScript agar kompatibel di berbagai perangkat. Komponen UI berbasis Bootstrap dipilih untuk memastikan tampilan responsif, khususnya pada layar kiosk front desk dan perangkat mobile masyarakat. JavaScript digunakan untuk interaksi dinamis seperti pengisian layanan berdasarkan instansi, refresh status antrian, dan validasi form tanpa perlu reload penuh.

Database menggunakan MySQL/MariaDB karena stabil, memiliki dukungan indeks yang baik untuk query harian, serta mudah diintegrasikan dengan PHP. Data antrian, instansi, layanan, dan user disimpan secara relasional sehingga mudah diaudit dan dilaporkan. 

Integrasi printer thermal dilakukan menggunakan service lokal berbasis Python. Flask digunakan sebagai web service ringan di mini PC, sedangkan python‑escpos dan pyusb menyediakan kemampuan komunikasi langsung dengan perangkat printer USB melalui perintah ESC/POS. Pendekatan ini menjamin proses cetak tetap berjalan cepat dan tidak bergantung pada browser, sehingga stabil untuk operasional front desk.

### 4.2 Alasan Pemilihan Teknologi
Pemilihan PHP dan MySQL/MariaDB didasarkan pada kestabilan, kemudahan deploy, serta kompatibilitas dengan infrastruktur server yang umum digunakan di lingkungan pemerintahan. Stack ini juga didukung dokumentasi luas dan ketersediaan SDM yang terbiasa dengan teknologi tersebut, sehingga pemeliharaan jangka panjang lebih mudah dilakukan.

JavaScript dan Bootstrap dipilih untuk memastikan interaksi pengguna cepat dan tampilan konsisten pada modul masyarakat, front desk, admin, dan display. Komponen UI yang responsif sangat penting karena aplikasi digunakan pada berbagai ukuran layar, termasuk perangkat mobile dan kiosk layanan.

Python digunakan pada printer service karena ketersediaan library ESC/POS yang matang serta kemampuan mengelola koneksi USB printer secara lebih fleksibel dan stabil dibandingkan pendekatan berbasis browser. Dengan service lokal, proses cetak tidak bergantung pada jaringan eksternal dan tetap dapat berjalan walaupun koneksi server mengalami gangguan sementara.

---

## BAB V. IMPLEMENTASI MODUL
### 5.1 Modul Masyarakat
Modul masyarakat merupakan pintu utama pemohon dalam mengambil nomor antrian secara mandiri. Pada sisi aplikasi, modul ini dibangun sebagai antarmuka web yang menampilkan daftar instansi dan layanan aktif, kemudian memproses permintaan pengambilan nomor melalui endpoint backend yang sudah divalidasi. Logika bisnis pada sisi server memastikan bahwa nomor antrian dibentuk secara konsisten berdasarkan kode layanan/instansi, urutan harian, serta tanggal pelayanan, sehingga tidak terjadi duplikasi.

Dari sisi alur, pemohon memulai dengan memilih instansi, kemudian sistem menampilkan layanan yang tersedia di instansi tersebut. Setelah layanan dipilih, permintaan dikirim ke backend untuk pembuatan nomor antrian. Backend mencatat data antrian ke database dan mengembalikan nomor antrian beserta informasi ringkas layanan. Informasi ini ditampilkan kembali di layar masyarakat, dilengkapi ringkasan posisi antrian atau estimasi waktu agar pemohon dapat memperkirakan kapan dipanggil.

Secara skema data, modul ini berinteraksi dengan entitas `instansi`, `jenis_layanan`, dan `antrian`. Relasi data memastikan bahwa setiap nomor yang terbentuk terhubung jelas dengan layanan serta instansi yang dipilih. Selain itu, validasi input dilakukan untuk mencegah pemilihan layanan tidak aktif atau instansi yang sedang tutup.

Menu yang disediakan pada modul masyarakat dijelaskan secara rinci berdasarkan tampilan dashboard mobile sebagai berikut:
1. **Beranda** – Halaman ringkasan yang menampilkan identitas akun, sapaan pengguna, dan tombol utama untuk pendaftaran antrian.
2. **Daftar** – Akses langsung ke proses pendaftaran/ambil nomor antrian.
3. **Antrian** – Menampilkan status antrian aktif (jika ada) beserta informasi ringkas posisi antrian.
4. **Riwayat** – Ringkasan jumlah antrian yang pernah diambil dan akses ke detail riwayat.
5. **Pesan ke CS** – Indikator jumlah pesan dan status balasan dari Customer Service.
6. **Aksi Cepat** – Sekumpulan tombol cepat seperti `Daftar`, `Antrian Saya`, `Riwayat`, dan `Chat CS` untuk mempercepat navigasi layanan.

Pada halaman **Daftar Antrian** (sesuai tampilan screenshot), modul masyarakat menyediakan formulir pendaftaran yang lebih terstruktur. Pengguna terlebih dahulu memilih **Instansi** melalui dropdown yang hanya menampilkan instansi aktif. Setelah instansi dipilih, pilihan **Jenis Layanan** akan terisi secara dinamis berdasarkan instansi tersebut sehingga pemohon tidak salah memilih layanan. Sistem juga menyediakan **Tanggal Kunjungan** agar pemohon dapat menentukan jadwal kedatangan; pada jam tertentu (contoh pukul 15:30) pendaftaran otomatis dialihkan ke hari berikutnya untuk menghindari overload di hari berjalan. 

Tombol **Daftar Antrian** menjadi aksi utama yang mengirim data formulir ke backend. Pada sisi server, data diverifikasi (instansi/layanan valid, tanggal sesuai aturan, dan kuota tersedia jika diterapkan), lalu sistem menghasilkan nomor antrian. Hasil pendaftaran ditampilkan kembali kepada pemohon dalam bentuk ringkasan nomor, nama layanan, dan instruksi singkat. Dengan alur ini, pendaftaran tetap terkontrol dan data antrian masuk secara konsisten ke database.

Pada tahap lanjutan, modul ini dapat diperluas dengan fitur tambahan seperti QR code verifikasi untuk pengecekan status di loket/display, notifikasi otomatis saat antrian mendekati giliran, dan halaman detail persyaratan layanan agar pemohon dapat menyiapkan dokumen sebelum datang ke loket.

Halaman **Riwayat Antrian** (sesuai screenshot) menampilkan daftar transaksi antrian yang pernah diambil oleh pemohon. Setiap kartu riwayat berisi nomor antrian, nama layanan, tanggal pengambilan, serta badge status seperti **Terdaftar**, **Dipanggil**, atau **Selesai**. Informasi ini membantu pemohon memantau progres layanan dan membedakan antrian yang masih aktif dengan yang sudah selesai. Pada antrian dengan status **Terdaftar** atau **Dipanggil**, sistem menyediakan aksi **Batalkan Antrian** sebagai kontrol mandiri apabila pemohon tidak dapat hadir. Ketika antrian telah berstatus **Selesai**, tombol aksi dinonaktifkan dengan keterangan “Tidak ada aksi tersedia” untuk menjaga konsistensi status data. 

Secara alur, data riwayat ditarik dari tabel antrian berdasarkan identitas akun pemohon, disusun dari yang terbaru, dan ditampilkan sebagai daftar kartu. Ketika pengguna menekan **Batalkan Antrian**, sistem melakukan validasi status (hanya boleh dibatalkan jika belum selesai) lalu memperbarui status di database dan menyegarkan tampilan riwayat. Dengan demikian, halaman riwayat berfungsi sebagai audit personal bagi pemohon sekaligus mekanisme pengendalian antrian agar kuota layanan tidak terbuang.

Halaman **Antrian Saya** (sesuai screenshot) menampilkan daftar antrian aktif yang masih berjalan untuk akun pemohon. Setiap kartu antrian memuat nomor antrian, nama instansi, nama layanan, tanggal kunjungan, serta status check‑in seperti **Belum Check‑In** atau **Sudah Check‑In**. Pada status **Belum Check‑In**, sistem menampilkan tombol **Scan QR Check‑In** yang memungkinkan pemohon melakukan check‑in di lokasi layanan (misalnya melalui QR di loket atau display). Setelah check‑in berhasil, status berubah menjadi **Sudah Check‑In** dan tombol aksi digantikan dengan informasi bahwa check‑in telah dilakukan.

Secara proses, data antrian aktif diambil dari database berdasarkan akun pemohon dengan filter status yang masih berjalan. Ketika pengguna melakukan scan QR, sistem memvalidasi kode QR, memastikan antrian masih berlaku, lalu memperbarui field check‑in pada data antrian. Pembaruan ini memastikan petugas loket dapat membedakan pemohon yang sudah hadir dan yang belum, sehingga proses pemanggilan dapat lebih terkontrol dan antrian lebih tertib.

### 5.2 Modul Front Desk
Modul front desk dirancang untuk menangani dua skenario utama: pendaftaran **walk‑in** dan **check‑in online**. Antarmuka dibangun dalam bentuk tab agar petugas dapat berpindah konteks dengan cepat tanpa meninggalkan halaman. Desainnya fokus pada kecepatan operasional dan minim input manual, karena layanan front desk biasanya memiliki trafik tinggi.

Pada tab **Walk‑In**, petugas disajikan daftar kartu instansi berbentuk grid. Setiap kartu mewakili instansi aktif dan dapat disentuh untuk memunculkan daftar layanan. Setelah instansi dipilih, sistem membuka dialog **Pilih Layanan** yang menampilkan daftar layanan di instansi tersebut. Ketika petugas memilih layanan, sistem secara otomatis membentuk nomor antrian, menyimpan data ke database, dan mengirim perintah cetak ke printer thermal. Dengan alur ini, proses pendaftaran cukup dilakukan dengan dua langkah: pilih instansi dan pilih layanan, sehingga waktu layanan di loket menjadi lebih singkat.

Pada tab **Check‑In Online**, front desk melayani pemohon yang sebelumnya sudah mengambil nomor secara online. Halaman ini menyediakan area **QR Check‑In** yang digunakan untuk memindai QR pemohon. Sistem memvalidasi QR tersebut, mengecek status antrian, lalu memperbarui status ke **Hadir/Check‑In**. Sebagai alternatif, tersedia tombol **Check‑In Manual** apabila pemohon tidak membawa QR atau terjadi kendala pemindaian. Mode manual membuka form pencarian nomor/antrian agar petugas tetap dapat melakukan check‑in tanpa menghambat proses.

Dengan pemisahan dua tab ini, petugas dapat menangani dua alur layanan (walk‑in dan online) secara bersamaan tetapi tetap rapi. Semua aksi pada front desk tercatat di database sehingga data antrian selalu konsisten dengan modul admin layanan dan display.

### 5.3 Modul Admin Layanan
Modul admin layanan adalah pusat operasional bagi petugas instansi. Dari sidebar, petugas mengakses menu **Antrian Hari Ini**, **Riwayat Antrian**, **Kelola Layanan**, serta **Laporan**. Pada halaman riwayat/antrian, tersedia filter tanggal, pencarian nama/nomor, serta pengaturan jumlah baris untuk memudahkan penelusuran. Tabel menampilkan nomor, nama pemohon, layanan, serta badge status seperti **Terdaftar**, **Dipanggil**, **Selesai**, atau **Batal**. Petugas dapat memperbarui status melalui dropdown **Ubah Status** sehingga perubahan langsung tersinkron ke display dan laporan.

Menu **Kelola Layanan** digunakan untuk membuka/menutup status layanan instansi. Ketika layanan ditutup, sistem akan membatalkan antrian aktif sesuai aturan operasional sehingga tidak terjadi penumpukan. Menu **Laporan** menyediakan rekap per hari, detail antrian, hasil layanan, serta waktu layanan. Setiap laporan dilengkapi filter rentang tanggal, filter layanan, jumlah baris tampil, serta tombol **Download Excel** untuk kebutuhan pelaporan administrasi.

Pada sisi proses, setiap aksi status (dipanggil, selesai, batal) tercatat ke database dengan timestamp. Hal ini memungkinkan sistem menghasilkan metrik layanan seperti jumlah pemohon datang, tidak datang, serta durasi layanan per petugas. Dengan demikian, modul admin layanan tidak hanya berfungsi sebagai panel pemanggilan, tetapi juga alat monitoring kinerja operasional instansi secara menyeluruh.

### 5.4 Modul Superadmin
Superadmin berperan sebagai pengelola utama dengan akses penuh atas konfigurasi sistem, operasional antrian, serta pelaporan lintas instansi. Menu pada sidebar superadmin dirancang untuk memisahkan aktivitas operasional harian, pengelolaan data master, dan pelaporan statistik. Dengan pemisahan ini, pengelola dapat melakukan kontrol menyeluruh tanpa mengganggu proses layanan di loket.

Uraian menu dan sub‑menu Superadmin disusun secara terstruktur sebagai berikut.

Pertama, **Dashboard** berfungsi sebagai ringkasan operasional. Halaman ini menampilkan statistik utama seperti jumlah instansi aktif, jumlah jenis layanan, jumlah admin layanan, serta ringkasan antrian harian. Dengan ringkasan tersebut, superadmin dapat memantau beban layanan tanpa harus membuka laporan detail. Dashboard juga berperan sebagai titik awal untuk memeriksa kesehatan operasional harian.

Kedua, **Operasional Antrian** berisi sub‑menu yang terkait langsung dengan layanan lapangan. Sub‑menu **Monitor Display** digunakan untuk menampilkan nomor antrian yang sedang dipanggil dan nomor berikutnya di layar publik. Sub‑menu **Front Desk** menyediakan akses ke mode front desk yang berbeda (Manual, V2, dan Tab) agar dapat disesuaikan dengan kebutuhan operasional dan tata ruang layanan. Pemilihan mode ini memengaruhi cara instansi ditampilkan, pola navigasi, serta kecepatan pendaftaran.

Ketiga, **Manajemen Sistem** merupakan pusat pengelolaan data master dan konfigurasi. Di dalamnya terdapat sub‑menu berikut:\n
1. **Manajemen User** – Mengelola akun admin, CS, dan operator, termasuk pengaturan peran/role dan status aktif.\n
2. **Verifikasi Akun** – Memvalidasi akun pemohon sebelum dapat menggunakan layanan tertentu.\n
3. **Instansi** – Mengelola daftar instansi, data profil, serta status layanan (aktif/tutup).\n
4. **Jenis Layanan** – Mengatur layanan per instansi, kode huruf antrian, serta deskripsi layanan.\n
5. **Kelola Layanan** – Mengatur buka/tutup layanan secara massal atau per instansi, termasuk tindakan otomatis terhadap antrian aktif.\n
6. **Pengaturan Sektor** – Mengelompokkan instansi ke dalam sektor untuk kebutuhan display dan pelaporan.\n
7. **Pengaturan Video** – Mengatur konten video/tampilan pendukung pada layar display.\n
8. **Pengaturan Speed Suara** – Mengatur kecepatan audio panggilan untuk kenyamanan pemohon.\n

Keempat, **Panduan Printer** menyediakan dokumentasi teknis terkait konfigurasi printer thermal di front desk. Sub‑menu ini memuat langkah instalasi service cetak lokal, format cetak tiket, serta panduan troubleshooting agar operasional tidak terganggu jika terjadi kendala perangkat.

Kelima, **Laporan** adalah pusat pelaporan statistik dan evaluasi. Sub‑menu di dalamnya meliputi:\n
1. **Rekap Per Hari** – Ringkasan jumlah antrian harian per instansi dan layanan.\n
2. **Detail Antrian** – Daftar transaksi antrian per pemohon lengkap dengan status dan waktu.\n
3. **Hasil Layanan** – Klasifikasi output layanan (selesai/batal/dipanggil) untuk evaluasi.\n
4. **Waktu Layanan** – Analisis durasi pelayanan per petugas untuk melihat beban kerja.\n
Setiap laporan menyediakan filter rentang tanggal, filter instansi/layanan, serta tombol unduh Excel sehingga laporan dapat langsung digunakan untuk pelaporan administrasi.

Melalui susunan menu ini, superadmin memiliki kontrol end‑to‑end terhadap operasional antrian: mengelola data master, mengatur layanan di lapangan, memantau display, serta menyusun laporan evaluasi lintas instansi.

### 5.5 Modul Display Antrian
Modul Display Antrian berfungsi sebagai media informasi utama di ruang tunggu yang menampilkan nomor antrian yang sedang dipanggil, loket tujuan, serta daftar antrian berikutnya. Tampilan ini dirancang untuk bersifat **real‑time** sehingga setiap perubahan status di panel admin layanan langsung tercermin pada display. Hal ini mencegah perbedaan informasi antara petugas dan pemohon, serta menjaga keteraturan alur pelayanan.

Secara operasional, display menarik data panggilan terbaru dari server aplikasi dan menampilkan prioritas antrian yang sedang dipanggil. Di samping itu, display juga dapat menampilkan informasi tambahan seperti nama instansi, jenis layanan, serta status layanan (buka/tutup). Dengan model ini, pemohon dapat memahami konteks layanan dan tidak hanya melihat nomor antrian semata.

Modul ini terintegrasi dengan **pengaturan sektor** untuk kebutuhan segmentasi ruang tunggu. Instansi dapat dikelompokkan ke sektor tertentu sehingga display dapat menampilkan antrian sesuai lokasi layanan. Selain itu, modul display dapat digabungkan dengan **konten video** atau informasi publik yang diatur oleh superadmin, sehingga layar tidak hanya menampilkan nomor panggilan tetapi juga menjadi media edukasi dan informasi layanan.

Dari sisi teknis, display bekerja dengan polling atau mekanisme pembaruan berkala untuk memastikan data selalu terbaru. Ketika status antrian berubah menjadi **Dipanggil** atau **Selesai**, modul ini langsung memperbarui daftar panggilan tanpa perlu refresh manual. Dengan demikian, display berfungsi sebagai “papan informasi digital” yang konsisten dan dapat dipercaya oleh pemohon.

### 5.6 Integrasi Printer Thermal
Integrasi printer thermal dilakukan melalui mini PC yang menjalankan service Python lokal. Service ini menerima payload JSON dari aplikasi melalui endpoint khusus (`/print`) dan mengeksekusi perintah ESC/POS untuk menghasilkan tiket dengan format standar. Pendekatan ini dipilih agar pencetakan tidak bergantung pada browser dan tetap stabil meskipun jaringan utama mengalami latensi. Service dijalankan sebagai daemon (systemd) sehingga otomatis aktif saat perangkat menyala.\n
Secara teknis, lingkungan di mini PC membutuhkan dependensi OS dan library Python. Paket yang diinstal meliputi `python3-venv`, `python3-pip`, `libusb-1.0-0`, dan `usbutils` untuk memastikan USB printer dapat terdeteksi dengan baik. Di sisi Python, digunakan `Flask` sebagai server HTTP lokal, `python-escpos` untuk perintah ESC/POS, dan `pyusb` untuk akses langsung ke perangkat USB. Konfigurasi akses USB dilakukan melalui rule udev agar service dapat membaca perangkat printer tanpa konflik driver.\n
Konfigurasi service dilakukan dengan menempatkan script cetak pada direktori layanan, kemudian membuat unit systemd yang memanggil interpreter Python di virtual environment. Parameter VID/PID printer dikonfigurasikan di script (atau environment) agar service dapat mengikat perangkat yang tepat. Saat menerima permintaan cetak, service memvalidasi payload, membentuk layout tiket, melakukan print, lalu menutup koneksi USB agar perangkat tidak terkunci.\n
Dengan konfigurasi ini, alur cetak menjadi deterministik: aplikasi web mengirimkan data antrian → service lokal memproses → printer thermal mencetak → status cetak dikembalikan ke aplikasi. Integrasi ini memastikan front desk dapat mencetak tiket secara otomatis tanpa intervensi manual, dengan performa yang konsisten di lingkungan layanan.

---

## BAB VI. DATABASE DAN LOGIKA PENOMORAN
### 6.1 Struktur Data
Struktur data dirancang untuk merepresentasikan instansi, layanan, user, dan antrian secara relasional agar konsisten dan mudah diaudit. Tabel **instansi** menyimpan profil unit layanan (nama, sektor, status aktif/tutup), sedangkan tabel **jenis_layanan** menyimpan daftar layanan per instansi, termasuk kode huruf antrian, nama layanan, dan status aktif. Tabel **antrian** menyimpan transaksi pengambilan nomor dan status proses, dengan kolom kunci seperti `layanan_id`, `nomor_antrian`, `tanggal`, `status`, `hadir` (check‑in), serta timestamp `created_at`/`updated_at`. Tabel **users** menyimpan akun petugas dan admin dengan role akses (superadmin, admin layanan, CS).\n
Relasi inti yang digunakan adalah: `instansi (1) -> (n) jenis_layanan` dan `jenis_layanan (1) -> (n) antrian`. Relasi ini memastikan bahwa setiap nomor antrian selalu terikat pada satu layanan dan instansi tertentu. Indeks diterapkan pada kolom `tanggal`, `layanan_id`, dan `nomor_antrian` untuk mempercepat query ketika menampilkan antrian harian, memanggil antrian aktif, atau membuat laporan rekap.\n
Struktur ini juga memungkinkan segmentasi data per instansi atau per layanan dengan aman, sehingga admin layanan hanya melihat data yang menjadi kewenangannya, sementara superadmin dapat mengakses seluruh data lintas instansi.

### 6.2 Penomoran Antrian
Penomoran antrian mengikuti format `KODE_HURUF + NOMOR_URUT` (misalnya A001, A002) dengan reset harian berdasarkan tanggal layanan. Proses penomoran dilakukan dengan langkah teknis sebagai berikut:\n
1. Sistem mengambil `kode_huruf` dari tabel **jenis_layanan** sesuai layanan yang dipilih.\n
2. Sistem menghitung jumlah antrian pada tanggal yang sama untuk instansi/layanan terkait, lalu menambahkan 1 sebagai nomor urut berikutnya.\n
3. Nomor urut diformat dengan padding tiga digit (`001`, `002`, dst) dan digabung dengan `kode_huruf`.\n
4. Nomor hasil dibungkus dalam transaksi database untuk mencegah konflik saat pendaftaran simultan.\n
\n
Untuk menghindari duplikasi pada kondisi beban tinggi, query penomoran dijalankan dalam transaksi (transaction) dengan kunci yang konsisten pada `layanan_id` dan `tanggal`. Dengan demikian, walaupun ada dua permintaan bersamaan, sistem tetap menghasilkan nomor unik dan terurut. Logika ini juga memungkinkan penghitungan lintas instansi jika dibutuhkan, misalnya ketika penomoran diatur berdasarkan instansi bukan per layanan.\n
Skema ini memastikan nomor antrian mudah dipahami oleh pemohon, konsisten di layar display, dan mudah dilacak kembali pada laporan harian maupun rekap bulanan.

---

## BAB VII. TAHAPAN PENGENDALIAN MUTU DAN OPERASIONAL
Bab ini menjelaskan tahapan pengendalian mutu dan operasional pasca‑pengembangan, meliputi pengujian sistem, implementasi lapangan, pelatihan pengguna, serta strategi pemeliharaan. Tujuannya adalah memastikan aplikasi siap digunakan, stabil saat operasional, dan dapat dikembangkan secara berkelanjutan sesuai kebutuhan layanan MPP.

### 7.1 Pengujian dan Validasi
Pengujian dilakukan secara bertahap mulai dari uji fungsional, uji integrasi, hingga validasi operasional. Uji fungsional menilai seluruh alur utama seperti pengambilan nomor antrian (online dan walk‑in), pembentukan nomor, pencetakan tiket, pemanggilan antrian, pembaruan status, serta sinkronisasi display. Setiap fungsi diuji dengan data normal dan data batas (edge cases) agar perilaku sistem dapat dipastikan konsisten. 

Uji integrasi memfokuskan pada interaksi antar modul: front desk harus dapat memicu cetak melalui service lokal, status yang diubah pada panel admin harus langsung tampil pada display, dan laporan harus merefleksikan perubahan status terbaru. Validasi operasional dilakukan melalui simulasi layanan harian yang menyerupai kondisi nyata, termasuk jam padat, perubahan status cepat, dan skenario antrian batal/tidak hadir. Dengan pendekatan ini, kualitas sistem tidak hanya diuji pada level fungsi, tetapi juga pada kestabilan operasional di lapangan.

### 7.2 Implementasi dan Pelatihan
Implementasi dilakukan secara bertahap dengan uji coba lapangan di lingkungan MPP. Pada fase awal, sistem dijalankan paralel dengan prosedur manual sebagai mekanisme mitigasi agar layanan tetap berjalan apabila terjadi kendala teknis. Setelah stabil, proses manual secara bertahap dihentikan dan seluruh transaksi dialihkan ke sistem. 

Pelatihan diberikan kepada admin, petugas loket, dan front desk dengan fokus pada alur kerja yang berbeda. Admin mendapatkan materi pengelolaan instansi, layanan, serta laporan. Petugas loket dilatih pada proses pemanggilan, pembaruan status, serta penanganan antrian batal/tidak hadir. Front desk dilatih pada pendaftaran walk‑in, cetak tiket, serta check‑in online. Dokumentasi singkat, panduan visual, dan video tutorial disiapkan agar petugas memiliki referensi cepat saat bertugas.

### 7.3 Pemeliharaan dan Pengembangan
Pemeliharaan mencakup backup data berkala, monitoring performa server dan database, serta pembaruan keamanan aplikasi. Aktivitas backup dilakukan terjadwal agar data antrian dan laporan tidak hilang ketika terjadi gangguan. Monitoring log aplikasi dan service printer thermal dilakukan untuk mendeteksi error sejak dini, sehingga troubleshooting dapat dilakukan sebelum mengganggu layanan. 

Pengembangan lanjutan yang direkomendasikan meliputi notifikasi WhatsApp bagi pemohon, statistik layanan lanjutan per instansi, serta modul rekap laporan PDF/Excel terjadwal. Selain itu, penguatan modul audit dan logging dapat ditambahkan untuk meningkatkan akuntabilitas layanan. Dengan skema pemeliharaan dan pengembangan ini, sistem tidak hanya stabil untuk operasional harian, tetapi juga siap berkembang sesuai dinamika kebutuhan MPP.

---

## BAB X. PENUTUP
Laporan akhir ini menegaskan bahwa aplikasi antrian berbasis web untuk MPP Kabupaten Rembang telah diimplementasikan dengan modul inti yang lengkap, terintegrasi, dan siap digunakan secara operasional. Sistem dibangun dengan pendekatan modular agar setiap instansi dapat menjalankan layanan secara konsisten dalam satu platform terpadu. Integrasi front desk, admin layanan, display antrian, serta printer thermal memastikan proses antrian berjalan dari awal hingga akhir tanpa ketergantungan prosedur manual.\n
Dari sisi operasional, aplikasi memberikan dampak nyata terhadap efisiensi layanan: waktu pendaftaran lebih singkat, pemanggilan lebih tertib, serta data layanan terdokumentasi dengan baik. Bagi pengelola, sistem menyediakan sumber data yang dapat digunakan untuk evaluasi kinerja, perencanaan kapasitas layanan, dan pengambilan keputusan berbasis data. Hal ini sejalan dengan kebutuhan peningkatan kualitas pelayanan publik yang terukur.\n
Ke depan, sistem ini tetap membuka ruang pengembangan sesuai dinamika kebutuhan MPP, seperti integrasi notifikasi digital, peningkatan analitik layanan, maupun perluasan modul pelaporan. Dengan fondasi yang telah dibangun, sistem antrian ini diharapkan menjadi bagian penting dari tata kelola layanan publik di MPP Kabupaten Rembang yang lebih modern, transparan, dan berkelanjutan.

---

**Lokasi Dokumen:** `ptsp/readme/LAPORAN_AKHIR.md`
