<?php

namespace App\Models\DataApbdModel;

use CodeIgniter\Model;

class AngkasModel extends Model
{
    protected $table = 'ta_angkasapbd';
    protected $primaryKey = 'Id_angkas';
    protected $returnType     = 'array'; // Tipe data yang dikembalikan
    protected $allowedFields = [
        'TAHUN',
        'KODE_UNIT_SKPD',
        'NAMA_UNIT_SKPD',
        'KODE_SUB_GIAT',
        'NAMA_SUB_GIAT',
        'TOTAL_ANGGARAN',
        'BULAN_ANGKAS',
        'ANGKAS',
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
    public function getDataAngkas($tahun, $bulan)
    {
        return $this
            ->select('*, SUM(TOTAL_ANGGARAN) as total_anggaran')
            ->where('TAHUN', $tahun)
            ->where('BULAN_ANGKAS', $bulan)
            ->groupBy('KODE_UNIT_SKPD')
            ->get()->getResultArray();
    }
    public function getDataAngkaOpd($tahun)
    {
        return $this
            ->select('*, SUM(ANGKAS) as total_angkas')
            ->where('TAHUN', $tahun)
            // ->where('BULAN_ANGKAS', $bulan)
            ->groupBy('KODE_UNIT_SKPD')
            ->get()->getResultArray();
    }
    public function getTotalDataAngkasPerBulanOPD($tahun, $kdSU, $bulan)
    {
        return $this
            ->select('*, SUM(ANGKAS) as total_angkas')
            ->where('TAHUN', $tahun)
            ->where('KODE_UNIT_SKPD', $kdSU)
            ->where('BULAN_ANGKAS', $bulan)
            ->groupBy('KODE_UNIT_SKPD')
            ->get()->getRowArray();
    }
    public function getTotalAngkasPerBulanAll($tahun, $bulan)
    {
        return $this
            ->select('*, SUM(ANGKAS) as total_anggaran')
            ->where('TAHUN', $tahun)
            ->where('BULAN_ANGKAS', $bulan)
            ->groupBy('BULAN_ANGKAS')
            ->get()->getRowArray();
    }
    public function getDataAngkasPerGiat($tahun, $kdSU, $kdSK)
    {
        return $this
            ->select('*')
            ->where('TAHUN', $tahun)
            ->where('KODE_UNIT_SKPD', $kdSU)
            ->where('KODE_SUB_GIAT', $kdSK)
            // ->groupBy('KODE_SUB_GIAT')
            ->get()->getResultArray();
    }
    public function getDataAngkasOpd($tahun, $NAMA_UNIT_SKPD)
    {
        return $this
            ->select('*, SUM(ANGKAS) as total_angkas')
            ->where('TAHUN', $tahun)
            // ->where('BULAN_ANGKAS', $bulan)
            ->where('NAMA_UNIT_SKPD', $NAMA_UNIT_SKPD)
            ->groupBy('KODE_UNIT_SKPD')
            ->get()->getRowArray();
    }
    public function getDataAngkasSubGiat($tahun, $KODE_UNIT_SKPD)
    {
        return $this
            ->select('*')
            // ->select('*, SUM(TOTAL_ANGGARAN) as total_anggaran')
            ->where('TAHUN', $tahun)
            // ->where('BULAN_ANGKAS', $bulan)
            ->where('KODE_UNIT_SKPD', $KODE_UNIT_SKPD)
            ->groupBy('KODE_SUB_GIAT')
            ->get()->getResultArray();
    }
    public function getDataTotalAngkasSubGiat($tahun, $NAMA_UNIT_SKPD, $NAMA_SUB_GIAT)
    {
        return $this
            ->select('*, SUM(ANGKAS) as total_angkas')
            ->where('TAHUN', $tahun)
            // ->where('BULAN_ANGKAS', $bulan)
            ->where('NAMA_UNIT_SKPD', $NAMA_UNIT_SKPD)
            ->where('NAMA_SUB_GIAT', $NAMA_SUB_GIAT)
            ->groupBy('KODE_SUB_GIAT')
            ->get()->getRowArray();
    }
    public function getDataAngkasSubGiatDetail($tahun, $bulan, $NAMA_UNIT_SKPD, $NAMA_SUB_GIAT)
    {
        return $this
            ->select('*, SUM(ANGKAS) as total_angkas')
            ->where('TAHUN', $tahun)
            ->where('BULAN_ANGKAS', $bulan)
            ->where('NAMA_UNIT_SKPD', $NAMA_UNIT_SKPD)
            ->where('NAMA_SUB_GIAT', $NAMA_SUB_GIAT)
            ->get()->getRowArray();
    }
}
