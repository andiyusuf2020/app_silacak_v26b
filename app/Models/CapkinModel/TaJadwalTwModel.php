<?php

namespace App\Models\CapkinModel;

use CodeIgniter\Model;

class TaJadwalTwModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_jadwaltw';
    protected $primaryKey           = 'id_tw';
    protected $useAutoIncrement     = true;
    protected $allowedFields  = [
        'id_tw',
        'tahun',
        'tw',
        'status',
    ];

    public function twaktif()
    {
        return $this
            ->select('tw')
            ->where('status', 1)
            ->get()
            ->getRowArray();
    }
}
