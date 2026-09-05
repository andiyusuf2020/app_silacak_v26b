<?php

namespace App\Models\LrfkProvModel;

use CodeIgniter\Model;

class TaResumeRealisasiModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_resume_realopd';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement = true;

    protected $returnType     = 'array'; // Tipe data yang dikembalikan

    public function perOPD($kd_sub_unit, $tahun)
    {
        return $this
            ->select('*')
            ->where('kd_sub_unit', $kd_sub_unit)
            ->where('tahun', $kd_sub_unit)
            ->get()
            ->getRowArray();
    }
    public function perOPDObjek($kdOPD, $kdObjek)
    {
        return $this
            ->selectSum('pagu')
            ->where('pagu<>', '0')
            ->where('kd_opd', $kdOPD)
            ->like('kd_akun', $kdObjek)
            ->get()
            ->getRowArray();
    }
    public function datapendapatanperOPD($kdU)
    {
        return $this
            ->select('*')
            ->where('pagu<>', '0')
            ->where('kd_opd', $kdU)
            ->get()
            ->getResultArray();
    }
}
