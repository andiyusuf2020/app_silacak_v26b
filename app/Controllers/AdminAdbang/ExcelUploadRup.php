<?php

namespace App\Controllers\AdminAdbang;

use \Myth\Auth\Authorization\GroupModel;
use App\Controllers\BaseController;

use App\Models\ExcelRupModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ExcelUploadRup extends BaseController
{
    protected $model;
    protected $helpers = ['form', 'url'];
    protected $security;
    public function __construct()
    {
        // $this->model = new ExcelModel();
        $this->model = new ExcelRupModel();
        // Load library security untuk sanitasi
        $this->security = \Config\Services::security();
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

        $data = [
            'titlepage' => 'Selamat Data di e-TAPIS Laporan Realisasi Fisik Anggaran Program Kegiatan Perangkat Daerah Provinsi Lampung',
            'groupuser' => $namagroup,
            'groupmenu' => $namagroup,
            'title' => 'Upload Data Rencana Umum Pengadaan (RUP)',
            'status' => 'rup',
            'validation' => \Config\Services::validation()
        ];

        return view('upload_form', $data);
    }

    public function upload()
    {
        // Validasi CSRF token
        // if (!$this->request->is('post') || !$this->request->getPost($this->securitygetCSRFTokenName())) {
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
                    'tahun' => $this->security->sanitizeFilename($row[1] ?? ''),
                    'bulan' => $this->security->sanitizeFilename($row[2] ?? ''),
                    'Cara_Pengadaan' => $this->security->sanitizeFilename($row[3] ?? ''),
                    'Jenis_Pengadaan' => $this->security->sanitizeFilename($row[4] ?? ''),
                    'Kode_RUP' => $this->security->sanitizeFilename($row[5] ?? ''),
                    'Metode_Pengadaan' => $this->security->sanitizeFilename($row[6] ?? ''),
                    'Nama_Instansi' => $this->security->sanitizeFilename($row[7] ?? ''),
                    'Nama_Paket' => $this->security->sanitizeFilename($row[8] ?? ''),
                    'Nama_Satuan_Kerja' => $this->security->sanitizeFilename($row[9] ?? ''),
                    'Produk_Dalam_Negeri' => $this->security->sanitizeFilename($row[10] ?? ''),
                    'Sumber_Dana' => $this->security->sanitizeFilename($row[11] ?? ''),
                    'Tahun_Anggaran' => $this->security->sanitizeFilename($row[12] ?? ''),
                    'Total_Nilai' => $this->security->sanitizeFilename($row[13] ?? ''),
                ];
            }

            // Insert ke database dalam batch
            if (!empty($dataToInsert)) {
                $this->model->insertBatchData($dataToInsert);
            }

            // Hapus file setelah diproses
            unlink($filePath);

            return redirect()->to('adminprov/uploadrup')->with('success', 'Data berhasil diupload: ' . count($dataToInsert) . ' record');
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
