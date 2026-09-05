<?php

namespace App\Models\CapkinModel;

use CodeIgniter\Model;

class TaMOpdTematikModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_mopd_tematik';
    protected $primaryKey           = 'id_opdtematik';
    protected $useAutoIncrement     = true;
    protected $allowedFields  = [
        'id_opdtematik',
        'kd_sub_unit',
        'nm_subunit',
    ];

    public function cekopd($kd_sub_unit)
    {
        return $this
            ->select('nm_subunit')
            ->where('kd_sub_unit', $kd_sub_unit)
            ->get()
            ->getRowArray();
    }
}
