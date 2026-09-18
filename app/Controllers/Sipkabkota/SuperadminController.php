<?php

namespace App\Controllers\Sipkabkota;

use App\Controllers\BaseController;
use \Myth\Auth\Authorization\GroupModel;
use App\Models\DataApbdModel\RealApbdModel;

class SuperadminController extends BaseController
{
    protected $realapbdmodel;

    public function __construct()
    {
        helper(['form', 'url', 'filesystem']);
        $this->realapbdmodel = new RealApbdModel();
    }

    public function index()
    {
        // if (logged_in()) {
        $user = user();
        $groupModel = new GroupModel();
        $groupuser = $groupModel->getGroupsForUser($user->id);
        foreach ($groupuser as $row) {
            $namagroup = $row['name'];
        }
        // }
        $data['titlepage'] = 'Selamat Data di halaman Superadmin - SiTAPIS KABUPATEN/KOTA';
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
        return view('sipkabkota/superadmin/apexchart', $data);
    }
    public function dashboard()
    {
        $tgldata = session()->get('tglaktif');
        $data['dataopdadmin'] = $this->realapbdmodel->getCapaianKinerjaDenganGeometri($tgldata);

        return view('sipkabkota/dashboard', $data);
    }
    public function pilihakses()
    {
        return view('sipkabkota/pilihakses');
    }
}
