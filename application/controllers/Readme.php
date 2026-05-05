<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Readme extends CI_Controller
{
    public function printer()
    {
        $data['title'] = 'Panduan Printer Ubuntu';

        $path = FCPATH . 'readme/README_UBUNTU_PRINTER.md';
        if (file_exists($path)) {
            $data['readme_md'] = file_get_contents($path);
        } else {
            $data['readme_md'] = "# File tidak ditemukan\n\nPastikan file README_UBUNTU_PRINTER.md ada di folder `ptsp/readme`.";
        }

        $this->load->view('templates/_header', $data);
        $this->load->view('templates/_sidebar', $data);
        $this->load->view('readme/printer', $data);
        $this->load->view('templates/_footer');
    }

    public function download($name = null)
    {
        $this->load->helper('download');

        $files = [
            'ubuntu-printer' => [
                'path' => FCPATH . 'readme/README_UBUNTU_PRINTER.md',
                'name' => 'README_UBUNTU_PRINTER.md',
                'type' => 'text/markdown'
            ],
            'thermal-server' => [
                'path' => FCPATH . 'readme/thermal_server.py',
                'name' => 'thermal_server.py',
                'type' => 'text/x-python'
            ],
            'printer-win-simple' => [
                'path' => FCPATH . 'readme/printer_win_simple.py',
                'name' => 'printer_win_simple.py',
                'type' => 'text/x-python'
            ],
            'service-ubuntu' => [
                'path' => FCPATH . 'readme/ptsp-printer.service',
                'name' => 'ptsp-printer.service',
                'type' => 'text/plain'
            ]
        ];

        if (!$name || !isset($files[$name])) {
            show_404();
            return;
        }

        $file = $files[$name];
        if (!file_exists($file['path'])) {
            show_404();
            return;
        }

        $data = file_get_contents($file['path']);
        force_download($file['name'], $data);
    }
}
