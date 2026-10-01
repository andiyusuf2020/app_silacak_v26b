<?php

namespace App\Models\UserModel;

use CodeIgniter\Model;
use Myth\Auth\Models\GroupModel as ModelGroup;

class GroupUserModel extends ModelGroup
{
    protected $DBGroup              = 'default';
    protected $table                = 'auth_groups';
    protected $primaryKey           = 'id';
    protected $returnType           = 'array';
    protected $protectFields        = true;
    protected $allowedFields        = ['id', 'name', 'description'];

    // Pesan validasi    protected $skipValidation       = false;
    // protected $cleanValidationRules = true;

    public function listgroupkabkota()
    {
        return $this->select('*')
            ->where('name<>', 'superadmin')->get()->getResultArray();
    }
}
