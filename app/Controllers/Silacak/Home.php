<?php

namespace App\Controllers\Silacak;

use App\Controllers\BaseController;
use App\Models\DataApbdModel\TglApbdModel;

class Home extends BaseController
{
    protected $tglapbdmodel;

    public function __construct()
    {
        helper(['form', 'url', 'filesystem']);
        $this->tglapbdmodel = new TglApbdModel();
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

        if ($groupmenu == 'userlrfkprov') {
            return redirect()->to(base_url('lrfkopd'));
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
        return view('silacak/home');
    }
    public function dashboard()
    {
        return view('silacak/dashboard');
    }
    public function pilihakses()
    {
        return view('silacak/pilihakses');
    }
}
