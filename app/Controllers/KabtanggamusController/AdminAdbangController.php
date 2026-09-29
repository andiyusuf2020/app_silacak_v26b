<?php

namespace App\Controllers\KabtanggamusController;

use \Myth\Auth\Authorization\GroupModel;
use App\Models\KabtanggamusModel\RealApbdModel;
use App\Models\KabtanggamusModel\JadwalModel;

use App\Models\KabtanggamusModel\TglApbdModel;
use App\Models\KabtanggamusModel\ExcelApbdModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

use App\Controllers\BaseController;
use App\Libraries\CryptoUrl;

class AdminAdbangController extends BaseController
{
    protected $realapbdmodel;
    protected $tglapbdmodel;
    protected $excelmodel;
    protected $helpers = ['form', 'url'];
    protected $security;
    protected $jadwalmodel;

    public function __construct()
    {
        helper(['form', 'url', 'filesystem']);
        // Load library security untuk sanitasi
        $this->security = \Config\Services::security();
        $this->jadwalmodel = new JadwalModel();

        $this->realapbdmodel = new RealApbdModel();
        $this->tglapbdmodel = new TglApbdModel();
        $this->excelmodel = new ExcelApbdModel();
    }
    public function dataperopd()
    {
        $token = $this->request->getGet('token');

        if (empty($token)) {
            return redirect()->back()->with('error', 'Token URL tidak ditemukan.');
        }

        // Dekripsi & Validasi Token (Maksimal 15 menit)
        $payload = CryptoUrl::decrypt($token, 900);

        if (!$payload) {
            return redirect()->back()->with('error', 'Akses ditolak: Token tidak valid atau telah kadaluwarsa.');
        }

        // Ekstrak data hasil dekripsi (hanya ID dan Name)
        $kdSU   = $payload['kdSU'];
        $blndata = $payload['blndata'];
        $user = user();
        $groupModel = new GroupModel();
        $groupuser = $groupModel->getGroupsForUser($user->id);
        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();


        $data =
            [
                'wilayah' => session()->get('wilayah'),
                'groupuser' => $groupuser[0]['name'],
                'groupmenu' => $groupuser[0]['name'],
                'tahunaktif' => session()->get('tahun'),
                'titlepage' => 'halaman Admin Adbang ',
                'datauser' => $user->sub_unit,
                'tglaktif' => session()->get('tglaktif'),
                'nama_opd' => $kdSU,
                'rekap' => $this->realapbdmodel->getRekapLengkapPerTingkatAman($blndata, $kdSU),
                // 'rekap' => $this->realapbdmodel->getRekapLengkapPerTingkat($blndata, $kdSU),
                'blndata' => $blndata,
                'tglapbd' => $this->realapbdmodel->tgldata(),
                'tglapbdaktif' => $this->tglapbdmodel->tgldataaktif(),
                'bulan' => $jadwalaktif['bulan'],
                'validation' => \Config\Services::validation()

            ];
        // echo dd($blndata . '-' . $kdSU);
        // $data['rekap'] = $this->realapbdmodel->getRekapLengkapPerTingkat($blndata, $kdSU);
        // echo dd($data['rekap']);
        return view('KabtanggamusViews/listsubkeg3', $data);
    }

    public function index()
    {
        $req = $this->request;
        $receivedParams = $req->getGet();
        $wilayah = $receivedParams['wilayah'] ?? null;
        $hal = $receivedParams['hal'] ?? null;
        $action = $receivedParams['action'] ?? null;
        $kdSU = $receivedParams['kdSU'] ?? null;
        $blndata = $receivedParams['blndata'] ?? null;
        $kdSK = $receivedParams['kdSK'] ?? null;
        $tgldataopd = $receivedParams['tgldataopd'] ?? null;
        $tgldata = $receivedParams['tgldata'] ?? null;
        $id = $receivedParams['id'] ?? null;


        $user = user();
        $groupModel = new GroupModel();
        $groupuser = $groupModel->getGroupsForUser($user->id);
        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        // $data['jadwalaktif'] = $this->jadwalmodel->jadwalaktifskrg();

        $data =
            [
                'wilayah' => session()->get('wilayah'),
                'groupuser' => $groupuser[0]['name'],
                'groupmenu' => $groupuser[0]['name'],
                'tahun' => session()->get('tahun'),
                'titlepage' => 'halaman Admin Adbang ',
                'datauser' => $user->sub_unit,
                'tglaktif' => session()->get('tglaktif'),
                'nama_opd' => $kdSU,
                'blndata' => $blndata,
                'kdSK' => $kdSK,
                'tgldataopd' => $tgldataopd,
                'tglapbd' => $this->realapbdmodel->tgldata(),
                'tglapbdaktif' => $this->tglapbdmodel->tgldataaktif(),
                'status' => 'apbd',
                'title' => 'Upload Realisasi APBD dari Data excel SIPD',
                'bulan' => $jadwalaktif['bulan'],
                'jadwal' => $this->jadwalmodel->jadwal(),
                'validation' => \Config\Services::validation()

            ];
        // $data['tglapbd'] = $this->realapbdmodel->tgldata();
        // $data['tglapbdaktif'] = $this->tglapbdmodel->tgldataaktif();


        if (!$receivedParams) {
            // echo dd($data);
            if (!$data['groupuser']) {
                return redirect()->to(base_url());
            }
            if (!$data['wilayah']) {
                return redirect()->to(base_url());
            }
            if (!$data['tahun']) {
                session()->set('groupuser', $data['groupuser']);
                session()->set('groupmenu', $data['groupuser']);
                return view('pilihtahun', $data);
            }

            $datatglaktif = $this->tglapbdmodel->tgldataaktif();
            session()->set('tglaktif', $datatglaktif['tanggal']);
            $tahun = session()->get('tahun');
            $tglaktif = session()->get('tglaktif');
            $data['dataopdadmin'] = $this->realapbdmodel->getCapaianKinerjaDenganGeometri($tahun, $tglaktif);
            // echo dd($tahun . '-'  . $tglaktif);
            // echo dd($data['dataopdadmin']);
            // echo dd($data);
            return view('KabtanggamusViews/Adminadbang/index', $data);
        }
        $wilayah = session()->get('wilayah');
        if (!$wilayah) {
            return redirect()->to(base_url());
        }
        $this->ValidasiHash($req);
        if ($hal == 'apbdopd') {
            return view('KabtanggamusViews/Adminadbang/listtgldata', $data);

            // $data['dataopdadmin'] = $this->realapbdmodel->getCapaianKinerjaDenganGeometri($tgldata);
            // return view('KabtanggamusViews/Adminadbang/apbdopd', $data);
        } elseif ($hal == 'uploadapbd') {
            // $data['status'] = 'apbd';
            // $data['title'] = 'Upload Realisasi APBD dari Data excel SIPD';
            // $data['validation'] = \Config\Services::validation();
            return view('KabtanggamusViews/Adminadbang/upload_form', $data);
        } elseif ($hal == 'angkasopd') {
            $data['dataopdadmin'] = $this->realapbdmodel->getCapaianKinerjaDenganGeometri($data['tahun'], $data['tglaktif']);
            return view('KabtanggamusViews/Adminadbang/angkasopd', $data);
        } elseif ($hal == 'datapbj') {
            $data['dataopdadmin'] = $this->realapbdmodel->getCapaianKinerjaDenganGeometri($data['tahun'], $data['tglaktif']);
            return view('KabtanggamusViews/Adminadbang/datapbj', $data);
        } elseif ($hal == 'jadwal') {

            $data['jadwalaktif'] = $this->jadwalmodel->jadwalaktifskrg();
            //tanggal data pada tabel apbd
            $data['tglapbd'] = $this->realapbdmodel->tgldata();
            $data['tglapbdaktif'] = $this->tglapbdmodel->tgldataaktif();
            // echo dd($data['tglapbd']);
            return view('KabtanggamusViews/Adminadbang/v_jadwal', $data);
        }
        if ($hal == 'gantibulanaktif') {

            return view('KabtanggamusViews/Adminadbang/v_listjadwal', $data);
            //echo dd($data['jadwal']);
        }
        if ($hal == 'editbulanaktif') {
            // $idAwal = $data['jadwalaktif']['id'] ?? null;
            // $tahun = $data['jadwalaktif']['tahun'] ?? null;
            // $bulan = $data['jadwalaktif']['bulan'] ?? null;
            $idAwal = $jadwalaktif['id'] ?? null;
            $tahun = $jadwalaktif['tahun'] ?? null;
            $bulan = $jadwalaktif['bulan'] ?? null;
            //   echo $idUbah;
            $nonaktifbln = [
                'id' => $idAwal,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'status' => '0'
            ];
            $dataubah = $this->jadwalmodel->jdwlygdiaktifkan($id);
            $aktifbln = [
                'id' => $id,
                'tahun' => $dataubah['tahun'],
                'bulan' => $dataubah['bulan'],
                'status' => '1'
            ];
            // echo dd($nonaktifbln);
            $aktifasi = $this->jadwalmodel->save($aktifbln);
            $nonaktifasi = $this->jadwalmodel->save($nonaktifbln);
            if (!$aktifasi && !$nonaktifasi) {
                echo "Terjadi kesalahan";
            } else {
                return redirect()->to(hash_url('' . $data['wilayah'] . '/' . $data['groupuser'] . '/', ['hal' => 'jadwal']));
            }
        }
        if ($hal == 'gantitglaktif') {
            $updateAktif = $this->tglapbdmodel->updatetgl(1, $data['tahun'], $id);

            if (!$updateAktif) {
                session()->setFlashdata('message', 'Tanggal Data APBD berhasil diaktifkan');
            }
            // return redirect()->to(base_url('lrfkadmin/jadwal')->withInput()->with('message', 'Tanggal Data APBD berhasil diaktifkan'));
            return redirect()->to(hash_url('' . $data['wilayah'] . '/' . $data['groupuser'] . '/', ['hal' => 'jadwal']))->withInput()->with('message', 'Tanggal Data APBD berhasil diaktifkan');
        }


        if ($tgldata) {
            $dataopdadmin = $this->realapbdmodel->getCapaianKinerjaDenganGeometri($data['tahun'], $tgldata);
            // $dataapbdopd = $dataopdadmin['data'];
            // $data['dataopdadmin'] = $this->realapbdmodel->getRekapSubSkpdLengkap($tgldata, $data['bulan']);
            $dataopdapbd = $this->realapbdmodel->getRekapSubSkpd($tgldata);
            $data['dataopdadmin'] = $this->realapbdmodel->getRekapSubSkpd($tgldata);

            // Hanya enkripsi 'id' dan 'name'
            $dataopdapbds = array_map(function ($dataopdapbd) {
                $dataopdapbd['update_token'] = CryptoUrl::encrypt([
                    'kdSU'   => $dataopdapbd['NAMA_UNIT_SKPD'],
                    'blndata' => $dataopdapbd['BULAN']
                ]);
                return $dataopdapbd;
            }, $data['dataopdadmin']);
            $datauserx = json_encode($dataopdapbds);
            $data['datajson'] = preg_replace('/"([^"]+)"\s*:/', '$1:', $datauserx);

            // echo dd($data['dataopdadmin']);
            return view('KabtanggamusViews/Adminadbang/listopdapbd2', $data);
        }
        // $data['tgldata'] = $tgldata;
        // $data['dataopdadmin'] = $this->realapbdmodel->listopdadmin($tgldata);


        if ($hal == 'detail') {
            if ($action == 'opd') {
                $data['dataopd'] = $this->realapbdmodel->rpersk($kdSU, $data['tahun'], $blndata, $tgldataopd);
                // echo dd($kdSU . '-' . $tahunaktif . '-' . $bulanaktif . '-' . $tglaktif);
                // echo dd($data['dataopd']);
                return view('KabtanggamusViews/Adminadbang/detailopdapbd', $data);
            }
            if ($action == 'detailopd') {
                $data['dataopd'] = $this->realapbdmodel->rperrso2($kdSU, $kdSK, $data['tahun'], $tgldataopd)->getResultArray();

                // echo dd($kdSU . '-' . $kdSK . '-' . $tahunaktif . '-' . $tglaktif);
                // echo dd($data['dataopd']);
                return view('superadmin/2026/detailopdsro', $data);
            }
        }
    }
    public function uploadapbd()
    {
        ini_set('max_execution_time', 0);

        $rules = [
            'excel_file' => [
                'label' => 'Excel File',
                'rules' => 'uploaded[excel_file]|ext_in[excel_file,xls,xlsx]|max_size[excel_file,20480]',
                'errors' => [
                    'uploaded' => 'Harus mengupload file',
                    'ext_in' => 'Hanya file Excel yang diperbolehkan',
                    'max_size' => 'Ukuran file maksimal 2MB'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil file
        $file = $this->request->getFile('excel_file');

        // Generate nama file random untuk keamanan
        $newName = $file->getRandomName();
        $file->move(WRITEPATH . 'uploads', $newName);
        $filePath = WRITEPATH . 'uploads/' . $newName;

        try {
            // Load file Excel
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Siapkan data untuk database
            $dataToInsert = [];
            $headerSkipped = false;

            foreach ($rows as $row) {
                // Skip header
                if (!$headerSkipped) {
                    $headerSkipped = true;
                    continue;
                }
                $dataToInsert[] = [
                    'TAHUN' => $this->security->sanitizeFilename($row[0] ?? ''),
                    'BULAN' => $this->security->sanitizeFilename($row[1] ?? ''),
                    'KODE_SKPD' => $this->security->sanitizeFilename($row[2] ?? ''),
                    'NAMA_SKPD' => $this->security->sanitizeFilename($row[3] ?? ''),
                    'KODE_UNIT_SKPD' => $this->security->sanitizeFilename($row[4] ?? ''),
                    'NAMA_UNIT_SKPD' => $this->security->sanitizeFilename($row[5] ?? ''),
                    'KODE_URUSAN' => $this->security->sanitizeFilename($row[6] ?? ''),
                    'NAMA_URUSAN' => $this->security->sanitizeFilename($row[7] ?? ''),
                    'KODE_BIDANG_URUSAN' => $this->security->sanitizeFilename($row[8] ?? ''),
                    'NAMA_BIDANG_URUSAN' => $this->security->sanitizeFilename($row[9] ?? ''),
                    'KODE_PROGRAM' => $this->security->sanitizeFilename($row[10] ?? ''),
                    'NAMA_PROGRAM' => $this->security->sanitizeFilename($row[11] ?? ''),
                    'KODE_GIAT' => $this->security->sanitizeFilename($row[12] ?? ''),
                    'NAMA_GIAT' => $this->security->sanitizeFilename($row[13] ?? ''),
                    'KODE_SUB_GIAT' => $this->security->sanitizeFilename($row[14] ?? ''),
                    'NAMA_SUB_GIAT' => $this->security->sanitizeFilename($row[15] ?? ''),
                    'KODE_AKUN' => $this->security->sanitizeFilename($row[16] ?? ''),
                    'NAMA_AKUN' => $this->security->sanitizeFilename($row[17] ?? ''),
                    'KODE_KELOMPOK' => $this->security->sanitizeFilename($row[18] ?? ''),
                    'NAMA_KELOMPOK' => $this->security->sanitizeFilename($row[19] ?? ''),
                    'KODE_JENIS' => $this->security->sanitizeFilename($row[20] ?? ''),
                    'NAMA_JENIS' => $this->security->sanitizeFilename($row[21] ?? ''),
                    'KODE_OBJEK' => $this->security->sanitizeFilename($row[22] ?? ''),
                    'NAMA_OBJEK' => $this->security->sanitizeFilename($row[23] ?? ''),
                    'KODE_RINCIAN_OBJEK' => $this->security->sanitizeFilename($row[24] ?? ''),
                    'NAMA_RINCIAN_OBJEK' => $this->security->sanitizeFilename($row[25] ?? ''),
                    'KODE_SRO' => $this->security->sanitizeFilename($row[26] ?? ''),
                    'NAMA_SRO' => $this->security->sanitizeFilename($row[27] ?? ''),
                    'TOTAL_ANGGARAN' => $this->security->sanitizeFilename($row[28] ?? ''),
                    'TOTAL_REALISASI' => $this->security->sanitizeFilename($row[29] ?? ''),
                    'SELISIH' => $this->security->sanitizeFilename($row[30] ?? ''),
                    'KETERANGAN' => $this->security->sanitizeFilename($row[31] ?? ''),
                    // 'CREATE_AT' => $row[32],
                    // 'UPDATE_AT' => $row[33],
                ];
            }

            // Insert ke database dalam batch
            if (!empty($dataToInsert)) {
                $this->excelmodel->insertBatchData($dataToInsert);
            }

            // Hapus file setelah diproses
            unlink($filePath);
            $user = user();
            $groupModel = new GroupModel();
            $groupuser = $groupModel->getGroupsForUser($user->id);

            $data =
                [
                    'wilayah' => session()->get('wilayah'),
                    'groupuser' => $groupuser[0]['name'],
                    'groupmenu' => $groupuser[0]['name'],
                    'tahun' => session()->get('tahun'),
                    'titlepage' => 'halaman Admin Adbang ',
                    'datauser' => $user->sub_unit,
                    'tglaktif' => session()->get('tglaktif'),
                    'status' => 'apbd',
                    'title' => 'Upload Realisasi APBD dari Data excel SIPD',
                    'validation' => \Config\Services::validation()

                ];

            return redirect()->to(hash_url(' ' . $data['wilayah'] . '/' . $data['groupuser'] . '', ['hal' => 'uploadapbd']))->with('success', 'Data berhasil diupload: ' . count($dataToInsert) . ' record');
        } catch (\Exception $e) {
            // Hapus file jika terjadi error
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            log_message('error', 'Excel Upload Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
