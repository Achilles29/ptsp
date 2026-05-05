<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config = array(
    'protocol'  => 'smtp',
    'smtp_host' => '',
    'smtp_port' => 465,
    'smtp_user' => '',
    'smtp_pass' => '',
    'mailtype'  => 'html',
    'charset'   => 'utf-8',
    'newline'   => "\r\n",
    'crlf'      => "\r\n",
    'wordwrap'  => TRUE
);

// Catatan:
// Pengaturan email verifikasi utama sekarang dibaca secara dinamis
// dari menu Superadmin > Pengaturan Email.
