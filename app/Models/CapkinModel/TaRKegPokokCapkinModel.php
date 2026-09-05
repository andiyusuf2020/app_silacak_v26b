<?php

namespace App\Models\CapkinModel;

use CodeIgniter\Model;

class TaRKegPokokCapkinModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_dok_r_kegpokok';
    protected $primaryKey           = 'id_dr';
    protected $useAutoIncrement     = true;
    protected $allowedFields  = [
        'id_dr',
        'id_rkegpokok',
        'id_targetsubkeg',
        'id_kegpokok',
        'id_progprioritas',
        'id_progunggulan',
        'id_progtematik',
        'tahun',
        'bulan',
        'kegiatan',
        'kd_subunit',
        'kd_subkegiatan',
        'kabupaten',
        'kecamatan',
        'desa',
        'latitude',
        'longitude',
        'deskripsi',
        'gambar',
        'kategori',
    ];
    protected $useSoftDeletes = true;
    protected $useTimestamps = true; // Tidak menggunakan timestamps
    protected $dateFormat    = 'date';
    protected $createdField  = 'create_at';
    protected $updatedField  = 'update_at';
    protected $deletedField  = 'delete_at';


    public function getLokasi($tahun, $kd_subunit, $id_dr)
    {
        if ($id_dr === false) {
            return $this
                ->select('ta_dok_r_kegpokok.*')
                ->select('ta_realisasi_kegpokok.id_r')
                ->select('ta_kegpokok_capkin_apbd2.id_kp')
                ->select('ta_subkeg_capkin_apbd.id_sk')
                // ->select('ta_mprog_prioritas.*')
                ->join('ta_realisasi_kegpokok', 'ta_realisasi_kegpokok.id_r=ta_dok_r_kegpokok.id_rkegpokok')
                ->join('ta_kegpokok_capkin_apbd2', 'ta_kegpokok_capkin_apbd2.id_kp=ta_dok_r_kegpokok.id_kegpokok')
                ->join('ta_subkeg_capkin_apbd', 'ta_subkeg_capkin_apbd.id_sk=ta_dok_r_kegpokok.id_targetsubkeg')
                // ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_dok_r_kegpokok.id_progprioritas')
                ->where('ta_dok_r_kegpokok.tahun', $tahun)
                ->where('ta_dok_r_kegpokok.kd_subunit', $kd_subunit)
                ->where('ta_dok_r_kegpokok.delete_at=', 0)
                ->where('ta_realisasi_kegpokok.delete_at=', 0)
                ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
                ->where('ta_subkeg_capkin_apbd.delete_at=', 0)
                ->orderBy('ta_dok_r_kegpokok.create_at', 'DESC')
                // ->findAll();
                ->get()->getResultArray();
        }
        return $this
            // ->where(['id_dr' => $id_dr])->first();
            ->select('ta_dok_r_kegpokok.*')
            ->select('ta_realisasi_kegpokok.id_r')
            ->select('ta_subkeg_capkin_apbd.id_sk,nm_subunit as nm_sub_skpd ,
            nm_program,nm_kegiatan,nm_subkegiatan as nm_sub_giat')
            // ->select('ta_kegpokok_capkin_apbd2.id_kp')
            ->select('ta_mprog_prioritas.*')
            ->join('ta_realisasi_kegpokok', 'ta_realisasi_kegpokok.id_r=ta_dok_r_kegpokok.id_rkegpokok')
            // ->join('ta_kegpokok_capkin_apbd2', 'ta_kegpokok_capkin_apbd2.id_kp=ta_dok_r_kegpokok.id_kegpokok')
            ->join('ta_subkeg_capkin_apbd', 'ta_subkeg_capkin_apbd.id_sk=ta_dok_r_kegpokok.id_targetsubkeg')
            ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_dok_r_kegpokok.id_progprioritas')
            // // ->join('ta_mprog_unggulan', 'ta_mprog_unggulan.id_pung=ta_dok_r_kegpokok.id_progunggulan')
            // // ->join('ta_mprog_tematik', 'ta_mprog_tematik.id_tematik=ta_dok_r_kegpokok.id_progtematik')
            // // ->where('ta_dok_r_kegpokok.tahun', $tahun)
            // // ->where('ta_dok_r_kegpokok.kd_subunit', $kd_subunit)
            ->where('ta_dok_r_kegpokok.id_dr', $id_dr)
            // ->where('ta_dok_r_kegpokok.delete_at=', 0)
            ->where('ta_realisasi_kegpokok.delete_at=', 0)
            // ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
            // ->where('ta_subkeg_capkin_apbd.delete_at=', 0)
            ->orderBy('ta_dok_r_kegpokok.create_at', 'DESC')
            // ->findAll();
            ->first(); //->get()->getResultArray();
    }
    public function getLokasi26($tahun, $kd_subunit, $id_dr = false, $tgldata = false)
    {
        if ($id_dr === false) {
            return $this
                ->select('ta_dok_r_kegpokok.*')
                ->select('ta_realisasi_kegpokok.id_r')
                ->select('ta_kegpokok_capkin_apbd2.id_kp')
                ->select('ta_subkeg_mappingcapkin.id_skcapkin')
                ->select('ta_mprog_prioritas.*')
                ->join('ta_realisasi_kegpokok', 'ta_realisasi_kegpokok.id_r=ta_dok_r_kegpokok.id_rkegpokok')
                ->join('ta_kegpokok_capkin_apbd2', 'ta_kegpokok_capkin_apbd2.id_kp=ta_dok_r_kegpokok.id_kegpokok')
                ->join('ta_subkeg_mappingcapkin', 'ta_subkeg_mappingcapkin.id_skcapkin=ta_dok_r_kegpokok.id_targetsubkeg')
                ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_dok_r_kegpokok.id_progprioritas')
                ->where('ta_dok_r_kegpokok.tahun', $tahun)
                ->where('ta_dok_r_kegpokok.kd_subunit', $kd_subunit)
                ->where('ta_dok_r_kegpokok.delete_at=', 0)
                ->where('ta_realisasi_kegpokok.delete_at=', 0)
                ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
                ->where('ta_subkeg_mappingcapkin.delete_at=', 0)
                ->orderBy('ta_dok_r_kegpokok.create_at', 'DESC')
                // ->findAll();
                ->get()->getResultArray();
        }
        return $this
            // ->where(['id_dr' => $id_dr])->first();
            ->select('ta_dok_r_kegpokok.*')
            ->select('ta_realisasi_kegpokok.id_r')
            ->select('ta_kegpokok_capkin_apbd2.id_kp,')
            ->select('ta_subkeg_mappingcapkin.*')
            ->select('ta_mprog_prioritas.*')
            // ->select('ta_mprog_unggulan.*')
            // ->select('ta_mprog_tematik.*')
            ->join('ta_realisasi_kegpokok', 'ta_realisasi_kegpokok.id_r=ta_dok_r_kegpokok.id_rkegpokok')
            ->join('ta_kegpokok_capkin_apbd2', 'ta_kegpokok_capkin_apbd2.id_kp=ta_dok_r_kegpokok.id_kegpokok')
            ->join('ta_subkeg_mappingcapkin', 'ta_subkeg_mappingcapkin.id_skcapkin=ta_dok_r_kegpokok.id_targetsubkeg')
            ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_dok_r_kegpokok.id_progprioritas')
            // ->join('ta_mprog_unggulan', 'ta_mprog_unggulan.id_pung=ta_dok_r_kegpokok.id_progunggulan')
            // ->join('ta_mprog_tematik', 'ta_mprog_tematik.id_tematik=ta_dok_r_kegpokok.id_progtematik')
            ->where('ta_dok_r_kegpokok.tahun', $tahun)
            ->where('ta_dok_r_kegpokok.kd_subunit', $kd_subunit)
            ->where('ta_dok_r_kegpokok.id_dr', $id_dr)
            ->where('ta_dok_r_kegpokok.delete_at=', 0)
            ->where('ta_realisasi_kegpokok.delete_at=', 0)
            ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
            ->where('ta_subkeg_mappingcapkin.delete_at=', 0)
            ->orderBy('ta_dok_r_kegpokok.create_at', 'DESC')
            // ->findAll();
            ->first(); //->get()->getResultArray();
    }
    public function perkeyword26($keyword, $tahun, $kd_subunit)
    {
        return $this
            ->select('ta_dok_r_kegpokok.*')
            ->select('ta_realisasi_kegpokok.id_r')
            ->select('ta_kegpokok_capkin_apbd2.id_kp')
            ->select('ta_subkeg_mappingcapkin.id_skcapkin')
            ->select('ta_mprog_prioritas.*')
            ->join('ta_realisasi_kegpokok', 'ta_realisasi_kegpokok.id_r=ta_dok_r_kegpokok.id_rkegpokok')
            ->join('ta_kegpokok_capkin_apbd2', 'ta_kegpokok_capkin_apbd2.id_kp=ta_dok_r_kegpokok.id_kegpokok')
            ->join('ta_subkeg_mappingcapkin', 'ta_subkeg_mappingcapkin.id_skcapkin=ta_dok_r_kegpokok.id_targetsubkeg')
            ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_dok_r_kegpokok.id_progprioritas')
            ->where('ta_dok_r_kegpokok.delete_at=', 0)
            ->where('ta_realisasi_kegpokok.delete_at=', 0)
            ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
            ->where('ta_subkeg_mappingcapkin.delete_at=', 0)
            ->where('ta_dok_r_kegpokok.tahun', $tahun)
            ->where('ta_dok_r_kegpokok.kd_subunit', $kd_subunit)
            ->like('ta_dok_r_kegpokok.kegiatan', $keyword)
            // ->orLike('tahun', $tahun)
            // ->orLike('kd_subunit', $kd_subunit)
            ->orLike('ta_dok_r_kegpokok.deskripsi', $keyword)
            // ->orLike('ta_dok_r_kegpokok.kategori', $keyword)
            ->orLike('ta_dok_r_kegpokok.kabupaten', $keyword)
            ->orLike('ta_dok_r_kegpokok.kecamatan', $keyword)
            ->orLike('ta_dok_r_kegpokok.desa', $keyword)
            ->orderBy('ta_dok_r_kegpokok.create_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function perkeyword($keyword, $tahun, $kd_subunit)
    {
        return $this
            ->select('*')
            ->where('tahun', $tahun)
            ->where('kd_subunit', $kd_subunit)
            ->where('delete_at=', 0)
            ->like('kegiatan', $keyword)
            // ->orLike('tahun', $tahun)
            // ->orLike('kd_subunit', $kd_subunit)
            ->orLike('deskripsi', $keyword)
            ->orLike('kategori', $keyword)
            ->orLike('kabupaten', $keyword)
            ->orLike('kecamatan', $keyword)
            ->orLike('desa', $keyword)
            ->orderBy('create_at', 'DESC')
            ->get()
            ->getResultArray();
    }
    public function perkategori26($kategori, $tahun, $kd_subunit)
    {
        return $this
            ->select('ta_dok_r_kegpokok.*')
            ->select('ta_realisasi_kegpokok.id_r')
            ->select('ta_kegpokok_capkin_apbd2.id_kp')
            ->select('ta_subkeg_mappingcapkin.id_skcapkin')
            ->select('ta_mprog_prioritas.*')
            ->join('ta_realisasi_kegpokok', 'ta_realisasi_kegpokok.id_r=ta_dok_r_kegpokok.id_rkegpokok')
            ->join('ta_kegpokok_capkin_apbd2', 'ta_kegpokok_capkin_apbd2.id_kp=ta_dok_r_kegpokok.id_kegpokok')
            ->join('ta_subkeg_mappingcapkin', 'ta_subkeg_mappingcapkin.id_skcapkin=ta_dok_r_kegpokok.id_targetsubkeg')
            ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_dok_r_kegpokok.id_progprioritas')
            ->where('ta_dok_r_kegpokok.delete_at=', 0)
            ->where('ta_realisasi_kegpokok.delete_at=', 0)
            ->where('ta_kegpokok_capkin_apbd2.delete_at=', 0)
            ->where('ta_subkeg_mappingcapkin.delete_at=', 0)
            ->where('ta_dok_r_kegpokok.tahun', $tahun)
            ->where('ta_dok_r_kegpokok.kd_subunit', $kd_subunit)
            ->where('ta_dok_r_kegpokok.kategori', $kategori)
            ->orderBy('ta_dok_r_kegpokok.create_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function perkategori($kategori, $tahun, $kd_subunit)
    {
        return $this
            ->select('*')
            ->where('kategori', $kategori)
            ->where('tahun', $tahun)
            ->where('kd_subunit', $kd_subunit)
            ->orderBy('create_at', 'DESC')
            ->get()
            ->getResultArray();
    }
    public function getKategori()
    {
        return $this->distinct()->select('kategori')->findAll();
    }
    public function DataPerDRKegPokok26($id_dr)
    {
        return $this
            ->select('ta_dok_r_kegpokok.*')
            ->select('ta_realisasi_kegpokok.*')
            ->select('ta_kegpokok_capkin_apbd2.*')
            ->select('ta_subkeg_mappingcapkin.*')
            ->select('ta_mprog_prioritas.*')

            ->join('ta_realisasi_kegpokok', 'ta_realisasi_kegpokok.id_r=ta_dok_r_kegpokok.id_rkegpokok')
            ->join('ta_kegpokok_capkin_apbd2', 'ta_kegpokok_capkin_apbd2.id_kp=ta_dok_r_kegpokok.id_kegpokok')
            ->join('ta_subkeg_mappingcapkin', 'ta_subkeg_mappingcapkin.id_skcapkin=ta_dok_r_kegpokok.id_targetsubkeg')
            ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_dok_r_kegpokok.id_progprioritas')
            ->where('ta_dok_r_kegpokok.id_dr', $id_dr)
            ->get()
            ->getRowArray();
    }
    public function DataPerDRKegPokok($id_dr)
    {
        return $this
            ->select('ta_dok_r_kegpokok.*')
            ->select('ta_realisasi_kegpokok.*')
            ->select('ta_kegpokok_capkin_apbd2.*')
            ->select('ta_subkeg_capkin_apbd.*')
            ->select('ta_mprog_prioritas.*')
            ->join('ta_realisasi_kegpokok', 'ta_realisasi_kegpokok.id_r=ta_dok_r_kegpokok.id_rkegpokok')
            ->join('ta_kegpokok_capkin_apbd2', 'ta_kegpokok_capkin_apbd2.id_kp=ta_dok_r_kegpokok.id_kegpokok')
            ->join('ta_subkeg_capkin_apbd', 'ta_subkeg_capkin_apbd.id_sk=ta_dok_r_kegpokok.id_targetsubkeg')
            ->join('ta_mprog_prioritas', 'ta_mprog_prioritas.id_pprio=ta_dok_r_kegpokok.id_progprioritas')
            ->where('ta_dok_r_kegpokok.id_dr', $id_dr)
            ->get()
            ->getRowArray();
    }

    public function DataPerRKegPokok($id_r, $tahun)
    {
        return $this
            ->select('ta_dok_r_kegpokok.*')
            ->select('ta_realisasi_kegpokok.id_r,r_target,sat_target,r_uraian')
            ->select('ta_subkeg_capkin_apbd.id_sk,nm_subunit,nm_program,nm_kegiatan,nm_subkegiatan')
            ->join('ta_subkeg_capkin_apbd', 'ta_subkeg_capkin_apbd.id_sk=ta_dok_r_kegpokok.id_targetsubkeg')
            ->join('ta_realisasi_kegpokok', 'ta_realisasi_kegpokok.id_r=ta_dok_r_kegpokok.id_rkegpokok')
            ->where('ta_dok_r_kegpokok.id_rkegpokok', $id_r)
            ->where('ta_dok_r_kegpokok.tahun', $tahun)
            ->where('ta_dok_r_kegpokok.delete_at=', 0)
            ->where('ta_realisasi_kegpokok.delete_at=', 0)
            ->get()
            ->getResultArray();
    }
    public function DataPerRKegPokok26($id_r)
    {
        return $this
            ->select('ta_dok_r_kegpokok.*')
            ->select('ta_realisasi_kegpokok.id_r,r_target,sat_target,r_uraian')
            ->select('ta_subkeg_mappingcapkin.id_skcapkin,nm_sub_giat,total_anggaran')
            ->join('ta_subkeg_mappingcapkin', 'ta_subkeg_mappingcapkin.id_skcapkin=ta_dok_r_kegpokok.id_targetsubkeg')
            ->join('ta_realisasi_kegpokok', 'ta_realisasi_kegpokok.id_r=ta_dok_r_kegpokok.id_rkegpokok')
            ->where('ta_dok_r_kegpokok.id_rkegpokok', $id_r)
            ->where('ta_dok_r_kegpokok.delete_at=', 0)
            ->get()
            ->getResultArray();
    }
    public function DataPerSasaranPerOPD($tahun, $kd_subunit, $kd_progprioritas)
    {
        return $this
            ->select('ta_dok_r_kegpokok.*')
            ->select('ta_realisasi_kegpokok.id_r,r_target,sat_target,r_uraian')
            ->select('ta_subkeg_mappingcapkin.id_skcapkin,nm_sub_giat,total_anggaran')
            ->join('ta_subkeg_mappingcapkin', 'ta_subkeg_mappingcapkin.id_skcapkin=ta_dok_r_kegpokok.id_targetsubkeg')
            ->join('ta_realisasi_kegpokok', 'ta_realisasi_kegpokok.id_r=ta_dok_r_kegpokok.id_rkegpokok')
            ->where('ta_dok_r_kegpokok.tahun', $tahun)
            ->where('ta_dok_r_kegpokok.kd_subunit', $kd_subunit)
            ->where('ta_dok_r_kegpokok.id_progprioritas', $kd_progprioritas)
            ->where('ta_dok_r_kegpokok.delete_at=', 0)
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
    public function DataDokPerSubUnit($tahun, $bulan)
    {
        return $this
            ->select('ta_dok_r_kegpokok.*')
            ->select('ta_apbd.nm_sub_unit')
            ->join('ta_apbd', 'ta_apbd.kd_sub_unit=ta_dok_r_kegpokok.kd_subunit')
            ->where('ta_dok_r_kegpokok.tahun', $tahun)
            ->where('ta_dok_r_kegpokok.bulan', $bulan)
            ->where('ta_dok_r_kegpokok.delete_at=', 0)
            ->groupBy('ta_dok_r_kegpokok.kd_subunit')
            ->get()
            ->getResultArray();
    }
}
