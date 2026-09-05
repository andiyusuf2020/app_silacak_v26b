<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Tcpdf extends BaseConfig
{
    public $tcpdf = [
        'orientation'   => 'P', // P = portrait, L = landscape
        'unit'          => 'mm',
        'format'        => 'A4',
        'unicode'       => true,
        'encoding'      => 'UTF-8',
        'diskcache'    => true, // Mengaktifkan disk caching untuk performa
        'pdfa'          => false // Set true untuk PDF/A mode
    ];
}
