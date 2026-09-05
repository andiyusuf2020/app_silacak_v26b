<?php

namespace App\Controllers\LrfkController;

use App\Models\LrfkProvModel\JadwalModel;
use App\Models\UserModel\TaUserModel;
use App\Models\LrfkProvModel\SubKegModel; // as lrfkModel;
use \Myth\Auth\Authorization\GroupModel;
use App\Models\LrfkProvModel\RealisasiSubKegModel;
use App\Models\LrfkProvModel\TotalRealisasiModel;
use App\Models\LrfkProvModel\TaIndikatorProgModal;
use App\Models\LrfkProvModel\TaIndikatorSubKegModal;
use App\Models\LrfkProvModel\TaRealisasiRinciModel;
use App\Models\LrfkProvModel\TaPendapatanModel;
use App\Models\LrfkProvModel\TaRealisasiPendapatan;

use App\Libraries\PdfLibrary;
use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;
use FontLib\Table\Type\post;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;
use TCPDF;

class PendapatanOPDController extends BaseController
{

    protected $jadwalmodel;
    protected $subkegmodel;
    protected $tausermodel;
    protected $realisasilrfk;
    protected $realisasilrfkrinci;
    protected $totalrealisasiM;
    protected $tcpdfConfig;
    protected $taindikator;
    protected $taindisubkeg;
    protected $db;

    protected $pendapatan;
    protected $rpendapatan;

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
        $this->tcpdfConfig = new \Config\Tcpdf();
        helper(['form', 'url', 'filesystem']);
        $this->db = db_connect();

        $this->pendapatan = new TaPendapatanModel();
        $this->rpendapatan = new TaRealisasiPendapatan();
    }
    function bulan($bulan)
    {
        if ($bulan == '01') {
            $bln = 'Januari';
        }
        if ($bulan == '02') {
            $bln = 'Februari';
        }
        if ($bulan == '03') {
            $bln = 'Maret';
        }
        if ($bulan == '04') {
            $bln = 'April';
        }
        if ($bulan == '05') {
            $bln = 'Mei';
        }
        if ($bulan == '06') {
            $bln = 'Juni';
        }
        if ($bulan == '07') {
            $bln = 'Juli';
        }
        if ($bulan == '08') {
            $bln = 'Agustus';
        }
        if ($bulan == '09') {
            $bln = 'September';
        }
        if ($bulan == '10') {
            $bln = 'Oktober';
        }
        if ($bulan == '11') {
            $bln = 'November';
        }
        if ($bulan == '12') {
            $bln = 'Desember';
        }
        return $bln;
    }
    public function index()
    {
        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        $tahunaktif = session()->get('tahun'); //   $tahunaktif;
        $bulanaktif = $jadwalaktif['bulan'];
        $req = $this->request;


        // Contoh penggunaan parameter
        $receivedParams = $req->getGet();
        $page = $receivedParams['page'] ?? null;
        $action = $receivedParams['action'] ?? null;
        $kdRek = $receivedParams['kdRek'] ?? null;
        $id = $receivedParams['id'] ?? null;
        $kdSK = $receivedParams['kdSK'] ?? null;
        $kdSU = $receivedParams['kdSU'] ?? null;
        $kdU = $receivedParams['kdU'] ?? null;
        $bulan = $receivedParams['bulan'] ?? null;


        $user = user();
        if (logged_in()) {
            $user = user();
            $groupModel = new GroupModel();
            $groupuser = $groupModel->getGroupsForUser($user->id);
            foreach ($groupuser as $row) {
                $namagroup = $row['name'];
            }
        }
        //$datauser = $this->tausermodel->listuser($user->id);
        $data = [
            'groupuser' => $namagroup,
            'groupmenu' => 'userlrfkprov',
            'titlepage' => 'Selamat Data di e-TAPIS Laporan Pendapatan Daerah, Perangkat Daerah Pengelola Pendapatan Daerah Provinsi Lampung',
            'subtitlepage' => 'Data Anggaran Pendapatan dan Belanja Daerah TA ' . $tahunaktif . '   Perangkat Daerah ',
            'datauser' => $this->tausermodel->listuser($user->id),
            'jadwalaktif' => $jadwalaktif,
            'tahunaktif'  => $tahunaktif
        ];
        $dataopd = $this->subkegmodel->listopd($data['datauser']['sub_unit']);

        //PAD Pajak Daerah
        $data['kdPajak'] = '4.1.01.'; //Kode Rekening Retribusi
        $data['pajak'] = 'Pajak Daerah';
        $padpajak = $this->pendapatan->perOPDObjek($dataopd['kd_skpd'], $data['kdPajak']);
        $data['opdPajak'] = $padpajak['pagu'];
        //  $data['RPendapatanBlnPajak'] = $this->rpendapatan->cekperKdAkun($tahunaktif, $bulanaktif, $dataopd['kd_skpd'], $data['kdPajak']);
        if ($bulan) {
            $data['RPendapatanBlnPajakAktif'] = $this->rpendapatan->Rbulanakhirperakun($tahunaktif, $bulan, $dataopd['kd_skpd'], $data['kdPajak']);
        } else {
            $data['RPendapatanBlnPajakAktif'] = $this->rpendapatan->Rbulanakhirperakun($tahunaktif, $bulanaktif, $dataopd['kd_skpd'], $data['kdPajak']);
        }

        // $databln = $this->bulan(date('m'));
        // echo dd($data['RPendapatanBlnPajak']);

        //PAD Retribusi
        $data['kdRetribusi'] = '4.1.02.'; //Kode Rekening Retribusi
        $data['retribusi'] = 'Retribusi Daerah';
        $padretribusi = $this->pendapatan->perOPDObjek($dataopd['kd_skpd'], $data['kdRetribusi']);
        $data['opdRetribusi'] = $padretribusi['pagu'];
        // $data['RPendapatanBlnRetribusi'] = $this->rpendapatan->cekperKdAkun($tahunaktif, $bulanaktif, $dataopd['kd_skpd'], $data['kdRetribusi']);
        if ($bulan) {
            $data['RPendapatanBlnRetribusiAktif'] = $this->rpendapatan->Rbulanakhirperakun($tahunaktif, $bulan, $dataopd['kd_skpd'], $data['kdRetribusi']);
        } else {
            $data['RPendapatanBlnRetribusiAktif'] = $this->rpendapatan->Rbulanakhirperakun($tahunaktif, $bulanaktif, $dataopd['kd_skpd'], $data['kdRetribusi']);
        }

        //PAD kekayaan
        $data['kdKekayaan'] = '4.1.03.'; //Kode Rekening Retribusi
        $data['kekayaan'] = 'Hasil Pengelolaan Kekayaan Daerah yang Dipisahkan';
        $padkekayaan = $this->pendapatan->perOPDObjek($dataopd['kd_skpd'], $data['kdKekayaan']);
        $data['opdKekayaan'] = $padkekayaan['pagu'];
        if ($bulan) {
            $data['RPendapatanBlnKekayaanAktif'] = $this->rpendapatan->cekperKdAkun($tahunaktif, $bulan, $dataopd['kd_skpd'], $data['kdKekayaan']);
        } else {
            $data['RPendapatanBlnKekayaanAktif'] = $this->rpendapatan->cekperKdAkun($tahunaktif, $bulanaktif, $dataopd['kd_skpd'], $data['kdKekayaan']);
        }

        //PAD lain-lain
        $data['kdLainlain'] = '4.1.04.'; //Kode Rekening Retribusi
        $data['lainlain'] = 'Lain-lain PAD yang Sah';
        $padlainlain = $this->pendapatan->perOPDObjek($dataopd['kd_skpd'], $data['kdLainlain']);
        $data['opdLainlain'] = $padlainlain['pagu'];
        if ($bulan) {
            $data['RPendapatanBlnLainlainAktif'] = $this->rpendapatan->cekperKdAkun($tahunaktif, $bulan, $dataopd['kd_skpd'], $data['kdLainlain']);
        } else {
            $data['RPendapatanBlnLainlainAktif'] = $this->rpendapatan->cekperKdAkun($tahunaktif, $bulanaktif, $dataopd['kd_skpd'], $data['kdLainlain']);
        }


        //PAD TRansfer Pusat
        $data['kdTfPusat'] = '4.2.01.'; //Kode Rekening Retribusi
        $data['tfpusat'] = 'Pendapatan Transfer Pemerintah Pusat';
        $padtfpusat = $this->pendapatan->perOPDObjek($dataopd['kd_skpd'], $data['kdTfPusat']);
        $data['opdtfpusat'] = $padtfpusat['pagu'];
        if ($bulan) {
            $data['RPendapatanBlntfpusatAktif'] = $this->rpendapatan->cekperKdAkun($tahunaktif, $bulan, $dataopd['kd_skpd'], $data['kdTfPusat']);
        } else {
            $data['RPendapatanBlntfpusatAktif'] = $this->rpendapatan->cekperKdAkun($tahunaktif, $bulanaktif, $dataopd['kd_skpd'], $data['kdTfPusat']);
        }


        //PAD TRansfer Daerah
        $data['kdTfDaerah'] = '4.2.02.'; //Kode Rekening Retribusi
        $data['tfdaerah'] = 'Pendapatan Transfer Antar Daerah';
        $padtfdaerah = $this->pendapatan->perOPDObjek($dataopd['kd_skpd'], $data['kdTfDaerah']);
        $data['opdtfdaerah'] = $padtfdaerah['pagu'];
        if ($bulan) {
            $data['RPendapatanBlntfdaerahAktif'] = $this->rpendapatan->cekperKdAkun($tahunaktif, $bulan, $dataopd['kd_skpd'], $data['kdTfDaerah']);
        } else {
            $data['RPendapatanBlntfdaerahAktif'] = $this->rpendapatan->cekperKdAkun($tahunaktif, $bulanaktif, $dataopd['kd_skpd'], $data['kdTfDaerah']);
        }

        //PAD Hibah
        $data['kdHibah'] = '4.3.01.'; //Kode Rekening Retribusi
        $data['hibah'] = 'Pendapatan Hibah';
        $padhibah = $this->pendapatan->perOPDObjek($dataopd['kd_skpd'], $data['kdHibah']);
        $data['opdhibah'] = $padhibah['pagu'];
        if ($bulan) {
            $data['RPendapatanBlnhibahAktif'] = $this->rpendapatan->cekperKdAkun($tahunaktif, $bulan, $dataopd['kd_skpd'], $data['kdHibah']);
        } else {
            $data['RPendapatanBlnhibahAktif'] = $this->rpendapatan->cekperKdAkun($tahunaktif, $bulanaktif, $dataopd['kd_skpd'], $data['kdHibah']);
        }

        if ($bulan) {
            $data['RPendapatanBlnAktif'] = $this->rpendapatan->RtotalperBln($tahunaktif, $bulan, $dataopd['kd_skpd']);
        } else {
            $data['RPendapatanBlnAktif'] = $this->rpendapatan->RtotalperBln($tahunaktif, $bulanaktif, $dataopd['kd_skpd']);
        }

        if ($data['RPendapatanBlnAktif'] == null) {
            $data['totrealisasi'] = '';
        } else {
            $data['totrealisasi'] = $data['RPendapatanBlnAktif']['realisasi'];
        }
        //  echo dd($data['RPendapatanBlnAktif']);
        $data['kd_skpd'] = $dataopd['kd_skpd'];

        if ($kdRek == $data['kdPajak']) {
            $data['KodeRek'] = '4.1.01.';
            $data['nmRek'] = 'Pajak Daerah';
            $pagupad = $this->pendapatan->perOPDObjek($dataopd['kd_skpd'], $data['KodeRek']);
            $data['pagupad'] = $pagupad['pagu'];
        }
        if ($kdRek == $data['kdRetribusi']) {
            $data['KodeRek'] = '4.1.02.';
            $data['nmRek'] = 'Retribusi Daerah';
            $pagupad = $this->pendapatan->perOPDObjek($dataopd['kd_skpd'], $data['KodeRek']);
            $data['pagupad'] = $pagupad['pagu'];
        }
        if ($kdRek == $data['kdKekayaan']) {
            $data['KodeRek'] = '4.1.03.';
            $data['nmRek'] = 'Hasil Pengelolaan Kekayaan Daerah yang Dipisahkan';
            $pagupad = $this->pendapatan->perOPDObjek($dataopd['kd_skpd'], $data['KodeRek']);
            $data['pagupad'] = $pagupad['pagu'];
        }
        if ($kdRek == $data['kdLainlain']) {
            $data['KodeRek'] = '4.1.04.';
            $data['nmRek'] = 'Lain-lain PAD yang Sah';
            $pagupad = $this->pendapatan->perOPDObjek($dataopd['kd_skpd'], $data['KodeRek']);
            $data['pagupad'] = $pagupad['pagu'];
        }
        if ($kdRek == $data['kdTfPusat']) {
            $data['KodeRek'] = '4.2.01.';
            $data['nmRek'] = 'Pendapatan Transfer Pemerintah Pusat';
            $pagupad = $this->pendapatan->perOPDObjek($dataopd['kd_skpd'], $data['KodeRek']);
            $data['pagupad'] = $pagupad['pagu'];
        }
        if ($kdRek == $data['kdTfDaerah']) {
            $data['KodeRek'] = '4.2.02.';
            $data['nmRek'] = 'Pendapatan Transfer Antar Daerah';
            $pagupad = $this->pendapatan->perOPDObjek($dataopd['kd_skpd'], $data['KodeRek']);
            $data['pagupad'] = $pagupad['pagu'];
        }
        if ($kdRek == $data['kdHibah']) {
            $data['KodeRek'] = '4.3.01.';
            $data['nmRek'] = 'Pendapatan Hibah';
            $pagupad = $this->pendapatan->perOPDObjek($dataopd['kd_skpd'], $data['KodeRek']);
            $data['pagupad'] = $pagupad['pagu'];
        }
        $jadwalall = $this->jadwalmodel->findAll();
        $data['datajadwal'] =  $jadwalall;
        if ($page == 'index') {
            $datapendapatan  = $this->pendapatan->perOPD($dataopd['kd_skpd']);
            $data['bulanpilih'] = '';
            $data['datapendapatanopd'] = $datapendapatan['pagu'];
            // echo dd($dataopd['kd_skpd']);
            return view('lrfk/opd/v_pendapatan', $data);
        } else {
            $this->ValidasiHash($req);
        }

        if ($page == 'datapendapatan') {
            if ($action == 'inputpajak') {
                $data['idR'] = '';
                $data['dRealisasi'] = '';
                return view('lrfk/opd/form_inputpendapatan', $data);
            }
            if ($action == 'cek') {
                $data['bulanpilih'] = $bulan;
                $datapendapatan  = $this->pendapatan->perOPD($dataopd['kd_skpd']);
                $data['datapendapatanopd'] = $datapendapatan['pagu'];
                return view('lrfk/opd/v_pendapatan', $data);
            }
            if ($action == 'ubahpajak') {
                $data['idR'] = $id;
                $data['dRealisasi'] = $this->rpendapatan->dataPerId($id);
                // echo dd($data['dRealisasi']);
                return view('lrfk/opd/form_inputpendapatan', $data);
            }
            if ($action == 'hapuspajak') {
                //  $data['idR'] = $id;
                //   $data['dRealisasi'] = $this->rpendapatan->dataPerId($id);
                $this->rpendapatan->delete($id);
                return redirect()->to('lrfkopd')->withInput()->with('message', 'Hapus data berhasil');
                //   return view('lrfk/opd/form_inputpendapatan', $data);
            }
            if ($action == 'cetak') {
                $data['bulanpilih'] = $bulan;
                $data['dRealisasi'] = $this->rpendapatan->dataPerOPD($kdU, $tahunaktif, $bulan);
                $data['datapendapatan'] = $this->pendapatan->datapendapatanperOPD($kdU);
                //   echo dd($dataopd['nm_sub_unit']);
                $data['nm_opd'] = $dataopd['nm_sub_unit'];
                $html = view('/lrfk/opd/CetakPendapatanOpd', $data);
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
                $pdf->Output('Realisasi_Pendapatan_' . $tahunaktif . '_' . $dataopd['nm_sub_unit'], 'I');
            }
        }
    }
    public function simpanpendapatan()
    {
        // Validasi Input
        $validation = \Config\Services::validation();
        $validation->setRules(
            [
                'realisasi' => 'required|numeric|min_length[1]|max_length[20]',
            ],
            ['realisasi' => [
                'required' => 'realisasi Wajib di Isi',
                'numeric' => 'realisasi Wajib Angka',
                'min_length' => 'realisasi minimal 1 angka.',
                'max_length' => 'Vrealisasi maksimal 20 angka.',
            ]],

        );
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        $id = $this->request->getPost('id');

        if (!$id) {
            $data = [
                'tahun' => $this->request->getPost('tahun'),
                'bulan' => $this->request->getPost('bulan'),
                'kd_skpd' => $this->request->getPost('kd_skpd'),
                'sub_unit' => $this->request->getPost('sub_unit'),
                'kd_akun' => $this->request->getPost('kd_akun'),
                'nm_rekening' => $this->request->getPost('nm_rekening'),
                'pagu' => $this->request->getPost('pagu'),
                'realisasi' => $this->request->getPost('realisasi'),
            ];
        } else {
            $data = [
                'id' => $this->request->getPost('id'),
                'tahun' => $this->request->getPost('tahun'),
                'bulan' => $this->request->getPost('bulan'),
                'kd_skpd' => $this->request->getPost('kd_skpd'),
                'sub_unit' => $this->request->getPost('sub_unit'),
                'kd_akun' => $this->request->getPost('kd_akun'),
                'nm_rekening' => $this->request->getPost('nm_rekening'),
                'pagu' => $this->request->getPost('pagu'),
                'realisasi' => $this->request->getPost('realisasi'),
            ];
        }
        // echo dd($data);
        $this->rpendapatan->save($data);
        //  return redirect()->back()->withInput()->with('message', 'penyimpanan data berhasil');
        return redirect()->to(base_url('lrfkopd/pendapatan?page=index'))->withInput()->with('message', 'penyimpanan data berhasil');

        //echo dd($data);
    }
}
