<?php

namespace App\Models\EksekutifModel;

use CodeIgniter\Model;

class TaTtdLaporanModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_ttd_laporan';
    protected $primaryKey           = 'id_ttd';
    protected $useAutoIncrement     = true;
    protected $allowedFields  = false;
    protected $useSoftDeletes = true;
    protected $useTimestamps = true; // Tidak menggunakan timestamps
    protected $dateFormat    = 'date';
    protected $createdField  = 'create_at';
    protected $updatedField  = 'update_at';
    protected $deletedField  = 'delete_at';

    public function DataTtdOpd($tahun, $kd_subunit)
    {
        return $this
            ->select('*')
            ->where('tahun', $tahun)
            ->where('kd_sub_unit', $kd_subunit)
            ->get()
            ->getRowArray();
    }
}
