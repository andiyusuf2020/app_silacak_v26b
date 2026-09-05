<?php

namespace App\Controllers;

use App\Models\ExcelApbdModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ExcelUploadApbd extends BaseController
{
    protected $model;
    protected $helpers = ['form', 'url'];
    protected $security;
    public function __construct()
    {
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

        $data = [
            'title' => 'Upload Excel to MySQL',
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
        ini_set('max_execution_time', 0);

        $rules = [
            'excel_file' => [
                'label' => 'Excel File',
                'rules' => 'uploaded[excel_file]|ext_in[excel_file,xls,xlsx]|max_size[excel_file,2048]',
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

                $data2 = [
                    `tahun` => $row[0] ?? NULL,
                    `kd_urusan` => $row[1] ?? NULL,
                    `nm_urusan` => $row[2] ?? NULL,
                    `kd_skpd` => $row[3] ?? NULL,
                    `nm_skpd` => $row[4] ?? NULL,
                    `kd_sub_unit` => $row[5] ?? NULL,
                    `nm_sub_unit` => $row[6] ?? NULL,
                    `kd_bidang_urusan` => $row[7] ?? NULL,
                    `nm_bidang_urusan` => $row[8] ?? NULL,
                    `kd_program` => $row[9] ?? NULL,
                    `nm_program` => $row[10] ?? NULL,
                    `kd_kegiatan` => $row[11] ?? NULL,
                    `nm_kegiatan` => $row[12] ?? NULL,
                    `kd_subkegiatan` => $row[13] ?? NULL,
                    `nm_subkegiatan` => $row[14] ?? NULL,
                    `kd_sumber_dana` => $row[15] ?? NULL,
                    `nm_sumber_dana` => $row[16] ?? NULL,
                    `kd_rek_belanja` => $row[17] ?? NULL,
                    `nm_rekening` => $row[18] ?? NULL,
                    `pagu` => $row[19] ?? NULL
                ];
                //   if (!$this->model->validate($validationData)) {
                //      continue; // Skip data yang tidak valid
                //  }

                $dataToInsert[] = $data2;
            }

            // Insert ke database dalam batch
            if (!empty($dataToInsert)) {
                $this->model->insertBatchData($dataToInsert);
            }

            // Hapus file setelah diproses
            unlink($filePath);

            return redirect()->to('/excel-upload')->with('success', 'Data berhasil diupload: ' . count($dataToInsert) . ' record');
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
