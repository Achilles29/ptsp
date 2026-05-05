<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Email_setting_model extends CI_Model
{
    private $table = 'email_settings';

    public function __construct()
    {
        parent::__construct();
        $this->ensure_schema();
    }

    public function ensure_schema()
    {
        if (!$this->db->table_exists($this->table)) {
            $this->db->query("
                CREATE TABLE {$this->table} (
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
                )
            ");
        }

        if (!$this->db->field_exists('verification_sent_at', 'users')) {
            $this->db->query("ALTER TABLE users ADD COLUMN verification_sent_at DATETIME NULL AFTER verify_token");
        }

        $count = (int) $this->db->count_all($this->table);
        if ($count === 0) {
            $now = date('Y-m-d H:i:s');
            $this->db->insert($this->table, [
                'protocol' => 'smtp',
                'smtp_host' => 'smtp.gmail.com',
                'smtp_port' => 465,
                'smtp_crypto' => 'ssl',
                'smtp_user' => '',
                'smtp_pass' => '',
                'from_email' => '',
                'from_name' => 'Portal MPP Rembang',
                'reply_to_email' => '',
                'mailtype' => 'html',
                'charset_name' => 'utf-8',
                'resend_cooldown_minutes' => 5,
                'verification_subject' => 'Verifikasi Akun {app_name}',
                'verification_message' => $this->get_default_verification_message(),
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }
    }

    public function get_settings()
    {
        $row = $this->db->order_by('id', 'ASC')->get($this->table)->row_array();
        if (!$row) {
            $this->ensure_schema();
            $row = $this->db->order_by('id', 'ASC')->get($this->table)->row_array();
        }

        if (!$row) {
            return [];
        }

        $row['verification_subject'] = trim((string) ($row['verification_subject'] ?? '')) !== ''
            ? $row['verification_subject']
            : 'Verifikasi Akun {app_name}';

        $row['verification_message'] = trim((string) ($row['verification_message'] ?? '')) !== ''
            ? $row['verification_message']
            : $this->get_default_verification_message();

        $row['from_name'] = trim((string) ($row['from_name'] ?? '')) !== ''
            ? $row['from_name']
            : 'Portal MPP Rembang';

        $row['mailtype'] = trim((string) ($row['mailtype'] ?? '')) !== ''
            ? $row['mailtype']
            : 'html';

        $row['charset_name'] = trim((string) ($row['charset_name'] ?? '')) !== ''
            ? $row['charset_name']
            : 'utf-8';

        $row['resend_cooldown_minutes'] = max(1, (int) ($row['resend_cooldown_minutes'] ?? 5));

        return $row;
    }

    public function save_settings(array $payload)
    {
        $current = $this->get_settings();
        $id = (int) ($current['id'] ?? 0);
        $now = date('Y-m-d H:i:s');

        $data = [
            'protocol' => 'smtp',
            'smtp_host' => trim((string) ($payload['smtp_host'] ?? 'smtp.gmail.com')),
            'smtp_port' => max(1, (int) ($payload['smtp_port'] ?? 465)),
            'smtp_crypto' => in_array(($payload['smtp_crypto'] ?? 'ssl'), ['ssl', 'tls', ''], true) ? $payload['smtp_crypto'] : 'ssl',
            'smtp_user' => trim((string) ($payload['smtp_user'] ?? '')),
            'from_email' => trim((string) ($payload['from_email'] ?? '')),
            'from_name' => trim((string) ($payload['from_name'] ?? 'Portal MPP Rembang')),
            'reply_to_email' => trim((string) ($payload['reply_to_email'] ?? '')),
            'mailtype' => 'html',
            'charset_name' => 'utf-8',
            'resend_cooldown_minutes' => max(1, (int) ($payload['resend_cooldown_minutes'] ?? 5)),
            'verification_subject' => trim((string) ($payload['verification_subject'] ?? 'Verifikasi Akun {app_name}')),
            'verification_message' => trim((string) ($payload['verification_message'] ?? $this->get_default_verification_message())),
            'updated_at' => $now
        ];

        if (!empty($payload['smtp_pass'])) {
            $data['smtp_pass'] = $payload['smtp_pass'];
        } elseif (!$id) {
            $data['smtp_pass'] = '';
        }

        if ($id > 0) {
            $this->db->where('id', $id)->update($this->table, $data);
            return $id;
        }

        $data['created_at'] = $now;
        $this->db->insert($this->table, $data);
        return (int) $this->db->insert_id();
    }

    public function get_default_verification_message()
    {
        return <<<HTML
<p>Halo {nama_lengkap},</p>
<p>Terima kasih sudah mendaftar di <strong>{app_name}</strong>. Untuk mengaktifkan akun Anda, silakan klik tombol verifikasi di bawah ini.</p>
<p><a href="{verification_link}" style="display:inline-block;padding:12px 20px;border-radius:10px;background:#1f5eff;color:#ffffff;text-decoration:none;font-weight:700;">Verifikasi Akun Saya</a></p>
<p>Jika tombol tidak bisa diklik, salin tautan berikut ke browser Anda:</p>
<p>{verification_link}</p>
<p>Email ini dikirim ke alamat <strong>{email}</strong>. Jika Anda tidak merasa mendaftar, abaikan email ini.</p>
<p>Salam,<br>{from_name}</p>
HTML;
    }

    public function get_resend_cooldown_minutes()
    {
        $settings = $this->get_settings();
        return max(1, (int) ($settings['resend_cooldown_minutes'] ?? 5));
    }

    public function get_remaining_resend_seconds($verification_sent_at)
    {
        if (empty($verification_sent_at)) {
            return 0;
        }

        $cooldown = $this->get_resend_cooldown_minutes() * 60;
        $sent_at = strtotime($verification_sent_at);
        if (!$sent_at) {
            return 0;
        }

        return max(0, ($sent_at + $cooldown) - time());
    }

    public function mark_verification_sent($user_id)
    {
        $this->db->where('id', (int) $user_id)->update('users', [
            'verification_sent_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function send_verification_email($email, $token, array $context = [])
    {
        $settings = $this->get_settings();
        if (!$this->is_ready_for_delivery($settings)) {
            return [
                'status' => false,
                'message' => 'Pengaturan email belum lengkap. Silakan lengkapi SMTP, akun pengirim, dan email pengirim terlebih dahulu.'
            ];
        }

        $verification_link = base_url('auth/verify_email/' . $token);
        $subject = $this->replace_placeholders($settings['verification_subject'], $settings, $context, $verification_link, $email);
        $message = $this->replace_placeholders($settings['verification_message'], $settings, $context, $verification_link, $email);

        return $this->dispatch_email([
            'to' => $email,
            'subject' => $subject,
            'message' => $message
        ], $settings);
    }

    public function send_reset_password_email($email, $nama, $password_baru)
    {
        $settings = $this->get_settings();
        if (!$this->is_ready_for_delivery($settings)) {
            return [
                'status' => false,
                'message' => 'Pengaturan email belum lengkap. Silakan hubungi admin untuk melengkapi SMTP.'
            ];
        }

        $subject = 'Password Baru Akun Anda';
        $message = nl2br(
            "Halo {$nama},\n\n" .
            "Berikut adalah password baru Anda:\n\n" .
            "Password: {$password_baru}\n\n" .
            "Silakan login dan segera ganti password Anda setelah berhasil masuk.\n\n" .
            "Salam,\n" . ($settings['from_name'] ?? 'Portal MPP Rembang')
        );

        return $this->dispatch_email([
            'to' => $email,
            'subject' => $subject,
            'message' => $message
        ], $settings);
    }

    private function dispatch_email(array $payload, array $settings)
    {
        $config = $this->build_runtime_config($settings);

        $this->email->clear(true);
        $this->email->initialize($config);
        $this->email->from($settings['from_email'], $settings['from_name']);

        if (!empty($settings['reply_to_email'])) {
            $this->email->reply_to($settings['reply_to_email'], $settings['from_name']);
        }

        $this->email->to($payload['to']);
        $this->email->subject($payload['subject']);
        $this->email->message($payload['message']);

        if ($this->email->send()) {
            return [
                'status' => true,
                'message' => 'Email berhasil dikirim.'
            ];
        }

        log_message('error', 'Email delivery failed: ' . $this->email->print_debugger(['headers']));

        return [
            'status' => false,
            'message' => 'Email gagal dikirim. Periksa SMTP host, port, enkripsi, akun pengirim, dan app password.'
        ];
    }

    private function build_runtime_config(array $settings)
    {
        $smtp_host = trim((string) ($settings['smtp_host'] ?? ''));
        $smtp_crypto = trim((string) ($settings['smtp_crypto'] ?? ''));
        $smtp_crypto_config = $smtp_crypto;

        if ($smtp_crypto === 'ssl' && $smtp_host !== '' && strpos($smtp_host, '://') === false) {
            $smtp_host = 'ssl://' . $smtp_host;
            $smtp_crypto_config = '';
        }

        return [
            'protocol' => 'smtp',
            'smtp_host' => $smtp_host,
            'smtp_port' => max(1, (int) ($settings['smtp_port'] ?? 465)),
            'smtp_user' => trim((string) ($settings['smtp_user'] ?? '')),
            'smtp_pass' => (string) ($settings['smtp_pass'] ?? ''),
            'smtp_crypto' => $smtp_crypto_config,
            'mailtype' => trim((string) ($settings['mailtype'] ?? 'html')),
            'charset' => trim((string) ($settings['charset_name'] ?? 'utf-8')),
            'newline' => "\r\n",
            'crlf' => "\r\n",
            'wordwrap' => true
        ];
    }

    private function replace_placeholders($template, array $settings, array $context, $verification_link, $email)
    {
        $app_name = 'Portal MPP Rembang';
        $nama = trim((string) ($context['nama_lengkap'] ?? 'Pengguna'));

        return strtr((string) $template, [
            '{app_name}' => $app_name,
            '{nama_lengkap}' => $nama,
            '{email}' => (string) $email,
            '{verification_link}' => (string) $verification_link,
            '{from_name}' => (string) ($settings['from_name'] ?? 'Portal MPP Rembang')
        ]);
    }

    private function is_ready_for_delivery(array $settings)
    {
        return trim((string) ($settings['smtp_host'] ?? '')) !== ''
            && trim((string) ($settings['smtp_user'] ?? '')) !== ''
            && trim((string) ($settings['smtp_pass'] ?? '')) !== ''
            && trim((string) ($settings['from_email'] ?? '')) !== '';
    }
}
