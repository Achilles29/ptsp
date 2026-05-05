<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        $this->load->model(['Laporan_model', 'Instansi_model', 'Layanan_model']);
        $this->load->library('pagination');
    }

    private function role_id()
    {
        return (int) $this->session->userdata('role_id');
    }

    private function session_instansi_id()
    {
        return (int) $this->session->userdata('instansi_id');
    }

    private function scope_instansi_id($requested_instansi_id = null)
    {
        if ($this->role_id() !== 1) {
            return $this->session_instansi_id();
        }

        return $requested_instansi_id ? (int) $requested_instansi_id : null;
    }

    private function date_start_default()
    {
        return date('Y-m-01');
    }

    private function date_end_default()
    {
        return date('Y-m-d');
    }

    private function normalize_date_range()
    {
        $start_date = $this->input->get('start_date') ?: $this->date_start_default();
        $end_date = $this->input->get('end_date') ?: $this->date_end_default();

        if ($start_date > $end_date) {
            $temp = $start_date;
            $start_date = $end_date;
            $end_date = $temp;
        }

        return [$start_date, $end_date];
    }

    private function pagination_links($path, $params, $total_rows, $limit)
    {
        if ((int) $limit <= 0 || (int) $total_rows <= 0) {
            return '';
        }

        $query = http_build_query($params);
        $config['base_url'] = site_url($path . ($query ? '?' . $query : ''));
        $config['total_rows'] = $total_rows;
        $config['per_page'] = (int) $limit;
        $config['page_query_string'] = true;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = true;
        $config['reuse_query_string'] = true;
        $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['attributes'] = ['class' => 'page-link'];
        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }

    private function render_report($view, $data)
    {
        $this->load->view('templates/_header', $data);
        $this->load->view('templates/_sidebar', $data);
        $this->load->view($view, $data);
        $this->load->view('templates/_footer');
    }

    public function index()
    {
        redirect('laporan/dashboard');
    }

    public function dashboard()
    {
        [$start_date, $end_date] = $this->normalize_date_range();
        $role_id = $this->role_id();
        $instansi_id = $this->scope_instansi_id($this->input->get('instansi_id'));

        $data = [
            'title' => 'Dashboard Laporan',
            'start_date' => $start_date,
            'end_date' => $end_date,
            'instansi_id' => $instansi_id,
            'instansi_list' => $this->Instansi_model->get_all(),
            'summary' => $this->Laporan_model->get_dashboard_summary($start_date, $end_date, $role_id, $instansi_id),
            'trend_rows' => $this->Laporan_model->get_tren_antrian($start_date, $end_date, $role_id, $instansi_id, 'harian'),
            'konversi' => $this->Laporan_model->get_konversi_antrian($start_date, $end_date, $role_id, $instansi_id),
            'top_instansi' => $this->Laporan_model->get_top_instansi($start_date, $end_date, $role_id, $instansi_id, 6),
            'sla_rows' => $this->Laporan_model->get_sla_layanan_report($start_date, $end_date, $role_id, $instansi_id),
            'no_show_sources' => $this->Laporan_model->get_no_show_summary_by_source($start_date, $end_date, $role_id, $instansi_id),
        ];

        $this->render_report('laporan/dashboard', $data);
    }

    public function rekap_antrian()
    {
        [$start_date, $end_date] = $this->normalize_date_range();
        $role_id = $this->role_id();
        $instansi_id = $this->scope_instansi_id($this->input->get('instansi_id'));
        $limit = (int) ($this->input->get('limit') ?? 25);
        $page = max(1, (int) ($this->input->get('page') ?? 1));
        $offset = $limit > 0 ? ($page - 1) * $limit : 0;

        $rekap = $this->Laporan_model->get_rekap_antrian_fix($start_date, $end_date, $role_id, $instansi_id, $limit, $offset);
        $total = $this->Laporan_model->count_rekap_antrian_fix($start_date, $end_date, $role_id, $instansi_id);
        $pagination = $this->pagination_links('laporan/rekap_antrian', [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'instansi_id' => $instansi_id,
            'limit' => $limit,
        ], $total, $limit);

        $data = [
            'title' => 'Laporan Rekap Antrian',
            'rekap' => $rekap,
            'pagination' => $pagination,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'limit' => $limit,
            'page' => $page,
            'instansi_id' => $instansi_id,
            'instansi_list' => $this->Instansi_model->get_all(),
            'chart_rows' => $this->Laporan_model->get_rekap_chart_series($start_date, $end_date, $role_id, $instansi_id),
            'konversi' => $this->Laporan_model->get_konversi_antrian($start_date, $end_date, $role_id, $instansi_id),
        ];

        $this->render_report('laporan/rekap_antrian', $data);
    }

    public function detail_antrian()
    {
        [$start_date, $end_date] = $this->normalize_date_range();
        $instansi_id = $this->scope_instansi_id($this->input->get('instansi_id'));
        $layanan_id = $this->input->get('layanan_id') ?: null;
        $limit = (int) ($this->input->get('limit') ?: 25);
        $page = max(1, (int) ($this->input->get('page') ?: 1));
        $offset = $limit > 0 ? (($page - 1) * $limit) : 0;

        $detail = $this->Laporan_model->get_laporan_detail_antrian($start_date, $end_date, $instansi_id, $layanan_id, $limit, $offset);
        $total_rows = $this->Laporan_model->count_laporan_detail_antrian($start_date, $end_date, $instansi_id, $layanan_id);
        $pagination = $this->pagination_links('laporan/detail_antrian', [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'instansi_id' => $instansi_id,
            'layanan_id' => $layanan_id,
            'limit' => $limit
        ], $total_rows, $limit);

        $data = [
            'title' => 'Laporan Detail Antrian',
            'detail' => $detail,
            'total_rows' => $total_rows,
            'pagination' => $pagination,
            'instansi_list' => $this->Instansi_model->get_all(),
            'layanan_list' => $this->Layanan_model->get_by_instansi($instansi_id),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'limit' => $limit,
            'page' => $page,
            'instansi_id' => $instansi_id,
            'layanan_id' => $layanan_id,
        ];

        $this->render_report('laporan/detail_antrian', $data);
    }

    public function waktu_layanan()
    {
        [$start_date, $end_date] = $this->normalize_date_range();
        $role_id = $this->role_id();
        $instansi_id = $this->scope_instansi_id($this->input->get('instansi_id'));
        $limit = (int) ($this->input->get('limit') ?? 25);

        $data = [
            'title' => 'Laporan Waktu Layanan',
            'start_date' => $start_date,
            'end_date' => $end_date,
            'limit' => $limit,
            'instansi_id' => $instansi_id,
            'instansi_list' => $this->Instansi_model->get_all(),
            'data' => ($role_id === 1 && empty($instansi_id))
                ? $this->Laporan_model->get_waktu_layanan_all($start_date, $end_date, $limit)
                : $this->Laporan_model->get_waktu_layanan_by_instansi($role_id === 1 ? $instansi_id : $this->session_instansi_id(), $start_date, $end_date, $limit),
        ];

        $this->render_report('laporan/waktu_layanan', $data);
    }

    public function detail_hasil_layanan()
    {
        [$start_date, $end_date] = $this->normalize_date_range();
        $instansi_id = $this->scope_instansi_id($this->input->get('instansi_id'));
        $layanan_id = $this->input->get('layanan_id');
        $limit = (int) ($this->input->get('limit') ?? 25);
        $page = max(1, (int) ($this->input->get('page') ?? 1));
        $offset = $limit > 0 ? ($page - 1) * $limit : 0;
        $total_rows = $this->Laporan_model->count_detail_hasil_layanan($start_date, $end_date, $instansi_id, $layanan_id);

        $data = [
            'title' => 'Laporan Detail Hasil Layanan',
            'hasil' => $this->Laporan_model->get_detail_hasil_layanan($start_date, $end_date, $instansi_id, $layanan_id, $limit, $offset),
            'pagination' => $this->pagination_links('laporan/detail_hasil_layanan', [
                'start_date' => $start_date,
                'end_date' => $end_date,
                'instansi_id' => $instansi_id,
                'layanan_id' => $layanan_id,
                'limit' => $limit
            ], $total_rows, $limit),
            'total_rows' => $total_rows,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'limit' => $limit,
            'page' => $page,
            'instansi_id' => $instansi_id,
            'layanan_id' => $layanan_id,
            'instansi_list' => $this->Instansi_model->get_all(),
            'layanan_list' => $this->Layanan_model->get_by_instansi($instansi_id),
        ];

        $this->render_report('laporan/detail_hasil_layanan', $data);
    }

    public function jam_sibuk()
    {
        [$start_date, $end_date] = $this->normalize_date_range();
        $role_id = $this->role_id();
        $instansi_id = $this->scope_instansi_id($this->input->get('instansi_id'));

        $data = [
            'title' => 'Laporan Jam Sibuk',
            'start_date' => $start_date,
            'end_date' => $end_date,
            'instansi_id' => $instansi_id,
            'instansi_list' => $this->Instansi_model->get_all(),
            'matrix_rows' => $this->Laporan_model->get_busy_hours_matrix($start_date, $end_date, $role_id, $instansi_id),
            'top_hours' => $this->Laporan_model->get_busy_hours_top($start_date, $end_date, $role_id, $instansi_id),
        ];

        $this->render_report('laporan/jam_sibuk', $data);
    }

    public function sla_layanan()
    {
        [$start_date, $end_date] = $this->normalize_date_range();
        $role_id = $this->role_id();
        $instansi_id = $this->scope_instansi_id($this->input->get('instansi_id'));

        $data = [
            'title' => 'Laporan SLA Layanan',
            'start_date' => $start_date,
            'end_date' => $end_date,
            'instansi_id' => $instansi_id,
            'instansi_list' => $this->Instansi_model->get_all(),
            'rows' => $this->Laporan_model->get_sla_layanan_report($start_date, $end_date, $role_id, $instansi_id),
        ];

        $this->render_report('laporan/sla_layanan', $data);
    }

    public function no_show()
    {
        [$start_date, $end_date] = $this->normalize_date_range();
        $role_id = $this->role_id();
        $instansi_id = $this->scope_instansi_id($this->input->get('instansi_id'));

        $data = [
            'title' => 'Laporan No-Show',
            'start_date' => $start_date,
            'end_date' => $end_date,
            'instansi_id' => $instansi_id,
            'instansi_list' => $this->Instansi_model->get_all(),
            'rows' => $this->Laporan_model->get_no_show_report($start_date, $end_date, $role_id, $instansi_id),
            'source_summary' => $this->Laporan_model->get_no_show_summary_by_source($start_date, $end_date, $role_id, $instansi_id),
        ];

        $this->render_report('laporan/no_show', $data);
    }

    public function kinerja_petugas()
    {
        [$start_date, $end_date] = $this->normalize_date_range();
        $role_id = $this->role_id();
        $instansi_id = $this->scope_instansi_id($this->input->get('instansi_id'));

        $data = [
            'title' => 'Laporan Kinerja Petugas',
            'start_date' => $start_date,
            'end_date' => $end_date,
            'instansi_id' => $instansi_id,
            'instansi_list' => $this->Instansi_model->get_all(),
            'rows' => $this->Laporan_model->get_kinerja_petugas_report($start_date, $end_date, $role_id, $instansi_id),
        ];

        $this->render_report('laporan/kinerja_petugas', $data);
    }

    public function tren_antrian()
    {
        [$start_date, $end_date] = $this->normalize_date_range();
        $role_id = $this->role_id();
        $instansi_id = $this->scope_instansi_id($this->input->get('instansi_id'));
        $mode = $this->input->get('mode') === 'bulanan' ? 'bulanan' : 'harian';

        $data = [
            'title' => 'Laporan Tren Harian dan Bulanan',
            'start_date' => $start_date,
            'end_date' => $end_date,
            'instansi_id' => $instansi_id,
            'instansi_list' => $this->Instansi_model->get_all(),
            'mode' => $mode,
            'rows' => $this->Laporan_model->get_tren_antrian($start_date, $end_date, $role_id, $instansi_id, $mode),
        ];

        $this->render_report('laporan/tren_antrian', $data);
    }

    public function konversi_antrian()
    {
        [$start_date, $end_date] = $this->normalize_date_range();
        $role_id = $this->role_id();
        $instansi_id = $this->scope_instansi_id($this->input->get('instansi_id'));

        $data = [
            'title' => 'Laporan Konversi Antrian',
            'start_date' => $start_date,
            'end_date' => $end_date,
            'instansi_id' => $instansi_id,
            'instansi_list' => $this->Instansi_model->get_all(),
            'konversi' => $this->Laporan_model->get_konversi_antrian($start_date, $end_date, $role_id, $instansi_id),
        ];

        $this->render_report('laporan/konversi_antrian', $data);
    }

    public function kepadatan_sektor()
    {
        [$start_date, $end_date] = $this->normalize_date_range();
        $role_id = $this->role_id();
        $instansi_id = $this->scope_instansi_id($this->input->get('instansi_id'));

        $data = [
            'title' => 'Laporan Kepadatan Sektor',
            'start_date' => $start_date,
            'end_date' => $end_date,
            'instansi_id' => $instansi_id,
            'instansi_list' => $this->Instansi_model->get_all(),
            'rows' => $this->Laporan_model->get_kepadatan_sektor($start_date, $end_date, $role_id, $instansi_id),
        ];

        $this->render_report('laporan/kepadatan_sektor', $data);
    }

    public function export_excel()
    {
        $role_id = $this->role_id();
        [$start_date, $end_date] = $this->normalize_date_range();
        $instansi_id = $this->scope_instansi_id($this->input->get('instansi_id'));
        $rekap = $this->Laporan_model->get_rekap_antrian_fix($start_date, $end_date, $role_id, $instansi_id);

        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=rekap_antrian_{$start_date}_to_{$end_date}.xls");

        echo "<table border='1'>";
        echo "<tr><th>Tanggal</th><th>Instansi</th><th>Layanan</th><th>Total Pendaftar</th><th>Datang</th><th>Tidak Datang</th><th>Selesai</th></tr>";
        foreach ($rekap as $r) {
            echo "<tr>
                <td>{$r->tanggal}</td>
                <td>{$r->nama_instansi}</td>
                <td>{$r->nama_layanan}</td>
                <td>{$r->total_pendaftar}</td>
                <td>{$r->datang}</td>
                <td>{$r->tidak_datang}</td>
                <td>{$r->selesai}</td>
            </tr>";
        }
        echo "</table>";
    }

    public function export_detail_antrian_excel()
    {
        $role_id = $this->role_id();
        [$start_date, $end_date] = $this->normalize_date_range();
        $instansi_id = $this->scope_instansi_id($this->input->get('instansi_id'));
        $rows = $this->Laporan_model->get_laporan_detail_antrian_excel($start_date, $end_date, $role_id === 1 ? $instansi_id : $this->session_instansi_id());

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=detail_antrian_{$start_date}_sampai_{$end_date}.xls");

        echo "<table border='1'>";
        echo "<tr><th>No</th><th>Tanggal</th><th>Instansi</th><th>Layanan</th><th>Nama Pemohon</th><th>Nomor Antrian</th><th>Hadir</th><th>Status</th><th>Sumber</th><th>Hasil Layanan</th><th>Waktu Selesai</th></tr>";
        $no = 1;
        foreach ($rows as $r) {
            echo "<tr>
                <td>{$no}</td>
                <td>{$r->tanggal}</td>
                <td>{$r->nama_instansi}</td>
                <td>{$r->nama_layanan}</td>
                <td>{$r->nama_lengkap}</td>
                <td>{$r->nomor_antrian}</td>
                <td>" . ($r->hadir == 1 ? 'Datang' : 'Tidak Datang') . "</td>
                <td>{$r->status}</td>
                <td>{$r->sumber_daftar}</td>
                <td>" . ($r->jenis_hasil ?? '-') . "</td>
                <td>" . ($r->selesai_at ?? '-') . "</td>
            </tr>";
            $no++;
        }
        echo "</table>";
        exit;
    }

    public function export_durasi_layanan_excel()
    {
        $role_id = $this->role_id();
        [$start_date, $end_date] = $this->normalize_date_range();
        $instansi_id = $this->scope_instansi_id($this->input->get('instansi_id'));
        $rows = $this->Laporan_model->get_laporan_durasi_layanan_excel($start_date, $end_date, $role_id === 1 ? $instansi_id : $this->session_instansi_id());

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=durasi_layanan_{$start_date}_sampai_{$end_date}.xls");

        echo "<table border='1'>";
        echo "<tr><th>No</th><th>Nomor Antrian</th><th>Instansi</th><th>Layanan</th><th>Target SLA</th><th>Nama Petugas</th><th>Durasi Pelayanan</th><th>Waktu Mulai</th><th>Waktu Selesai</th></tr>";
        $no = 1;
        foreach ($rows as $r) {
            $durasi = '-';
            if ($r->called_at && $r->selesai_at) {
                $start = new DateTime($r->called_at);
                $end = new DateTime($r->selesai_at);
                $durasi = $start->diff($end)->format('%H:%I:%S');
            }

            echo "<tr>
                <td>{$no}</td>
                <td>{$r->nomor_antrian}</td>
                <td>{$r->nama_instansi}</td>
                <td>{$r->nama_layanan}</td>
                <td>{$r->target_durasi_menit} menit</td>
                <td>{$r->nama_petugas}</td>
                <td>{$durasi}</td>
                <td>{$r->called_at}</td>
                <td>{$r->selesai_at}</td>
            </tr>";
            $no++;
        }
        echo "</table>";
        exit;
    }

    public function export_detail_hasil_layanan_excel()
    {
        [$start_date, $end_date] = $this->normalize_date_range();
        $instansi_id = $this->scope_instansi_id($this->input->get('instansi_id'));
        $layanan_id = $this->input->get('layanan_id');
        $rows = $this->Laporan_model->get_detail_hasil_layanan($start_date, $end_date, $instansi_id, $layanan_id, 0, 0);

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=detail_hasil_layanan_{$start_date}_sampai_{$end_date}.xls");

        echo "<table border='1'>";
        echo "<tr><th>No</th><th>Tanggal</th><th>Instansi</th><th>Layanan</th><th>Nomor Antrian</th><th>Jenis Hasil</th><th>Petugas</th><th>Ringkasan / Produk</th><th>Catatan</th><th>Waktu Selesai</th></tr>";
        $no = 1;
        foreach ($rows as $row) {
            $deskripsi = '-';
            if ($row->jenis_hasil === 'konsultasi') {
                $deskripsi = $row->ringkasan_konsultasi ?: '-';
            } elseif ($row->jenis_hasil === 'produk_hukum') {
                $deskripsi = trim(($row->jenis_produk_hukum ?: '-') . ' / ' . ($row->nomor_produk ?: '-'));
            }

            echo "<tr>
                <td>{$no}</td>
                <td>{$row->tanggal}</td>
                <td>{$row->nama_instansi}</td>
                <td>{$row->nama_layanan}</td>
                <td>{$row->nomor_antrian}</td>
                <td>{$row->jenis_hasil}</td>
                <td>{$row->nama_petugas}</td>
                <td>{$deskripsi}</td>
                <td>{$row->catatan_petugas}</td>
                <td>{$row->selesai_at}</td>
            </tr>";
            $no++;
        }
        echo "</table>";
        exit;
    }
}
