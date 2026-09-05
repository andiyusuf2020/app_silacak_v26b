<?php

namespace App\Models\LrfkProvModel;

use CodeIgniter\Model;

class TaRealisasiRinciModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'ta_rinci_realisasi_lrfk';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $useSoftDeletes = true;
    protected $protectFields  = false;
    protected $allowedFields  = [
        'id',
        'kd_urusan',
        'nm_urusan',
        'kd_sub_unit',
        'nm_sub_unit',
        'tahun',
        'bulan',
        'kd_program',
        'nm_program',
        'kd_kegiatan',
        'nm_kegiatan',
        'kd_subkegiatan',
        'nm_subkegiatan',
        'kd_rek_belanja',
        'nm_rekening',
        'pagu_rincian',
        'realisasi',
    ];
    protected $useTimestamps = true; // Tidak menggunakan timestamps
    protected $dateFormat    = 'date';
    protected $createdField  = 'create_at';
    protected $updatedField  = 'update_at';
    protected $deletedField  = 'delete_at';


    public function dataRperbelanja($kdSU, $tahun, $bulan, $kode)
    {
        return $this
            ->selectSum('realisasi')
            ->where('kd_sub_unit', $kdSU)
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->where('delete_at=', 0)
            // ->like('kd_rekbelanja', $kode)
            ->get()
            ->getRowArray();
    }
    public function cekROpd($kd_subunit, $tahun)
    {
        return $this
            ->select('*')
            ->where('kd_sub_unit', $kd_subunit)
            ->where('tahun', $tahun)
            ->where('delete_at=', 0)
            ->get()
            ->getResultArray();
    }

    public function cek($id_real_lrfk)
    {
        return $this
            ->select('*')
            ->where('id_real_lrfk', $id_real_lrfk)
            ->where('delete_at=', 0)
            ->get()
            ->getResultArray();
    }
    public function cekpagu($id_real_lrfk)
    {
        return $this
            ->selectSum('pagu')
            ->selectSum('realisasi')
            ->where('id_real_lrfk', $id_real_lrfk)
            ->where('delete_at=', 0)
            ->groupBy('id_real_lrfk', $id_real_lrfk)
            ->get()
            ->getResultArray();
    }
    public function totalrealperOpd($kdSU, $tahun, $bulan)
    {
        return $this
            ->selectSum('realisasi')
            ->where('kd_sub_unit', $kdSU)
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->where('delete_at=', 0)
            ->groupBy('kd_sub_unit')
            ->get()
            ->getRowArray();
    }
    public function totalrealperSKpeBln($kdSU, $kdSK, $tahun, $bulan)
    {
        return $this
            ->selectSum('realisasi')
            ->where('kd_sub_unit', $kdSU)
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->where('kd_subkegiatan', $kdSK)
            ->where('delete_at=', 0)
            ->groupBy('kd_subkegiatan')
            ->get()
            ->getRowArray();
    }
    public function realperSKpeBlnRinci($kdSU, $kdSK, $kdRB, $tahun, $bulan)
    {
        // return $this
        //     ->select('ta_rinci_realisasi_lrfk.*,ta_apbd_perbelanja.kd_rek_belanja,nm_urusan,nm_sub_unit,nm_program,nm_kegiatan')
        //     ->join(
        //         'ta_apbd_perbelanja',
        //         'ta_rinci_realisasi_lrfk.kd_rekbelanja = ta_apbd_perbelanja.kd_rek_belanja',
        //         'left'
        //     )
        //     ->where('ta_apbd_perbelanja.kd_sub_unit', $kdSU)
        //     ->where('ta_apbd_perbelanja.kd_subkegiatan', $kdSK)
        //     ->where('ta_rinci_realisasi_lrfk.tahun', $tahun)
        //     ->where('ta_rinci_realisasi_lrfk.bulan', $bulan)
        //     ->where('ta_rinci_realisasi_lrfk.delete_at=', 0)
        //     ->get()
        //     ->getResultArray();
        return $this
            ->select('*')
            ->where('kd_sub_unit', $kdSU)
            ->where('kd_subkegiatan', $kdSK)
            ->where('kd_rek_belanja', $kdRB)
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->where('delete_at=', 0)
            ->get()
            ->getRowArray();
    }
    public function realperSKpeBlnRinci2($kdSU, $kdSK, $tahun, $bulan)
    {
        return $this
            ->select('ta_rinci_realisasi_lrfk.
                        id,
                        kd_urusan, nm_urusan, kd_sub_unit,nm_sub_unit,
                        tahun,
                        bulan,
                        kd_program,
                        nm_program,
                        kd_kegiatan,
                        nm_kegiatan,
                        kd_subkegiatan,
                        nm_subkegiatan,
                        kd_rek_belanja,
                        nm_rekening,
                        jenis_belanja,
                        realisasi')
            ->select('ta_apbd.pagu_rincian')
            ->join(
                'ta_apbd',
                'ta_rinci_realisasi_lrfk.kd_rekbelanja = ta_apbd.kd_rek_belanja',
                'left'
            )
            ->where('ta_rinci_realisasi_lrfk.kd_sub_unit', $kdSU)
            ->where('ta_rinci_realisasi_lrfk.kd_subkegiatan', $kdSK)
            ->where('ta_rinci_realisasi_lrfk.tahun', $tahun)
            ->where('ta_rinci_realisasi_lrfk.bulan', $bulan)
            ->where('ta_rinci_realisasi_lrfk.delete_at=', 0)
            ->get()
            ->getResultArray();
    }
    public function realperSKpeBlnRinci3($kdSU, $kdSK, $tahun, $bulan)
    {
        return $this
            ->select('
            id,
        kd_urusan,
        nm_urusan,
        kd_sub_unit,
        nm_sub_unit,
        tahun,
        bulan,
        kd_program,
        nm_program,
        kd_kegiatan,
        nm_kegiatan,
        kd_subkegiatan,
        nm_subkegiatan,
        kd_rek_belanja,
        nm_rekening,
        realisasi,
            ')
            ->selectSum('pagu_rincian')
            ->where('kd_sub_unit', $kdSU)
            ->where('kd_subkegiatan', $kdSK)
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->where('delete_at=', 0)
            ->groupBy('kd_rek_belanja')
            ->get()
            ->getResultArray();
    }
    public function rekapallopd($tahun, $bulan)
    {
        return $this->select('ta_rinci_realisasi_lrfk.*')
            ->selectSum('ta_rinci_realisasi_lrfk.realisasi')
            ->where('ta_rinci_realisasi_lrfk.tahun', $tahun)
            ->where('ta_rinci_realisasi_lrfk.bulan', $bulan)
            ->where('ta_rinci_realisasi_lrfk.delete_at=', 0)
            ->groupBy('ta_rinci_realisasi_lrfk.kd_sub_unit')
            ->get()
            ->getResultArray();
    }
    public function rekapagu($tahun, $bulan)
    {
        return $this
            ->selectSum('ta_rinci_realisasi_lrfk.realisasi')
            ->selectSum('ta_rinci_realisasi_lrfk.pagu_rincian')
            ->where('ta_rinci_realisasi_lrfk.tahun', $tahun)
            ->where('ta_rinci_realisasi_lrfk.bulan', $bulan)
            ->where('ta_rinci_realisasi_lrfk.delete_at=', 0)
            ->get()
            ->getRowArray();
    }
    public function realSK($kdSU, $tahun, $bulanaktif)
    {
        $q = $this
            ->select('*')
            // ->selectSUM('pagu_rincian')
            ->selectSUM('realisasi')
            ->where('kd_sub_unit', $kdSU)
            ->where('tahun', $tahun)
            ->where('bulan', $bulanaktif)
            ->groupBy('kd_subkegiatan')
            ->get()
            ->getResultArray();

        return $q;
    }
    public function realSKPerBel($kdSU, $tahun, $bulanaktif, $kdBel)
    {
        $q = $this
            ->select('*')
            // ->selectSUM('pagu_rincian')
            // ->selectSUM('realisasi')
            ->where('kd_sub_unit', $kdSU)
            ->where('tahun', $tahun)
            ->where('bulan', $bulanaktif)
            ->like('kd_rek_belanja', $kdBel)
            // ->groupBy('kd_rek_belanja')
            ->get()
            ->getResultArray();

        return $q;
    }
}
