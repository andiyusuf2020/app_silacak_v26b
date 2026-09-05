<?php

namespace App\Models\LrfkProvModel;

use CodeIgniter\Model;

class TaRealPertahunModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_realisasipertahunopd';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;



    public function real($nm_sub_unit)
    {
        // $tahun = session()->get('tahun');
        $q = $this
            //  ->where('kd_sub_unit', $kd_sub_unit)
            ->where('nm_sub_unit', $nm_sub_unit)
            // ->where('tahun', $this->$tahun)

            ->get()
            ->getResultArray();
        foreach ($q as $key => $row) {
            $r20 = $row['real_20'] / $row['pagu_20'] * 100;
            $r21 = $row['real_21'] / $row['pagu_21'] * 100;
            $r22 = $row['real_22'] / $row['pagu_22'] * 100;
            $r23 = $row['real_23'] / $row['pagu_23'] * 100;
            $r24 = $row['real_24'] / $row['pagu_24'] * 100;

            $data = [
                number_format($r20, 2, '.'),
                number_format($r21, 2, '.'),
                number_format($r22, 2, '.'),
                number_format($r23, 2, '.'),
                number_format($r24, 2, '.')
            ];
            //        $isi20 = $row['pagu_20'];
        }
        return $data;
    }
}
