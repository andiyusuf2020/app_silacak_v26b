<?php

namespace App\Models\KabtanggamusModel;

use CodeIgniter\Model;

class RealisasiPendapatanModel extends Model
{
    protected $table            = 'kabtanggamus_realisasi_pendapatan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'tahun_anggaran',
        'bulan',
        'jenis',
        'kode_rekening',
        'uraian',
        'anggaran',
        'realisasi'
    ];

    protected $useTimestamps = true;

    // Validasi dasar
    protected $validationRules = [
        'tahun_anggaran' => 'required|numeric|exact_length[4]',
        'bulan'          => 'required|numeric|greater_than_equal_to[1]|less_than_equal_to[12]',
        'jenis'          => 'required|in_list[Pendapatan,Belanja]',
        'kode_rekening'  => 'required|string|max_length[50]',
        'uraian'         => 'required|string|max_length[255]',
        'anggaran'       => 'required|numeric',
        'realisasi'      => 'required|numeric',
    ];
    // Mengambil ringkasan data bulan terakhir yang ada di database
    public function getSummaryBulanTerakhir()
    {
        $latest = $this->select('tahun_anggaran, bulan')
            ->orderBy('tahun_anggaran', 'DESC')
            ->orderBy('bulan', 'DESC')
            ->first();

        if (!$latest) {
            return null;
        }

        return $this->getSummaryByPeriode($latest['tahun_anggaran'], $latest['bulan']);
    }

    // METHOD BARU: Mengambil data agregat berdasarkan parameter spesifik
    public function getSummaryByPeriode($tahun, $bulan)
    {
        $builder = $this->db->table($this->table);
        $builder->select('jenis, SUM(anggaran) as total_anggaran, SUM(realisasi) as total_realisasi');
        $builder->where('tahun_anggaran', $tahun);
        $builder->where('bulan', $bulan);
        $builder->groupBy('jenis');

        $result = $builder->get()->getResultArray();

        return [
            'tahun' => $tahun,
            'bulan' => $bulan,
            'data'  => $result
        ];
    }
}
