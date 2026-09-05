<?php

namespace App\Controllers\LrfkController;

use App\Models\LrfkProvModel\JadwalModel;
use App\Models\UserModel\TaUserModel;
// use App\Models\LrfkProvModel\SubKegModel; // as lrfkModel;

use \Myth\Auth\Authorization\GroupModel;

use App\Models\UserModel;
use \Myth\Auth\Password;

// use App\Models\LrfkProvModel\RealisasiSubKegModel;
// use App\Models\LrfkProvModel\TotalRealisasiModel;
// use App\Models\LrfkProvModel\TaIndikatorProgModal;
// use App\Models\LrfkProvModel\TaIndikatorSubKegModal;
// use App\Models\LrfkProvModel\TaRealisasiRinciModel;

use App\Models\DataApbdModel\RealApbdModel;
use App\Models\RupModel\SirupModel;
use App\Models\RupModel\RealRupModel;
use App\Models\DataApbdModel\PendApbdModel;
use App\Models\DataApbdModel\RealPendApbdModel;
use App\Models\DataApbdModel\AngkasModel;
use App\Libraries\PdfLibrary;
use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;
use FontLib\Table\Type\post;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;
use TCPDF;


class OpdProvController26 extends BaseController
{

    protected $jadwalmodel;
    protected $tcpdfConfig;
    protected $tausermodel;

    // protected $subkegmodel;
    // protected $realisasilrfk;
    // protected $realisasilrfkrinci;

    // protected $totalrealisasiM;
    // protected $taindikator;
    // protected $taindisubkeg;
    // protected $db;
    protected $realapbdmodel;
    protected $sirupmodel;
    protected $realrupmodel;
    protected $pendapatanmodel;
    protected $realpendapatanmodel;
    protected $angkasmodel;

    public function __construct()
    {
        $this->tausermodel = new TaUserModel();
        $this->jadwalmodel = new JadwalModel();
        $this->realapbdmodel = new RealApbdModel();
        $this->sirupmodel = new SirupModel();
        $this->realrupmodel = new RealRupModel();
        $this->pendapatanmodel = new PendApbdModel();
        $this->realpendapatanmodel = new RealPendApbdModel();
        $this->angkasmodel = new AngkasModel();
        // $this->subkegmodel = new SubKegModel();
        // $this->totalrealisasiM = new TotalRealisasiModel();
        // $this->taindikator = new TaIndikatorProgModal();
        // $this->taindisubkeg = new TaIndikatorSubKegModal();
        // $this->realisasilrfkrinci = new TaRealisasiRinciModel();
        $this->tcpdfConfig = new \Config\Tcpdf();
        helper(['form', 'url', 'filesystem']);
        // $this->db = db_connect();
    }

    public function index()
    {
        $req = $this->request;
        $this->ValidasiHash($req);
        // Contoh penggunaan parameter
        $receivedParams = $req->getGet();
        $hal = $receivedParams['hal'] ?? null;
        $action = $receivedParams['action'] ?? null;
        $tahun = $receivedParams['tahun'] ?? null;
        $tgldata = $receivedParams['tgldata'] ?? null;
        $kdSK = $receivedParams['kdSK'] ?? null;
        $kdSU = $receivedParams['kdSU'] ?? null;
        $kdU = $receivedParams['kdU'] ?? null;
        $kdR = $receivedParams['kdR'] ?? null;
        $rSK = $receivedParams['rSK'] ?? null;


        // $bulan = $receivedParams['bulan'] ?? null;

        $user = user();
        $tahunaktif =  session()->get('tahun'); //   $jadwalaktif['tahun'];
        $tglaktif = session()->get('tglaktif');
        $groupname = session()->get('groupuser');
        // echo dd($tahunaktif . ' - ' . $tglaktif . ' - ' . $groupname);
        $datauser = $this->tausermodel->listuser($user->id);
        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();

        if (!$tahunaktif) {
            return redirect()->to(base_url('lrfkopd'))->withInput()->with('message', 'Tahun aktif belum dipilih, silahkan pilih tahun aktif terlebih dahulu');
        }
        if ($tahunaktif == Date('Y')) {
            $tglaktif = session()->get('tglaktif');
            $bulan = $jadwalaktif['bulan']; //'Maret'; //$data['bln'];
            $bulanaktif = $jadwalaktif['bulan'];
            $data['dataopd'] = $this->realapbdmodel->dataopd($datauser['sub_unit']);
        } else {
            $tglaktif = '2026-06-07'; //session()->get('tglaktif');
            $bulan = 'Desember';
            $bulanaktif = 'Desember';
            $data['dataopd'] = $this->realapbdmodel->dataopd2025($datauser['sub_unit']);
        }
        // $bulanaktif = $jadwalaktif['bulan'];

        $data = [
            'groupuser' => $groupname,
            'groupmenu' => 'userlrfkprov',
            'titlepage' => 'Selamat Data di e-TAPIS Laporan Realisasi Fisik Keuangan(LRFK) Program Kegiatan Perangkat Daerah Provinsi Lampung',
            'datauser' => $this->tausermodel->listuser($user->id),
            'jadwalaktif' => $jadwalaktif,
            'tahunaktif' => $tahunaktif,
            'tglaktif' => $tglaktif,
            'bulanaktif' => $bulan,
        ];

        //data sub kegiatan per OPD
        $data['dataapbdopd'] = $this->realapbdmodel->dataopd($datauser['sub_unit']);
        $data['subgiat'] = $this->realapbdmodel->rpersk($datauser['sub_unit'], $tahunaktif, $bulan, $tglaktif);
        $data['program'] = $this->realapbdmodel->listprogram($datauser['sub_unit'], $tahunaktif, $bulan, $tglaktif);
        $data['cekdatapendapatan'] = $this->pendapatanmodel->getDataPendapatan($tahunaktif, $datauser['sub_unit']);
        $data['listbulan'] = $this->jadwalmodel->getlist();
        $datauser = $this->tausermodel->listuser($user->id);
        // echo dd($tahunaktif, $bulanaktif, $datauser['sub_unit'], $tglaktif);
        // echo dd($data['program']);
        if ($hal == 'profile') {
            return view('lrfk/opd/profile', $data);
        }

        if ($hal == 'listsubkeg') {
            if ($action == 'all') {
                // echo dd($cekdatapendapatan);
                if ($data['cekdatapendapatan']) {
                    $cekrealisasipendapatan = $this->realpendapatanmodel->getDataRPendapatan($tahunaktif, $bulanaktif, $datauser['sub_unit']);
                    if ($cekrealisasipendapatan['NAMA_OPD'] == null) { //|| $cekrealisasipendapatan['REALISASI_PEND'] == 0) {
                        // echo dd($cekrealisasipendapatan);
                        $data['datapendapatan'] = $this->realpendapatanmodel->getDataRPendapatanAll($tahunaktif, $datauser['sub_unit']);

                        return view('lrfk/opd/ver26/form_realpendapatan', $data);
                    } else {
                        // echo dd($cekrealisasipendapatan);
                        // echo dd('Data Realisasi Pendapatan sudah diinput untuk bulan ini, silahkan ubah data realisasi pendapatan jika ingin mengubah data realisasi pendapatan');
                        return view('lrfk/opd/ver26/listsubkeg', $data);
                    }
                } else {
                    // $cekdataangkasopd = $this->angkasmodel->getDataAngkasOpd($tahunaktif, $datauser['sub_unit']);
                    // if ($cekdataangkasopd['total_angkas'] ?? null != $data['dataapbdopd']['anggaran']) {
                    //     echo dd('Data Angkas belum diinput atau tidak sesuai dengan data APBD, silahkan input data Angkas terlebih dahulu');
                    // } else {
                    //     return view('lrfk/opd/ver26/listsubkeg', $data);
                    // }

                    // echo dd($data['program']);
                    return view('lrfk/opd/ver26/listsubkeg', $data);
                }
            }
        }
        if ($hal == 'dataapbd') {
            if ($action == 'ubahreal') {
                //data sub rincian objek belanja per sub kegiatan per OPD
                $data['srobelanja'] = $this->realapbdmodel->rperrso($tahun, $tgldata, $kdSU, $kdSK)->getResultArray();
                $data['datasrobelanja'] = $this->realapbdmodel->rperrso($tahun, $tgldata, $kdSU, $kdSK)->getRowArray();
                // echo dd($rSK);
                // if ($rSK == '0.00') {
                //     return redirect()->back()->withInput()->with(
                //         'message',
                //         'Realisasi Anggaran (SIPD) Sub Rincian Objek Belanja belum tersedia untuk sub kegiatan ini,
                //         mohon diinputkan terlebih dahulu di aplikasi SIPD, agar data realisasi anggaran dapat ditampilkan di e-TAPIS LRFK'
                //     );
                // } else {
                //     return view('lrfk/opd/ver26/form_realisasi_rinci', $data);
                // }
                return view('lrfk/opd/ver26/form_realisasi_rinci', $data);
            }
        }
        if ($hal == 'pbj') {
            // $bulanaktif = 'Maret';

            if ($action == 'rup') {
                $subunit = $datauser['sub_unit']; //'DINAS PENDIDIKAN DAN KEBUDAYAAN'; //
                // echo dd($tahunaktif, $bulanaktif, $subunit);
                //data sub rincian objek belanja per sub kegiatan per OPD
                $data['datarupopd'] = $this->sirupmodel->rupopd($tahunaktif, $bulanaktif, $subunit)->getResultArray();
                $data['datarupopdpenyedia'] = $this->sirupmodel->rupopdcara($tahunaktif, $bulanaktif, $subunit, 'Penyedia')->getResultArray();
                $data['datarupopdswakelola'] = $this->sirupmodel->rupopdcara($tahunaktif, $bulanaktif, $subunit, 'Swakelola')->getResultArray();
                $data['datarupopdkatalog'] = $this->sirupmodel->rupopdmetoda($tahunaktif, $bulanaktif, $subunit, 'E-Purchasing')->getResultArray();
                $data['datarupopdpl'] = $this->sirupmodel->rupopdmetoda($tahunaktif, $bulanaktif, $subunit, 'Pengadaan Langsung')->getResultArray();
                $data['datarupopdkecuali'] = $this->sirupmodel->rupopdmetoda($tahunaktif, $bulanaktif, $subunit, 'Dikecualikan')->getResultArray();
                $data['datarupopdtunjuk'] = $this->sirupmodel->rupopdmetoda($tahunaktif, $bulanaktif, $subunit, 'Penunjukan Langsung')->getResultArray();
                $data['datarupopdtender'] = $this->sirupmodel->rupopdmetoda($tahunaktif, $bulanaktif, $subunit, 'Tender')->getResultArray();
                $data['datarupopdseleksi'] = $this->sirupmodel->rupopdmetoda($tahunaktif, $bulanaktif, $subunit, 'Seleksi')->getResultArray();

                $data['datajmlrupopd'] = $this->sirupmodel->jmldatarup($tahunaktif, $bulanaktif, $subunit)->groupBy('Nama_Satuan_Kerja')
                    ->get()->getRowArray();
                $data['datajmlrupopdP'] = $this->sirupmodel->jmldatarup($tahunaktif, $bulanaktif, $subunit)
                    ->where('Cara_Pengadaan', 'Penyedia')
                    ->groupBy('Nama_Satuan_Kerja')
                    ->get()->getRowArray();
                $data['datajmlrupopdS'] = $this->sirupmodel->jmldatarup($tahunaktif, $bulanaktif, $subunit)
                    ->where('Cara_Pengadaan', 'Swakelola')
                    ->groupBy('Nama_Satuan_Kerja')
                    ->get()->getRowArray();
                $data['datajmlrupopdE'] = $this->sirupmodel->jmldatarup($tahunaktif, $bulanaktif, $subunit)
                    ->where('Metode_Pengadaan', 'E-Purchasing')
                    ->groupBy('Nama_Satuan_Kerja')
                    ->get()->getRowArray();
                $data['datajmlrupopdPL'] = $this->sirupmodel->jmldatarup($tahunaktif, $bulanaktif,   $subunit)
                    ->where('Metode_Pengadaan', 'Pengadaan Langsung')
                    ->groupBy('Nama_Satuan_Kerja')
                    ->get()->getRowArray();
                $data['datajmlrupopdTunjuk'] = $this->sirupmodel->jmldatarup($tahunaktif, $bulanaktif, $subunit)
                    ->where('Metode_Pengadaan', 'Penunjukan Langsung')
                    ->groupBy('Nama_Satuan_Kerja')
                    ->get()->getRowArray();
                $data['datajmlrupopdKecuali'] = $this->sirupmodel->jmldatarup($tahunaktif, $bulanaktif, $subunit)
                    ->where('Metode_Pengadaan', 'Dikecualikan')
                    ->groupBy('Nama_Satuan_Kerja')
                    ->get()->getRowArray();
                $data['datajmlrupopdTender'] = $this->sirupmodel->jmldatarup($tahunaktif, $bulanaktif, $subunit)
                    ->where('Metode_Pengadaan', 'Tender')
                    ->groupBy('Nama_Satuan_Kerja')
                    ->get()->getRowArray();
                $data['datajmlrupopdSeleksi'] = $this->sirupmodel->jmldatarup($tahunaktif, $bulanaktif, $subunit)
                    ->where('Metode_Pengadaan', 'Seleksi')
                    ->groupBy('Nama_Satuan_Kerja')
                    ->get()->getRowArray();
                $data['datajmlrupopdPDN'] = $this->sirupmodel->jmldatarup($tahunaktif, $bulanaktif, $subunit)
                    ->where('Produk_Dalam_Negeri', 'Ya')
                    ->groupBy('Nama_Satuan_Kerja')
                    ->get()->getRowArray();

                $data['datajmlrupopdNonPDN'] = $this->sirupmodel->jmldatarup($tahunaktif, $bulanaktif, $subunit)
                    ->where('Produk_Dalam_Negeri', 'Tidak')
                    ->groupBy('Nama_Satuan_Kerja')
                    ->get()->getRowArray();
                // echo dd($data['datajmlrupopd']['total_anggaran'], $data['datajmlrupopd']['jumlah_paket']);
                // echo dd($data['datarupopd'], $data['datajmlrupopd']);
                // echo dd($bulanaktif);
                return view('lrfk/opd/ver26/listrup', $data);
            }
            if ($action == 'realisasi') {
                $data['subunit'] = $datauser['sub_unit']; //'DINAS PENDIDIKAN DAN KEBUDAYAAN'; //
                $data['realisasirup'] = $this->realrupmodel->realrupopd($tahunaktif, $bulanaktif, $data['subunit'])->getResultArray();
                $data['dataruppermetode'] = $this->sirupmodel->rupopdmetode($tahunaktif, $bulanaktif, $data['subunit'])->getResultArray();
                $data['datarealpermetode'] = $this->realrupmodel->realrupopdmetode($tahunaktif, $bulanaktif, $data['subunit'])->getResultArray();
                // echo dd($data['realisasirup']);
                return view('lrfk/opd/ver26/realisasirup', $data);
            }
            if ($action == 'laporanpbj') {
                $subunit = $datauser['sub_unit']; //'DINAS PENDIDIKAN DAN KEBUDAYAAN'; //
                $data['realisasirup'] = $this->realrupmodel->realrupopd($tahunaktif, $bulanaktif, $subunit)->getResultArray();
                // echo dd($subunit, $data['realisasirup']);
                return view('lrfk/opd/ver26/laporanpbj', $data);
            }
        }
        if ($hal == 'datapendapatan') {
            if ($action == 'listall') {
                // echo dd($data['cekdatapendapatan']);
                if ($data['cekdatapendapatan']) {
                    $cekrealisasipendapatan = $this->realpendapatanmodel->getDataRPendapatan($tahunaktif, $bulanaktif, $datauser['sub_unit']);
                    // echo dd($cekrealisasipendapatan);
                    if ($cekrealisasipendapatan['NAMA_OPD'] == null) {
                        // echo dd($data['cekdatapendapatan']);
                        $data['datapendapatan'] = $this->realpendapatanmodel->getDataRPendapatanAll($tahunaktif, $datauser['sub_unit']);

                        return view('lrfk/opd/ver26/form_realpendapatan', $data);
                    } else {
                        $data['datapendapatan'] = $this->realpendapatanmodel->getDataRPendapatan($tahunaktif, $bulanaktif, $datauser['sub_unit']);
                        return view('lrfk/opd/ver26/datapendapatan', $data);
                    }
                } else {
                    return redirect()->to(base_url('lrfkopd'))
                        ->withInput()->with('message', 'Perangkat Daerah :' . ' ' . $datauser['sub_unit'] . ' '
                            . 'tidak mengelola  Pendapatan APBD');
                    // $data['datapendapatan'] = $this->realpendapatanmodel->getDataRPendapatan($tahunaktif, $bulanaktif, $datauser['sub_unit']);

                    // // echo dd($data['datapendapatan']);
                    // return view('lrfk/opd/ver26/datapendapatan', $data);
                }
            }
        }
        $data['listbulan'] = $this->jadwalmodel->getlist();
        $data['dataangkasapbd'] = $this->angkasmodel->getDataAngkas($tahunaktif, $bulanaktif, $datauser['sub_unit']);
        $data['dataangkasapbdpergiat'] = $this->angkasmodel->getDataAngkasPerGiat($tahunaktif, $kdSU, $kdSK);
        $data['subkegperopd'] = $this->realapbdmodel->rperskperopd($kdSU, $kdSK, $tahunaktif, $bulanaktif, $tglaktif);

        if ($hal == 'dataangkasapbd') {
            if ($action == 'listall') {
                // echo dd($data['listbulan']);
                return view('lrfk/opd/ver26/dataangkasapbd', $data);
            }
            if ($action == 'ubahangkas') {
                return view('lrfk/opd/ver26/form_ubahangkasapbd', $data);
            }
            if ($action == 'inputangkas') {
                // echo dd($data['subkegperopd']);
                return view('lrfk/opd/ver26/form_angkasapbd', $data);
            }
        }
        if ($hal == 'cetakopd') {
            if ($action == 'realisasirup') {
                $subunit = $datauser['sub_unit']; //'DINAS PENDIDIKAN DAN KEBUDAYAAN'; //
                $jadwalall = $this->jadwalmodel->findAll();
                $data['datajadwal'] =  $jadwalall;
                if (!$bulan) {
                    $data['bulanpilih'] = '';
                } else {
                    $data['bulanpilih'] = $bulan;
                }
                $datauser = $this->tausermodel->listuser($user->id);
                $data['realisasirup'] = $this->realrupmodel->realrupopd($tahunaktif, $bulanaktif, $subunit)->getResultArray();

                // $data['listapbdopd'] = $this->subkegmodel->paguperurusan($datauser['sub_unit'], $dataopd['kd_sub_unit'], $tahunaktif);
                $data['datauser'] = $datauser;
                // echo dd($data['datauser']);
                $html = view('/lrfk/opd/ver26/cetak_realisasi_rup', $data);
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
                        'Sistem Data Pengendalian dan Informasi (SiTAPIS) TA .' . $tahunaktif,
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
                $pdf->Output('Rekap_RealisasiPBJ_' . $tahunaktif . '_' . $datauser['sub_unit'], 'I');
                // return view('lrfk/opd/ver26/cetak_realisasi_rup', $data);
            }
            if ($action == 'lrfk') {
                $jadwalall = $this->jadwalmodel->findAll();
                $data['datajadwal'] =  $jadwalall;
                if (!$bulan) {
                    $data['bulanpilih'] = '';
                } else {
                    $data['bulanpilih'] = $bulan;
                }
                $datauser = $this->tausermodel->listuser($user->id);
                $data['subgiat'] = $this->realapbdmodel->rpersk($datauser['sub_unit'], $tahunaktif, $bulan, $tglaktif);

                // $data['listapbdopd'] = $this->subkegmodel->paguperurusan($datauser['sub_unit'], $dataopd['kd_sub_unit'], $tahunaktif);
                $data['datauser'] = $datauser;
                // echo dd($data['datauser']);
                $html = view('/lrfk/opd/ver26/CetakApbdOpd2', $data);
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
                        'Sistem Data Pengendalian dan Informasi (SiTAPIS) TA .' . $tahunaktif,
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
                $pdf->Output('Rekap_LRFK_' . $tahunaktif . '_' . $datauser['sub_unit'], 'I');
            }
        }
    }
    public function simpanangkasapbd()
    {
        // $dataangkasapbd = [
        //     'KODE_OPD' => $this->request->getPost('KODE_OPD'),
        //     'NAMA_OPD' => $this->request->getPost('NAMA_OPD'),
        //     'PAGU' => $this->request->getPost('PAGU'),
        //     'ANGKA_KREDIT' => $this->request->getPost('ANGKA_KREDIT'),
        //     'TAHUN' => $this->request->getPost('TAHUN'),
        //     'BULAN' => $this->request->getPost('BULAN'),
        // ];
        // $realisasi = $this->request->getPost('REALISASI_PEND') ?? null;
        $dataangkas = $this->request->getPost('dataangkas');
        $totalangkas = array_sum(array_column($dataangkas, 'ANGKAS'));
        // echo dd($dataangkas, $totalangkas);
        if ($totalangkas == 0) {
            return redirect()->back()->withInput()->with(
                'message',
                'Total Anggaran Kas Tidak boleh 0'
            );
        }
        if ($totalangkas > $dataangkas[0]['TOTAL_ANGGARAN']) {
            return redirect()->back()->withInput()->with(
                'message',
                'Total Anggaran Kas Tidak boleh melebihi pagu belanja subkegiatan. '
            );
        }
        if ($totalangkas < $dataangkas[0]['TOTAL_ANGGARAN']) {
            return redirect()->back()->withInput()->with(
                'message',
                ' Anggaran Kas yang diinput kurang dari total pagu belanja subkegiatan.'
            );
        }
        if ($dataangkas) {
            // echo dd($dataangkas);
            if ($dataangkas[0]['Id_angkas'] == null) {
                $simpan = $this->angkasmodel->insertBatchData($dataangkas);
            } else {
                $simpan = $this->angkasmodel->updateBatch($dataangkas, 'Id_angkas');
            }
        } else {
            return redirect()->back()->withInput()->with(
                'message',
                'Total Anggaran Kas Tidak Tersimpan'
            );
        }
        if ($simpan) {
            return redirect()->back()->withInput()->with(
                'message',
                'penyimpanan data berhasil dengan angka kredit sebesar  Rp. ' . number_format($totalangkas, 0, ',', '.') . ' berhasil disimpan   '
            );
        }
    }
    public function simpanpendapatanapbd()
    {
        $id = $this->request->getPost('id');
        $datarealpendapatan = [
            'KODE_OPD' => $this->request->getPost('KODE_OPD'),
            'NAMA_OPD' => $this->request->getPost('NAMA_OPD'),
            'PAGU' => $this->request->getPost('PAGU'),
            'REALISASI_PEND' => $this->request->getPost('REALISASI_PEND'),
            'TAHUN' => $this->request->getPost('TAHUN'),
            'BULAN' => $this->request->getPost('BULAN'),
        ];
        // $realisasi = $this->request->getPost('REALISASI_PEND') ?? null;
        $datapendapatan = $this->request->getPost('datarealp');
        // echo dd($datarealpendapatan);
        if ($id == null) {
            $simpan = $this->realpendapatanmodel->insertBatchData($datapendapatan);
        } else {
            $simpan = $this->realpendapatanmodel->update($id, $datarealpendapatan);
            // echo dd($datapendapatan);
        }
        if ($simpan) {
            return redirect()->back()->withInput()->with(
                'message',
                'penyimpanan data berhasil dengan realisasi pendapatan  sebesar  Rp. ' . number_format($this->request->getPost('REALISASI_PEND'), 0, ',', '.') . ' berhasil disimpan   '
            );
        }
    }

    public function saveperubahan()
    {

        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        // $tahunaktif = $jadwalaktif['tahun'];
        // $bulanaktif = $jadwalaktif['bulan'];

        $datareal = $this->request->getPost('datareal');

        // echo dd($datareal);
        $jumlah = count($datareal); // Mengetahui jumlah elemen
        // echo dd($datareal, $jumlah);
        for ($i = 0; $i < $jumlah; $i++) {
            if ($datareal[$i]['REALISASI_SPJ'] > $datareal[$i]['TOTAL_ANGGARAN']) {
                return redirect()->back()->withInput()->with('message', 'Realisasi  ' . $datareal[$i]['NAMA_SRO'] . '  melebihi pagu belanja');
            }
        }
        // for ($i = 0; $i < $jumlah; $i++) {
        //     if ($datareal[$i]['REALISASI_SPJ'] == 0) {
        //         $datareal[$i]['REALISASI_SPJ'] = $datareal[$i]['TOTAL_REALISASI'];
        //         // return redirect()->back()->withInput()->with('message', 'Realisasi  ' . $datareal[$i]['NAMA_SRO'] . '  melebihi pagu belanja');
        //     }
        // }
        // echo dd($datareal);
        if ($datareal[0]['NO']) {
            // echo dd($datareal);
            $this->realapbdmodel->updateBatch($datareal, 'NO');
            // return redirect()->back()->withInput()->with('message', 'penyimpanan data berhasil');
            return redirect()->to(hash_url('lrfkopd/apbdopd', ['hal' => 'listsubkeg', 'action' => 'all']))->withInput()->with('message', 'penyimpanan data berhasil');
        }
        // else {
        //     // echo dd($datareal);
        //     $this->realisasilrfkrinci->insertBatch($datareal);
        //     return redirect()->to(base_url('lrfkopd/subkegiatan'))->withInput()->with('message', 'penyimpanan data berhasil');
        // }
    }

    public function updateprofile()
    {
        $tahunaktif = session()->get('tahun'); //   $jadwalaktif['tahun'];

        $data['titlepage'] = 'Selamat Data di e-TAPIS Laporan Realisasi Fisik Anggaran Program Kegiatan Perangkat Daerah Provinsi Lampung';
        $data['groupuser'] = 'forbiddenopd';
        $data['listopd'] = $this->realapbdmodel->listopd();

        // Validasi Input
        $validation = \Config\Services::validation();
        // Validation
        $validation->setRules(
            [
                'nip' => 'required|numeric|min_length[18]|max_length[18]',
                'nama' => 'required|min_length[4]|max_length[100]',
                'jabatan' => 'required|min_length[5]|max_length[255]',
            ],
            ['nip' => [
                'required' => 'NIP  wajib diisi.',
                'numeric' => 'NIP Wajib Angka',
                'min_length' => 'NIP minimal 4 karakter.',
                'max_length' => 'NIP maksimal 100 karakter.',
            ]],
            ['nama' => [
                'required' => 'Nama  wajib diisi.',
                'min_length' => 'Nama minimal 4 karakter.',
                'max_length' => 'Nama maksimal 100 karakter.',
            ]],
            ['jabatan' => [
                'required' => 'Jabatan  wajib diisi.',
                'min_length' => 'Jabatan minimal 5 karakter.',
                'max_length' => 'Jabatan maksimal 100 karakter.',
            ]],
        );
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        $user = user();
        $nmopd =  $this->request->getPost('sub_unit');
        $listopd = $this->realapbdmodel->dataopd($this->request->getPost('sub_unit'));
        // $data = [
        //     'kd_skpd' => $listopd['kd_skpd'],
        //     'kd_sub_unit' => $listopd['kd_sub_unit'],
        //     'sub_unit' => $this->request->getPost('nm_opd'),
        // ];
        if ($nmopd == null) {
            $dataupdate = [
                'id' => $user->id, //$this->request->getPost('id'),
                'nama' => $this->request->getPost('nama'),
                'nip' => $this->request->getPost('nip'),
                'jabatan' => $this->request->getPost('jabatan'),
            ];
            $this->tausermodel->save($dataupdate);
            return redirect()->back()->withInput()->with('message', 'penyimpanan data berhasil');

            // return redirect()->to(base_url('lrfkopd/profile'));
        }
        if ($nmopd) {
            $dataupdate = [
                'id' => $user->id, //$this->request->getPost('id'),
                'kd_skpd' => $listopd['KODE_SKPD'],
                'kd_sub_unit' => $listopd['KODE_UNIT_SKPD'],
                'sub_unit' => $this->request->getPost('NAMA_UNIT_SKPD'),
                'nama' => $this->request->getPost('nama'),
                'nip' => $this->request->getPost('nip'),
                'jabatan' => $this->request->getPost('jabatan'),
            ];
            $this->tausermodel->save($dataupdate);
            return redirect()->to(base_url('lrfkopd'))->withInput()->with('message', 'penyimpanan data berhasil');

            // return view('lrfkopd', $data);
        }

        // echo dd($dataupdate); //"masuk update0";
    }
}
