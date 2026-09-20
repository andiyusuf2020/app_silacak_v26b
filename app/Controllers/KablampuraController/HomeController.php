<?php

namespace App\Controllers\KablampuraController;

use App\Models\DataApbdModel\RealApbdModel;
use App\Models\DataApbdModel\TglApbdModel;

use App\Controllers\BaseController;

class HomeController extends BaseController
{
    protected $realapbdmodel;
    protected $tglapbdmodel;
    public function __construct()
    {
        helper(['form', 'url', 'filesystem']);
        $this->realapbdmodel = new RealApbdModel();
        $this->tglapbdmodel = new TglApbdModel();
    }

    public function dashboard()
    {
        // session()->set('tahun', date('Y'));

        $tgldata = $this->tglapbdmodel->tgldataaktif();
        session()->set('tglaktif', $tgldata['tanggal']);
        $tahun = date('Y');
        $tgldata = session()->get('tglaktif');
        $data['dataopdadmin'] = $this->realapbdmodel->getCapaianKinerjaDenganGeometri($tahun, $tgldata);
        // echo dd($tahun . '-'  . $tgldata);
        // echo dd($data['dataopdadmin']);
        return view('Kablampuraviews/dashboard', $data);
    }
}
