<?php

namespace App\Models\UserModel;

use CodeIgniter\Model;

class TaUserModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'users';
    protected $primaryKey           = 'id';
    protected $returnType           = 'object';
    protected $protectFields        = true;
    protected $allowedFields        = ['id', 'kd_skpd', 'kd_sub_unit', 'sub_unit', 'email', 'username', 'nama', 'nip', 'jabatan', 'active'];

    // Pesan validasi    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    public function searchRealTime($keyword)
    {
        return $this
            ->select('*')

            // ->select('users.*,auth_groups.*,auth_groups_users.*')
            ->join('auth_groups_users', 'users.id=auth_groups_users.user_id')
            ->join('auth_groups', 'auth_groups.id=auth_groups_users.group_id')
            ->where('users.deleted_at', null)
            ->like('users.email', $keyword)
            ->orLike('auth_groups.name', $keyword)
            ->orLike('users.username', $keyword)
            ->orLike('users.sub_unit', $keyword)
            ->get()->getResultArray(); //->findAll();
    }
    public function listuser($id = null)

    {
        //return $this->get()->getResultArray();
        if ($id == null) {
            return $this
                ->select('*')
                ->where('deleted_at', null)->get()->getResultArray();
            // ->select('users.*,auth_groups.*,auth_groups_users.*')
            // ->join('auth_groups_users', 'users.id=auth_groups_users.user_id')
            // ->join('auth_groups', 'auth_groups.id=auth_groups_users.group_id')
            // ->get()->getResultArray(); //where('deleted_at=', 0)->get()->
            //findAll(); //where('deleted_at=', 0)->get()->getResultArray(); //findAll();
        }
        if ($id) {
            return $this->where('id', $id)->get()->getRowArray();
        }
    }

    public function getDataUser($name)
    {
        return $this->select('*')->where('username', $name)->orWhere('email', $name)->get();
    }
    public function getDataProfile($name)
    {
        $kosong = '';
        return $this->select('*')->where('username', $name)->orWhere('email', $name)->get();
    }
    public function getIdUser($name)
    {
        return $this->select('id')->where('username', $name)->orWhere('email', $name)->get();
    }
    public function UpdateUserProfile($id, $dataprofile)
    {
        return $this->update(['id' => $id], $dataprofile);
    }
    public function cekProfileUser($email = null)
    {
        //return $this select('nama,nip,jabatan')->
    }
}
