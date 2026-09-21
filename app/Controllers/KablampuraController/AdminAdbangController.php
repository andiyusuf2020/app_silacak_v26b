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
        $hal = $receivedParams['hal'] ?? null;
        $action = $receivedParams['action'] ?? null;
        $kdSU = $receivedParams['kdSU'] ?? null;
        $blndata = $receivedParams['blndata'] ?? null;
        $kdSK = $receivedParams['kdSK'] ?? null;
        $tgldataopd = $receivedParams['tgldataopd'] ?? null;
        $tgldata = $receivedParams['tgldata'] ?? null;

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
                'nama_opd' => $kdSU,
                'blndata' => $blndata,
                'kdSK' => $kdSK,
                'tgldataopd' => $tgldataopd,
                'tglapbd' => $this->realapbdmodel->tgldata(),
                'tglapbdaktif' => $this->tglapbdmodel->tgldataaktif()
            ];
        // $data['tglapbd'] = $this->realapbdmodel->tgldata();
        // $data['tglapbdaktif'] = $this->tglapbdmodel->tgldataaktif();


        if (!$receivedParams) {
            // echo dd($data);
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
            return view('Kablampuraviews/Adminadbang/index', $data);
        }
        $wilayah = session()->get('wilayah');
        if (!$wilayah) {
            return redirect()->to(base_url());
        }
        $this->ValidasiHash($req);
        if ($hal == 'apbdopd') {
            return view('Kablampuraviews/Adminadbang/listtgldata', $data);

            // $data['dataopdadmin'] = $this->realapbdmodel->getCapaianKinerjaDenganGeometri($tgldata);
            // return view('Kablampuraviews/Adminadbang/apbdopd', $data);
        } elseif ($hal == 'angkasopd') {
            $data['dataopdadmin'] = $this->realapbdmodel->getCapaianKinerjaDenganGeometri($data['tahun'], $data['tglaktif']);
            return view('Kablampuraviews/Adminadbang/angkasopd', $data);
        } elseif ($hal == 'datapbj') {
            $data['dataopdadmin'] = $this->realapbdmodel->getCapaianKinerjaDenganGeometri($data['tahun'], $data['tglaktif']);
            return view('Kablampuraviews/Adminadbang/datapbj', $data);
        }
        if ($tgldata) {
            $data['dataopdadmin'] = $this->realapbdmodel->getCapaianKinerjaDenganGeometri($data['tahun'], $tgldata);
            return view('Kablampuraviews/Adminadbang/listopdapbd', $data);
        }
        // $data['tgldata'] = $tgldata;
        // $data['dataopdadmin'] = $this->realapbdmodel->listopdadmin($tgldata);


        if ($hal == 'detail') {
            if ($action == 'opd') {
                $data['dataopd'] = $this->realapbdmodel->rpersk($kdSU, $data['tahun'], $blndata, $tgldataopd);
                // echo dd($kdSU . '-' . $tahunaktif . '-' . $bulanaktif . '-' . $tglaktif);
                // echo dd($data['dataopd']);
                return view('Kablampuraviews/Adminadbang/detailopdapbd', $data);
            }
            if ($action == 'detailopd') {
                $data['dataopd'] = $this->realapbdmodel->rperrso2($kdSU, $kdSK, $data['tahun'], $tgldataopd)->getResultArray();

                // echo dd($kdSU . '-' . $kdSK . '-' . $tahunaktif . '-' . $tglaktif);
                // echo dd($data['dataopd']);
                return view('superadmin/2026/detailopdsro', $data);
            }
        }
    }
}
