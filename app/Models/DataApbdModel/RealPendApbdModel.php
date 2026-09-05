<?php

namespace App\Models\DataApbdModel;

use CodeIgniter\Model;

class RealPendApbdModel extends Model
{
    protected $table = 'ta_real_opdpendapatan';
    protected $primaryKey = 'id';
    protected $returnType     = 'array'; // Tipe data yang dikembalikan
    protected $allowedFields = [
        'KODE_OPD',
        'NAMA_OPD',
        'PAGU',
        'REALISASI_PEND',
        'TAHUN',
        'BULAN',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'CREATE_AT';
    protected $updatedField = 'UPDATE_AT';
    protected $deletedField = 'DELETE_AT';


    // Fungsi untuk insert batch data
    public function insertBatchData(array $data)
    {
        return $this->insertBatch($data);
    }
    //fungsi update batch data
    public function updateBatchData(array $data, $id)
    {
        return $this->updateBatch($data, $id);
    }
    public function getDataRealisasiPendapatan($tahun, $bulan, $NAMA_OPD)
    {
        return $this
            ->select('*, SUM(PAGU) as total_pagu, SUM(REALISASI_PEND) as total_realisasi_pend')
            ->where('TAHUN', $tahun)
            ->where('BULAN', $bulan)
            ->where('NAMA_OPD', $NAMA_OPD)
            ->groupBy('NAMA_OPD')
            ->get()->getRowArray();
    }
    public function getDataROpdPendapatan($tahun, $bulan)
    {
        return $this
            ->select('*, SUM(PAGU) as total_pagu, SUM(REALISASI_PEND) as total_realisasi_pend')
            ->where('TAHUN', $tahun)
            ->where('BULAN', $bulan)
            ->groupBy('KODE_OPD')
            ->get()->getResultArray();
    }
    public function getDataRPendapatan($tahun, $bulan, $NAMA_OPD)
    {
        return $this
            ->select('*, SUM(PAGU) as total_pagu, SUM(REALISASI_PEND) as total_realisasi_pend')
            ->where('TAHUN', $tahun)
            ->where('BULAN', $bulan)
            ->where('NAMA_OPD', $NAMA_OPD)
            ->get()->getRowArray();
    }
    public function getDataRPendapatanAll($tahun, $NAMA_OPD)
    {
        return $this
            ->select('*, SUM(PAGU) as total_pagu, SUM(REALISASI_PEND) as total_realisasi_pend')
            ->where('TAHUN', $tahun)
            ->where('NAMA_OPD', $NAMA_OPD)
            ->get()->getRowArray();
    }
}
