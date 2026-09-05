<?php

namespace App\Controllers\AdminAdbang;

use App\Models\DataApbdModel\AngkasModel;
use App\Models\DataApbdModel\PendApbdModel;
use \Myth\Auth\Authorization\GroupModel;
use App\Models\LrfkProvModel\JadwalModel;
use App\Models\UserModel\TaUserModel;

use App\Models\RupModel\RealRupModel;
use App\Models\RupModel\SirupModel;
use App\Models\CapkinModel\TaMProgPrioritasModel;

use App\Models\CapkinModel\TaProgUnggulan;
use App\Models\CapkinModel\TaKegPokokCapkinModel;
use App\Models\CapkinModel\TaSubKegCapkin2026;

use App\Models\DataApbdModel\RealApbdModel;
use App\Models\DataApbdModel\TglApbdModel;
use App\Models\ExcelRSipdModel;
use App\Models\CapkinModel\TaKategoriModel;
use App\Models\CapkinModel\TaRKegPokokCapkinModel;

use App\Libraries\PdfLibrary;
use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;
use FontLib\Table\Type\post;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;
use TCPDF;

class adminadbang26 extends BaseController
{

    protected $jadwalmodel;
    protected $tausermodel;
    protected $realrupmodel;
    protected $sirupmodel;
    protected $realapbdmodel;
    protected $tglapbdmodel;
    protected $pendapbdmodel;
    protected $angkapbdmodel;
    protected $sipdmodel;
    protected $tcpdfConfig;

    protected $db;

    protected $pager;
    protected $progprioritas;
    protected $kegpokokmodal;
    protected $kategorimodel;
    protected $rdkegpokokmodal;

    protected $lokasiModel;

    protected $subkegcapkin2026model;

    public function __construct()
    {
        // helper(['form']);
        $this->tausermodel = new TaUserModel();
        $this->jadwalmodel = new JadwalModel();
        $this->realrupmodel = new RealRupModel();
        $this->sirupmodel = new SirupModel();
        $this->realapbdmodel = new RealApbdModel();
        $this->pendapbdmodel = new PendApbdModel();
        $this->tglapbdmodel = new TglApbdModel();
        $this->angkapbdmodel = new AngkasModel();
        $this->sipdmodel = new ExcelRSipdModel();

        $this->kategorimodel = new TaKategoriModel();
        $this->rdkegpokokmodal = new TaRKegPokokCapkinModel();

        $this->progprioritas = new TaMProgPrioritasModel();
        $this->kegpokokmodal = new TaKegPokokCapkinModel();
        $this->subkegcapkin2026model = new TaSubKegCapkin2026();

        $this->tcpdfConfig = new \Config\Tcpdf();
        helper(['form', 'url', 'filesystem']);
        $this->db = db_connect();

        $this->pager = \Config\Services::pager();
        if (logged_in()) {
            $user = user();
            $groupModel = new GroupModel();
            $groupuser = $groupModel->getGroupsForUser($user->id);
            foreach ($groupuser as $row) {
                $namagroup = $row['name'];
            }
        }
        if ($namagroup != 'adminprov' && $namagroup != 'superadmin' && $namagroup != 'adminadbang') {
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini.');
            return redirect()->to(base_url('login'));
        }
    }
    public function laporanapbdopd()
    {
        if (logged_in()) {
            $user = user();
            $groupModel = new GroupModel();
            $groupuser = $groupModel->getGroupsForUser($user->id);
            foreach ($groupuser as $row) {
                $namagroup = $row['name'];
            }
        }

        $req = $this->request;
        // Contoh penggunaan parameter
        $receivedParams = $req->getGet();
        $hal = $receivedParams['hal'] ?? null;
        $action = $receivedParams['action'] ?? null;
        $periode = $receivedParams['periode'] ?? null;
        $kdA = $receivedParams['kdA'] ?? null;
        $kdSK = $receivedParams['kdSK'] ?? null;
        $tgldata = $receivedParams['tgldata'] ?? null;
        $blndata = $receivedParams['blndata'] ?? null;
        $tgldataopd = $receivedParams['tgldataopd'] ?? null;

        $kdSU = $receivedParams['kdSU'] ?? null;
        $kdU = $receivedParams['kdU'] ?? null;
        $bulan = $receivedParams['bulan'] ?? null;
        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        $tahunaktif = session()->get('tahun'); //$jadwalaktif['tahun'];
        if ($tahunaktif == '2025') {
            $bulanaktif = 'Desember';
        } else {
            $bulanaktif = $jadwalaktif['bulan'];
        }
        $tahunaktif =  session()->get('tahun'); //   $jadwalaktif['tahun'];
        $tglaktif = session()->get('tglaktif');

        $data = [
            'groupuser' => $namagroup,
            'groupmenu' => 'adminprov',
            'titlepage' => 'Selamat Datang di Administrator SiTAPIS Provinsi Lampung',
            'datauser'  => $this->tausermodel->listuser($user->id),
            'tahunaktif'     => $tahunaktif,
            'bulan'     => $bulanaktif,
            'jadwalaktif' => $jadwalaktif,
            'periode' => $periode,
            'tglaktif' => $tglaktif,
            'nama_opd' => $kdSU,
            'tglapbd' => $this->sipdmodel->tgldata(),
            'tgldata' => $tgldata,
        ];

        if (!$receivedParams) {
            // echo dd($tahunaktif . '-' . $bulanaktif . '-' . $tglaktif);
            // echo dd($data['tglapbd']);
            return view('superadmin/2026/listtgldatalaporan', $data);
        }
        if ($tgldata) {
            // $data['tgldata'] = $tgldata;
            $data['dataopdadmin'] = $this->sipdmodel->listopdadmin($tgldata);
            // echo dd($data['dataopdadmin']);
            return view('superadmin/2026/listopdapbdlaporan', $data);
        }
    }
    public function index()
    {
        if (logged_in()) {
            $user = user();
            $groupModel = new GroupModel();
            $groupuser = $groupModel->getGroupsForUser($user->id);
            foreach ($groupuser as $row) {
                $namagroup = $row['name'];
            }
        }
        $req = $this->request;
        // Contoh penggunaan parameter

        // $this->ValidasiHash($req);

        $receivedParams = $req->getGet();
        $hal = $receivedParams['hal'] ?? null;
        $action = $receivedParams['action'] ?? null;
        $periode = $receivedParams['periode'] ?? null;
        $idSasaran = $receivedParams['idSasaran'] ?? null;
        $kdSK = $receivedParams['kdSK'] ?? null;
        $tgldata = $receivedParams['tgldata'] ?? null;
        $blndata = $receivedParams['blndata'] ?? null;
        $tgldataopd = $receivedParams['tgldataopd'] ?? null;

        $kdSU = $receivedParams['kdSU'] ?? null;
        $nmSU = $receivedParams['nmSU'] ?? null;
        $kdU = $receivedParams['kdU'] ?? null;
        $bulan = $receivedParams['bulan'] ?? null;
        $idD = $receivedParams['idD'] ?? null;


        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        $tahunaktif = session()->get('tahun'); //$jadwalaktif['tahun'];
        if ($tahunaktif == '2025') {
            $bulanaktif = 'Desember';
        } else {
            $bulanaktif = $jadwalaktif['bulan'];
        }
        $tahunaktif =  session()->get('tahun'); //   $jadwalaktif['tahun'];
        $tglaktif = session()->get('tglaktif');
        $groupname = session()->get('groupuser');
        $data = [
            'groupuser' => $namagroup,
            'groupmenu' => 'adminprov',
            'titlepage' => 'Selamat Datang di Administrator SiTAPIS Provinsi Lampung',
            'datauser'  => $this->tausermodel->listuser($user->id),
            'tahunaktif'     => $tahunaktif,
            'bulan'     => $bulanaktif,
            'jadwalaktif' => $jadwalaktif,
            'periode' => $periode,
            'tglaktif' => $tglaktif,
            'kdSU' => $kdSU,
            'nama_opd' => $nmSU,
            'dataopd' => $this->realapbdmodel->listopdadmin($tglaktif, $nmSU),
            'kategoriList' => $this->kategorimodel->listkategori(), //$this->lokasiModel->getKategori(),

        ];
        $data['tglapbd'] = $this->realapbdmodel->tgldata();
        $data['tglapbdaktif'] = $this->tglapbdmodel->tgldataaktif();

        if (!$tahunaktif) {
            session()->set('groupuser', $namagroup);
            session()->set('groupmenu', 'adminprov');
            return view('pilihtahun', $data);
        }
        $data['dataopd'] = $this->realapbdmodel->listopd();
        if (!$receivedParams) {
            // echo dd($tahunaktif . '-' . $bulanaktif . '-' . $tglaktif);
            return view('superadmin/2026/listtgldata', $data);
        }
        if ($tgldata) {
            $this->ValidasiHash($req);

            $data['tgldata'] = $tgldata;
            $data['dataopdadmin'] = $this->realapbdmodel->listopdadmin($tgldata);
            // echo dd($data['dataopdadmin']);
            return view('superadmin/2026/listopdapbd', $data);
        }
        if ($hal == 'rekapcapkin' && $action == 'persasaran') {
            $this->ValidasiHash($req);
            $data['dataopd'] = $this->realapbdmodel->listopd();
            $data['dataprogprioritas'] = $this->progprioritas->findAll();
            $data['opdperprioritas'] = $this->kegpokokmodal->DataPerIdPrio($idSasaran, $tahunaktif);

            if ($idSasaran) {
                // echo dd($data['opdperprioritas']);
                return view('superadmin/2026/listsopdpersasaran', $data);
            } else {
                return view('superadmin/2026/listsasaran', $data);
            }
            // echo dd($data['dataprogprioritas']);

        }
        if ($hal == 'rekapcapkin' && $action == 'peropd') {
            $this->ValidasiHash($req);
            $data['dataopdadmin'] = $this->realapbdmodel->listopdadmin($tglaktif);
            $data['dataprogprioritas'] = $this->progprioritas->findAll();
            // $data['opdperprioritas'] = $this->kegpokokmodal->DataPerIdPrio($idSasaran, $tahunaktif);
            if ($kdSU) {
                // echo dd($data['data?Sopd']);
                return view('superadmin/2026/listsubgiatpersasaran', $data);
            }
            // echo dd($tglaktif);
            // echo dd($data['dataopdadmin']);
            return view('superadmin/2026/listopdallcapkin', $data);
        }
        $kategori = $this->request->getVar('kategori');
        $keyword = $this->request->getVar('keyword');
        $nmSU = $this->request->getVar('nmSU');
        $kodeSU = $this->request->getVar('kdSU');
        if ($kategori) {
            if ($tahunaktif == 2025) {
                $data['lokasi'] = $this->rdkegpokokmodal->perkategori($kategori, $tahunaktif, $kodeSU);
            } else {
                $data['lokasi'] = $this->rdkegpokokmodal->perkategori26($kategori, $tahunaktif, $kodeSU);
            }
            // echo dd($data['lokasi']);
            return view('superadmin/2026/listdokumentasiopd', $data);
        }
        if ($keyword) {
            if ($tahunaktif == 2025) {
                $data['lokasi'] = $this->rdkegpokokmodal->perkeyword($keyword, $tahunaktif, $kodeSU);
            } else {
                $data['lokasi'] = $this->rdkegpokokmodal->perkeyword26($keyword, $tahunaktif, $kodeSU);
            }
            // echo dd($data['lokasi']);
            return view('superadmin/2026/listdokumentasiopd', $data);
        }
        if ($idD) {
            if ($tahunaktif == 2025) {
                $data = [
                    'title' => 'Detail Lokasi',
                    'lokasi' => $this->rdkegpokokmodal->getLokasi($tahunaktif, $kodeSU, $idD)
                ];
                // $data['lokasi'] = $this->rdkegpokokmodal->getLokasi($tahundata, $data['datauser']['kd_sub_unit'], $id_dr = false);
            } else {
                $data = [
                    'title' => 'Detail Lokasi',
                    'lokasi' => $this->rdkegpokokmodal->getLokasi26($tahunaktif, $kodeSU, $idD)
                ];
                // $data['lokasi'] = $this->rdkegpokokmodal->getLokasi26($tahundata, $data['datauser']['kd_sub_unit'], $id_dr = false);
            }

            // echo dd($data);
            if (empty($data['lokasi'])) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Lokasi dengan ID ' . $idD . ' tidak ditemukan');
            }
            // echo dd($data['lokasi']);
            // return view('capkin/v_detaildok', $data);
            return view('superadmin/2026/v_detaildokopd', $data);
        }

        if ($hal == 'rekapcapkin' && $action == 'detaildokumentasi') {
            $this->ValidasiHash($req);
            // $kategori = $receivedParams['kategori'] ?? null;
            // $kategori = $this->request->getVar('kategori');
            // $keyword = $this->request->getVar('keyword');
            if ($tahunaktif == 2025) {
                $data['lokasi'] = $this->rdkegpokokmodal->getLokasi($tahunaktif, $kdSU, $id_dr = false);
            } else {
                $data['lokasi'] = $this->rdkegpokokmodal->getLokasi26($tahunaktif, $kdSU, $id_dr = false);
            }

            return view('superadmin/2026/listdokumentasiopd', $data);
        }
        if ($hal == 'rekapcapkin' && $action == 'Cetak') {
            $this->ValidasiHash($req);
            // $data = [
            //     'groupuser' => $groupname,
            //     'groupmenu' => 'usercapkinprov',
            //     'titlepage' => 'Selamat Data di e-TAPIS Laporan Aktifitas Program Kegiatan Perangkat Daerah Provinsi Lampung',
            //     'datauser' => $this->tausermodel->listuser($user->id),
            //     // 'jadwalaktif' => $this->jadwalmodel->jadwalaktifskrg(),
            //     'tahunaktif' => $tahunaktif,
            //     'tglaktif' => $tglaktif,
            //     'bulan' => $bulanaktif,
            //     'subkegcapkindipilih' => $this->subkegcapkin2026model->find($id_subgiatmapping),
            //     'tahun' => $tahunaktif,
            //     'id_subgiatmapping' => $id_subgiatmapping,
            //     'idKP'    => $idKP
            // ];
            $data['tahun'] = $tahunaktif;
            $data['datauser'] = [
                'sub_unit' => $nmSU,
                'kd_sub_unit' => $kdSU,
            ];
            $data['sasaranopd'] = $this->kegpokokmodal->DataPerSasaranPerOPD26($tahunaktif, $kdSU);
            // echo dd($data['sasaranopd']);

            // $data['tahunaktif'] = $tahunaktif;
            // $data['bulantw'] = $periode;
            // $data['bulan'] = $bulanaktif;
            $html = view('/superadmin/2026/cetakcapkinopd', $data);

            $pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Biro Administrasi Pembangunan');
            $pdf->SetTitle('Laporan SiTAPIS Perangkat Daerah');
            $pdf->SetSubject('Laporan');
            $pdf->SetHeaderData(
                PDF_HEADER_LOGO,
                PDF_HEADER_LOGO_WIDTH,
                PDF_HEADER_TITLE . $data['datauser']['sub_unit'],
                PDF_HEADER_STRING .
                    'Sistem Data Pengendalian dan Informasi (SiTAPIS)',
                array(1, 64, 255),
                array(1, 64, 100)
            );
            // $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT, false);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->setPrintHeader(true);
            $pdf->setPrintFooter(true);
            $pdf->addPage();
            // output the HTML content
            $pdf->writeHTML($html, true, true, true, false, '');
            //line ini penting
            $this->response->setContentType('application/pdf');
            //Close and output PDF document
            $pdf->Output('Laporan_Sasaran' . $tahunaktif . '_' . $data['datauser']['sub_unit'], 'I');
        }
        if ($hal == 'cetakrekap') {
            $this->ValidasiHash($req);
            if ($action == 'all') {
                $jadwalall = $this->jadwalmodel->findAll();
                $data['datajadwal'] =  $jadwalall;
                if (!$bulan) {
                    $data['bulanpilih'] = '';
                } else {
                    $data['bulanpilih'] = $bulan;
                }
                $datauser = $this->tausermodel->listuser($user->id);
                $data['datauser'] = $datauser;
                // echo dd($data['datauser']);
                $html = view('/superadmin/2026/CetakRekapLrfk', $data);
                $pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('Biro Administrasi Pembangunan');
                $pdf->SetTitle('Laporan RFK Perangkat Daerah');
                $pdf->SetSubject('Laporan');
                $pdf->SetHeaderData(
                    PDF_HEADER_LOGO,
                    PDF_HEADER_LOGO_WIDTH,
                    PDF_HEADER_TITLE . 'PEMERINTAH PROVINSI LAMPUNG',
                    PDF_HEADER_STRING .
                        'Sistem Data Pengendalian dan Informasi (SiTAPIS)',
                    array(1, 64, 255),
                    array(1, 64, 100)
                );
                $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                $pdf->setPrintHeader(true);
                $pdf->setPrintFooter(true);
                $pdf->addPage();
                // output the HTML content
                $pdf->writeHTML($html, true, true, true, false, '');
                //line ini penting
                $this->response->setContentType('application/pdf');
                //Close and output PDF document
                $pdf->Output('Rekap_LRFK_OPD' . $tahunaktif . '_' . $tglaktif, 'I');
            }
            if ($action == 'pendapatan') {
                $data['opdpendapatan'] = $this->pendapbdmodel->getDataOpdPendapatan($tahunaktif);
                // echo dd($kdSU . '-' . $tahunaktif . '-' . $bulanaktif . '-' . $tglaktif);
                // echo dd($data['opdpendapatan']);
                $html = view('/superadmin/2026/CetakRekapPendapatanOpd', $data);
                $pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('Biro Administrasi Pembangunan');
                $pdf->SetTitle('Laporan Pendapatan APBD Perangkat Daerah');
                $pdf->SetSubject('Laporan');
                $pdf->SetHeaderData(
                    PDF_HEADER_LOGO,
                    PDF_HEADER_LOGO_WIDTH,
                    PDF_HEADER_TITLE . 'PEMERINTAH PROVINSI LAMPUNG',
                    PDF_HEADER_STRING .
                        'Sistem Data Pengendalian dan Informasi (SiTAPIS)',
                    array(1, 64, 255),
                    array(1, 64, 100)
                );
                $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                $pdf->setPrintHeader(true);
                $pdf->setPrintFooter(true);
                $pdf->addPage();
                // output the HTML content
                $pdf->writeHTML($html, true, true, true, false, '');
                //line ini penting
                $this->response->setContentType('application/pdf');
                //Close and output PDF document
                $pdf->Output('Rekap_Pendapatan_OPD_' . esc($data['opdpendapatan'][0]['NAMA_OPD']) . '_' . $tahunaktif . '_' . $tglaktif, 'I');
            }
            if ($action == 'realisasirup') {
                $data['subunit'] = null; //$datauser['sub_unit']; //'DINAS PENDIDIKAN DAN KEBUDAYAAN'; //
                $data['realisasirup'] = $this->realrupmodel->realrupopd($tahunaktif, $data['subunit'])->getResultArray();
                $data['dataruppermetode'] = $this->sirupmodel->rupopdmetode($tahunaktif, $data['subunit'])->getResultArray();
                $data['datarealpermetode'] = $this->realrupmodel->realrupopdmetode($tahunaktif, $data['subunit'])->getResultArray();
                // echo dd($data['datarealpermetode']);
                $html = view('/superadmin/2026/CetakRekapRealisasiRupOpd', $data);
                $pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('Biro Administrasi Pembangunan');
                $pdf->SetTitle('Laporan Realisasi PBJ Perangkat Daerah');
                $pdf->SetSubject('Laporan');
                $pdf->SetHeaderData(
                    PDF_HEADER_LOGO,
                    PDF_HEADER_LOGO_WIDTH,
                    PDF_HEADER_TITLE . 'PEMERINTAH PROVINSI LAMPUNG',
                    PDF_HEADER_STRING .
                        'Sistem Data Pengendalian dan Informasi (SiTAPIS)',
                    array(1, 64, 255),
                    array(1, 64, 100)
                );
                $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                $pdf->setPrintHeader(true);
                $pdf->setPrintFooter(true);
                $pdf->addPage();
                // output the HTML content
                $pdf->writeHTML($html, true, true, true, false, '');
                //line ini penting
                $this->response->setContentType('application/pdf');
                //Close and output PDF document
                $pdf->Output('Rekap_RealisasiPBJ_' . $tahunaktif . '_' . $data['bulan'], 'I');
                // return view('lrfk/opd/ver26/cetak_realisasi_rup', $data);
            }
        }
        if ($hal == 'detail') {
            $this->ValidasiHash($req);
            if ($action == 'opd') {
                $data['dataopd'] = $this->realapbdmodel->rpersk($kdSU, $tahunaktif, $blndata, $tgldataopd);
                // echo dd($kdSU . '-' . $tahunaktif . '-' . $bulanaktif . '-' . $tglaktif);
                // echo dd($data['dataopd']);
                return view('superadmin/2026/detailopdapbd', $data);
            }
            if ($action == 'detailopd') {
                $data['dataopd'] = $this->realapbdmodel->rperrso2($kdSU, $kdSK, $tahunaktif, $tgldataopd)->getResultArray();

                // echo dd($kdSU . '-' . $kdSK . '-' . $tahunaktif . '-' . $tglaktif);
                // echo dd($data['dataopd']);
                return view('superadmin/2026/detailopdsro', $data);
            }
        }
    }
    public function pendapatan()
    {
        if (logged_in()) {
            $user = user();
            $groupModel = new GroupModel();
            $groupuser = $groupModel->getGroupsForUser($user->id);
            foreach ($groupuser as $row) {
                $namagroup = $row['name'];
            }
        }
        $req = $this->request;
        // Contoh penggunaan parameter
        $receivedParams = $req->getGet();
        $hal = $receivedParams['hal'] ?? null;
        $action = $receivedParams['action'] ?? null;
        $periode = $receivedParams['periode'] ?? null;
        $kdA = $receivedParams['kdA'] ?? null;
        $kdSK = $receivedParams['kdSK'] ?? null;
        $kdSU = $receivedParams['kdSU'] ?? null;
        $kdU = $receivedParams['kdU'] ?? null;
        $bulan = $receivedParams['bulan'] ?? null;

        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        $tahunaktif = session()->get('tahun'); //$jadwalaktif['tahun'];
        if ($tahunaktif == '2025') {
            $bulanaktif = 'Desember';
        } else {
            $bulanaktif = $jadwalaktif['bulan'];
        }
        $tahunaktif =  session()->get('tahun'); //   $jadwalaktif['tahun'];
        $tglaktif = session()->get('tglaktif');
        $groupname = session()->get('groupuser');
        $data = [
            'groupuser' => $namagroup,
            'groupmenu' => 'adminprov',
            'titlepage' => 'Selamat Datang di Administrator SiTAPIS Provinsi Lampung',
            'datauser'  => $this->tausermodel->listuser($user->id),
            'tahunaktif'     => $tahunaktif,
            'bulan'     => $bulanaktif,
            'jadwalaktif' => $jadwalaktif,
            'periode' => $periode,
            'tglaktif' => $tglaktif,
            'nama_opd' => $kdSU
        ];
        if (!$tahunaktif) {
            session()->set('groupuser', $namagroup);
            session()->set('groupmenu', 'adminprov');
            return view('pilihtahun', $data);
        }

        $data['opdpendapatan'] = $this->pendapbdmodel->getDataOpdPendapatan($tahunaktif);
        if (!$receivedParams) {
            // echo dd($data['opdpendapatan']);
            return view('superadmin/2026/lisopdpendapatan', $data);
        }
    }
    public function angkasapbd()
    {
        if (logged_in()) {
            $user = user();
            $groupModel = new GroupModel();
            $groupuser = $groupModel->getGroupsForUser($user->id);
            foreach ($groupuser as $row) {
                $namagroup = $row['name'];
            }
        }
        $req = $this->request;
        // Contoh penggunaan parameter
        $receivedParams = $req->getGet();
        $hal = $receivedParams['hal'] ?? null;
        $action = $receivedParams['action'] ?? null;
        $periode = $receivedParams['periode'] ?? null;
        $kdA = $receivedParams['kdA'] ?? null;
        $kdSK = $receivedParams['kdSK'] ?? null;
        $kdSU = $receivedParams['kdSU'] ?? null;
        $kdU = $receivedParams['kdU'] ?? null;
        $bulan = $receivedParams['bulan'] ?? null;

        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        $tahunaktif = session()->get('tahun'); //$jadwalaktif['tahun'];
        if ($tahunaktif == '2025') {
            $bulanaktif = 'Desember';
        } else {
            $bulanaktif = $jadwalaktif['bulan'];
        }
        $tahunaktif =  session()->get('tahun'); //   $jadwalaktif['tahun'];
        $tglaktif = session()->get('tglaktif');
        $groupname = session()->get('groupuser');
        $data = [
            'groupuser' => $namagroup,
            'groupmenu' => 'adminprov',
            'titlepage' => 'Selamat Datang di Administrator SiTAPIS Provinsi Lampung',
            'datauser'  => $this->tausermodel->listuser($user->id),
            'tahunaktif'     => $tahunaktif,
            'bulan'     => $bulanaktif,
            'jadwalaktif' => $jadwalaktif,
            'periode' => $periode,
            'tglaktif' => $tglaktif,
            'nama_opd' => ''
        ];
        if (!$tahunaktif) {
            session()->set('groupuser', $namagroup);
            session()->set('groupmenu', 'adminprov');
            return view('pilihtahun', $data);
        }
        $data['listopdangkasapbd'] = $this->angkapbdmodel->getDataAngkaOpd($tahunaktif);
        $data['listbulan'] = $this->jadwalmodel->getlist();

        if (!$receivedParams) {
            // echo dd($data['listopdangkasapbd']);
            return view('superadmin/2026/listangkasopd', $data);
        }
        if ($hal == 'angkasopd') {
            $this->ValidasiHash($req);
            if ($action == 'detail') {
                $data['dataopd'] = $this->realapbdmodel->rpersk($kdSU, $tahunaktif, $bulanaktif, $tglaktif);
                // $data['dataangkassubgiatopd'] = $this->angkapbdmodel->getDataAngkasSubGiat($tahunaktif, $kdSU);
                // $data['subgiat'] = $this->realapbdmodel->rpersk($kdSU, $tahunaktif, $bulan, $tglaktif);
                // echo dd($kdSU . '-' . $tahunaktif . '-' . $bulanaktif . '-' . $tglaktif);
                // echo dd($data['subgiat']);
                return view('superadmin/2026/detailangkasopd', $data);
            }
        }
    }
    public function ruppbj()
    {
        if (logged_in()) {
            $user = user();
            $groupModel = new GroupModel();
            $groupuser = $groupModel->getGroupsForUser($user->id);
            foreach ($groupuser as $row) {
                $namagroup = $row['name'];
            }
        }
        $req = $this->request;
        // Contoh penggunaan parameter
        $receivedParams = $req->getGet();
        $hal = $receivedParams['hal'] ?? null;
        $action = $receivedParams['action'] ?? null;
        $periode = $receivedParams['periode'] ?? null;
        $kdA = $receivedParams['kdA'] ?? null;
        $kdSK = $receivedParams['kdSK'] ?? null;
        $kdSU = $receivedParams['kdSU'] ?? null;
        $kdU = $receivedParams['kdU'] ?? null;
        $bulan = $receivedParams['bulan'] ?? null;

        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        $tahunaktif = session()->get('tahun'); //$jadwalaktif['tahun'];
        if ($tahunaktif == '2025') {
            $bulanaktif = 'Desember';
        } else {
            $bulanaktif = $jadwalaktif['bulan'];
        }
        $tahunaktif =  session()->get('tahun'); //   $jadwalaktif['tahun'];
        $tglaktif = session()->get('tglaktif');
        $groupname = session()->get('groupuser');
        $data = [
            'groupuser' => $namagroup,
            'groupmenu' => 'adminprov',
            'titlepage' => 'Selamat Datang di Administrator SiTAPIS Provinsi Lampung',
            'datauser'  => $this->tausermodel->listuser($user->id),
            'tahunaktif'     => $tahunaktif,
            'bulan'     => $bulanaktif,
            'jadwalaktif' => $jadwalaktif,
            'periode' => $periode,
            'tglaktif' => $tglaktif,
            'nama_opd' => $kdSU
        ];
        if (!$tahunaktif) {
            session()->set('groupuser', $namagroup);
            session()->set('groupmenu', 'adminprov');
            return view('pilihtahun', $data);
        }

        $data['opdpendapatan'] = $this->pendapbdmodel->getDataOpdPendapatan($tahunaktif);
        if (!$receivedParams) {
            $subunit = 'DINAS PENDIDIKAN DAN KEBUDAYAAN'; //
            //data sub rincian objek belanja per sub kegiatan per OPD
            // echo dd($tahunaktif . '-' . $bulanaktif);
            $data['datarupopd'] = $this->sirupmodel->rupopdall($tahunaktif, $bulanaktif)->getResultArray();
            // echo dd($data['datarupopd']);
            $data['datarupopdpenyedia'] = $this->sirupmodel->rupopdcara($tahunaktif, $bulanaktif, null, 'Penyedia');
            $data['datarupopdswakelola'] = $this->sirupmodel->rupopdcara($tahunaktif, $bulanaktif, null, 'Swakelola');
            $data['datarupopdkatalog'] = $this->sirupmodel->rupopdmetoda($tahunaktif, $bulanaktif, null, 'E-Purchasing');
            $data['datarupopdpl'] = $this->sirupmodel->rupopdmetoda($tahunaktif, $bulanaktif, null, 'Pengadaan Langsung');
            $data['datarupopdkecuali'] = $this->sirupmodel->rupopdmetoda($tahunaktif, $bulanaktif, null, 'Dikecualikan');
            $data['datarupopdtunjuk'] = $this->sirupmodel->rupopdmetoda($tahunaktif, $bulanaktif, null, 'Penunjukan Langsung');
            $data['datarupopdtender'] = $this->sirupmodel->rupopdmetoda($tahunaktif, $bulanaktif, null, 'Tender');
            $data['datarupopdseleksi'] = $this->sirupmodel->rupopdmetoda($tahunaktif, $bulanaktif, null, 'Seleksi');
            $data['datarupopdPDN'] = $this->sirupmodel->rupopdmetoda($tahunaktif, $bulanaktif, null, 'Produk Dalam Negeri');
            $data['datajmlrupopd'] = $this->sirupmodel->jmldatarup($tahunaktif, $bulanaktif, null)
                // ->groupBy('Nama_Satuan_Kerja')
                ->get()->getRowArray();
            $data['datajmlrupopdP'] = $this->sirupmodel->jmldatarup($tahunaktif, $bulanaktif, null)
                ->where('Cara_Pengadaan', 'Penyedia')
                // ->groupBy('Nama_Satuan_Kerja')
                ->get()->getRowArray();
            $data['datajmlrupopdS'] = $this->sirupmodel->jmldatarup($tahunaktif, $bulanaktif, null)
                ->where('Cara_Pengadaan', 'Swakelola')
                // ->groupBy('Nama_Satuan_Kerja')
                ->get()->getRowArray();
            $data['datajmlrupopdE'] = $this->sirupmodel->jmldatarup($tahunaktif, $bulanaktif, null)
                ->where('Metode_Pengadaan', 'E-Purchasing')
                // ->groupBy('Nama_Satuan_Kerja')
                ->get()->getRowArray();
            $data['datajmlrupopdPL'] = $this->sirupmodel->jmldatarup($tahunaktif, $bulanaktif, null)
                ->where('Metode_Pengadaan', 'Pengadaan Langsung')
                // ->groupBy('Nama_Satuan_Kerja')
                ->get()->getRowArray();
            $data['datajmlrupopdTunjuk'] = $this->sirupmodel->jmldatarup($tahunaktif, $bulanaktif, null)
                ->where('Metode_Pengadaan', 'Penunjukan Langsung')
                // ->groupBy('Nama_Satuan_Kerja')
                ->get()->getRowArray();
            $data['datajmlrupopdKecuali'] = $this->sirupmodel->jmldatarup($tahunaktif, $bulanaktif, null)
                ->where('Metode_Pengadaan', 'Dikecualikan')
                // ->groupBy('Nama_Satuan_Kerja')
                ->get()->getRowArray();
            $data['datajmlrupopdTender'] = $this->sirupmodel->jmldatarup($tahunaktif, $bulanaktif, null)
                ->like('Metode_Pengadaan', 'Tender')
                // ->groupBy('Nama_Satuan_Kerja')
                ->get()->getRowArray();
            $data['datajmlrupopdSeleksi'] = $this->sirupmodel->jmldatarup($tahunaktif, $bulanaktif, null)
                ->where('Metode_Pengadaan', 'Seleksi')
                // ->groupBy('Nama_Satuan_Kerja')
                ->get()->getRowArray();
            $data['datajmlrupopdSwakelola'] = $this->sirupmodel->jmldatarup($tahunaktif, $bulanaktif, null)
                ->where('Metode_Pengadaan', ' ')
                // ->groupBy('Nama_Satuan_Kerja')
                ->get()->getRowArray();

            $data['datajmlrupopdPDN'] = $this->sirupmodel->jmldatarup($tahunaktif, $bulanaktif, null)
                ->where('Produk_Dalam_Negeri', 'Ya')
                // ->groupBy('Nama_Satuan_Kerja')
                ->get()->getRowArray();
            $data['datajmlrupopdNonPDN'] = $this->sirupmodel->jmldatarup($tahunaktif, $bulanaktif, null)
                ->where('Produk_Dalam_Negeri', 'Tidak')
                // ->groupBy('Nama_Satuan_Kerja')
                ->get()->getRowArray();
            // echo dd($data['datajmlrupopd']['total_anggaran'], $data['datajmlrupopd']['jumlah_paket']);
        }
        // echo dd($data['datarupopdpenyedia']);
        return view('superadmin/2026/listopdruppbj', $data);
    }
    public function realpbj()
    {
        if (logged_in()) {
            $user = user();
            $groupModel = new GroupModel();
            $groupuser = $groupModel->getGroupsForUser($user->id);
            foreach ($groupuser as $row) {
                $namagroup = $row['name'];
            }
        }
        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        $tahunaktif = session()->get('tahun'); //$jadwalaktif['tahun'];
        if ($tahunaktif == '2025') {
            $bulanaktif = 'Desember';
        } else {
            $bulanaktif = $jadwalaktif['bulan'];
        }
        $tahunaktif =  session()->get('tahun'); //   $jadwalaktif['tahun'];
        $tglaktif = session()->get('tglaktif');
        $groupname = session()->get('groupuser');
        $data = [
            'groupuser' => $namagroup,
            'groupmenu' => 'adminprov',
            'titlepage' => 'Selamat Datang di Administrator SiTAPIS Provinsi Lampung',
            'datauser'  => $this->tausermodel->listuser($user->id),
            'tahunaktif'     => $tahunaktif,
            'bulan'     => $bulanaktif,
            'jadwalaktif' => $jadwalaktif,
            'tglaktif' => $tglaktif,
        ];
        if (!$tahunaktif) {
            session()->set('groupuser', $namagroup);
            session()->set('groupmenu', 'adminprov');
            return view('pilihtahun', $data);
        }
        $datauser = $this->tausermodel->listuser($user->id);
        // $data['subunit'] = 'INSPEKTORAT'; //$datauser['sub_unit']; //'DINAS PENDIDIKAN DAN KEBUDAYAAN'; //
        $data['realisasirup'] = $this->realrupmodel->realrupopd($tahunaktif, $bulanaktif, null)->getResultArray();
        $data['dataruppermetode'] = $this->sirupmodel->rupopdmetode($tahunaktif, $bulanaktif, null)->getResultArray();
        $data['datarealpermetode'] = $this->realrupmodel->realrupopdmetode($tahunaktif, $bulanaktif, null)->getResultArray();
        // echo dd($data['realisasirup']);
        return view('superadmin/2026/realisasirupopd', $data);
    }
}
