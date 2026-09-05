<?php

namespace App\Models\CapkinModel;

use CodeIgniter\Model;

class TaSubKegCapkinModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_subkeg_capkin_apbd';
    protected $primaryKey           = 'id_sk';
    protected $useAutoIncrement     = true;
    protected $allowedFields  = [
        'id_sk',
        'tahun',
        'kd_subunit',
        'nm_subunit',
        'kd_urusan',
        'kd_program',
        'kd_kegiatan',
        'kd_subkegiatan',
        'nm_program',
        'nm_kegiatan',
        'nm_subkegiatan',
        'pagu',
    ];
    protected $useSoftDeletes = true;
    protected $useTimestamps = true; // Tidak menggunakan timestamps
    protected $dateFormat    = 'date';
    protected $createdField  = 'create_at';
    protected $updatedField  = 'update_at';
    protected $deletedField  = 'delete_at';

    public function DataPerSK($id_sk)
    {
        return $this
            ->select('*')
            ->where('id_sk', $id_sk)
            ->get()
            ->getRowArray();
    }

    public function DataPerOpd($tahun, $kd_sub_unit)
    {
        return $this
            ->select('*')
            ->where('tahun', $tahun)
            ->where('kd_subunit', $kd_sub_unit)
            ->where('delete_at=', 0)
            ->get()
            ->getResultArray();
    }
    public function cekskcapkin($tahun, $kd_sub_unit, $kd_subkegiatan)
    {
        return $this
            ->select('*')
            ->where('tahun', $tahun)
            ->where('kd_subunit', $kd_sub_unit)
            ->where('kd_subkegiatan', $kd_subkegiatan)
            ->where('delete_at=', 0)
            ->get()
            ->getRowArray();
    }
}
