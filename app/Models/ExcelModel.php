<?php

namespace App\Models;

use CodeIgniter\Model;

class ExcelModel extends Model
{
    protected $table = 'excel_data';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'phone', 'address'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validasi input
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[100]',
        'email' => 'required|valid_email|max_length[100]',
        'phone' => 'permit_empty|max_length[20]',
        'address' => 'permit_empty|max_length[255]'
    ];

    // Pesan error validasi
    protected $validationMessages = [
        'name' => [
            'required' => 'Nama wajib diisi',
            'min_length' => 'Nama minimal 3 karakter',
            'max_length' => 'Nama maksimal 100 karakter'
        ],
        'email' => [
            'required' => 'Email wajib diisi',
            'valid_email' => 'Email tidak valid',
            'max_length' => 'Email maksimal 100 karakter'
        ]
    ];

    // Fungsi untuk insert batch data
    public function insertBatchData(array $data)
    {
        return $this->insertBatch($data);
    }
}
