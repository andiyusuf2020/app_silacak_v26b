<?php

namespace App\Models\LrfkProvModel;

use CodeIgniter\Model;

class RealisasiSubKegModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_realisasi_lrfk';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $useSoftDeletes       = true;
    protected $protectFields        = false;
    protected $allowedFields        = [
        'id',
        'tahun',
        'bulan',
        'kd_sub_unit',
        'sub_unit',
        'id_subkegiatan',
        'kd_subkegiatan',
        'nm_subkegiatan',
        'pagu_subkeg',
        'pagu_realisasi',
        'uraian_realisasi',
        'dokumentasi',
        'link_dokumentasi',
    ];
    protected $useTimestamps = true; // Tidak menggunakan timestamps
    protected $dateFormat    = 'date';
    protected $createdField  = 'create_at';
    protected $updatedField  = 'update_at';
    protected $deletedField  = 'delete_at';


    public function cek($tahun, $bulanaktif, $sub_unit, $kd_subkegiatan)
    {
        return $this
            ->select('*')
            ->where('tahun', $tahun)
            ->where('bulan', $bulanaktif)
            ->where('sub_unit', $sub_unit)
            ->where('kd_subkegiatan', $kd_subkegiatan)
            ->where('delete_at=', 0)
            ->get()
            ->getRowArray();
    }
    public function totalRperBlnOPD($tahun, $bulanaktif, $sub_unit)
    {
        return $this
            ->selectSUM('pagu_realisasi')
            ->where('tahun', $tahun)
            ->where('bulan', $bulanaktif)
            ->where('sub_unit', $sub_unit)
            ->where('delete_at', 0)
            ->groupBy('bulan')
            ->get()
            ->getRowArray();
    }
    public function dataperId($id)
    {
        return $this
            ->select('*')
            ->where('id', $id)
            ->where('delete_at=', 0)
            ->get()
            ->getRowArray();
    }
    public function listrealisasi($id)
    {
        return $this->select('ta_realisasi_lrfk.*,ta_apbd_persubkegiatan.pagu_rincian,nm_urusan,nm_sub_unit,nm_program,nm_kegiatan')
            ->join('ta_apbd_persubkegiatan', 'ta_apbd_persubkegiatan.id = ta_realisasi_lrfk.id_subkegiatan')
            ->where('ta_realisasi_lrfk.id', $id)
            ->where('ta_realisasi_lrfk.delete_at=', 0)
            ->get()
            ->getRowArray();
    }
    public function RperSubKegOpd($kd_subkegiatan, $tahun, $kdSU)
    {
        return $this->select('ta_realisasi_lrfk.*,ta_apbd_perbelanja.*')
            ->selectSum('ta_apbd_perbelanja.pagu_rincian')
            ->join('ta_apbd_perbelanja', 'ta_apbd_perbelanja.kd_subkegiatan = ta_realisasi_lrfk.kd_subkegiatan')
            ->where('ta_realisasi_lrfk.kd_subkegiatan', $kd_subkegiatan)
            ->where('ta_realisasi_lrfk.tahun', $tahun)
            ->where('ta_apbd_perbelanja.kd_sub_unit', $kdSU)
            ->where('ta_realisasi_lrfk.delete_at=', 0)
            ->groupBy('ta_apbd_perbelanja.kd_subkegiatan')
            ->get()
            ->getResultArray();
    }
    public function RperTahunOpd($tahun, $sub_unit)
    {
        return $this->select('*')
            ->where('ta_realisasi_lrfk.tahun', $tahun)
            ->where('ta_realisasi_lrfk.sub_unit', $sub_unit)
            ->where('ta_realisasi_lrfk.delete_at=', 0)
            ->get()
            ->getResultArray();
    }
    public function realperSKpeBln($kdSU, $kdSK, $tahun, $bulan)
    {
        return $this
            ->select('ta_realisasi_lrfk.*,ta_apbd_perbelanja.kd_rek_belanja,nm_urusan,nm_sub_unit,nm_program,nm_kegiatan')
            // ->selectSum('ta_realisasi_lrfk.realisasi')
            // ->selectSum('ta_realisasi_lrfk.pagu_rincian')
            ->join(
                'ta_apbd_perbelanja',
                'ta_realisasi_lrfk.kd_subkegiatan = ta_apbd_perbelanja.kd_subkegiatan'
            )
            ->where('ta_apbd_perbelanja.kd_sub_unit', $kdSU)
            ->where('ta_apbd_perbelanja.kd_subkegiatan', $kdSK)
            ->where('ta_realisasi_lrfk.tahun', $tahun)
            ->where('ta_realisasi_lrfk.bulan', $bulan)
            ->groupBy('ta_apbd_perbelanja.kd_subkegiatan')
            ->where('ta_realisasi_lrfk.delete_at=', 0)
            ->get()
            ->getRowArray();
    }
}
