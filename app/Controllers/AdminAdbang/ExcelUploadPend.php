<?php

namespace App\Controllers\AdminAdbang;

use \Myth\Auth\Authorization\GroupModel;

use App\Models\ExcelPendApbdModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Controllers\BaseController;

class ExcelUploadPend extends BaseController
{
    protected $model;
    protected $helpers = ['form', 'url'];
    protected $security;
    public function __construct()
    {
        // $this->model = new ExcelModel();
        $this->model = new ExcelPendApbdModel();
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

            'title' => 'Upload Pengelola Pendapatan APBD',
            'status' => 'pendapatan',
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
                    'KODE_AKUN' => $this->security->sanitizeFilename($row[1] ?? ''),
                    'NAMA_AKUN' => $this->security->sanitizeFilename($row[2] ?? ''),
                    'KODE_OPD' => $this->security->sanitizeFilename($row[3] ?? ''),
                    'NAMA_OPD' => $this->security->sanitizeFilename($row[4] ?? ''),
                    'URAIAN' => $this->security->sanitizeFilename($row[5] ?? ''),
                    'KETERANGAN' => $this->security->sanitizeFilename($row[6] ?? ''),
                    'PAGU' => $this->security->sanitizeFilename($row[7] ?? ''),
                ];
            }
            // echo dd($dataToInsert);
            // Insert ke database dalam batch
            if (!empty($dataToInsert)) {
                $this->model->insertBatchData($dataToInsert);
            }

            // Hapus file setelah diproses
            unlink($filePath);

            return redirect()->to('adminprov/uploadpendapatan')->with('success', 'Data berhasil diupload: ' . count($dataToInsert) . ' record');
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
