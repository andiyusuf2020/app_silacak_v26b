<?php

namespace App\Models\ApbnModel;

use CodeIgniter\Model;

class danaapbnModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_danaapbn';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $allowedFields  = [
        'id',
        'tahun',
        'bulan',
        'kd_subunit',
        'nm_subunit',
        'dana_dekon',
        'real_dekon',
        'dana_tp',
        'real_tp',
        'ket',
    ];
    public function DataAll($tahun)
    {
        return $this
            ->select('*')
            ->where('tahun', $tahun)
            ->get()
            ->getResultArray();
    }
    public function Dataperbulan($tahun, $bulan)
    {
        return $this
            ->select('*')
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->get()
            ->getResultArray();
    }
    public function DataPerOpd($tahun, $nm_subunit)
    {
        return $this
            ->select('*')
            ->where('tahun', $tahun)
            ->where('nm_subunit', $nm_subunit)
            ->get()
            ->getResultArray();
    }
}
