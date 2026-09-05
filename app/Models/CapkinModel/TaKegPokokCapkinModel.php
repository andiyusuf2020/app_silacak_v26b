<?php

namespace App\Models\CapkinModel;

use CodeIgniter\Model;

class TaKegPokokCapkinModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_kegpokok_capkin_apbd2';
    protected $primaryKey           = 'id_kp';
    protected $useAutoIncrement     = true;
    protected $allowedFields  = [
        'id_kp',
        'id_targetsubkeg',
        'id_progprioritas',
        'id_progunggulan',
        'id_progtematik',
        'tahun',
        'kd_subunit',
        // 'nm_subunit',
        // 'kd_urusan',
        // 'kd_program',
        // 'kd_kegiatan',
        'kd_subkegiatan',
        // 'nm_subkegiatan',
        'pagu',
        // 'realisasi',
        'vol_target',
        'sat_target',
        'uraian_target',
        'hasil',
        'lokasi',
        'kelompok',
    ];
    protected $useSoftDeletes = true;
    protected $useTimestamps = true; // Tidak menggunakan timestamps
    protected $dateFormat    = 'date';
    protected $createdField  = 'create_at';
    protected $updatedField  = 'update_at';
    protected $deletedField  = 'delete_at';

    public function DataPerIdPrio($id_prio, $tahun)
    {
        return $this
            ->select('ta_kegpokok_capkin_apbd2.*')
            ->select('ta_subkeg_mappingcapkin.nm_sub_skpd')
            ->select('ta_mprog_prioritas.nm_progprioritas')
            // ->selectCount('ta_subkeg_mappingcapkin.id_targetsubkeg', 'jmlsubkeg')
            ->selectCount('ta_kegpokok_capkin_apbd2.id_kp', 'jmlaktivitasutama')
            ->join('ta_subkeg_mappingcapkin', 'ta_subkeg_mappingcapkin.id_skcapkin=ta_kegpokok_capkin_apbd2.id_targetsubkeg')
            ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_kegpokok_capkin_apbd2.id_progprioritas')
            // ->where('ta_kegpokok_capkin_apbd2.kd_subunit', $dataprogprio['kd_subunit'])
            ->where('ta_kegpokok_capkin_apbd2.tahun', $tahun)
            ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
            ->where('ta_subkeg_mappingcapkin.delete_at=', 0)
            ->where('ta_kegpokok_capkin_apbd2.id_progprioritas', $id_prio)
            ->groupBy('ta_kegpokok_capkin_apbd2.kd_subunit')
            ->get()
            ->getResultArray();
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
    public function DataPerIdSK26($idSK, $tahunaktif)
    {
        return $this
            ->select('*')
            ->select('ta_mprog_prioritas.nm_progprioritas')
            ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_kegpokok_capkin_apbd2.id_progprioritas')
            ->where('id_targetsubkeg', $idSK)
            ->where('tahun', $tahunaktif)
            ->where('delete_at=', 0)
            ->groupBy('id_progprioritas')
            ->get()
            ->getResultArray();
    }
    public function DataPerIdSK($idSK)
    {
        return $this
            ->select('*')
            ->select('ta_mprog_prioritas.nm_progprioritas')
            ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_kegpokok_capkin_apbd2.id_progprioritas')
            ->where('id_targetsubkeg', $idSK)
            // ->where('tahun', $tahunaktif)
            ->where('delete_at=', 0)
            ->groupBy('id_progprioritas')
            ->get()
            ->getResultArray();
    }
    public function DataPerSasaranPerIdSK($idSK, $id_pprio)
    {
        return $this
            ->select('*')
            ->select('ta_mprog_prioritas.nm_progprioritas')
            ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_kegpokok_capkin_apbd2.id_progprioritas')
            ->where('id_targetsubkeg', $idSK)
            ->where('id_progprioritas', $id_pprio)
            ->where('delete_at=', 0)
            ->get()
            ->getResultArray();
    }
    public function DataPerIdKegPokok($idKP)
    {
        return $this
            ->select('ta_kegpokok_capkin_apbd2.*')
            ->select('ta_subkeg_capkin_apbd.nm_subkegiatan')
            ->select('ta_mprog_prioritas.nm_progprioritas')
            ->join('ta_subkeg_capkin_apbd', 'ta_subkeg_capkin_apbd.id_sk=ta_kegpokok_capkin_apbd2.id_targetsubkeg')
            ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_kegpokok_capkin_apbd2.id_progprioritas')
            ->where('ta_kegpokok_capkin_apbd2.id_kp', $idKP)
            ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
            ->get()
            ->getRowArray();
    }
    public function DataPerIdKegPokok26($idKP)
    {
        return $this
            ->select('ta_kegpokok_capkin_apbd2.*')
            ->select('ta_subkeg_mappingcapkin.nm_program,nm_kegiatan,nm_sub_giat')
            ->select('ta_mprog_prioritas.nm_progprioritas')
            ->join('ta_subkeg_mappingcapkin', 'ta_subkeg_mappingcapkin.id_skcapkin=ta_kegpokok_capkin_apbd2.id_targetsubkeg')
            ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_kegpokok_capkin_apbd2.id_progprioritas')
            ->where('ta_kegpokok_capkin_apbd2.id_kp', $idKP)
            ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
            ->get()
            ->getRowArray();
    }
    public function DataPerOpdPerIdPrio($tahun, $kd_subunit, $id_pprio)
    {
        return $this
            ->select('ta_kegpokok_capkin_apbd2.*')
            ->select('ta_subkeg_mappingcapkin.nm_program,nm_kegiatan,nm_sub_giat')
            ->select('ta_mprog_prioritas.nm_progprioritas')
            ->join('ta_subkeg_mappingcapkin', 'ta_subkeg_mappingcapkin.id_skcapkin=ta_kegpokok_capkin_apbd2.id_targetsubkeg')
            ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_kegpokok_capkin_apbd2.id_progprioritas')
            ->where('ta_kegpokok_capkin_apbd2.tahun', $tahun)
            ->where('ta_kegpokok_capkin_apbd2.kd_subunit', $kd_subunit)
            ->where('ta_kegpokok_capkin_apbd2.id_progprioritas', $id_pprio)
            ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
            ->get()
            ->getRowArray();
    }
    public function DataPerPprioOPD26($tahun, $kd_subunit)
    {
        return $this
            ->select('ta_kegpokok_capkin_apbd2.*')
            ->select('ta_subkeg_mappingcapkin.nm_program,nm_kegiatan,nm_sub_giat')
            ->select('ta_mprog_prioritas.nm_progprioritas')
            ->join('ta_subkeg_mappingcapkin', 'ta_subkeg_mappingcapkin.id_skcapkin=ta_kegpokok_capkin_apbd2.id_targetsubkeg')
            ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_kegpokok_capkin_apbd2.id_progprioritas')
            ->where('ta_kegpokok_capkin_apbd2.kd_subunit', $kd_subunit)
            ->where('ta_kegpokok_capkin_apbd2.tahun', $tahun)
            ->where('ta_subkeg_mappingcapkin.delete_at=', 0)
            ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
            ->where('ta_kegpokok_capkin_apbd2.id_progprioritas<>', 0)
            // ->groupBy('ta_kegpokok_capkin_apbd2.id_progprioritas')
            ->get()
            ->getResultArray();
    }
    public function DataPerSasaranPerOPD26($tahun, $kd_subunit)
    {
        return $this
            ->select('ta_kegpokok_capkin_apbd2.*')
            ->select('ta_subkeg_mappingcapkin.nm_program,nm_kegiatan,nm_sub_giat')
            ->select('ta_mprog_prioritas.nm_progprioritas')
            ->join('ta_subkeg_mappingcapkin', 'ta_subkeg_mappingcapkin.id_skcapkin=ta_kegpokok_capkin_apbd2.id_targetsubkeg')
            ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_kegpokok_capkin_apbd2.id_progprioritas')
            ->where('ta_kegpokok_capkin_apbd2.kd_subunit', $kd_subunit)
            ->where('ta_kegpokok_capkin_apbd2.tahun', $tahun)
            ->where('ta_subkeg_mappingcapkin.delete_at=', 0)
            ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
            ->where('ta_kegpokok_capkin_apbd2.id_progprioritas<>', 0)
            ->groupBy('ta_kegpokok_capkin_apbd2.id_progprioritas')
            ->get()
            ->getResultArray();
    }
    public function DataAktifitasPerPprioOPD26($tahun, $kd_subunit, $id_pprio)
    {
        return $this
            ->select('ta_kegpokok_capkin_apbd2.*')
            ->select('ta_subkeg_mappingcapkin.nm_program,nm_kegiatan,nm_sub_giat')
            ->select('ta_mprog_prioritas.nm_progprioritas')
            ->join('ta_subkeg_mappingcapkin', 'ta_subkeg_mappingcapkin.id_skcapkin=ta_kegpokok_capkin_apbd2.id_targetsubkeg')
            ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_kegpokok_capkin_apbd2.id_progprioritas')
            ->where('ta_kegpokok_capkin_apbd2.kd_subunit', $kd_subunit)
            ->where('ta_kegpokok_capkin_apbd2.tahun', $tahun)
            ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
            ->where('ta_kegpokok_capkin_apbd2.id_progprioritas', $id_pprio)
            // ->groupBy('ta_kegpokok_capkin_apbd2.id_progprioritas')
            ->get()
            ->getResultArray();
    }
    public function DataPerIdKegPokokUnggulan($idKP)
    {
        return $this
            ->select('ta_kegpokok_capkin_apbd2.*')
            ->select('ta_subkeg_capkin_apbd.nm_subkegiatan')
            ->select('ta_mprog_unggulan.nm_progunggulan')
            ->join('ta_subkeg_capkin_apbd', 'ta_subkeg_capkin_apbd.id_sk=ta_kegpokok_capkin_apbd2.id_targetsubkeg')
            ->join('ta_mprog_unggulan', 'ta_mprog_unggulan.id_pung=ta_kegpokok_capkin_apbd2.id_progunggulan')
            ->where('ta_kegpokok_capkin_apbd2.id_kp', $idKP)
            ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
            ->get()
            ->getRowArray();
    }
    public function DataPerIdKegPokokTematik($idKP)
    {
        return $this
            ->select('ta_kegpokok_capkin_apbd2.*')
            ->select('ta_subkeg_capkin_apbd.nm_subkegiatan')
            ->select('ta_mprog_tematik.nm_tematik')
            ->join('ta_subkeg_capkin_apbd', 'ta_subkeg_capkin_apbd.id_sk=ta_kegpokok_capkin_apbd2.id_targetsubkeg')
            ->join('ta_mprog_tematik', 'ta_mprog_tematik.id_tematik=ta_kegpokok_capkin_apbd2.id_progtematik')
            ->where('ta_kegpokok_capkin_apbd2.id_kp', $idKP)
            ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
            ->get()
            ->getRowArray();
    }
    public function DataPerIdKegPokokLengkap($idKP)
    {
        return $this
            ->select('ta_kegpokok_capkin_apbd2.*')
            ->select('ta_subkeg_capkin_apbd.nm_subkegiatan')
            ->select('ta_mprog_prioritas.nm_progprioritas')
            ->select('ta_mprog_unggulan.nm_progunggulan')
            // ->select('ta_mprog_tematik.nm_tematik')

            ->join('ta_subkeg_capkin_apbd', 'ta_subkeg_capkin_apbd.id_sk=ta_kegpokok_capkin_apbd2.id_targetsubkeg')
            ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_kegpokok_capkin_apbd2.id_progprioritas')
            ->join('ta_mprog_unggulan', 'ta_mprog_unggulan.id_pung=ta_kegpokok_capkin_apbd2.id_progunggulan')
            // ->where('ta_kegpokok_capkin_apbd2.id_progunggulan<>', '0')
            ->where('ta_kegpokok_capkin_apbd2.id_kp', $idKP)
            ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
            ->get()
            ->getRowArray();
    }
    public function DataPerIdKegPokokLengkap2($idKP)
    {
        return $this
            ->select('ta_kegpokok_capkin_apbd2.*')
            ->select('ta_subkeg_capkin_apbd.nm_subkegiatan')
            ->select('ta_mprog_prioritas.nm_progprioritas')
            ->select('ta_mprog_tematik.nm_tematik')
            ->join('ta_subkeg_capkin_apbd', 'ta_subkeg_capkin_apbd.id_sk=ta_kegpokok_capkin_apbd2.id_targetsubkeg')
            ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_kegpokok_capkin_apbd2.id_progprioritas')
            ->join('ta_mprog_tematik', 'ta_mprog_tematik.id_tematik=ta_kegpokok_capkin_apbd2.id_progtematik')
            ->where('ta_kegpokok_capkin_apbd2.id_kp', $idKP)
            ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
            ->get()
            ->getRowArray();
    }

    public function DataPerPprioOPD($tahun, $kd_subunit)
    {
        return $this
            ->select('ta_kegpokok_capkin_apbd2.*')
            ->select('ta_subkeg_capkin_apbd.nm_subkegiatan,nm_kegiatan,nm_program')
            ->select('ta_mprog_prioritas.nm_progprioritas')
            ->join('ta_subkeg_capkin_apbd', 'ta_subkeg_capkin_apbd.id_sk=ta_kegpokok_capkin_apbd2.id_targetsubkeg')
            ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_kegpokok_capkin_apbd2.id_progprioritas')
            ->where('ta_kegpokok_capkin_apbd2.kd_subunit', $kd_subunit)
            ->where('ta_kegpokok_capkin_apbd2.tahun', $tahun)
            ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
            ->where('ta_kegpokok_capkin_apbd2.id_progprioritas<>', 0)
            ->groupBy('ta_kegpokok_capkin_apbd2.id_progprioritas')
            ->get()
            ->getResultArray();
    }
    public function DataPerPUnggulOPD($tahun, $kd_subunit)
    {
        return $this
            ->select('ta_kegpokok_capkin_apbd2.*')
            ->select('ta_subkeg_capkin_apbd.nm_subkegiatan,nm_kegiatan,nm_program')
            ->select('ta_mprog_unggulan.nm_progunggulan')
            ->join('ta_subkeg_capkin_apbd', 'ta_subkeg_capkin_apbd.id_sk=ta_kegpokok_capkin_apbd2.id_targetsubkeg')
            ->join('ta_mprog_unggulan', 'ta_mprog_unggulan.id_pung=ta_kegpokok_capkin_apbd2.id_progunggulan')
            ->where('ta_kegpokok_capkin_apbd2.kd_subunit', $kd_subunit)
            ->where('ta_kegpokok_capkin_apbd2.tahun', $tahun)
            ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
            ->where('ta_kegpokok_capkin_apbd2.id_progunggulan<>', 0)
            ->groupBy('ta_kegpokok_capkin_apbd2.id_progunggulan')
            ->get()
            ->getResultArray();
    }
    public function DataPerTematikOPD($tahun, $kd_subunit)
    {
        return $this
            ->select('ta_kegpokok_capkin_apbd2.*')
            ->select('ta_subkeg_capkin_apbd.nm_subkegiatan,nm_kegiatan,nm_program')
            ->select('ta_mprog_tematik.nm_tematik')
            ->join('ta_subkeg_capkin_apbd', 'ta_subkeg_capkin_apbd.id_sk=ta_kegpokok_capkin_apbd2.id_targetsubkeg')
            ->join('ta_mprog_tematik', 'ta_mprog_tematik.id_tematik=ta_kegpokok_capkin_apbd2.id_progtematik')
            ->where('ta_kegpokok_capkin_apbd2.kd_subunit', $kd_subunit)
            ->where('ta_kegpokok_capkin_apbd2.tahun', $tahun)
            ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
            ->where('ta_kegpokok_capkin_apbd2.id_progtematik<>', 0)
            ->groupBy('ta_kegpokok_capkin_apbd2.id_progtematik')
            ->get()
            ->getResultArray();
    }
}
