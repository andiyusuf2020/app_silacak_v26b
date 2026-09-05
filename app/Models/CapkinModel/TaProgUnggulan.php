<?php

namespace App\Models\CapkinModel;

use CodeIgniter\Model;

class TaProgUnggulan extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_mprog_unggulan';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $allowedFields  = [
        'id',
        'nm_progunggulan',
    ];

    public function listprogunggulan()
    {
        return $this
            ->select('nm_progunggulan')
            ->get()
            ->getResultArray();
    }
    public function DataPerId($id)
    {
        return $this
            ->select('nm_progunggulan')
            ->where('id', $id)
            ->get()
            ->getRowArray();
    }
}
