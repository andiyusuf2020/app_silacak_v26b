<?php

namespace App\Controllers\DesaKuMajuController;

use App\Models\LrfkProvModel\JadwalModel;
use App\Models\UserModel\TaUserModel;
use \Myth\Auth\Authorization\GroupModel;

// use App\Models\CapkinModel\TaKegPokokCapkinModel;
// use App\Models\CapkinModel\TaMProgPrioritasModel;
// use App\Models\CapkinModel\TaRealisasiKegPokokModel;
// use App\Models\CapkinModel\TaRKegPokokCapkinModel;
// use App\Models\CapkinModel\TaMOpdProgUnggulan;
// use App\Models\CapkinModel\TaProgUnggulan;
// use App\Models\CapkinModel\TaMProgTematikModel;
// use App\Models\CapkinModel\TaMOpdTematikModel;
// use App\Models\CapkinModel\TaJadwalTwModel;


use App\Models\EksekutifModel\TaTtdLaporanModel;


use App\Models\CapkinModel\TaKabModel;
use App\Models\CapkinModel\TaKecamatanModel;
use App\Models\CapkinModel\TaDesaModel;

use App\Models\CapkinModel\TaKategoriModel;

use App\Models\LokasiModel;


use App\Libraries\PdfLibrary;
use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;
use FontLib\Table\Type\post;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;
use TCPDF;

//2026
use App\Models\DataApbdModel\RealApbdModel;
use App\Models\RupModel\SirupModel;
use App\Models\RupModel\RealRupModel;
use App\Models\DataApbdModel\PendApbdModel;
use App\Models\DataApbdModel\RealPendApbdModel;
use App\Models\DataApbdModel\AngkasModel;
use App\Models\CapkinModel\TaSubKegCapkin2026;
use App\Models\CapkinModel\TaPermasalahanAktifitas;
use App\Models\CapkinModel\TaNomenklaturModel;

use App\Models\DesakumajuModel\TaKeterlibatanOpdModel;


class OpdController extends BaseController
{

    protected $jadwalmodel;
    // protected $subkegmodel;
    protected $tausermodel;
    // protected $realisasilrfk;
    // protected $realisasilrfkrinci;

    // protected $targetsubkegmodel;
    // protected $kegpokokmodal;
    // protected $progprioritas;

    // protected $twmodel;

    // protected $rkegpokokmodal;
    // protected $rdkegpokokmodal;
    // protected $opdprogunggulan;
    // protected $progunggulan;
    // protected $progtematik;
    // protected $opdprogtematik;

    protected $kabmodel;
    protected $kecmodel;
    protected $desamodel;

    // protected $kategorimodel;


    // protected $lokasiModel;
    protected $ttdmodel;

    // protected $totalrealisasiM;
    protected $tcpdfConfig;
    // protected $taindikator;
    // protected $taindisubkeg;
    protected $db;

    //2026
    protected $realapbdmodel;
    protected $sirupmodel;
    protected $realrupmodel;
    protected $pendapatanmodel;
    protected $realpendapatanmodel;
    protected $angkasmodel;
    protected $subkegcapkin2026model;
    protected $permasalahanaktifitasmodel;
    protected $tanomenklaturmodel;


    protected $keterlibatanopdmodel;

    public function __construct()
    {
        // helper(['form']);
        $this->tausermodel = new TaUserModel();
        $this->jadwalmodel = new JadwalModel();
        // $this->subkegmodel = new SubKegModel();
        // $this->realisasilrfk = new RealisasiSubKegModel();
        // $this->totalrealisasiM = new TotalRealisasiModel();
        // $this->taindikator = new TaIndikatorProgModal();
        // $this->taindisubkeg = new TaIndikatorSubKegModal();
        // $this->realisasilrfkrinci = new TaRealisasiRinciModel();

        // $this->targetsubkegmodel = new TaSubKegCapkinModel();
        // $this->kegpokokmodal = new TaKegPokokCapkinModel();
        // $this->progprioritas = new TaMProgPrioritasModel();
        $this->ttdmodel = new TaTtdLaporanModel();

        // $this->twmodel = new TaJadwalTwModel();

        // $this->rkegpokokmodal = new TaRealisasiKegPokokModel();
        // $this->rdkegpokokmodal = new TaRKegPokokCapkinModel();
        // $this->opdprogunggulan = new TaMOpdProgUnggulan();
        // $this->progunggulan = new TaProgUnggulan();
        // $this->progtematik = new TaMProgTematikModel();
        // $this->opdprogtematik = new TaMOpdTematikModel();


        $this->kabmodel = new TaKabModel();
        $this->kecmodel = new TaKecamatanModel();
        $this->desamodel = new TaDesaModel();

        // $this->kategorimodel = new TaKategoriModel();


        // $this->lokasiModel = new LokasiModel();

        $this->tcpdfConfig = new \Config\Tcpdf();
        helper(['form', 'url', 'filesystem']);
        $this->db = db_connect();

        //2026
        $this->realapbdmodel = new RealApbdModel();
        $this->sirupmodel = new SirupModel();
        $this->realrupmodel = new RealRupModel();
        $this->pendapatanmodel = new PendApbdModel();
        $this->realpendapatanmodel = new RealPendApbdModel();
        $this->angkasmodel = new AngkasModel();
        $this->subkegcapkin2026model = new TaSubKegCapkin2026();
        $this->permasalahanaktifitasmodel = new TaPermasalahanAktifitas();
        $this->tanomenklaturmodel = new TaNomenklaturModel();

        $this->keterlibatanopdmodel = new TaKeterlibatanOpdModel();
    }

    function namagroupuser($id)
    {
        // $user = user();
        $groupModel = new GroupModel();
        $groupuser = $groupModel->getGroupsForUser($id);
        foreach ($groupuser as $row) {
            $namagroup = $row['name'];
        }
        return $namagroup;
    }
    public function index()
    {
        $user = user();
        $namagroup = $this->namagroupuser($user->id);
        $data = [
            'tahunaktif' => session()->get('tahun'), //   $jadwalaktif['tahun'];
            'groupmenu' => 'userdesakumaju',
            'nama_subunit' => $user->sub_unit,
            'titlepage' => 'Selamat Data di e-TAPIS Laporan Pelaksanaan Program Desaku Maju Provinsi Lampung',
            'groupuser' => $namagroup
        ];
        if (!$data['tahunaktif']) {
            session()->set('groupuser', $namagroup);
            session()->set('groupmenu', $data['groupmenu']);
            return view('pilihtahun', $data);
        }
        // echo dd($user->sub_unit);
        return view('desakumaju/opd/home', $data);
    }
    public function data()
    {

        $req = $this->request;
        // Contoh penggunaan parameter
        $this->ValidasiHash($req);

        $receivedParams = $req->getGet();
        $hal = $receivedParams['hal'] ?? null;
        $action = $receivedParams['action'] ?? null;

        $user = user();
        $tahunaktif =  session()->get('tahun'); //   $jadwalaktif['tahun'];
        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        $tglaktif = session()->get('tglaktif');
        $groupname = session()->get('groupuser');

        $data = [
            'groupuser' => $groupname,
            'groupmenu' => 'userdesakumaju',
            'nama_subunit' => $user->sub_unit,
            'titlepage' => 'Selamat Data di e-TAPIS Laporan Pelaksanaan Program Desaku Maju Provinsi Lampung',
            'datauser' => $this->tausermodel->listuser($user->id),
            'tahunaktif' => session()->get('tahun'),
            'tglaktif' => session()->get('tglaktif'),
            'bulanaktif' => $jadwalaktif['bulan'],
        ];
        if (!$tahunaktif) {
            session()->set('groupuser', $groupname);
            session()->set('groupmenu', 'usercapkinprov');
            return view('pilihtahun', $data);
        }
        // echo dd($data);
        if (!$receivedParams) {
            echo "Tidak ada parameter yang diterima.";
        }
        if ($hal === 'rencana' && $action === 'mappingaktifitas') {
            $data['titlepage'] = 'Rencana Aktifitas Program Desaku Maju';
            return view('desakumaju/opd/rencanaaktifitas', $data);
        } elseif ($hal === 'realisasi' && $action === 'hasilmappingaktifitas') {
            return view('desakumaju/opd/realisasiaktifitas', $data);
        } elseif ($hal === 'laporan' && $action === 'all') {
            return view('desakumaju/opd/cetaklaporan', $data);
        } else {
            echo "Parameter tidak valid.";
        }
        // return view('hakakses.php');
    }
    public function simpanketerlibatan()
    {

        $validation = \Config\Services::validation();

        // Validasi file
        $validation->setRules([
            'file_pdf' => [
                'label'  => 'File PDF',
                'rules'  => 'uploaded[file_pdf]|max_size[file_pdf,1048]|ext_in[file_pdf,pdf]',
                'errors' => [
                    'uploaded' => 'Anda harus memilih file untuk diupload.',
                    'max_size' => 'Ukuran file maksimal 1 MB (1048 KB).',
                    'ext_in'   => 'File harus berformat PDF.',
                ]
            ]
        ]);

        if (!$this->validate($validation->getRules())) {
            // Jika validasi gagal, kembali ke form dengan error
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        // $fileGambar = $this->request->getFile('gambar');
        // $namaGambar = $fileGambar->getRandomName();
        // $fileGambar->move(FCPATH . 'uploads/dokumentasi/', $namaGambar); // Simpan di public/

        // Proses upload file
        $file = $this->request->getFile('file_pdf');
        if ($file->isValid() && !$file->hasMoved()) {
            // Generate nama file baru (opsional)
            $newName = $file->getRandomName();

            //     // Pindahkan file ke folder uploads
            //     $file->move('uploads', $newName);

            // Simpan informasi file ke database (opsional)
            $user = user();
            $datauser = $this->tausermodel->listuser($user->id);

            $data = [
                'tahun' => session()->get('tahun'),
                'kd_subunit' => $datauser['kd_sub_unit'],
                'nm_subunit' => $datauser['sub_unit'],
                'pokja' => $this->request->getVar('pokja'),
                'program_utama' => $this->request->getVar('program_utama'),
                'program_intervensi' => $this->request->getVar('program_intervensi'),
                'dasar_peraturan' => $newName,
            ];
            echo dd($data);
            //     // $this->db->table('files')->insert($data);

            //     // Set flash data untuk notifikasi
            //     session()->setFlashdata('success', 'File berhasil diupload!');

            //     return redirect()->to('/upload');
        } else {
            // Jika file tidak valid
            session()->setFlashdata('error', 'Gagal mengupload file. Silahkan coba lagi.');
            return redirect()->back();
        }
        // Ambil data dari request POST
        // $dataToSave = [
        //     'pokja' => $user->id,
        //     'program_utama' => $receivedParams['keterlibatan'] ?? null,
        //     'program_intervensi' => $tahunaktif,
        //     'created_at' => date('Y-m-d H:i:s'),
        //     'updated_at' => date('Y-m-d H:i:s'),
        // ];

        // Simpan data ke database (misalnya menggunakan model)
        // Pastikan Anda memiliki model yang sesuai untuk menyimpan data ini
        // Contoh: $this->keterlibatanModel->save($dataToSave);

        // Tampilkan pesan sukses atau redirect sesuai kebutuhan
        // return redirect()->to('/desakumaju/opd/data?hal=rencana&action=mappingaktifitas')->with('success', 'Data keterlibatan berhasil disimpan.');
    }
}
