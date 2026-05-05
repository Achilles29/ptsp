-- =========================================================
-- SKEMA SEKTOR DISPLAY ANTRIAN
-- =========================================================

CREATE TABLE IF NOT EXISTS sektor_display (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kode_sektor VARCHAR(30) NOT NULL UNIQUE,
  nama_sektor VARCHAR(100) NOT NULL,
  slug VARCHAR(120) NOT NULL UNIQUE,
  lokasi_display VARCHAR(150) NULL,
  is_aktif TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NULL,
  updated_at DATETIME NULL
);

ALTER TABLE instansi
  ADD COLUMN IF NOT EXISTS sektor_id INT NULL AFTER nama_instansi;

CREATE INDEX idx_instansi_sektor ON instansi (sektor_id);

-- DEFAULT SEKTOR
INSERT INTO sektor_display (kode_sektor, nama_sektor, slug, lokasi_display, is_aktif, created_at, updated_at)
SELECT 'UMUM', 'Sektor Umum', 'sektor-umum', 'Display Utama', 1, NOW(), NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM sektor_display WHERE slug = 'sektor-umum'
);

-- BACKFILL INSTANSI LAMA
UPDATE instansi
SET sektor_id = (SELECT id FROM sektor_display WHERE slug = 'sektor-umum' LIMIT 1)
WHERE sektor_id IS NULL;
