<?php

namespace App\Models\RupModel;

use CodeIgniter\Model;

class SirupModel extends Model
{
    protected $table = 'ta_ruppengadaan';
    protected $primaryKey = 'id';
    protected $returnType     = 'array'; // Tipe data yang dikembalikan
    protected $allowedFields = [
        'id',
        'tahun',
        'bulan',
        'Cara_Pengadaan',
        'Jenis_Pengadaan',
        'Kode_RUP',
        'Metode_Pengadaan',
        'Nama_Instansi',
        'Nama_Paket',
        'Nama_Satuan_Kerja',
        'Produk_Dalam_Negeri',
        'Sumber_Dana',
        'Tahun_Anggaran',
        'Total_Nilai',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'create_at';
    protected $updatedField = 'update_at';


    // Fungsi untuk insert batch data
    public function insertBatchData(array $data)
    {
        return $this->insertBatch($data);
    }
    public function rupopd($tahun, $bulan, $subunit)
    {
        return $this->select('*')
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->where('Nama_Satuan_Kerja', $subunit)
            ->get();
    }
    public function rupopdall($tahun, $bulan)
    {
        return $this->select('*')
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->get();
    }


    public function jmldatarup($tahun, $bulan, $subunit = null)
    {
        if ($subunit == null) {
            return $this
                // ->select('*')
                ->selectSum('Total_Nilai', 'total_anggaran')
                ->selectCount('id', 'jumlah_paket')
                ->where('tahun', $tahun)
                ->where('bulan', $bulan);
        } else {
            return $this
                // ->select('*')
                ->selectSum('Total_Nilai', 'total_anggaran')
                ->selectCount('id', 'jumlah_paket')
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->where('Nama_Satuan_Kerja', $subunit);
        }
    }
    public function rupopdcara($tahun, $bulan, $subunit = null, $cara)
    {
        if ($subunit == null) {
            return $this->select('*')
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->where('Cara_Pengadaan', $cara)
                ->get()->getResultArray();
        } else {
            return $this->select('*')
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->where('Nama_Satuan_Kerja', $subunit)
                ->where('Cara_Pengadaan', $cara)
                ->get();
        }
    }
    public function rupopdmetoda($tahun, $bulan, $subunit = null, $metoda)
    {
        if ($subunit == null) {
            return $this->select('*')
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->where('Metode_Pengadaan', $metoda)
                ->get()->getResultArray();;
        } else {
            return $this->select('*')
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->where('Nama_Satuan_Kerja', $subunit)
                ->where('Metode_Pengadaan', $metoda)
                ->get();
        }
    }
    public function rupopdmetode($tahun, $bulan, $subunit = null)
    {
        if ($subunit == null) {
            return $this->select('*')
                ->selectSum('Total_Nilai', 'total_anggaran')
                ->selectCount('id', 'jumlah_paket')
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                // ->where('Metode_Pengadaan <>', '-')
                ->groupBy('Metode_Pengadaan')
                ->get();
        } else {
            return $this->select('*')
                ->selectSum('Total_Nilai', 'total_anggaran')
                ->selectCount('id', 'jumlah_paket')
                ->where('tahun', $tahun)
                ->where('Nama_Satuan_Kerja', $subunit)
                // ->where('Metode_Pengadaan <>', '-')
                ->groupBy('Metode_Pengadaan')
                ->get();
        }
    }
}
