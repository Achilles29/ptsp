        <!-- Menu -->

        <?php
        $role_id = $this->session->userdata('role_id');
        ?>
        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <div class="app-brand demo">
                <a href="<?= base_url('dashboard'); ?>" class="app-brand-link">
                    <span class="app-brand-logo demo me-1">
                        <span class="text-primary">
                            <i class="ri ri-home-smile-line fs-2"></i>
                        </span>
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
                    <a href="<?= base_url('dashboard'); ?>" class="menu-link">
                        <i class="menu-icon ri ri-dashboard-line"></i>
                        <div>Dashboard</div>
                    </a>
                </li>

                <?php if ($role_id == 1): // Super Admin 
                ?>
                    <li class="menu-header">Superadmin</li>
                    <li class="menu-item"><a href="<?= base_url('superadmin/users'); ?>" class="menu-link"><i class="menu-icon ri ri-user-settings-line"></i>
                            <div>Manajemen User</div>
                        </a></li>
                    <li class="menu-item"><a href="<?= base_url('superadmin/instansi'); ?>" class="menu-link"><i class="menu-icon ri ri-folder-settings-line"></i>
                            <div>Instansi</div>
                        </a></li>
                    <li class="menu-item"><a href="<?= base_url('jenislayanan'); ?>" class="menu-link"><i class="menu-icon ri ri-folder-settings-line"></i>
                            <div>Jenis Layanan</div>
                        </a></li>
                    <li class="menu-item"><a href="<?= base_url('antrian_display'); ?>" class="menu-link"><i class="menu-icon ri ri-folder-settings-line"></i>
                            <div>Monitor</div>
                        </a></li>
                    <li class="menu-item"><a href="<?= base_url('pendaftaran/manual'); ?>" class="menu-link"><i class="menu-icon ri ri-folder-settings-line"></i>
                            <div>Front Desk</div>
                        </a></li>

                    <li class="menu-item"><a href="<?= base_url('superadmin/video_setting'); ?>" class="menu-link"><i class="menu-icon ri ri-folder-settings-line"></i>
                            <div>Pengaturan video</div>
                        </a></li>

                    <li class="menu-item"><a href="<?= base_url('superadmin/laporan'); ?>" class="menu-link"><i class="menu-icon ri ri-bar-chart-grouped-line"></i>
                            <div>Laporan</div>
                        </a></li>

                <?php elseif ($role_id == 2): // Admin Layanan 
                ?>
                    <li class="menu-header">Admin Layanan</li>
                    <li class="menu-item"><a href="<?= base_url('admin_layanan/antrian_hari_ini'); ?>" class="menu-link"><i class="menu-icon ri ri-list-check"></i>
                            <div>Antrian Hari Ini</div>
                        </a></li>
                    <li class="menu-item"><a href="<?= base_url('admin_layanan/riwayat_antrian'); ?>" class="menu-link"><i class="menu-icon ri ri-list-check"></i>
                            <div>Riwayat Antrian</div>
                        </a></li>

                    <li class="menu-item"><a href="<?= base_url('admin_layanan/kelola_layanan'); ?>" class="menu-link"><i class="menu-icon ri ri-list-check"></i>
                            <div>Kelola Layanan</div>
                        </a></li>
                    <li class="menu-item"><a href="<?= base_url('admin_layanan/laporan'); ?>" class="menu-link"><i class="menu-icon ri ri-clipboard-line"></i>
                            <div>Laporan Antrian</div>
                        </a></li>

                <?php elseif ($role_id == 3): // Customer Service Layanan 
                ?>
                    <li class="menu-header">Customer Service</li>
                    <li class="menu-item"><a href="<?= base_url('cs/cek_antrian'); ?>" class="menu-link"><i class="menu-icon ri ri-search-line"></i>
                            <div>Cek Antrian</div>
                        </a></li>

                <?php elseif ($role_id == 4): // Masyarakat 
                ?>
                    <li class="menu-header">Layanan Publik</li>
                    <li class="menu-item"><a href="<?= base_url('masyarakat/daftar_antrian'); ?>" class="menu-link"><i class="menu-icon ri ri-calendar-check-line"></i>
                            <div>Daftar Antrian</div>
                        </a></li>
                    <li class="menu-item"><a href="<?= base_url('masyarakat/riwayat_antrian'); ?>" class="menu-link"><i class="menu-icon ri ri-time-line"></i>
                            <div>Riwayat Antrian</div>
                        </a></li>
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

                <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
                    <!-- Search -->
                    <div class="navbar-nav align-items-center">
                        <div class="nav-item d-flex align-items-center">
                            <i class="icon-base ri ri-search-line icon-lg lh-0"></i>
                            <input type="text" class="form-control border-0 shadow-none" placeholder="Search..."
                                aria-label="Search..." />
                        </div>
                    </div>
                    <!-- /Search -->

                    <ul class="navbar-nav flex-row align-items-center ms-md-auto">
                        <!-- Place this tag where you want the button to render. -->
                        <li class="nav-item lh-1 me-4">
                            <a class="github-button"
                                href="https://github.com/themeselection/materio-bootstrap-html-admin-template-free"
                                data-icon="octicon-star" data-size="large" data-show-count="true"
                                aria-label="Star themeselection/materio-html-admin-template-free on GitHub">Star</a>
                        </li>

                        <!-- User -->
                        <li class="nav-item navbar-dropdown dropdown-user dropdown">
                            <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
                                data-bs-toggle="dropdown">
                                <div class="avatar avatar-online">
                                    <img src="../assets/img/avatars/1.png" alt="alt" class="rounded-circle" />
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar avatar-online">
                                                    <img src="../assets/img/avatars/1.png" alt="alt"
                                                        class="w-px-40 h-auto rounded-circle" />
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">John Doe</h6>
                                                <small class="text-body-secondary">Admin</small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <div class="dropdown-divider my-1"></div>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="icon-base ri ri-user-line icon-md me-3"></i>
                                        <span>My Profile</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="icon-base ri ri-settings-4-line icon-md me-3"></i>
                                        <span>Settings</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <span class="d-flex align-items-center align-middle">
                                            <i class="flex-shrink-0 icon-base ri ri-bank-card-line icon-md me-3"></i>
                                            <span class="flex-grow-1 align-middle ms-1">Billing Plan</span>
                                            <span class="flex-shrink-0 badge rounded-pill bg-danger">4</span>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <div class="dropdown-divider my-1"></div>
                                </li>
                                <li>
                                    <div class="d-grid px-4 pt-2 pb-1">
                                        <a class="btn btn-danger d-flex" href="/auth/logout">
                                            <small class="align-middle">Logout</small>
                                            <i class="ri ri-logout-box-r-line ms-2 ri-xs"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!--/ User -->
                    </ul>
                </div>
            </nav>

            <!-- / Navbar -->