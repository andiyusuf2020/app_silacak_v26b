<?php

namespace App\Models;

use CodeIgniter\Model;

class RekapModel extends Model
{
    protected $table = 'rekap_sumber_dana'; // Sesuaikan dengan nama tabel di database Anda

    public function getRekapSubSkpd()
    {
        return $this->builder()
            ->select('
                nama_unit_skpd,
                
                -- 1. Jumlah total item NAMA SRO
                COUNT(DISTINCT nama_sro) AS jumlah_item_sro,
                
                -- 2. Jumlah item NAMA SRO yang terealisasi (> 0)
                COUNT(DISTINCT CASE WHEN total_realisasi > 0 THEN nama_sro ELSE NULL END) AS jumlah_item_sro_terealisasi,
                
                -- 3. Jumlah item NAMA SRO yang total realisasinya 0
                COUNT(DISTINCT CASE WHEN total_realisasi = 0 THEN nama_sro ELSE NULL END) AS jumlah_item_sro_realisasi_nol,
                
                -- 4. Jumlah Total Anggaran
                SUM(total_anggaran) AS total_anggaran,
                
                -- 5. Total Realisasi
                SUM(total_realisasi) AS total_realisasi,
                
                -- 6. Persentase Capaian Realisasi Anggaran
                ROUND(
                    CASE 
                        WHEN SUM(total_anggaran) > 0 
                        THEN (SUM(total_realisasi) / SUM(total_anggaran)) * 100 
                        ELSE 0 
                    END, 2
                ) AS persen_capaian_anggaran,
                
                -- 7. Persentase Capaian Jumlah Item SRO Terealisasi
                ROUND(
                    CASE 
                        WHEN COUNT(DISTINCT nama_sro) > 0 
                        THEN (COUNT(DISTINCT CASE WHEN total_realisasi > 0 THEN nama_sro ELSE NULL END) / COUNT(DISTINCT nama_sro)) * 100 
                        ELSE 0 
                    END, 2
                ) AS persen_capaian_sro
            ')
            ->groupBy('nama_unit_skpd')
            ->get()
            ->getResultArray();
    }
}
