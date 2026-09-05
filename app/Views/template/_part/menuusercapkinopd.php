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
            <!--begin::Sidebar Menu-->
            <?php
            $tahun = session()->get('tahun');
            if ($tahun == 2026) { ?>
                <ul
                    class="nav sidebar-menu flex-column"
                    data-lte-toggle="treeview"
                    role="menu"
                    data-accordion="false">
                    <li class="nav-item menu-open">
                        <a href="<?= base_url('capkin') ?>" class="nav-link active">
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
                                Data Aktifitas TA 2026
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"> <a
                                    href="<?= hash_url('capkin2026', ['hal' => 'rencana', 'action' => 'mappingsubgiat']); //base_url('capkin/pendapatan?page=index') 
                                            ?>" class="nav-link">

                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Rencana Aktifitas</p>
                                </a>
                            </li>
                        </ul>

                        <ul class="nav nav-treeview">
                            <li class="nav-item"> <a
                                    href="<?= hash_url('capkin2026', ['hal' => 'realisasi', 'action' => 'hasilmappingsubgiat']); //base_url('capkin/pendapatan?page=index') 
                                            ?>" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Realisasi Aktifitas</p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"> <a
                                    href="<?= hash_url('capkin2026', ['hal' => 'cetaksasaran', 'action' => 'all']); //base_url('capkin/pendapatan?page=index') 
                                            ?>" class="nav-link" target="_blank">

                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Cetak Capaian Kinerja <br>Program Sasaran</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            <?php } ?>

            <!--end::Sidebar Menu-->
            <?php
            $tahun = session()->get('tahun');
            if ($tahun == 2025) {
            ?>
                <ul
                    class="nav sidebar-menu flex-column"
                    data-lte-toggle="treeview"
                    role="menu"
                    data-accordion="false">
                    <li class="nav-item menu-open">
                        <a href="<?= base_url('capkin') ?>" class="nav-link active">
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
                                Data Aktifitas TA 2025
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"> <a
                                    href="<?= hash_url('capkin', ['page' => 'dashboard', 'action' => 'listall']); //base_url('capkin/pendapatan?page=index') 
                                            ?>" class="nav-link">

                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Data Aktifitas</p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"> <a
                                    href="<?= hash_url('capkin', ['page' => 'cetak', 'action' => 'sasaran']); //base_url('capkin/pendapatan?page=index') 
                                            ?>" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Laporan Sasaran Program Prioritas</p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"> <a
                                    href="<?= hash_url('capkin', ['page' => 'cetak', 'action' => 'unggulan']); //base_url('capkin/pendapatan?page=index') 
                                            ?>" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Laporan Program Unggulan</p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"> <a
                                    href="<?= hash_url('capkin', ['page' => 'cetak', 'action' => 'tematik']); //base_url('capkin/pendapatan?page=index') 
                                            ?>" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Laporan Tematik</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            <?php } ?>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>