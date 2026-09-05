<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Authentication;
use PhpOffice\PhpSpreadsheet\IOFactory;
use CodeIgniter\Files\File;

class ExcelImport extends BaseController
{
    protected $auth;
    protected $helpers = ['form'];



    public function index()
    {
        return view('excel_import_form');
    }

    public function import()
    {
        ini_set('max_execution_time', 0);
        // Validasi
        $rules = [
            'excel_file' => [
                'label' => 'File Excel',
                'rules' => 'uploaded[excel_file]|ext_in[excel_file,xls,xlsx]|max_size[excel_file,2048]',
                'errors' => [
                    'uploaded' => 'Harus memilih file Excel',
                    'ext_in' => 'Hanya file Excel (.xls, .xlsx) yang diperbolehkan',
                    'max_size' => 'Ukuran file maksimal 2MB'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('excel_file');

        if (!$file->isValid()) {
            return redirect()->back()->withInput()->with('error', $file->getErrorString());
        }

        // Pindahkan file ke folder writable/uploads
        $newName = $file->getRandomName();
        $file->move(WRITEPATH . 'uploads', $newName);
        $filePath = WRITEPATH . 'uploads/' . $newName;

        try {
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Hapus header jika ada
            array_shift($rows);

            $db = \Config\Database::connect();
            $db->transStart();

            foreach ($rows as $row) {
                // Sesuaikan dengan struktur tabel Anda
                $data = [
                    'nama' => $row[0] ?? null,
                    'email' => $row[1] ?? null,
                    'alamat' => $row[2] ?? null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
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
                    `pagu_rincian` => $row[19] ?? NULL,
                    `label_subkegiatan` => $row[20] ?? NULL,
                    `perubahan_ke` => $row[21] ?? NULL

                ];
                // Validasi data sebelum insert
                if (!empty($data['nama']) && !empty($data['email'])) {
                    $db->table('data_import')->insert($data);
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Terjadi kesalahan saat menyimpan data ke database');
            }

            // Hapus file setelah diproses
            unlink($filePath);

            return redirect()->to('/excel-import')->with('message', 'Data berhasil diimpor!');
        } catch (\Exception $e) {
            // Hapus file jika terjadi error
            if (isset($filePath) && file_exists($filePath)) {
                unlink($filePath);
            }

            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
