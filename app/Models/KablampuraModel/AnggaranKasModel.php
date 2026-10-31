<?php

namespace App\Models\KablampuraModel;

use CodeIgniter\Model;

class AnggaranKasModel extends Model
{
    protected $table            = 'kablampura_anggaran_kas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields    = [
        'perangkat_daerah_id',
        'kode_sub_unit',
        'sub_unit',
        'tahun_anggaran',
        'sub_kegiatan',
        'pagu_anggaran',
        'januari',
        'februari',
        'maret',
        'april',
        'mei',
        'juni',
        'juli',
        'agustus',
        'september',
        'oktober',
        'november',
        'desember',
        'total_anggaran_kas'
    ];
    // Fungsi untuk insert batch data
    public function simpan($data)
    {
        return $this->insert($data);
    }
    public function updatedata($id, $data)
    {
        return $this->update($id, $data);
    }
}
