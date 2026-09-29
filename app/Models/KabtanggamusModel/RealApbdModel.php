<?php

namespace App\Models\KabtanggamusModel;

use CodeIgniter\Model;

class RealApbdModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'kabtanggamus_ta_sipd';
    protected $primaryKey           = 'NO';
    protected $useAutoIncrement     = true;
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'NO',
        'TAHUN',
        'BULAN',
        'KODE_SKPD',
        'NAMA_SKPD',
        'KODE_UNIT_SKPD',
        'NAMA_UNIT_SKPD',
        'KODE_URUSAN',
        'NAMA_URUSAN',
        'KODE_BIDANG_URUSAN',
        'NAMA_BIDANG_URUSAN',
        'KODE_PROGRAM',
        'NAMA_PROGRAM',
        'KODE_GIAT',
        'NAMA_GIAT',
        'KODE_SUB_GIAT',
        'NAMA_SUB_GIAT',
        'KODE_AKUN',
        'NAMA_AKUN',
        'KODE_KELOMPOK',
        'NAMA_KELOMPOK',
        'KODE_JENIS',
        'NAMA_JENIS',
        'KODE_OBJEK',
        'NAMA_OBJEK',
        'KODE_RINCIAN_OBJEK',
        'NAMA_RINCIAN_OBJEK',
        'KODE_SRO',
        'NAMA_SRO',
        'TOTAL_ANGGARAN',
        'TOTAL_REALISASI',
        'SELISIH',
        'KETERANGAN',
        'REALISASI_SPJ',
        'CREATE_AT',
        'UPDATE_AT',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'CREATE_AT';
    protected $updatedField = 'UPDATE_AT';


    public function getRekapLengkapPerTingkatAman($bulan, $kdSU)
    {
        // 1. Query mengambil data hingga level SRO
        $builder = $this->select('
            KODE_UNIT_SKPD,
            NAMA_UNIT_SKPD,
            KODE_PROGRAM,
            NAMA_PROGRAM,
            KODE_GIAT,
            NAMA_GIAT,
            KODE_SUB_GIAT,
            NAMA_SUB_GIAT,
            KODE_SRO,
            NAMA_SRO,
            SUM(TOTAL_ANGGARAN) AS total_anggaran,
            SUM(TOTAL_REALISASI) AS total_realisasi
        ')
            ->where('BULAN', $bulan);

        // Filter fleksibel untuk Kode atau Nama Unit SKPD
        $builder->groupStart()
            ->where('KODE_UNIT_SKPD', $kdSU)
            ->orWhere('NAMA_UNIT_SKPD', $kdSU)
            ->groupEnd();

        $data = $builder->groupBy([
            'KODE_UNIT_SKPD',
            'NAMA_UNIT_SKPD',
            'KODE_PROGRAM',
            'NAMA_PROGRAM',
            'KODE_GIAT',
            'NAMA_GIAT',
            'KODE_SUB_GIAT',
            'NAMA_SUB_GIAT',
            'KODE_SRO',
            'NAMA_SRO'
        ])
            ->orderBy('KODE_PROGRAM', 'ASC')
            ->orderBy('KODE_GIAT', 'ASC')
            ->orderBy('KODE_SUB_GIAT', 'ASC')
            ->orderBy('KODE_SRO', 'ASC')
            ->get()
            ->getResultArray();

        if (empty($data)) {
            return [];
        }

        $rekap = [];

        foreach ($data as $row) {
            $kdSkpd    = !empty($row['KODE_UNIT_SKPD']) ? $row['KODE_UNIT_SKPD'] : $row['NAMA_UNIT_SKPD'];
            $kdProg    = $row['KODE_PROGRAM'];
            $kdGiat    = $row['KODE_GIAT'];
            $kdSubGiat = $row['KODE_SUB_GIAT'];
            $kdSro     = $row['KODE_SRO'];

            $anggaran  = (float)$row['total_anggaran'];
            $realisasi = (float)$row['total_realisasi'];
            $isSroNol  = ($realisasi == 0) ? 1 : 0;

            // 1. Inisialisasi Level SKPD
            if (!isset($rekap[$kdSkpd])) {
                $rekap[$kdSkpd] = [
                    'kode'      => $kdSkpd,
                    'nama'      => $row['NAMA_UNIT_SKPD'],
                    'anggaran'  => 0,
                    'realisasi' => 0,
                    'sro'       => 0,
                    'sro_nol'   => 0,
                    'program'   => []
                ];
            }

            // 2. Inisialisasi Level Program
            if (!isset($rekap[$kdSkpd]['program'][$kdProg])) {
                $rekap[$kdSkpd]['program'][$kdProg] = [
                    'kode'      => $kdProg,
                    'nama'      => $row['NAMA_PROGRAM'],
                    'anggaran'  => 0,
                    'realisasi' => 0,
                    'sro'       => 0,
                    'sro_nol'   => 0,
                    'kegiatan'  => []
                ];
            }

            // 3. Inisialisasi Level Kegiatan
            if (!isset($rekap[$kdSkpd]['program'][$kdProg]['kegiatan'][$kdGiat])) {
                $rekap[$kdSkpd]['program'][$kdProg]['kegiatan'][$kdGiat] = [
                    'kode'         => $kdGiat,
                    'nama'         => $row['NAMA_GIAT'],
                    'anggaran'     => 0,
                    'realisasi'    => 0,
                    'sro'          => 0,
                    'sro_nol'      => 0,
                    'sub_kegiatan' => []
                ];
            }

            // 4. Inisialisasi Level Sub-Kegiatan
            if (!isset($rekap[$kdSkpd]['program'][$kdProg]['kegiatan'][$kdGiat]['sub_kegiatan'][$kdSubGiat])) {
                $rekap[$kdSkpd]['program'][$kdProg]['kegiatan'][$kdGiat]['sub_kegiatan'][$kdSubGiat] = [
                    'kode'      => $kdSubGiat,
                    'nama'      => $row['NAMA_SUB_GIAT'],
                    'anggaran'  => 0,
                    'realisasi' => 0,
                    'sro'       => 0,
                    'sro_nol'   => 0,
                    'list_sro'  => []
                ];
            }

            // 5. Level SRO (Hanya Anggaran dan Realisasi, Tanpa Capaian SRO)
            $rekap[$kdSkpd]['program'][$kdProg]['kegiatan'][$kdGiat]['sub_kegiatan'][$kdSubGiat]['list_sro'][$kdSro] = [
                'kode'      => $kdSro,
                'nama'      => $row['NAMA_SRO'],
                'anggaran'  => $anggaran,
                'realisasi' => $realisasi
            ];

            // 6. Akumulasi Anggaran, Realisasi, & Counter SRO ke Tingkat Atas
            $rekap[$kdSkpd]['anggaran']  += $anggaran;
            $rekap[$kdSkpd]['realisasi'] += $realisasi;
            $rekap[$kdSkpd]['sro']       += 1;
            $rekap[$kdSkpd]['sro_nol']   += $isSroNol;

            $rekap[$kdSkpd]['program'][$kdProg]['anggaran']  += $anggaran;
            $rekap[$kdSkpd]['program'][$kdProg]['realisasi'] += $realisasi;
            $rekap[$kdSkpd]['program'][$kdProg]['sro']       += 1;
            $rekap[$kdSkpd]['program'][$kdProg]['sro_nol']   += $isSroNol;

            $rekap[$kdSkpd]['program'][$kdProg]['kegiatan'][$kdGiat]['anggaran']  += $anggaran;
            $rekap[$kdSkpd]['program'][$kdProg]['kegiatan'][$kdGiat]['realisasi'] += $realisasi;
            $rekap[$kdSkpd]['program'][$kdProg]['kegiatan'][$kdGiat]['sro']       += 1;
            $rekap[$kdSkpd]['program'][$kdProg]['kegiatan'][$kdGiat]['sro_nol']   += $isSroNol;

            $rekap[$kdSkpd]['program'][$kdProg]['kegiatan'][$kdGiat]['sub_kegiatan'][$kdSubGiat]['anggaran']  += $anggaran;
            $rekap[$kdSkpd]['program'][$kdProg]['kegiatan'][$kdGiat]['sub_kegiatan'][$kdSubGiat]['realisasi'] += $realisasi;
            $rekap[$kdSkpd]['program'][$kdProg]['kegiatan'][$kdGiat]['sub_kegiatan'][$kdSubGiat]['sro']       += 1;
            $rekap[$kdSkpd]['program'][$kdProg]['kegiatan'][$kdGiat]['sub_kegiatan'][$kdSubGiat]['sro_nol']   += $isSroNol;
        }

        // 7. Hitung kalkulasi capaian untuk level Sub-Kegiatan, Kegiatan, Program, dan SKPD
        foreach ($rekap as $kdSkpd => $skpd) {
            $rekap[$kdSkpd]['capaian_realisasi'] = $this->hitungPersen($skpd['realisasi'], $skpd['anggaran']);
            $rekap[$kdSkpd]['capaian_sro']       = $this->hitungPersen($skpd['sro'] - $skpd['sro_nol'], $skpd['sro']);

            foreach ($skpd['program'] as $kdProg => $prog) {
                $rekap[$kdSkpd]['program'][$kdProg]['capaian_realisasi'] = $this->hitungPersen($prog['realisasi'], $prog['anggaran']);
                $rekap[$kdSkpd]['program'][$kdProg]['capaian_sro']       = $this->hitungPersen($prog['sro'] - $prog['sro_nol'], $prog['sro']);

                foreach ($prog['kegiatan'] as $kdGiat => $giat) {
                    $rekap[$kdSkpd]['program'][$kdProg]['kegiatan'][$kdGiat]['capaian_realisasi'] = $this->hitungPersen($giat['realisasi'], $giat['anggaran']);
                    $rekap[$kdSkpd]['program'][$kdProg]['kegiatan'][$kdGiat]['capaian_sro']       = $this->hitungPersen($giat['sro'] - $giat['sro_nol'], $giat['sro']);

                    foreach ($giat['sub_kegiatan'] as $kdSubGiat => $subGiat) {
                        $rekap[$kdSkpd]['program'][$kdProg]['kegiatan'][$kdGiat]['sub_kegiatan'][$kdSubGiat]['capaian_realisasi'] = $this->hitungPersen($subGiat['realisasi'], $subGiat['anggaran']);
                        $rekap[$kdSkpd]['program'][$kdProg]['kegiatan'][$kdGiat]['sub_kegiatan'][$kdSubGiat]['capaian_sro']       = $this->hitungPersen($subGiat['sro'] - $subGiat['sro_nol'], $subGiat['sro']);
                    }
                }
            }
        }

        return $rekap;
    }

    /**
     * Helper function internal untuk menghitung persentase
     */
    private function hitungPersen($pembilang, $penyebut)
    {
        return $penyebut > 0 ? round(($pembilang / $penyebut) * 100, 2) : 0;
    }
    public function getRekapLengkapPerTingkat2($bulan, $kdSU)
    {
        // Query agregat dasar per Sub-Kegiatan
        $data = $this->select('*,       
        SUM(`TOTAL_ANGGARAN`) AS total_anggaran,
        SUM(`TOTAL_REALISASI`) AS total_realisasi,
        COUNT(`KODE_SRO`) AS jumlah_sro,
        SUM(CASE WHEN `TOTAL_REALISASI` = 0 THEN 1 ELSE 0 END) AS sro_nol
        ')
            ->where('BULAN', $bulan)
            ->where('NAMA_UNIT_SKPD', $kdSU)
            ->groupBy([
                // 'KODE_UNIT_SKPD',
                // 'NAMA_UNIT_SKPD',
                'KODE_PROGRAM',
                'NAMA_PROGRAM',
                'KODE_GIAT',
                'NAMA_GIAT',
                'KODE_SUB_GIAT',
                'NAMA_SUB_GIAT'
            ])
            // ->orderBy('NAMA_UNIT_SKPD', 'ASC')
            ->orderBy('KODE_PROGRAM', 'ASC')
            ->orderBy('KODE_GIAT', 'ASC')
            ->orderBy('KODE_SUB_GIAT', 'ASC')
            ->get()
            ->getResultArray();

        $rekap = [];

        foreach ($data as $row) {
            $skpd = $row['NAMA_UNIT_SKPD'];
            $prog = $row['NAMA_PROGRAM'];
            $giat = $row['NAMA_GIAT'];
            $anggaran = (float)$row['total_anggaran'];
            $realisasi = (float)$row['total_realisasi'];
            $sroTotal = (int)$row['jumlah_sro'];
            $sroNol = (int)$row['sro_nol'];

            // Struktur SKPD
            if (!isset($rekap[$skpd])) {
                $rekap[$skpd] = [
                    'kode' => $row['NAMA_UNIT_SKPD'],
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
                    'kode' => $row['KODE_PROGRAM'],
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
                    'kode' => $row['KODE_GIAT'],
                    'anggaran' => 0,
                    'realisasi' => 0,
                    'sro' => 0,
                    'sro_nol' => 0,
                    'sub_kegiatan' => []
                ];
            }

            // Sub-Kegiatan
            $rekap[$skpd]['program'][$prog]['kegiatan'][$giat]['sub_kegiatan'][] = [
                'kode' => $row['KODE_SUB_GIAT'],
                'nama' => $row['NAMA_SUB_GIAT'],
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
    public function getRekapLengkapPerTingkat($bulan, $kdSU)
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
            ->where('NAMA_UNIT_SKPD', $kdSU)
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
            $kdSkpd   = $row['KODE_UNIT_SKPD'] ?? $row['NAMA_UNIT_SKPD']; // Gunakan kode sebagai key
            $kdProg   = $row['KODE_PROGRAM'];
            $kdGiat   = $row['KODE_GIAT'];
            $kdSubGiat = $row['KODE_SUB_GIAT'];

            $anggaran  = (float)$row['total_anggaran'];
            $realisasi = (float)$row['total_realisasi'];
            $sroTotal  = (int)$row['jumlah_sro'];
            $sroNol    = (int)$row['sro_nol'];

            // 1. Struktur SKPD
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

            // 2. Struktur Program
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

            // 3. Struktur Kegiatan
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
                'capaian_realisasi' => $anggaran > 0 ? round(($realisasi / $anggaran) * 100, 2) : 0,
                'sro' => $sroTotal,
                'sro_nol' => $sroNol,
                'capaian_sro' => $sroTotal > 0 ? round((($sroTotal - $sroNol) / $sroTotal) * 100, 2) : 0
            ];

            // 5. Akumulasi Anggaran & SRO Ke Tingkat Atas
            foreach (
                [
                    &$rekap[$kdSkpd]['program'][$kdProg]['kegiatan'][$kdGiat],
                    &$rekap[$kdSkpd]['program'][$kdProg],
                    &$rekap[$kdSkpd]
                ] as &$node
            ) {
                $node['anggaran']  += $anggaran;
                $node['realisasi'] += $realisasi;
                $node['sro']       += $sroTotal;
                $node['sro_nol']   += $sroNol;
            }
        }

        // Optional: Hitung capaian persentase di level Kegiatan, Program, dan SKPD
        foreach ($rekap as &$skpd) {
            $skpd['capaian_realisasi'] = $skpd['anggaran'] > 0 ? round(($skpd['realisasi'] / $skpd['anggaran']) * 100, 2) : 0;
            $skpd['capaian_sro']       = $skpd['sro'] > 0 ? round((($skpd['sro'] - $skpd['sro_nol']) / $skpd['sro']) * 100, 2) : 0;

            foreach ($skpd['program'] as &$prog) {
                $prog['capaian_realisasi'] = $prog['anggaran'] > 0 ? round(($prog['realisasi'] / $prog['anggaran']) * 100, 2) : 0;
                $prog['capaian_sro']       = $prog['sro'] > 0 ? round((($prog['sro'] - $prog['sro_nol']) / $prog['sro']) * 100, 2) : 0;

                foreach ($prog['kegiatan'] as &$giat) {
                    $giat['capaian_realisasi'] = $giat['anggaran'] > 0 ? round(($giat['realisasi'] / $giat['anggaran']) * 100, 2) : 0;
                    $giat['capaian_sro']       = $giat['sro'] > 0 ? round((($giat['sro'] - $giat['sro_nol']) / $giat['sro']) * 100, 2) : 0;
                }
            }
        }

        return $rekap;
    }

    public function getRekapLengkapPerTingkatBelanja($bulan, $kdSU)
    {
        // Query mengambil detail hingga tingkat KODE_SRO
        $data = $this->select('
            KODE_UNIT_SKPD,
            NAMA_UNIT_SKPD,
            KODE_PROGRAM,
            NAMA_PROGRAM,
            KODE_GIAT,
            NAMA_GIAT,
            KODE_SUB_GIAT,
            NAMA_SUB_GIAT,
            KODE_SRO,
            NAMA_SRO,
            TOTAL_ANGGARAN,
            TOTAL_REALISASI
        ')
            ->where('BULAN', $bulan)
            ->where('NAMA_UNIT_SKPD', $kdSU)
            ->orderBy('KODE_PROGRAM', 'ASC')
            ->orderBy('KODE_GIAT', 'ASC')
            ->orderBy('KODE_SUB_GIAT', 'ASC')
            ->orderBy('KODE_SRO', 'ASC')
            ->get()
            ->getResultArray();

        $rekap = [];

        foreach ($data as $row) {
            $kdSkpd   = $row['KODE_UNIT_SKPD'] ?? $row['NAMA_UNIT_SKPD'];
            $kdProg   = $row['KODE_PROGRAM'];
            $kdGiat   = $row['KODE_GIAT'];
            $kdSubGiat = $row['KODE_SUB_GIAT'];
            $kdSro    = $row['KODE_SRO'];

            $anggaran  = (float)$row['TOTAL_ANGGARAN'];
            $realisasi = (float)$row['TOTAL_REALISASI'];
            $isNol     = ($realisasi == 0) ? 1 : 0;

            // 1. Level SKPD
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

            // 2. Level Program
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

            // 3. Level Kegiatan
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

            // 4. Level Sub-Kegiatan
            if (!isset($rekap[$kdSkpd]['program'][$kdProg]['kegiatan'][$kdGiat]['sub_kegiatan'][$kdSubGiat])) {
                $rekap[$kdSkpd]['program'][$kdProg]['kegiatan'][$kdGiat]['sub_kegiatan'][$kdSubGiat] = [
                    'kode' => $kdSubGiat,
                    'nama' => $row['NAMA_SUB_GIAT'],
                    'anggaran' => 0,
                    'realisasi' => 0,
                    'sro' => 0,
                    'sro_nol' => 0,
                    'sro_list' => []
                ];
            }

            // 5. Level SRO (Detail Paling Bawah)
            $rekap[$kdSkpd]['program'][$kdProg]['kegiatan'][$kdGiat]['sub_kegiatan'][$kdSubGiat]['sro_list'][$kdSro] = [
                'kode' => $kdSro,
                'nama' => $row['NAMA_SRO'] ?? 'SRO ' . $kdSro,
                'anggaran' => $anggaran,
                'realisasi' => $realisasi,
                'capaian_realisasi' => $anggaran > 0 ? round(($realisasi / $anggaran) * 100, 2) : 0,
                'status' => $isNol ? 'Belum Terealisasi (0)' : 'Terealisasi'
            ];

            // 6. Akumulasi nilai Anggaran, Realisasi, Jumlah SRO, dan SRO Nol ke Level Atas
            foreach (
                [
                    &$rekap[$kdSkpd]['program'][$kdProg]['kegiatan'][$kdGiat]['sub_kegiatan'][$kdSubGiat],
                    &$rekap[$kdSkpd]['program'][$kdProg]['kegiatan'][$kdGiat],
                    &$rekap[$kdSkpd]['program'][$kdProg],
                    &$rekap[$kdSkpd]
                ] as &$node
            ) {
                $node['anggaran']  += $anggaran;
                $node['realisasi'] += $realisasi;
                $node['sro']       += 1;
                $node['sro_nol']   += $isNol;
            }
        }

        // Hitung persentase capaian agregat untuk tiap parent level
        foreach ($rekap as &$skpd) {
            $skpd['capaian_realisasi'] = $skpd['anggaran'] > 0 ? round(($skpd['realisasi'] / $skpd['anggaran']) * 100, 2) : 0;
            $skpd['capaian_sro']       = $skpd['sro'] > 0 ? round((($skpd['sro'] - $skpd['sro_nol']) / $skpd['sro']) * 100, 2) : 0;

            foreach ($skpd['program'] as &$prog) {
                $prog['capaian_realisasi'] = $prog['anggaran'] > 0 ? round(($prog['realisasi'] / $prog['anggaran']) * 100, 2) : 0;
                $prog['capaian_sro']       = $prog['sro'] > 0 ? round((($prog['sro'] - $prog['sro_nol']) / $prog['sro']) * 100, 2) : 0;

                foreach ($prog['kegiatan'] as &$giat) {
                    $giat['capaian_realisasi'] = $giat['anggaran'] > 0 ? round(($giat['realisasi'] / $giat['anggaran']) * 100, 2) : 0;
                    $giat['capaian_sro']       = $giat['sro'] > 0 ? round((($giat['sro'] - $giat['sro_nol']) / $giat['sro']) * 100, 2) : 0;

                    foreach ($giat['sub_kegiatan'] as &$sub) {
                        $sub['capaian_realisasi'] = $sub['anggaran'] > 0 ? round(($sub['realisasi'] / $sub['anggaran']) * 100, 2) : 0;
                        $sub['capaian_sro']       = $sub['sro'] > 0 ? round((($sub['sro'] - $sub['sro_nol']) / $sub['sro']) * 100, 2) : 0;
                    }
                }
            }
        }

        return $rekap;
    }
    public function getRekapSubSkpdLengkap($tgl, $bulan)
    {
        // 1. Buat Subquery untuk Menghitung Rata-Rata Deviasi Persen per Unit SKPD
        $subQueryDeviasi = $this->db->table('ta_angkasapbdopd') // Sesuaikan jika nama tabel angkas berbeda
            ->select('
        kode_unit_skpd,
        nama_unit_skpd,
        ROUND(
            AVG(
                CASE 
                    WHEN ANGKAS > 0 
                    THEN (ABS(REALISASI_ANGGARAN - ANGKAS) / ANGKAS) * 100 
                    ELSE 0 
                END
            ), 2
        ) AS rata_rata_deviasi_persen
    ')
            ->whereIn('LOWER(bulan)', $bulan)
            ->groupBy('kode_unit_skpd, nama_unit_skpd');

        // 2. Gabungkan dengan Query Utama (Capaian SRO, Anggaran, & Efisiensi)
        return $this->builder()
            ->select('
        main.nama_unit_skpd,
        deviasi.kode_unit_skpd,
        
        -- 1. Jumlah total item NAMA SRO
        COUNT(DISTINCT main.nama_sro) AS jumlah_item_sro,
        
        -- 2. Jumlah item NAMA SRO yang terealisasi (> 0)
        COUNT(DISTINCT CASE WHEN main.total_realisasi > 0 THEN main.nama_sro ELSE NULL END) AS jumlah_item_sro_terealisasi,
        
        -- 3. Jumlah item NAMA SRO yang total realisasinya 0
        COUNT(DISTINCT CASE WHEN main.total_realisasi = 0 THEN main.nama_sro ELSE NULL END) AS jumlah_item_sro_realisasi_nol,
        
        -- 4. Jumlah Total Anggaran
        SUM(main.total_anggaran) AS total_anggaran,
        
        -- 5. Total Realisasi
        SUM(main.total_realisasi) AS total_realisasi,
        
        -- 6. Persentase Capaian Realisasi Anggaran
        ROUND(
            CASE 
                WHEN SUM(main.total_anggaran) > 0 
                THEN (SUM(main.total_realisasi) / SUM(main.total_anggaran)) * 100 
                ELSE 0 
            END, 2
        ) AS persen_capaian_anggaran,
        
        -- 7. Persentase Capaian Jumlah Item SRO Terealisasi
        ROUND(
            CASE 
                WHEN COUNT(DISTINCT main.nama_sro) > 0 
                THEN (COUNT(DISTINCT CASE WHEN main.total_realisasi > 0 THEN main.nama_sro ELSE NULL END) / COUNT(DISTINCT main.nama_sro)) * 100 
                ELSE 0 
            END, 2
        ) AS persen_capaian_sro,

        -- 8. Capaian Efisiensi (% Capaian SRO - % Capaian Anggaran)
        ROUND(
            (
                CASE 
                    WHEN COUNT(DISTINCT main.nama_sro) > 0 
                    THEN (COUNT(DISTINCT CASE WHEN main.total_realisasi > 0 THEN main.nama_sro ELSE NULL END) / COUNT(DISTINCT main.nama_sro)) * 100 
                    ELSE 0 
                END
            ) - (
                CASE 
                    WHEN SUM(main.total_anggaran) > 0 
                    THEN (SUM(main.total_realisasi) / SUM(main.total_anggaran)) * 100 
                    ELSE 0 
                END
            ), 2
        ) AS capaian_efisiensi,

        -- 9. Rata-Rata Deviasi Persen (Hasil Join)
        COALESCE(deviasi.rata_rata_deviasi_persen, 0) AS rata_rata_deviasi_persen
    ', false) // set false agar CodeIgniter tidak otomatis meng-escape query kompleks
            ->from($this->table . ' AS main')
            ->join(
                '(' . $subQueryDeviasi->getCompiledSelect() . ') AS deviasi',
                'deviasi.nama_unit_skpd = main.nama_unit_skpd',
                'left'
            )
            ->where('main.CREATE_AT', $tgl)
            ->groupBy('main.nama_unit_skpd, deviasi.kode_unit_skpd, deviasi.rata_rata_deviasi_persen')
            ->get()
            ->getResultArray();
    }


    public function getRekapSubSkpd($tgl)
    {
        return $this->builder()
            ->select('*,
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
        ) AS persen_capaian_sro,

        -- 8. Capaian Efisiensi (% Capaian SRO - % Capaian Anggaran)
        ROUND(
            (
                CASE 
                    WHEN COUNT(DISTINCT nama_sro) > 0 
                    THEN (COUNT(DISTINCT CASE WHEN total_realisasi > 0 THEN nama_sro ELSE NULL END) / COUNT(DISTINCT nama_sro)) * 100 
                    ELSE 0 
                END
            ) - (
                CASE 
                    WHEN SUM(total_anggaran) > 0 
                    THEN (SUM(total_realisasi) / SUM(total_anggaran)) * 100 
                    ELSE 0 
                END
            ), 2
        ) AS capaian_efisiensi
    ')
            ->where('CREATE_AT', $tgl)
            ->groupBy('nama_unit_skpd')
            ->get()
            ->getResultArray();
    }

    public function tgldata()
    {
        $q = $this
            ->select('CREATE_AT,BULAN')
            ->where('tahun', session()->get('tahun'))
            ->groupBy('CREATE_AT')
            ->get()
            ->getResultArray();
        return $q;
    }
    public function getCapaianKinerjaperopd($tglaktif, $kdsu)
    {
        $builder = $this->db->table($this->table);

        // Hitung total anggaran keseluruhan
        $totalAnggaranKeseluruhan = $builder->selectSum('TOTAL_ANGGARAN')
            ->selectcount('KODE_UNIT_SKPD', 'JumlahBelanja')
            ->where('KODE_UNIT_SKPD', $kdsu)
            ->get()->getRow()->TOTAL_ANGGARAN;

        // Query utama
        $builder->select('KODE_UNIT_SKPD, NAMA_UNIT_SKPD,KODE_PROGRAM');
        $builder->selectSum('TOTAL_ANGGARAN', 'TotalAnggaran');
        $builder->selectSum('TOTAL_REALISASI', 'TotalRealisasi');
        $builder->where('KODE_UNIT_SKPD', $kdsu);
        $builder->where('tahun', session()->get('tahun'));
        $builder->where('CREATE_AT', $tglaktif);
        $builder->groupBy('NAMA_PROGRAM');

        $result = $builder->get()->getResultArray();
        $persentaseList = [];

        foreach ($result as &$row) {
            if ($row['TotalRealisasi'] > 0) { // hanya dihitung jika realisasi > 0
                $row['PersentaseRealisasi'] = ($row['TotalRealisasi'] / $row['TotalAnggaran']) * 100;
                $row['BobotAnggaran'] = ($row['TotalAnggaran'] / $totalAnggaranKeseluruhan) * 100;
                $row['SkorCapaian'] = $row['PersentaseRealisasi'] * $row['BobotAnggaran'];

                $persentaseList[] = $row['PersentaseRealisasi'];
            } else {
                $row['PersentaseRealisasi'] = 0;
                $row['BobotAnggaran'] = 0;
                $row['SkorCapaian'] = 0;
            }
        }

        // Hitung rata-rata geometri hanya dari persentase > 0
        $n = count($persentaseList);
        $product = $n > 0 ? array_product($persentaseList) : 0;
        $geometricMean = $n > 0 ? pow($product, 1 / $n) : 0;
        //hitung total realisasi keseluruhan
        $totalRealisasiKeseluruhan = $builder->selectSum('TOTAL_REALISASI')->get()->getRow()->TOTAL_REALISASI;
        $persentaseRealisasiKeseluruhan = $totalAnggaranKeseluruhan > 0 ? ($totalRealisasiKeseluruhan / $totalAnggaranKeseluruhan) * 100 : 0;

        return [
            'data' => $result,
            'geometric_mean' => $geometricMean,
            'total_realisasi_keseluruhan' => $totalRealisasiKeseluruhan,
            'total_anggaran_keseluruhan' => $totalAnggaranKeseluruhan,
            'persentase_realisasi_keseluruhan' => $persentaseRealisasiKeseluruhan
        ];
    }
    public function getCapaianKinerjaDenganGeometri($tahun, $tglaktif)
    {
        $builder = $this->db->table($this->table);

        // Hitung total anggaran keseluruhan
        $totalAnggaranKeseluruhan = $builder->selectSum('TOTAL_ANGGARAN')->get()->getRow()->TOTAL_ANGGARAN;

        // Query utama
        $builder->select('*');
        $builder->selectSum('TOTAL_ANGGARAN', 'TotalAnggaran');
        $builder->selectSum('TOTAL_REALISASI', 'TotalRealisasi');
        $builder->selectCount('TOTAL_ANGGARAN', 'JumlahBelanja');
        $builder->where('tahun', $tahun);
        $builder->where('CREATE_AT', $tglaktif);
        $builder->groupBy('NAMA_UNIT_SKPD');
        $builder->orderBy('TOTAL_REALISASI', 'ASC');
        $result = $builder->get()->getResultArray();

        $persentaseList = [];

        foreach ($result as &$row) {
            if ($row['TotalRealisasi'] > 0) { // hanya dihitung jika realisasi > 0
                $row['PersentaseRealisasi'] = ($row['TotalRealisasi'] / $row['TotalAnggaran']) * 100;
                $row['BobotAnggaran'] = ($row['TotalAnggaran'] / $totalAnggaranKeseluruhan) * 100;
                $row['SkorCapaian'] = $row['PersentaseRealisasi'] * $row['BobotAnggaran'];

                $persentaseList[] = $row['PersentaseRealisasi'];
            } else {
                $row['PersentaseRealisasi'] = 0;
                $row['BobotAnggaran'] = 0;
                $row['SkorCapaian'] = 0;
            }
        }

        // Hitung rata-rata geometri hanya dari persentase > 0
        $n = count($persentaseList);
        $product = $n > 0 ? array_product($persentaseList) : 0;
        $geometricMean = $n > 0 ? pow($product, 1 / $n) : 0;
        //hitung total realisasi keseluruhan
        $totalRealisasiKeseluruhan = $builder->selectSum('TOTAL_REALISASI')->get()->getRow()->TOTAL_REALISASI;
        $persentaseRealisasiKeseluruhan = $totalAnggaranKeseluruhan > 0 ? ($totalRealisasiKeseluruhan / $totalAnggaranKeseluruhan) * 100 : 0;

        return [
            'data' => $result,
            'geometric_mean' => $geometricMean,
            'total_realisasi_keseluruhan' => $totalRealisasiKeseluruhan,
            'total_anggaran_keseluruhan' => $totalAnggaranKeseluruhan,
            'persentase_realisasi_keseluruhan' => $persentaseRealisasiKeseluruhan
        ];
    }
    public function listopduserreg($tglaktif)
    {
        $q = $this
            ->select('NAMA_UNIT_SKPD')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            ->selectSum('REALISASI_SPJ', 'realisasi_spj')
            ->where('tahun', date('Y'))
            ->where('CREATE_AT', $tglaktif)
            ->groupBy('NAMA_UNIT_SKPD')
            ->get()
            ->getResultArray();
        return $q;
    }
    public function listopdadmin($tglaktif, $nm_opd = null)
    {
        if ($nm_opd == null) {
            $q = $this
                ->select('*')
                ->selectSum('TOTAL_REALISASI', 'realisasi')
                ->selectSum('TOTAL_ANGGARAN', 'anggaran')
                ->selectSum('REALISASI_SPJ', 'realisasi_spj')
                ->where('tahun', session()->get('tahun'))
                ->where('CREATE_AT', $tglaktif)
                // ->where('TOTAL_ANGGARAN <>', 0)
                // ->where('TOTAL_REALISASI <>', 0)
                // // ->select('kode_skpd, nama_skpd, total_anggaran, realisasi')
                // // ->select('(realisasi / total_anggaran * 100) as persentase_realisasi')
                // ->select('(total_anggaran / (SELECT SUM(total_anggaran) FROM apbd) * 100) as bobot_anggaran')
                // ->select('((realisasi / total_anggaran * 100) * (total_anggaran / (SELECT SUM(total_anggaran) FROM apbd) * 100)) as skor_capaian')
                ->groupBy('NAMA_UNIT_SKPD')
                ->get()
                ->getResultArray();
            return $q;
        } else {
            $q = $this
                ->select('*')
                ->selectSum('TOTAL_REALISASI', 'realisasi')
                ->selectSum('TOTAL_ANGGARAN', 'anggaran')
                ->selectSum('REALISASI_SPJ', 'realisasi_spj')
                ->where('tahun', session()->get('tahun'))
                ->where('CREATE_AT', $tglaktif)
                ->where('NAMA_UNIT_SKPD', $nm_opd)
                ->groupBy('NAMA_UNIT_SKPD')
                ->get()
                ->getResultArray();
            return $q;
        }
    }
    public function listopd($nm_opd = null)
    {
        if ($nm_opd == null) {
            $q = $this
                ->select('*')
                ->selectSum('TOTAL_REALISASI', 'realisasi')
                ->selectSum('TOTAL_ANGGARAN', 'anggaran')
                ->selectSum('REALISASI_SPJ', 'realisasi_spj')
                ->where('tahun', session()->get('tahun'))
                ->where('CREATE_AT', session()->get('tglaktif'))
                ->groupBy('NAMA_UNIT_SKPD')
                ->get()
                ->getResultArray();
            return $q;
        } else {
            $q = $this
                ->select('*')
                ->selectSum('TOTAL_REALISASI', 'realisasi')
                ->selectSum('TOTAL_ANGGARAN', 'anggaran')
                ->selectSum('REALISASI_SPJ', 'realisasi_spj')
                ->where('tahun', session()->get('tahun'))
                ->where('CREATE_AT', session()->get('tglaktif'))
                ->where('NAMA_UNIT_SKPD', $nm_opd)
                ->groupBy('NAMA_UNIT_SKPD')
                ->get()
                ->getResultArray();
            return $q;
        }
    }
    public function dataopd($nm_opd)
    {
        $q = $this
            ->select('*')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            ->where('tahun', session()->get('tahun'))
            ->where('CREATE_AT', session()->get('tglaktif'))
            ->where('NAMA_UNIT_SKPD', $nm_opd)
            ->groupBy('NAMA_UNIT_SKPD')
            ->get()
            ->getRowArray();
        return $q;
    }
    public function dataopd2025($nm_opd)
    {
        $q = $this
            ->select('*')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            ->where('tahun', session()->get('tahun'))
            ->where('CREATE_AT', '2026-07-29')
            ->where('NAMA_UNIT_SKPD', $nm_opd)
            ->groupBy('NAMA_UNIT_SKPD')
            ->get()
            ->getRowArray();
        return $q;
    }
    public function rperopdperbulan($kdSU, $tahun)
    {
        $q = $this
            ->select('*')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            ->selectSum('REALISASI_SPJ', 'realisasi_spj')
            ->select('format((sum(TOTAL_REALISASI) / sum(TOTAL_ANGGARAN)) * 100, 2) as persen')
            ->where('TAHUN', $tahun)
            ->where('KODE_UNIT_SKPD', $kdSU)
            ->groupBy('BULAN')
            ->get()
            ->getResultArray();
        return $q;
    }
    public function rpersk($nm_opd, $tahun, $bulan, $tglaktif)
    {
        $q = $this
            ->select('*')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            ->selectSum('REALISASI_SPJ', 'realisasi_spj')
            ->where('TAHUN', $tahun)
            ->where('BULAN', $bulan)
            ->where('CREATE_AT', $tglaktif)
            ->where('NAMA_UNIT_SKPD', $nm_opd)
            ->groupBy('NAMA_SUB_GIAT')
            ->orderBy('KODE_SUB_GIAT', 'asc')
            ->get()
            ->getResultArray();
        return $q;
    }
    public function rperskperopd($kdSU, $kdSK, $tahun, $bulan, $tglaktif)
    {
        $q = $this
            ->select('*')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            ->selectSum('REALISASI_SPJ', 'realisasi_spj')
            ->where('TAHUN', $tahun)
            ->where('BULAN', $bulan)
            ->where('CREATE_AT', $tglaktif)
            ->where('KODE_UNIT_SKPD', $kdSU)
            ->where('KODE_SUB_GIAT', $kdSK)
            ->groupBy('NAMA_SUB_GIAT')
            ->orderBy('KODE_SUB_GIAT', 'asc')
            ->get()
            ->getRowArray();
        return $q;
    }
    public function rperskperopdperbulan($kdSU, $kdSK, $tahun)
    {
        $q = $this
            ->select('*')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            ->selectSum('REALISASI_SPJ', 'realisasi_spj')
            ->where('TAHUN', $tahun)
            // ->where('BULAN', $bulan)
            ->where('KODE_UNIT_SKPD', $kdSU)
            ->where('KODE_SUB_GIAT', $kdSK)
            ->groupBy('BULAN')
            // ->orderBy('KODE_SUB_GIAT', 'asc')
            ->get()
            ->getResultArray();
        return $q;
    }
    public function rperrso($tahun, $tgldata, $kdSU, $kdSK)
    {
        $q = $this
            ->select('*')
            ->where('TAHUN', $tahun)
            ->where('CREATE_AT', $tgldata)
            ->where('KODE_UNIT_SKPD', $kdSU)
            ->where('KODE_SUB_GIAT', $kdSK)
            ->orderBy('KODE_SRO', 'asc')
            ->get();
        // ->getResultArray();
        return $q;
    }
    public function rperrso2($kdSU, $kdSK, $tahun, $tgldata)
    {
        $q = $this
            ->select('*')
            ->where('TAHUN', $tahun)
            ->where('CREATE_AT', $tgldata)
            ->where('NAMA_UNIT_SKPD', $kdSU)
            ->where('KODE_SUB_GIAT', $kdSK)
            ->orderBy('KODE_SRO', 'asc')
            ->get();
        // ->getResultArray();
        return $q;
    }
    public function rperkelbel($nm_opd, $tahun, $bulan, $tglaktif, $kelbel)
    {
        $q = $this
            ->select('*')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            ->where('TAHUN', $tahun)
            ->where('BULAN', $bulan)
            ->where('CREATE_AT', $tglaktif)
            ->where('NAMA_UNIT_SKPD', $nm_opd)
            ->like('KODE_SRO', $kelbel)
            // ->groupBy('NAMA_SUB_GIAT')
            ->get()
            ->getRowArray();
        return $q;
    }
    public function rperrob($nm_opd, $tahun, $bulan, $tglaktif, $rso)
    {
        $q = $this
            ->select('*')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            // ->where('TOTAL_ANGGARAN <>', 0)
            // ->where('TOTAL_REALISASI <>', 0)
            ->where('TAHUN', $tahun)
            ->where('BULAN', $bulan)
            ->where('CREATE_AT', $tglaktif)
            ->where('NAMA_UNIT_SKPD', $nm_opd)
            ->like('NAMA_SRO', $rso)
            // ->groupBy('NAMA_UNIT_SKPD')
            ->get()
            ->getRowArray();
        return $q;
    }
    public function listprogram($nm_opd, $tahun, $bulan, $tglaktif)
    {
        $q = $this
            ->select('*')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            ->selectSum('REALISASI_SPJ', 'realisasi_spj')
            // ->where('TOTAL_ANGGARAN <>', 0)
            // ->where('TOTAL_REALISASI <>', 0)
            ->where('TAHUN', $tahun)
            ->where('BULAN', $bulan)
            ->where('CREATE_AT', $tglaktif)
            ->where('NAMA_UNIT_SKPD', $nm_opd)
            ->groupBy('NAMA_PROGRAM')
            ->orderBy('KODE_PROGRAM', 'asc')
            ->get()
            ->getResultArray();
        return $q;
    }
    public function liskegiatan($nm_opd, $tahun, $tglaktif, $kdprogram)
    {
        $q = $this
            ->select('*')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            ->selectSum('REALISASI_SPJ', 'realisasi_spj')

            // ->where('TOTAL_ANGGARAN <>', 0)
            // ->where('TOTAL_REALISASI <>', 0)
            ->where('TAHUN', $tahun)
            // ->where('BULAN', $bulan)
            ->where('CREATE_AT', $tglaktif)
            ->where('NAMA_UNIT_SKPD', $nm_opd)
            ->where('KODE_PROGRAM', $kdprogram)
            ->groupBy('KODE_GIAT')
            ->orderBy('KODE_GIAT', 'asc')
            ->get()
            ->getResultArray();
        return $q;
    }
    public function lissubkegiatan($nm_opd, $tahun,  $tglaktif, $kdprogram, $kdgiat)
    {
        $q = $this
            ->select('*')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            ->selectSum('REALISASI_SPJ', 'realisasi_spj')
            // ->where('TOTAL_ANGGARAN <>', 0)
            // ->where('TOTAL_REALISASI <>', 0)
            ->where('TAHUN', $tahun)
            // ->where('BULAN', $bulan)
            ->where('CREATE_AT', $tglaktif)
            ->where('NAMA_UNIT_SKPD', $nm_opd)
            ->where('KODE_PROGRAM', $kdprogram)
            ->where('KODE_GIAT', $kdgiat)
            ->groupBy('NAMA_SUB_GIAT')
            ->orderBy('KODE_SUB_GIAT', 'asc')
            ->get()
            ->getResultArray();
        return $q;
    }
    public function lissubkegiatan2($nm_opd, $tahun,  $tglaktif)
    {
        $q = $this
            ->select('*')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            ->selectSum('REALISASI_SPJ', 'realisasi_spj')
            // ->where('TOTAL_ANGGARAN <>', 0)
            // ->where('TOTAL_REALISASI <>', 0)
            ->where('TAHUN', $tahun)
            // ->where('BULAN', $bulan)
            ->where('CREATE_AT', $tglaktif)
            ->where('NAMA_UNIT_SKPD', $nm_opd)
            // ->where('KODE_PROGRAM', $kdprogram)
            // ->where('KODE_GIAT', $kdgiat)
            ->groupBy('NAMA_SUB_GIAT')
            ->orderBy('KODE_SUB_GIAT', 'asc')
            ->get()
            ->getResultArray();
        return $q;
    }
    public function rperrsoopd($nm_opd, $tahun, $bulan, $tglaktif, $kdprogram, $kdgiat, $kdsubgiat)
    {
        $q = $this
            ->select('KODE_SRO, NAMA_SRO')
            ->selectSum('TOTAL_REALISASI', 'realisasi')
            ->selectSum('TOTAL_ANGGARAN', 'anggaran')
            // ->where('TOTAL_ANGGARAN <>', 0)
            // ->where('TOTAL_REALISASI <>', 0)
            ->where('TAHUN', $tahun)
            ->where('BULAN', $bulan)
            ->where('CREATE_AT', $tglaktif)
            ->where('NAMA_UNIT_SKPD', $nm_opd)
            ->where('KODE_PROGRAM', $kdprogram)
            ->where('KODE_GIAT', $kdgiat)
            ->where('KODE_SUB_GIAT', $kdsubgiat)
            ->groupBy('NAMA_SRO')
            ->orderBy('KODE_SRO', 'asc')
            ->get()
            ->getResultArray();
        return $q;
    }
}
