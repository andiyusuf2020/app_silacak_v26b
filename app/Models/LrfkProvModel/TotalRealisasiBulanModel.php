<?php

namespace App\Models\LrfkProvModel;

use CodeIgniter\Model;

class TotalRealisasiBulanModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_totalrealisasibulan_opd';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $useSoftDeletes = true;
    protected $protectFields  = false;
    protected $allowedFields  = [
        'id',
        'tahun',
        'bulan',
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

    public function isiRealisasiperBulan($tahun, $kd_sub_unit, $sub_unit, $kd_subkegiatan, $nm_subkegiatan, $pagu_uraian, $isibulan, $bulan)
    {
        $cekRbulan = $this
            ->select('*')
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->where('kd_sub_unit', $kd_sub_unit)
            ->where('sub_unit', $sub_unit)
            ->where('kd_subkegiatan', $kd_subkegiatan)
            ->get()
            ->getRowArray();
        if ($cekRbulan == null) {
            if ($isibulan == '') {
                $Rbln = 0;
            } else {
                $Rbln = $isibulan;
            }
            $dataTR = [
                'tahun' => $tahun,
                'bulan' => $bulan,
                'kd_sub_unit' => $kd_sub_unit,
                'sub_unit' => $sub_unit,
                'kd_subkegiatan' => $kd_subkegiatan,
                'nm_subkegiatan' => $nm_subkegiatan,
                'pagu_subkeg' => $pagu_uraian,
                'total_realisasi' => $Rbln,
            ];

            //echo dd($dataTR);
            $this->save($dataTR);
        } else {
            $id = $cekRbulan['id'];
            if ($isibulan == '') {
                $Rbln = 0;
            } else {
                $Rbln = $isibulan;
            }
            $dataTR = [
                'id' => $id,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'kd_sub_unit' => $kd_sub_unit,
                'sub_unit' => $sub_unit,
                'kd_subkegiatan' => $kd_subkegiatan,
                'nm_subkegiatan' => $nm_subkegiatan,
                'pagu_subkeg' => $pagu_uraian,
                'total_realisasi' => $Rbln,
            ];
            //  echo dd($dataTR);

            $this->save($dataTR);
        }
    }
    public function cek($tahun, $bulan, $sub_unit, $kd_subkegiatan)
    {
        return $this
            ->select('*')
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->where('sub_unit', $sub_unit)
            ->where('kd_subkegiatan', $kd_subkegiatan)
            ->get()
            ->getRowArray();
    }
    public function total($tahun, $bulan,  $sub_unit)
    {
        return $this
            ->selectSUM('total_realisasi')
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->where('sub_unit', $sub_unit)
            ->groupBy('sub_unit')
            ->get()
            ->getRowArray();
    }
    public function RperUrusanOpd($tahun, $bulan, $kd_sub_unit, $kd_urusan)
    {
        return $this->selectSUM('ta_totalrealisasibulan_opd.total_realisasi')
            ->join('ta_apbd_persubkegiatan', 'ta_apbd_persubkegiatan.kd_subkegiatan=ta_totalrealisasibulan_opd.kd_subkegiatan')
            ->where('ta_totalrealisasibulan_opd.tahun', $tahun)
            ->where('ta_totalrealisasibulan_opd.bulan', $bulan)
            ->where('ta_apbd_persubkegiatan.kd_urusan', $kd_urusan)
            ->where('ta_totalrealisasibulan_opd.kd_sub_unit', $kd_sub_unit)
            ->where('ta_apbd_persubkegiatan.pagu_rincian<>', '0')
            ->groupBy('ta_apbd_persubkegiatan.kd_urusan')
            ->get()
            ->getRowArray();
    }

    public function RperProgramOpd($tahun, $bulan, $kd_sub_unit, $sub_unit)
    {
        return $this->selectSUM('ta_totalrealisasibulan_opd.total_realisasi')
            ->join('ta_apbd_persubkegiatan', 'ta_apbd_persubkegiatan.kd_subkegiatan=ta_totalrealisasibulan_opd.kd_subkegiatan')
            ->where('ta_totalrealisasibulan_opd.tahun', $tahun)
            ->where('ta_totalrealisasibulan_opd.bulan', $bulan)
            ->where('ta_totalrealisasibulan_opd.sub_unit', $sub_unit)
            ->where('ta_apbd_persubkegiatan.kd_sub_unit', $kd_sub_unit)

            ->where('ta_apbd_persubkegiatan.pagu_rincian<>', '0')
            ->groupBy('ta_apbd_persubkegiatan.kd_program')
            ->get()
            ->getResultArray();
    }
    public function RperKegiatanOpd($tahun, $bulan, $kd_sub_unit, $sub_unit)
    {
        return $this->selectSUM('ta_totalrealisasibulan_opd.total_realisasi')
            ->join('ta_apbd_persubkegiatan', 'ta_apbd_persubkegiatan.kd_subkegiatan=ta_totalrealisasibulan_opd.kd_subkegiatan')
            ->where('ta_totalrealisasibulan_opd.tahun', $tahun)
            ->where('ta_totalrealisasibulan_opd.bulan', $bulan)
            ->where('ta_totalrealisasibulan_opd.sub_unit', $sub_unit)
            ->where('ta_apbd_persubkegiatan.kd_sub_unit', $kd_sub_unit)

            ->where('ta_apbd_persubkegiatan.pagu_rincian<>', '0')
            ->groupBy('ta_apbd_persubkegiatan.kd_kegiatan')
            ->get()
            ->getResultArray();
    }
    public function RUrusanOpd($tahun, $bulan, $kd_sub_unit, $sub_unit, $kd_urusan)
    {
        return $this->selectSUM('ta_totalrealisasibulan_opd.total_realisasi')
            ->join('ta_apbd_persubkegiatan', 'ta_apbd_persubkegiatan.kd_subkegiatan=ta_totalrealisasibulan_opd.kd_subkegiatan')
            ->where('ta_totalrealisasibulan_opd.tahun', $tahun)
            ->where('ta_totalrealisasibulan_opd.bulan', $bulan)
            ->where('ta_totalrealisasibulan_opd.sub_unit', $sub_unit)
            ->where('ta_apbd_persubkegiatan.kd_urusan', $kd_urusan)
            //  ->where('ta_apbd_persubkegiatan.kd_program', $kd_program)
            ->where('ta_apbd_persubkegiatan.kd_sub_unit', $kd_sub_unit)

            ->where('ta_apbd_persubkegiatan.pagu_rincian<>', '0')
            ->groupBy('ta_apbd_persubkegiatan.kd_urusan')
            ->get()
            ->getRowArray();
    }
    public function RUrusanProgramOpd($tahun, $bulan, $kd_sub_unit, $sub_unit, $kd_urusan, $kd_program)
    {
        return $this->selectSUM('ta_totalrealisasibulan_opd.total_realisasi')
            ->join('ta_apbd_persubkegiatan', 'ta_apbd_persubkegiatan.kd_subkegiatan=ta_totalrealisasibulan_opd.kd_subkegiatan')
            ->where('ta_totalrealisasibulan_opd.tahun', $tahun)
            ->where('ta_totalrealisasibulan_opd.bulan', $bulan)
            ->where('ta_totalrealisasibulan_opd.sub_unit', $sub_unit)
            ->where('ta_apbd_persubkegiatan.kd_urusan', $kd_urusan)
            ->where('ta_apbd_persubkegiatan.kd_program', $kd_program)
            ->where('ta_apbd_persubkegiatan.kd_sub_unit', $kd_sub_unit)

            ->where('ta_apbd_persubkegiatan.pagu_rincian<>', '0')
            ->groupBy('ta_apbd_persubkegiatan.kd_program')
            ->get()
            ->getRowArray();
    }
    public function RUrusanProgramKegiatanOpd($tahun, $bulan, $kd_sub_unit, $sub_unit, $kd_urusan, $kd_program, $kd_kegiatan)
    {
        return $this->selectSUM('ta_totalrealisasibulan_opd.total_realisasi')
            ->join('ta_apbd_persubkegiatan', 'ta_apbd_persubkegiatan.kd_subkegiatan=ta_totalrealisasibulan_opd.kd_subkegiatan')
            ->where('ta_totalrealisasibulan_opd.tahun', $tahun)
            ->where('ta_totalrealisasibulan_opd.bulan', $bulan)
            ->where('ta_totalrealisasibulan_opd.sub_unit', $sub_unit)
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
