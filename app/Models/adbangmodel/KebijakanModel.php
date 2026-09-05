<?php

namespace App\Models\adbangmodel;

use CodeIgniter\Model;

class KebijakanModel extends Model
{
    protected $table      = 'ta_arah_kebijakan'; // Nama tabel
    protected $primaryKey = 'id';      // Primary key tabel

    protected $useAutoIncrement = true;

    protected $returnType     = 'array'; // Tipe data yang dikembalikan
    protected $useSoftDeletes = true;
    protected $protectFields  = false;
    protected $allowedFields = []; // Kolom yang boleh diisi

    protected $useTimestamps = true; // Tidak menggunakan timestamps
    protected $dateFormat    = 'date';
    protected $createdField  = 'create_at';
    protected $updatedField  = 'update_at';
    protected $deletedField  = 'delete_at';

    protected $validationRules    = [
        //'id_misi' => 'required|numeric',
        //       'tahun' => 'required|numeric',
        'judul_kebijakan' => 'required|min_length[15]|max_length[255]',
        // 'gambar' => 'max_size[image,1024]|is_image[image]',
        // 'gambar' => 'is_image[image]',

        'ket' => 'required|min_length[15]'
    ];
    protected $validationMessages = [
        'judul_kebijakan' => [
            'required' => 'Arah Kebijakan wajib diisi.',
            'min_length' => 'Arah Kebijakan minimal 15 karakter.',
            'max_length' => 'Arah Kebijakan maksimal 255 karakter.',
        ],
        'ket' => [
            'required' => 'Deskripsi/Keterangan keterangan   wajib diisi.',
            'min_length' => 'Deskripsi/Keterangan minimal 15 karakter.',

        ]
    ]; // Pesan validasi
    protected $skipValidation     = false; // Lewati validasi (opsional)

    // Method untuk pagination
    public function getPaginatedData($perPage = 2)
    {
        return $this->paginate($perPage);
    }

    public function listArahKeb($idpk)
    {
        return $this
            ->where(' id_progkerja', $idpk)
            ->where('delete_at', null)
            ->get()
            ->getResultArray();
    }
    public function listArahKebRow($idpk)
    {
        return $this
            ->where(' id_progkerja', $idpk)
            ->where('delete_at', null)
            ->get()
            ->getResult();
    }
}
