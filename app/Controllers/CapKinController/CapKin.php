<?php

namespace App\Controllers\CapKinController;

use App\Models\LrfkProvModel\JadwalModel;
use App\Models\UserModel\TaUserModel;
use App\Models\LrfkProvModel\SubKegModel; // as lrfkModel;
use \Myth\Auth\Authorization\GroupModel;
use App\Models\LrfkProvModel\RealisasiSubKegModel;
use App\Models\LrfkProvModel\TotalRealisasiModel;
use App\Models\LrfkProvModel\TaIndikatorProgModal;
use App\Models\LrfkProvModel\TaIndikatorSubKegModal;
use App\Models\LrfkProvModel\TaRealisasiRinciModel;

use App\Models\CapkinModel\TaSubKegCapkinModel;
use App\Models\CapkinModel\TaKegPokokCapkinModel;
use App\Models\CapkinModel\TaMProgPrioritasModel;
use App\Models\CapkinModel\TaRealisasiKegPokokModel;
use App\Models\CapkinModel\TaRKegPokokCapkinModel;
use App\Models\CapkinModel\TaMOpdProgUnggulan;
use App\Models\CapkinModel\TaProgUnggulan;
use App\Models\CapkinModel\TaMProgTematikModel;
use App\Models\CapkinModel\TaMOpdTematikModel;
use App\Models\CapkinModel\TaJadwalTwModel;

use App\Models\EksekutifModel\TaTtdLaporanModel;




use App\Models\CapkinModel\TaKabModel;
use App\Models\CapkinModel\TaKecamatanModel;
use App\Models\CapkinModel\TaDesaModel;

use App\Models\CapkinModel\TaKategoriModel;

use App\Models\LokasiModel;


use App\Libraries\PdfLibrary;
use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;
use FontLib\Table\Type\post;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;
use TCPDF;

//2026
use App\Models\DataApbdModel\RealApbdModel;

class CapKin extends BaseController
{

    protected $jadwalmodel;
    protected $subkegmodel;
    protected $tausermodel;
    protected $realisasilrfk;
    protected $realisasilrfkrinci;

    protected $targetsubkegmodel;
    protected $kegpokokmodal;
    protected $progprioritas;

    protected $twmodel;

    protected $rkegpokokmodal;
    protected $rdkegpokokmodal;
    protected $opdprogunggulan;
    protected $progunggulan;
    protected $progtematik;
    protected $opdprogtematik;

    protected $kabmodel;
    protected $kecmodel;
    protected $desamodel;

    protected $kategorimodel;

    protected $lokasiModel;
    protected $ttdmodel;

    protected $totalrealisasiM;
    protected $tcpdfConfig;
    protected $taindikator;
    protected $taindisubkeg;
    protected $db;

    //2026
    protected $realapbdmodel;
    public function __construct()
    {
        // helper(['form']);
        $this->tausermodel = new TaUserModel();
        $this->jadwalmodel = new JadwalModel();
        $this->subkegmodel = new SubKegModel();
        $this->realisasilrfk = new RealisasiSubKegModel();
        $this->totalrealisasiM = new TotalRealisasiModel();
        $this->taindikator = new TaIndikatorProgModal();
        $this->taindisubkeg = new TaIndikatorSubKegModal();
        $this->realisasilrfkrinci = new TaRealisasiRinciModel();

        $this->targetsubkegmodel = new TaSubKegCapkinModel();
        $this->kegpokokmodal = new TaKegPokokCapkinModel();
        $this->progprioritas = new TaMProgPrioritasModel();
        $this->ttdmodel = new TaTtdLaporanModel();

        $this->twmodel = new TaJadwalTwModel();

        $this->rkegpokokmodal = new TaRealisasiKegPokokModel();
        $this->rdkegpokokmodal = new TaRKegPokokCapkinModel();
        $this->opdprogunggulan = new TaMOpdProgUnggulan();
        $this->progunggulan = new TaProgUnggulan();
        $this->progtematik = new TaMProgTematikModel();
        $this->opdprogtematik = new TaMOpdTematikModel();


        $this->kabmodel = new TaKabModel();
        $this->kecmodel = new TaKecamatanModel();
        $this->desamodel = new TaDesaModel();

        $this->kategorimodel = new TaKategoriModel();


        $this->lokasiModel = new LokasiModel();

        $this->tcpdfConfig = new \Config\Tcpdf();
        helper(['form', 'url', 'filesystem']);
        $this->db = db_connect();

        //2026
        $this->realapbdmodel = new RealApbdModel();
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
        $receivedParams = $req->getGet();
        $page = $receivedParams['page'] ?? null;
        $action = $receivedParams['action'] ?? null;
        $periode = $receivedParams['periode'] ?? null;
        $id = $receivedParams['id'] ?? null;
        $idD = $receivedParams['idD'] ?? null;
        $kdSU = $receivedParams['kdSU'] ?? null;
        $kdU = $receivedParams['kdU'] ?? null;
        $bulan = $receivedParams['bulan'] ?? null;

        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        $tahunaktif = session()->get('tahun'); //$jadwalaktif['tahun'];
        $bulanaktif =  $jadwalaktif['bulan'];
        // session()->set('tahun', $tahunaktif);

        $tahun = 2025; //session()->get('tahun');
        if ($namagroup == 'adminprov') {
            return redirect()->to(base_url('lrfkadmin'));
        }
        $data = [
            'groupuser' => $namagroup,
            'groupmenu' => 'usercapkinprov',
            'titlepage' => 'Selamat Datang di e-TAPIS Laporan Capaian Kinerja Output Perangkat Daerah Provinsi Lampung',
            'datauser'  => $this->tausermodel->listuser($user->id),
            'tahun'     => $tahunaktif,
            'bulan'     => $bulanaktif

        ];
        if (!$tahunaktif) {
            session()->set('groupuser', $namagroup);
            session()->set('groupmenu', 'usercapkinprov');
            return view('pilihtahun', $data);
        }
        // $ceklrfk = $this->realisasilrfkrinci->cekROpd($data['datauser']['kd_sub_unit'], $tahunaktif);
        // if (empty($ceklrfk)) {
        //     throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Realisasi Anggaran Belum terinput, Mohon diinput..Untuk Melanjutkan');
        // }
        // $kategori = $receivedParams['kategori'] ?? null;
        $kategori = $this->request->getVar('kategori');
        $keyword = $this->request->getVar('keyword');
        $tahuncek = session()->get('tahun');

        if ($tahunaktif == 2025) {
            $tahundata = 2025;
            $bulandata = 'Desember';;
        } else {
            $tahundata = $tahunaktif;
            $bulandata = $jadwalaktif['bulan'];
        }
        if ($kategori) {
            $data = [
                'kategoriList' => $this->kategorimodel->listkategori(), //$this->lokasiModel->getKategori(),
                'groupuser' => $namagroup,
                'groupmenu' => 'usercapkinprov',
                'titlepage' => 'Selamat Datang di e-TAPIS Laporan Capaian Kinerja Output Perangkat Daerah Provinsi Lampung',
                'datauser'  => $this->tausermodel->listuser($user->id),
                'tahun'     => $tahunaktif,
                'bulan'     => $bulanaktif,
                'kab' => $this->kabmodel->listkab(),
                'kec' => $this->kecmodel->listkecamatan(),
                'desa' => $this->desamodel->listdesa(),
            ];
            $kategori = $this->request->getVar('kategori');
            if ($tahundata == 2025) {
                $data['lokasi'] = $this->rdkegpokokmodal->perkategori($kategori, $tahundata, $data['datauser']['kd_sub_unit']);
            } else {
                $data['lokasi'] = $this->rdkegpokokmodal->perkategori26($kategori, $tahundata, $data['datauser']['kd_sub_unit']);
            }
            return view('capkin/v_index', $data);
        }
        if ($keyword) {
            $data = [
                'kategoriList' => $this->kategorimodel->listkategori(), //$this->lokasiModel->getKategori(),
                'groupuser' => $namagroup,
                'groupmenu' => 'usercapkinprov',
                'titlepage' => 'Selamat Datang di e-TAPIS Laporan Capaian Kinerja Output Perangkat Daerah Provinsi Lampung',
                'datauser'  => $this->tausermodel->listuser($user->id),
                'tahun'     => $tahunaktif,
                'bulan'     => $bulanaktif,
                'kab' => $this->kabmodel->listkab(),
                'kec' => $this->kecmodel->listkecamatan(),
                'desa' => $this->desamodel->listdesa(),
            ];
            $keyword = $this->request->getVar('keyword');
            if ($tahundata == 2025) {
                $data['lokasi'] = $this->rdkegpokokmodal->perkeyword($keyword, $tahundata, $data['datauser']['kd_sub_unit']);
            } else {
                $data['lokasi'] = $this->rdkegpokokmodal->perkeyword26($keyword, $tahundata, $data['datauser']['kd_sub_unit']);
            }
            // echo dd($data['lokasi']);
            return view('capkin/v_index', $data);
        }
        if ($idD) {
            if ($tahundata == 2025) {
                $data = [
                    'title' => 'Detail Lokasi',
                    'lokasi' => $this->rdkegpokokmodal->getLokasi($tahundata, $data['datauser']['kd_sub_unit'], $idD)
                ];
                // $data['lokasi'] = $this->rdkegpokokmodal->getLokasi($tahundata, $data['datauser']['kd_sub_unit'], $id_dr = false);
            } else {
                $data = [
                    'title' => 'Detail Lokasi',
                    'lokasi' => $this->rdkegpokokmodal->getLokasi26($tahundata, $data['datauser']['kd_sub_unit'], $idD)
                ];
                // $data['lokasi'] = $this->rdkegpokokmodal->getLokasi26($tahundata, $data['datauser']['kd_sub_unit'], $id_dr = false);
            }

            // echo dd($data);
            if (empty($data['lokasi'])) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Lokasi dengan ID ' . $idD . ' tidak ditemukan');
            }
            // echo dd($data['lokasi']);
            return view('capkin/v_detaildok', $data);
        }
        if (!$receivedParams) {
            $datauser = $this->tausermodel->listuser($user->id);
            if ($datauser['sub_unit'] == '') {
                $data['groupuser'] = 'forbiddenopd';
                return view('lrfk/opd/v_forbidden', $data);
            }
            // $data['tahunaktif'] = session()->get('tahun');28
            if (!$tahunaktif) {
                session()->set('groupuser', $namagroup);
                session()->set('groupmenu', 'usercapkinprov');
                return view('pilihtahun', $data);
            } else {
                $data = [
                    //     'title' => 'Daftar Lokasi',
                    'kategoriList' => $this->kategorimodel->listkategori(), //$this->lokasiModel->getKategori(),
                    //     'groupuser' => '',
                    //     'titlepage' => 'Data Lokasi'
                    // 'lokasi' => $this->rdkegpokokmodal->getLokasi($tahundata, $data['datauser']['kd_sub_unit'], $id_dr = false),
                    'groupuser' => $namagroup,
                    'groupmenu' => 'usercapkinprov',
                    'titlepage' => 'Selamat Datang di e-TAPIS Laporan Capaian Kinerja Output Perangkat Daerah Provinsi Lampung',
                    'datauser'  => $this->tausermodel->listuser($user->id),
                    'tahun'     => $tahundata,
                    'bulan'     => $bulandata,
                    'kab' => $this->kabmodel->listkab(),
                    'kec' => $this->kecmodel->listkecamatan(),
                    'desa' => $this->desamodel->listdesa(),
                ];
                if ($tahundata == 2025) {
                    $data['lokasi'] = $this->rdkegpokokmodal->getLokasi($tahundata, $data['datauser']['kd_sub_unit'], $id_dr = false);
                } else {
                    $data['lokasi'] = $this->rdkegpokokmodal->getLokasi26($tahundata, $data['datauser']['kd_sub_unit'], $id_dr = false);
                }
                // echo dd($tahundata, $bulandata, $data['lokasi'], $data['datauser']['kd_sub_unit']);
                // echo dd($data['lokasi']);
                return view('capkin/v_index', $data);
            }
        }
        if ($page == 'ttdlaporan') {
            if ($action == 'tambah') {
            }
        }
        if ($page == 'dashboard') {
            $this->ValidasiHash($req);

            if ($action == 'listall') {

                $data['dataskcapkinopd'] = $this->targetsubkegmodel->DataPerOpd($tahun, $data['datauser']['kd_sub_unit']);
                $data['jmlsubkegtermapping'] = count($data['dataskcapkinopd']);
                // echo dd($tahun, $data['datauser']['kd_sub_unit']);
                // echo dd($data['dataskcapkinopd']);

                $data['tahunaktif'] = $tahunaktif;
                $mappingsasaran = $this->kegpokokmodal->select('*')->where('tahun', $tahunaktif)->where('kd_subunit', $data['datauser']['kd_sub_unit'])
                    ->where('delete_at=', 0)->where('id_progprioritas<>', 0)->orderBy('update_at', 'DESC')->get()->getResultArray();
                $data['sasaranterupdete'] = $mappingsasaran[0]['update_at'] ?? null;
                $data['jmlmappingprio'] = count($mappingsasaran) ?? null; // Mengetahui jumlah elemen
                $mappingunggulan = $this->kegpokokmodal->select('*')->where('tahun', $tahunaktif)->where('kd_subunit', $data['datauser']['kd_sub_unit'])
                    ->where('delete_at=', 0)->where('id_progunggulan<>', 0)->orderBy('update_at', 'DESC')->get()->getResultArray();
                $data['unggulanterupdete'] = $mappingunggulan[0]['update_at'] ?? null;
                $data['jmlmappingunggulan'] = count($mappingunggulan ?? null); // Mengetahui jumlah elemen
                $mappingtematik = $this->kegpokokmodal->select('*')->where('tahun', $tahunaktif)->where('kd_subunit', $data['datauser']['kd_sub_unit'])
                    ->where('delete_at=', 0)->where('id_progtematik<>', 0)->orderBy('update_at', 'DESC')->get()->getResultArray();
                $data['jmlmappingtematik'] = count($mappingtematik ?? null); // Mengetahui jumlah elemen
                $data['tematikterupdete'] = $mappingtematik[0]['update_at'] ?? null;
                $data['cekopdunggulan'] = $this->opdprogunggulan->cekopd($data['datauser']['kd_sub_unit']);
                $data['cekopdtematik'] = $this->opdprogtematik->cekopd($data['datauser']['kd_sub_unit']);
                // echo dd($data['sasaranterupdete']);
                return view('capkin/v_datacapkin', $data);
            }
            if ($action == 'progreslrfk') {
                $datauser = $this->tausermodel->listuser($user->id);
                $dataopd =  $this->realapbdmodel->listopd($datauser['sub_unit']);

                // $paguopd = $this->subkegmodel->totpaguopd($data['datauser']['kd_sub_unit']);
                // $data['totpaguopd'] = $paguopd['pagu_rincian'];
                // $data['totBelOpd'] = $paguopd['kd_rek_belanja'];


                $data = [
                    'groupuser' => $namagroup,
                    'groupmenu' => 'usercapkinprov',
                    'titlepage' => 'Selamat Datang di e-TAPIS Laporan Realisasi Fisik Anggaran Program Kegiatan Perangkat Daerah Provinsi Lampung',
                    'datauser' => $this->tausermodel->listuser($user->id),
                    'jadwalaktif' => $jadwalaktif,
                    'tahunaktif' => $tahunaktif,
                    // 'totpaguopd' => $paguopd['pagu_rincian'],
                ];
                $jadwalall = $this->jadwalmodel->findAll();
                $data['datajadwal'] =  $jadwalall;
                if (!$bulan) {
                    $data['bulanpilih'] = '';
                } else {
                    $data['bulanpilih'] = $bulan;
                }
                $datauser = $this->tausermodel->listuser($user->id);
                $data['listapbdopd'] = $this->subkegmodel->paguperurusan($datauser['sub_unit'], $data['datauser']['kd_sub_unit'], $tahunaktif);
                return view('capkin/v_lrfk', $data);
            }
        }
        if ($page == 'data') {
            $this->ValidasiHash($req);
            if ($action == 'tambah') {
                // $datask =  $this->subkegmodel->SKKegPokokOPD($data['datauser']['kd_sub_unit']); //$this->db1->table('ta_agenda_aku')->select('*')->get()->getResult();
                // $datask =  $this->subkegmodel->SKKegPokokOPD2($data['datauser']['kd_sub_unit']); //$this->db1->table('ta_agenda_aku')->select('*')->get()->getResult();
                $data['sknoadum'] =  $this->subkegmodel->SKKegPokokOPD2($data['datauser']['kd_sub_unit']); //$this->db1->table('ta_agenda_aku')->select('*')->get()->getResult();
                // $data['sknoadum'] = ['' => 'pilih']  + array_column($datask, 'nm_subkegiatan', 'kd_subkegiatan');
                $data['kd_subunit'] = $data['datauser']['kd_sub_unit'];
                // $data['sknoadum'] = $this->subkegmodel->SKKegPokokOPD($data['datauser']['kd_sub_unit']);
                $data['idUbah'] = '';
                // echo dd($datask);

                // return view('capkin/form_inputcapkin', $data);
                return view('capkin/form_pilihsubkeg', $data);
            }
        }
        if ($page == 'ttdlaporan') {
            $this->ValidasiHash($req);
            if ($action == 'tambah') {
                return view('capkin/form_inputttd', $data);
            }
        }
        if ($page == 'cetak') {
            $this->ValidasiHash($req);
            $data = [
                //     'title' => 'Daftar Lokasi',
                'kategoriList' => $this->kategorimodel->listkategori(), //$this->lokasiModel->getKategori(),
                //     'groupuser' => '',
                //     'titlepage' => 'Data Lokasi'
                'lokasi' => $this->rdkegpokokmodal->getLokasi($tahunaktif, $data['datauser']['kd_sub_unit'], $id_dr = false),
                'groupuser' => $namagroup,
                'groupmenu' => 'usercapkinprov',
                'titlepage' => 'Selamat Datang di e-TAPIS Laporan Capaian Kinerja Output Perangkat Daerah Provinsi Lampung',
                'datauser'  => $this->tausermodel->listuser($user->id),
                'tahun'     => $tahunaktif,
                'bulan'     => $bulanaktif,
                'twaktif'  => $this->twmodel->twaktif(),
                'kab' => $this->kabmodel->listkab(),
                'kec' => $this->kecmodel->listkecamatan(),
                'desa' => $this->desamodel->listdesa(),
            ];
            $jadwalall = $this->jadwalmodel->findAll();
            $data['datajadwal'] =  $jadwalall;

            if ($action == 'sasaran') {
                $data['judulcetak'] = 'Sasaran Program Prioritas Perangkat Daerah';
                $data['action'] = 'sasaran';
                $data['periode'] = $periode;
                // $data['nmttd'] = '';
                // $data['nipttd'] = '';
                // $data['jabatanttd'] = '';

                if (!$periode) {
                    return view('capkin/periodecetakbulan', $data);
                }
                $data['progprio'] = $this->kegpokokmodal->DataPerPprioOPD($tahun, $data['datauser']['kd_sub_unit']);

                $data['dataskcapkinopd'] = $this->targetsubkegmodel->DataPerOpd($tahun, $data['datauser']['kd_sub_unit']);
                $data['jmltotpagu'] = $this->subkegmodel->totpaguopd($data['datauser']['kd_sub_unit'], $tahunaktif);

                // $data['datarealisasi'] = $this->realisasilrfk->totalRperBlnOPD($tahunaktif, $periode, $data['datauser']['sub_unit']);
                $data['datarealisasi'] = $this->realisasilrfkrinci->selectSum('realisasi')
                    ->where('kd_sub_unit', $data['datauser']['kd_sub_unit'])
                    ->where('tahun', $tahunaktif)
                    ->where('bulan', $periode)
                    ->where('delete_at=', 0)
                    ->groupBy('kd_sub_unit')
                    ->get()
                    ->getRowArray();

                // echo dd($tahun, $data['datauser']['kd_sub_unit']);
                // echo dd($data['progprio']);
                // echo dd($data['datauser']['sub_unit']);
                $data['tahunaktif'] = $tahunaktif;
                $data['bulantw'] = $periode;
                $data['bulan'] = $bulanaktif;
                $html = view('/capkin/cetaksasaran', $data);
                $pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('Biro Administrasi Pembangunan');
                $pdf->SetTitle('Laporan SiTAPIS Perangkat Daerah');
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
            if ($action == 'unggulan') {
                $data['judulcetak'] = 'Program Unggulan Perangkat Daerah';
                $data['action'] = 'unggulan';
                $data['periode'] = $periode;

                if (!$periode) {
                    return view('capkin/periodecetakbulan', $data);
                }
                $data['progunggulan'] = $this->kegpokokmodal->DataPerPUnggulOPD($tahun, $data['datauser']['kd_sub_unit']);

                $data['dataskcapkinopd'] = $this->targetsubkegmodel->DataPerOpd($tahun, $data['datauser']['kd_sub_unit']);
                // echo dd($data['progunggulan']);
                $data['tahunaktif'] = $tahunaktif;
                $data['bulantw'] = $periode;
                $data['bulan'] = $bulanaktif;

                $html = view('/capkin/cetakunggulan', $data);
                $pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('Biro Administrasi Pembangunan');
                $pdf->SetTitle('Laporan SiTAPIS Perangkat Daerah');
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
                $pdf->Output('Laporan_Sasaran' . $tahunaktif . '_' . $data['datauser']['sub_unit'], 'I');
            }
            if ($action == 'tematik') {
                $data['judulcetak'] = 'Program Tematik Pembangunan Perangkat Daerah';
                $data['action'] = 'tematik';
                $data['periode'] = $periode;

                if (!$periode) {
                    return view('capkin/periodecetakbulan', $data);
                }
                $data['progtematik'] = $this->kegpokokmodal->DataPerTematikOPD($tahun, $data['datauser']['kd_sub_unit']);

                $data['dataskcapkinopd'] = $this->targetsubkegmodel->DataPerOpd($tahun, $data['datauser']['kd_sub_unit']);
                // echo dd($data['progunggulan']);
                $data['tahunaktif'] = $tahunaktif;

                // if ($periode == 'tw1') {
                //     $data['bulantw'] = 'Maret';
                // }
                // if ($periode == 'tw2') {
                //     $data['bulantw'] = 'Juni';
                // }
                // if ($periode == 'tw3') {
                //     $data['bulantw'] = 'September';
                // }
                // if ($periode == 'tw4') {
                //     $data['bulantw'] = 'Desember';
                // }
                $data['bulantw'] = $periode;
                $data['bulan'] = $bulanaktif;
                $html = view('/capkin/cetaktematik', $data);
                $pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('Biro Administrasi Pembangunan');
                $pdf->SetTitle('Laporan SiTAPIS Perangkat Daerah');
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
                $pdf->Output('Laporan_Sasaran' . $tahunaktif . '_' . $data['datauser']['sub_unit'], 'I');
            }
        }
    }
    public function simpancapkin()
    {
        $kdsubkeg = $this->request->getPost('kd_subkegiatan');
        $kdsubunit = $this->request->getPost('kd_subunit');
        $id  = $this->request->getPost('idUbah');
        session()->set('kd_subkegiatan', $kdsubkeg);
        session()->set('kd_subunit', $kdsubunit);
        // if ($kdsubkeg) {
        // echo dd($id);
        $tahun = 2025; //session()->get('tahun');
        $datasubkeg = $this->subkegmodel->subkeg($kdsubkeg, $kdsubunit, $tahun);
        if ($id) {
            $data =  [
                'id_sk' => $id,
                'tahun' => $tahun,
                'kd_subunit' => $kdsubunit,
                'nm_subunit' => $datasubkeg['nm_sub_unit'],
                'kd_urusan' => $datasubkeg['kd_urusan'],
                'kd_program' => $datasubkeg['kd_program'],
                'kd_kegiatan' => $datasubkeg['kd_kegiatan'],
                'kd_subkegiatan' => $kdsubkeg,
                'nm_program' => $datasubkeg['nm_program'],
                'nm_kegiatan' => $datasubkeg['nm_kegiatan'],
                'nm_subkegiatan' => $datasubkeg['nm_subkegiatan'],
                'pagu' => $datasubkeg['pagu_rincian']
            ];
            $pesan = 'Subkegiatan Berhasil diUbah';
        } else {
            $data =  [
                'tahun' => $tahun,
                'kd_subunit' => $kdsubunit,
                'nm_subunit' => $datasubkeg['nm_sub_unit'],
                'kd_urusan' => $datasubkeg['kd_urusan'],
                'kd_program' => $datasubkeg['kd_program'],
                'kd_kegiatan' => $datasubkeg['kd_kegiatan'],
                'kd_subkegiatan' => $kdsubkeg,
                'nm_program' => $datasubkeg['nm_program'],
                'nm_kegiatan' => $datasubkeg['nm_kegiatan'],
                'nm_subkegiatan' => $datasubkeg['nm_subkegiatan'],
                'pagu' => $datasubkeg['pagu_rincian']
            ];
            $pesan = 'Subkegiatan Berhasil ditambahkan';
        }
        $cekdatask = $this->targetsubkegmodel->select('id_sk')
            ->where('kd_subunit', $kdsubunit)
            ->where('tahun', $tahun)
            ->where('delete_at=', 0)
            ->where('kd_subkegiatan', $kdsubkeg)
            ->get()->getRowArray();
        // echo dd($cekdatask);
        if ($cekdatask) {
            return redirect()->back()->withInput()->with('message', 'SubKegiaan yang dipilih sudah ada');
        } else {
            // echo dd($data);
            $this->targetsubkegmodel->save($data);
            // return redirect()->to(base_url('capkin/kegpokok'));
            return redirect()->back()->withInput()->with('message', $pesan);
        }
        // }
    }
    public function kegpokok()
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
        $page = $receivedParams['page'] ?? null;
        $action = $receivedParams['action'] ?? null;
        $idUbah = $receivedParams['idUbah'] ?? null;
        $idHapus = $receivedParams['idHapus'] ?? null;
        $idSK = $receivedParams['idSK'] ?? null;
        $idKP = $receivedParams['idKP'] ?? null;
        $idRKP = $receivedParams['idRKP'] ?? null;
        $bulan = $receivedParams['bulan'] ?? null;
        $id_r = $receivedParams['idRKP'] ?? null;
        $id_dr = $receivedParams['idDR'] ?? null;

        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        $tahunaktif = session()->get('tahun'); //$jadwalaktif['tahun'];
        $bulanaktif = $jadwalaktif['bulan'];
        $data = [
            'groupuser' => $namagroup,
            'groupmenu' => 'usercapkinprov',
            'titlepage' => 'Selamat Datang di e-TAPIS Laporan Capaian Kinerja Output Perangkat Daerah Provinsi Lampung',
            'datauser'  => $this->tausermodel->listuser($user->id),
            'tahun'     => $tahunaktif,
            'bulan'     => $bulanaktif
        ];

        $data['titlepage'] = 'Selamat Datang di e-TAPIS Laporan Realisasi Fisik Anggaran Program Kegiatan Perangkat Daerah Provinsi Lampung';
        $data['listopd'] = $this->subkegmodel->listopd($data['datauser']['kd_sub_unit'], 2025);
        // $datauser = $this->tausermodel->listuser($user->id);

        $data['tahunaktif'] = session()->get('tahun');
        if (!$data['tahunaktif']) {
            session()->set('groupuser', $namagroup);
            session()->set('groupmenu', 'userlrfkprov');
            return view('pilihtahun', $data);
        }

        if ($page == 'kegpokok') {
            if ($action == 'ubahsubkeg') {
                // $datask =  $this->subkegmodel->SKKegPokokOPD2($data['datauser']['kd_sub_unit']); //$this->db1->table('ta_agenda_aku')->select('*')->get()->getResult();
                // $data['sknoadum'] = ['' => 'pilih']  + array_column($datask, 'nm_subkegiatan', 'kd_subkegiatan');
                $data['sknoadum'] =  $this->subkegmodel->SKKegPokokOPD2($data['datauser']['kd_sub_unit']); //$this->db1->table('ta_agenda_aku')->select('*')->get()->getResult();
                $data['kd_subunit'] = $data['datauser']['kd_sub_unit'];
                $data['idUbah'] = $idUbah;
                // $data['sknoadum'] = $this->subkegmodel->SKKegPokokOPD($data['datauser']['kd_sub_unit']);
                // 
                // echo dd($data['subkeg']);
                // return view('capkin/form_inputcapkin', $data);
                return view('capkin/form_pilihsubkeg', $data);
            }
            if ($action == 'tambah') {
                $datasktargettambah = $this->targetsubkegmodel->DataPerSK($idSK);
                // echo dd($datasktargettambah);
                $data = [
                    'groupuser' => $namagroup,
                    'groupmenu' => 'usercapkinprov',
                    'subkegiatan' => $datasktargettambah['nm_subkegiatan'],
                    'id_targetsubkeg'       => $datasktargettambah['id_sk'],
                    'tahundt'       => $datasktargettambah['tahun'],
                    'kd_subunit'       => $datasktargettambah['kd_subunit'],
                    'kd_subkegiatan'       => $datasktargettambah['kd_subkegiatan'],
                    'pagu'       => $datasktargettambah['pagu'],
                    'titlepage' => 'Selamat Datang di e-TAPIS Laporan Capaian Kinerja Output Perangkat Daerah Provinsi Lampung',
                    'datauser'  => $this->tausermodel->listuser($user->id),
                    'tahun'     => $tahunaktif,
                    'id'    => ''
                ];
                // echo dd($data);
                $dataprogprioritas =  $this->progprioritas->findAll(); //($data['datauser']['kd_sub_unit']); //$this->db1->table('ta_agenda_aku')->select('*')->get()->getResult();
                $data['dataprogprioritas'] = ['' => 'pilih']  + array_column($dataprogprioritas, 'nm_progprioritas', 'id_pprio');
                // echo dd($dataprogprioritas);

                $dataprogungulan =  $this->progunggulan->findAll(); //($data['datauser']['kd_sub_unit']); //$this->db1->table('ta_agenda_aku')->select('*')->get()->getResult();
                $data['dataprogunggulan'] = ['' => 'pilih']  + array_column($dataprogungulan, 'nm_progunggulan', 'id_pung');

                $dataprogtematik =  $this->progtematik->findAll(); //($data['datauser']['kd_sub_unit']); //$this->db1->table('ta_agenda_aku')->select('*')->get()->getResult();
                $data['dataprogtematik'] = ['' => 'pilih']  + array_column($dataprogtematik, 'nm_tematik', 'id_tematik');

                $data['cekopdunggulan'] = $this->opdprogunggulan->cekopd($data['datauser']['kd_sub_unit']);
                $data['cekopdtematik'] = $this->opdprogtematik->cekopd($data['datauser']['kd_sub_unit']);

                // echo dd($data['cekopdtematik']);
                return view('capkin/form_inputkegpokok', $data);
            }
            if ($action == 'hapussubkeg') {
                $this->targetsubkegmodel->delete($idHapus);
                // return redirect()->to(base_url('capkin/kegpokok'));
                return redirect()->back()->withInput()->with('message', 'SubKegiaan Berhasil Dihapus');
            }
            if ($action == 'editkegpokok') {
                $cekdatakp = $this->kegpokokmodal->DataPerIdKegPokok($idKP);
                // echo dd($cekdatakp);
                $data['cekopdunggulan'] = $this->opdprogunggulan->cekopd($data['datauser']['kd_sub_unit']);
                $data['cekopdtematik'] = $this->opdprogtematik->cekopd($data['datauser']['kd_sub_unit']);

                if ($cekdatakp['id_progunggulan'] == '0') {
                    $data['datakegpokok'] = $this->kegpokokmodal->DataPerIdKegPokok($idKP);
                } else {
                    $data['datakegpokok'] = $this->kegpokokmodal->DataPerIdKegPokokLengkap($idKP);
                    $dataprogungulan =  $this->progunggulan->findAll(); //($data['datauser']['kd_sub_unit']); //$this->db1->table('ta_agenda_aku')->select('*')->get()->getResult();
                    $data['dataprogunggulan'] = ['' => $data['datakegpokok']['nm_progunggulan'], 'kosong']  + array_column($dataprogungulan, 'nm_progunggulan', 'id_pung') + ['' => 'pilih'];
                    $data['nm_progunggulan'] = $data['datakegpokok']['nm_progunggulan'];
                    //    $data['datakegpokok'] = ['nm_progunggulan' => ]
                }

                if ($cekdatakp['id_progtematik'] == '0') {
                    $data['datakegpokok'] = $this->kegpokokmodal->DataPerIdKegPokok($idKP);
                } else {
                    $data['datakegpokok'] = $this->kegpokokmodal->DataPerIdKegPokokLengkap2($idKP);
                    // echo dd($data['datakegpokok']);
                    $dataprogtematik =  $this->progtematik->findAll(); //($data['datauser']['kd_sub_unit']); //$this->db1->table('ta_agenda_aku')->select('*')->get()->getResult();
                    $data['dataprogtematik'] = ['' => $data['datakegpokok']['nm_tematik'], 'kosong']  + array_column($dataprogtematik, 'nm_tematik', 'id_tematik');
                    $data['nm_progtematik'] = $data['datakegpokok']['nm_tematik'];
                    // echo dd($data['nm_progtematik']);
                }
                $dataprogprioritas =  $this->progprioritas->findAll(); //($data['datauser']['kd_sub_unit']); //$this->db1->table('ta_agenda_aku')->select('*')->get()->getResult();
                $data['dataprogprioritas'] = ['' => $data['datakegpokok']['nm_progprioritas']]  + array_column($dataprogprioritas, 'nm_progprioritas', 'id_pprio');

                $dataprogungulanX =  $this->progunggulan->findAll(); //($data['datauser']['kd_sub_unit']); //$this->db1->table('ta_agenda_aku')->select('*')->get()->getResult();
                $data['dataprogunggulanX'] = ['' => 'pilih']  + array_column($dataprogungulanX, 'nm_progunggulan', 'id_pung');

                $dataprogtematikX =  $this->progtematik->findAll(); //($data['datauser']['kd_sub_unit']); //$this->db1->table('ta_agenda_aku')->select('*')->get()->getResult();
                $data['dataprogtematikX'] = ['' => 'pilih']  + array_column($dataprogtematikX, 'nm_tematik', 'id_tematik');


                // echo dd($data['datakegpokok']);
                return view('capkin/form_editkegpokok', $data);

                // return redirect()->back()->withInput()->with('message', 'SubKegiaan Berhasil Dihapus');
            }
            if ($action == 'hapuskegpokok') {
                $this->kegpokokmodal->delete($idKP);
                return redirect()->back()->withInput()->with('message', 'Kegiatan Pokok Berhasil Dihapus');
            }
            if ($action == 'tambahrealisasi') {
                $data['datakegpokok'] = $this->kegpokokmodal->DataPerIdKegPokok($idKP);
                // echo dd($data['datakegpokok']);
                return view('capkin/form_editkegpokok', $data);

                // return redirect()->back()->withInput()->with('message', 'SubKegiaan Berhasil Dihapus');
            }
        }
        if ($page == 'realisasikegpokok') {
            $data = [
                'groupuser' => $namagroup,
                'groupmenu' => 'usercapkinprov',
                'titlepage' => 'Selamat Datang di e-TAPIS Laporan Capaian Kinerja Output Perangkat Daerah Provinsi Lampung',
                'datauser'  => $this->tausermodel->listuser($user->id),
                'twaktif'  => $this->twmodel->twaktif(),
                'tahun'     => $tahunaktif,
                'bulan'     => $bulanaktif
            ];
            // echo dd($data['twaktif']);
            if ($action == 'tambah') {
                $data['datakp'] = $this->kegpokokmodal->DataPerIdKegPokok($idKP);
                // echo dd($data['datakp']);
                return view('capkin/form_inputRkegpokok', $data);
            }
            if ($action == 'edit') {
                $data['datarkp'] = $this->rkegpokokmodal->DataPerID_R($idRKP);
                // echo dd($data['datarkp']);

                return view('capkin/form_editRkegpokok', $data);
            }
            if ($action == 'hapus') {
                // $data['datarkp'] = $this->rkegpokokmodal->DataPerID_R($idRKP);
                // echo dd($data['datarkp']);
                $this->rkegpokokmodal->delete($id_r);
                return redirect()->back()->withInput()->with('message', 'Realisasi Kegiatan Pokok Berhasil Dihapus');

                // return view('capkin/form_editRkegpokok', $data);
            }
        }
        if ($page == 'rdokumentasi') {
            if ($action == 'tambah') {

                $keyword = $this->request->getVar('keyword');
                $kategori = $this->request->getVar('kategori');
                $kab = $this->request->getVar('kabupaten');

                // $lokasi = $lokasiModel->findAll();
                $data = [
                    //     'title' => 'Daftar Lokasi',
                    'kategoriList' => $this->kategorimodel->listkategori(), //$this->lokasiModel->getKategori(),
                    //     'groupuser' => '',
                    //     'titlepage' => 'Data Lokasi'
                    'lokasi' => $this->lokasiModel->getLokasi(),
                    'groupuser' => $namagroup,
                    'groupmenu' => 'usercapkinprov',
                    'titlepage' => 'Selamat Datang di e-TAPIS Laporan Capaian Kinerja Output Perangkat Daerah Provinsi Lampung',
                    'datauser'  => $this->tausermodel->listuser($user->id),
                    'tahun'     => $tahunaktif,
                    'bulan'     => $bulanaktif,
                    'twaktif'  => $this->twmodel->twaktif(),
                    'kab' => $this->kabmodel->listkab(),
                    'kec' => $this->kecmodel->listkecamatan(),
                    'desa' => $this->desamodel->listdesa(),
                ];
                // if ($keyword) {
                //     $data['lokasi'] = $this->lokasiModel->search($keyword);
                //     $data['keyword'] = $keyword;
                // } elseif ($kategori) {
                //     $data['lokasi'] = $this->lokasiModel->where('kategori', $kategori)->findAll();
                //     $data['kategori'] = $kategori;
                // } else {
                //     $data['lokasi'] = $this->lokasiModel->getLokasi();
                // }
                $data['datarkp'] = $this->rkegpokokmodal->DataPerID_R($idRKP);
                // session()->set('idRKP', $idRKP);
                // echo dd($data['datarkp']);
                // return redirect()->back()->withInput()->with('message', 'Realisasi Kegiatan Pokok Berhasil Dihapus');
                return view('capkin/form_inputdokument', $data);
            }
            if ($action == 'edit') {
                $data = [
                    //     'title' => 'Daftar Lokasi',
                    'kategoriList' => $this->kategorimodel->listkategori(), //$this->lokasiModel->getKategori(),
                    //     'groupuser' => '',
                    //     'titlepage' => 'Data Lokasi'
                    'lokasi' => $this->lokasiModel->getLokasi(),
                    'groupuser' => $namagroup,
                    'groupmenu' => 'usercapkinprov',
                    'titlepage' => 'Selamat Datang di e-TAPIS Laporan Capaian Kinerja Output Perangkat Daerah Provinsi Lampung',
                    'datauser'  => $this->tausermodel->listuser($user->id),
                    'tahun'     => $tahunaktif,
                    'bulan'     => $bulanaktif,
                    'twaktif'  => $this->twmodel->twaktif(),
                    'kab' => $this->kabmodel->listkab(),
                    'kec' => $this->kecmodel->listkecamatan(),
                    'desa' => $this->desamodel->listdesa(),
                ];
                $data['dataDR'] = $this->rdkegpokokmodal->DataPerDRKegPokok($id_dr);
                // session()->set('id_dr', $id_dr);

                // echo dd($data['dataDR']);
                return view('capkin/form_editdokument', $data);
            }
            if ($action == 'hapus') {
                $this->rdkegpokokmodal->delete($id_dr);
                return redirect()->back()->withInput()->with('message', 'Realisasi Kegiatan Pokok Berhasil Dihapus');
            }
        }
        // if (!$receivedParams) {
        //     $tahun = session()->get('tahun');
        //     $kd_sub_unit = session()->get('kd_subunit');
        //     $kd_subkegiatan = session()->get('kd_subkegiatan');
        //     $datasktarget = $this->targetsubkegmodel->cekskcapkin($tahun, $kd_sub_unit, $kd_subkegiatan);
        //     if ($datasktarget) {
        //         // $data['subkegiatan'] = $datasktarget['nm_subkegiatan'];
        //         // $data['id'] = $datasktarget['id'];
        //         $data = [
        //             'groupuser' => $namagroup,
        //             'groupmenu' => 'usercapkinprov',
        //             'subkegiatan' => $datasktarget['nm_subkegiatan'],
        //             'id_targetsubkeg'       => $datasktarget['id_sk'],
        //             'tahundt'       => $datasktarget['tahun'],
        //             'kd_subunit'       => $datasktarget['kd_subunit'],
        //             'kd_subkegiatan'       => $datasktarget['kd_subkegiatan'],
        //             'pagu'       => $datasktarget['pagu'],
        //             'titlepage' => 'Selamat Datang di e-TAPIS Laporan Capaian Kinerja Output Perangkat Daerah Provinsi Lampung',
        //             'datauser'  => $this->tausermodel->listuser($user->id),
        //             'tahun'     => $tahun,
        //             'id'    => ''

        //         ];
        //         // echo dd($data);
        //         return view('capkin/form_inputkegpokok', $data);
        //     }
        //     return redirect()->to(base_url('capkin'));
        // }
    }
    public function simpankegpokok()
    {
        $datakegpokok = $this->request->getPost('data');
        // $datauser  = $this->tausermodel->listuser($user->id);
        if (empty($datakegpokok[0]['id_kp'])) {
            $datakeg = $this->request->getPost('data');
            $jumlah = count($datakeg); // Mengetahui jumlah elemen
            for ($i = 0; $i < $jumlah; $i++) {
                if ($datakeg[$i]['id_progprioritas'] == '') {
                    return redirect()->back()->withInput()->with('peringatan', 'Salah Satu Kegiatan Pokok/Aktifitas Belum termapping ke Sasaran Pembangunan');
                }
            }
            // $data['cekopdunggulan'] = $this->opdprogunggulan->cekopd($data['datauser']['kd_sub_unit']);
            // $data['cekopdtematik'] = $this->opdprogtematik->cekopd($data['datauser']['kd_sub_unit']);

            $this->kegpokokmodal->insertBatch($datakegpokok);
            // return redirect()->to(base_url('capkin/kegpokok'))->withInput()->with('message', 'penyimpanan data berhasil');
            return redirect()->back()->withInput()->with('message', 'penyimpanan data berhasil');
        } else {
            if ($datakegpokok[0]['id_progprioritas'] == '') {
                $datakegpokokX = $this->kegpokokmodal->DataPerIdKegPokok($datakegpokok[0]['id_kp']);
                $datakegpokok[0]['id_progprioritas'] = $datakegpokokX['id_progprioritas'];
            }
            if ($datakegpokok[0]['id_progunggulan'] == '') {
                $datakegpokokY = $this->kegpokokmodal->DataPerIdKegPokok($datakegpokok[0]['id_kp']);
                $datakegpokok[0]['id_progunggulan'] = $datakegpokokY['id_progunggulan'];
            }
            if ($datakegpokok[0]['id_progtematik'] == '') {
                $datakegpokokZ = $this->kegpokokmodal->DataPerIdKegPokok($datakegpokok[0]['id_kp']);
                $datakegpokok[0]['id_progtematik'] = $datakegpokokZ['id_progtematik'];
            }
            // echo dd($datakegpokok);
            $this->kegpokokmodal->updateBatch($datakegpokok, 'id_kp');
            // return redirect()->to(base_url('capkin/kegpokok'))->withInput()->with('message', 'penyimpanan data berhasil');
            return redirect()->back()->withInput()->with('message', 'Kegiatan Pokok Berhasil diubah');
        }
    }
    public function simpanrealisasikp()
    {
        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        $tahunaktif = session()->get('tahun');
        $bulanaktif = $jadwalaktif['bulan'];
        // session()->set('tahun', $tahunaktif);
        $twaktif  = $this->twmodel->twaktif();

        // $tahun = session()->get('tahun');
        $id_r = $this->request->getPost('id_r');
        // echo dd($id_r);
        $datakegpokok = $this->kegpokokmodal->DataPerIdKegPokok($this->request->getPost('idKP'));
        if ($id_r) {
            $datarkp = [
                'id_r' => $id_r,
                'id_targetsubkeg' => $datakegpokok['id_targetsubkeg'],
                'id_kegpokok' => $this->request->getPost('idKP'),
                'id_progprioritas' => $datakegpokok['id_progprioritas'],
                'id_progunggulan' => $datakegpokok['id_progunggulan'],
                'id_progtematik' => $datakegpokok['id_progtematik'],
                'tahun' => $tahunaktif,
                'bulan' => $bulanaktif, //$twaktif['tw'],
                'kd_subunit' => $datakegpokok['kd_subunit'],
                'r_target' => $this->request->getPost('r_target'),
                'sat_target' => $datakegpokok['sat_target'],
                'r_uraian' => $this->request->getPost('r_uraian'),

            ];
        } else {
            $datarkp = [
                'id_targetsubkeg' => $datakegpokok['id_targetsubkeg'],
                'id_kegpokok' => $this->request->getPost('idKP'),
                'id_progprioritas' => $datakegpokok['id_progprioritas'],
                'id_progunggulan' => $datakegpokok['id_progunggulan'],
                'id_progtematik' => $datakegpokok['id_progtematik'],
                'tahun' => $tahunaktif,
                'bulan' => $bulanaktif, //$twaktif['tw'],
                'kd_subunit' => $datakegpokok['kd_subunit'],
                'r_target' => $this->request->getPost('r_target'),
                'sat_target' => $datakegpokok['sat_target'],
                'r_uraian' => $this->request->getPost('r_uraian'),

            ];
        }

        // echo dd($datarkp);
        $this->rkegpokokmodal->save($datarkp);
        return redirect()->back()->withInput()->with('message', 'penyimpanan data berhasil');

        // return redirect()->to(base_url('capkin/kegpokok'))->withInput()->with('message', 'penyimpanan data berhasil');
    }
    public function simpanlokasi()
    {

        // // $fileGambar->move('uploads/lokasi', $namaGambar);
        // $datakategori = $this->request->getVar('kategori');
        // if (!$datakategori) {
        //     $kategori = $this->request->getVar('kategori_baru');
        // } else {
        //     $kategori = $this->request->getVar('kategori');
        // }
        $idRKP = $this->request->getPost('idRKP');
        $idDR = $this->request->getPost('id_dr');

        // $idRKP = session()->get('idRKP');
        // $idDR = session()->get('id_dr');
        // $twaktif  = $this->twmodel->twaktif();
        // echo dd($idDR);

        if ($idDR) {
            $rules = [
                'kegiatan' => 'required|max_length[255]',
                'kabupaten' => 'required',
                'kecamatan' => 'required',
                'desa' => 'required',
                'latitude' => 'required|decimal',
                'longitude' => 'required|decimal',
                'deskripsi' => 'required',
                'gambar' => 'max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]',
                'kategori' => 'required|max_length[50]'
            ];

            // echo dd($data);
            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
            $datarkp = $this->rdkegpokokmodal->DataPerDRKegPokok($idDR);
            $fileGambar = $this->request->getFile('gambar');

            if ($fileGambar->isValid() && !$fileGambar->hasMoved()) {
                $namaGambar = $fileGambar->getRandomName();
                $fileGambar->move(FCPATH . 'uploads/dokumentasi/', $namaGambar);
                // Hapus gambar lama jika ada

                $dataDR = $this->rdkegpokokmodal->find($idDR);
                // echo dd($dataDR);

                if ($dataDR['gambar']) {
                    unlink('uploads/dokumentasi/' . $dataDR['gambar']);
                    // $namaGambar = $dataDR['gambar'];

                }
                $data['gambar'] = $namaGambar;
                // echo dd($namaGambar);
            } else {
                $namaGambar = $datarkp['gambar'];
            }


            // if (!$fileGambar) {
            //     $namaGambar = $datarkp['gambar'];
            //     echo dd($namaGambar);
            // }
            $data = [
                'id_dr' => $idDR,
                'id_rkegpokok' => $datarkp['id_r'],
                'id_targetsubkeg' => $datarkp['id_targetsubkeg'],
                'id_kegpokok' => $datarkp['id_kegpokok'],
                'id_progprioritas' => $datarkp['id_progprioritas'],
                'id_progunggulan' => $datarkp['id_progunggulan'],
                'id_progtematik' => $datarkp['id_progtematik'],

                'tahun' => $datarkp['tahun'],
                'twaktif'  => $this->twmodel->twaktif(),
                'bulan' => $datarkp['bulan'],
                'kegiatan' => $this->request->getVar('kegiatan'),
                'kd_subunit' => $datarkp['kd_subunit'],
                'kd_subkegiatan' => $datarkp['kd_subkegiatan'],
                'kabupaten' => $this->request->getVar('kabupaten'),
                'kecamatan' => $this->request->getVar('kecamatan'),
                'desa' => $this->request->getVar('desa'),
                'latitude' => $this->request->getVar('latitude'),
                'longitude' => $this->request->getVar('longitude'),
                'deskripsi' => $this->request->getVar('deskripsi'),
                'gambar' => $namaGambar,
                'kategori' => $this->request->getVar('kategori')
            ];
            // echo dd($data);
        }
        if ($idRKP) {
            $rules = [
                'kegiatan' => 'required|max_length[255]',
                'kabupaten' => 'required',
                'kecamatan' => 'required',
                'desa' => 'required',
                'latitude' => 'required|decimal',
                'longitude' => 'required|decimal',
                'deskripsi' => 'required',
                'gambar' => 'uploaded[gambar]|max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]',
                'kategori' => 'required|max_length[50]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
            $fileGambar = $this->request->getFile('gambar');
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move(FCPATH . 'uploads/dokumentasi/', $namaGambar); // Simpan di public/

            $datarkp = $this->rkegpokokmodal->DataPerID_R($idRKP);

            $data = [
                'id_rkegpokok' => $idRKP,
                'id_targetsubkeg' => $datarkp['id_targetsubkeg'],
                'id_kegpokok' => $datarkp['id_kegpokok'],
                'id_progprioritas' => $datarkp['id_progprioritas'],
                'id_progunggulan' => $datarkp['id_progunggulan'],
                'id_progtematik' => $datarkp['id_progtematik'],
                'tahun' => $datarkp['tahun'],
                'bulan' => $datarkp['bulan'],
                'kegiatan' => $this->request->getVar('kegiatan'),
                'kd_subunit' => $datarkp['kd_subunit'],
                'kd_subkegiatan' => $datarkp['kd_subkegiatan'],
                'kabupaten' => $this->request->getVar('kabupaten'),
                'kecamatan' => $this->request->getVar('kecamatan'),
                'desa' => $this->request->getVar('desa'),
                'latitude' => $this->request->getVar('latitude'),
                'longitude' => $this->request->getVar('longitude'),
                'deskripsi' => $this->request->getVar('deskripsi'),
                'gambar' => $namaGambar,
                'kategori' => $this->request->getVar('kategori')
            ];
        }
        // echo dd($data);
        $this->rdkegpokokmodal->save($data);
        // session()->remove(['idRKP', 'id_dr']);
        // return redirect()->back()->withInput()->with('message', 'Dokumentasi berhasil Disimpan');
        return redirect()->to(base_url('capkin'))->withInput()->with('message', 'penyimpanan data berhasil');
    }
}
