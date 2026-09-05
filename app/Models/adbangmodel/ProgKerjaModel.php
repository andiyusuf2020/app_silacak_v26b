<?php

namespace App\Models\adbangmodel;

use CodeIgniter\Model;

class ProgKerjaModel extends Model
{
    protected $table      = 'ta_program_kerja'; // Nama tabel
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
        'judul_programkerja' => 'required|min_length[15]|max_length[255]',
        'indikator' => 'required|min_length[15]|max_length[255]',
        // 'gambar' => 'max_size[image,1024]|is_image[image]',
        // 'gambar' => 'is_image[image]',

        'ket' => 'required|min_length[15]'
    ];
    protected $validationMessages = [
        'judul_programkerja' => [
            'required' => 'Program Kerja  wajib diisi.',
            'min_length' => 'Program Kerja minimal 15 karakter.',
            'max_length' => 'Program Kerja maksimal 255 karakter.',
        ],
        'indikator' => [
            'required' => 'Indikator  wajib diisi.',
            'min_length' => 'Indikator minimal 15 karakter.',
            'max_length' => 'Indikator maksimal 255 karakter.',
        ],
        //'gambar' => [
        //'uploaded' => 'Gambar wajib diunggah.',
        //'max_size' => 'Ukuran gambar maksimal 1MB.',
        //     'is_image' => 'File yang diunggah harus berupa gambar.',
        //     ],
        'ket' => [
            'required' => 'Deskripsi keterangan   wajib diisi.',
            'min_length' => 'Deskripsi keterangan minimal 15 karakter.',

        ]
    ]; // Pesan validasi
    protected $skipValidation     = false; // Lewati validasi (opsional)


    public function searchRealTime($keyword)
    {
        return $this
            ->like('judul_programkerja', $keyword)
            ->orLike('indikator', $keyword)
            ->orLike('ket', $keyword);
    }
    public function ProgKerjaAll()
    {
        return $this->findAll();
    }
    // Method untuk pagination
    public function getPaginatedData($perPage = 5)
    {
        return $this->paginate($perPage);
    }

    public function listProgKerja($idmisi = null)
    {
        if ($idmisi == null) {
            return $this->where('delete_at', null)->get()->getResultArray();
        } else {
            return $this
                ->where('id_misi', $idmisi)
                ->where('delete_at', null)
                ->get()
                ->getResultArray();
        }
    }
    public function ProgKerja($idpk)
    {
        return $this
            ->where('id', $idpk)
            ->where('delete_at', null)
            ->get()
            ->getRowArray();
    }
}
