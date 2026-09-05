<?php

namespace App\Models;

use CodeIgniter\Model;

class ExcelRupModel extends Model
{
    protected $table = 'ta_ruppengadaan';
    protected $primaryKey = 'id';
    protected $returnType     = 'array'; // Tipe data yang dikembalikan
    protected $allowedFields = [
        'id',
        'tahun',
        'bulan',
        'Cara_Pengadaan',
        'Jenis_Pengadaan',
        'Kode_RUP',
        'Metode_Pengadaan',
        'Nama_Instansi',
        'Nama_Paket',
        'Nama_Satuan_Kerja',
        'Produk_Dalam_Negeri',
        'Sumber_Dana',
        'Tahun_Anggaran',
        'Total_Nilai',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'create_at';
    protected $updatedField = 'update_at';


    // Fungsi untuk insert batch data
    public function insertBatchData(array $data)
    {
        return $this->insertBatch($data);
    }
}
