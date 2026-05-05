-- =========================================================
-- MIGRASI OPERASIONAL ANTRIAN
-- Tanggal : 2026-04-22
-- Tujuan  : Menambahkan pengaturan jam operasional pada tabel instansi
-- Catatan : Jalankan script ini setelah memilih database aplikasi PTSP
--           Script ini aman dijalankan ulang.
-- =========================================================

START TRANSACTION;

SET @current_db = DATABASE();

SET @sql = IF(
    (
        SELECT COUNT(*)
        FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = @current_db
          AND TABLE_NAME = 'instansi'
          AND COLUMN_NAME = 'jam_tutup_pendaftaran'
    ) = 0,
    "ALTER TABLE instansi ADD COLUMN jam_tutup_pendaftaran TIME NOT NULL DEFAULT '15:30:00' AFTER status_layanan",
    "SELECT 'Column jam_tutup_pendaftaran already exists' AS info"
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
    (
        SELECT COUNT(*)
        FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = @current_db
          AND TABLE_NAME = 'instansi'
          AND COLUMN_NAME = 'jam_layanan_mulai'
    ) = 0,
    "ALTER TABLE instansi ADD COLUMN jam_layanan_mulai TIME NOT NULL DEFAULT '08:30:00' AFTER jam_tutup_pendaftaran",
    "SELECT 'Column jam_layanan_mulai already exists' AS info"
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
    (
        SELECT COUNT(*)
        FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = @current_db
          AND TABLE_NAME = 'instansi'
          AND COLUMN_NAME = 'jam_layanan_selesai'
    ) = 0,
    "ALTER TABLE instansi ADD COLUMN jam_layanan_selesai TIME NOT NULL DEFAULT '16:00:00' AFTER jam_layanan_mulai",
    "SELECT 'Column jam_layanan_selesai already exists' AS info"
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
    (
        SELECT COUNT(*)
        FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = @current_db
          AND TABLE_NAME = 'instansi'
          AND COLUMN_NAME = 'jam_tutup_kantor'
    ) = 0,
    "ALTER TABLE instansi ADD COLUMN jam_tutup_kantor TIME NOT NULL DEFAULT '16:30:00' AFTER jam_layanan_selesai",
    "SELECT 'Column jam_tutup_kantor already exists' AS info"
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
    (
        SELECT COUNT(*)
        FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = @current_db
          AND TABLE_NAME = 'instansi'
          AND COLUMN_NAME = 'status_layanan_mode'
    ) = 0,
    "ALTER TABLE instansi ADD COLUMN status_layanan_mode ENUM('otomatis','buka','tutup') NOT NULL DEFAULT 'otomatis' AFTER jam_tutup_kantor",
    "SELECT 'Column status_layanan_mode already exists' AS info"
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

UPDATE instansi
SET
    jam_tutup_pendaftaran = COALESCE(jam_tutup_pendaftaran, '15:30:00'),
    jam_layanan_mulai = COALESCE(jam_layanan_mulai, '08:30:00'),
    jam_layanan_selesai = COALESCE(jam_layanan_selesai, '16:00:00'),
    jam_tutup_kantor = COALESCE(jam_tutup_kantor, '16:30:00'),
    status_layanan_mode = COALESCE(NULLIF(status_layanan_mode, ''), 'otomatis');

COMMIT;

-- Setelah script ini dijalankan:
-- 1. aplikasi akan otomatis menyinkronkan status layanan berdasarkan mode dan jam
-- 2. antrian aktif dari hari sebelumnya akan dibatalkan saat request berikutnya masuk
-- 3. sisa antrian aktif hari berjalan akan dibatalkan saat request setelah jam tutup kantor
