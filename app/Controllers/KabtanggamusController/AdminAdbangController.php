<?php

namespace App\Controllers\KabtanggamusController;

use App\Controllers\Sipkabkota\SuperadminController;

use \Myth\Auth\Authorization\GroupModel;
use App\Models\KabtanggamusModel\RealApbdModel;
use App\Models\KabtanggamusModel\JadwalModel;
use App\Models\KabtanggamusModel\AnggaranKasModel;
use App\Models\KabtanggamusModel\RealisasiPendapatanModel;
use App\Models\UserModel\TaUserModel;
use App\Models\KabtanggamusModel\DataDashboardModel;
use App\Models\KabtanggamusModel\TglApbdModel;
use App\Models\KabtanggamusModel\ExcelApbdModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

use App\Controllers\BaseController;
use App\Libraries\CryptoUrl;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Base;

class AdminAdbangController extends BaseController
{
    protected $realapbdmodel;
    protected $tglapbdmodel;
    protected $excelmodel;
    protected $helpers = ['form', 'url'];
    protected $security;
    protected $jadwalmodel;
    protected $SuperadminController;
    protected $tausermodel;
    protected $anggaranKasModel;
    protected $realisasiPendapatanModel;
    protected $dataDashboardModel;
    protected $encrypter;
    public function __construct()
    {
        helper(['form', 'url', 'filesystem']);
        // Load library security untuk sanitasi
        $this->security = \Config\Services::security();
        $this->SuperadminController = new SuperadminController();
        $this->jadwalmodel = new JadwalModel();
        $this->tausermodel = new TaUserModel();
        $this->anggaranKasModel = new AnggaranKasModel();
        $this->realapbdmodel = new RealApbdModel();
        $this->tglapbdmodel = new TglApbdModel();
        $this->excelmodel = new ExcelApbdModel();
        $this->realisasiPendapatanModel = new RealisasiPendapatanModel();
        $this->dataDashboardModel = new DataDashboardModel();
        $this->encrypter      = \Config\Services::encrypter();
    }
    public function index()
    {
        $req = $this->request;
        $receivedParams = $req->getGet();
        $wilayah = $receivedParams['wilayah'] ?? null;
        $hal = $receivedParams['hal'] ?? null;
        $action = $receivedParams['action'] ?? null;
        $kdSU = $receivedParams['kdSU'] ?? null;
        $blndata = $receivedParams['blndata'] ?? null;
        $kdSK = $receivedParams['kdSK'] ?? null;
        $tgldataopd = $receivedParams['tgldataopd'] ?? null;
        $tgldata = $receivedParams['tgldata'] ?? null;
        $id = $receivedParams['id'] ?? null;

        $user = user();
        $groupModel = new GroupModel();
        $groupuser = $groupModel->getGroupsForUser($user->id);
        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        // $data['jadwalaktif'] = $this->jadwalmodel->jadwalaktifskrg();
        // echo dd($user);
        $data =
            [
                'wilayah' => session()->get('wilayah'),
                'namawilayah' => session()->get('namawilayah'),
                'groupuser' => $groupuser[0]['name'],
                'groupmenu' => $groupuser[0]['name'],
                'tahun' => session()->get('tahun'),
                'titlepage' => 'halaman Admin Adbang ',
                'datauser' => $user->sub_unit,
                'tglaktif' => session()->get('tglaktif'),
                'nama_opd' => $kdSU,
                'blndata' => $blndata,
                'kdSK' => $kdSK,
                'tgldataopd' => $tgldataopd,
                'tglapbd' => $this->realapbdmodel->tgldata(),
                'tglapbdaktif' => $this->tglapbdmodel->tgldataaktif(),
                'status' => 'apbd',
                'title' => 'Upload Realisasi APBD dari Data excel SIPD',
                'bulan' => $jadwalaktif['bulan'],
                'jadwal' => $this->jadwalmodel->jadwal(),
                'validation' => \Config\Services::validation(),
                'list_anggaran' => $this->anggaranKasModel->findAll(),

            ];

        if (!$receivedParams) {
            // echo dd($data);
            if (!$data['groupuser']) {
                $data = [
                    'title1' => 'Anda belum memilih wilayah kabupaten/kota diawal halaman atau anda belum memilih halaman hak akses'
                ];
                return view('hakakses', $data);
                // return redirect()->to(base_url());
            }
            if (!$data['wilayah']) {
                $data = [
                    'title1' => 'Anda belum memilih wilayah kabupaten/kota diawal halaman atau anda belum memilih halaman hak akses'
                ];
                return view('hakakses', $data);
                // return redirect()->to(base_url());
            }
            if (!$data['tahun']) {
                session()->set('groupuser', $data['groupuser']);
                session()->set('groupmenu', $data['groupuser']);
                return view('pilihtahun', $data);
            }

            $datatglaktif = $this->tglapbdmodel->tgldataaktif();
            session()->set('tglaktif', $datatglaktif['tanggal']);
            $tahun = session()->get('tahun');
            $tglaktif = session()->get('tglaktif');
            $data['dataopdadmin'] = $this->realapbdmodel->getCapaianKinerjaDenganGeometri($tahun, $tglaktif);
            // echo dd($tahun . '-'  . $tglaktif);
            // echo dd($data['dataopdadmin']);
            // echo dd($data);
            // echo dd($data['wilayah'] . '/' . $user->id);
            $urlasli = $req->getPath();
            $url = explode('/', $urlasli)[0];
            if ($url !== $data['wilayah']) {
                return redirect()->to(base_url('user'));
            }
            $tahunSelected = $this->request->getGet('tahun') ?? date('Y');
            $trenBulanan = $this->realisasiPendapatanModel->getTrenPersentaseBulanan($tahunSelected);
            $data['trenBulanan'] = $trenBulanan;
            $data['tahunSelected'] = $tahunSelected;
            // // return view('realisasi/form_input', [
            // //     'title'        => 'Input & Grafik Tren Realisasi APBD',
            // //     'tahunSelected' => $tahunSelected,
            // //     'trenBulanan'  => $trenBulanan
            // // ]);
            // return view('KabtanggamusViews/Adminadbang/form_inputpendapatan', $data);


            $tahunSelected = $this->request->getGet('tahun') ?? date('Y');
            $bulanSelected = $this->request->getGet('bulan') ?? 'all';

            $dataRealisasi = $this->realisasiPendapatanModel->getDataFilter($tahunSelected, $bulanSelected);
            $data['dataRealisasi'] = $dataRealisasi;
            $data['tahunSelected'] = $tahunSelected;
            $data['bulanSelected'] = $bulanSelected;
            // return view('realisasi/data_list', [

            return view('KabtanggamusViews/Adminadbang/index', $data);
        }
        $wilayah = session()->get('wilayah');
        if (!$wilayah) {
            return redirect()->to(base_url());
        }
        $this->ValidasiHash($req);
        if ($hal == 'apbdopd') {
            return view('KabtanggamusViews/Adminadbang/listtgldata', $data);
        } elseif ($hal == 'uploadapbd') {
            return view('KabtanggamusViews/Adminadbang/upload_form', $data);
        } elseif ($hal == 'tambahdataangkasopd') {
            $data['dataopdadmin'] = $this->realapbdmodel->getRekapSubSkpd(session()->get('tglaktif'));
            // Hanya enkripsi 'id' dan 'name'
            $dataopdapbds = array_map(function ($dataopdapbd) {
                $dataopdapbd['angkas_token'] = CryptoUrl::encrypt([
                    'hal'     => 'angkasopd',
                    'kdUS'   => $dataopdapbd['KODE_UNIT_SKPD'],
                    'kdSU'   => $dataopdapbd['NAMA_UNIT_SKPD'],
                    'total_anggaran' => $dataopdapbd['total_anggaran'],
                    'blndata' => $dataopdapbd['BULAN'],
                    'tgldata' => session()->get('tglaktif')
                ]);
                return $dataopdapbd;
            }, $data['dataopdadmin']);
            $datauserx = json_encode($dataopdapbds);
            $data['datajson'] = preg_replace('/"([^"]+)"\s*:/', '$1:', $datauserx);
            // echo dd($dataopdapbds);
            return view('KabtanggamusViews/Adminadbang/listopdangkas', $data);
        } elseif ($hal == 'listopdangkas') {
            $data['anggaran'] = $this->anggaranKasModel->findAll();
            $dataangkasopds = array_map(function ($dataangkasopd) use ($data, $jadwalaktif) {
                $dataangkasopd['hash'] = CryptoUrl::encrypt([
                    'hal'     => 'angkasopd',
                    'kdUS' => $dataangkasopd['perangkat_daerah_id'],
                    'kdSU' => $dataangkasopd['sub_unit'],
                    'total_anggaran' => $dataangkasopd['pagu_anggaran'],
                    'blndata' => $jadwalaktif['bulan'],
                    'tgldata' => session()->get('tglaktif')

                ]);
                $dataangkasopd['hashidhapusdata'] = CryptoUrl::encrypt([
                    'idhapusangkas'   => $dataangkasopd['id'],
                ]);
                return $dataangkasopd;
            }, $data['anggaran']);
            $data['anggaran'] = $dataangkasopds;
            // echo dd($dataangkasopds);
            return view('KabtanggamusViews/Adminadbang/listopdangkas2', $data);
        } elseif ($hal == 'angkasopd') {
            $data['list_anggaran'] = $this->anggaranKasModel->findAll();
            return view('KabtanggamusViews/Adminadbang/forminputangkasopd3lengkap2', $data);
        } elseif ($hal == 'datapbj') {
            $data['dataopdadmin'] = $this->realapbdmodel->getCapaianKinerjaDenganGeometri($data['tahun'], $data['tglaktif']);
            return view('KabtanggamusViews/Adminadbang/datapbj', $data);
        } elseif ($hal == 'jadwal') {
            $data['jadwalaktif'] = $this->jadwalmodel->jadwalaktifskrg();
            //tanggal data pada tabel apbd
            $data['tglapbd'] = $this->realapbdmodel->tgldata();
            $data['tglapbdaktif'] = $this->tglapbdmodel->tgldataaktif();
            // echo dd($data['tglapbd']);
            return view('KabtanggamusViews/Adminadbang/v_jadwal', $data);
        } elseif ($hal == 'pendapatan') {
            $tahunSelected = $this->request->getGet('tahun') ?? date('Y');
            $bulanSelected = $this->request->getGet('bulan') ?? 'all';

            $rawRealisasi = $this->realisasiPendapatanModel->getDataFilter($tahunSelected, $bulanSelected);
            $trenBulanan  = $this->realisasiPendapatanModel->getTrenPersentaseBulanan($tahunSelected);

            // Grouping Data Per Bulan
            $groupedData = [];
            $bulanTerakhir = 0;

            foreach ($rawRealisasi as $row) {
                $b = (int) $row['bulan'];
                $groupedData[$b][] = $row;
                if ($b > $bulanTerakhir) {
                    $bulanTerakhir = $b;
                }
            }

            // Subtotal Akumulasi khusus Bulan Terakhir
            $subtotalPendapatanBulanTerakhir = ['anggaran' => 0, 'realisasi' => 0];
            $subtotalBelanjaBulanTerakhir    = ['anggaran' => 0, 'realisasi' => 0];

            if (isset($groupedData[$bulanTerakhir])) {
                foreach ($groupedData[$bulanTerakhir] as $row) {
                    if ($row['jenis'] === 'Pendapatan') {
                        $subtotalPendapatanBulanTerakhir['anggaran']  += $row['anggaran'];
                        $subtotalPendapatanBulanTerakhir['realisasi'] += $row['realisasi'];
                    } else if ($row['jenis'] === 'Belanja') {
                        $subtotalBelanjaBulanTerakhir['anggaran']  += $row['anggaran'];
                        $subtotalBelanjaBulanTerakhir['realisasi'] += $row['realisasi'];
                    }
                }
            }

            $data['dataRealisasi'] = $rawRealisasi;
            $data['trenBulanan']   = $trenBulanan;
            $data['bulanTerakhir'] = $bulanTerakhir;
            $data['subtotalPendapatanBulanTerakhir'] = $subtotalPendapatanBulanTerakhir;
            $data['subtotalBelanjaBulanTerakhir']    = $subtotalBelanjaBulanTerakhir;
            $data['tahunSelected'] = $tahunSelected;
            $data['bulanSelected'] = $bulanSelected;
            $data['title'] = 'Data Realisasi APBD';
            $data['groupedData'] = $groupedData;

            return view('KabtanggamusViews/Adminadbang/data_listpendapatanFinalperBulan2', $data);
        } elseif ($hal == 'inputbulanan') {
            $summary = $this->realisasiPendapatanModel->getSummaryBulanTerakhir();
            $data['summaryStatis'] = $summary;
            $tahunSelected = $this->request->getGet('tahun') ?? date('Y');
            $trenBulanan = $this->realisasiPendapatanModel->getTrenPersentaseBulanan($tahunSelected);
            $data['trenBulanan'] = $trenBulanan;
            $data['tahunSelected'] = $tahunSelected;
            // return view('realisasi/form_input', [
            //     'title'        => 'Input & Grafik Tren Realisasi APBD',
            //     'tahunSelected' => $tahunSelected,
            //     'trenBulanan'  => $trenBulanan
            // ]);
            return view('KabtanggamusViews/Adminadbang/form_inputpendapatan', $data);
        } elseif ($hal == 'inputdashboardutama') {
            $dataStat = $this->dataDashboardModel->first();
            // echo dd($dataStat);
            // Buat record awal jika database masih kosong
            if (!$dataStat) {
                $this->dataDashboardModel->insert([
                    'jumlah_perangkat_daerah' => 125,
                    'jumlah_kecamatan'        => 12,
                    'jumlah_tiuh_kampung'     => 120,
                    'total_anggaran_apbd'     => '1.5T',
                    'index_sakip'             => '(B) 90.19',
                    'index_rb'                => '7.392',
                    'tingkat_kemiskinan'      => '28.5%',
                    'angka_stunting'          => '99.9%',
                ]);
            }
            $dataStat2 = $this->dataDashboardModel->first();

            // Encrypt ID untuk proteksi parameter URL/Form
            $encryptedId = bin2hex($this->encrypter->encrypt((string)$dataStat2['id']));

            $data['title'] = 'Input Data Dashboard Pembangunan';
            $data['stat'] = $dataStat2;
            $data['encryptedId'] = $encryptedId;
            $data['validation'] = \Config\Services::validation();
            return view('KabtanggamusViews/Adminadbang/form_inputdashboardutama', $data);
        } elseif ($hal == 'laporanrfkopd') {
            $data['dataRekap'] = $this->realapbdmodel->getRekapPerSKPD($jadwalaktif['bulan']);
            $data['bulan'] = $jadwalaktif['bulan'];
            $data['title'] = 'Rekap Per SKPD';
            // echo dd($data['dataRekap']);
            return view('KabtanggamusViews/Adminadbang/laporanlrfkopd2', $data);
        } elseif ($hal == 'laporanrfkopdperjenis') {
            $akun  = $this->request->getGet('akun') ?? 'nama_kelompok'; // Default akun
            // Ambil data dari model berdasarkan filter
            $rekapData = $this->realapbdmodel->getRekapRealisasiAkun($jadwalaktif['bulan'], $akun);
            // Kirim data ke View
            $data['bulan'] = $jadwalaktif['bulan'];
            $data['title'] = 'Rekap Per Jenis';
            $data['akun'] = $akun;
            $data['rekapData'] = $rekapData;
            // echo dd($data['rekapData']);
            return view('KabtanggamusViews/Adminadbang/laporanlrfkopdperakun3', $data);
        }
        if ($hal == 'gantibulanaktif') {
            return view('KabtanggamusViews/Adminadbang/v_listjadwal', $data);
        }
        if ($hal == 'editbulanaktif') {
            $idAwal = $jadwalaktif['id'] ?? null;
            $tahun = $jadwalaktif['tahun'] ?? null;
            $bulan = $jadwalaktif['bulan'] ?? null;
            $nonaktifbln = [
                'id' => $idAwal,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'status' => '0'
            ];
            $dataubah = $this->jadwalmodel->jdwlygdiaktifkan($id);
            $aktifbln = [
                'id' => $id,
                'tahun' => $dataubah['tahun'],
                'bulan' => $dataubah['bulan'],
                'status' => '1'
            ];
            // echo dd($nonaktifbln);
            $aktifasi = $this->jadwalmodel->save($aktifbln);
            $nonaktifasi = $this->jadwalmodel->save($nonaktifbln);
            if (!$aktifasi && !$nonaktifasi) {
                echo "Terjadi kesalahan";
            } else {
                return redirect()->to(hash_url('' . $data['wilayah'] . '/' . $data['groupuser'] . '/', ['hal' => 'jadwal']));
            }
        }
        if ($hal == 'gantitglaktif') {
            $updateAktif = $this->tglapbdmodel->updatetgl(1, $data['tahun'], $id);

            if (!$updateAktif) {
                session()->setFlashdata('message', 'Tanggal Data APBD berhasil diaktifkan');
            }
            // return redirect()->to(base_url('lrfkadmin/jadwal')->withInput()->with('message', 'Tanggal Data APBD berhasil diaktifkan'));
            return redirect()->to(hash_url('' . $data['wilayah'] . '/' . $data['groupuser'] . '/', ['hal' => 'jadwal']))->withInput()->with('message', 'Tanggal Data APBD berhasil diaktifkan');
        }
        if ($tgldata) {
            $data['dataopdadmin'] = $this->realapbdmodel->getRekapSubSkpd($tgldata);
            // Hanya enkripsi 'id' dan 'name'
            $dataopdapbds = array_map(function ($dataopdapbd) {
                $dataopdapbd['update_token'] = CryptoUrl::encrypt([
                    'kdSU'   => $dataopdapbd['NAMA_UNIT_SKPD'],
                    'blndata' => $dataopdapbd['BULAN']
                ]);
                return $dataopdapbd;
            }, $data['dataopdadmin']);
            $datauserx = json_encode($dataopdapbds);
            $data['datajson'] = preg_replace('/"([^"]+)"\s*:/', '$1:', $datauserx);
            // echo dd($data['dataopdadmin']);
            return view('KabtanggamusViews/Adminadbang/listopdapbd3', $data);
        }

        if ($hal == 'detail') {
            if ($action == 'opd') {
                $data['dataopd'] = $this->realapbdmodel->rpersk($kdSU, $data['tahun'], $blndata, $tgldataopd);
                // echo dd($kdSU . '-' . $tahunaktif . '-' . $bulanaktif . '-' . $tglaktif);
                // echo dd($data['dataopd']);
                return view('KabtanggamusViews/Adminadbang/detailopdapbd', $data);
            }
            if ($action == 'detailopd') {
                $data['dataopd'] = $this->realapbdmodel->rperrso2($kdSU, $kdSK, $data['tahun'], $tgldataopd)->getResultArray();

                // echo dd($kdSU . '-' . $kdSK . '-' . $tahunaktif . '-' . $tglaktif);
                // echo dd($data['dataopd']);
                return view('superadmin/2026/detailopdsro', $data);
            }
        }
    }
    public function getDataperAkun()
    {

        // Menangkap parameter dari URL (jika form filter disubmit)
        $bulan = $this->request->getGet('bulan');
        $akun  = $this->request->getGet('akun') ?? 'nama_akun'; // Default akun

        // Ambil data dari model berdasarkan filter
        $rekapData = $this->realapbdmodel->getRekapRealisasiAkun($bulan, $akun);

        // Kirim data ke View
        $user = user();
        $groupModel = new GroupModel();
        $groupuser = $groupModel->getGroupsForUser($user->id);

        $data = [
            'wilayah' => session()->get('wilayah'),
            'namawilayah' => session()->get('namawilayah'),
            'groupuser' => $groupuser[0]['name'],
            'groupmenu' => $groupuser[0]['name'],
            'tahun' => session()->get('tahun'),
            'titlepage' => 'halaman Admin Adbang ',
            'bulan'     => $bulan,
            'akun'      => $akun,
            'rekapData' => $rekapData

        ];
        return view('KabtanggamusViews/Adminadbang/laporanlrfkopdperakun3', $data);
    }
    public function cetakpdfrekapperakun()
    {
        $bulan = $this->request->getPost('bulan');
        $akun  = $this->request->getPost('akun');
        $chartImage = $this->request->getPost('chart_image'); // Menerima base64 gambar chart

        // Ambil data rekapitulasi berdasarkan filter yang sama
        $rekapData = $this->realapbdmodel->getRekapRealisasiAkun($bulan, $akun);
        // Path lokal file logo Anda
        $pathLogo = FCPATH . 'cssportal/img_home/lampung.png';
        $logoBase64 = '';

        // Cek apakah file logo benar-benar ada
        if (file_exists($pathLogo)) {
            $type = pathinfo($pathLogo, PATHINFO_EXTENSION);
            $data = file_get_contents($pathLogo);
            // Ubah ke format data URI base64
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        // Konfigurasi Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);

        // Buat template HTML khusus untuk PDF (atau parse dari view lain)
        $html = view('KabtanggamusViews/Adminadbang/laporanperakunpdf', [
            'rekapData'  => $rekapData ?? [],
            'bulan'      => $bulan,
            'akun'       => $akun,
            'chartImage' => $chartImage,
            'logoBase64' => $logoBase64
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Output file PDF (Stream / Download langsung di browser)
        $dompdf->stream("Rekap_Realisasi_Anggaran.pdf", ["Attachment" => false]);
    }
    public function dataperopd()
    {
        $token = $this->request->getGet('token');

        if (empty($token)) {
            return redirect()->back()->with('error', 'Token URL tidak ditemukan.');
        }

        // Dekripsi & Validasi Token (Maksimal 15 menit)
        $payload = CryptoUrl::decrypt($token, 1800); // 1800 detik = 30 menit

        if (!$payload) {
            return redirect()->back()->with('error', 'Akses ditolak: Token tidak valid atau telah kadaluwarsa.');
        }

        // Ekstrak data hasil dekripsi (hanya ID dan Name)
        $kdUS   = $payload['kdUS'] ?? null;
        $kdSU   = $payload['kdSU'] ?? null;
        $blndata = $payload['blndata'] ?? null;
        $tgldata = $payload['tgldata'] ?? null;
        $hal = $payload['hal'] ?? null;
        $total_anggaran = $payload['total_anggaran'] ?? null;

        $idhapusangkas = $payload['idhapusangkas'] ?? null;
        $user = user();
        $groupModel = new GroupModel();
        $groupuser = $groupModel->getGroupsForUser($user->id);
        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();

        $data =
            [
                'wilayah' => session()->get('wilayah'),
                'groupuser' => $groupuser[0]['name'],
                'groupmenu' => $groupuser[0]['name'],
                'tahunaktif' => session()->get('tahun'),
                'titlepage' => 'halaman Admin Adbang ',
                'datauser' => $user->sub_unit,
                'tglaktif' => session()->get('tglaktif'),
                'nama_opd' => $kdSU,
                'tgldata' => $tgldata,
                // 'rekap' => $this->realapbdmodel->getRekapLengkapPerTingkatAmanlengkapperOPDperProgram($blndata),
                // 'rekap' => $this->realapbdmodel->getRekapLengkapPerTingkatAmanlengkap($blndata, $kdSU),
                // 'rekap' => $this->realapbdmodel->getRekapLengkapPerTingkatAman($blndata, $kdSU),
                'rekap' => $this->realapbdmodel->getRekapLengkapPerTingkatAmanlengkapDetail($blndata, $kdSU),
                // 'rekap' => $this->realapbdmodel->getRekapLengkapPerTingkat($blndata, $kdSU),
                'blndata' => $blndata,
                'tglapbd' => $this->realapbdmodel->tgldata(),
                'tglapbdaktif' => $this->tglapbdmodel->tgldataaktif(),
                'bulan' => $jadwalaktif['bulan'],
                'validation' => \Config\Services::validation()

            ];
        if ($hal == 'angkasopd') {
            // echo dd($kdSU . '-' . $blndata . '-' . $tgldata);
            $data['kdUS'] = $kdUS;
            $data['sub_unit_skpd'] = $kdSU;
            $data['bulan'] = $blndata;
            $data['tanggal'] = $tgldata;
            $data['total_anggaran'] = $total_anggaran;
            // $data['dataopdadmin'] = $this->realapbdmodel->getRekapSubSkpd($tgldata);
            $data['listdataangkasopd'] = $this->anggaranKasModel->where('perangkat_daerah_id', $kdUS)
                ->where('tahun_anggaran', $data['tahunaktif'])->where('sub_unit', $kdSU)->first();
            // ->findAll();
            // echo dd($data['listdataangkasopd']);

            return view('KabtanggamusViews/Adminadbang/forminputangkasopd3lengkap2', $data);
        }
        // echo dd($payload['idhapusangkas']);
        if ($idhapusangkas) {
            $this->anggaranKasModel->delete($idhapusangkas);
            return redirect()->back();
        }
        return view('KabtanggamusViews/listsubkegOpd', $data);
    }
    public function update_sro()
    {
        $idSro = $this->request->getPost('id_sro');
        $realisasi = $this->request->getPost('realisasi');
        $anggaran = $this->request->getPost('anggaran');
        // echo dd($idSro . '-' . $realisasi . '-' . $anggaran);
        if ($realisasi > $anggaran) {
            return redirect()->back()->with('error', 'Realisasi tidak boleh melebihi anggaran.');
        } else {
            // Proses update ke database sesuai model Anda
            $this->realapbdmodel->update($idSro, ['TOTAL_REALISASI' => $realisasi]);

            return redirect()->back()->with('success', 'Data realisasi SRO berhasil diperbarui.');
        }
    }
    public function getDataJson()
    {
        // $bulan = $this->request->getGet('bulan') ?? date('m');
        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        $dataRekap = $this->realapbdmodel->getRekapPerSKPD($jadwalaktif['bulan']);
        return $this->response->setJSON($dataRekap);
    }

    public function exportXlsx()
    {
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        $data['bulan_nama'] = $months[(int)$bulan] ?? 'Bulan Tidak Valid';

        $dataRekap = $this->realapbdmodel->getRekapPerSKPD($data['bulan_nama']);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Judul Header Spreadsheet
        $sheet->setCellValue('A1', 'REKAPITULASI CAPAIAN DAN EFISIENSI PER SKPD');
        $sheet->setCellValue('A2', 'BULAN: ' . $data['bulan_nama']);
        $sheet->mergeCells('A1:J1');
        $sheet->mergeCells('A2:J2');
        $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(12);

        // Header Tabel
        $headers = [
            'NO',
            'KODE SKPD',
            'NAMA SKPD',
            'ANGGARAN (Rp)',
            'REALISASI (Rp)',
            'TOTAL SRO',
            'SRO NOL',
            'CAPAIAN REALISASI (%)',
            'CAPAIAN SRO (%)',
            'STATUS EFISIENSI'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '4', $header);
            $col++;
        }

        // Style Header Tabel
        $sheet->getStyle('A4:J4')->getFont()->setBold(true);
        $sheet->getStyle('A4:J4')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('D9E1F2');
        $sheet->getStyle('A4:J4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Isi Data
        $rowNum = 5;
        $no = 1;
        foreach ($dataRekap as $row) {
            $sheet->setCellValue('A' . $rowNum, $no++);
            $sheet->setCellValue('B' . $rowNum, $row['kode']);
            $sheet->setCellValue('C' . $rowNum, $row['nama']);
            $sheet->setCellValue('D' . $rowNum, $row['anggaran']);
            $sheet->setCellValue('E' . $rowNum, $row['realisasi']);
            $sheet->setCellValue('F' . $rowNum, $row['sro']);
            $sheet->setCellValue('G' . $rowNum, $row['sro_nol']);
            $sheet->setCellValue('H' . $rowNum, $row['capaian_realisasi'] / 100);
            $sheet->setCellValue('I' . $rowNum, $row['capaian_sro'] / 100);
            $sheet->setCellValue('J' . $rowNum, $row['status_efisiensi']);

            // Format Angka & Persentase
            $sheet->getStyle('D' . $rowNum . ':E' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle('H' . $rowNum . ':I' . $rowNum)->getNumberFormat()->setFormatCode('0.00%');
            $sheet->getStyle('A' . $rowNum . ':B' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F' . $rowNum . ':J' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $rowNum++;
        }

        // Border Tabel
        $styleBorder = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];
        $sheet->getStyle('A4:J' . ($rowNum - 1))->applyFromArray($styleBorder);

        // Auto Size Kolom
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Export File
        $filename = "Rekap_SKPD_Bulan_{$bulan}.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function exportPdf()
    {
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        $data['bulan_nama'] = $months[(int)$bulan] ?? 'Bulan Tidak Valid';
        $dataRekap = $this->realapbdmodel->getRekapPerSKPD($data['bulan_nama']);
        $data = [
            'title'     => 'Rekapitulasi Per SKPD',
            'bulan'     => $bulan,
            'dataRekap' => $dataRekap,
        ];
        // echo dd($data['bulan_nama']);
        $html = view('KabtanggamusViews/Adminadbang/laporanlrfkopdpdf', $data);
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $dompdf->stream("Rekap_SKPD_Bulan_{$bulan}.pdf", ["Attachment" => true]);
        exit;
    }
    public function updatedatadashboard()
    {
        $rules = [
            'enc_id'                  => 'required',
            'jumlah_perangkat_daerah' => 'required|integer|greater_than_equal_to[0]',
            'jumlah_kecamatan'        => 'required|integer|greater_than_equal_to[0]',
            'jumlah_tiuh_kampung'     => 'required|integer|greater_than_equal_to[0]',
            'total_anggaran_apbd'     => 'required|string|max_length[50]',
            'index_sakip'             => 'required|string|max_length[50]',
            'index_rb'                => 'required|string|max_length[50]',
            'tingkat_kemiskinan'      => 'required|string|max_length[50]',
            'angka_stunting'          => 'required|string|max_length[50]',
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'errors' => $this->validator->getErrors(),
                ]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $encId = $this->request->getPost('enc_id');
            $decryptedId = $this->encrypter->decrypt(hex2bin($encId));
        } catch (\Exception $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Token/ID tidak valid.']);
            }
            return redirect()->back()->with('error', 'Token/ID tidak valid.');
        }

        $saveData = [
            'jumlah_perangkat_daerah' => $this->request->getPost('jumlah_perangkat_daerah'),
            'jumlah_kecamatan'        => $this->request->getPost('jumlah_kecamatan'),
            'jumlah_tiuh_kampung'     => $this->request->getPost('jumlah_tiuh_kampung'),
            'total_anggaran_apbd'     => $this->request->getPost('total_anggaran_apbd'),
            'index_sakip'             => $this->request->getPost('index_sakip'),
            'index_rb'                => $this->request->getPost('index_rb'),
            'tingkat_kemiskinan'      => $this->request->getPost('tingkat_kemiskinan'),
            'angka_stunting'          => $this->request->getPost('angka_stunting'),
        ];

        $this->dataDashboardModel->update($decryptedId, $saveData);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Data Dashboard Pembangunan berhasil diperbarui!',
            ]);
        }

        return redirect()->to(hash_url('' . session()->get('wilayah') . '/' . session()->get('groupuser'), ['hal' => 'inputdashboardutama']))->with('success', 'Data Dashboard Pembangunan berhasil diperbarui!');
    }
    // Endpoint untuk data Grafik Ringkasan (Chart.js)
    public function getChartDataOpdRangking()
    {
        // $model = new SkpdModel();
        // $tahun = session()->get('tahun') ?? date('Y');
        $tgldata = session()->get('tglaktif') ?? null;
        $data['dataopdadmin'] = $this->realapbdmodel->getRekapSubSkpdRangking($tgldata);

        // Ambil data ringkasan per SKPD (nama, pagu, realisasi)
        $results = $this->realapbdmodel->getRekapSubSkpdRangking($tgldata);
        // echo dd($results);
        return $this->response->setJSON($results);
    }
    /*
     * ==========================================================================
     *  CRUD DATA REALISASI PENDAPATAN DAN BELANJA
     * ==========================================================================
     */

    // 2. Endpoint AJAX - Ambil Detail Single Data untuk Modal Edit
    public function getDetail($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['status' => 'error', 'message' => 'Method not allowed']);
        }

        $data = $this->realisasiPendapatanModel->find($id);
        if (!$data) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan.']);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    // 3. Endpoint AJAX - Update Data Realisasi
    public function update($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['status' => 'error', 'message' => 'Method not allowed']);
        }

        $row = $this->realisasiPendapatanModel->find($id);
        if (!$row) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan.']);
        }

        $anggaranRaw  = $this->request->getPost('anggaran');
        $realisasiRaw = $this->request->getPost('realisasi');

        $anggaran  = str_replace(['.', ','], ['', '.'], $anggaranRaw ?? '0');
        $realisasi = str_replace(['.', ','], ['', '.'], $realisasiRaw ?? '0');

        $updateData = [
            'tahun_anggaran' => $this->request->getPost('tahun_anggaran'),
            'bulan'          => $this->request->getPost('bulan'),
            'jenis'          => $this->request->getPost('jenis'),
            'kode_rekening'  => $this->request->getPost('kode_rekening'),
            'uraian'         => $this->request->getPost('uraian'),
            'anggaran'       => floatval($anggaran),
            'realisasi'      => floatval($realisasi),
        ];

        if ($this->realisasiPendapatanModel->update($id, $updateData)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil diperbarui!']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memperbarui data.']);
    }

    // 4. Endpoint AJAX - Hapus Data Realisasi
    public function delete($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['status' => 'error', 'message' => 'Method not allowed']);
        }

        $row = $this->realisasiPendapatanModel->find($id);
        if (!$row) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan.']);
        }

        if ($this->realisasiPendapatanModel->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil dihapus!']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus data.']);
    }
    // Halaman Utama Data Realisasi (Laporan & Grafik Gabungan)
    public function listData()
    {

        $tahunSelected = $this->request->getGet('tahun') ?? date('Y');
        $bulanSelected = $this->request->getGet('bulan') ?? 'all';

        $rawRealisasi = $this->realisasiPendapatanModel->getDataFilter($tahunSelected, $bulanSelected);
        $trenBulanan  = $this->realisasiPendapatanModel->getTrenPersentaseBulanan($tahunSelected);

        // Grouping Data Per Bulan
        $groupedData = [];
        $bulanTerakhir = 0;

        foreach ($rawRealisasi as $row) {
            $b = (int) $row['bulan'];
            $groupedData[$b][] = $row;
            if ($b > $bulanTerakhir) {
                $bulanTerakhir = $b;
            }
        }

        // Subtotal Akumulasi khusus Bulan Terakhir
        $subtotalPendapatanBulanTerakhir = ['anggaran' => 0, 'realisasi' => 0];
        $subtotalBelanjaBulanTerakhir    = ['anggaran' => 0, 'realisasi' => 0];

        if (isset($groupedData[$bulanTerakhir])) {
            foreach ($groupedData[$bulanTerakhir] as $row) {
                if ($row['jenis'] === 'Pendapatan') {
                    $subtotalPendapatanBulanTerakhir['anggaran']  += $row['anggaran'];
                    $subtotalPendapatanBulanTerakhir['realisasi'] += $row['realisasi'];
                } else if ($row['jenis'] === 'Belanja') {
                    $subtotalBelanjaBulanTerakhir['anggaran']  += $row['anggaran'];
                    $subtotalBelanjaBulanTerakhir['realisasi'] += $row['realisasi'];
                }
            }
        }
        $user = user();
        $groupModel = new GroupModel();
        $groupuser = $groupModel->getGroupsForUser($user->id);

        $data = [
            'wilayah' => session()->get('wilayah'),
            'namawilayah' => session()->get('namawilayah'),
            'groupuser' => $groupuser[0]['name'],
            'groupmenu' => $groupuser[0]['name'],
            'tahun' => session()->get('tahun'),
            'titlepage' => 'halaman Admin Adbang ',
        ];

        $data['dataRealisasi'] = $rawRealisasi;
        $data['trenBulanan']   = $trenBulanan;
        $data['bulanTerakhir'] = $bulanTerakhir;
        $data['subtotalPendapatanBulanTerakhir'] = $subtotalPendapatanBulanTerakhir;
        $data['subtotalBelanjaBulanTerakhir']    = $subtotalBelanjaBulanTerakhir;
        $data['tahunSelected'] = $tahunSelected;
        $data['bulanSelected'] = $bulanSelected;
        $data['title'] = 'Data Realisasi APBD';
        $data['groupedData'] = $groupedData;
        return view('KabtanggamusViews/Adminadbang/data_listpendapatanFinalperBulan2', $data);
    }

    // Export Excel (.xlsx) menggunakan Header HTML Spreadsheet
    public function exportExcelPendapatan()
    {
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $bulan = $this->request->getGet('bulan') ?? 'all';

        $rawRealisasi = $this->realisasiPendapatanModel->getDataFilter($tahun, $bulan);

        $groupedData = [];
        foreach ($rawRealisasi as $row) {
            $groupedData[(int)$row['bulan']][] = $row;
        }

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Laporan_Realisasi_APBD_{$tahun}.xls");

        echo '<table border="1">';
        echo '<tr><th colspan="7" style="font-size:16px; font-weight:bold;">LAPORAN REALISASI APBD TAHUN ' . $tahun . '</th></tr>';
        echo '<tr><td colspan="7"></td></tr>';

        foreach ($groupedData as $blnNum => $rows) {
            echo '<tr style="background-color:#d1e7dd;"><th colspan="7" style="text-align:left;">BULAN: ' . strtoupper($months[$blnNum] ?? "Bulan $blnNum") . '</th></tr>';
            echo '<tr style="background-color:#f8f9fa;"><th>No</th><th>Jenis</th><th>Kode Rekening</th><th>Uraian</th><th>Anggaran (Rp)</th><th>Realisasi (Rp)</th><th>Capaian (%)</th></tr>';

            $no = 1;
            $subAng = 0;
            $subReal = 0;

            foreach ($rows as $r) {
                $pct = $r['anggaran'] > 0 ? ($r['realisasi'] / $r['anggaran']) * 100 : 0;
                $subAng += $r['anggaran'];
                $subReal += $r['realisasi'];

                echo '<tr>';
                echo '<td>' . $no++ . '</td>';
                echo '<td>' . $r['jenis'] . '</td>';
                echo '<td>' . $r['kode_rekening'] . '</td>';
                echo '<td>' . $r['uraian'] . '</td>';
                echo '<td align="right">' . number_format($r['anggaran'], 0, ',', '.') . '</td>';
                echo '<td align="right">' . number_format($r['realisasi'], 0, ',', '.') . '</td>';
                echo '<td align="center">' . number_format($pct, 1, ',', '.') . '%</td>';
                echo '</tr>';
            }

            $subPct = $subAng > 0 ? ($subReal / $subAng) * 100 : 0;
            echo '<tr style="font-weight:bold; background-color:#f1f3f5;"><td colspan="4" align="right">SUBTOTAL BULAN ' . strtoupper($months[$blnNum]) . ':</td><td align="right">' . number_format($subAng, 0, ',', '.') . '</td><td align="right">' . number_format($subReal, 0, ',', '.') . '</td><td align="center">' . number_format($subPct, 1, ',', '.') . '%</td></tr>';
            echo '<tr><td colspan="7"></td></tr>';
        }

        echo '</table>';
        exit();
    }

    // Export PDF (Print View Realisasi Pendapatan dan Belanja) menggunakan Dompdf
    public function exportPdfrealisasiPendapatan()
    {
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $bulan = $this->request->getGet('bulan') ?? 'all';

        $rawRealisasi = $this->realisasiPendapatanModel->getDataFilter($tahun, $bulan);
        $trenBulanan  = $this->realisasiPendapatanModel->getTrenPersentaseBulanan($tahun);

        $groupedData = [];
        $bulanTerakhir = 0;
        foreach ($rawRealisasi as $row) {
            $b = (int) $row['bulan'];
            $groupedData[$b][] = $row;
            if ($b > $bulanTerakhir) $bulanTerakhir = $b;
        }

        return view('KabtanggamusViews/Adminadbang/print_pdf_pendapatan', [
            'tahun'         => $tahun,
            'bulan'         => $bulan,
            'groupedData'   => $groupedData,
            'trenBulanan'   => $trenBulanan,
            'bulanTerakhir' => $bulanTerakhir
        ]);
    }

    // Endpoint AJAX untuk re-render data grafik gabungan saat filter tahun diubah
    public function getTrenBulanan()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['status' => 'error']);
        }

        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $data  = $this->realisasiPendapatanModel->getTrenPersentaseBulanan($tahun);

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $data
        ]);
    }



    // METHOD BARU: Mengambil ringkasan data berdasarkan filter AJAX
    public function getSummaryByPeriode()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['status' => 'error', 'message' => 'Method Not Allowed']);
        }

        $tahun = $this->request->getGet('tahun');
        $bulan = $this->request->getGet('bulan');

        if (!$tahun || !$bulan) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Parameter tahun dan bulan wajib diisi.']);
        }

        $summary = $this->realisasiPendapatanModel->getSummaryByPeriode($tahun, $bulan);

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $summary
        ]);
    }
    /*
     * ==========================================================================
     *  CRUD DATA ANGGARAN KAS PERANGKAT DAERAH
     * ==========================================================================
     */
    public function storeangkas()
    {
        // 1. Validasi Input Server-Side
        $rules = [
            'perangkat_daerah_id' => 'required|numeric',
            'tahun_anggaran'      => 'required|numeric|exact_length[4]',
            'sub_kegiatan'        => 'required|min_length[3]',
            'pagu_anggaran'       => 'required',
            'anggaran_kas'        => 'required|is_array',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        /* 
         * [PERBAIKAN BUG]
         * Helper khusus untuk membersihkan string format Rupiah dari View (e.g. "100.000.000")
         * menjadi Float/Double murni yang aman dari glitch floating point.
         */
        $cleanRupiah = function ($val) {
            if (empty($val)) return 0.00;
            // Hapus semua karakter non-angka
            $cleaned = preg_replace('/[^0-9]/', '', $val);
            return round((float) $cleaned, 2);
        };

        // Parse Nilai Pagu Anggaran
        $paguAnggaran = $cleanRupiah($this->request->getPost('pagu_anggaran'));
        $inputBulanan = $this->request->getPost('anggaran_kas');

        $bulanList = [
            'januari',
            'februari',
            'maret',
            'april',
            'mei',
            'juni',
            'juli',
            'agustus',
            'september',
            'oktober',
            'november',
            'desember'
        ];

        $dataToSave = [
            'perangkat_daerah_id' => $this->request->getPost('perangkat_daerah_id'),
            'tahun_anggaran'      => $this->request->getPost('tahun_anggaran'),
            'sub_kegiatan'        => $this->request->getPost('sub_kegiatan'),
            'pagu_anggaran'       => $paguAnggaran,
        ];

        $totalKas = 0.00;

        // 2. Loop dan Ekstrak Data Input per Bulan
        foreach ($bulanList as $bulan) {
            $nilaiBulan = isset($inputBulanan[$bulan]) ? $cleanRupiah($inputBulanan[$bulan]) : 0.00;
            $dataToSave[$bulan] = $nilaiBulan;

            // Akumulasi total dengan pembulatan 2 desimal
            $totalKas = round($totalKas + $nilaiBulan, 2);
        }
        // echo dd($dataToSave);
        $dataToSave['total_anggaran_kas'] = $totalKas;

        /*
         * [PERBAIKAN BUG]
         * Validasi Keseimbangan (Balance): Pagu DPA VS Akumulasi Total Anggaran Kas
         * Menggunakan batas toleransi 0.01 cent untuk menghindari kesalahan pembulatan IEEE-754 float
         */
        if (abs($paguAnggaran - $totalKas) > 0.01) {
            $paguFormatted  = "Rp " . number_format($paguAnggaran, 0, ',', '.');
            $totalFormatted = "Rp " . number_format($totalKas, 0, ',', '.');

            return redirect()->back()->withInput()->with(
                'error',
                "Gagal Simpan! Total Anggaran Kas ({$totalFormatted}) tidak seimbang (unbalance) dengan Pagu DPA ({$paguFormatted}). Silakan periksa kembali."
            );
        }

        // 3. Simpan Ke Database Via Model
        $this->anggaranKasModel->simpan($dataToSave);
        return redirect()->back()->with('success', 'Data Anggaran Kas berhasil disimpan!');
        // return redirect()->to(hash_url('' . $data['wilayah'] . '/' . $data['groupuser'] . '/', ['hal' => 'angkasopd']));

        // return redirect()->to('/anggaran-kas')->with('success', 'Data Anggaran Kas berhasil disimpan!');
    }
    public function storeangkas_ajax()
    {

        //Jika eror penyimaan  cek file format.php diconfig pastikan ada skrip ini
        // TAMBAHKAN PROPERTI INI JIKA BELUM ADA DI CONFIG ANDA:
        // public int $jsonEncodeOptions = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
        // public int $jsonEncodeDepth = 512;

        // Netralkan Output Buffer dari output PHP liar
        if (ob_get_length()) {
            ob_clean();
        }
        // Helper lokal untuk kirim respon JSON murni tanpa melewati Formatter CI4 yang bermasalah
        $sendJson = function (int $statusCode, array $payload) {
            return $this->response
                ->setStatusCode($statusCode)
                ->setHeader('Content-Type', 'application/json')
                ->setBody(json_encode($payload, JSON_UNESCAPED_UNICODE));
        };
        try {
            // 1. Validasi Input Server-Side
            $rules = [
                'perangkat_daerah_id' => 'required',
                'tahun_anggaran'      => 'required|numeric|exact_length[4]',
                'sub_unit'              => 'required',
                'sub_kegiatan'        => 'required',
                'pagu_anggaran'       => 'required',
            ];

            if (!$this->validate($rules)) {
                return $sendJson(400, [
                    'status'     => 'error',
                    'message'    => 'Validasi gagal, harap periksa kembali inputan Anda.',
                    'errors'     => $this->validator->getErrors(),
                    'csrf_token' => csrf_hash()
                ]);
            }

            // Helper Pembersih String Format Rupiah ke Float
            $cleanRupiah = function ($val) {
                if (empty($val)) return 0.00;
                $cleaned = preg_replace('/[^0-9]/', '', (string)$val);
                return round((float) $cleaned, 2);
            };

            $paguAnggaran = $cleanRupiah($this->request->getPost('pagu_anggaran'));
            $inputBulanan = $this->request->getPost('anggaran_kas');

            if (!is_array($inputBulanan)) {
                return $sendJson(400, [
                    'status'     => 'error',
                    'message'    => 'Rincian anggaran bulanan tidak valid atau kosong.',
                    'csrf_token' => csrf_hash()
                ]);
            }

            $bulanList = [
                'januari',
                'februari',
                'maret',
                'april',
                'mei',
                'juni',
                'juli',
                'agustus',
                'september',
                'oktober',
                'november',
                'desember'
            ];

            $dataToSave = [
                'perangkat_daerah_id' => trim((string) $this->request->getPost('perangkat_daerah_id')),
                'tahun_anggaran'      => (int) $this->request->getPost('tahun_anggaran'),
                'sub_unit'            => trim((string) $this->request->getPost('sub_unit')),
                'sub_kegiatan'        => trim((string) $this->request->getPost('sub_kegiatan')),
                'pagu_anggaran'       => $paguAnggaran,
            ];

            $totalKas = 0.00;

            foreach ($bulanList as $bulan) {
                $nilaiBulan = isset($inputBulanan[$bulan]) ? $cleanRupiah($inputBulanan[$bulan]) : 0.00;
                $dataToSave[$bulan] = $nilaiBulan;
                $totalKas = round($totalKas + $nilaiBulan, 2);
            }

            $dataToSave['total_anggaran_kas'] = $totalKas;

            // 2. Cek Balance Pagu DPA vs Total Anggaran Kas
            if (abs($paguAnggaran - $totalKas) > 0.01) {
                $paguFormatted  = "Rp " . number_format($paguAnggaran, 0, ',', '.');
                $totalFormatted = "Rp " . number_format($totalKas, 0, ',', '.');

                return $sendJson(422, [
                    'status'     => 'error',
                    'message'    => "Total Anggaran Kas ({$totalFormatted}) tidak balance dengan Pagu DPA ({$paguFormatted}).",
                    'csrf_token' => csrf_hash()
                ]);
            }

            // 3. Simpan ke Database
            // echo dd($dataToSave);
            $id_angkas = (int) $this->request->getPost('id');
            if ($id_angkas == null) {
                $insertStatus = $this->anggaranKasModel->simpan($dataToSave);
            } else {
                $insertStatus = $this->anggaranKasModel->updatedata($id_angkas, $dataToSave);
            }
            if ($insertStatus === false) {
                return $sendJson(500, [
                    'status'     => 'error',
                    'message'    => 'Gagal menyimpan data ke database.',
                    'errors'     => $this->anggaranKasModel->errors(),
                    'csrf_token' => csrf_hash()
                ]);
            }

            return $sendJson(200, [
                'status'     => 'success',
                'message'    => 'Data Anggaran Kas berhasil disimpan!',
                'csrf_token' => csrf_hash()
            ]);
        } catch (\Throwable $e) {
            return $sendJson(500, [
                'status'     => 'error',
                'message'    => 'Server Exception: ' . $e->getMessage(),
                'file'       => basename($e->getFile()),
                'line'       => $e->getLine(),
                'csrf_token' => csrf_hash()
            ]);
        }
    }
    /**
     * Method untuk Export PDF anggaran kas menggunakan Dompdf
     */
    public function exportPdfAnggaranKas($id = null)
    {
        if (ob_get_length()) {
            ob_clean();
        }

        // Cari data berdasarkan ID atau ambil record terbaru
        $dataAnggaran = $id ? $this->anggaranKasModel->find($id) : $this->anggaranKasModel->orderBy('id', 'DESC')->first();

        if (!$dataAnggaran) {
            return $this->response->setStatusCode(404)->setBody('Data Anggaran Kas tidak ditemukan.');
        }

        // Daftar Nama SKPD (Sesuai ID)
        $namaPerangkatDaerah = [
            1 => 'Dinas Pekerjaan Umum dan Penataan Ruang',
            2 => 'Dinas Pendidikan dan Kebudayaan',
            3 => 'Dinas Kesehatan'
        ];

        $data = [
            'title'            => 'Dokumen Rencana Anggaran Kas',
            'perangkat_daerah' => $namaPerangkatDaerah[$dataAnggaran['perangkat_daerah_id']] ?? 'Perangkat Daerah',
            'tahun_anggaran'   => $dataAnggaran['tahun_anggaran'],
            'sub_kegiatan'     => $dataAnggaran['sub_kegiatan'],
            'pagu_anggaran'    => $dataAnggaran['pagu_anggaran'],
            'data'             => $dataAnggaran
        ];

        // Konfigurasi Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);

        // Load View PDF Template
        $html = view('KabtanggamusViews/Adminadbang/pdf_laporan_angkas', $data);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Stream PDF ke browser
        $filename = "Anggaran_Kas_" . url_title($data['sub_kegiatan'], '_', true) . "_" . date('YmdHis') . ".pdf";
        $dompdf->stream($filename, ["Attachment" => false]);
        exit();
    }
    public function exportPdfLaporanAngkasAdmin()
    {
        // $model = new \App\Models\AnggaranKasModel();
        $data['anggaran'] = $this->anggaranKasModel->findAll();

        // $html = view('Kab/pdflaporanangkasopd', $data);
        $html = view('KabtanggamusViews/Adminadbang/pdflaporanangkasopd', $data);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Stream file PDF langsung ke browser
        $dompdf->stream("laporan_anggaran_kas.pdf", ["Attachment" => false]);
    }
    public function uploadapbd()
    {
        ini_set('max_execution_time', 0);

        $rules = [
            'excel_file' => [
                'label' => 'Excel File',
                'rules' => 'uploaded[excel_file]|ext_in[excel_file,xls,xlsx]|max_size[excel_file,20480]',
                'errors' => [
                    'uploaded' => 'Harus mengupload file',
                    'ext_in' => 'Hanya file Excel yang diperbolehkan',
                    'max_size' => 'Ukuran file maksimal 2MB'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil file
        $file = $this->request->getFile('excel_file');

        // Generate nama file random untuk keamanan
        $newName = $file->getRandomName();
        $file->move(WRITEPATH . 'uploads', $newName);
        $filePath = WRITEPATH . 'uploads/' . $newName;

        try {
            // Load file Excel
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Siapkan data untuk database
            $dataToInsert = [];
            $headerSkipped = false;

            foreach ($rows as $row) {
                // Skip header
                if (!$headerSkipped) {
                    $headerSkipped = true;
                    continue;
                }
                $dataToInsert[] = [
                    'TAHUN' => $this->security->sanitizeFilename($row[0] ?? ''),
                    'BULAN' => $this->security->sanitizeFilename($row[1] ?? ''),
                    'KODE_SKPD' => $this->security->sanitizeFilename($row[2] ?? ''),
                    'NAMA_SKPD' => $this->security->sanitizeFilename($row[3] ?? ''),
                    'KODE_UNIT_SKPD' => $this->security->sanitizeFilename($row[4] ?? ''),
                    'NAMA_UNIT_SKPD' => $this->security->sanitizeFilename($row[5] ?? ''),
                    'KODE_URUSAN' => $this->security->sanitizeFilename($row[6] ?? ''),
                    'NAMA_URUSAN' => $this->security->sanitizeFilename($row[7] ?? ''),
                    'KODE_BIDANG_URUSAN' => $this->security->sanitizeFilename($row[8] ?? ''),
                    'NAMA_BIDANG_URUSAN' => $this->security->sanitizeFilename($row[9] ?? ''),
                    'KODE_PROGRAM' => $this->security->sanitizeFilename($row[10] ?? ''),
                    'NAMA_PROGRAM' => $this->security->sanitizeFilename($row[11] ?? ''),
                    'KODE_GIAT' => $this->security->sanitizeFilename($row[12] ?? ''),
                    'NAMA_GIAT' => $this->security->sanitizeFilename($row[13] ?? ''),
                    'KODE_SUB_GIAT' => $this->security->sanitizeFilename($row[14] ?? ''),
                    'NAMA_SUB_GIAT' => $this->security->sanitizeFilename($row[15] ?? ''),
                    'KODE_AKUN' => $this->security->sanitizeFilename($row[16] ?? ''),
                    'NAMA_AKUN' => $this->security->sanitizeFilename($row[17] ?? ''),
                    'KODE_KELOMPOK' => $this->security->sanitizeFilename($row[18] ?? ''),
                    'NAMA_KELOMPOK' => $this->security->sanitizeFilename($row[19] ?? ''),
                    'KODE_JENIS' => $this->security->sanitizeFilename($row[20] ?? ''),
                    'NAMA_JENIS' => $this->security->sanitizeFilename($row[21] ?? ''),
                    'KODE_OBJEK' => $this->security->sanitizeFilename($row[22] ?? ''),
                    'NAMA_OBJEK' => $this->security->sanitizeFilename($row[23] ?? ''),
                    'KODE_RINCIAN_OBJEK' => $this->security->sanitizeFilename($row[24] ?? ''),
                    'NAMA_RINCIAN_OBJEK' => $this->security->sanitizeFilename($row[25] ?? ''),
                    'KODE_SRO' => $this->security->sanitizeFilename($row[26] ?? ''),
                    'NAMA_SRO' => $this->security->sanitizeFilename($row[27] ?? ''),
                    'TOTAL_ANGGARAN' => $this->security->sanitizeFilename($row[28] ?? ''),
                    'TOTAL_REALISASI' => $this->security->sanitizeFilename($row[29] ?? ''),
                    'SELISIH' => $this->security->sanitizeFilename($row[30] ?? ''),
                    'KETERANGAN' => $this->security->sanitizeFilename($row[31] ?? ''),
                    // 'CREATE_AT' => $row[32],
                    // 'UPDATE_AT' => $row[33],
                ];
            }

            // Insert ke database dalam batch
            if (!empty($dataToInsert)) {
                $this->excelmodel->insertBatchData($dataToInsert);
            }

            // Hapus file setelah diproses
            unlink($filePath);
            $user = user();
            $groupModel = new GroupModel();
            $groupuser = $groupModel->getGroupsForUser($user->id);

            $data =
                [
                    'wilayah' => session()->get('wilayah'),
                    'groupuser' => $groupuser[0]['name'],
                    'groupmenu' => $groupuser[0]['name'],
                    'tahun' => session()->get('tahun'),
                    'titlepage' => 'halaman Admin Adbang ',
                    'datauser' => $user->sub_unit,
                    'tglaktif' => session()->get('tglaktif'),
                    'status' => 'apbd',
                    'title' => 'Upload Realisasi APBD dari Data excel SIPD',
                    'validation' => \Config\Services::validation()

                ];

            return redirect()->to(hash_url(' ' . $data['wilayah'] . '/' . $data['groupuser'] . '', ['hal' => 'uploadapbd']))->with('success', 'Data berhasil diupload: ' . count($dataToInsert) . ' record');
        } catch (\Exception $e) {
            // Hapus file jika terjadi error
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            log_message('error', 'Excel Upload Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    /**
     * Export Data Rekap ke Excel (.xlsx) dari Backend
     */
    public function exportExcelLrfkOpd()
    {
        $req = $this->request;
        $receivedParams = $req->getGet();
        $kdSU = $receivedParams['kdSU'] ?? null;
        $bulan = $receivedParams['blndata'] ?? null;
        // Fetch Data menggunakan Model yang sama
        $rekap = $this->realapbdmodel->getRekapLengkapPerTingkatAmanlengkap($bulan, $kdSU);
        // echo dd($rekap);
        if (empty($rekap)) {
            return redirect()->back()->with('error', 'Tidak ada data untuk diexport.');
        }

        // Inisialisasi Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan LRFK OPD ' . $bulan);

        // --- 1. HEADER JUDUL ---
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'LAPORAN REALISASI FISIK KEUANGAN & KINERJA PERANGKAT DAERAH (LRFK) - TAHUN '
            . session()->get('tahun'));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:G2');
        $sheet->setCellValue('A2', 'Bulan: ' . $bulan . ' | Nama SKPD: ' . ($kdSU ?: 'Semua SKPD'));
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(11);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // --- 2. HEADER TABEL ---
        $headers1 = [
            'A4' => 'Kode & Nama SKPD / Nama Program / Kegiatan / Sub Rincian Objek (Belanja) SRO',
            'B4' => 'Pagu Anggaran(Rp)',
            'C4' => 'Realisasi Anggaran(Rp)',
            'D4' => 'Capaian Realisasi Anggaran (%)',
            'E4' => 'Capaian Kinerja SRO',
            'F4' => 'Nilai Efisiensi (%)',
            'G4' => 'Status',

            'A5' => '1',
            'B5' => '2',
            'C5' => '3',
            'D5' => '4=(3/4)*100',
            'E5' => '5',
            'F5' => '6=(5-4)',
            'G5' => 'Status (7=6 "-" Inefisien,"+" Efisien)'
        ];

        foreach ($headers1 as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Styling Header Tabel
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '212529'] // Dark Background
            ]
        ];
        $sheet->getStyle('A4:G5')->applyFromArray($headerStyle);
        $sheet->getRowDimension(4)->setRowHeight(28);

        // --- 3. POPULASI DATA HIERARKI ---
        $row = 6; // Mulai dari baris ke-6 setelah header

        foreach ($rekap as $skpd) {
            // Level 1: SKPD
            $row = $this->writeExcelRow($sheet, $row, '[SKPD] [' . $skpd['kode'] . '] ' . $skpd['nama'], $skpd, 'D9E2EC', true);

            foreach ($skpd['program'] as $prog) {
                // Level 2: Program
                $row = $this->writeExcelRow($sheet, $row, '  [PROGRAM] [' . $prog['kode'] . '] ' . $prog['nama'], $prog, 'E2E8F0', true);

                foreach ($prog['kegiatan'] as $giat) {
                    // Level 3: Kegiatan
                    $row = $this->writeExcelRow($sheet, $row, '    [GIAT] [' . $giat['kode'] . '] ' . $giat['nama'], $giat, 'F1F5F9', false);

                    foreach ($giat['sub_kegiatan'] as $subGiat) {
                        // Level 4: Sub-Kegiatan
                        $row = $this->writeExcelRow($sheet, $row, '      [SUB GIAT] [' . $subGiat['kode'] . '] ' . $subGiat['nama'], $subGiat, 'FEF3C7', false);

                        foreach ($subGiat['list_sro'] as $sro) {
                            // Level 5: SRO
                            $sheet->setCellValue('A' . $row, '        [SRO] [' . $sro['kode'] . '] ' . $sro['nama']);
                            $sheet->setCellValue('B' . $row, $sro['anggaran']);
                            $sheet->setCellValue('C' . $row, $sro['realisasi']);
                            $sheet->setCellValue('D' . $row, '-');
                            $sheet->setCellValue('E' . $row, '-');
                            $sheet->setCellValue('F' . $row, '-');
                            $sheet->setCellValue('G' . $row, '-');

                            // Styling khusus level SRO
                            $sheet->getStyle('A' . $row)->getFont()->setItalic(true);
                            $sheet->getStyle('D' . $row . ':G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $row++;
                        }
                    }
                }
            }
        }

        // --- 4. FORMATTING KOLOM (Number Format, Borders, Alignment) ---
        $lastRow = $row - 1;

        // Number Format Rupiah/Angka
        $sheet->getStyle('B5:C' . $lastRow)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('D5:F' . $lastRow)->getNumberFormat()->setFormatCode('0.00');

        // Alignment Kolom
        $sheet->getStyle('B5:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('D5:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Border Seluruh Tabel
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'D3D3D3'],
                ],
            ],
        ];
        $sheet->getStyle('A4:G' . $lastRow)->applyFromArray($borderStyle);

        // Auto-size Lebar Kolom
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // --- 5. OUTPUT RESPONSE DOWNLOAD ---
        $filename = 'Rekap_Anggaran_SRO_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Helper privat untuk penulisan baris dan warna hirarki Excel
     */
    private function writeExcelRow($sheet, $row, $label, $data, $hexColor, $isBold)
    {
        $sheet->setCellValue('A' . $row, $label);
        $sheet->setCellValue('B' . $row, $data['anggaran']);
        $sheet->setCellValue('C' . $row, $data['realisasi']);
        $sheet->setCellValue('D' . $row, $data['capaian_realisasi']);
        $sheet->setCellValue('E' . $row, $data['capaian_sro']);
        $sheet->setCellValue('F' . $row, $data['efisiensi']);
        $sheet->setCellValue('G' . $row, $data['status_efisiensi']);

        // Color Fill & Font Weight
        $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray([
            'font' => ['bold' => $isBold],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $hexColor]
            ]
        ]);

        return $row + 1;
    }
    public function simpanapbd()
    {
        echo dd($this->request->getPost());
    }
}
