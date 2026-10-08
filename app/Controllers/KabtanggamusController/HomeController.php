<?php

namespace App\Controllers\KabtanggamusController;

use App\Models\KabtanggamusModel\RealApbdModel;
use App\Models\KabtanggamusModel\TglApbdModel;
use App\Models\KabtanggamusModel\DataDashboardModel;
use App\Models\KabtanggamusModel\RealisasiPendapatanModel;

use App\Controllers\BaseController;

class HomeController extends BaseController
{
    protected $realapbdmodel;
    protected $dataDashboardModel;
    protected $tglapbdmodel;
    protected $realisasiPendapatanModel;
    public function __construct()
    {
        helper(['form', 'url', 'filesystem']);
        $this->realapbdmodel = new RealApbdModel();
        $this->dataDashboardModel = new DataDashboardModel();
        $this->tglapbdmodel = new TglApbdModel();
        $this->realisasiPendapatanModel = new RealisasiPendapatanModel();
    }

    public function dashboard()
    {
        // session()->set('tahun', date('Y'));

        $tgldata = $this->tglapbdmodel->tgldataaktif();
        session()->set('tglaktif', $tgldata['tanggal']);
        $tahun = date('Y');
        $tgldata = session()->get('tglaktif');
        $data['dataopdadmin'] = $this->realapbdmodel->getCapaianKinerjaDenganGeometri($tahun, $tgldata);
        $data['datadashboard'] = $this->dataDashboardModel->first();

        // echo dd($tahun . '-'  . $tgldata);
        // echo dd($data['datadashboard']);
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

        return view('KabtanggamusViews/dashboard', $data);
    }
}
