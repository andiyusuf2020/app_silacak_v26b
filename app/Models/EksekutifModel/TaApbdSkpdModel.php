<?php

namespace App\Models\EksekutifModel;

use CodeIgniter\Model;

class TaApbdSkpdModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_skpd_prov';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $allowedFields  = false;
    protected $useSoftDeletes = true;
    protected $useTimestamps = true; // Tidak menggunakan timestamps
    protected $dateFormat    = 'date';
    protected $createdField  = 'create_at';
    protected $updatedField  = 'update_at';
    protected $deletedField  = 'delete_at';

    public function DataAll($tahun, $bulan)
    {
        return $this
            ->select('*')
            // ->select('(format((realisasi / pagu),2))')
            ->select('format((realisasi / pagu),3) as isi')
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->where('delete_at=', 0)
            ->where('nama_subunit<>', 'Sekretariat Daerah')
            ->orderBy('isi', 'DESC')
            ->get()
            ->getResultArray();
    }
    public function DataPerOpd($tahun, $bulan, $kd_subunit)
    {
        return $this
            ->select('*')
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->where('kd_subunit', $kd_subunit)
            ->where('delete_at=', 0)
            ->get()
            ->getRowArray();
    }
}
