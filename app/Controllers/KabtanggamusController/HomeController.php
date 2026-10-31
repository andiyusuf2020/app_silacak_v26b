<?php

namespace App\Controllers\KabtanggamusController;

use App\Models\KabtanggamusModel\RealApbdModel;
use App\Models\KabtanggamusModel\TglApbdModel;
use App\Models\KabtanggamusModel\DataDashboardModel;
use App\Models\KabtanggamusModel\RealisasiPendapatanModel;


use App\Models\KablampuraModel\RealApbdModel as RealApbdModelLampura;
use App\Models\KablampuraModel\TglApbdModel as TglApbdModelLampura;
use App\Models\KablampuraModel\DataDashboardModel as DataDashboardModelLampura;
use App\Models\KablampuraModel\RealisasiPendapatanModel as RealisasiPendapatanModelLampura;

use App\Models\KablamselModel\RealApbdModel as RealApbdModelLamsel;
use App\Models\KablamselModel\TglApbdModel as TglApbdModelLamsel;
use App\Models\KablamselModel\DataDashboardModel as DataDashboardModelLamsel;
use App\Models\KablamselModel\RealisasiPendapatanModel as RealisasiPendapatanModelLamsel;

use App\Models\KabtbbModel\RealApbdModel as RealApbdModelTbb;
use App\Models\KabtbbModel\TglApbdModel as TglApbdModelTbb;
use App\Models\KabtbbModel\DataDashboardModel as DataDashboardModelTbb;
use App\Models\KabtbbModel\RealisasiPendapatanModel as RealisasiPendapatanModelTbb;

use App\Controllers\BaseController;
use App\Controllers\HomeKabController;

class HomeController extends BaseController
{
    protected $realapbdmodel;
    protected $dataDashboardModel;
    protected $tglapbdmodel;
    protected $realisasiPendapatanModel;
    public function __construct()
    {
        helper(['form', 'url', 'filesystem']);
        if (session()->get('wilayah') === 'lampungutara') {
            $this->realapbdmodel = new RealApbdModelLampura();
            $this->dataDashboardModel = new DataDashboardModelLampura();
            $this->tglapbdmodel = new TglApbdModelLampura();
            $this->realisasiPendapatanModel = new RealisasiPendapatanModelLampura();
        }
        if (session()->get('wilayah') === 'tanggamus') {
            $this->realapbdmodel = new RealApbdModel();
            $this->dataDashboardModel = new DataDashboardModel();
            $this->tglapbdmodel = new TglApbdModel();
            $this->realisasiPendapatanModel = new RealisasiPendapatanModel();
        }
        if (session()->get('wilayah') === 'lampungselatan') {
            $this->realapbdmodel = new RealApbdModelLamsel();
            $this->dataDashboardModel = new DataDashboardModelLamsel();
            $this->tglapbdmodel = new TglApbdModelLamsel();
            $this->realisasiPendapatanModel = new RealisasiPendapatanModelLamsel();
        }
        if (session()->get('wilayah') === 'tulangbawangbarat') {
            $this->realapbdmodel = new RealApbdModelTbb();
            $this->dataDashboardModel = new DataDashboardModelTbb();
            $this->tglapbdmodel = new TglApbdModelTbb();
            $this->realisasiPendapatanModel = new RealisasiPendapatanModelTbb();
        }
    }

    public function dashboard()
    {
        // session()->set('tahun', date('Y'));
        $tgldata = $this->tglapbdmodel->tgldataaktif();
        if ($tgldata == null) {
            $tgldata = [
                'tahun' => date('Y'),
                'tanggal' => date('Y-m-d')
            ];
        }
        // echo dd($tgldata);
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
        $data['logokabkota'] = session()->get('logokabkota');
        $data['logobupati'] = session()->get('logobupati');
        $data['logowakil'] = session()->get('logowakil');
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
        $data['namawilayah'] = session()->get('namawilayah');        // return view('realisasi/data_list', [

        return view('KabtanggamusViews/dashboard', $data);
    }
}
