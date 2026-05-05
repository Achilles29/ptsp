<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Operasional_model extends CI_Model
{
    private $default_jam_tutup_pendaftaran = '15:30:00';
    private $default_jam_layanan_mulai = '08:30:00';
    private $default_jam_layanan_selesai = '16:00:00';
    private $default_jam_tutup_kantor = '16:30:00';

    public function ensure_instansi_operasional_schema()
    {
        if (!$this->db->field_exists('jam_tutup_pendaftaran', 'instansi')) {
            $this->db->query("ALTER TABLE instansi ADD COLUMN jam_tutup_pendaftaran TIME NOT NULL DEFAULT '15:30:00' AFTER status_layanan");
        }

        if (!$this->db->field_exists('jam_layanan_mulai', 'instansi')) {
            $this->db->query("ALTER TABLE instansi ADD COLUMN jam_layanan_mulai TIME NOT NULL DEFAULT '08:30:00' AFTER jam_tutup_pendaftaran");
        }

        if (!$this->db->field_exists('jam_layanan_selesai', 'instansi')) {
            $this->db->query("ALTER TABLE instansi ADD COLUMN jam_layanan_selesai TIME NOT NULL DEFAULT '16:00:00' AFTER jam_layanan_mulai");
        }

        if (!$this->db->field_exists('jam_tutup_kantor', 'instansi')) {
            $this->db->query("ALTER TABLE instansi ADD COLUMN jam_tutup_kantor TIME NOT NULL DEFAULT '16:30:00' AFTER jam_layanan_selesai");
        }

        if (!$this->db->field_exists('status_layanan_mode', 'instansi')) {
            $this->db->query("ALTER TABLE instansi ADD COLUMN status_layanan_mode ENUM('otomatis','buka','tutup') NOT NULL DEFAULT 'otomatis' AFTER jam_tutup_kantor");
        }
    }

    public function normalize_time_value($value, $fallback)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return $fallback;
        }

        if (preg_match('/^\d{2}:\d{2}$/', $value)) {
            return $value . ':00';
        }

        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $value)) {
            return $value;
        }

        return $fallback;
    }

    public function normalize_status_mode($value)
    {
        $value = trim((string) $value);
        if (in_array($value, ['otomatis', 'buka', 'tutup'], true)) {
            return $value;
        }

        if (in_array($value, ['open', 'opened'], true)) {
            return 'buka';
        }

        if (in_array($value, ['close', 'closed'], true)) {
            return 'tutup';
        }

        return 'otomatis';
    }

    public function get_time_defaults()
    {
        return [
            'jam_tutup_pendaftaran' => $this->default_jam_tutup_pendaftaran,
            'jam_layanan_mulai' => $this->default_jam_layanan_mulai,
            'jam_layanan_selesai' => $this->default_jam_layanan_selesai,
            'jam_tutup_kantor' => $this->default_jam_tutup_kantor,
        ];
    }

    public function prepare_operasional_payload(array $input = [], $existing = null)
    {
        $raw_mode = $input['status_layanan_mode'] ?? null;
        if ($raw_mode === null && isset($input['status_layanan'])) {
            $raw_mode = $input['status_layanan'];
        }

        $mode = $this->normalize_status_mode($raw_mode ?? ($existing->status_layanan_mode ?? 'otomatis'));
        $jam_tutup_pendaftaran = $this->normalize_time_value(
            $input['jam_tutup_pendaftaran'] ?? ($existing->jam_tutup_pendaftaran ?? null),
            $this->default_jam_tutup_pendaftaran
        );
        $jam_layanan_mulai = $this->normalize_time_value(
            $input['jam_layanan_mulai'] ?? ($existing->jam_layanan_mulai ?? null),
            $this->default_jam_layanan_mulai
        );
        $jam_layanan_selesai = $this->normalize_time_value(
            $input['jam_layanan_selesai'] ?? ($existing->jam_layanan_selesai ?? null),
            $this->default_jam_layanan_selesai
        );
        $jam_tutup_kantor = $this->normalize_time_value(
            $input['jam_tutup_kantor'] ?? ($existing->jam_tutup_kantor ?? null),
            $this->default_jam_tutup_kantor
        );

        return [
            'jam_tutup_pendaftaran' => $jam_tutup_pendaftaran,
            'jam_layanan_mulai' => $jam_layanan_mulai,
            'jam_layanan_selesai' => $jam_layanan_selesai,
            'jam_tutup_kantor' => $jam_tutup_kantor,
            'status_layanan_mode' => $mode,
            'status_layanan' => $this->calculate_effective_status_from_values(
                $mode,
                $jam_layanan_mulai,
                $jam_layanan_selesai
            ),
        ];
    }

    public function calculate_effective_status_from_values($mode, $jam_layanan_mulai, $jam_layanan_selesai, $now_time = null)
    {
        $mode = $this->normalize_status_mode($mode);
        if ($mode === 'buka') {
            return 'buka';
        }

        if ($mode === 'tutup') {
            return 'tutup';
        }

        $now_time = $this->normalize_time_value($now_time ?: date('H:i:s'), date('H:i:s'));
        $jam_layanan_mulai = $this->normalize_time_value($jam_layanan_mulai, $this->default_jam_layanan_mulai);
        $jam_layanan_selesai = $this->normalize_time_value($jam_layanan_selesai, $this->default_jam_layanan_selesai);

        return ($now_time >= $jam_layanan_mulai && $now_time < $jam_layanan_selesai) ? 'buka' : 'tutup';
    }

    public function get_effective_status($instansi, $now_time = null)
    {
        return $this->calculate_effective_status_from_values(
            $instansi->status_layanan_mode ?? 'otomatis',
            $instansi->jam_layanan_mulai ?? $this->default_jam_layanan_mulai,
            $instansi->jam_layanan_selesai ?? $this->default_jam_layanan_selesai,
            $now_time
        );
    }

    public function is_force_closed($instansi)
    {
        return $this->normalize_status_mode($instansi->status_layanan_mode ?? 'otomatis') === 'tutup';
    }

    public function is_offline_registration_open($instansi, $now_time = null)
    {
        $now_time = $this->normalize_time_value($now_time ?: date('H:i:s'), date('H:i:s'));
        $jam_tutup_kantor = $this->normalize_time_value($instansi->jam_tutup_kantor ?? null, $this->default_jam_tutup_kantor);

        return $this->get_effective_status($instansi, $now_time) === 'buka' && $now_time < $jam_tutup_kantor;
    }

    public function validate_online_registration($instansi, $tanggal)
    {
        $today = date('Y-m-d');
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        $now_time = date('H:i:s');
        $tanggal = trim((string) $tanggal);

        if (isset($instansi->instansi_aktif) && (int) $instansi->instansi_aktif !== 1) {
            return [
                'allowed' => false,
                'message' => 'Instansi sedang tidak aktif.',
                'suggested_date' => $today,
            ];
        }

        if (isset($instansi->is_aktif) && (int) $instansi->is_aktif !== 1) {
            return [
                'allowed' => false,
                'message' => 'Instansi sedang tidak aktif.',
                'suggested_date' => $today,
            ];
        }

        if ($tanggal === '') {
            return [
                'allowed' => false,
                'message' => 'Tanggal kunjungan wajib dipilih.',
                'suggested_date' => $today,
            ];
        }

        if ($tanggal < $today) {
            return [
                'allowed' => false,
                'message' => 'Tanggal kunjungan tidak boleh lebih kecil dari hari ini.',
                'suggested_date' => $today,
            ];
        }

        if ($this->is_force_closed($instansi)) {
            return [
                'allowed' => false,
                'message' => 'Layanan sedang ditutup oleh admin. Silakan pilih instansi lain atau hubungi petugas.',
                'suggested_date' => $tanggal === $today ? $tomorrow : $tanggal,
            ];
        }

        if ($tanggal === $today) {
            $jam_tutup_pendaftaran = $this->normalize_time_value(
                $instansi->jam_tutup_pendaftaran ?? null,
                $this->default_jam_tutup_pendaftaran
            );

            if ($now_time >= $jam_tutup_pendaftaran) {
                return [
                    'allowed' => false,
                    'message' => 'Pendaftaran online untuk hari ini sudah ditutup. Silakan pilih tanggal besok.',
                    'suggested_date' => $tomorrow,
                ];
            }
        }

        return [
            'allowed' => true,
            'message' => null,
            'suggested_date' => $tanggal,
        ];
    }

    public function validate_offline_registration($instansi)
    {
        if (isset($instansi->is_aktif) && (int) $instansi->is_aktif !== 1) {
            return [
                'allowed' => false,
                'message' => 'Instansi sedang tidak aktif.',
            ];
        }

        if ($this->is_force_closed($instansi)) {
            return [
                'allowed' => false,
                'message' => 'Layanan sedang ditutup oleh admin.',
            ];
        }

        if (!$this->is_offline_registration_open($instansi)) {
            return [
                'allowed' => false,
                'message' => 'Layanan sedang di luar jam operasional.',
            ];
        }

        return [
            'allowed' => true,
            'message' => null,
        ];
    }

    public function update_instansi_status_mode($instansi_id, $mode)
    {
        $this->ensure_instansi_operasional_schema();

        $instansi = $this->db->get_where('instansi', ['id' => (int) $instansi_id])->row();
        if (!$instansi) {
            return null;
        }

        $payload = $this->prepare_operasional_payload([
            'status_layanan_mode' => $mode,
            'jam_tutup_pendaftaran' => $instansi->jam_tutup_pendaftaran ?? null,
            'jam_layanan_mulai' => $instansi->jam_layanan_mulai ?? null,
            'jam_layanan_selesai' => $instansi->jam_layanan_selesai ?? null,
            'jam_tutup_kantor' => $instansi->jam_tutup_kantor ?? null,
        ], $instansi);
        $payload['updated_at'] = date('Y-m-d H:i:s');

        $this->db->where('id', (int) $instansi_id)->update('instansi', $payload);
        return $payload;
    }

    public function sync_operasional_harian()
    {
        $this->ensure_instansi_operasional_schema();

        $today = date('Y-m-d');
        $now = date('Y-m-d H:i:s');
        $now_time = date('H:i:s');
        $active_status = ['terdaftar', 'menunggu', 'dipanggil'];

        $this->db->where('tanggal <', $today);
        $this->db->where_in('status', $active_status);
        $this->db->update('antrian', [
            'status' => 'selesai',
            'updated_at' => $now,
            'updated_by' => null,
            'updated_role' => 'system',
        ]);

        $instansi_list = $this->db
            ->select('id, status_layanan, status_layanan_mode, jam_layanan_mulai, jam_layanan_selesai, jam_tutup_kantor')
            ->from('instansi')
            ->get()
            ->result();

        $close_office_ids = [];
        foreach ($instansi_list as $instansi) {
            $effective_status = $this->get_effective_status($instansi, $now_time);
            if (($instansi->status_layanan ?? '') !== $effective_status) {
                $this->db->where('id', (int) $instansi->id)->update('instansi', [
                    'status_layanan' => $effective_status,
                    'updated_at' => $now,
                ]);
            }

            $jam_tutup_kantor = $this->normalize_time_value(
                $instansi->jam_tutup_kantor ?? null,
                $this->default_jam_tutup_kantor
            );
            if ($now_time >= $jam_tutup_kantor) {
                $close_office_ids[] = (int) $instansi->id;
            }
        }

        if (!empty($close_office_ids)) {
            $layanan_ids = $this->db
                ->select('id')
                ->from('jenis_layanan')
                ->where_in('instansi_id', $close_office_ids)
                ->get()
                ->result_array();

            $layanan_ids = array_column($layanan_ids, 'id');
            if (!empty($layanan_ids)) {
                $this->db->where_in('layanan_id', $layanan_ids);
                $this->db->where('tanggal', $today);
                $this->db->where_in('status', $active_status);
                $this->db->update('antrian', [
                    'status' => 'selesai',
                    'updated_at' => $now,
                    'updated_by' => null,
                    'updated_role' => 'system',
                ]);
            }
        }
    }
}
