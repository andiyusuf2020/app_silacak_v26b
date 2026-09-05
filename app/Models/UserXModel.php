<?php

namespace App\Models;

use CodeIgniter\Model;

class UserXModel extends Model
{
    protected $table = 'usersx';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'created_at'];
    protected $useTimestamps = false;

    public function getUsers($limit, $offset)
    {
        return $this->orderBy('id', 'DESC')
            ->limit($limit, $offset)
            ->findAll();
    }

    public function countAllUsers()
    {
        return $this->countAll();
    }
}
