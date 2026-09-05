<?php

namespace App\Models\CapkinModel;

use CodeIgniter\Model;

class TaRealisasiKegPokokModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_realisasi_kegpokok';
    protected $primaryKey           = 'id_r';
    protected $useAutoIncrement     = true;
    protected $allowedFields  = [
        'id_r',
        'id_targetsubkeg',
        'id_kegpokok',
        'id_progprioritas',
        'id_progunggulan',
        'id_progtematik',
        'tahun',
        'bulan',
        'kd_subunit',
        'r_target',
        'sat_target',
        'r_uraian',
    ];
    protected $useSoftDeletes = true;
    protected $useTimestamps = true; // Tidak menggunakan timestamps
    protected $dateFormat    = 'date';
    protected $createdField  = 'create_at';
    protected $updatedField  = 'update_at';
    protected $deletedField  = 'delete_at';

    public function DataPerKegPokok($idKP)
    {
        return $this
            ->select('ta_realisasi_kegpokok.*')
            ->select('ta_kegpokok_capkin_apbd2.*')
            ->select('ta_subkeg_capkin_apbd.*')
            ->join('ta_subkeg_capkin_apbd', 'ta_subkeg_capkin_apbd.id_sk=ta_realisasi_kegpokok.id_targetsubkeg')
            ->join('ta_kegpokok_capkin_apbd2', 'ta_kegpokok_capkin_apbd2.id_kp=ta_realisasi_kegpokok.id_kegpokok')
            ->where('ta_realisasi_kegpokok.id_kegpokok', $idKP)
            ->where('ta_realisasi_kegpokok.delete_at=', 0)
            ->get()
            ->getResultArray();
    }
    public function DataPerKegPokok26($idKP)
    {
        return $this
            ->select('ta_realisasi_kegpokok.*')
            ->select('ta_kegpokok_capkin_apbd2.id_kp')
            ->select('ta_subkeg_mappingcapkin.id_skcapkin')
            ->join('ta_subkeg_mappingcapkin', 'ta_subkeg_mappingcapkin.id_skcapkin=ta_realisasi_kegpokok.id_targetsubkeg')
            ->join('ta_kegpokok_capkin_apbd2', 'ta_kegpokok_capkin_apbd2.id_kp=ta_realisasi_kegpokok.id_kegpokok')
            ->where('ta_realisasi_kegpokok.id_kegpokok', $idKP)
            ->where('ta_realisasi_kegpokok.delete_at=', 0)
            ->get()
            ->getResultArray();
    }
    public function DataPerID_R($idRKP)
    {
        return $this
            ->select('ta_realisasi_kegpokok.*')
            ->select('ta_kegpokok_capkin_apbd2.*')
            ->select('ta_subkeg_capkin_apbd.*')
            ->select('ta_mprog_prioritas.*')
            ->join('ta_kegpokok_capkin_apbd2', 'ta_kegpokok_capkin_apbd2.id_kp=ta_realisasi_kegpokok.id_kegpokok')
            ->join('ta_subkeg_capkin_apbd', 'ta_subkeg_capkin_apbd.id_sk=ta_realisasi_kegpokok.id_targetsubkeg')
            ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_realisasi_kegpokok.id_progprioritas')
            ->where('ta_realisasi_kegpokok.id_r', $idRKP)
            ->get()
            ->getRowArray();
    }
    public function DataPerID_R26($idRKP)
    {
        return $this
            ->select('ta_realisasi_kegpokok.*')
            ->select('ta_kegpokok_capkin_apbd2.*')
            ->select('ta_subkeg_mappingcapkin.*')
            ->select('ta_mprog_prioritas.*')
            ->join('ta_kegpokok_capkin_apbd2', 'ta_kegpokok_capkin_apbd2.id_kp=ta_realisasi_kegpokok.id_kegpokok')
            ->join('ta_subkeg_mappingcapkin', 'ta_subkeg_mappingcapkin.id_skcapkin=ta_realisasi_kegpokok.id_targetsubkeg')
            ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_realisasi_kegpokok.id_progprioritas')
            ->where('ta_realisasi_kegpokok.id_r', $idRKP)
            ->get()
            ->getRowArray();
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
            ->where('ta_kegpokok_capkin_apbd2.id_kp', $idKP)
            ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
            ->get()
            ->getRowArray();
    }
}
