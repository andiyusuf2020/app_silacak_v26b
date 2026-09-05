<?php

namespace App\Models\CapkinModel;

use CodeIgniter\Model;

class TaNomenklaturModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_nomenklatur';
    protected $primaryKey           = 'ID';
    protected $useAutoIncrement     = true;
    protected $allowedFields  = [
        'ID',
        'TAHUN',
        'JENIS_PEMDA',
        'KODE_BIDANG',
        'BIDANGA',
        'KODE_PROGRAM',
        'PROGRAM',
        'KODE_KEGIATAN',
        'KEGIATAN',
        'KODE_SUBKEGIATAN',
        'SUBKEGIATAN',
        'KINERJA',
        'INDIKATOR',
        'SATUAN',
        'TAG',
        'DEFINISI_OPERASIONAL',
        'PELAKSANA',
        'JENIS'
    ];

    public function DataIndikatorSubKeg($tahun, $kdSU)
    {
        return $this
            ->select('*')
            ->where('TAHUN', $tahun)
            ->where('KODE_SUBKEGIATAN', $kdSU)
            ->get()
            ->getResultArray();
    }
}
