<?php

namespace App\Models\RupModel;

use CodeIgniter\Model;

class RealRupModel extends Model
{
    protected $table = 'ta_real_ruppengadaan';
    protected $primaryKey = 'id';
    protected $returnType     = 'array'; // Tipe data yang dikembalikan
    protected $allowedFields = [
        'tahun',
        'bulan',
        'Jenis_Pengadaan',
        'Kode_Paket',
        'Kode_RUP',
        'Metode_Pengadaan',
        'Nama_Instansi',
        'Nama_Paket',
        'Nama_Penyedia',
        'Nama_Satuan_Kerja',
        'Nilai_PDN',
        'Status_Paket',
        'Sumber_Dana',
        'Sumber_Transaksi',
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
    public function getDataOpd($tahun, $bulan, $periode)
    {
        return $this->select('*')
            ->selectSum('Total_Nilai', 'total_anggaran')
            ->selectSum('Nilai_PDN', 'total_pdn')
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->where('periode', $periode)
            ->groupBy('Nama_Satuan_Kerja')
            ->get();
    }
    public function realrupopd($tahun, $bulan, $subunit = null)
    {
        if ($subunit == null) {

            return $this->select('*')
                ->selectSum('Total_Nilai', 'total_anggaran')
                ->selectSum('Nilai_PDN', 'total_pdn')
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->groupBy('Kode_RUP')
                ->get();
        } else {
            return $this->select('*')
                ->selectSum('Total_Nilai', 'total_anggaran')
                ->selectSum('Nilai_PDN', 'total_pdn')
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->where('Nama_Satuan_Kerja', $subunit)
                ->groupBy('Kode_RUP')
                ->get();
        }
    }
    public function realrupopdmetode($tahun, $bulan, $subunit = null)
    {
        if ($subunit == null) {
            return $this->select('*')
                ->selectSum('Total_Nilai', 'total_anggaran')
                ->selectSum('Nilai_PDN', 'total_pdn')
                ->selectCount('id', 'jumlah_paket')
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->groupBy('Metode_Pengadaan')
                ->get();
        } else {
            return $this->select('*')
                ->selectSum('Total_Nilai', 'total_anggaran')
                ->selectSum('Nilai_PDN', 'total_pdn')
                ->selectCount('id', 'jumlah_paket')
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->where('Nama_Satuan_Kerja', $subunit)
                ->groupBy('Metode_Pengadaan')
                ->get();
        }
    }
    public function getRealisasiRupByMetodePengadaan($tahun, $bulan, $subunit = null, $metodePengadaan)
    {
        if ($subunit == null) {
            return $this->select('*')
                ->selectSum('Total_Nilai', 'total_anggaran')
                ->selectSum('Nilai_PDN', 'total_pdn')
                ->selectCount('id', 'jumlah_paket')
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->where('Metode_Pengadaan', $metodePengadaan)
                // ->whereIn('Status_Paket', ['', 'SELESAI', 'COMPLETED', 'PAKET SELESAI', 'PAYMENT OUTSIDE SYSTEM'])
                ->groupBy('Metode_Pengadaan')
                ->get()
                ->getRowArray();
        } else {
            return $this->select('*')
                ->selectSum('Total_Nilai', 'total_anggaran')
                ->selectSum('Nilai_PDN', 'total_pdn')
                ->selectCount('id', 'jumlah_paket')
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->where('Nama_Satuan_Kerja', $subunit)
                ->where('Metode_Pengadaan', $metodePengadaan)
                // ->whereNotIn('Status_Paket', ['SELESAI', 'COMPLETED', 'PAKET SELESAI', 'PAYMENT OUTSIDE SYSTEM'])
                ->groupBy('Metode_Pengadaan')
                ->get()
                ->getRowArray();
        }
    }
    public function getRealisasiRupByMetodePengadaanSelesai($tahun, $bulan, $subunit = null, $metodePengadaan)
    {
        if ($subunit == null) {
            return $this->select('*')
                ->selectSum('Total_Nilai', 'total_anggaran')
                ->selectSum('Nilai_PDN', 'total_pdn')
                ->selectCount('id', 'jumlah_paket')
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->where('Metode_Pengadaan', $metodePengadaan)
                ->whereIn('Status_Paket', ['SELESAI', 'COMPLETED', 'PAKET SELESAI', 'PAYMENT OUTSIDE SYSTEM', ''])
                ->groupBy('Metode_Pengadaan')
                ->get()
                ->getRowArray();
        } else {
            return $this->select('*')
                ->selectSum('Total_Nilai', 'total_anggaran')
                ->selectSum('Nilai_PDN', 'total_pdn')
                ->selectCount('id', 'jumlah_paket')
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->where('Nama_Satuan_Kerja', $subunit)
                ->where('Metode_Pengadaan', $metodePengadaan)
                ->whereIn('Status_Paket', ['SELESAI', 'COMPLETED', 'PAKET SELESAI', 'PAYMENT OUTSIDE SYSTEM', ''])
                ->groupBy('Metode_Pengadaan')
                ->get()
                ->getRowArray();
        }
    }

    public function getRealisasiRupByMetodePengadaanProses($tahun, $bulan, $subunit, $metodePengadaan)
    {
        if ($subunit == null) {
            return $this->select('*')
                ->selectSum('Total_Nilai', 'total_anggaran')
                ->selectSum('Nilai_PDN', 'total_pdn')
                ->selectCount('id', 'jumlah_paket')
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->where('Metode_Pengadaan', $metodePengadaan)
                ->whereNotIn('Status_Paket', ['', 'SELESAI', 'COMPLETED', 'PAKET SELESAI', 'PAYMENT OUTSIDE SYSTEM'])
                ->groupBy('Metode_Pengadaan')
                ->get()
                ->getRowArray();
        } else {
            return $this->select('*')
                ->selectSum('Total_Nilai', 'total_anggaran')
                ->selectSum('Nilai_PDN', 'total_pdn')
                ->selectCount('id', 'jumlah_paket')
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->where('Nama_Satuan_Kerja', $subunit)
                ->where('Metode_Pengadaan', $metodePengadaan)
                ->whereNotIn('Status_Paket', ['', 'SELESAI', 'COMPLETED', 'PAKET SELESAI', 'PAYMENT OUTSIDE SYSTEM'])
                ->groupBy('Metode_Pengadaan')
                ->get()
                ->getRowArray();
        }
    }
    public function getTotalSelesai($tahun, $bulan, $subunit = null)
    {
        if ($subunit == null) {
            return $this
                // ->select('*')
                ->selectSum('Total_Nilai', 'total_anggaran')
                ->selectSum('Nilai_PDN', 'total_pdn')
                ->selectCount('id', 'jumlah_paket')
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->whereIn('Status_Paket', ['SELESAI', 'COMPLETED', 'PAKET SELESAI', 'PAYMENT OUTSIDE SYSTEM'])
                ->get()
                ->getRowArray();
        } else {
            return $this->select('*')
                ->selectSum('Total_Nilai', 'total_anggaran')
                ->selectSum('Nilai_PDN', 'total_pdn')
                ->selectCount('id', 'jumlah_paket')
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->where('Nama_Satuan_Kerja', $subunit)
                ->whereIn('Status_Paket', ['SELESAI', 'COMPLETED', 'PAKET SELESAI', 'PAYMENT OUTSIDE SYSTEM'])
                ->get()
                ->getRowArray();
        }
    }
    public function getTotalProses($tahun, $subunit = null)
    {
        if ($subunit == null) {
            return $this
                ->select('*')
                ->selectSum('Total_Nilai', 'total_anggaran')
                ->selectSum('Nilai_PDN', 'total_pdn')
                ->selectCount('id', 'jumlah_paket')
                ->where('tahun', $tahun)
                ->whereNotIn('Status_Paket', ['SELESAI', 'COMPLETED'])
                ->get()
                ->getRowArray();
        } else {
            return $this->select('*')
                ->selectSum('Total_Nilai', 'total_anggaran')
                ->selectSum('Nilai_PDN', 'total_pdn')
                ->selectCount('id', 'jumlah_paket')
                ->where('tahun', $tahun)
                ->where('Nama_Satuan_Kerja', $subunit)
                ->whereNotIn('Status_Paket', ['SELESAI', 'COMPLETED'])
                ->get()
                ->getRowArray();
        }
    }
}
