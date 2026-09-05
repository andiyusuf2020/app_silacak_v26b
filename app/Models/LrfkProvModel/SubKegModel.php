<?php

namespace App\Models\LrfkProvModel;

use CodeIgniter\Model;
use GuzzleHttp\RetryMiddleware;

class SubKegModel extends Model
{

    protected $DBGroup              = 'default';
    //    protected $table                = 'ta_apbd_persubkegiatan';
    // protected $table                = 'ta_apbd_perbelanja';
    // protected $table                = 'ta_apbd_perbelanjap2';
    protected $table                = 'ta_apbd';

    protected $primaryKey           = 'id';
    protected $useAutoIncrement = true;

    protected $returnType     = 'array'; // Tipe data yang dikembalikan

    public function dataperbelanjapersk($kdSU, $kdSK, $kdbel)
    {
        return $this
            ->selectSUM('pagu_rincian')
            ->where('pagu_rincian<>', '0')
            ->where('kd_sub_unit', $kdSU)
            ->where('kd_subkegiatan', $kdSK)
            ->where('kd_rek_belanja', $kdbel)
            ->groupBy('kd_rek_belanja')
            ->get()
            ->getRowArray();
    }

    public function dataperbelanja($kdSU, $kode)
    {
        $tahun = session()->get('tahun');
        return $this
            // ->select('nm_sub_unit,nm_subkegiatan,kd_rek_belanja,nm_rekening,pagu_rincian')

            ->selectSum('pagu_rincian')
            ->where('pagu_rincian<>', '0')
            ->where('kd_sub_unit', $kdSU)
            ->where('tahun', $tahun)
            ->like('kd_rek_belanja', $kode)
            ->get()
            ->getRowArray();
    }
    public function datapagupersk($kdSU)
    {
        return $this
            ->select('kd_sub_unit,kd_subkegiatan,nm_subkegiatan')
            ->selectSum('pagu_rincian')
            ->where('pagu_rincian<>', '0')
            ->where('kd_sub_unit', $kdSU)
            ->groupBy('kd_subkegiatan')
            ->get()
            ->getResultArray();
    }
    public function TotbelOpd($kdSU, $kdSK = null)
    {
        if ($kdSK == null) {
            $q = $this
                ->selectCount('kd_rek_belanja')
                ->where('pagu_rincian<>', '0')
                ->where('kd_sub_unit', $kdSU)
                ->get()
                ->getRowArray();
        } else {
            $q = $this
                ->selectCount('kd_rek_belanja')
                ->where('pagu_rincian<>', '0')
                ->where('kd_sub_unit', $kdSU)
                ->where('kd_subkegiatan', $kdSK)
                ->get()
                ->getRowArray();
        }
        return $q;
    }
    public function RBelOpd($kdSU, $kdSK)
    {
        return $this
            ->select('*')
            ->where('pagu_rincian<>', '0')
            ->where('kd_sub_unit', $kdSU)
            ->where('kd_subkegiatan', $kdSK)
            ->get()
            ->getResultArray();
    }
    public function jumlahprogram($tahun, $kd_subunit = null)
    {
        if ($kd_subunit == null) {
            $data = $this
                ->select('nm_program')
                ->where('tahun', $tahun)
                ->where('pagu_rincian<>', '0')
                ->groupBy('nm_program')
                ->get()
                ->getResultArray();
        } else {
            $data = $this
                ->select('nm_program')
                ->where('tahun', $tahun)
                ->where('kd_sub_unit', $kd_subunit)
                ->where('pagu_rincian<>', '0')
                ->groupBy('nm_program')
                ->get()
                ->getResultArray();
        }
        return  $data;
    }
    public function jumlahkegiatan($tahun, $kd_subunit = null)
    {
        if ($kd_subunit == null) {
            $data = $this
                ->select('nm_kegiatan')
                ->where('tahun', $tahun)
                ->where('pagu_rincian<>', '0')
                ->groupBy('nm_kegiatan')
                ->get()
                ->getResultArray();
        } else {
            $data = $this
                ->select('nm_kegiatan')
                ->where('tahun', $tahun)
                ->where('kd_sub_unit', $kd_subunit)
                ->where('pagu_rincian<>', '0')
                ->groupBy('nm_kegiatan')
                ->get()
                ->getResultArray();
        }
        return  $data;
    }
    public function jumlahsubkegiatan($tahun, $kd_subunit = null)
    {
        if ($kd_subunit == null) {
            $data = $this
                ->select('nm_subkegiatan')
                ->where('tahun', $tahun)
                ->where('pagu_rincian<>', '0')
                ->groupBy('nm_subkegiatan')
                ->get()
                ->getResultArray();
        } else {
            $data = $this
                ->select('nm_subkegiatan')
                ->where('tahun', $tahun)
                ->where('kd_sub_unit', $kd_subunit)
                ->where('pagu_rincian<>', '0')
                ->groupBy('nm_subkegiatan')
                ->get()
                ->getResultArray();
        }
        return  $data;
    }
    public function belatk($kdSU)
    {
        $tahun = session()->get('tahun');
        return $this
            ->selectSum('pagu_rincian')
            // ->select('nm_sub_unit,nm_subkegiatan,kd_rek_belanja,nm_rekening,pagu_rincian')
            ->where('pagu_rincian<>', '0')
            ->where('nm_sub_unit', $kdSU)
            ->where('tahun', $tahun)
            ->like('nm_rekening', 'Alat Tulis Kantor')
            ->get()
            ->getRowArray();
    }

    public function belcetak($kdSU)
    {
        $tahun = session()->get('tahun');

        return $this
            ->selectSum('pagu_rincian')
            ->where('pagu_rincian<>', '0')
            ->where('nm_sub_unit', $kdSU)
            ->where('tahun', $tahun)
            ->like('nm_rekening', 'Bahan Cetak')
            ->get()
            ->getRowArray();
    }
    public function belsppd($kdSU)
    {
        $tahun = session()->get('tahun');

        return $this
            ->selectSum('pagu_rincian')
            ->where('pagu_rincian<>', '0')
            ->where('nm_sub_unit', $kdSU)
            ->where('tahun', $tahun)
            ->like('nm_rekening', 'Perjalanan Dinas')
            ->get()
            ->getRowArray();
    }
    public function totpaguopd2($kdSU)
    {
        $tahun = session()->get('tahun');

        return $this
            ->selectSum('pagu_rincian')
            ->where('pagu_rincian<>', '0')
            ->where('nm_sub_unit', $kdSU)
            ->where('tahun', $tahun)
            ->get()
            ->getRowArray();
    }
    public function totpaguopd($kdSU, $tahun)
    {
        return $this
            ->selectSum('pagu_rincian')
            ->selectCount('kd_rek_belanja')
            ->where('pagu_rincian<>', '0')
            ->where('kd_sub_unit', $kdSU)
            ->where('tahun', $tahun)
            ->get()
            ->getRowArray();
    }

    public function urusanopd($kdSU, $kdU)
    {
        return $this
            ->select('*')
            ->selectSum('pagu_rincian')
            ->where('pagu_rincian<>', '0')
            ->where('kd_urusan', $kdU)
            ->where('kd_sub_unit', $kdSU)
            ->groupBy('kd_urusan')
            ->get()
            ->getRowArray();
    }

    public function programopd($kdSU)
    {
        return $this
            ->where('pagu_rincian<>', '0')
            ->where('kd_sub_unit', $kdSU)
            ->groupBy('kd_program')
            ->get()
            ->getResultArray();
    }
    public function subkegiatanperopd($kdSU, $limit = null, $offset = null)
    {
        if ($offset == null and $limit == null) {
            $q = $this
                ->where('pagu_rincian<>', '0')
                ->where('kd_sub_unit', $kdSU)
                ->groupBy('kd_subkegiatan')
                ->get()
                ->getResultArray();
        } else {
            $q = $this
                ->select('*')
                ->selectsum('pagu_rincian')
                ->where('pagu_rincian<>', '0')
                ->where('kd_sub_unit', $kdSU)
                ->groupBy('kd_subkegiatan')
                ->orderBy('kd_subkegiatan', 'ASC')
                ->limit($limit, $offset)
                ->get()
                ->getResultArray();
        }
        return $q;
    }
    public function SKKegPokokOPD2($kdSU)
    {
        return $this
            ->select('*')
            ->where('pagu_rincian<>', '0')
            ->where('kd_sub_unit', $kdSU)
            // ->where('nm_program<>', 'PROGRAM PENUNJANG URUSAN PEMERINTAHAN DAERAH PROVINSI')
            ->where('nm_subkegiatan<>', 'Penyediaan Gaji dan Tunjangan ASN')
            ->groupBy('kd_subkegiatan')
            ->get()
            ->getResultArray();
    }
    public function SKKegPokokOPD($kdSU)
    {
        $data =  $this->select('ta_apbd.nm_subkegiatan as SK2,ta_subkeg_capkin_apbd.nm_subkegiatan')
            ->join('ta_subkeg_capkin_apbd', 'ta_apbd.kd_sub_unit = ta_subkeg_capkin_apbd.kd_subunit')
            ->where('ta_apbd.kd_sub_unit', $kdSU)
            ->where('ta_apbd.nm_subkegiatan<>', 'Penyediaan Gaji dan Tunjangan ASN')
            // ->where('ta_subkeg_capkin_apbd.kd_subkegiatan', 'is null')
            ->groupBy('ta_apbd.kd_subkegiatan')
            ->get()
            ->getResultArray();

        // $data = $this->select('a ta_apbd.*')
        //     ->where('a.pagu_rincian<>', '0')
        //     ->where('a.kd_sub_unit', $kdSU)
        //     ->where('a.nm_program<>', 'PROGRAM PENUNJANG URUSAN PEMERINTAHAN DAERAH PROVINSI')
        //     ->groupBy('kd_subkegiatan')
        //     ->get()
        //     ->getResultArray();
        //     //$this->findAll();
        // $options = [];

        // foreach ($products as $key => $product) {
        //     $options[$product['id'] = $product['id'] . ' (' . $product['nm_subkegiatan'] . ')'];
        // }

        return $data;
    }
    public function perprogramopd($kdSU, $kdP)
    {
        return $this
            ->where('pagu_rincian<>', '0')
            ->where('kd_sub_unit', $kdSU)
            ->where('kd_program', $kdP)
            ->get()
            ->getRowArray();
    }
    public function subkeg($kdSK, $kdSU, $tahun)
    {
        return $this
            ->select('*')->selectSum('pagu_rincian')
            ->where('pagu_rincian<>', '0')
            ->where('kd_subkegiatan', $kdSK)
            ->where('kd_sub_unit', $kdSU)
            ->where('tahun', $tahun)
            ->groupBy('kd_subkegiatan')
            //  ->orderBy('kd_sub_unit', 'ASC')
            ->get()
            ->getRowArray();
    }
    public function pagusubkeg($kdSK, $kdSU, $tahun)
    {
        return $this
            ->selectSum('pagu_rincian')
            ->where('pagu_rincian<>', '0')
            ->where('kd_subkegiatan', $kdSK)
            ->where('kd_sub_unit', $kdSU)
            ->where('tahun', $tahun)
            ->groupBy('kd_subkegiatan')
            ->get()
            ->getRowArray();
    }
    public function subkegbelanja2($kdSK, $kdSU)
    {
        return $this
            ->where('pagu_rincian<>', '0')
            ->where('kd_subkegiatan', $kdSK)
            ->where('kd_sub_unit', $kdSU)

            //  ->groupBy('nm_program')
            //  ->orderBy('kd_sub_unit', 'ASC')
            ->get()
            ->getResultArray();
    }
    public function subkegbelanja($kdSK, $kdSU, $tahun)
    {
        return $this
            ->select('
        id,tahun,kd_urusan, nm_urusan, kd_skpd,nm_skpd, kd_sub_unit, nm_sub_unit, kd_bidang_urusan,
        nm_bidang_urusan, kd_program, nm_program, kd_kegiatan,nm_kegiatan, kd_subkegiatan, nm_subkegiatan,
        kd_sumber_dana,nm_sumber_dana,kd_rek_belanja,nm_rekening,label_subkegiatan ')
            ->selectSum('pagu_rincian')
            ->where('pagu_rincian<>', '0')
            ->where('kd_subkegiatan', $kdSK)
            ->where('kd_sub_unit', $kdSU)
            ->where('tahun', $tahun)
            ->groupBy('kd_rek_belanja')
            //  ->orderBy('kd_sub_unit', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function listopd($nm_sub_unit, $tahun)
    {
        if ($nm_sub_unit == null) {
            $q =  $this
                ->selectSum('pagu_rincian')
                ->select('kd_skpd,kd_sub_unit,nm_sub_unit,tahun')
                ->where('pagu_rincian<>', '0')
                ->where('tahun', $tahun)
                ->groupBy('nm_sub_unit')
                ->orderBy('kd_sub_unit', 'ASC')
                ->get()
                ->getResultArray();
        } else {
            $q = $this
                ->select('kd_skpd,kd_sub_unit,nm_sub_unit')
                ->where('pagu_rincian<>', '0')
                ->where('nm_sub_unit', $nm_sub_unit)
                ->where('tahun', $tahun)
                ->groupBy('nm_sub_unit')
                ->get()
                ->getRowArray();
        }
        return $q;
    }

    public function listopdAll($limit, $offset)
    {
        $q =  $this
            ->selectSum('pagu_rincian')
            ->select('kd_skpd,kd_sub_unit,nm_sub_unit,tahun')
            ->where('pagu_rincian<>', '0')
            ->groupBy('nm_sub_unit')
            ->orderBy('kd_sub_unit', 'ASC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();
        return $q;
    }
    public function countAllOpd()
    {
        $q =  $this
            ->selectSum('pagu_rincian')
            ->select('kd_skpd,kd_sub_unit,nm_sub_unit,tahun')
            ->where('pagu_rincian<>', '0')
            ->groupBy('nm_sub_unit')
            ->orderBy('kd_sub_unit', 'ASC')
            ->get()
            ->getResultArray();
        $jml = count($q);

        return $jml;
    }
    public function datasubunit($kdSU)
    {
        return $this
            ->select('*')
            ->where('pagu_rincian<>', '0')
            ->where('kd_sub_unit', $kdSU)
            ->groupBy('kd_sub_unit')
            ->get()
            ->getRowArray();
    }

    public function listprogram($opd, $kd_sub_unit, $tahun)
    {
        return $this->select('*')->selectSUM('pagu_rincian')->where('pagu_rincian<>', '0')
            ->where('nm_sub_unit', $opd)
            ->where('kd_sub_unit', $kd_sub_unit)
            ->where('tahun', $tahun)
            ->groupBy('kd_urusan')
            ->where('pagu_rincian<>', '0')
            ->get()
            ->getResultArray();
    }
    public function listapbd($opd)
    {
        return $this
            ->where('pagu_rincian<>', '0')
            ->where('nm_sub_unit', $opd)
            ->where('pagu_rincian<>', '0')
            ->get()
            ->getResultArray();
    }
    public function paguperurusan($opd, $kd_sub_unit, $tahun)
    {
        return $this
            ->select('*')
            ->selectSum('pagu_rincian')
            ->where('nm_sub_unit', $opd)
            ->where('kd_sub_unit', $kd_sub_unit)
            ->where('tahun', $tahun)
            ->groupBy('kd_urusan')
            ->where('pagu_rincian<>', '0')
            ->get()
            ->getResultArray();
    }
    public function dataSKOpd($kdSU, $tahun)
    {
        return $this
            ->select('ta_apbd.tahun,kd_sub_unit,kd_program,kd_kegiatan,kd_subkegiatan,nm_subkegiatan')
            ->selectSUM('ta_apbd.pagu_rincian', 'pagu')
            ->where('ta_apbd.kd_sub_unit', $kdSU)
            ->where('ta_apbd.tahun', $tahun)
            ->groupBy('ta_apbd.kd_subkegiatan')
            ->orderBy('ta_apbd.kd_subkegiatan', 'asc')
            ->get()
            ->getResultArray();
        // ->select('ta_rinci_realisasi_lrfk.*,b.pagu_rincian')
        // ->selectSUM('b.pagu_rincian')

        // ->selectSUM('ta_rinci_realisasi_lrfk.realisasi')
        // ->join('ta_apbd b', 'ta_rinci_realisasi_lrfk.kd_subkegiatan=b.kd_subkegiatan')
        // ->where('ta_rinci_realisasi_lrfk.kd_sub_unit', $kdSU)
        // ->where('ta_rinci_realisasi_lrfk.delete_at=', 0)

    }
}
