<?php

namespace App\Models\LrfkProvModel;

use CodeIgniter\Model;

class TaIndikatorSubKegModal extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_indi_subkegopd';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $allowedFields  = [
        'id',
        'tahun',
        'kd_sub_unit',
        'sub_unit',
        'kd_subkegiatan',
        'nm_subkegiatan',
        'satuan_indi',
        'vol_indi',
        'uraian'
    ];
    protected $useSoftDeletes = true;
    protected $useTimestamps = true; // Tidak menggunakan timestamps
    protected $dateFormat    = 'date';
    protected $createdField  = 'create_at';
    protected $updatedField  = 'update_at';
    protected $deletedField  = 'delete_at';

    public function listPerIndi($idindi)
    {
        return $this
            ->where('id', $idindi)
            ->where('delete_at=', 0)
            ->get()
            ->getRowArray();
    }
    public function listIndiPerOpd($kdSU)
    {
        return $this
            ->where('kd_sub_unit', $kdSU)
            ->where('delete_at=', 0)
            ->get()
            ->getResultArray();
    }
    public function listIndiPerProgperOpd($kdSK, $kdSU)
    {
        return $this
            ->where('kd_subkegiatan', $kdSK)
            ->where('kd_sub_unit', $kdSU)
            ->where('delete_at=', 0)
            ->get()
            ->getRowArray();
    }
}
