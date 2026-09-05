<?php

namespace App\Models\CapkinModel;

use CodeIgniter\Model;

class TaSubKegCapkin2026 extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_subkeg_mappingcapkin';
    protected $primaryKey           = 'id_skcapkin';
    protected $useAutoIncrement     = true;
    protected $allowedFields  = [
        // 'id_skcapkin',
        'tahun',
        'bulan',
        'tgldata',
        'kd_sub_skpd',
        'nm_sub_skpd',
        'kd_program',
        'nm_program',
        'kd_kegiatan',
        'nm_kegiatan',
        'kd_sub_giat',
        'nm_sub_giat',
        'total_anggaran',
        'total_realisasi',
        'total_realisasi_spj',
    ];
    protected $useSoftDeletes = true;
    protected $useTimestamps = true; // Tidak menggunakan timestamps
    protected $dateFormat    = 'date';
    protected $createdField  = 'create_at';
    protected $updatedField  = 'update_at';
    protected $deletedField  = 'delete_at';

    // Fungsi untuk insert batch data
    public function insertBatchData(array $data)
    {
        return $this->insertBatch($data);
    }
    public function updateBatchData(array $data, $id)
    {
        return $this->updateBatch($data, $id);
    }
    public function DataPerSKPerSkpdpertahun($tahun, $kd_sub_skpd)
    {
        return $this
            ->select('ta_subkeg_mappingcapkin.*')
            ->where('ta_subkeg_mappingcapkin.tahun', $tahun)
            // ->where('ta_subkeg_mappingcapkin.bulan', $bulan)
            // ->where('ta_subkeg_mappingcapkin.tgldata', $tgldata)
            ->where('ta_subkeg_mappingcapkin.kd_sub_skpd', $kd_sub_skpd)
            ->where('ta_subkeg_mappingcapkin.delete_at=', 0)
            ->groupBy('ta_subkeg_mappingcapkin.kd_sub_giat')
            ->get()
            ->getResultArray();
    }
    public function DataPerSKPerSkpdpertahunPerIdPrio($tahun, $kd_sub_skpd, $idProgPrio)
    {
        return $this
            ->select('ta_subkeg_mappingcapkin.*')
            ->where('ta_subkeg_mappingcapkin.tahun', $tahun)
            // ->where('ta_subkeg_mappingcapkin.bulan', $bulan)
            // ->where('ta_subkeg_mappingcapkin.tgldata', $tgldata)
            ->where('ta_subkeg_mappingcapkin.kd_sub_skpd', $kd_sub_skpd)
            ->where('ta_subkeg_mappingcapkin.id_progprioritas', $idProgPrio)
            ->where('ta_subkeg_mappingcapkin.delete_at=', 0)
            ->groupBy('ta_subkeg_mappingcapkin.kd_sub_giat')
            ->get()
            ->getResultArray();
    }
    public function DataPerSKPerSkpd($tahun, $bulan, $tgldata, $kd_sub_skpd)
    {
        return $this
            ->select('ta_subkeg_mappingcapkin.*')
            // ->select('b.*')
            // ->join(
            //     'ta_realisasi_apbd b',
            //     'ta_subkeg_mappingcapkin.kd_sub_giat = b.KODE_SUB_GIAT AND ta_subkeg_mappingcapkin.tahun = b.TAHUN 
            //     AND ta_subkeg_mappingcapkin.bulan = b.BULAN 
            //     AND ta_subkeg_mappingcapkin.tgldata = b.CREATE_AT 
            //     AND ta_subkeg_mappingcapkin.kd_sub_skpd = b.KODE_UNIT_SKPD',
            //     'left'
            // )
            ->where('ta_subkeg_mappingcapkin.tahun', $tahun)
            // ->where('ta_subkeg_mappingcapkin.bulan', $bulan)
            // ->where('ta_subkeg_mappingcapkin.tgldata', $tgldata)
            ->where('ta_subkeg_mappingcapkin.kd_sub_skpd', $kd_sub_skpd)
            ->where('ta_subkeg_mappingcapkin.delete_at=', 0)

            ->groupBy('ta_subkeg_mappingcapkin.kd_sub_giat')
            ->get()
            ->getResultArray();
    }
    public function DataBelanjaPerSKPerSkpd2($tahun, $bulan, $tgldata, $kd_sub_skpd, $kd_sub_giat)
    {
        return $this
            ->select('ta_subkeg_mappingcapkin.*')
            ->select('b.*')
            ->join(
                'ta_realisasi_apbd b',
                'ta_subkeg_mappingcapkin.kd_sub_giat = b.KODE_SUB_GIAT AND ta_subkeg_mappingcapkin.tahun = b.TAHUN 
                AND ta_subkeg_mappingcapkin.bulan = b.BULAN 
                AND ta_subkeg_mappingcapkin.tgldata = b.CREATE_AT 
                AND ta_subkeg_mappingcapkin.kd_sub_skpd = b.KODE_UNIT_SKPD
                AND ta_subkeg_mappingcapkin.kd_sub_giat = b.KODE_SUB_GIAT',
                // 'left'
            )
            ->where('ta_subkeg_mappingcapkin.tahun', $tahun)
            ->where('ta_subkeg_mappingcapkin.bulan', $bulan)
            ->where('ta_subkeg_mappingcapkin.tgldata', $tgldata)
            ->where('ta_subkeg_mappingcapkin.kd_sub_skpd', $kd_sub_skpd)
            ->where('ta_subkeg_mappingcapkin.delete_at=', 0)
            ->where('ta_subkeg_mappingcapkin.kd_sub_giat', $kd_sub_giat)
            // ->where('b.TOTAL_REALISASI =', 0)
            ->get()
            ->getResultArray();
    }
    public function DataBelanjaPerSKPerSkpd($tahun, $bulan, $tgldata, $kd_sub_skpd, $kd_sub_giat, $total_realisasi = false)
    {
        if ($total_realisasi == false) {

            return $this
                ->select('ta_subkeg_mappingcapkin.*')
                ->select('b.*')
                ->join(
                    'ta_realisasi_apbd b',
                    'ta_subkeg_mappingcapkin.kd_sub_giat = b.KODE_SUB_GIAT AND ta_subkeg_mappingcapkin.tahun = b.TAHUN 
                AND ta_subkeg_mappingcapkin.bulan = b.BULAN 
                AND ta_subkeg_mappingcapkin.tgldata = b.CREATE_AT 
                AND ta_subkeg_mappingcapkin.kd_sub_skpd = b.KODE_UNIT_SKPD
                AND ta_subkeg_mappingcapkin.kd_sub_giat = b.KODE_SUB_GIAT',
                    'left'
                )
                ->where('ta_subkeg_mappingcapkin.tahun', $tahun)
                ->where('ta_subkeg_mappingcapkin.bulan', $bulan)
                ->where('ta_subkeg_mappingcapkin.tgldata', $tgldata)
                ->where('ta_subkeg_mappingcapkin.kd_sub_skpd', $kd_sub_skpd)
                ->where('ta_subkeg_mappingcapkin.delete_at=', 0)
                ->where('ta_subkeg_mappingcapkin.kd_sub_giat', $kd_sub_giat)
                // ->where('b.TOTAL_REALISASI =', 0)

                ->get()
                ->getResultArray();
        } else {
            return $this
                ->select('ta_subkeg_mappingcapkin.*')
                ->select('b.*')
                ->join(
                    'ta_realisasi_apbd b',
                    'ta_subkeg_mappingcapkin.kd_sub_giat = b.KODE_SUB_GIAT AND ta_subkeg_mappingcapkin.tahun = b.TAHUN 
                AND ta_subkeg_mappingcapkin.bulan = b.BULAN 
                AND ta_subkeg_mappingcapkin.tgldata = b.CREATE_AT 
                AND ta_subkeg_mappingcapkin.kd_sub_skpd = b.KODE_UNIT_SKPD
                AND ta_subkeg_mappingcapkin.kd_sub_giat = b.KODE_SUB_GIAT',
                    'left'
                )
                ->where('ta_subkeg_mappingcapkin.tahun', $tahun)
                ->where('ta_subkeg_mappingcapkin.bulan', $bulan)
                ->where('ta_subkeg_mappingcapkin.tgldata', $tgldata)
                ->where('ta_subkeg_mappingcapkin.kd_sub_skpd', $kd_sub_skpd)
                ->where('ta_subkeg_mappingcapkin.delete_at=', 0)
                ->where('ta_subkeg_mappingcapkin.kd_sub_giat', $kd_sub_giat)
                ->where('b.TOTAL_REALISASI >', 0)
                ->get()
                ->getResultArray();
        }
    }

    // public function DataPerSKPerSkpdSubGiat($tahun, $bulan, $tgldata, $kd_sub_skpd, $kd_sub_giat)
    public function DataPerSKPerSkpdSubGiat($tahun, $kd_sub_skpd, $kd_sub_giat)
    {
        return $this
            ->select('*')
            ->where('tahun', $tahun)
            // ->where('bulan', $bulan)
            // ->where('tgldata', $tgldata)
            ->where('kd_sub_skpd', $kd_sub_skpd)
            ->where('kd_sub_giat', $kd_sub_giat)
            ->where('delete_at=', 0)
            ->get()
            ->getRowArray();
    }
}
