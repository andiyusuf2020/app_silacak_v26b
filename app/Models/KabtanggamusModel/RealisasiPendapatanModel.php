<?php

namespace App\Models\KabtanggamusModel;

use CodeIgniter\Model;

class RealisasiPendapatanModel extends Model
{
    protected $table            = 'kabtanggamus_realisasi_pendapatan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes = true;

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
    protected $createdField = 'CREATE_AT';
    protected $updatedField = 'UPDATE_AT';

    // Ambil list data berdasarkan filter tahun & bulan
    public function getDataFilter($tahun, $bulan = 'all')
    {
        $builder = $this->builder();
        $builder->where('tahun_anggaran', $tahun);

        if ($bulan !== 'all' && $bulan !== '') {
            $builder->where('bulan', $bulan);
        }

        $builder->orderBy('bulan', 'ASC');
        $builder->orderBy('jenis', 'ASC');
        $builder->orderBy('kode_rekening', 'ASC');

        return $builder->get()->getResultArray();
    }
    public function getTrenPersentaseBulanan($tahun)
    {
        $builder = $this->db->table($this->table);
        $builder->select('bulan, jenis, SUM(anggaran) as total_anggaran, SUM(realisasi) as total_realisasi');
        $builder->where('tahun_anggaran', $tahun);
        $builder->groupBy(['bulan', 'jenis']);
        $builder->orderBy('bulan', 'ASC');

        $rows = $builder->get()->getResultArray();

        // Inisialisasi array 12 bulan (default 0%)
        $pendapatanPct = array_fill(1, 12, 0);
        $belanjaPct    = array_fill(1, 12, 0);

        foreach ($rows as $row) {
            $b = (int) $row['bulan'];
            $anggaran  = (float) $row['total_anggaran'];
            $realisasi = (float) $row['total_realisasi'];
            $pct       = $anggaran > 0 ? round(($realisasi / $anggaran) * 100, 2) : 0;

            if ($row['jenis'] === 'Pendapatan') {
                $pendapatanPct[$b] = $pct;
            } else if ($row['jenis'] === 'Belanja') {
                $belanjaPct[$b] = $pct;
            }
        }

        return [
            'pendapatan' => array_values($pendapatanPct),
            'belanja'    => array_values($belanjaPct)
        ];
    }
    // Mengambil ringkasan data bulan terakhir yang ada di database
    public function getSummaryBulanTerakhir()
    {
        // Cari periode bulan & tahun paling baru
        $latest = $this->select('tahun_anggaran, bulan')
            ->orderBy('tahun_anggaran', 'DESC')
            ->orderBy('bulan', 'DESC')
            ->first();

        if (!$latest) {
            return null;
        }

        // Hitung total anggaran & realisasi per jenis (Pendapatan & Belanja)
        $builder = $this->db->table($this->table);
        $builder->select('jenis, SUM(anggaran) as total_anggaran, SUM(realisasi) as total_realisasi');
        $builder->where('tahun_anggaran', $latest['tahun_anggaran']);
        $builder->where('bulan', $latest['bulan']);
        $builder->groupBy('jenis');

        $result = $builder->get()->getResultArray();

        return [
            'tahun' => $latest['tahun_anggaran'],
            'bulan' => $latest['bulan'],
            'data'  => $result
        ];
    }
}
