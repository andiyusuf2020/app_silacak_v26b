<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="light">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="<?= base_url() ?>" class="brand-link">
            <!--begin::Brand Image-->
            <img
                src="<?= base_url() ?>cssportal/img_home/lampung.png"
                alt="AdminLTE Logo"
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
                    <a href="<?= base_url('lrfkadmin') ?>" class="nav-link active">
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
                            <a href="<?= base_url('adbang/opd_prov') ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Perangkat Daerah Provinsi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('adbang/kab_kota') ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Admin Kab/Kota</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('lrfkadmin/usersitapis') ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>User SiTAPIS</p>
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
                            <a href="<?= base_url('lrfkadmin/jadwal') ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Jadwal Input LRFK</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('adminprov/uploadsipd') ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Upload Realisasi SIPD</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('adminprov/uploadapbd') ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Upload Realisasi APBD</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('adminprov/uploadpendapatan') ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Upload Pendapatan APBD</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('adminprov/uploadrealrup') ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Upload Realisasi PBJ(RUP)</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('adminprov/uploadrup') ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Upload Data SiRUP</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-clipboard-fill"></i>
                        <p>
                            DATA LRFK
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('adminprov/pendapatan');
                                        ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Rekap Pendapatan APBD</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"> <a
                                href="<?= base_url('adminprov/apbdopd');
                                        ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>LRFK Rill</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"> <a
                                href="<?= base_url('adminprov/laporanapbdopd');
                                        ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Laporan APBD OPD(SIPD)</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('adminprov/angkasapbd') ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Angkas APBD</p>
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
                            <a href="<?= base_url('adminprov/ruppbj');
                                        ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Data RUP</p>
                            </a>
                        </li>
                    </ul>
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
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-pencil-square"></i>
                        <p>
                            DATA KINERJA
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <?php if (session()->get('tahun') == '2025') { ?>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= hash_url('adminprov', ['hal' => 'rekapcapkin', 'action' => 'all']);
                                            ?>" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>REKAPITULASI</p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= hash_url('adminprov', ['hal' => 'rekapperopd', 'action' => 'pilihperiode']);
                                            ?>" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>CAPAIAN PER-OPD</p>
                                </a>
                            </li>
                        </ul>
                    <?php } else { ?>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= hash_url('adminprov/capkin', ['hal' => 'rekapcapkin', 'action' => 'persasaran']);
                                            ?>" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>REKAP PER-SASARAN</p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= hash_url('adminprov/capkin', ['hal' => 'rekapcapkin', 'action' => 'peropd']);
                                            ?>" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>REKAP PER-OPD</p>
                                </a>
                            </li>
                        </ul>

                    <?php
                    } ?>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-pencil-square"></i>
                        <p>
                            LRFK Provinsi
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./forms/general.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Realisasi</p>
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