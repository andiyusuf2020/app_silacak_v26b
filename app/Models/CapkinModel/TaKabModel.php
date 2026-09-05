<?php

namespace App\Models\CapkinModel;

use CodeIgniter\Model;

class TaKabModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_kab';
    protected $primaryKey           = 'id_kab';
    protected $useAutoIncrement     = true;
    protected $allowedFields  = [
        'id_kab',
        'kode',
        'nama',
    ];

    public function listkab()
    {
        return $this
            ->select('nama')
            ->get()
            ->getResultArray();
    }
    public function listkecamatan()
    {
        return $this->table('ta_kecamatan')
            ->select('ta_kecamatan.nama_kecamatan')
            ->get()
            ->getResultArray();
    }
}
