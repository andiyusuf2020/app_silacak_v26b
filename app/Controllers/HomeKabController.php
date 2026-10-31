<?php

namespace App\Controllers;

use App\Models\KabtanggamusModel\RealApbdModel;
use App\Models\KabtanggamusModel\TglApbdModel;
use App\Models\KabtanggamusModel\DataDashboardModel;
use App\Models\KabtanggamusModel\RealisasiPendapatanModel;


use App\Models\KablampuraModel\RealApbdModel as RealApbdModelLampura;
use App\Models\KablampuraModel\TglApbdModel as TglApbdModelLampura;
use App\Models\KablampuraModel\DataDashboardModel as DataDashboardModelLampura;
use App\Models\KablampuraModel\RealisasiPendapatanModel as RealisasiPendapatanModelLampura;


use App\Controllers\BaseController;

class HomeKabController extends BaseController
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
    }

    public function index()
    {
        $req = $this->request;
        $receivedParams = $req->getGet();
        $wilayah = $receivedParams['wilayah'] ?? null;
        if (!$receivedParams) {
            return view('index');
        }
        $this->ValidasiHash($req);
        if ($wilayah == 'provinsi') {
            session()->set('wilayah', 'provinsi');
            session()->set('namawilayah', 'Provinsi Lampung');
            return redirect()->to(base_url('provinsi'));
        }
        if ($wilayah == 'lambar') {
            return view('maintenis/soon');
            // session()->set('wilayah', 'lambar');
            // session()->set('namawilayah', 'Kabuapaten Lampung Barat');
            // return redirect()->to(base_url('lambar'));
        } elseif ($wilayah === 'lampungselatan') {
            // return view('maintenis/soon');
            session()->set('wilayah', 'lampungselatan');
            session()->set('namawilayah', 'Kabupaten Lampung Selatan');
            session()->set('logokabkota', 'cssportal/img_home/lamsel.png');
            session()->set('logobupati', 'cssportal/img_home/bupatilamsel.png');
            session()->set('logowakil', 'cssportal/img_home/wabuplamsel.png');
            return redirect()->to(base_url('lampungselatan'));
        } elseif ($wilayah === 'lampungtimur') {
            return view('maintenis/soon');
            // session()->set('wilayah', 'lampungtimur');
            // session()->set('namawilayah', 'Kabupaten Lampung Timur');
            // return redirect()->to(base_url('lampungtimur'));
        } elseif ($wilayah === 'lampungtengah') {
            return view('maintenis/soon');
            // session()->set('wilayah', 'lampungtengah');
            // session()->set('namawilayah', 'Kabupaten Lampung Tengah');
            // return redirect()->to(base_url('lampungtengah'));
        } elseif ($wilayah === 'lampungutara') {
            // return view('maintenis/soon');
            session()->set('wilayah', 'lampungutara');
            session()->set('namawilayah', 'Kabupaten Lampung Utara');
            session()->set('logokabkota', 'cssportal/img_home/lamut.png');
            session()->set('logobupati', 'cssportal/img_home/bupatilampura.png');
            session()->set('logowakil', 'cssportal/img_home/wabuplampura.png');

            return redirect()->to(base_url('lampungutara'));
        } elseif ($wilayah === 'mesuji') {
            return view('maintenis/soon');
            // session()->set('wilayah', 'mesuji');
            // session()->set('namawilayah', 'Kabupaten Mesuji');
            // return redirect()->to(base_url('mesuji'));
        } elseif ($wilayah === 'pesawaran') {
            return view('maintenis/soon');
            // session()->set('wilayah', 'pesawaran');
            // session()->set('namawilayah', 'Kabupaten Pesawaran');
            // return redirect()->to(base_url('pesawaran'));
        } elseif ($wilayah === 'pringsewu') {
            return view('maintenis/soon');
            // session()->set('wilayah', 'pringsewu');
            // session()->set('namawilayah', 'Kabupaten Pringsewu');
            // return redirect()->to(base_url('pringsewu'));
        } elseif ($wilayah === 'tanggamus') {
            session()->set('wilayah', 'tanggamus');
            session()->set('namawilayah', 'Kabupaten Tanggamus');
            session()->set('logokabkota', 'cssportal/img_home/tanggamus.png');
            session()->set('logobupati', 'cssportal/img_home/bupati_tanggamus.jpg');
            session()->set('logowakil', 'cssportal/img_home/wakil_bup_tanggamus.jpg');
            return redirect()->to(base_url('tanggamus'));
        } elseif ($wilayah === 'tulangbawang') {
            return view('maintenis/soon');
            // session()->set('wilayah', 'tulangbawang');
            // session()->set('namawilayah', 'Kabupaten Tulang Bawang');
            // return redirect()->to(base_url('tulangbawang'));

        } elseif ($wilayah === 'tulangbawangbarat') {
            // return view('maintenis/soon');
            session()->set('wilayah', 'tulangbawangbarat');
            session()->set('namawilayah', 'Kabupaten Tulang Bawang Barat');
            // return redirect()->to(base_url('tulangbawangbarat'));
            session()->set('logokabkota', 'cssportal/img_home/tbb.png');
            session()->set('logobupati', 'cssportal/img_home/bupatitubaba.png');
            session()->set('logowakil', 'cssportal/img_home/wabubtubaba.png');
            return redirect()->to(base_url('tulangbawangbarat'));
        } else {
            // Handle the case when the wilayah parameter is not recognized
            // You can choose to show an error page or redirect to a default page
            return view('index'); // Redirect to the index page as a fallback
        }
    }
    public function pilihakses()
    {
        $wilayah = session()->get('wilayah');
        if (!$wilayah) {
            return redirect()->to(base_url());
        }
        $data =
            [
                'wilayah' => $wilayah
            ];
        // echo dd($data);
        return view('Sipkabkota/pilihakses', $data);
    }
    public function simpantahun()
    {
        $tahun = $this->request->getPost('tahun');

        // simpan tahun di session
        session()->set('tahun', $tahun);
        return redirect()->back();
    }
    public function dilarang()
    {
        $data = [
            'title1' => 'Anda tidak memiliki hak akses untuk halaman ini',
            // 'title2' => 
        ];

        return view('hakakses', $data);
    }
}
