<?php

namespace App\Models\LrfkProvModel;

use CodeIgniter\Model;

class JadwalModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_jadwallrfk';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $allowedFields  = [
        'id',
        'tahun',
        'bulan',
        'status',
    ];
    public function getlist()
    {
        return $this
            ->select('*')
            ->get()
            ->getResultArray();
    }
    public function jadwal()
    {
        return $this
            ->where('status', '0')
            ->get()
            ->getResultArray();
    }
    public function bulan($tahunaktif)
    {
        return $this
            ->select('*')
            ->where('tahun', $tahunaktif)
            // ->where('status', '0')
            ->get()
            ->getResultArray();
    }

    public function jadwalaktifskrg()
    {
        return $this
            ->where('status', '1')
            ->get()
            ->getRowArray();
    }
    public function jdwlygdiaktifkan($id)
    {
        return $this
            //       ->select('*')
            ->where('id', $id)
            ->get()
            ->getRowArray();
    }
}
