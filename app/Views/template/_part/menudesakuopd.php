<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="light">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="<?= base_url('capkin') ?>" class="brand-link">
            <!--begin::Brand Image-->
            <img
                src="<?= base_url() ?>cssportal/img_home/lampung.png"
                alt="Lampung Logo"
                class="brand-image opacity-75 shadow" />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">SiTAPIS</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul
                class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="menu"
                data-accordion="false">
                <li class="nav-item menu-open">
                    <a href="<?= base_url('desakumaju/opd') ?>" class="nav-link active">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>

                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-box-seam-fill"></i>
                        <p>
                            Program Desaku <br>Maju TA 2026
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"> <a
                                href="<?= hash_url('desakumaju/opd/data', ['hal' => 'rencana', 'action' => 'mappingaktifitas']); //base_url('capkin/pendapatan?page=index') 
                                        ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Rencana <br>Aktifitas <br>
                                    Penunjang Desaku Maju
                                </p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item"> <a
                                href="<?= hash_url('desakumaju/opd/data', ['hal' => 'realisasi', 'action' => 'hasilmappingaktifitas']); //base_url('capkin/pendapatan?page=index') 
                                        ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Realisasi<br> Aktifitas<br>
                                    Penunjang Desaku Maju
                                </p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"> <a
                                href="<?= hash_url('desaku/opd/data', ['hal' => 'laporan', 'action' => 'all']); //base_url('capkin/pendapatan?page=index') 
                                        ?>" class="nav-link" target="_blank">

                                <i class="nav-icon bi bi-circle"></i>
                                <p>Cetak Capaian Program<br>Desaku Maju</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>