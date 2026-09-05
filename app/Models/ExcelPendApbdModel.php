<?php

namespace App\Models;

use CodeIgniter\Model;

class ExcelPendApbdModel extends Model
{
    protected $table = 'ta_opdpendapatan';
    protected $primaryKey = 'NO';
    protected $returnType     = 'array'; // Tipe data yang dikembalikan
    protected $allowedFields = [
        'TAHUN',
        'KODE_AKUN',
        'NAMA_AKUN',
        'KODE_OPD',
        'NAMA_OPD',
        'URAIAN',
        'KETERANGAN',
        'PAGU',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'CREATE_AT';
    protected $updatedField = 'UPDATE_AT';


    // Fungsi untuk insert batch data
    public function insertBatchData(array $data)
    {
        return $this->insertBatch($data);
    }
}
