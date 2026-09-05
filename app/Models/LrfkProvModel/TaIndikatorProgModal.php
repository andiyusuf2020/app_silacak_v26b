<?php

namespace App\Models\LrfkProvModel;

use CodeIgniter\Model;

class TaIndikatorProgModal extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_indi_iku_progopd';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $allowedFields  = [
        'id',
        'tahun',
        'kd_sub_unit',
        'sub_unit',
        'kd_program',
        'nm_program',
        'indikator',
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
    public function listIndiPerProgperOpd($kdP, $kdSU)
    {
        return $this
            ->where('kd_program', $kdP)
            ->where('kd_sub_unit', $kdSU)
            ->where('delete_at=', 0)
            ->get()
            ->getRowArray();
    }
}
