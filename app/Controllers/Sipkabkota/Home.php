<?php

namespace App\Controllers\Sipkabkota;

use App\Controllers\BaseController;
use App\Models\DataApbdModel\TglApbdModel;
use App\Models\DataApbdModel\RealApbdModel;

class Home extends BaseController
{
    protected $tglapbdmodel;
    protected $realapbdmodel;
    public function __construct()
    {
        helper(['form', 'url', 'filesystem']);
        $this->tglapbdmodel = new TglApbdModel();
        $this->realapbdmodel = new RealApbdModel();
    }
    public function simpantahunsilacak()
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
        // echo dd($tahun . ' - ' . session()->get('tglaktif') . ' - ' . $groupmenu);
        if ($groupmenu == 'superadmin') {
            return redirect()->to(base_url('superadmin'));
        }
        // if ($groupmenu == 'usercapkinprov') {
        //     return redirect()->to(base_url('capkin'));
        // }
        // if ($groupmenu == 'adminprov') {
        //     return redirect()->to(base_url('lrfkadmin'));
        // }
        // if ($groupmenu == 'userdesakumaju') {
        //     return redirect()->to(base_url('desakumaju/opd'));
        // }
    }
    public function dilarang()
    {
        return view('hakakses');
    }

    public function index()
    {
        session()->set('tahun', date('Y'));

        $tgldata = $this->tglapbdmodel->tgldataaktif();
        session()->set('tglaktif', $tgldata['tanggal']);
        $tahun = session()->get('tahun');
        $tgldata = session()->get('tglaktif');
        $data['dataopdadmin'] = $this->realapbdmodel->getCapaianKinerjaDenganGeometri($tgldata);
        // echo dd($tahun . '-'  . $tgldata);
        // echo dd($data['dataopdadmin']);
        return view('Sipkabkota/dashboard2', $data);
    }
    public function dashboard()
    {
        return view('Sipkabkota/dashboard');
    }
    public function pilihakses()
    {
        return view('Sipkabkota/pilihakses');
    }
}
