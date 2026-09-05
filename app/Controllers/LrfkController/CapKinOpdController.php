<?php

namespace App\Controllers\LrfkController;

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

class CapKinOpdController extends BaseController
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
    /// fungsiona kinerj opd

    public function kinerja()
    {
        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        $tahunaktif = $jadwalaktif['tahun'];
        $bulanaktif = $jadwalaktif['bulan'];
        $req = $this->request;

        $this->ValidasiHash($req);
        // Contoh penggunaan parameter
        $receivedParams = $req->getGet();
        $page = $receivedParams['page'] ?? null;
        $action = $receivedParams['action'] ?? null;
        $kdP = $receivedParams['kdP'] ?? null;
        $kdI = $receivedParams['kdI'] ?? null;
        $kdSK = $receivedParams['kdSK'] ?? null;
        $kdSU = $receivedParams['kdSU'] ?? null;
        $kdU = $receivedParams['kdU'] ?? null;


        $bulan = $receivedParams['bulan'] ?? null;

        $user = user();
        if (logged_in()) {
            $user = user();
            $groupModel = new GroupModel();
            $groupuser = $groupModel->getGroupsForUser($user->id);
            foreach ($groupuser as $row) {
                $namagroup = $row['name'];
            }
        }
        //$datauser = $this->tausermodel->listuser($user->id);
        $data = [
            'groupuser' => $namagroup,
            'titlepage' => 'Selamat Data di e-TAPIS Laporan Indikator Kinerja Program Kegiatan Perangkat Daerah Provinsi Lampung',
            'datauser' => $this->tausermodel->listuser($user->id),
            'jadwalaktif' => $jadwalaktif,
        ];

        $data['listopd'] = $this->subkegmodel->listopd();

        $data['subkeg'] = $this->subkegmodel->subkeg($kdSK, $kdSU);
        $datauser = $this->tausermodel->listuser($user->id);

        $dataopd =  $this->subkegmodel->listopd($datauser['sub_unit']);

        if ($page == 'kinerja') {
            if ($action == 'list') {
                return view('lrfk/opd/v_kinerjaopd', $data);
            }
            if ($action == 'iku') {
                $data['listprogram'] = $this->subkegmodel->programopd($dataopd['kd_sub_unit']);
                return view('lrfk/opd/v_ikuprogramopd', $data);
            }
            if ($action == 'input') {
                $data['listprogram'] = $this->subkegmodel->perprogramopd($kdSU, $kdP);
                $data['listIndikator'] = $this->taindikator->listPerIndi($kdI);

                //echo dd($data['listprogram']);
                return view('lrfk/opd/form_ikuprogram', $data);
            }
            if ($action == 'ubah') {
                $data['listprogram'] = $this->subkegmodel->perprogramopd($kdSU, $kdP);

                $data['listIndikator'] = $this->taindikator->listPerIndi($kdI);
                // echo dd($data['listIndikator']);
                return view('lrfk/opd/form_ikuprogram', $data);
            }
            if ($action == 'hapus') {
                $this->taindikator->delete($kdI);
                return redirect()->to('lrfkopd')->withInput()->with('message', 'Hapus data berhasil');
            }
        }
        if ($page == 'output') {
            if ($action == 'indikator') {
                $data['listsubkegiatan'] = $this->subkegmodel->subkegiatanperopd($dataopd['kd_sub_unit']);
                //                echo dd($data['listsubkegiatan']);
                return view('lrfk/opd/v_indiSubKegopd', $data);
            }
            if ($action == 'input') {
                $data['listsubkegiatan'] = $this->subkegmodel->subkeg($kdSK, $kdSU);
                $data['listIndikatorSK'] = $this->taindisubkeg->listPerIndi($kdI);

                // echo dd($data['listsubkegiatan']);
                return view('lrfk/opd/form_indiSubKeg', $data);
            }
            if ($action == 'ubah') {
                $data['listsubkegiatan'] = $this->subkegmodel->subkeg($kdSK, $kdSU);
                $data['listIndikatorSK'] = $this->taindisubkeg->listPerIndi($kdI);
                return view('lrfk/opd/form_indiSubKeg', $data);
            }
            if ($action == 'hapus') {

                //echo dd($kdI);
                $this->taindisubkeg->delete($kdI);
                return redirect()->back()->withInput()->with('message', 'Hapus data berhasil');
            }
        }
    }
    public function saveindikator()
    {
        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        $tahunaktif = $jadwalaktif['tahun'];
        $bulanaktif = $jadwalaktif['bulan'];
        // Validasi Input
        $validation = \Config\Services::validation();
        $validation->setRules(
            [
                'indikator' => 'required|min_length[5]|max_length[255]',
                'uraian' => 'required|regex_match[/target/i]',
            ],
            ['indikator' => [
                'required' => 'Pagu Realisasi Wajib di Isi',
                'min_length' => 'Pagu Realisasi minimal 5 karakter.',
                'max_length' => 'Pagu Realisasi maksimal 17 angka.',
            ]],
            ['uraian' => [
                'required' => 'Uraian Realisasi/Pelaksanaan Subkegiatan  wajib diisi.',
                'regex_match' => 'Uraian Realisasi/Pelasksanaan Subkegiatan harus mengandung kata target dan realisasi',
            ]],

        );

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $idindi = $this->request->getPost('id') ?? null;
        if ($idindi == null) {
            $data = [
                'tahun' => $tahunaktif,
                'kd_sub_unit' => $this->request->getPost('kd_sub_unit'),
                'sub_unit' => $this->request->getPost('sub_unit'),
                'kd_program' => $this->request->getPost('kd_program'),
                'nm_program' => $this->request->getPost('nm_program'),
                'indikator' => $this->request->getPost('indikator'),
                'uraian' => $this->request->getPost('uraian')
            ];
        } else {
            $data = [
                'id' => $idindi,
                'tahun' => $tahunaktif,
                'kd_sub_unit' => $this->request->getPost('kd_sub_unit'),
                'sub_unit' => $this->request->getPost('sub_unit'),
                'kd_program' => $this->request->getPost('kd_program'),
                'nm_program' => $this->request->getPost('nm_program'),
                'indikator' => $this->request->getPost('indikator'),
                'uraian' => $this->request->getPost('uraian')
            ];
        }
        // echo dd($data);
        $this->taindikator->save($data);
        return redirect()->to('lrfkopd')->withInput()->with('message', 'penyimpanan data berhasil');
        //return redirect()->to(base_url('lrfkopd/subkegiatan'))->withInput()->with('message', 'penyimpanan data berhasil');
    }

    public function saveindisk()
    {
        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        $tahunaktif = $jadwalaktif['tahun'];
        $bulanaktif = $jadwalaktif['bulan'];
        // Validasi Input
        $validation = \Config\Services::validation();
        $validation->setRules(
            [
                'vol_indi' => 'required|numeric|min_length[1]|max_length[5]',
                'satuan_indi' => 'required|min_length[3]|max_length[25]',
                'uraian' => 'required|regex_match[/target/i]',
            ],
            ['vol_indi' => [
                'required' => 'Volume Indikator Wajib di Isi',
                'numeric' => 'Volume Indikator Wajib Angka',
                'min_length' => 'Volume Indikator minimal 1 angka.',
                'max_length' => 'Volume Indikator maksimal 5 angka.',
            ]],
            ['satuan_indi' => [
                'required' => 'Satuan Indikator Wajib di Isi',
                'min_length' => 'Satuan Indikator minimal 3 karakter.',
                'max_length' => 'Satuan Indikator maksimal 25 karakter.',
            ]],
            ['uraian' => [
                'required' => 'Uraian Realisasi/Pelaksanaan Subkegiatan  wajib diisi.',
                'regex_match' => 'Uraian Realisasi/Pelasksanaan Subkegiatan harus mengandung kata target dan realisasi',
            ]],

        );

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $idindi = $this->request->getPost('id') ?? null;
        if ($idindi == null) {
            $data = [
                'tahun' => $tahunaktif,
                'kd_sub_unit' => $this->request->getPost('kd_sub_unit'),
                'sub_unit' => $this->request->getPost('sub_unit'),
                'kd_subkegiatan' => $this->request->getPost('kd_subkegiatan'),
                'nm_subkegiatan' => $this->request->getPost('nm_subkegiatan'),
                'satuan_indi' => $this->request->getPost('satuan_indi'),
                'vol_indi' => $this->request->getPost('vol_indi'),
                'uraian' => $this->request->getPost('uraian')
            ];
        } else {
            $data = [
                'id' => $idindi,
                'tahun' => $tahunaktif,
                'kd_sub_unit' => $this->request->getPost('kd_sub_unit'),
                'sub_unit' => $this->request->getPost('sub_unit'),
                'kd_subkegiatan' => $this->request->getPost('kd_subkegiatan'),
                'nm_subkegiatan' => $this->request->getPost('nm_subkegiatan'),
                'satuan_indi' => $this->request->getPost('satuan_indi'),
                'vol_indi' => $this->request->getPost('vol_indi'),
                'uraian' => $this->request->getPost('uraian')
            ];
        }
        //echo dd($data);
        $this->taindisubkeg->save($data);
        return redirect()->back()->withInput()->with('message', 'penyimpanan data berhasil');
        //return redirect()->to(base_url('lrfkopd/subkegiatan'))->withInput()->with('message', 'penyimpanan data berhasil');

    }
}
