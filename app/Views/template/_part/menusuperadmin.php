<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="light">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="<?= base_url() ?>" class="brand-link">
            <!--begin::Brand Image-->
            <img
                src="<?= base_url() ?>cssportal/img_home/silacak3a.png"
                alt=""
                class="brand-image opacity-75 shadow" />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->

            <!--end::Brand Text-->
        </a>
        <br>
        <!-- <span class="brand-text fw-light">TUBABA</span> -->
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
                class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="menu"
                data-accordion="false">
                <li class="nav-item menu-open">
                    <a href="<?= base_url('superadmin') ?>" class="nav-link active">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>

                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-palette"></i>
                        <p>Manajemen User
                            <i class="nav-arrow bi bi-chevron-right"></i>

                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('superadmin/daftaruser') ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>User SILACAK</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-box-seam-fill"></i>
                        <p>
                            Data Master
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('superadmin/jadwal') ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Jadwal Input LRFK</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('superadmin/uploadapbd') ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Upload Data SIPD</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-clipboard-fill"></i>
                        <p>
                            DATA APBD
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('superadmin/pendapatan');
                                        ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Laporan LRA <br> BPKAD</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"> <a
                                href="<?= base_url('superadmin/laporanapbdopd');
                                        ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Laporan RFK <br> Perangkat Daerah</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-clipboard-fill"></i>
                        <p>
                            DATA PBJ
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('adminprov/realpbj');
                                        ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Data Realisasi PBJ</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header">MANAJEMEN OPD</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-clipboard-fill"></i>
                        <p>
                            DATA RFK OPD
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"> <a
                                href="<?= hash_url('lrfkopd/apbdopd', ['hal' => 'listsubkeg', 'action' => 'all']);
                                        ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>LRFK Rill</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"> <a
                                href="<?= hash_url('lrfkopd/laporanapbdopd', ['hal' => 'cetak', 'action' => 'all']);
                                        ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Laporan RFK <br> Perangkat Daerah</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header">MANAJEMEN BPKAD</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-clipboard-fill"></i>
                        <p>
                            DATA LRA BPKAD
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"> <a
                                href="<?= hash_url('lrfkopd/apbdopd', ['hal' => 'listsubkeg', 'action' => 'all']);
                                        ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Input LRA</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"> <a
                                href="<?= hash_url('lrfkopd/laporanapbdopd', ['hal' => 'cetak', 'action' => 'all']);
                                        ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Laporan LRA <br> Perangkat Daerah</p>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>