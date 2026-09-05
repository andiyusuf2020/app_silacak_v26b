<?php

namespace App\Models\CapkinModel;

use CodeIgniter\Model;

class TaMProgPrioritasModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_mprog_prioritas';
    protected $primaryKey           = 'id_pprio';
    protected $useAutoIncrement     = true;
    protected $allowedFields  = [
        'id_pprio',
        'misi',
        'nm_progprioritas',
    ];

    public function DataPerId($id)
    {
        return $this
            ->select('*')
            ->where('id_pprio', $id)
            ->get()
            ->getRowArray();
    }
    public function DataPerOpd($tahun, $kd_sub_unit)
    {
        return $this
            ->select('*')
            ->where('tahun', $tahun)
            ->where('kd_subunit', $kd_sub_unit)
            ->where('delete_at=', 0)
            ->get()
            ->getResultArray();
    }
    public function DataPerIdSK($idSK)
    {
        return $this
            ->select('*')
            ->where('id_targetsubkeg', $idSK)
            ->where('delete_at=', 0)
            ->get()
            ->getResultArray();
    }
    public function DataPerIdKegPokok($idKP)
    {
        return $this
            ->select('ta_kegpokok_capkin_apbd2.*')
            ->select('ta_subkeg_capkin_apbd.nm_subkegiatan')
            ->join('ta_subkeg_capkin_apbd', 'ta_subkeg_capkin_apbd.id_sk=ta_kegpokok_capkin_apbd2.id_targetsubkeg')
            ->where('ta_kegpokok_capkin_apbd2.id_sk', $idKP)
            ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
            ->get()
            ->getRowArray();
    }
}
