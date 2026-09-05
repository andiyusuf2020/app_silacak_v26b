<?php

namespace App\Controllers\PdnController;

use App\Models\LrfkProvModel\JadwalModel;
use App\Models\UserModel\TaUserModel;
use App\Models\LrfkProvModel\SubKegModel; // as lrfkModel;
use \Myth\Auth\Authorization\GroupModel;
use App\Models\LrfkProvModel\RealisasiSubKegModel;
use App\Models\LrfkProvModel\TotalRealisasiModel;
use App\Models\LrfkProvModel\TaIndikatorProgModal;
use App\Models\LrfkProvModel\TaIndikatorSubKegModal;
use App\Models\LrfkProvModel\TaRealisasiRinciModel;

use App\Libraries\PdfLibrary;
use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;
use FontLib\Table\Type\post;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;
use TCPDF;

class p3dnController extends BaseController
{

    protected $jadwalmodel;
    protected $subkegmodel;
    protected $tausermodel;
    protected $realisasilrfk;
    protected $realisasilrfkrinci;

    protected $totalrealisasiM;
    protected $tcpdfConfig;
    protected $taindikator;
    protected $taindisubkeg;
    protected $db;
    public function __construct()
    {
        // helper(['form']);
        $this->tausermodel = new TaUserModel();
        $this->jadwalmodel = new JadwalModel();
        $this->subkegmodel = new SubKegModel();
        $this->realisasilrfk = new RealisasiSubKegModel();
        $this->totalrealisasiM = new TotalRealisasiModel();
        $this->taindikator = new TaIndikatorProgModal();
        $this->taindisubkeg = new TaIndikatorSubKegModal();
        $this->realisasilrfkrinci = new TaRealisasiRinciModel();
        $this->tcpdfConfig = new \Config\Tcpdf();
        helper(['form', 'url', 'filesystem']);
        $this->db = db_connect();
    }

    public function index()
    {
        if (logged_in()) {
            $user = user();
            $groupModel = new GroupModel();
            $groupuser = $groupModel->getGroupsForUser($user->id);
            foreach ($groupuser as $row) {
                $namagroup = $row['name'];
            }
        }
        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        $tahunaktif = session()->get('tahun'); //   $jadwalaktif['tahun'];

        $data = [
            'groupuser' => $namagroup,
            'groupmenu' => 'userlrfkprov',
            'titlepage' => 'Selamat Datang di e-TAPIS Laporan P3DN Perangkat Daerah Provinsi Lampung',
            'datauser' => $this->tausermodel->listuser($user->id),
            'jadwalaktif' => $jadwalaktif,
            'tahun'  => $tahunaktif,
        ];

        echo dd($data);
    }
}
