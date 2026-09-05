<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="light">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="./index.html" class="brand-link">
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
            <ul
                class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="menu"
                data-accordion="false">
                <li class="nav-item menu-open">
                    <a href="<?= base_url('lrfkopd') ?>" class="nav-link active">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>

                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-palette"></i>
                        <p>User
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a
                                href="<?= hash_url('lrfkopd/opd/', ['page' => 'profile', 'action' => 'edit']);
                                        ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Profile</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                href="<?= hash_url('lrfkopd/opd/', ['page' => 'profile', 'action' => 'edit']);
                                        ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Ubah Password</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-box-seam-fill"></i>
                        <p>
                            Data LRFK Pemb.
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <?php
                    $user = session()->get('user');
                    $tahun = session()->get('tahun');
                    if ($tahun == 2025) { ?>

                    <?php } else { ?>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"> <a
                                    href="<?= hash_url('lrfkopd/apbdopd', ['hal' => 'datapendapatan', 'action' => 'listall']) ?>"
                                    class="nav-link">

                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Data Pendapatan</p>
                                </a>
                            </li>
                        </ul>
                        <!-- <ul class="nav nav-treeview">
                            <li class="nav-item"> <a
                                    href="<?php // hash_url('lrfkopd/apbdopd', ['hal' => 'dataangkasapbd', 'action' => 'listall']) 
                                            ?>"
                                    class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Data Ang.Kas APBD</p>
                                </a>
                            </li>
                        </ul> -->
                    <?php }
                    ?>

                    <ul class="nav nav-treeview">
                        <li class="nav-item"> <a
                                href="<?= hash_url('lrfkopd/apbdopd', ['hal' => 'listsubkeg', 'action' => 'all']);
                                        ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>LRFK Rill</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-box-seam-fill"></i>
                        <p>
                            Data PBJ
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"> <a
                                href="<?= hash_url('lrfkopd/pbj', ['hal' => 'pbj', 'action' => 'rup']);
                                        ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>RUP</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"> <a
                                href="<?= hash_url('lrfkopd/pbj', ['hal' => 'pbj', 'action' => 'realisasi']);
                                        ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Realisasi PBJ</p>
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