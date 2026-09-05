<?php

namespace App\Models;

use CodeIgniter\Model;

class ExcelApbdModel extends Model
{
    protected $table = 'ta_realisasi_apbd';
    protected $primaryKey = 'NO';
    protected $returnType     = 'array'; // Tipe data yang dikembalikan
    protected $allowedFields = [
        'TAHUN',
        'BULAN',
        'KODE_SKPD',
        'NAMA_SKPD',
        'KODE_UNIT_SKPD',
        'NAMA_UNIT_SKPD',
        'KODE_URUSAN',
        'NAMA_URUSAN',
        'KODE_BIDANG_URUSAN',
        'NAMA_BIDANG_URUSAN',
        'KODE_PROGRAM',
        'NAMA_PROGRAM',
        'KODE_GIAT',
        'NAMA_GIAT',
        'KODE_SUB_GIAT',
        'NAMA_SUB_GIAT',
        'KODE_AKUN',
        'NAMA_AKUN',
        'KODE_KELOMPOK',
        'NAMA_KELOMPOK',
        'KODE_JENIS',
        'NAMA_JENIS',
        'KODE_OBJEK',
        'NAMA_OBJEK',
        'KODE_RINCIAN_OBJEK',
        'NAMA_RINCIAN_OBJEK',
        'KODE_SRO',
        'NAMA_SRO',
        'TOTAL_ANGGARAN',
        'TOTAL_REALISASI',
        'SELISIH',
        'KETERANGAN',
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
