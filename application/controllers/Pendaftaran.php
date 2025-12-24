<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pendaftaran extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function manual()
    {
        $data['title'] = 'Pendaftaran Manual & Check-In';
        $data['instansi'] = $this->db->get('instansi')->result();
        $this->load->view('pendaftaran/manual', $data);
    }


    public function manual_v2()
    {
        $data['title'] = 'Frontdesk Antrian - Versi 2';

        $data['instansi'] = $this->db
            ->where('is_aktif', 1)
            ->order_by('nama_instansi', 'ASC')
            ->get('instansi')
            ->result();

        $this->load->view('pendaftaran/manual_v2', $data);
    }

    /* ==========================
       FRONTDESK: MODE V2 X
    ===========================*/
    public function manual_v2_x()
    {
        $data['title'] = 'Frontdesk Antrian - Mode X';

        $data['instansi'] = $this->db
            ->where('is_aktif', 1)
            ->order_by('nama_instansi', 'ASC')
            ->get('instansi')
            ->result();

        $this->load->view('pendaftaran/manual_v2_x', $data);
    }

    /* ===============================
       GET LAYANAN BY INSTANSI (AJAX)
       Dipakai manual, v2, v2_x
    ================================*/
    public function get_layanan_by_instansi($instansi_id = null)
    {
        if (!$instansi_id) {
            echo json_encode([]);
            return;
        }

        $data = $this->db
            ->select('id, nama_layanan, kode_huruf')
            ->from('jenis_layanan')
            ->where('instansi_id', $instansi_id)
            ->order_by('nama_layanan', 'ASC')
            ->get()
            ->result();

        echo json_encode($data);
    }


    /**
     * ======================================================
     * GENERATE NOMOR ANTRIAN BARU (per instansi & tanggal)
     * ======================================================
     */
    private function generate_nomor($layanan_id, $tanggal)
    {
        // Ambil instansi dari layanan terkait
        $layanan = $this->db->select('id, kode_huruf, instansi_id')
            ->from('jenis_layanan')
            ->where('id', $layanan_id)
            ->get()
            ->row();

        if (!$layanan) {
            return 'X000'; // fallback
        }

        $instansi_id = $layanan->instansi_id;
        $kode_huruf  = !empty($layanan->kode_huruf) ? $layanan->kode_huruf : 'X';

        // Hitung total antrian hari ini utk seluruh layanan di instansi tsb
        $this->db->from('antrian a');
        $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
        $this->db->where('jl.instansi_id', $instansi_id);
        $this->db->where('a.tanggal', $tanggal);
        $count = $this->db->count_all_results();

        // Nomor berikutnya (A001, A002, dst)
        $next = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return $kode_huruf . $next;
    }

    public function generate_antrian_v2()
    {
        $layanan_id = $this->input->post('layanan_id');
        $tanggal    = date('Y-m-d');

        if (!$layanan_id) {
            echo json_encode(['success' => false, 'message' => 'Layanan tidak valid']);
            return;
        }

        $nomor = $this->generate_nomor($layanan_id, $tanggal);

        $data = [
            'user_id'       => null,
            'layanan_id'    => $layanan_id,
            'tanggal'       => $tanggal,
            'status'        => 'terdaftar',
            'nomor_antrian' => $nomor,
            'hadir'         => 1, // walk-in = langsung hadir
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s')
        ];

        $this->db->insert('antrian', $data);

        echo json_encode([
            'success' => true,
            'nomor'   => $nomor
        ]);
    }


    /**
     * ======================================================
     * SIMPAN DAN CETAK ANTRIAN MANUAL (walk-in)
     * ======================================================
     */
    public function generate_antrian()
    {
        $layanan_id = $this->input->post('layanan_id');
        $tanggal = date('Y-m-d');

        $this->db->trans_start();
        $nomor = $this->generate_nomor($layanan_id, $tanggal);

        $data = [
            'user_id'       => null,
            'layanan_id'    => $layanan_id,
            'tanggal'       => $tanggal,
            'status'        => 'terdaftar',
            'nomor_antrian' => $nomor,
            'updated_by'    => null,
            'updated_role'  => null,
            'hadir'         => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s')
        ];

        $this->db->insert('antrian', $data);
        $id = $this->db->insert_id();
        $this->db->trans_complete();

        echo json_encode([
            'success' => true,
            'nomor' => $nomor,
            'id' => $id
        ]);
    }

    public function check_in()
    {
        $antrian_id = $this->input->post('antrian_id');

        $antrian = $this->db->get_where('antrian', [
            'id' => $antrian_id,
            'tanggal' => date('Y-m-d')
        ])->row();

        if (!$antrian) {
            echo json_encode(['success' => false, 'message' => 'Data antrian tidak ditemukan']);
            return;
        }

        // update hadir jadi 1
        $this->db->where('id', $antrian_id)->update('antrian', [
            'hadir' => 1,
            'status' => 'terdaftar' // tetap, tidak berubah
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Check-in berhasil',
            'nomor' => $antrian->nomor_antrian
        ]);
    }

    public function list_antrian_manual_today()
    {
        $today = date('Y-m-d');

        $data = $this->db->select('a.*, u.nama_lengkap, jl.nama_layanan')
            ->from('antrian a')
            ->join('users u', 'u.id = a.user_id', 'left')
            ->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left')
            ->where('a.tanggal', $today)
            ->where('a.hadir', 0)
            ->order_by('a.nomor_antrian', 'ASC')
            ->get()
            ->result();

        ob_start(); ?>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor</th>
                    <th>Nama</th>
                    <th>Layanan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($data as $i => $row): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><b><?= $row->nomor_antrian ?></b></td>
                        <td><?= $row->nama_lengkap ?? '-' ?></td>
                        <td><?= $row->nama_layanan ?></td>
                        <td>
                            <button class="btn btn-sm btn-success" onclick="manualCheckin(<?= $row->id ?>)">
                                <i class="bi bi-check2-circle"></i> Check-In
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>

        <script>
            function manualCheckin(id) {
                Swal.fire({
                    title: 'Check-in?',
                    text: 'Tandai pengunjung sudah hadir?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Check-in'
                }).then((r) => {
                    if (r.isConfirmed) {
                        $.post('<?= site_url("pendaftaran/check_in") ?>', {
                            antrian_id: id
                        }, function(res) {
                            r = JSON.parse(res);
                            if (r.success) {

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Check-in Berhasil',
                                    text: 'Pengunjung sudah hadir.',
                                    timer: 1200,
                                    showConfirmButton: false
                                }).then(() => {
                                    $("#modalManual").modal("hide");

                                    // Tunggu sebentar biar modal menutup smooth
                                    setTimeout(() => {
                                        location.reload();
                                    }, 500);
                                });

                            } else {
                                Swal.fire('Gagal', r.message, 'error');
                            }
                        });
                    }
                });
            }
        </script>


<?php
        echo ob_get_clean();
    }
}
