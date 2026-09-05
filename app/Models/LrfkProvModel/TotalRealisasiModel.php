<?php

namespace App\Models\LrfkProvModel;

use CodeIgniter\Model;

class TotalRealisasiModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_totalrealisasi_opd';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $useSoftDeletes = true;
    protected $protectFields  = false;
    protected $allowedFields  = [
        'id',
        'tahun',
        'sub_unit',
        'kd_subkegiatan',
        'nm_subkegiatan',
        'pagu_subkeg',
        'total_realisasi',
    ];
    protected $useTimestamps = true; // Tidak menggunakan timestamps
    protected $dateFormat    = 'date';
    protected $createdField  = 'create_at';
    protected $updatedField  = 'update_at';
    protected $deletedField  = 'delete_at';


    public function cek($tahun, $kd_sub_unit, $sub_unit, $kd_subkegiatan)
    {
        return $this
            ->select('*')
            ->where('tahun', $tahun)
            ->where('kd_sub_unit', $kd_sub_unit)
            ->where('sub_unit', $sub_unit)
            ->where('delete_at=', 0)
            ->where('kd_subkegiatan', $kd_subkegiatan)
            ->get()
            ->getRowArray();
    }
    public function total($tahun, $kd_sub_unit, $sub_unit)
    {
        return $this
            ->selectSUM('total_realisasi')
            ->where('tahun', $tahun)
            ->where('kd_sub_unit', $kd_sub_unit)
            ->where('sub_unit', $sub_unit)
            ->where('delete_at=', 0)
            ->groupBy('sub_unit')
            ->get()
            ->getRowArray();
    }
    public function RperUrusanOpd($tahun, $kd_sub_unit, $sub_unit)
    {
        return $this->selectSUM('ta_totalrealisasi_opd.total_realisasi')
            ->join('ta_apbd_persubkegiatan', 'ta_apbd_persubkegiatan.kd_subkegiatan=ta_totalrealisasi_opd.kd_subkegiatan')
            ->where('ta_totalrealisasi_opd.tahun', $tahun)
            ->where('ta_totalrealisasi_opd.sub_unit', $sub_unit)
            ->where('ta_apbd_persubkegiatan.kd_sub_unit', $kd_sub_unit)
            ->where('ta_apbd_persubkegiatan.pagu_rincian<>', '0')
            //  ->where('delete_at=', 0)

            ->groupBy('ta_apbd_persubkegiatan.kd_urusan')
            ->get()
            ->getResultArray();
    }

    public function RperProgramOpd($tahun,  $kd_sub_unit, $sub_unit)
    {
        return $this->selectSUM('ta_totalrealisasi_opd.total_realisasi')
            ->join('ta_apbd_persubkegiatan', 'ta_apbd_persubkegiatan.kd_subkegiatan=ta_totalrealisasi_opd.kd_subkegiatan')
            ->where('ta_totalrealisasi_opd.tahun', $tahun)
            ->where('ta_totalrealisasi_opd.sub_unit', $sub_unit)
            ->where('ta_apbd_persubkegiatan.kd_sub_unit', $kd_sub_unit)
            ->where('ta_apbd_persubkegiatan.pagu_rincian<>', '0')
            ->groupBy('ta_apbd_persubkegiatan.kd_program')
            ->get()
            ->getResultArray();
    }
    public function RperKegiatanOpd($tahun,  $kd_sub_unit, $sub_unit)
    {
        return $this->selectSUM('ta_totalrealisasi_opd.total_realisasi')
            ->join('ta_apbd_persubkegiatan', 'ta_apbd_persubkegiatan.kd_subkegiatan=ta_totalrealisasi_opd.kd_subkegiatan')
            ->where('ta_totalrealisasi_opd.tahun', $tahun)
            ->where('ta_totalrealisasi_opd.sub_unit', $sub_unit)
            ->where('ta_apbd_persubkegiatan.kd_sub_unit', $kd_sub_unit)

            ->where('ta_apbd_persubkegiatan.pagu_rincian<>', '0')
            ->groupBy('ta_apbd_persubkegiatan.kd_kegiatan')
            ->get()
            ->getResultArray();
    }
    public function RUrusanOpd($tahun, $kd_sub_unit, $sub_unit, $kd_urusan)
    {
        return $this->selectSUM('ta_totalrealisasi_opd.total_realisasi')
            ->join('ta_apbd_persubkegiatan', 'ta_apbd_persubkegiatan.kd_subkegiatan=ta_totalrealisasi_opd.kd_subkegiatan')
            ->where('ta_totalrealisasi_opd.tahun', $tahun)
            ->where('ta_totalrealisasi_opd.sub_unit', $sub_unit)
            ->where('ta_apbd_persubkegiatan.kd_urusan', $kd_urusan)
            ->where('ta_apbd_persubkegiatan.kd_sub_unit', $kd_sub_unit)

            ->where('ta_apbd_persubkegiatan.pagu_rincian<>', '0')
            ->groupBy('ta_apbd_persubkegiatan.kd_urusan')
            ->get()
            ->getRowArray();
    }
    public function RUrusanProgramOpd($tahun, $kd_sub_unit, $sub_unit, $kd_urusan, $kd_program)
    {
        return $this->selectSUM('ta_totalrealisasi_opd.total_realisasi')
            ->join('ta_apbd_persubkegiatan', 'ta_apbd_persubkegiatan.kd_subkegiatan=ta_totalrealisasi_opd.kd_subkegiatan')
            ->where('ta_totalrealisasi_opd.tahun', $tahun)
            ->where('ta_totalrealisasi_opd.sub_unit', $sub_unit)
            ->where('ta_apbd_persubkegiatan.kd_urusan', $kd_urusan)
            ->where('ta_apbd_persubkegiatan.kd_program', $kd_program)
            ->where('ta_apbd_persubkegiatan.kd_sub_unit', $kd_sub_unit)
            ->where('ta_apbd_persubkegiatan.pagu_rincian<>', '0')
            ->groupBy('ta_apbd_persubkegiatan.kd_program')
            ->get()
            ->getRowArray();
    }
    public function RUrusanProgramKegiatanOpd($tahun, $kd_sub_unit, $sub_unit, $kd_urusan, $kd_program, $kd_kegiatan)
    {
        return $this->selectSUM('ta_totalrealisasi_opd.total_realisasi')
            ->join('ta_apbd_persubkegiatan', 'ta_apbd_persubkegiatan.kd_subkegiatan=ta_totalrealisasi_opd.kd_subkegiatan')
            ->where('ta_totalrealisasi_opd.tahun', $tahun)
            ->where('ta_totalrealisasi_opd.sub_unit', $sub_unit)
            ->where('ta_apbd_persubkegiatan.kd_urusan', $kd_urusan)
            ->where('ta_apbd_persubkegiatan.kd_program', $kd_program)
            ->where('ta_apbd_persubkegiatan.kd_kegiatan', $kd_kegiatan)
            ->where('ta_apbd_persubkegiatan.kd_sub_unit', $kd_sub_unit)

            ->where('ta_apbd_persubkegiatan.pagu_rincian<>', '0')
            ->groupBy('ta_apbd_persubkegiatan.kd_program')
            ->get()
            ->getRowArray();
    }
}
