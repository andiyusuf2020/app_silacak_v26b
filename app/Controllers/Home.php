<?php

namespace App\Controllers;

use \Myth\Auth\Authorization\GroupModel;
use App\Models\UserModel\TaUserModel;
use App\Models\LrfkProvModel\SubKegModel; // as lrfkModel;
use App\Models\LrfkProvModel\RealisasiSubKegModel;
use App\Models\LrfkProvModel\JadwalModel;
use App\Models\LrfkProvModel\TaRealPertahunModel;
use App\Models\LrfkProvModel\TaRealisasiRinciModel;
use PHPUnit\Framework\Constraint\Count;

// use App\Models\LrfkProvModel\TaResumeRealisasiModel;

use App\Models\DataApbdModel\RealApbdModel;
use App\Models\DataApbdModel\TglApbdModel;
use App\Models\ExcelRSipdModel;
use App\Models\CapkinModel\TaRKegPokokCapkinModel;
use App\Models\CapkinModel\TaKategoriModel;

use PhpOffice\PhpSpreadsheet\Shared\Date;

class Home extends BaseController
{
    protected $subkegmodel;

    protected $realisasilrfk;
    protected $jadwalmodel;
    protected $realpertahun;
    protected $realisasilrfkrinci;
    protected $resumerealiasi;
    protected $tausermodel;
    protected $sipdmodel;
    protected $rdkegpokokmodal;
    protected $kategorimodel;

    protected $realapbdmodel;
    protected $tglapbdmodel;
    protected $namag;
    public function __construct()
    {
        helper(['form', 'url', 'filesystem']);
        $this->subkegmodel = new SubKegModel();
        $this->realisasilrfk = new RealisasiSubKegModel();
        $this->realisasilrfkrinci = new TaRealisasiRinciModel();
        $this->rdkegpokokmodal = new TaRKegPokokCapkinModel();

        $this->jadwalmodel = new JadwalModel();
        $this->realpertahun = new TaRealPertahunModel();
        $this->tausermodel = new TaUserModel();
        // $this->realisasilrfk = new TaResumeRealisasiModel();
        $this->realapbdmodel = new RealApbdModel();
        $this->tglapbdmodel = new TglApbdModel();
        $this->sipdmodel = new ExcelRSipdModel();
        $this->kategorimodel = new TaKategoriModel();
    }
    function namagroupuser($id)
    {
        // $user = user();
        $groupModel = new GroupModel();
        $groupuser = $groupModel->getGroupsForUser($id);
        foreach ($groupuser as $row) {
            $namagroup = $row['name'];
        }
        return $namagroup;
    }
    public function maintenis()
    {
        return view('maintenis/index.php');
    }
    public function index()
    {
        session()->set('tahun', '2026');
        $tgl = $this->tglapbdmodel->tgldataaktif();

        $tgldata = $tgl['tanggal'];
        // $data['dataopdadmin'] = $this->sipdmodel->listopdadmin($tgldata);
        $data['dataopdadmin'] = $this->realapbdmodel->listopdadmin($tgldata);

        // echo dd($data['dataopdadmin']);
        return view('eksekutif/dashboard3', $data);
    }
    public function eksekutifcapkin()
    {
        $req = $this->request;
        // Contoh penggunaan parameter
        $this->ValidasiHash($req);
        $receivedParams = $req->getGet();
        $kdSU = $receivedParams['kdSU'] ?? null;
        $nmSU = $receivedParams['nmSU'] ?? null;
        $action = $receivedParams['action'] ?? null;
        $idD = $receivedParams['idD'] ?? null;

        // $idD = $this->request->getVar('idD');
        session()->set('tahun', '2026');
        $tahunaktif = session()->get('tahun');


        if ($idD) {
            if ($tahunaktif == 2025) {
                $data = [
                    'title' => 'Detail Lokasi',
                    'lokasi' => $this->rdkegpokokmodal->getLokasi($tahunaktif, $kdSU, $idD)
                ];
                // $data['lokasi'] = $this->rdkegpokokmodal->getLokasi($tahundata, $data['datauser']['kd_sub_unit'], $id_dr = false);
            } else {
                $data = [
                    'title' => 'Detail Lokasi',
                    'lokasi' => $this->rdkegpokokmodal->getLokasi26($tahunaktif, $kdSU, $idD)
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



        if ($action == 'detaildokumentasi') {

            $tgldata = '2026-07-13';
            $data['dataopdadmin'] = $this->realapbdmodel->listopdadmin($tgldata);
            $data['kategoriList'] = $this->kategorimodel->listkategori(); //$this->lokasiModel->getKategori(),

            $data['nama_opd'] = $nmSU;
            $data['kdSU'] = $kdSU;
            if ($tahunaktif == 2025) {
                $data['lokasi'] = $this->rdkegpokokmodal->getLokasi($tahunaktif, $kdSU, $id_dr = false);
            } else {
                $data['lokasi'] = $this->rdkegpokokmodal->getLokasi26($tahunaktif, $kdSU, $id_dr = false);
            }
            return view('eksekutif/listdokumentasiopd', $data);
        }
    }
    public function log()
    {
        return view('login2');
    }
    public function appprov()
    {
        return view('appportal/appprov');
        // return view('eksekutif/dashboard2');

        // return view('template/layout_v4');
    }
    public function gratech()
    {
        return view('appportal/home');
        // return view('appportal/index');
    }
    public function simpantahun()
    {

        $tahun = $this->request->getPost('tahun');
        $tgldata = $this->tglapbdmodel->tgldataaktif();

        // echo dd($tahun . ' - ' . $tglaktif);
        // echo dd($tgldata);
        $groupmenu = session()->get('groupmenu');
        // simpan tahun di session
        session()->set('tahun', $tahun);
        if ($tahun == '2025') {
            session()->set('tglaktif', '2026-03-17');
        } else {
            session()->set('tglaktif', $tgldata['tanggal']);
        }
        // session()->set('tglaktif', $tgldata['tanggal']);

        if ($groupmenu == 'userlrfkprov') {
            return redirect()->to(base_url('lrfkopd'));
        }
        if ($groupmenu == 'usercapkinprov') {
            return redirect()->to(base_url('capkin'));
        }
        if ($groupmenu == 'adminprov') {
            return redirect()->to(base_url('lrfkadmin'));
        }
        if ($groupmenu == 'userdesakumaju') {
            return redirect()->to(base_url('desakumaju/opd'));
        }
    }

    public function adbang()
    {
        $data['titlepage'] = 'Selamat Data di e-TAPIS Program Kerja Gubernur dan Wakil Gubernur Lampung 2026-20230';
        $data['groupuser'] = 'adminprogkerja';
        $data['groupmenu'] = 'adminprogkerja';

        return view('appprov/dashboard', $data);
    }
    public function user()
    {
        if (logged_in()) {
            $user = user();
            $groupModel = new GroupModel();
            $groupuser = $groupModel->getGroupsForUser($user->id);
            foreach ($groupuser as $row) {
                $namagroup = $row['name'];
            }
        }
        $data['titlepage'] = 'Selamat Data di Si-TAPIS Biro Administrasi Pembangunan Setda Provinsi Lampung';
        $data['groupuser'] = $namagroup;
        $data['groupmenu'] = '';

        return view('user/userumum', $data);
    }
    public function lrfkadmin()
    {
        if (logged_in()) {
            $user = user();
            $groupModel = new GroupModel();
            $groupuser = $groupModel->getGroupsForUser($user->id);
            foreach ($groupuser as $row) {
                $namagroup = $row['name'];
            }
        }
        $data['titlepage'] = 'Selamat Data di e-TAPIS Laporan Realisasi Fisik Anggaran Program Kegiatan Perangkat Daerah Provinsi Lampung';
        $data['groupuser'] = $namagroup;
        $data['groupmenu'] = $namagroup;
        $tahun = session()->get('tahun');
        $tgldataaktif = session()->get('tglaktif');
        // if ($data['groupuser'] == 'user') {
        //     return redirect()->to(base_url('user'));
        // }

        if (!$tahun) {
            session()->set('groupuser', $namagroup);
            session()->set('groupmenu', $namagroup);
            return view('pilihtahun', $data);
        }
        // echo dd($data['groupmenu']);
        // echo dd($tahun . '-'  . $tgldataaktif);
        return view('lrfk/admin/dashboard', $data);
    }
    public function lrfkopd()
    {

        // if (logged_in()) {
        $user = user();
        $namagroup = $this->namagroupuser($user->id);
        // $groupModel = new GroupModel();
        // $groupuser = $groupModel->getGroupsForUser($user->id);
        // //     foreach ($groupuser as $row) {
        //         $namagroup = $row['name'];
        //     }
        // }
        // $namag = $this->namag;
        // echo dd($namagroup);
        $datauser = $this->tausermodel->listuser($user->id);
        $tahunaktif = session()->get('tahun');
        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();

        if ($tahunaktif == Date('Y')) {
            $tglaktif = session()->get('tglaktif');
            $bulan = $jadwalaktif['bulan']; //$data['bln'];
            $data['bulan'] = $bulan;
            $data['dataopd'] = $this->realapbdmodel->dataopd($datauser['sub_unit']);
        } else {
            $tglaktif = '2026-07-29'; //session()->get('tglaktif');
            $bulan = 'Desember';
            $data['bulan'] = $bulan;
            $data['dataopd'] = $this->realapbdmodel->dataopd2025($datauser['sub_unit']);
            // echo dd($datauser['sub_unit'] . ' - ' . $tahunaktif . ' - ' . $bulan . ' - ' . $tglaktif);
        }

        $data['tahunaktif'] = session()->get('tahun');
        $data['tglaktif'] = $tglaktif; //session()->get('tglaktif');
        $data['titlepage'] = 'Selamat Data di e-TAPIS Laporan Realisasi Fisik Anggaran Program Kegiatan Perangkat Daerah Provinsi Lampung';
        $data['listopd'] = $this->subkegmodel->listopd($nm_sub_unit = null, $data['tahunaktif']);
        $data['groupuser'] = $namagroup;
        $data['groupmenu'] = 'userlrfkprov';


        // if ($data['tahunaktif'] == '2026') {
        //     echo "APBD TA 2026 DALAM TAHAP PERENCANAAN";
        // }

        if (!$data['tahunaktif']) {
            session()->set('groupuser', $namagroup);
            session()->set('groupmenu', 'userlrfkprov');
            return view('pilihtahun', $data);
        }
        if ($data['groupuser'] == 'user') {
            return redirect()->to(base_url('user'));
        }
        if ($data['groupuser'] == 'adminprov') {
            return redirect()->to(base_url('lrfkadmin'));
        }
        if ($datauser['sub_unit'] == '') {
            $data['groupuser'] = 'forbiddenopd';
            return view('lrfk/opd/v_forbidden', $data);
        }
        if ($datauser['sub_unit'] == '') {
            $data['groupuser'] = 'forbiddenopd';
            return view('lrfk/opd/v_forbidden', $data);
        }
        // return redirect()->to(base_url('lrfkopd'));
        //  return redirect(base_url('lrfkopd'));
        if ($jadwalaktif['bulan'] == 'Januari') {
            $data['bln'] = 'Januari';
        }
        if ($jadwalaktif['bulan'] == 'Februari') {
            $data['bln'] = 'Januari';
        }
        if ($jadwalaktif['bulan'] == 'Maret') {
            $data['bln'] = 'Februari';
        }
        if ($jadwalaktif['bulan'] == 'April') {
            $data['bln'] = 'Maret';
        }
        if ($jadwalaktif['bulan'] == 'Mei') {
            $data['bln'] = 'April';
        }
        if ($jadwalaktif['bulan'] == 'Juni') {
            $data['bln'] = 'Mei';
        }
        if ($jadwalaktif['bulan'] == 'Juli') {
            $data['bln'] = 'Juni';
        }
        if ($jadwalaktif['bulan'] == 'Agustus') {
            $data['bln'] = 'Juli';
        }
        if ($jadwalaktif['bulan'] == 'September') {
            $data['bln'] = 'Agustus';
        }
        if ($jadwalaktif['bulan'] == 'Oktober') {
            $data['bln'] = 'September';
        }
        if ($jadwalaktif['bulan'] == 'November') {
            $data['bln'] = 'Oktober';
        }
        if ($jadwalaktif['bulan'] == 'Desember') {
            $data['bln'] = 'November';
        }
        // if ($jadwalaktif['bulan'] == 'Desember') {
        //     $bln = 'Desember';
        // }

        // echo dd($tahunaktif . ' - ' . $tglaktif . ' - ' . $bulan);
        $data['nm_opd'] = $datauser['sub_unit'];

        // echo dd($data['dataopd']);
        $data['totanggaran'] = $data['dataopd']['anggaran'] ?? 0;
        $data['totrealisasi'] = $data['dataopd']['realisasi'] ?? 0;

        $data['datarealisasi'] = $this->realapbdmodel->rpersk($datauser['sub_unit'], $tahunaktif, $bulan, $tglaktif);
        $data['dataperRSatk'] = $this->realapbdmodel->rperrob($datauser['sub_unit'], $tahunaktif, $bulan, $tglaktif, 'Alat Tulis Kantor');
        $data['dataperRSperjadin'] = $this->realapbdmodel->rperrob($datauser['sub_unit'], $tahunaktif, $bulan, $tglaktif, 'Perjalanan Dinas');
        $data['dataperRSCetak'] = $this->realapbdmodel->rperrob($datauser['sub_unit'], $tahunaktif, $bulan, $tglaktif, 'Bahan Cetak');

        $data['bOperasi'] = $this->realapbdmodel->rperkelbel($datauser['sub_unit'], $tahunaktif, $bulan, $tglaktif, '5.1.');
        $data['bPegawai'] = $this->realapbdmodel->rperkelbel($datauser['sub_unit'], $tahunaktif, $bulan, $tglaktif, '5.1.01.');
        $data['bBarjas'] = $this->realapbdmodel->rperkelbel($datauser['sub_unit'], $tahunaktif, $bulan, $tglaktif, '5.1.02.');
        $data['bModal'] = $this->realapbdmodel->rperkelbel($datauser['sub_unit'], $tahunaktif, $bulan, $tglaktif, '5.2.');

        // echo dd($data['dataperRSatk']);
        // echo dd($datauser['sub_unit'] . ' - ' . $tahunaktif . ' - ' . $bulan . ' - ' . $tglaktif);.
        $belatk = $this->subkegmodel->belatk($datauser['sub_unit']);
        $belcetak = $this->subkegmodel->belcetak($datauser['sub_unit']);
        $belsppd = $this->subkegmodel->belsppd($datauser['sub_unit']);
        $totpaguopd = $this->subkegmodel->totpaguopd2($datauser['sub_unit']);
        $data['atk'] = $data['dataperRSatk']['anggaran'] / $data['totanggaran'] * 100;
        $data['cetak'] = $data['dataperRSCetak']['anggaran'] / $data['totanggaran'] * 100;
        $data['sppd'] = $data['dataperRSperjadin']['anggaran'] / $data['totanggaran'] * 100;
        $data['rpatk'] = $belatk['pagu_rincian'];
        $data['rpcetak'] = $belcetak['pagu_rincian'];
        $data['rpsppd'] = $belsppd['pagu_rincian'];
        // $data['bOperasi'] = $this->subkegmodel->dataperbelanja($datauser['kd_sub_unit'], '5.1.');
        // $data['bPegawai'] = $this->subkegmodel->dataperbelanja($datauser['kd_sub_unit'], '5.1.01.');
        // $data['bBarjas'] = $this->subkegmodel->dataperbelanja($datauser['kd_sub_unit'], '5.1.02.');
        // $data['bModal'] = $this->subkegmodel->dataperbelanja($datauser['kd_sub_unit'], '5.2.');
        // $data['dataSKOpd'] = $this->realisasilrfkrinci->realSK($datauser['kd_sub_unit'], $tahunaktif, $data['bln']);
        $data['dataSKOpd'] = $this->realapbdmodel->rpersk($datauser['sub_unit'], $tahunaktif, $data['bln'], $tglaktif);
        $data['dataProgOpd'] = $this->realisasilrfkrinci->realSK($datauser['kd_sub_unit'], $tahunaktif, $data['bln']);
        $data['dataRSKBel'] = $this->realisasilrfkrinci->realSKPerBel($datauser['kd_sub_unit'], $tahunaktif, $data['bln'], '5.1.02.01');
        $jum = array_sum(array_column($data['dataRSKBel'], 'realisasi'));
        // echo dd($data['dataperRSatk']['anggaran'] . ' - ' . $data['dataperRSCetak']['anggaran'] . ' - ' . $data['dataperRSperjadin']['anggaran'] . ' - ' . $jum);
        // echo dd($tahunaktif . $datauser['sub_unit']);
        $data['dataROpdPerBln'] = $this->realapbdmodel->rperopdperbulan($datauser['kd_sub_unit'], $tahunaktif);
        // echo dd($datauser['sub_unit'] . ' - ' . $tahunaktif . ' - ' . $bulan . ' - ' . $tglaktif . ' - Perjalanan Dinas');
        // echo dd($data['dataSKOpd']);
        return view('lrfk/opd/dashboard', $data);
    }
}
