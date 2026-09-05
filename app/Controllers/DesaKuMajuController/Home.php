<?php

namespace App\Controllers\DesaKuMajuController;

use CodeIgniter\Controller;
use App\Models\LokasiModel;
use App\Models\EksekutifModel\TaApbdSkpdModel;
use App\Models\LrfkProvModel\SubKegModel; // as lrfkModel;


class Home extends Controller
{
    protected $lokasiModel;
    protected $apbdopdmodel;
    protected $subkegmodel;

    protected $helpers = ['form'];

    public function __construct()
    {
        $this->lokasiModel = new LokasiModel();
        $this->apbdopdmodel = new TaApbdSkpdModel();
        $this->subkegmodel = new SubKegModel();
    }
    public function index()
    {
        return view('desakumaju/index');
    }
}
