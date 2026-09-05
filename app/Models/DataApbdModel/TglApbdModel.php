<?php

namespace App\Models\DataApbdModel;

use CodeIgniter\Model;

class TglApbdModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'ta_tgldataapbd';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $allowedFields = [
        'id',
        'tahun',
        'tanggal',
    ];

    public function tgldataaktif()
    {
        $q = $this
            // ->select('tanggal')
            // ->where('tahun', session()->get('tahun'))
            ->get()
            ->getRowArray();
        return $q;
    }
    public function tglpilih($id)
    {
        $q = $this
            ->where('tahun', session()->get('tahun'))
            ->where('id', $id)
            ->get()
            ->getRowArray();
        return $q;
    }
    public function simpantgl($tahun, $tanggal)
    {
        $data = [
            'tahun' => $tahun,
            'tanggal' => $tanggal
        ];
        $this->insert($data);
    }
    public function updatetgl($id, $tahun, $tanggal)
    {
        $data = [
            'tahun' => $tahun,
            'tanggal' => $tanggal
        ];
        $this->update($id, $data);
    }
}
