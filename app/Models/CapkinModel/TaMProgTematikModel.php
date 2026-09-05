<?php

namespace App\Models\CapkinModel;

use CodeIgniter\Model;

class TaMProgTematikModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_mprog_tematik';
    protected $primaryKey           = 'id_tematik';
    protected $useAutoIncrement     = true;
    protected $allowedFields  = [
        'id_tematik',
        'nm_tematik',
    ];

    public function listtematik()
    {
        return $this
            ->select('nm_tematikk')
            ->get()
            ->getResultArray();
    }
}
