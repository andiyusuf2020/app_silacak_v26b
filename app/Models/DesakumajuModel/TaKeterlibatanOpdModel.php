<?php

namespace App\Models\DesakumajuModel;

use CodeIgniter\Model;

class TaKeterlibatanOpdModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_keterlibatan_desakumaju';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $allowedFields  = [
        'id',
        'tahun',
        'kd_subunit',
        'sub_unit',
        'pokja',
        'program_utama',
        'program_intervensi',
        'dasar_peraturan',
    ];
    protected $useSoftDeletes = true;
    protected $useTimestamps = true; // Tidak menggunakan timestamps
    protected $dateFormat    = 'date';
    protected $createdField  = 'create_at';
    protected $updatedField  = 'update_at';
    protected $deletedField  = 'delete_at';

    public function DataAll($tahun)
    {
        return $this
            ->select('*')
            ->where('tahun', $tahun)
            ->where('delete_at=', 0)
            ->get()
            ->getResultArray();
    }
    public function DataPerOpd($tahun, $kd_subunit)
    {
        return $this
            ->select('*')
            ->where('tahun', $tahun)
            ->where('kd_subunit', $kd_subunit)
            ->where('delete_at=', 0)
            ->get()
            ->getResultArray();
    }
}
