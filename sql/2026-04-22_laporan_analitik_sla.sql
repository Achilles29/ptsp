SET @db_name := DATABASE();

SET @has_target := (
  SELECT COUNT(*)
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = @db_name
    AND TABLE_NAME = 'jenis_layanan'
    AND COLUMN_NAME = 'target_durasi_menit'
);

SET @sql_add_target := IF(
  @has_target = 0,
  'ALTER TABLE `jenis_layanan` ADD COLUMN `target_durasi_menit` INT NULL DEFAULT 30 AFTER `deskripsi`',
  'SELECT 1'
);
PREPARE stmt_add_target FROM @sql_add_target;
EXECUTE stmt_add_target;
DEALLOCATE PREPARE stmt_add_target;

UPDATE `jenis_layanan`
SET `target_durasi_menit` = 30
WHERE `target_durasi_menit` IS NULL OR `target_durasi_menit` < 1;
