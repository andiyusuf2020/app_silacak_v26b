<?php

namespace App\Controllers\LrfkController;

use App\Models\LrfkProvModel\JadwalModel as lrfkModel;
use App\Models\UserModel\TaUserModel;
use \Myth\Auth\Authorization\GroupModel;

use App\Models\DataApbdModel\RealApbdModel;
use App\Models\DataApbdModel\TglApbdModel;
use App\Controllers\BaseController;

class AdminLrfkController extends BaseController
{

    protected $lrfkmodel;
    protected $misimodel;
    protected $tausermodel;
    protected $realapbdmodel;
    protected $tglapbdmodel;
    public function __construct()
    {
        helper(['form']);
        $this->lrfkmodel = new lrfkModel();
        $this->tausermodel = new TaUserModel();
        $this->realapbdmodel = new RealApbdModel();
        $this->tglapbdmodel = new TglApbdModel();
    }
    function saveCurrentUrl()
    {
        // Jangan simpan URL jika berasal dari form submit
        if ($this->request->getMethod() === 'get') {
            session()->set('previous_url', current_url());
        }
    }
    public function index()
    {
        $user = user();
        if (logged_in()) {
            $user = user();
            $groupModel = new GroupModel();
            $groupuser = $groupModel->getGroupsForUser($user->id);
            foreach ($groupuser as $row) {
                $namagroup = $row['name'];
            }
        }
        $tahunaktif =  session()->get('tahun'); //   $jadwalaktif['tahun'];
        if (!$tahunaktif) {
            return redirect()->to(base_url('lrfkopd'))->withInput()->with('message', 'Tahun aktif belum dipilih, silahkan pilih tahun aktif terlebih dahulu');
        }
        $data = [
            'groupuser' => $namagroup,
            'datauser' => $this->tausermodel->listuser($user->id),
            'titlepage' => 'Halaman Aktifasi Jadwal Inputa Data LRFK Perangkat Daerah',
            'groupmenu' => $namagroup,
            'tahunaktif' => $tahunaktif
        ];

        $data['jadwalaktif'] = $this->lrfkmodel->jadwalaktifskrg();
        //tanggal data pada tabel apbd
        $data['tglapbd'] = $this->realapbdmodel->tgldata();
        $data['tglapbdaktif'] = $this->tglapbdmodel->tgldataaktif();
        // echo dd($data['tglapbd']);
        return view('lrfk/admin/v_jadwal', $data);
    }
    public function jadwal()
    {
        $user = user();
        if (logged_in()) {
            $user = user();
            $groupModel = new GroupModel();
            $groupuser = $groupModel->getGroupsForUser($user->id);
            foreach ($groupuser as $row) {
                $namagroup = $row['name'];
            }
        }
        $tahunaktif =  session()->get('tahun'); //   $jadwalaktif['tahun'];
        $data = [
            'groupuser' => $namagroup,
            'datauser' => $this->tausermodel->listuser($user->id),
            'titlepage' => 'Halaman Aktifasi Jadwal Inputa Data LRFK Perangkat Daerah',
            'groupmenu' => $namagroup,
            'tahunaktif' => $tahunaktif
        ];

        $data['jadwalaktif'] = $this->lrfkmodel->jadwalaktifskrg();
        $data['jadwal'] = $this->lrfkmodel->jadwal();


        $req = $this->request;
        $this->ValidasiHash($req);
        $receivedParams = $req->getGet();
        $action = $receivedParams['action'] ?? null;
        $idUbah = $receivedParams['idUbah'] ?? null;
        $tglpilih = $receivedParams['tglpilih'] ?? null;

        if ($action == 'aktifasitgl') {
            $updateAktif = $this->tglapbdmodel->updatetgl(1, $tahunaktif, $tglpilih);

            if (!$updateAktif) {
                session()->setFlashdata('message', 'Tanggal Data APBD berhasil diaktifkan');
            }
            // return redirect()->to(base_url('lrfkadmin/jadwal')->withInput()->with('message', 'Tanggal Data APBD berhasil diaktifkan'));
            return redirect()->to(base_url('lrfkadmin/jadwal'));
        }

        if ($action == 'ganti') {
            $this->saveCurrentUrl();
            return view('lrfk/admin/v_listjadwal', $data);
            //echo dd($data['jadwal']);
        }
        if ($action == 'edit') {
            $idAwal = $data['jadwalaktif']['id'] ?? null;
            $tahun = $data['jadwalaktif']['tahun'] ?? null;
            $bulan = $data['jadwalaktif']['bulan'] ?? null;
            //   echo $idUbah;
            $nonaktifbln = [
                'id' => $idAwal,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'status' => '0'
            ];
            $dataubah = $this->lrfkmodel->jdwlygdiaktifkan($idUbah);
            $aktifbln = [
                'id' => $idUbah,
                'tahun' => $dataubah['tahun'],
                'bulan' => $dataubah['bulan'],
                'status' => '1'
            ];
            $ubahygaktf = $this->lrfkmodel->save($aktifbln);
            $ubahygaktf = $this->lrfkmodel->save($nonaktifbln);
            return redirect()->to(base_url('lrfkadmin/jadwal'));
        }
    }
}
