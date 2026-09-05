<?php

namespace App\Models\LrfkProvModel;

use CodeIgniter\Model;

class TaRealisasiPendapatan extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'ta_realisasi_pendapatan';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $useSoftDeletes = true;
    protected $protectFields  = false;
    protected $allowedFields  = [
        'id',
        'tahun',
        'bulan',
        'kd_skpd',
        'sub_unit',
        'kd_akun',
        'nm_rekening',
        'pagu',
        'realisasi',
    ];
    protected $useTimestamps = true; // Tidak menggunakan timestamps
    protected $dateFormat    = 'date';
    protected $createdField  = 'create_at';
    protected $updatedField  = 'update_at';
    protected $deletedField  = 'delete_at';

    public function dataPerId($id)
    {
        return $this
            ->select('*')
            ->where('id', $id)
            ->where('delete_at', 0)

            ->get()
            ->getRowArray();
    }

    public function dataPerOPD($kdU, $tahun, $bln)
    {
        return $this
            ->select('*')
            ->where('kd_skpd', $kdU)
            ->where('tahun', $tahun)
            ->where('bulan', $bln)
            ->where('delete_at', 0)

            ->get()
            ->getResultArray();
    }
    public function cekperKdAkun($tahun, $bulan, $kd_skpd, $kd_akun)
    {
        return $this
            ->select('*')
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->where('kd_skpd', $kd_skpd)
            ->where('kd_akun', $kd_akun)
            ->where('delete_at', 0)

            ->get()
            ->getRowArray();
    }

    public function Rbulanakhirperakun($tahun, $bulanini, $kd_skpd, $kd_akun)
    {
        return $this
            ->select('*')
            ->where('tahun', $tahun)
            ->where('bulan', $bulanini)
            ->where('kd_skpd', $kd_skpd)
            ->where('kd_akun', $kd_akun)
            ->where('delete_at', 0)

            ->get()
            ->getRowArray();
    }
    public function RtotalperBln($tahun, $bulanini, $kd_skpd)
    {
        return $this
            ->selectsum('realisasi')
            ->where('tahun', $tahun)
            ->where('bulan', $bulanini)
            ->where('kd_skpd', $kd_skpd)
            ->where('delete_at', 0)
            ->groupBy('bulan')
            ->get()
            ->getRowArray();
    }
}
