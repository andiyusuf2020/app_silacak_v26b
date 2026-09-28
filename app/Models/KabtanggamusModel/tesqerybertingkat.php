<?php

namespace App\Models;


use CodeIgniter\Model;

class AnggaranModel extends Model
{
    protected $table = 'nama_tabel_anda';

    public function getRekapLengkapPerTingkat()
    {
        // Query agregat dasar per Sub-Kegiatan
        $data = $this->select('
        `KODE_SKPD`, `NAMA_SKPD`,
        `KODE_PROGRAM`, `NAMA_PROGRAM`,
        `KODE_GIAT`, `NAMA_GIAT`,
        `KODE_SUB_GIAT`, `NAMA_SUB_GIAT`,
        SUM(`TOTAL_ANGGARAN`) AS total_anggaran,
        SUM(`TOTAL_REALISASI`) AS total_realisasi,
        COUNT(`KODE_SRO`) AS jumlah_sro,
        SUM(CASE WHEN `TOTAL_REALISASI` = 0 THEN 1 ELSE 0 END) AS sro_nol
        ')
            ->groupBy([
                'KODE_SKPD',
                'NAMA_SKPD',
                'KODE_PROGRAM',
                'NAMA_PROGRAM',
                'KODE_GIAT',
                'NAMA_GIAT',
                'KODE_SUB_GIAT',
                'NAMA_SUB_GIAT'
            ])
            ->orderBy('KODE_SKPD', 'ASC')
            ->orderBy('KODE_PROGRAM', 'ASC')
            ->orderBy('KODE_GIAT', 'ASC')
            ->orderBy('KODE_SUB_GIAT', 'ASC')
            ->findAll();

        $rekap = [];

        foreach ($data as $row) {
            $skpd = $row['NAMA_SKPD'];
            $prog = $row['NAMA_PROGRAM'];
            $giat = $row['NAMA_GIAT'];
            $anggaran = (float)$row['total_anggaran'];
            $realisasi = (float)$row['total_realisasi'];
            $sroTotal = (int)$row['jumlah_sro'];
            $sroNol = (int)$row['sro_nol'];

            // Struktur SKPD
            if (!isset($rekap[$skpd])) {
                $rekap[$skpd] = [
                    'kode' => $row['KODE SKPD'],
                    'anggaran' => 0,
                    'realisasi' => 0,
                    'sro' => 0,
                    'sro_nol' => 0,
                    'program' => []
                ];
            }

            // Struktur Program
            if (!isset($rekap[$skpd]['program'][$prog])) {
                $rekap[$skpd]['program'][$prog] = [
                    'kode' => $row['KODE PROGRAM'],
                    'anggaran' => 0,
                    'realisasi' => 0,
                    'sro' => 0,
                    'sro_nol' => 0,
                    'kegiatan' => []
                ];
            }

            // Struktur Kegiatan
            if (!isset($rekap[$skpd]['program'][$prog]['kegiatan'][$giat])) {
                $rekap[$skpd]['program'][$prog]['kegiatan'][$giat] = [
                    'kode' => $row['KODE GIAT'],
                    'anggaran' => 0,
                    'realisasi' => 0,
                    'sro' => 0,
                    'sro_nol' => 0,
                    'sub_kegiatan' => []
                ];
            }

            // Sub-Kegiatan
            $rekap[$skpd]['program'][$prog]['kegiatan'][$giat]['sub_kegiatan'][] = [
                'kode' => $row['KODE SUB GIAT'],
                'nama' => $row['NAMA SUB GIAT'],
                'anggaran' => $anggaran,
                'realisasi' => $realisasi,
                'capaian_realisasi' => $anggaran > 0 ? round(($realisasi / $anggaran) * 100, 2) : 0,
                'sro' => $sroTotal,
                'sro_nol' => $sroNol,
                'capaian_sro' => $sroTotal > 0 ? round((($sroTotal - $sroNol) / $sroTotal) * 100, 2) : 0
            ];

            // Akumulasi Anggaran & SRO Ke Tingkat Atas
            foreach ([&$rekap[$skpd]['program'][$prog]['kegiatan'][$giat], &$rekap[$skpd]['program'][$prog], &$rekap[$skpd]] as &$node) {
                $node['anggaran'] += $anggaran;
                $node['realisasi'] += $realisasi;
                $node['sro'] += $sroTotal;
                $node['sro_nol'] += $sroNol;
            }
        }

        return $rekap;
    }

    public function getRekapLengkapPerTingkatAman($bulan, $kdSU)
    {
        // Query agregat dasar per Sub-Kegiatan
        $data = $this->select('
            KODE_UNIT_SKPD,
            NAMA_UNIT_SKPD,
            KODE_PROGRAM,
            NAMA_PROGRAM,
            KODE_GIAT,
            NAMA_GIAT,
            KODE_SUB_GIAT,
            NAMA_SUB_GIAT,
            SUM(TOTAL_ANGGARAN) AS total_anggaran,
            SUM(TOTAL_REALISASI) AS total_realisasi,
            COUNT(KODE_SRO) AS jumlah_sro,
            SUM(CASE WHEN TOTAL_REALISASI = 0 THEN 1 ELSE 0 END) AS sro_nol
        ')
            ->where('BULAN', $bulan)
            ->where('KODE_UNIT_SKPD', $kdSU) // Disesuaikan menggunakan KODE_UNIT_SKPD
            ->groupBy([
                'KODE_UNIT_SKPD',
                'NAMA_UNIT_SKPD',
                'KODE_PROGRAM',
                'NAMA_PROGRAM',
                'KODE_GIAT',
                'NAMA_GIAT',
                'KODE_SUB_GIAT',
                'NAMA_SUB_GIAT'
            ])
            ->orderBy('KODE_PROGRAM', 'ASC')
            ->orderBy('KODE_GIAT', 'ASC')
            ->orderBy('KODE_SUB_GIAT', 'ASC')
            ->get()
            ->getResultArray();

        $rekap = [];

        foreach ($data as $row) {
            $kdSkpd    = $row['KODE_UNIT_SKPD'] ?? $row['NAMA_UNIT_SKPD'];
            $kdProg    = $row['KODE_PROGRAM'];
            $kdGiat    = $row['KODE_GIAT'];
            $kdSubGiat = $row['KODE_SUB_GIAT'];

            $anggaran  = (float)$row['total_anggaran'];
            $realisasi = (float)$row['total_realisasi'];
            $sroTotal  = (int)$row['jumlah_sro'];
            $sroNol    = (int)$row['sro_nol'];

            // 1. Inisialisasi Level SKPD
            if (!isset($rekap[$kdSkpd])) {
                $rekap[$kdSkpd] = [
                    'kode' => $kdSkpd,
                    'nama' => $row['NAMA_UNIT_SKPD'],
                    'anggaran' => 0,
                    'realisasi' => 0,
                    'sro' => 0,
                    'sro_nol' => 0,
                    'program' => []
                ];
            }

            // 2. Inisialisasi Level Program
            if (!isset($rekap[$kdSkpd]['program'][$kdProg])) {
                $rekap[$kdSkpd]['program'][$kdProg] = [
                    'kode' => $kdProg,
                    'nama' => $row['NAMA_PROGRAM'],
                    'anggaran' => 0,
                    'realisasi' => 0,
                    'sro' => 0,
                    'sro_nol' => 0,
                    'kegiatan' => []
                ];
            }

            // 3. Inisialisasi Level Kegiatan
            if (!isset($rekap[$kdSkpd]['program'][$kdProg]['kegiatan'][$kdGiat])) {
                $rekap[$kdSkpd]['program'][$kdProg]['kegiatan'][$kdGiat] = [
                    'kode' => $kdGiat,
                    'nama' => $row['NAMA_GIAT'],
                    'anggaran' => 0,
                    'realisasi' => 0,
                    'sro' => 0,
                    'sro_nol' => 0,
                    'sub_kegiatan' => []
                ];
            }

            // 4. Sub-Kegiatan
            $rekap[$kdSkpd]['program'][$kdProg]['kegiatan'][$kdGiat]['sub_kegiatan'][$kdSubGiat] = [
                'kode' => $kdSubGiat,
                'nama' => $row['NAMA_SUB_GIAT'],
                'anggaran' => $anggaran,
                'realisasi' => $realisasi,
                'capaian_realisasi' => $this->hitungPersen($realisasi, $anggaran),
                'sro' => $sroTotal,
                'sro_nol' => $sroNol,
                'capaian_sro' => $this->hitungPersen($sroTotal - $sroNol, $sroTotal)
            ];

            // 5. Akumulasi Anggaran & SRO Ke Tingkat Atas
            $nodes = [
                &$rekap[$kdSkpd]['program'][$kdProg]['kegiatan'][$kdGiat],
                &$rekap[$kdSkpd]['program'][$kdProg],
                &$rekap[$kdSkpd]
            ];

            foreach ($nodes as &$node) {
                $node['anggaran']  += $anggaran;
                $node['realisasi'] += $realisasi;
                $node['sro']       += $sroTotal;
                $node['sro_nol']   += $sroNol;
            }
            unset($node, $nodes); // Mencegah memory side-effect dari reference
        }

        // 6. Hitung persentase capaian di level Kegiatan, Program, dan SKPD
        foreach ($rekap as &$skpd) {
            $skpd['capaian_realisasi'] = $this->hitungPersen($skpd['realisasi'], $skpd['anggaran']);
            $skpd['capaian_sro']       = $this->hitungPersen($skpd['sro'] - $skpd['sro_nol'], $skpd['sro']);

            foreach ($skpd['program'] as &$prog) {
                $prog['capaian_realisasi'] = $this->hitungPersen($prog['realisasi'], $prog['anggaran']);
                $prog['capaian_sro']       = $this->hitungPersen($prog['sro'] - $prog['sro_nol'], $prog['sro']);

                foreach ($prog['kegiatan'] as &$giat) {
                    $giat['capaian_realisasi'] = $this->hitungPersen($giat['realisasi'], $giat['anggaran']);
                    $giat['capaian_sro']       = $this->hitungPersen($giat['sro'] - $giat['sro_nol'], $giat['sro']);
                }
                unset($giat);
            }
            unset($prog);
        }
        unset($skpd);

        return $rekap;
    }

    /**
     * Helper function internal untuk menghitung persentase
     */
    private function hitungPersen($pembilang, $penyebut)
    {
        return $penyebut > 0 ? round(($pembilang / $penyebut) * 100, 2) : 0;
    }
}
