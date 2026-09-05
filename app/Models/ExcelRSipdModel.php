<?php

namespace App\Models;

use CodeIgniter\Model;

class ExcelRSipdModel extends Model
{
    protected $table = 'ta_lap_realisasi_apbd';
    protected $primaryKey = 'NO';
    protected $returnType     = 'array'; // Tipe data yang dikembalikan
    protected $allowedFields = [
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
    ];
    protected $useTimestamps = true;
    protected $createdField = 'CREATE_AT';
    protected $updatedField = 'UPDATE_AT';


    // Fungsi untuk insert batch data
    public function insertBatchData(array $data)
    {
        return $this->insertBatch($data);
    }
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
}
