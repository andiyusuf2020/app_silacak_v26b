<?php

namespace App\Controllers\KablampuraController;

use \Myth\Auth\Authorization\GroupModel;
use App\Models\DataApbdModel\RealApbdModel;

use App\Models\DataApbdModel\TglApbdModel;

use App\Controllers\BaseController;

class AdminAdbangController extends BaseController
{
    protected $realapbdmodel;
    protected $tglapbdmodel;
    public function __construct()
    {
        helper(['form', 'url', 'filesystem']);
        $this->realapbdmodel = new RealApbdModel();
        $this->tglapbdmodel = new TglApbdModel();
    }

    public function index()
    {
        $req = $this->request;
        $receivedParams = $req->getGet();
        $wilayah = $receivedParams['wilayah'] ?? null;

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

                'tglaktif' => session()->get('tglaktif'),
            ];

        if (!$receivedParams) {
            // echo dd($data);
            if (!$data['tahun']) {
                session()->set('groupuser', $data['groupuser']);
                session()->set('groupmenu', $data['groupuser']);
                return view('pilihtahun', $data);
            }

            $tgldata = $this->tglapbdmodel->tgldataaktif();
            session()->set('tglaktif', $tgldata['tanggal']);
            $tahun = session()->get('tahun');
            $tgldata = session()->get('tglaktif');
            $data['dataopdadmin'] = $this->realapbdmodel->getCapaianKinerjaDenganGeometri($tahun, $tgldata);
            // echo dd($tahun . '-'  . $tgldata);
            // echo dd($data['dataopdadmin']);
            // echo dd($data);
            return view('Kablampuraviews/Adminadbang/index', $data);
        }
        $this->ValidasiHash($req);

        $wilayah = session()->get('wilayah');
        if (!$wilayah) {
            return redirect()->to(base_url());
        }
    }
}
