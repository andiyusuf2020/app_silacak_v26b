<?php

namespace App\Models\CapkinModel;

use CodeIgniter\Model;

class TaPermasalahanAktifitas extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_permasalahan_aktifitas';
    protected $primaryKey           = 'id_Pr';
    protected $useAutoIncrement     = true;
    protected $allowedFields  = [
        'id_Pr',
        'id_targetsubkeg',
        'id_kegpokok',
        'id_progprioritas',
        'id_progunggulan',
        'id_progtematik',
        'tahun',
        'bulan',
        'permasalahan',
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
            ->select('ta_permasalahan_aktifitas.*')
            ->select('ta_kegpokok_capkin_apbd2.*')
            ->select('ta_subkeg_capkin_apbd.*')
            ->join('ta_subkeg_capkin_apbd', 'ta_subkeg_capkin_apbd.id_sk=ta_permasalahan_aktifitas.id_targetsubkeg')
            ->join('ta_kegpokok_capkin_apbd2', 'ta_kegpokok_capkin_apbd2.id_kp=ta_permasalahan_aktifitas.id_kegpokok')
            ->where('ta_permasalahan_aktifitas.id_kegpokok', $idKP)
            ->where('ta_permasalahan_aktifitas.delete_at=', 0)
            ->get()
            ->getResultArray();
    }
    public function DataPerKegPokok26($idKP)
    {
        return $this
            ->select('ta_permasalahan_aktifitas.*')
            ->select('ta_kegpokok_capkin_apbd2.id_kp')
            ->select('ta_subkeg_mappingcapkin.id_skcapkin')
            ->join('ta_subkeg_mappingcapkin', 'ta_subkeg_mappingcapkin.id_skcapkin=ta_permasalahan_aktifitas.id_targetsubkeg')
            ->join('ta_kegpokok_capkin_apbd2', 'ta_kegpokok_capkin_apbd2.id_kp=ta_permasalahan_aktifitas.id_kegpokok')
            ->where('ta_permasalahan_aktifitas.id_kegpokok', $idKP)
            ->where('ta_permasalahan_aktifitas.delete_at=', 0)
            ->get()
            ->getResultArray();
    }
    public function DataPerID_R($idPR)
    {
        return $this
            ->select('ta_permasalahan_aktifitas.*')
            ->select('ta_kegpokok_capkin_apbd2.*')
            ->select('ta_subkeg_capkin_apbd.*')
            ->select('ta_mprog_prioritas.*')
            ->join('ta_kegpokok_capkin_apbd2', 'ta_kegpokok_capkin_apbd2.id_kp=ta_permasalahan_aktifitas.id_kegpokok')
            ->join('ta_subkeg_capkin_apbd', 'ta_subkeg_capkin_apbd.id_sk=ta_permasalahan_aktifitas.id_targetsubkeg')
            ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_permasalahan_aktifitas.id_progprioritas')
            ->where('ta_permasalahan_aktifitas.id_Pr', $idPR)
            ->get()
            ->getRowArray();
    }
    public function DataPerID_R26($idPR)
    {
        return $this
            ->select('ta_permasalahan_aktifitas.*')
            ->select('ta_kegpokok_capkin_apbd2.*')
            ->select('ta_subkeg_mappingcapkin.*')
            ->select('ta_mprog_prioritas.*')
            ->join('ta_kegpokok_capkin_apbd2', 'ta_kegpokok_capkin_apbd2.id_kp=ta_permasalahan_aktifitas.id_kegpokok')
            ->join('ta_subkeg_mappingcapkin', 'ta_subkeg_mappingcapkin.id_skcapkin=ta_permasalahan_aktifitas.id_targetsubkeg')
            ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_permasalahan_aktifitas.id_progprioritas')
            ->where('ta_permasalahan_aktifitas.id_Pr', $idPR)
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
