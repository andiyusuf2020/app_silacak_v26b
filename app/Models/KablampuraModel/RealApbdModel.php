<?php

namespace App\Models\KablampuraModel;

use CodeIgniter\Model;

class RealApbdModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_sipd_lampungutara';
    protected $primaryKey           = 'NO';
    protected $useAutoIncrement     = true;
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'NO',
        'TAHUN',
        'BULAN',
        'KODE_SKPD',
        'NAMA_SKPD',
        'KODE_UNIT_SKPD',
        'NAMA_UNIT_SKPD',
        'KODE_URUSAN',
        'NAMA_URUSAN',
        'KODE_BIDANG_URUSAN',
        'NAMA_BIDANG_URUSAN',
        'KODE_PROGRAM',
        'NAMA_PROGRAM',
        'KODE_GIAT',
        'NAMA_GIAT',
        'KODE_SUB_GIAT',
        'NAMA_SUB_GIAT',
        'KODE_AKUN',
        'NAMA_AKUN',
        'KODE_KELOMPOK',
        'NAMA_KELOMPOK',
        'KODE_JENIS',
        'NAMA_JENIS',
        'KODE_OBJEK',
        'NAMA_OBJEK',
        'KODE_RINCIAN_OBJEK',
        'NAMA_RINCIAN_OBJEK',
        'KODE_SRO',
        'NAMA_SRO',
        'TOTAL_ANGGARAN',
        'TOTAL_REALISASI',
        'SELISIH',
        'KETERANGAN',
        'REALISASI_SPJ',
        'CREATE_AT',
        'UPDATE_AT',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'CREATE_AT';
    protected $updatedField = 'UPDATE_AT';

    public function tgldata()
    {
        $q = $this
            ->select('CREATE_AT,BULAN')
            ->where('tahun', session()->get('tahun'))
            ->groupBy('CREATE_AT')
            ->get()
            ->getResultArray();
        return $q;
    }
    public function getCapaianKinerjaperopd($tglaktif, $kdsu)
    {
        $builder = $this->db->table($this->table);

        // Hitung total anggaran keseluruhan
        $totalAnggaranKeseluruhan = $builder->selectSum('TOTAL_ANGGARAN')
            ->selectcount('KODE_UNIT_SKPD', 'JumlahBelanja')
            ->where('KODE_UNIT_SKPD', $kdsu)
            ->get()->getRow()->TOTAL_ANGGARAN;

        // Query utama
        $builder->select('KODE_UNIT_SKPD, NAMA_UNIT_SKPD,KODE_PROGRAM');
        $builder->selectSum('TOTAL_ANGGARAN', 'TotalAnggaran');
        $builder->selectSum('TOTAL_REALISASI', 'TotalRealisasi');
        $builder->where('KODE_UNIT_SKPD', $kdsu);
        $builder->where('tahun', session()->get('tahun'));
        $builder->where('CREATE_AT', $tglaktif);
        $builder->groupBy('NAMA_PROGRAM');

        $result = $builder->get()->getResultArray();
        $persentaseList = [];

        foreach ($result as &$row) {
            if ($row['TotalRealisasi'] > 0) { // hanya dihitung jika realisasi > 0
                $row['PersentaseRealisasi'] = ($row['TotalRealisasi'] / $row['TotalAnggaran']) * 100;
                $row['BobotAnggaran'] = ($row['TotalAnggaran'] / $totalAnggaranKeseluruhan) * 100;
                $row['SkorCapaian'] = $row['PersentaseRealisasi'] * $row['BobotAnggaran'];

                $persentaseList[] = $row['PersentaseRealisasi'];
            } else {
                $row['PersentaseRealisasi'] = 0;
                $row['BobotAnggaran'] = 0;
                $row['SkorCapaian'] = 0;
            }
        }

        // Hitung rata-rata geometri hanya dari persentase > 0
        $n = count($persentaseList);
        $product = $n > 0 ? array_product($persentaseList) : 0;
        $geometricMean = $n > 0 ? pow($product, 1 / $n) : 0;
        //hitung total realisasi keseluruhan
        $totalRealisasiKeseluruhan = $builder->selectSum('TOTAL_REALISASI')->get()->getRow()->TOTAL_REALISASI;
        $persentaseRealisasiKeseluruhan = $totalAnggaranKeseluruhan > 0 ? ($totalRealisasiKeseluruhan / $totalAnggaranKeseluruhan) * 100 : 0;

        return [
            'data' => $result,
            'geometric_mean' => $geometricMean,
            'total_realisasi_keseluruhan' => $totalRealisasiKeseluruhan,
            'total_anggaran_keseluruhan' => $totalAnggaranKeseluruhan,
            'persentase_realisasi_keseluruhan' => $persentaseRealisasiKeseluruhan
        ];
    }
    public function getCapaianKinerjaDenganGeometri($tahun, $tglaktif)
    {
        $builder = $this->db->table($this->table);

        // Hitung total anggaran keseluruhan
        $totalAnggaranKeseluruhan = $builder->selectSum('TOTAL_ANGGARAN')->get()->getRow()->TOTAL_ANGGARAN;

        // Query utama
        $builder->select('*');
        $builder->selectSum('TOTAL_ANGGARAN', 'TotalAnggaran');
        $builder->selectSum('TOTAL_REALISASI', 'TotalRealisasi');
        $builder->selectCount('TOTAL_ANGGARAN', 'JumlahBelanja');
        $builder->where('tahun', $tahun);
        $builder->where('CREATE_AT', $tglaktif);
        $builder->groupBy('NAMA_UNIT_SKPD');
        $builder->orderBy('TOTAL_REALISASI', 'ASC');
        $result = $builder->get()->getResultArray();

        $persentaseList = [];

        foreach ($result as &$row) {
            if ($row['TotalRealisasi'] > 0) { // hanya dihitung jika realisasi > 0
                $row['PersentaseRealisasi'] = ($row['TotalRealisasi'] / $row['TotalAnggaran']) * 100;
                $row['BobotAnggaran'] = ($row['TotalAnggaran'] / $totalAnggaranKeseluruhan) * 100;
                $row['SkorCapaian'] = $row['PersentaseRealisasi'] * $row['BobotAnggaran'];

                $persentaseList[] = $row['PersentaseRealisasi'];
            } else {
                $row['PersentaseRealisasi'] = 0;
                $row['BobotAnggaran'] = 0;
                $row['SkorCapaian'] = 0;
            }
        }

        // Hitung rata-rata geometri hanya dari persentase > 0
        $n = count($persentaseList);
        $product = $n > 0 ? array_product($persentaseList) : 0;
        $geometricMean = $n > 0 ? pow($product, 1 / $n) : 0;
        //hitung total realisasi keseluruhan
        $totalRealisasiKeseluruhan = $builder->selectSum('TOTAL_REALISASI')->get()->getRow()->TOTAL_REALISASI;
        $persentaseRealisasiKeseluruhan = $totalAnggaranKeseluruhan > 0 ? ($totalRealisasiKeseluruhan / $totalAnggaranKeseluruhan) * 100 : 0;

        return [
            'data' => $result,
            'geometric_mean' => $geometricMean,
            'total_realisasi_keseluruhan' => $totalRealisasiKeseluruhan,
            'total_anggaran_keseluruhan' => $totalAnggaranKeseluruhan,
            'persentase_realisasi_keseluruhan' => $persentaseRealisasiKeseluruhan
        ];
    }
    public function listopduserreg($tglaktif)
    {
        $q = $this
            ->select('NAMA_UNIT_SKPD')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            ->selectSum('REALISASI_SPJ', 'realisasi_spj')
            ->where('tahun', date('Y'))
            ->where('CREATE_AT', $tglaktif)
            ->groupBy('NAMA_UNIT_SKPD')
            ->get()
            ->getResultArray();
        return $q;
    }
    public function listopdadmin($tglaktif, $nm_opd = null)
    {
        if ($nm_opd == null) {
            $q = $this
                ->select('*')
                ->selectSum('TOTAL_REALISASI', 'realisasi')
                ->selectSum('TOTAL_ANGGARAN', 'anggaran')
                ->selectSum('REALISASI_SPJ', 'realisasi_spj')
                ->where('tahun', session()->get('tahun'))
                ->where('CREATE_AT', $tglaktif)
                // ->where('TOTAL_ANGGARAN <>', 0)
                // ->where('TOTAL_REALISASI <>', 0)
                // // ->select('kode_skpd, nama_skpd, total_anggaran, realisasi')
                // // ->select('(realisasi / total_anggaran * 100) as persentase_realisasi')
                // ->select('(total_anggaran / (SELECT SUM(total_anggaran) FROM apbd) * 100) as bobot_anggaran')
                // ->select('((realisasi / total_anggaran * 100) * (total_anggaran / (SELECT SUM(total_anggaran) FROM apbd) * 100)) as skor_capaian')
                ->groupBy('NAMA_UNIT_SKPD')
                ->get()
                ->getResultArray();
            return $q;
        } else {
            $q = $this
                ->select('*')
                ->selectSum('TOTAL_REALISASI', 'realisasi')
                ->selectSum('TOTAL_ANGGARAN', 'anggaran')
                ->selectSum('REALISASI_SPJ', 'realisasi_spj')
                ->where('tahun', session()->get('tahun'))
                ->where('CREATE_AT', $tglaktif)
                ->where('NAMA_UNIT_SKPD', $nm_opd)
                ->groupBy('NAMA_UNIT_SKPD')
                ->get()
                ->getResultArray();
            return $q;
        }
    }
    public function listopd($nm_opd = null)
    {
        if ($nm_opd == null) {
            $q = $this
                ->select('*')
                ->selectSum('TOTAL_REALISASI', 'realisasi')
                ->selectSum('TOTAL_ANGGARAN', 'anggaran')
                ->selectSum('REALISASI_SPJ', 'realisasi_spj')
                ->where('tahun', session()->get('tahun'))
                ->where('CREATE_AT', session()->get('tglaktif'))
                ->groupBy('NAMA_UNIT_SKPD')
                ->get()
                ->getResultArray();
            return $q;
        } else {
            $q = $this
                ->select('*')
                ->selectSum('TOTAL_REALISASI', 'realisasi')
                ->selectSum('TOTAL_ANGGARAN', 'anggaran')
                ->selectSum('REALISASI_SPJ', 'realisasi_spj')
                ->where('tahun', session()->get('tahun'))
                ->where('CREATE_AT', session()->get('tglaktif'))
                ->where('NAMA_UNIT_SKPD', $nm_opd)
                ->groupBy('NAMA_UNIT_SKPD')
                ->get()
                ->getResultArray();
            return $q;
        }
    }
    public function dataopd($nm_opd)
    {
        $q = $this
            ->select('*')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            ->where('tahun', session()->get('tahun'))
            ->where('CREATE_AT', session()->get('tglaktif'))
            ->where('NAMA_UNIT_SKPD', $nm_opd)
            ->groupBy('NAMA_UNIT_SKPD')
            ->get()
            ->getRowArray();
        return $q;
    }
    public function dataopd2025($nm_opd)
    {
        $q = $this
            ->select('*')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            ->where('tahun', session()->get('tahun'))
            ->where('CREATE_AT', '2026-07-29')
            ->where('NAMA_UNIT_SKPD', $nm_opd)
            ->groupBy('NAMA_UNIT_SKPD')
            ->get()
            ->getRowArray();
        return $q;
    }
    public function rperopdperbulan($kdSU, $tahun)
    {
        $q = $this
            ->select('*')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            ->selectSum('REALISASI_SPJ', 'realisasi_spj')
            ->select('format((sum(TOTAL_REALISASI) / sum(TOTAL_ANGGARAN)) * 100, 2) as persen')
            ->where('TAHUN', $tahun)
            ->where('KODE_UNIT_SKPD', $kdSU)
            ->groupBy('BULAN')
            ->get()
            ->getResultArray();
        return $q;
    }
    public function rpersk($nm_opd, $tahun, $bulan, $tglaktif)
    {
        $q = $this
            ->select('*')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            ->selectSum('REALISASI_SPJ', 'realisasi_spj')
            ->where('TAHUN', $tahun)
            ->where('BULAN', $bulan)
            ->where('CREATE_AT', $tglaktif)
            ->where('NAMA_UNIT_SKPD', $nm_opd)
            ->groupBy('NAMA_SUB_GIAT')
            ->orderBy('KODE_SUB_GIAT', 'asc')
            ->get()
            ->getResultArray();
        return $q;
    }
    public function rperskperopd($kdSU, $kdSK, $tahun, $bulan, $tglaktif)
    {
        $q = $this
            ->select('*')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            ->selectSum('REALISASI_SPJ', 'realisasi_spj')
            ->where('TAHUN', $tahun)
            ->where('BULAN', $bulan)
            ->where('CREATE_AT', $tglaktif)
            ->where('KODE_UNIT_SKPD', $kdSU)
            ->where('KODE_SUB_GIAT', $kdSK)
            ->groupBy('NAMA_SUB_GIAT')
            ->orderBy('KODE_SUB_GIAT', 'asc')
            ->get()
            ->getRowArray();
        return $q;
    }
    public function rperskperopdperbulan($kdSU, $kdSK, $tahun)
    {
        $q = $this
            ->select('*')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            ->selectSum('REALISASI_SPJ', 'realisasi_spj')
            ->where('TAHUN', $tahun)
            // ->where('BULAN', $bulan)
            ->where('KODE_UNIT_SKPD', $kdSU)
            ->where('KODE_SUB_GIAT', $kdSK)
            ->groupBy('BULAN')
            // ->orderBy('KODE_SUB_GIAT', 'asc')
            ->get()
            ->getResultArray();
        return $q;
    }
    public function rperrso($tahun, $tgldata, $kdSU, $kdSK)
    {
        $q = $this
            ->select('*')
            ->where('TAHUN', $tahun)
            ->where('CREATE_AT', $tgldata)
            ->where('KODE_UNIT_SKPD', $kdSU)
            ->where('KODE_SUB_GIAT', $kdSK)
            ->orderBy('KODE_SRO', 'asc')
            ->get();
        // ->getResultArray();
        return $q;
    }
    public function rperrso2($kdSU, $kdSK, $tahun, $tgldata)
    {
        $q = $this
            ->select('*')
            ->where('TAHUN', $tahun)
            ->where('CREATE_AT', $tgldata)
            ->where('NAMA_UNIT_SKPD', $kdSU)
            ->where('KODE_SUB_GIAT', $kdSK)
            ->orderBy('KODE_SRO', 'asc')
            ->get();
        // ->getResultArray();
        return $q;
    }
    public function rperkelbel($nm_opd, $tahun, $bulan, $tglaktif, $kelbel)
    {
        $q = $this
            ->select('*')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            ->where('TAHUN', $tahun)
            ->where('BULAN', $bulan)
            ->where('CREATE_AT', $tglaktif)
            ->where('NAMA_UNIT_SKPD', $nm_opd)
            ->like('KODE_SRO', $kelbel)
            // ->groupBy('NAMA_SUB_GIAT')
            ->get()
            ->getRowArray();
        return $q;
    }
    public function rperrob($nm_opd, $tahun, $bulan, $tglaktif, $rso)
    {
        $q = $this
            ->select('*')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            // ->where('TOTAL_ANGGARAN <>', 0)
            // ->where('TOTAL_REALISASI <>', 0)
            ->where('TAHUN', $tahun)
            ->where('BULAN', $bulan)
            ->where('CREATE_AT', $tglaktif)
            ->where('NAMA_UNIT_SKPD', $nm_opd)
            ->like('NAMA_SRO', $rso)
            // ->groupBy('NAMA_UNIT_SKPD')
            ->get()
            ->getRowArray();
        return $q;
    }
    public function listprogram($nm_opd, $tahun, $bulan, $tglaktif)
    {
        $q = $this
            ->select('*')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            ->selectSum('REALISASI_SPJ', 'realisasi_spj')
            // ->where('TOTAL_ANGGARAN <>', 0)
            // ->where('TOTAL_REALISASI <>', 0)
            ->where('TAHUN', $tahun)
            ->where('BULAN', $bulan)
            ->where('CREATE_AT', $tglaktif)
            ->where('NAMA_UNIT_SKPD', $nm_opd)
            ->groupBy('NAMA_PROGRAM')
            ->orderBy('KODE_PROGRAM', 'asc')
            ->get()
            ->getResultArray();
        return $q;
    }
    public function liskegiatan($nm_opd, $tahun, $tglaktif, $kdprogram)
    {
        $q = $this
            ->select('*')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            ->selectSum('REALISASI_SPJ', 'realisasi_spj')

            // ->where('TOTAL_ANGGARAN <>', 0)
            // ->where('TOTAL_REALISASI <>', 0)
            ->where('TAHUN', $tahun)
            // ->where('BULAN', $bulan)
            ->where('CREATE_AT', $tglaktif)
            ->where('NAMA_UNIT_SKPD', $nm_opd)
            ->where('KODE_PROGRAM', $kdprogram)
            ->groupBy('KODE_GIAT')
            ->orderBy('KODE_GIAT', 'asc')
            ->get()
            ->getResultArray();
        return $q;
    }
    public function lissubkegiatan($nm_opd, $tahun,  $tglaktif, $kdprogram, $kdgiat)
    {
        $q = $this
            ->select('*')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            ->selectSum('REALISASI_SPJ', 'realisasi_spj')
            // ->where('TOTAL_ANGGARAN <>', 0)
            // ->where('TOTAL_REALISASI <>', 0)
            ->where('TAHUN', $tahun)
            // ->where('BULAN', $bulan)
            ->where('CREATE_AT', $tglaktif)
            ->where('NAMA_UNIT_SKPD', $nm_opd)
            ->where('KODE_PROGRAM', $kdprogram)
            ->where('KODE_GIAT', $kdgiat)
            ->groupBy('NAMA_SUB_GIAT')
            ->orderBy('KODE_SUB_GIAT', 'asc')
            ->get()
            ->getResultArray();
        return $q;
    }
    public function lissubkegiatan2($nm_opd, $tahun,  $tglaktif)
    {
        $q = $this
            ->select('*')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            ->selectSum('REALISASI_SPJ', 'realisasi_spj')
            // ->where('TOTAL_ANGGARAN <>', 0)
            // ->where('TOTAL_REALISASI <>', 0)
            ->where('TAHUN', $tahun)
            // ->where('BULAN', $bulan)
            ->where('CREATE_AT', $tglaktif)
            ->where('NAMA_UNIT_SKPD', $nm_opd)
            // ->where('KODE_PROGRAM', $kdprogram)
            // ->where('KODE_GIAT', $kdgiat)
            ->groupBy('NAMA_SUB_GIAT')
            ->orderBy('KODE_SUB_GIAT', 'asc')
            ->get()
            ->getResultArray();
        return $q;
    }
    public function rperrsoopd($nm_opd, $tahun, $bulan, $tglaktif, $kdprogram, $kdgiat, $kdsubgiat)
    {
        $q = $this
            ->select('KODE_SRO, NAMA_SRO')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            // ->where('TOTAL_ANGGARAN <>', 0)
            // ->where('TOTAL_REALISASI <>', 0)
            ->where('TAHUN', $tahun)
            ->where('BULAN', $bulan)
            ->where('CREATE_AT', $tglaktif)
            ->where('NAMA_UNIT_SKPD', $nm_opd)
            ->where('KODE_PROGRAM', $kdprogram)
            ->where('KODE_GIAT', $kdgiat)
            ->where('KODE_SUB_GIAT', $kdsubgiat)
            ->groupBy('NAMA_SRO')
            ->orderBy('KODE_SRO', 'asc')
            ->get()
            ->getResultArray();
        return $q;
    }
}
