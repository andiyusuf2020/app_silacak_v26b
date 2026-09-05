<?php

namespace App\Models;

use CodeIgniter\Model;

class ExcelRealRupModel extends Model
{
    protected $table = 'ta_real_ruppengadaan';
    protected $primaryKey = 'id';
    protected $returnType     = 'array'; // Tipe data yang dikembalikan
    protected $allowedFields = [
        'tahun',
        'bulan',
        'Jenis_Pengadaan',
        'Kode_Paket',
        'Kode_RUP',
        'Metode_Pengadaan',
        'Nama_Instansi',
        'Nama_Paket',
        'Nama_Penyedia',
        'Nama_Satuan_Kerja',
        'Nilai_PDN',
        'Status_Paket',
        'Sumber_Dana',
        'Sumber_Transaksi',
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
