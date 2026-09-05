<?php

namespace App\Models\CapkinModel;

use CodeIgniter\Model;

class TaMOpdProgUnggulan extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_mopd_progunggulan';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $allowedFields  = [
        'id',
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
