<?php

namespace App\Models;

use CodeIgniter\Model;

class LokasiModel extends Model
{
    protected $table = 'lokasi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama', 'latitude', 'longitude', 'deskripsi', 'gambar', 'alamat', 'kategori'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getLokasi($id = false)
    {
        if ($id === false) {
            return $this->orderBy('created_at', 'DESC')->findAll();
        }

        return $this->where(['id' => $id])->first();
    }

    public function search($keyword)
    {
        return $this->like('nama', $keyword)
            ->orLike('alamat', $keyword)
            ->orLike('deskripsi', $keyword)
            ->orLike('kategori', $keyword)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function getKategori()
    {
        return $this->distinct()->select('kategori')->findAll();
    }
}
