-- =========================================================
-- MIGRASI PENGATURAN EMAIL VERIFIKASI
-- Tanggal : 2026-04-22
-- Tujuan  : Menambahkan tabel pengaturan email dan cooldown resend
-- Catatan : Jalankan script ini setelah memilih database aplikasi PTSP
--           Script ini aman dijalankan ulang.
-- =========================================================

START TRANSACTION;

CREATE TABLE IF NOT EXISTS email_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    protocol VARCHAR(20) NOT NULL DEFAULT 'smtp',
    smtp_host VARCHAR(150) NOT NULL DEFAULT 'smtp.gmail.com',
    smtp_port INT NOT NULL DEFAULT 465,
    smtp_crypto VARCHAR(10) NOT NULL DEFAULT 'ssl',
    smtp_user VARCHAR(150) NULL,
    smtp_pass VARCHAR(255) NULL,
    from_email VARCHAR(150) NULL,
    from_name VARCHAR(150) NOT NULL DEFAULT 'Portal MPP Rembang',
    reply_to_email VARCHAR(150) NULL,
    mailtype VARCHAR(20) NOT NULL DEFAULT 'html',
    charset_name VARCHAR(20) NOT NULL DEFAULT 'utf-8',
    resend_cooldown_minutes INT NOT NULL DEFAULT 5,
    verification_subject VARCHAR(255) NOT NULL DEFAULT 'Verifikasi Akun {app_name}',
    verification_message MEDIUMTEXT NOT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
);

SET @current_db = DATABASE();

SET @sql = IF(
    (
        SELECT COUNT(*)
        FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = @current_db
          AND TABLE_NAME = 'users'
          AND COLUMN_NAME = 'verification_sent_at'
    ) = 0,
    "ALTER TABLE users ADD COLUMN verification_sent_at DATETIME NULL AFTER verify_token",
    "SELECT 'Column verification_sent_at already exists' AS info"
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

INSERT INTO email_settings (
    protocol,
    smtp_host,
    smtp_port,
    smtp_crypto,
    smtp_user,
    smtp_pass,
    from_email,
    from_name,
    reply_to_email,
    mailtype,
    charset_name,
    resend_cooldown_minutes,
    verification_subject,
    verification_message,
    created_at,
    updated_at
)
SELECT
    'smtp',
    'smtp.gmail.com',
    465,
    'ssl',
    '',
    '',
    '',
    'Portal MPP Rembang',
    '',
    'html',
    'utf-8',
    5,
    'Verifikasi Akun {app_name}',
    '<p>Halo {nama_lengkap},</p><p>Terima kasih sudah mendaftar di <strong>{app_name}</strong>. Untuk mengaktifkan akun Anda, silakan klik tombol verifikasi di bawah ini.</p><p><a href="{verification_link}" style="display:inline-block;padding:12px 20px;border-radius:10px;background:#1f5eff;color:#ffffff;text-decoration:none;font-weight:700;">Verifikasi Akun Saya</a></p><p>Jika tombol tidak bisa diklik, salin tautan berikut ke browser Anda:</p><p>{verification_link}</p><p>Email ini dikirim ke alamat <strong>{email}</strong>. Jika Anda tidak merasa mendaftar, abaikan email ini.</p><p>Salam,<br>{from_name}</p>',
    NOW(),
    NOW()
WHERE NOT EXISTS (
    SELECT 1 FROM email_settings
);

COMMIT;
