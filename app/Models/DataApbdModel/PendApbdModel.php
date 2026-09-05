<?php

namespace App\Models\DataApbdModel;

use CodeIgniter\Model;

class PendApbdModel extends Model
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
    public function getDataOpdPendapatan($tahun)
    {
        return $this
            ->select('*, SUM(PAGU) as total_pagu')
            ->where('TAHUN', $tahun)
            ->groupBy('KODE_OPD')
            ->get()->getResultArray();
    }
    public function getDataPendapatan($tahun, $NAMA_OPD)
    {
        return $this
            ->select('*, SUM(PAGU) as total_pagu')
            ->where('TAHUN', $tahun)
            ->where('NAMA_OPD', $NAMA_OPD)
            ->groupBy('NAMA_OPD')
            ->get()->getResultArray();
    }

    public function insertBatchData(array $data)
    {
        return $this->insertBatch($data);
    }
}
