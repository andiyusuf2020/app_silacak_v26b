<?php

namespace App\Models\adbangmodel;

use CodeIgniter\Model;

class Misimodel extends Model
{

    protected $DBGroup              = 'default';

    protected $table                = 'ta_misi_rpjmd';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = [];


    public function lisMisi($id = null)
    {
        if ($id == null) {
            return $this->get()->getResultArray();
        } else {
            return $this
                ->where('id', $id)
                ->get()
                ->getRowArray();
        }
    }
}
