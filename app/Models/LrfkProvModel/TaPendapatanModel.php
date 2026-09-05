<?php

namespace App\Models\LrfkProvModel;

use CodeIgniter\Model;

class TaPendapatanModel extends Model
{

    protected $DBGroup              = 'default';
    //    protected $table                = 'ta_apbd_persubkegiatan';
    protected $table                = 'ta_apbd_pendapatan';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement = true;

    protected $returnType     = 'array'; // Tipe data yang dikembalikan

    public function perOPD($kdOPD)
    {
        return $this
            ->selectSum('pagu')
            ->where('pagu<>', '0')
            ->where('kd_opd', $kdOPD)
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
