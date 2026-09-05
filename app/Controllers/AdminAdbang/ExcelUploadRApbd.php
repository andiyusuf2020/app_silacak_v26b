<?php

namespace App\Controllers\AdminAdbang;

use \Myth\Auth\Authorization\GroupModel;

use App\Models\ExcelApbdModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Controllers\BaseController;

class ExcelUploadRApbd extends BaseController
{
    protected $model;
    protected $helpers = ['form', 'url'];
    protected $security;
    public function __construct()
    {
        // $this->model = new ExcelModel();
        $this->model = new ExcelApbdModel();
        // Load library security untuk sanitasi
        $this->security = \Config\Services::security();
    }

    public function index()
    {
        // Cek apakah user sudah login (contoh sederhana)
        // if (!session()->get('isLoggedIn')) {
        //    return redirect()->to('/login');
        // }
        if (logged_in()) {
            $user = user();
            $groupModel = new GroupModel();
            $groupuser = $groupModel->getGroupsForUser($user->id);
            foreach ($groupuser as $row) {
                $namagroup = $row['name'];
            }
        }
        // $data['titlepage'] = 'Selamat Data di e-TAPIS Laporan Realisasi Fisik Anggaran Program Kegiatan Perangkat Daerah Provinsi Lampung';
        // $data['groupuser'] = $namagroup;
        // $data['groupmenu'] = $namagroup;

        $data = [
            'titlepage' => 'Selamat Data di e-TAPIS Laporan Realisasi Fisik Anggaran Program Kegiatan Perangkat Daerah Provinsi Lampung',
            'groupuser' => $namagroup,
            'groupmenu' => $namagroup,

            'title' => 'Upload Realisasi APBD',
            'status' => 'apbd',
            'validation' => \Config\Services::validation()
        ];

        return view('upload_form', $data);
    }

    public function upload()
    {
        // Validasi CSRF token
        //  if (!$this->request->is('post') || !$this->request->getPost($this->security->getCSRFTokenName())) {
        //     return redirect()->back()->with('error', 'Invalid CSRF Token');
        // }

        // Validasi form
        // Validasi form
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

                // Sanitasi data sebelum diproses
                // $name = $this->security->sanitizeFilename($row[0] ?? '');
                // $email = filter_var($row[1] ?? '', FILTER_SANITIZE_EMAIL);
                // $phone = preg_replace('/[^0-9]/', '', $row[2] ?? '');
                // $address = $this->security->sanitizeFilename($row[3] ?? '');

                // // Validasi data
                // $validationData = [
                //     'name' => $name,
                //     'email' => $email,
                //     'phone' => $phone,
                //     'address' => $address
                // ];

                // if (!$this->model->validate($validationData)) {
                //     continue; // Skip data yang tidak valid
                // }

                // $dataToInsert[] = $validationData;

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
                $this->model->insertBatchData($dataToInsert);
            }

            // Hapus file setelah diproses
            unlink($filePath);

            return redirect()->to('adminprov/uploadapbd')->with('success', 'Data berhasil diupload: ' . count($dataToInsert) . ' record');
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
