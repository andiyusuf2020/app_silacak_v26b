<?php


namespace App\Controllers\CapKinController;

use App\Models\LrfkProvModel\JadwalModel;
use App\Models\UserModel\TaUserModel;
use \Myth\Auth\Authorization\GroupModel;

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
use App\Models\RupModel\SirupModel;
use App\Models\RupModel\RealRupModel;
use App\Models\DataApbdModel\PendApbdModel;
use App\Models\DataApbdModel\RealPendApbdModel;
use App\Models\DataApbdModel\AngkasModel;
use App\Models\CapkinModel\TaSubKegCapkin2026;
use App\Models\CapkinModel\TaPermasalahanAktifitas;
use App\Models\CapkinModel\TaNomenklaturModel;



class CapKin26 extends BaseController
{

    protected $jadwalmodel;
    // protected $subkegmodel;
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
    protected $sirupmodel;
    protected $realrupmodel;
    protected $pendapatanmodel;
    protected $realpendapatanmodel;
    protected $angkasmodel;
    protected $subkegcapkin2026model;
    protected $permasalahanaktifitasmodel;
    protected $tanomenklaturmodel;

    public function __construct()
    {
        // helper(['form']);
        $this->tausermodel = new TaUserModel();
        $this->jadwalmodel = new JadwalModel();
        // $this->subkegmodel = new SubKegModel();
        // $this->realisasilrfk = new RealisasiSubKegModel();
        // $this->totalrealisasiM = new TotalRealisasiModel();
        // $this->taindikator = new TaIndikatorProgModal();
        // $this->taindisubkeg = new TaIndikatorSubKegModal();
        // $this->realisasilrfkrinci = new TaRealisasiRinciModel();

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
        $this->sirupmodel = new SirupModel();
        $this->realrupmodel = new RealRupModel();
        $this->pendapatanmodel = new PendApbdModel();
        $this->realpendapatanmodel = new RealPendApbdModel();
        $this->angkasmodel = new AngkasModel();
        $this->subkegcapkin2026model = new TaSubKegCapkin2026();
        $this->permasalahanaktifitasmodel = new TaPermasalahanAktifitas();
        $this->tanomenklaturmodel = new TaNomenklaturModel();
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
        $this->ValidasiHash($req);

        $receivedParams = $req->getGet();
        $hal = $receivedParams['hal'] ?? null;
        $action = $receivedParams['action'] ?? null;
        $idKP = $receivedParams['idKP'] ?? null;
        $idRKP = $receivedParams['idRKP'] ?? null;
        $idPR = $receivedParams['idPR'] ?? null;
        $idDRKP = $receivedParams['idDRKP'] ?? null;

        $user = user();
        $tahunaktif =  session()->get('tahun'); //   $jadwalaktif['tahun'];
        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        $bulanaktif = $jadwalaktif['bulan'];
        $tglaktif = session()->get('tglaktif');
        $groupname = session()->get('groupuser');
        $datauser = $this->tausermodel->listuser($user->id);
        $id_subgiatmapping = $receivedParams['id_subgiatmapping'] ?? null;


        $data = [
            'groupuser' => $groupname,
            'groupmenu' => 'usercapkinprov',
            'titlepage' => 'Selamat Data di e-TAPIS Laporan Aktifitas Program Kegiatan Perangkat Daerah Provinsi Lampung',
            'datauser' => $this->tausermodel->listuser($user->id),
            // 'jadwalaktif' => $this->jadwalmodel->jadwalaktifskrg(),
            'tahunaktif' => $tahunaktif,
            'tglaktif' => $tglaktif,
            'bulanaktif' => $bulanaktif,
            'subkegcapkindipilih' => $this->subkegcapkin2026model->find($id_subgiatmapping),
            'tahunaktif' => $tahunaktif,
            'id_subgiatmapping' => $id_subgiatmapping,
            'idKP'    => $idKP
        ];
        if (!$tahunaktif) {
            session()->set('groupuser', $namagroup);
            session()->set('groupmenu', 'usercapkinprov');
            return view('pilihtahun', $data);
        }
        $data['subgiat'] = $this->realapbdmodel->rpersk($data['datauser']['sub_unit'], $tahunaktif, $jadwalaktif['bulan'], $tglaktif);

        if (!$receivedParams) {
            echo "Tidak ada parameter yang diterima.";
        }
        if ($hal == 'realisasikegpokok') {
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
                $this->rkegpokokmodal->delete($idRKP);
                return redirect()->back()->withInput()->with('message', 'Realisasi Kegiatan Pokok Berhasil Dihapus');

                // return view('capkin/form_editRkegpokok', $data);
            }
        }
        if ($hal == 'cetaksasaran' && $action == 'all') {

            $data = [
                'groupuser' => $groupname,
                'groupmenu' => 'usercapkinprov',
                'titlepage' => 'Selamat Data di e-TAPIS Laporan Aktifitas Program Kegiatan Perangkat Daerah Provinsi Lampung',
                'datauser' => $this->tausermodel->listuser($user->id),
                // 'jadwalaktif' => $this->jadwalmodel->jadwalaktifskrg(),
                'tahunaktif' => $tahunaktif,
                'tglaktif' => $tglaktif,
                'bulan' => $bulanaktif,
                'subkegcapkindipilih' => $this->subkegcapkin2026model->find($id_subgiatmapping),
                'tahun' => $tahunaktif,
                'id_subgiatmapping' => $id_subgiatmapping,
                'idKP'    => $idKP
            ];
            $data['sasaranopd'] = $this->kegpokokmodal->DataPerSasaranPerOPD26($data['tahun'], $data['datauser']['kd_sub_unit']);
            // echo dd($data['sasaranopd']);

            // $data['tahunaktif'] = $tahunaktif;
            // $data['bulantw'] = $periode;
            // $data['bulan'] = $bulanaktif;
            $html = view('/capkin/2026/cetaksasaran', $data);
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
        if ($hal == 'realisasi' && $action == 'hasilmappingsubgiat') {
            $this->ValidasiHash($req);
            $data['datasubgiatcapkinopd'] = $this->subkegcapkin2026model->DataPerSKPerSkpd($tahunaktif, $bulanaktif, $tglaktif, $data['datauser']['kd_sub_unit']);

            $data['jmlsubkegtermapping'] = count($data['datasubgiatcapkinopd']);
            $data['tahunaktif'] = $tahunaktif;
            // $mappingsasaran = $this->kegpokokmodal->select('*')->where('tahun', $tahunaktif)->where('kd_subunit', $data['datauser']['kd_sub_unit'])
            //     ->where('delete_at=', 0)->where('id_progprioritas<>', 0)->orderBy('update_at', 'DESC')->get()->getResultArray();
            $mappingsasaran = $this->kegpokokmodal->DataPerPprioOPD26($tahunaktif, $data['datauser']['kd_sub_unit']);
            $data['sasaranterupdete'] = $mappingsasaran[0]['update_at'] ?? null;
            $data['jmlmappingprio'] = count($mappingsasaran) ?? null; // Mengetahui jumlah elemen
            // echo dd($data['datasubgiatcapkinopd']);
            return view('capkin/2026/v_hasilmapping', $data);
        }
        if ($hal == 'realisasi' && $action == 'permasalahanaktifitas') {
            $this->ValidasiHash($req);
            $data['datakp'] = $this->kegpokokmodal->DataPerIdKegPokok26($idKP);

            // $data['datarkp'] = $this->rkegpokokmodal->DataPerID_R($idRKP);
            // echo dd($data['datakp']);
            return view('capkin/2026/form_inputPermasalahan', $data);
        }
        if ($hal == 'realisasi' && $action == 'ubahpermasalahanaktifitas') {
            $this->ValidasiHash($req);
            $data['dataPkp'] = $this->permasalahanaktifitasmodel->DataPerID_R26($idPR);
            // $data['datarkp'] = $this->rkegpokokmodal->DataPerID_R26($idRKP);

            // echo dd($data['dataPkp']);
            return view('capkin/2026/form_editPermasalahan', $data);
        }
        if ($hal == 'realisasi' && $action == 'hapuspermasalahanaktifitas') {
            $this->ValidasiHash($req);
            $data['dataPkp'] = $this->permasalahanaktifitasmodel->DataPerID_R26($idPR);
            if ($data['dataPkp']) {
                $this->permasalahanaktifitasmodel->delete($idPR);
                return redirect()->back()->withInput()->with('message', 'Data Permasalahan Aktifitas berhasil dihapus.');
            } else {
                return redirect()->back()->withInput()->with('message', 'Data Permasalahan Aktifitas tidak ditemukan.');
            }
        }

        if ($hal == 'realisasi' && $action == 'inputrealisasiaktifitas') {
            $this->ValidasiHash($req);
            $data['datakp'] = $this->kegpokokmodal->DataPerIdKegPokok26($idKP);

            // $data['datarkp'] = $this->rkegpokokmodal->DataPerID_R($idRKP);
            // echo dd($data['datakp']);
            return view('capkin/2026/form_inputRaktifitas', $data);
        }
        if ($hal == 'realisasi' && $action == 'ubahrealisasiaktifitas') {
            $this->ValidasiHash($req);
            $data['datarkp'] = $this->rkegpokokmodal->DataPerID_R26($idRKP);
            // echo dd($data['datarkp']);
            return view('capkin/2026/form_editRaktifitas', $data);
        }
        if ($hal == 'realisasi' && $action == 'hapusrealisasiaktifitas') {
            $this->ValidasiHash($req);
            $data['datarkp'] = $this->rkegpokokmodal->DataPerID_R26($idRKP);
            if ($data['datarkp']) {
                $this->rkegpokokmodal->delete($idRKP);
                return redirect()->back()->withInput()->with('message', 'Data Realisasi Aktifitas berhasil dihapus.');
            } else {
                return redirect()->back()->withInput()->with('message', 'Data Realisasi Aktifitas tidak ditemukan.');
            }
        }

        if ($hal == 'dokumenrealisasi' && $action == 'inputdokumenrealisasiaktifitas') {
            $this->ValidasiHash($req);

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
            // echo dd($data);
            $data['datarkp'] = $this->rkegpokokmodal->DataPerID_R26($idRKP);

            // echo dd($data['datarkp']);
            // $data['datarkp'] = $this->rkegpokokmodal->DataPerID_R($idRKP);
            return view('capkin/2026/form_inputdokument', $data);
        }
        if ($hal == 'dokumenrealisasi' && $action == 'ubahdokumentasirealisasiaktifitas') {
            $this->ValidasiHash($req);
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

            $data['dataDR'] = $this->rdkegpokokmodal->DataPerDRKegPokok26($idDRKP);

            // echo dd($data['dataDR']);

            return view('capkin/2026/form_editdokument', $data);
        }
        if ($hal == 'dokumenrealisasi' && $action == 'hapusdokumentasirealisasiaktifitas') {
            $this->ValidasiHash($req);
            $data['dataDR'] = $this->rdkegpokokmodal->DataPerDRKegPokok26($idDRKP);
            if ($data['dataDR']) {
                $this->rdkegpokokmodal->delete($idDRKP);
                return redirect()->back()->withInput()->with('message', 'Dokumentasi Realisasi Aktifitas berhasil dihapus.');
            } else {
                return redirect()->back()->withInput()->with('message', 'Dokumentasi Realisasi Aktifitas tidak ditemukan.');
            }
        }
        if ($hal == 'rencana' && $action == 'mappingsubgiat') {
            $this->ValidasiHash($req);
            $data['subgiat'] = $this->realapbdmodel->rpersk($datauser['sub_unit'], $tahunaktif, $bulanaktif, $tglaktif);

            // $data['datasubgiatcapkinopd'] = $this->subkegcapkin2026model->DataPerSKPerSkpd($tahunaktif, $bulanaktif, $tglaktif, $data['datauser']['kd_sub_unit']);
            $data['datasubgiatcapkinopd'] = $this->subkegcapkin2026model->DataPerSKPerSkpdpertahun($tahunaktif, $data['datauser']['kd_sub_unit']);

            $data['jmlsubkegtermapping'] = count($data['datasubgiatcapkinopd']);
            $data['tahunaktif'] = $tahunaktif;
            $mappingsasaran = $this->kegpokokmodal->DataPerPprioOPD26($tahunaktif, $data['datauser']['kd_sub_unit']);
            // ->select('*')
            // ->where('tahun', $tahunaktif)
            // ->where('kd_subunit', $data['datauser']['kd_sub_unit'])
            // ->where('delete_at=', 0)
            // ->where('id_progprioritas<>', 0)
            // ->orderBy('update_at', 'DESC')->get()->getResultArray();
            // echo dd($mappingsasaran);
            $data['sasaranterupdete'] = $mappingsasaran[0]['update_at'] ?? null;
            $data['jmlmappingprio'] = count($mappingsasaran) ?? null; // Mengetahui jumlah elemen
            // echo dd($data['datasubgiatcapkinopd']);
            // echo dd($mappingsasaran);
            return view('capkin/2026/v_capkinopd', $data);
        }
        if ($hal == 'rencana' && $action == 'inputrencanaaktifitas') {
            $this->ValidasiHash($req);
            $data['datasubgiatcapkinopd'] = $this->subkegcapkin2026model->DataPerSKPerSkpd($tahunaktif, $bulanaktif, $tglaktif, $data['datauser']['kd_sub_unit']);
            $dataprogprioritas =  $this->progprioritas->findAll(); //($data['datauser']['kd_sub_unit']); //$this->db1->table('ta_agenda_aku')->select('*')->get()->getResult();
            $data['dataprogprioritas'] = ['' => 'pilih']  + array_column($dataprogprioritas, 'nm_progprioritas', 'id_pprio');
            // echo dd($data['subkegcapkindipilih']);
            return view('capkin/2026/form_inputrencanaaktifitas', $data);
        }
        if ($hal == 'rencana' && $action == 'ubahrencanaaktifitas') {
            $this->ValidasiHash($req);
            $data['datakegpokok'] = $this->kegpokokmodal->DataPerIdKegPokok26($idKP);
            $dataprogprioritas =  $this->progprioritas->findAll(); //($data['datauser']['kd_sub_unit']); //$this->db1->table('ta_agenda_aku')->select('*')->get()->getResult();
            $data['dataprogprioritas'] = ['' => $data['datakegpokok']['nm_progprioritas']]  + array_column($dataprogprioritas, 'nm_progprioritas', 'id_pprio');
            return view('capkin/2026/form_editrencanaaktifitas', $data);
        }
        if ($hal == 'rencana' && $action == 'hapusrencanaaktifitas') {
            $this->ValidasiHash($req);
            $data['datakegpokok'] = $this->kegpokokmodal->DataPerIdKegPokok26($idKP);
            if ($data['datakegpokok']) {
                $this->kegpokokmodal->delete($idKP);
                return redirect()->back()->withInput()->with('peringatan', 'Sub Kegiatan berhasil dihapus.');
            } else {
                return redirect()->back()->withInput()->with('peringatan', 'Sub Kegiatan tidak ditemukan.');
            }
        }

        if ($hal == 'rencana' && $action == 'hapussubkegtermapping') {
            $this->ValidasiHash($req);
            $id = $receivedParams['id_subgiatmapping'] ?? null;
            $dataSubKeg = $this->subkegcapkin2026model->find($id);
            if ($dataSubKeg) {
                $this->subkegcapkin2026model->delete($id);
                return redirect()->back()->withInput()->with('peringatan', 'Sub Kegiatan berhasil dihapus.');
            } else {
                return redirect()->back()->withInput()->with('peringatan', 'Sub Kegiatan tidak ditemukan.');
            }
        }
        if ($hal == 'mappingsubkeg' && $action == 'tambah') {
            $this->ValidasiHash($req);
            // $data['sknoadum'] =  $this->subkegmodel->SKKegPokokOPD2($data['datauser']['kd_sub_unit']); //$this->db1->table('ta_agenda_aku')->select('*')->get()->getResult();
            $data['kd_subunit'] = $data['datauser']['kd_sub_unit'];
            $data['idUbah'] = '';
            $data['program'] = $this->realapbdmodel->listprogram($datauser['sub_unit'], $tahunaktif, $bulanaktif, $tglaktif);
            $data['subgiat2'] = $this->realapbdmodel->lissubkegiatan2($datauser['sub_unit'], $tahunaktif, $tglaktif);
            // $data['subgiat2'] = $this->realapbdmodel->rpersk($datauser['sub_unit'], $tahunaktif, $bulanaktif, $tglaktif);

            // echo dd($data['program']);
            return view('capkin/2026/form_pilihprogram', $data);
        }
    }
    public function pilihprogram()
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
        $this->ValidasiHash($req);
        $user = user();
        $tahunaktif =  session()->get('tahun'); //   $jadwalaktif['tahun'];
        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        $bulanaktif = $jadwalaktif['bulan'];
        $tglaktif = session()->get('tglaktif');
        $groupname = session()->get('groupuser');
        $datauser = $this->tausermodel->listuser($user->id);

        $data = [
            'groupuser' => $namagroup,
            'groupmenu' => 'usercapkinprov',
            'titlepage' => 'Selamat Datang di e-TAPIS Laporan Capaian Kinerja Output Perangkat Daerah Provinsi Lampung',
            'datauser'  => $this->tausermodel->listuser($user->id),
            'twaktif'  => $this->twmodel->twaktif(),
            'tahun'     => $tahunaktif,
            'bulan'     => $bulanaktif,
            'kode_program' => $this->request->getVar('program') ?? null,
            'kode_kegiatan' => $this->request->getVar('kode_kegiatan') ?? null,
            'kode_sub_giat' => $this->request->getVar('kode_sub_giat') ?? null,
            'idUbah' => null

        ];
        // $program = $this->request->getVar('program');
        // echo dd($data['program']);
        if ($data['kode_program'] && !$data['kode_kegiatan'] && !$data['kode_sub_giat']) {
            $data['kegiatan'] = $this->realapbdmodel->liskegiatan($datauser['sub_unit'], $data['tahun'], $tglaktif, $data['kode_program']);
            // echo dd($datauser['sub_unit'] . ', ' . $data['tahun'] . ', ' . $tglaktif . ', ' . $data['program']);
            // echo dd($data['kegiatan']);
            return view('capkin/2026/form_pilihkegiatan', $data);
        }
        if ($data['kode_kegiatan'] && $data['kode_program'] && !$data['kode_sub_giat']) {
            $data['subgiat'] = $this->realapbdmodel->lissubkegiatan($datauser['sub_unit'], $data['tahun'], $tglaktif, $data['kode_program'], $data['kode_kegiatan']);
            // echo dd($data['subgiat']);
            return view('capkin/2026/form_pilihsubkeg', $data);
        }
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
            // $datarkp = $this->rkegpokokmodal->DataPerID_R26($idRKP);
            $datarkp = $this->rdkegpokokmodal->DataPerDRKegPokok26($idDR);
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

            $datarkp = $this->rkegpokokmodal->DataPerID_R26($idRKP);

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
        return redirect()->to(hash_url('capkin2026', ['hal' => 'rencana', 'action' => 'hasilmappingsubgiat']))->withInput()->with('message', 'penyimpanan data berhasil.');

        // return redirect()->back()->withInput()->with('message', 'penyimpanan data berhasil');
    }
    public function simpanpermasalahanaktifitas()
    {
        $id_Per = $this->request->getPost('id_Per');
        // echo dd($id_r);
        $datakegpokok = $this->kegpokokmodal->DataPerIdKegPokok($this->request->getPost('idKP'));
        // echo dd($datakegpokok);
        if ($id_Per) {
            $dataPkp = [
                'id_Pr' => $id_Per,
                'id_targetsubkeg' => $datakegpokok['id_targetsubkeg'],
                'id_kegpokok' => $this->request->getPost('idKP'),
                'id_progprioritas' => $datakegpokok['id_progprioritas'],
                'id_progunggulan' => $datakegpokok['id_progunggulan'],
                'id_progtematik' => $datakegpokok['id_progtematik'],
                'tahun' => $datakegpokok['tahun'],
                'bulan' => $this->request->getPost('bulan'),
                'kd_subunit' => $datakegpokok['kd_subunit'],
                'permasalahan' => $this->request->getPost('permasalahan'),
            ];
        } else {
            $dataPkp = [
                'id_targetsubkeg' => $datakegpokok['id_targetsubkeg'],
                'id_kegpokok' => $this->request->getPost('idKP'),
                'id_progprioritas' => $datakegpokok['id_progprioritas'],
                'id_progunggulan' => $datakegpokok['id_progunggulan'],
                'id_progtematik' => $datakegpokok['id_progtematik'],
                'tahun' => $datakegpokok['tahun'],
                'bulan' => $this->request->getPost('bulan'),
                'kd_subunit' => $datakegpokok['kd_subunit'],
                'permasalahan' => $this->request->getPost('permasalahan'),
            ];
        }

        // echo dd($dataPkp);
        $this->permasalahanaktifitasmodel->save($dataPkp);
        // return redirect()->back()->withInput()->with('message', 'penyimpanan data berhasil');
        return redirect()->to(hash_url('capkin2026', ['hal' => 'realisasi', 'action' => 'hasilmappingsubgiat']))->withInput()->with('message', 'Data Permasalahan Aktifitas berhasil disimpan.');
    }
    public function simpanrealisasiaktifitas()
    {
        $id_r = $this->request->getPost('id_r');
        // echo dd($id_r);
        $datakegpokok = $this->kegpokokmodal->DataPerIdKegPokok($this->request->getPost('idKP'));
        // echo dd($datakegpokok);
        if ($id_r) {
            $datarkp = [
                'id_r' => $id_r,
                'id_targetsubkeg' => $datakegpokok['id_targetsubkeg'],
                'id_kegpokok' => $this->request->getPost('idKP'),
                'id_progprioritas' => $datakegpokok['id_progprioritas'],
                'id_progunggulan' => $datakegpokok['id_progunggulan'],
                'id_progtematik' => $datakegpokok['id_progtematik'],
                'tahun' => $datakegpokok['tahun'],
                'bulan' => $this->request->getPost('bulan'),
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
                'tahun' => $datakegpokok['tahun'],
                'bulan' => $this->request->getPost('bulan'),
                'kd_subunit' => $datakegpokok['kd_subunit'],
                'r_target' => $this->request->getPost('r_target'),
                'sat_target' => $datakegpokok['sat_target'],
                'r_uraian' => $this->request->getPost('r_uraian'),

            ];
        }

        // echo dd($datarkp);
        $this->rkegpokokmodal->save($datarkp);
        return redirect()->to(hash_url('capkin2026', ['hal' => 'realisasi', 'action' => 'hasilmappingsubgiat']))->withInput()->with('message', 'Data Realisasi Aktifitas berhasil disimpan.');

        // return redirect()->back()->withInput()->with('message', 'penyimpanan data berhasil');
    }
    public function simpanrencanaaktifitas()
    {
        $datakegpokok = $this->request->getPost('data');
        // echo dd($datakegpokok);
        if (empty($datakegpokok[0]['id_kp'])) {
            // echo dd($datakegpokok[0]['id_kp']);
            $datakeg = $this->request->getPost('data');
            $jumlah = count($datakeg); // Mengetahui jumlah elemen
            for ($i = 0; $i < $jumlah; $i++) {
                if ($datakeg[$i]['id_progprioritas'] == '') {
                    return redirect()->back()->withInput()->with('peringatan', 'Salah Satu Kegiatan Pokok/Aktifitas Belum termapping ke Sasaran Pembangunan');
                }
            }
            $this->kegpokokmodal->insertBatch($datakegpokok);
            // return redirect()->back()->withInput()->with('message', 'penyimpanan data berhasil');
            return redirect()->to(hash_url('capkin2026', ['hal' => 'rencana', 'action' => 'mappingsubgiat']))->withInput()->with('message', 'penyimpanan data berhasil.');
        } else {
            if ($datakegpokok[0]['id_progprioritas'] == '') {
                $datakegpokokX = $this->kegpokokmodal->DataPerIdKegPokok($datakegpokok[0]['id_kp']);
                $datakegpokok[0]['id_progprioritas'] = $datakegpokokX['id_progprioritas'];
            }
            // if ($datakegpokok[0]['id_progunggulan'] == '') {
            //     $datakegpokokY = $this->kegpokokmodal->DataPerIdKegPokok($datakegpokok[0]['id_kp']);
            //     $datakegpokok[0]['id_progunggulan'] = $datakegpokokY['id_progunggulan'];
            // }
            // if ($datakegpokok[0]['id_progtematik'] == '') {
            //     $datakegpokokZ = $this->kegpokokmodal->DataPerIdKegPokok($datakegpokok[0]['id_kp']);
            //     $datakegpokok[0]['id_progtematik'] = $datakegpokokZ['id_progtematik'];
            // }
            // echo dd($datakegpokok);
            $this->kegpokokmodal->updateBatch($datakegpokok, 'id_kp');
            // return redirect()->to(base_url('capkin/kegpokok'))->withInput()->with('message', 'penyimpanan data berhasil');
            // return redirect()->back()->withInput()->with('message', 'Kegiatan Pokok Berhasil diubah');
            return redirect()->to(hash_url('capkin2026', ['hal' => 'rencana', 'action' => 'mappingsubgiat']))->withInput()->with('message', 'Rencana Aktifitas Berhasil DiUbah.');
        }
        // // echo dd($receivedParams);
        // // Validasi input
        // if (empty($receivedParams['data'])) {
        //     return redirect()->back()->withInput()->with('message', 'Data rencana aktifitas tidak ditemukan.');
        // }
        // $dataRencanaAktifitas = $receivedParams['data'];
        // // echo dd($dataRencanaAktifitas);
        // foreach ($dataRencanaAktifitas as $index => $rencana) {
        //     if (empty($rencana['uraian_target']) || empty($rencana['hasil_target'])) {
        //         return redirect()->back()->withInput()->with('message', 'Uraian dan hasil target harus diisi untuk semua rencana aktifitas.');
        //     }
        // }
    }

    public function simpansubkeg()
    {
        $req = $this->request;
        $receivedParams = $req->getPost();
        $tahunaktif = session()->get('tahun');
        $tglaktif = session()->get('tglaktif');
        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        $bulanaktif = $jadwalaktif['bulan'];

        // $datauser = session()->get('datauser');
        // $kd_subunit = $datauser['kd_sub_unit'];

        $idUbah = $receivedParams['idUbah'] ?? null;
        // Validasi input
        if (empty($receivedParams['KODE_SUB_GIAT'])) {
            return redirect()->back()->withInput()->with('message', 'Sub Kegiatan harus diisi.');
        }
        $datasubgiatopd = $this->realapbdmodel->rperskperopd($receivedParams['KODE_UNIT_SKPD'], $receivedParams['KODE_SUB_GIAT'], $tahunaktif, $bulanaktif, $tglaktif);
        $subkeg = $datasubkeg[0] ?? null; // Ambil elemen pertama jika ada, atau null jika tidak ada
        $cekdatasubgiatcapkin = $this->subkegcapkin2026model->DataPerSKPerSkpdSubGiat($tahunaktif, $receivedParams['KODE_UNIT_SKPD'], $receivedParams['KODE_SUB_GIAT']);
        if ($cekdatasubgiatcapkin) {
            return redirect()->to(hash_url('capkin2026', ['hal' => 'rencana', 'action' => 'mappingsubgiat']))->withInput()->with('message', 'Sub Kegiatan sudah ada.');
        } else {
            if ($idUbah) {
                $data =  [
                    'id_skcapkin' => $idUbah,
                    'tahun' => $tahunaktif,
                    'bulan' => $bulanaktif,
                    'tgldata' => $tglaktif,
                    'kd_sub_skpd' => $datasubgiatopd['KODE_UNIT_SKPD'],
                    'nm_sub_skpd' => $datasubgiatopd['NAMA_UNIT_SKPD'],
                    'kd_program' => $datasubgiatopd['KODE_PROGRAM'],
                    'nm_program' => $datasubgiatopd['NAMA_PROGRAM'],
                    'kd_kegiatan' => $datasubgiatopd['KODE_GIAT'],
                    'nm_kegiatan' => $datasubgiatopd['NAMA_GIAT'],
                    'kd_sub_giat' => $datasubgiatopd['KODE_SUB_GIAT'],
                    'nm_sub_giat' => $datasubgiatopd['NAMA_SUB_GIAT'],
                    'total_anggaran' => $datasubgiatopd['anggaran'],
                    'total_realisasi' => $datasubgiatopd['realisasi'],
                    'total_realisasi_spj' => $datasubgiatopd['realisasi_spj']
                ];
                $pesan = 'Subkegiatan Berhasil diUbah';
            } else {
                $data =  [
                    'tahun' => $tahunaktif,
                    'bulan' => $bulanaktif,
                    'tgldata' => $tglaktif,
                    'kd_sub_skpd' => $datasubgiatopd['KODE_UNIT_SKPD'],
                    'nm_sub_skpd' => $datasubgiatopd['NAMA_UNIT_SKPD'],
                    'kd_program' => $datasubgiatopd['KODE_PROGRAM'],
                    'nm_program' => $datasubgiatopd['NAMA_PROGRAM'],
                    'kd_kegiatan' => $datasubgiatopd['KODE_GIAT'],
                    'nm_kegiatan' => $datasubgiatopd['NAMA_GIAT'],
                    'kd_sub_giat' => $datasubgiatopd['KODE_SUB_GIAT'],
                    'nm_sub_giat' => $datasubgiatopd['NAMA_SUB_GIAT'],
                    'total_anggaran' => $datasubgiatopd['anggaran'],
                    'total_realisasi' => $datasubgiatopd['realisasi'],
                    'total_realisasi_spj' => $datasubgiatopd['realisasi_spj']
                ];
            }
            // echo dd($data);
            // Simpan data ke database
            try {
                if ($idUbah) {
                    $this->subkegcapkin2026model->updateBatch([$data], 'id_skcapkin');
                    // return redirect()->to('/capkin2026?hal=rencana&action=all')->with('message', 'Sub Kegiatan berhasil diubah.');
                    // return redirect()->back()->withInput()->with('message', 'Sub Kegiatan berhasil disimpan. ');
                    return redirect()->to(hash_url('capkin2026', ['hal' => 'rencana', 'action' => 'mappingsubgiat']))->withInput()->with('message', 'Sub Kegiatan berhasil diubah.');

                    // echo "dd update data dengan idUbah: $idUbah, KODE_SUB_GIAT: " . $receivedParams['KODE_SUB_GIAT'];
                } else {
                    // Simpan data baru jika idUbah tidak ada
                    $this->subkegcapkin2026model->insertBatchData([$data]);
                    return redirect()->to(hash_url('capkin2026', ['hal' => 'rencana', 'action' => 'mappingsubgiat']))->withInput()->with('message', 'Sub Kegiatan berhasil disimpan.');

                    // return redirect()->back()->withInput()->with('message', ' ');
                }
            } catch (\Exception $e) {
                // Tangani error jika terjadi masalah saat menyimpan data
                return redirect()->back()->withInput()->with('message', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
            }
        }
    }
}
