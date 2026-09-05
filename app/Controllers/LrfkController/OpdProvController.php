<?php

namespace App\Controllers\LrfkController;

use App\Models\LrfkProvModel\JadwalModel;
use App\Models\UserModel\TaUserModel;
use App\Models\LrfkProvModel\SubKegModel; // as lrfkModel;
use \Myth\Auth\Authorization\GroupModel;

use App\Models\UserModel;
use \Myth\Auth\Password;

use App\Models\LrfkProvModel\RealisasiSubKegModel;
use App\Models\LrfkProvModel\TotalRealisasiModel;
use App\Models\LrfkProvModel\TaIndikatorProgModal;
use App\Models\LrfkProvModel\TaIndikatorSubKegModal;
use App\Models\LrfkProvModel\TaRealisasiRinciModel;

use App\Libraries\PdfLibrary;
use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;
use FontLib\Table\Type\post;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;
use TCPDF;

class OpdProvController extends BaseController
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

    public function subkegiatan()
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
        $tahunaktif = session()->get('tahun'); //   $jadwalaktif['tahun'];
        $bulanaktif = $jadwalaktif['bulan'];
        if (!$tahunaktif) {
            return redirect()->to(base_url('lrfkopd'))->withInput()->with('message', 'Tahun aktif belum dipilih, silahkan pilih tahun aktif terlebih dahulu');
        }
        // echo dd($tahunaktif);

        $data = [
            'groupuser' => $namagroup,
            'groupmenu' => 'userlrfkprov',
            'titlepage' => 'Selamat Data di e-TAPIS Laporan Realisasi Fisik Anggaran Program Kegiatan Perangkat Daerah Provinsi Lampung',
            'datauser' => $this->tausermodel->listuser($user->id),
            'jadwalaktif' => $jadwalaktif,
            'tahun'  => $tahunaktif,
        ];
        $data['listopd'] = $this->subkegmodel->listopd($nm_sub_unit = null, $tahunaktif);
        $datauser = $this->tausermodel->listuser($user->id);
        $dataopd =  $this->subkegmodel->listopd($datauser['sub_unit'], $tahunaktif);
        $data['listprogram'] = $this->subkegmodel->listprogram($datauser['sub_unit'], $dataopd['kd_sub_unit'], $tahunaktif);

        return view('lrfk/opd/subkegiatan', $data);
    }
    public function index()
    {

        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        $tahunaktif =  session()->get('tahun'); //   $jadwalaktif['tahun'];
        if (!$tahunaktif) {
            return redirect()->to(base_url('lrfkopd'))->withInput()->with('message', 'Tahun aktif belum dipilih, silahkan pilih tahun aktif terlebih dahulu');
        }
        $bulanaktif = $jadwalaktif['bulan'];
        $req = $this->request;
        $this->ValidasiHash($req);
        // Contoh penggunaan parameter
        $receivedParams = $req->getGet();
        $page = $receivedParams['page'] ?? null;
        $action = $receivedParams['action'] ?? null;
        $kd = $receivedParams['kd'] ?? null;
        $kdR = $receivedParams['kdR'] ?? null;
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
            'titlepage' => 'Selamat Data di e-TAPIS Laporan Realisasi Fisik Anggaran Program Kegiatan Perangkat Daerah Provinsi Lampung',
            'datauser' => $this->tausermodel->listuser($user->id),
            'jadwalaktif' => $jadwalaktif,
            'tahunaktif' => $tahunaktif,
        ];


        $data['listopd'] = $this->subkegmodel->listopd($nm_sub_unit = null, $tahunaktif);

        $data['subkeg'] = $this->subkegmodel->subkeg($kdSK, $kdSU, $tahunaktif);
        $datauser = $this->tausermodel->listuser($user->id);
        $dataopd =  $this->subkegmodel->listopd($datauser['sub_unit'], $tahunaktif);
        $paguopd = $this->subkegmodel->totpaguopd($dataopd['kd_sub_unit'], $tahunaktif);

        $data['totpaguopd'] = $paguopd['pagu_rincian'];
        $data['totBelOpd'] = $paguopd['kd_rek_belanja'];

        // echo dd($paguopd);
        $data['blnskrg'] = $this->bulan(date('m'));

        if ($page == 'profile') {
            // if ($action == 'pass') {

            //     $data = [
            //         'groupuser' => $namagroup,
            //         'groupmenu' => $namagroup,
            //         'datauser' => $this->tausermodel->listuser($user->id),
            //         'titlepage' => 'Halaman Aktivasi User SiTAPIS Perangkat Daerah',
            //         'id' => $idUser,
            //         'title' => 'Update Password',
            //         'titlepage' => 'Halaman UBAH PASSWORD User SiTAPIS Perangkat Daerah',
            //     ];
            //     $data['listuser'] = $this->tausermodel->listuser($idUser);
            //     // echo dd($data['listuser']);
            //     return view('user/set_pasword', $data);
            // }
            return view('lrfk/opd/profile', $data);
        }

        if ($page == 'subkegiatan') {
            if ($action == 'list') {
                //  echo $page;
                $data['listprogram'] = $this->subkegmodel->listprogram($datauser['sub_unit'], $dataopd['kd_sub_unit'], $tahunaktif);
                return view('lrfk/opd/subkegiatan', $data);
            }
        }
        if ($page == 'progresrealisasi') {
            $jadwalall = $this->jadwalmodel->findAll();
            $data['datajadwal'] =  $jadwalall;
            if ($action == 'listall') {
                if (!$bulan) {
                    $data['bulanpilih'] = '';
                } else {
                    $data['bulanpilih'] = $bulan;
                }
                $datauser = $this->tausermodel->listuser($user->id);
                $data['listapbdopd'] = $this->subkegmodel->paguperurusan($datauser['sub_unit'], $dataopd['kd_sub_unit'], $tahunaktif);
                // echo dd($data['listapbdopd']);
                return view('lrfk/opd/subkegallrinci2', $data);
            }
        }

        if ($page == 'progres') {
            if ($action == 'lapor') {
                $data['subkeg'] = $this->subkegmodel->subkeg($kdSK, $kdSU, $tahunaktif);
                // $data['subkegbelanja'] = $this->subkegmodel->subkegbelanja($kdSK, $kdSU);
                $pagusk = $this->subkegmodel->pagusubkeg($kdSK, $kdSU, $tahunaktif);
                $data['totalpagusubkeg'] = $pagusk['pagu_rincian'];
                $datauser = $this->tausermodel->listuser($user->id);
                if ($bulanaktif == 'Januari') {
                    $data['bln'] = 'Januari';
                }
                if ($bulanaktif == 'Februari') {
                    $data['bln'] = 'Januari';
                }
                if ($bulanaktif == 'Maret') {
                    $data['bln'] = 'Februari';
                }
                if ($bulanaktif == 'April') {
                    $data['bln'] = 'Maret';
                }
                if ($bulanaktif == 'Mei') {
                    $data['bln'] = 'April';
                }
                if ($bulanaktif == 'Juni') {
                    $data['bln'] = 'Mei';
                }
                if ($bulanaktif == 'Juli') {
                    $data['bln'] = 'Juni';
                }
                if ($bulanaktif == 'Agustus') {
                    $data['bln'] = 'Juli';
                }
                if ($bulanaktif == 'September') {
                    $data['bln'] = 'Agustus';
                }
                if ($bulanaktif == 'Oktober') {
                    $data['bln'] = 'September';
                }
                if ($bulanaktif == 'November') {
                    $data['bln'] = 'Oktober';
                }
                if ($bulanaktif == 'Desember') {
                    $data['bln'] = 'November';
                }
                // if ($bulanaktif == '12') {
                //     $bln = 'Desember';
                // }
                $data['subkegbelanja'] = $this->subkegmodel->subkegbelanja($kdSK, $kdSU, $tahunaktif);

                // // $data['datarealisasirinci'] = $this->realisasilrfkrinci->realperSKpeBlnRinci3(
                // //     $kdSU,
                // //     $kdSK,
                // //     $tahunaktif,
                // //     $bulanaktif
                // // );
                // // if (!$data['datarealisasirinci']) {
                // //     $data['subkegbelanja'] = $this->subkegmodel->subkegbelanja($kdSK, $kdSU);
                // // } else {
                // //     $data['subkegbelanja'] = $this->realisasilrfkrinci->realperSKpeBlnRinci3(
                // //         $kdSU,
                // //         $kdSK,
                // //         $tahunaktif,
                // //         $bulanaktif
                // //     );
                //     // echo dd($data['datarealisasirinci']);
                // }
                // echo dd($data['subkegbelanja']);

                return view('lrfk/opd/form_realisasi_rinci', $data);
            }
            if ($action == 'lihat') {
                $data['subkegbelanja'] = $this->subkegmodel->subkegbelanja($kdSK, $kdSU, $tahunaktif);
                $data['datarealisasirinci'] = $this->realisasilrfkrinci->realperSKpeBlnRinci3(
                    $kdSU,
                    $kdSK,
                    $tahunaktif,
                    $bulanaktif
                );

                if (!$data['datarealisasirinci']) {
                    echo dd("data kosong");
                }
                return view('lrfk/opd/v_realisasirinci', $data);
            }
            if ($action == 'hapus') {

                $this->realisasilrfkrinci->delete($kdR);
                return redirect()->to('lrfkopd/subkegiatan')->withInput()->with('message', 'Hapus data berhasil');
            }
            if ($action == 'lihatpersubkeg') {
                $data['subkeg'] = $this->subkegmodel->subkeg($kdSK, $kdSU, $tahunaktif);
                $datauser = $this->tausermodel->listuser($user->id);
                $data['datarealisasi'] = $this->realisasilrfk->RperSubKegOpd($kdSK, $tahunaktif, $kdSU);
                $data['jadwal'] = $this->jadwalmodel->bulan($tahunaktif);
                return view('lrfk/opd/v_realisasipersubkeg', $data);
            }
        }
        if ($page == 'cetakopd') {
            if ($action == 'lrfkperurusan') {
                $jadwalall = $this->jadwalmodel->findAll();
                $data['datajadwal'] =  $jadwalall;
                if (!$bulan) {
                    $data['bulanpilih'] = '';
                } else {
                    $data['bulanpilih'] = $bulan;
                }
                $datauser = $this->tausermodel->listuser($user->id);
                $data['listapbdopd'] = $this->subkegmodel->paguperurusan($datauser['sub_unit'], $dataopd['kd_sub_unit'], $tahunaktif);
                $data['datauser'] = $datauser;
                // echo dd($data['datauser']);
                $html = view('/lrfk/opd/CetakApbdOpd2', $data);
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
                $pdf->Output('Rekap_LRFK_' . $tahunaktif . '_' . $datauser['sub_unit'], 'I');
            }
        }
    }
    public function saveprogres()
    {

        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        // $tahunaktif = $jadwalaktif['tahun'];
        // $bulanaktif = $jadwalaktif['bulan'];

        $datareal = $this->request->getPost('datareal');
        $jumlah = count($datareal); // Mengetahui jumlah elemen
        for ($i = 0; $i < $jumlah; $i++) {
            if ($datareal[$i]['realisasi'] > $datareal[$i]['pagu_rincian']) {
                return redirect()->back()->withInput()->with('message', 'Realisasi  ' . $datareal[$i]['nm_rekening'] . '  melebihi pagu belanja');
            }
        }

        if ($datareal[0]['id']) {
            // echo dd($datareal);
            $this->realisasilrfkrinci->updateBatch($datareal, 'id');
            return redirect()->to(base_url('lrfkopd/subkegiatan'))->withInput()->with('message', 'penyimpanan data berhasil');
        } else {
            // echo dd($datareal);
            $this->realisasilrfkrinci->insertBatch($datareal);
            return redirect()->to(base_url('lrfkopd/subkegiatan'))->withInput()->with('message', 'penyimpanan data berhasil');
        }
    }

    public function updateprofile()
    {
        $tahunaktif = session()->get('tahun'); //   $jadwalaktif['tahun'];

        $data['titlepage'] = 'Selamat Data di e-TAPIS Laporan Realisasi Fisik Anggaran Program Kegiatan Perangkat Daerah Provinsi Lampung';
        $data['groupuser'] = 'forbiddenopd';
        $data['listopd'] = $this->subkegmodel->listopd($opd = null, $tahunaktif);

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
        $listopd = $this->subkegmodel->listopd($this->request->getPost('sub_unit'), $tahunaktif);
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
                'kd_skpd' => $listopd['kd_skpd'],
                'kd_sub_unit' => $listopd['kd_sub_unit'],
                'sub_unit' => $this->request->getPost('sub_unit'),
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
