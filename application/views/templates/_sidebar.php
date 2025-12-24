        <!-- Menu -->

        <?php
        $role_id = $this->session->userdata('role_id');

        // Tentukan dashboard sesuai role
        if ($role_id == 1) {
            $dashboard_url = base_url('superadmin/dashboard');
        } elseif ($role_id == 2) {
            $dashboard_url = base_url('admin_layanan/dashboard');
        } elseif ($role_id == 3) {
            $dashboard_url = base_url('cs/dashboard');
            // kalau nama controllernya bukan cs/dashboard, ganti sesuai milik Anda
        } elseif ($role_id == 4) {
            $dashboard_url = base_url('masyarakat/dashboard');
        } else {
            $dashboard_url = base_url('dashboard'); // fallback jika tidak ada role
        }
        ?>

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <div class="app-brand demo">
                <a href="<?= $dashboard_url; ?>" class="app-brand-link">
                    <span class="app-brand-logo demo me-1">
                        <img src="<?= base_url('assets/img/logo.png') ?>"
                            alt="Logo Pemkab Rembang"
                            style="height:60px; width:auto; border-radius:4px; object-fit:contain;" />
                    </span>
                    <span class="app-brand-text demo menu-text fw-semibold ms-2">MPP</span>
                </a>

                <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                    <i class="menu-toggle-icon d-xl-inline-block align-middle"></i>
                </a>
            </div>

            <div class="menu-inner-shadow"></div>

            <ul class="menu-inner py-1">

                <!-- Dashboard -->
                <li class="menu-item">
                    <a href="<?= $dashboard_url; ?>" class="menu-link">
                        <i class="menu-icon ri ri-home-3-line"></i>
                        <div>Dashboard</div>
                    </a>
                </li>

                <?php if ($role_id == 1): // SUPERADMIN 
                ?>
                    <li class="menu-header">Superadmin</li>

                    <li class="menu-item">
                        <a href="<?= base_url('superadmin/users'); ?>" class="menu-link">
                            <i class="menu-icon ri ri-user-star-line"></i>
                            <div>Manajemen User</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="<?= base_url('superadmin/instansi'); ?>" class="menu-link">
                            <i class="menu-icon ri ri-government-line"></i>
                            <div>Instansi</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="<?= base_url('jenislayanan'); ?>" class="menu-link">
                            <i class="menu-icon ri ri-briefcase-2-line"></i>
                            <div>Jenis Layanan</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="<?= base_url('antrian_display'); ?>" class="menu-link">
                            <i class="menu-icon ri ri-tv-2-line"></i>
                            <div>Monitor Display</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="<?= base_url('pendaftaran/manual'); ?>" class="menu-link">
                            <i class="menu-icon ri ri-user-follow-line"></i>
                            <div>Front Desk</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="<?= base_url('pendaftaran/manual_v2'); ?>" class="menu-link">
                            <i class="menu-icon ri ri-group-line"></i>
                            <div>Front Desk V2</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="<?= base_url('superadmin/video_setting'); ?>" class="menu-link">
                            <i class="menu-icon ri ri-slideshow-line"></i>
                            <div>Pengaturan Video</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="<?= base_url('superadmin/laporan'); ?>" class="menu-link">
                            <i class="menu-icon ri ri-bar-chart-line"></i>
                            <div>Laporan</div>
                        </a>
                    </li>

                <?php elseif ($role_id == 2): // ADMIN LAYANAN 
                ?>
                    <li class="menu-header">Admin Layanan</li>

                    <li class="menu-item">
                        <a href="<?= base_url('admin_layanan/antrian_hari_ini'); ?>" class="menu-link">
                            <i class="menu-icon ri ri-time-line"></i>
                            <div>Antrian Hari Ini</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="<?= base_url('admin_layanan/riwayat_antrian'); ?>" class="menu-link">
                            <i class="menu-icon ri ri-file-list-3-line"></i>
                            <div>Riwayat Antrian</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="<?= base_url('admin_layanan/kelola_layanan'); ?>" class="menu-link">
                            <i class="menu-icon ri ri-settings-5-line"></i>
                            <div>Kelola Layanan</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="<?= base_url('admin_layanan/laporan'); ?>" class="menu-link">
                            <i class="menu-icon ri ri-pie-chart-2-line"></i>
                            <div>Laporan Antrian</div>
                        </a>
                    </li>

                <?php elseif ($role_id == 3): // CUSTOMER SERVICE 
                ?>
                    <li class="menu-header">Customer Service</li>

                    <li class="menu-item">
                        <a href="<?= base_url('cs/cek_antrian'); ?>" class="menu-link">
                            <i class="menu-icon ri ri-search-eye-line"></i>
                            <div>Cek Antrian</div>
                        </a>
                    </li>

                <?php elseif ($role_id == 4): // MASYARAKAT 
                ?>
                    <li class="menu-header">Layanan Publik</li>

                    <li class="menu-item">
                        <a href="<?= base_url('masyarakat/daftar_antrian'); ?>" class="menu-link">
                            <i class="menu-icon ri ri-add-box-line"></i>
                            <div>Daftar Antrian</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="<?= base_url('masyarakat/antrian_saya'); ?>" class="menu-link">
                            <i class="menu-icon ri ri-ticket-2-line"></i>
                            <div>Antrian Saya</div>
                        </a>
                    </li>


                    <li class="menu-item">
                        <a href="<?= base_url('masyarakat/riwayat_antrian'); ?>" class="menu-link">
                            <i class="menu-icon ri ri-history-line"></i>
                            <div>Riwayat Antrian</div>
                        </a>
                    </li>

                <?php endif; ?>

                <!-- Separator -->
                <li class="menu-header mt-3">Pengguna</li>

                <li class="menu-item">
                    <a href="<?= base_url('auth/logout'); ?>" class="menu-link">
                        <i class="menu-icon ri ri-logout-box-line"></i>
                        <div>Logout</div>
                    </a>
                </li>

            </ul>

        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">



            <!-- Navbar -->
            <nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
                id="layout-navbar">
                <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
                    <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                        <i class="icon-base ri ri-menu-line icon-md"></i>
                    </a>
                </div>


            </nav>

            <!-- / Navbar -->