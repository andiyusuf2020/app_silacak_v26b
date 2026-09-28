<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanRealisasiModel extends Model
{
    protected $table = 'rekap_sumber_dana';

    /**
     * Mengambil rekap capaian anggaran, capaian SRO, dan rasio konsistensi angkas.
     *
     * @return array
     */
    public function getRekapCapaianDanKonsistensi(): array
    {
        $db = \Config\Database::connect();

        // 1. Subquery Agregasi Tabel Angkas
        $subQueryAngkas = $db->table('angkas')
            ->select('kode_skpd')
            ->selectSum('nilai_angkas', 'total_nilai_angkas')
            ->selectSum('nilai_realisasi', 'total_realisasi_angkas')
            ->where('kode_skpd IS NOT NULL')
            ->groupBy('kode_skpd');

        // 2. Query Utama Query Builder CodeIgniter 4
        $builder = $db->table('rekap_sumber_dana r');
        $builder->select('
            r.nama_unit_skpd,
            COUNT(r.nama_sro) AS jumlah_item_sro,
            SUM(r.total_anggaran) AS total_anggaran,
            SUM(r.total_realisasi) AS total_realisasi,
            SUM(CASE WHEN r.total_realisasi = 0 THEN 1 ELSE 0 END) AS jumlah_sro_realisasi_nol,
            ROUND((SUM(r.total_realisasi) / SUM(r.total_anggaran)) * 100, 2) AS capaian_anggaran,
            ROUND(((COUNT(r.nama_sro) - SUM(CASE WHEN r.total_realisasi = 0 THEN 1 ELSE 0 END)) / COUNT(r.nama_sro)) * 100, 2) AS capaian_sro,
            ROUND(a.total_realisasi_angkas / NULLIF(a.total_nilai_angkas, 0), 4) AS rasio_konsistensi
        ');

        // JOIN Subquery Angkas
        $builder->join('(' . $subQueryAngkas->getCompiledSelect() . ') a', 'r.kode_skpd = a.kode_skpd', 'left');

        // Grouping berdasarkan Unit SKPD / Sub SKPD
        $builder->groupBy('r.kode_skpd, r.nama_unit_skpd, a.total_nilai_angkas, a.total_realisasi_angkas');

        return $builder->get()->getResultArray();
    }
}