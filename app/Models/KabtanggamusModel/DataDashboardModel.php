<?php

namespace App\Models\KabtanggamusModel;

use CodeIgniter\Model;

class DataDashboardModel extends Model
{
    protected $table            = 'kabtanggamus_dashboard_stats';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;

    protected $allowedFields    = [
        'jumlah_perangkat_daerah',
        'jumlah_kecamatan',
        'jumlah_tiuh_kampung',
        'total_anggaran_apbd',
        'index_sakip',
        'index_rb',
        'tingkat_kemiskinan',
        'angka_stunting',
    ];
}
