<?php

namespace App\Models\CapkinModel;

use CodeIgniter\Model;

class TaDesaModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_desa_kelurahan';
    protected $primaryKey           = 'id_desa';
    protected $useAutoIncrement     = true;
    protected $allowedFields  = [
        'id_kec',
        'kode_prov',
        'kode_kec',
        'kode_desa',
        'nm_desa',
    ];

    public function listdesa()
    {
        return $this
            ->select('nm_desa')
            ->get()
            ->getResultArray();
    }
}
