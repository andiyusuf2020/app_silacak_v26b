<?php

namespace App\Models\CapkinModel;

use CodeIgniter\Model;

class TaKategoriModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_kategori_dokumentasi';
    protected $primaryKey           = 'id_kategori';
    protected $useAutoIncrement     = true;
    protected $allowedFields  = [
        'id_kategori',
        'nm_kategori',
        'keterangan',
    ];

    public function listkategori()
    {
        return $this
            ->select('nm_kategori')
            ->get()
            ->getResultArray();
    }
}
