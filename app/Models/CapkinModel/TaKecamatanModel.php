<?php

namespace App\Models\CapkinModel;

use CodeIgniter\Model;

class TaKecamatanModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_kecamatan';
    protected $primaryKey           = 'id_kec';
    protected $useAutoIncrement     = true;
    protected $allowedFields  = [
        'id_kec',
        'kode_prov',
        'kode_kec',
        'nama_kecamatan',
    ];

    public function listkecamatan()
    {
        return $this
            ->select('nama_kecamatan')
            ->get()
            ->getResultArray();
    }
}
