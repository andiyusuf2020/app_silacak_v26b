<?php

namespace App\Controllers\AdminAdbang;

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

class adminadbang extends BaseController
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

    protected $pager;


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

        $this->pager = \Config\Services::pager();
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
        $bulanaktif = $jadwalaktif['bulan'];
        // session()->set('tahun', $tahunaktif);
        $data = [
            'groupuser' => $namagroup,
            'groupmenu' => 'adminprov',
            'titlepage' => 'Selamat Datang di Administrator SiTAPIS Provinsi Lampung',
            'datauser'  => $this->tausermodel->listuser($user->id),
            'tahun'     => $tahunaktif,
            'bulan'     => $bulanaktif,
            'periode' => $periode
        ];
        if (!$tahunaktif) {
            session()->set('groupuser', $namagroup);
            session()->set('groupmenu', 'adminprov');
            return view('pilihtahun', $data);
        }
        $pager = $this->request->getVar('page') ?? 1;
        $data['currentPage'] = $hal;


        if ($hal == 'dataopd') {
            $perPage = 10;
            $data['perPage'] = $perPage;
            $offset = ($pager - 1) * $perPage;
            $data['dataskopd'] = $this->subkegmodel->subkegiatanperopd($kdSU, $perPage, $offset);
            $data['nm_opd'] = $data['dataskopd'][0]['nm_sub_unit'];

            if ($action == 'subkeg') {
                $data['data'] = 'subkeg';
                $totalRecords = count($this->subkegmodel->subkegiatanperopd($kdSU)); //$this->subkegmodel->countAllOpd();
                $data['totalRecords'] = $totalRecords;
                $data['pager'] = $this->subkegmodel->pager = $this->pager->makeLinks($pager, $perPage, $totalRecords, 'bootstrap_pagination');
                // echo dd($data['dataskopd']);
                return view('superadmin/v_datasubkegiatan', $data);
            }
            if ($action == 'belanja') {
                $data['data'] = 'belanja';
                $totalRecords = count($this->subkegmodel->subkegiatanperopd($kdSU)); //$this->subkegmodel->countAllOpd();
                $data['totalRecords'] = $totalRecords;
                $data['pager'] = $this->subkegmodel->pager = $this->pager->makeLinks($pager, $perPage, $totalRecords, 'bootstrap_pagination');
                // echo dd($data['dataskopd']);
                return view('superadmin/v_datasubkegiatan', $data);
            }
            if ($action == 'Rbelanja') {
                $data['data'] = 'Rbelanjasubkeg';
                $totalRecords = count($this->subkegmodel->RBelOpd($kdSU, $kdSK)); //$this->subkegmodel->countAllOpd();
                $data['totalRecords'] = $totalRecords;
                $data['pager'] = $this->subkegmodel->pager = $this->pager->makeLinks($pager, $perPage, $totalRecords, 'bootstrap_pagination');

                $data['dBelOpd'] = $this->subkegmodel->RBelOpd($kdSU, $kdSK);
                // echo dd($dBelOpd);
                return view('superadmin/v_datasubkegiatan', $data);
            }

            if ($action == 'aktifitas') {
                $data['data'] = 'DAktifitasopd';

                $data['aktifitasopd'] = $this->kegpokokmodal->DataPerOpd($tahunaktif, $kdSU);
                return view('superadmin/v_datasubkegiatan', $data);

                // echo dd($data['aktifitasopd']);
            }
            if ($action == 'RAktifitas') {
                $data['data'] = 'RDAktifitasopd';

                $data['raktifitasopd'] = $this->rkegpokokmodal->DataPerKegPokok($kdA);
                return view('superadmin/v_detailaktivitas', $data);

                // echo dd($data['raktifitasopd']);
            }
        }
        if ($hal == 'rekapperopd') {
            if ($action == 'pilihperiode') {
                if (empty($periode)) {
                    // $jadwalall = $this->jadwalmodel->findAll();
                    $data['hal'] = $hal;
                    $data['datajadwal'] =  $this->jadwalmodel->findAll();
                    return view('superadmin/v_pilihperiode', $data);
                }
            }
            if ($action == 'allopd') {

                $perPage = 5;
                $data['perPage'] = $perPage;
                $offset = ($pager - 1) * $perPage;

                $totalRecords = $this->subkegmodel->countAllOpd();
                $data['totalRecords'] = $totalRecords;
                // Initialize pager
                // $data['pager'] = $this->userModel->pager = $this->pager->makeLinks($hal, $perPage, $totalRecords);
                $data['pager'] = $this->subkegmodel->pager = $this->pager->makeLinks($pager, $perPage, $totalRecords, 'bootstrap_pagination');

                $data['listopd'] = $this->subkegmodel->listopdAll($perPage, $offset);
                $data['datarekapdokcapkin'] = $this->rdkegpokokmodal->DataDokPerSubUnit($tahunaktif, $periode);
                $data['bulan'] = $periode;
                // echo dd($data['listopd']);
                return view('superadmin/v_rekapopd', $data);
            }
        }
        if ($hal == 'rekap') {
            if ($action == 'all') {
                if (empty($periode)) {
                    // $jadwalall = $this->jadwalmodel->findAll();
                    $data['hal'] = $hal;
                    $data['datajadwal'] =  $this->jadwalmodel->findAll();
                    return view('superadmin/v_pilihperiode', $data);
                }
                $data['datarekaplrfk'] = $this->realisasilrfkrinci->rekapallopd($tahunaktif, $periode);
                $data['datatotalpagu'] = $this->realisasilrfkrinci->rekapagu($tahunaktif, $periode);
                $data['totapbd'] = $this->subkegmodel->selectSUM('pagu_rincian')->get()->getRowArray();
                $data['bulan'] = $periode;
                // echo dd($data['totapbd']);
                return view('superadmin/v_rekaplrfkopd', $data);
            }
        }
        if ($hal == 'cetakrekaplrfk') {
            if ($action == 'all') {
                $data['datarekaplrfk'] = $this->realisasilrfkrinci->rekapallopd($tahunaktif, $periode);
                $data['datatotalpagu'] = $this->realisasilrfkrinci->rekapagu($tahunaktif, $periode);
                $data['totapbd'] = $this->subkegmodel->selectSUM('pagu_rincian')->get()->getRowArray();

                $data['bulan'] = $periode;
                // echo dd($data['datarekaplrfk']);
                // return view('superadmin/cetakrekaplrfk', $data);
                $html = view('superadmin/cetakrekaplrfk', $data);
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
                $pdf->Output('Laporan_Rekapitulasi_LRFK' . $tahunaktif, 'I');
            }
        }
        if ($hal == 'rekapcapkin') {
            if ($action == 'all') {
                if (empty($periode)) {
                    $data['hal'] = $hal;
                    // $jadwalall = $this->jadwalmodel->findAll();
                    $data['datajadwal'] =  $this->jadwalmodel->findAll();
                    return view('superadmin/v_pilihperiode', $data);
                }
                $data['listopd'] = $this->subkegmodel->listopd(null, $tahunaktif);
                $data['datarekapdokcapkin'] = $this->rdkegpokokmodal->DataDokPerSubUnit($tahunaktif, $periode);
                $data['bulan'] = $periode;
                // echo dd($data['listopd']);
                return view('superadmin/v_rekapcapkin', $data);
            }
        }
        if ($hal == 'rekapcek') {
            if ($action == 'pilihprogram') {
                $data['dataskcapkinopd'] = $this->targetsubkegmodel->DataPerOpd($tahunaktif, $data['datauser']['kd_sub_unit']);
                // echo dd($data['dataskcapkinopd']);
                $data['tahunaktif'] = $tahunaktif;
                $mappingsasaran = $this->kegpokokmodal->select('*')->where('tahun', $tahunaktif)->where('kd_subunit', $kdSU)
                    ->where('delete_at=', 0)->where('id_progprioritas<>', 0)->orderBy('update_at', 'DESC')->get()->getResultArray();
                $data['sasaranterupdete'] = $mappingsasaran[0]['update_at'];
                $data['jmlmappingprio'] = count($mappingsasaran) ?? null; // Mengetahui jumlah elemen
                $mappingunggulan = $this->kegpokokmodal->select('*')->where('tahun', $tahunaktif)->where('kd_subunit', $kdSU)
                    ->where('delete_at=', 0)->where('id_progunggulan<>', 0)->orderBy('update_at', 'DESC')->get()->getResultArray();
                $data['unggulanterupdete'] = $mappingunggulan[0]['update_at'] ?? null;
                $data['jmlmappingunggulan'] = count($mappingunggulan ?? null); // Mengetahui jumlah elemen
                $mappingtematik = $this->kegpokokmodal->select('*')->where('tahun', $tahunaktif)->where('kd_subunit', $kdSU)
                    ->where('delete_at=', 0)->where('id_progtematik<>', 0)->orderBy('update_at', 'DESC')->get()->getResultArray();
                $data['jmlmappingtematik'] = count($mappingtematik ?? null); // Mengetahui jumlah elemen
                $data['tematikterupdete'] = $mappingtematik[0]['update_at'] ?? null;

                $data['cekopdunggulan'] = $this->opdprogunggulan->cekopd($kdSU);
                $data['cekopdtematik'] = $this->opdprogtematik->cekopd($kdSU);

                $data['dataopd'] = $this->subkegmodel->datasubunit($kdSU);
                $data['bulan'] = $periode;
                // echo dd($data['unggulanterupdete']);

                return view('superadmin/pilihprogramrpjmd', $data);
            }
            // if ($action == 'sasaran') {
            //     $data['progprio'] = $this->kegpokokmodal->DataPerPprioOPD($tahunaktif, $kdSU);
            //     $data['dataopd'] = $this->subkegmodel->datasubunit($kdSU);
            //     $data['tahunaktif'] = $tahunaktif;

            //     $data['dataskcapkinopd'] = $this->targetsubkegmodel->DataPerOpd($tahunaktif, $kdSU);
            //     // echo dd($data['progprio']);
            //     return view('superadmin/v_ceksasaranopd', $data);
            // }
            if ($action == 'listsasaran') {
                $data['progprio'] = $this->kegpokokmodal->DataPerPprioOPD($tahunaktif, $kdSU);
                $data['dataopd'] = $this->subkegmodel->datasubunit($kdSU);
                $data['tahunaktif'] = $tahunaktif;
                $data['bulanpilih'] = $periode;
                $data['dataskcapkinopd'] = $this->targetsubkegmodel->DataPerOpd($tahunaktif, $kdSU);
                // echo dd($data['progprio']);
                return view('superadmin/v_ceksasaranopd', $data);
            }

            // if ($action == 'unggulan') {
            //     $data['progunggulan'] = $this->kegpokokmodal->DataPerPUnggulOPD($tahunaktif, $kdSU);
            //     $data['dataopd'] = $this->subkegmodel->datasubunit($kdSU);
            //     $data['tahunaktif'] = $tahunaktif;

            //     $data['dataskcapkinopd'] = $this->targetsubkegmodel->DataPerOpd($tahunaktif, $kdSU);
            //     // echo dd($data['progunggulan']);
            //     return view('superadmin/v_cekunggulanopd', $data);
            // }
            if ($action == 'listunggulan') {
                $data['progunggulan'] = $this->kegpokokmodal->DataPerPUnggulOPD($tahunaktif, $kdSU);
                $data['dataopd'] = $this->subkegmodel->datasubunit($kdSU);
                $data['tahunaktif'] = $tahunaktif;
                $data['bulanpilih'] = $periode;

                $data['dataskcapkinopd'] = $this->targetsubkegmodel->DataPerOpd($tahunaktif, $kdSU);
                // echo dd($data['progunggulan']);
                return view('superadmin/v_cekunggulanopd', $data);
            }
            // if ($action == 'tematik') {
            //     $data['progtematik'] = $this->kegpokokmodal->DataPerTematikOPD($tahunaktif, $kdSU);
            //     $data['dataopd'] = $this->subkegmodel->datasubunit($kdSU);
            //     $data['tahunaktif'] = $tahunaktif;

            //     $data['dataskcapkinopd'] = $this->targetsubkegmodel->DataPerOpd($tahunaktif, $kdSU);
            //     // echo dd($data['progunggulan']);
            //     return view('superadmin/v_datatematikopd', $data);
            // }
            if ($action == 'listtematik') {
                $data['progtematik'] = $this->kegpokokmodal->DataPerTematikOPD($tahunaktif, $kdSU);
                $data['dataopd'] = $this->subkegmodel->datasubunit($kdSU);
                $data['tahunaktif'] = $tahunaktif;
                $data['bulanpilih'] = $periode;

                $data['dataskcapkinopd'] = $this->targetsubkegmodel->DataPerOpd($tahunaktif, $kdSU);
                // echo dd($data['progunggulan']);
                return view('superadmin/v_cektematikopd', $data);
            }
            if ($action == 'listsubkegiatan') {
                $data['dataskcapkinopd'] = $this->targetsubkegmodel->DataPerOpd($tahunaktif, $kdSU);
                // echo dd($data['dataskcapkinopd']);
                return view('superadmin/v_listsubkegiatan', $data);
            }
        }
        $idD = $receivedParams['idD'] ?? null;

        if ($idD) {
            $data = [
                'title' => 'Detail Lokasi',
                'lokasi' => $this->rdkegpokokmodal->getLokasi(null, null, $idD)
            ];
            // echo dd($data['lokasi']);
            if (empty($data['lokasi'])) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Lokasi dengan ID ' . $idD . ' tidak ditemukan');
            }
            // echo dd($data['lokasi']);
            return view('capkin/v_detaildok', $data);
        }
    }
}
