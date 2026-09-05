<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Upload extends BaseConfig
{
    public $uploadPath = WRITEPATH . 'uploads/';
    public $allowedTypes = 'xlsx|xls';
    public $maxSize = 2048; // 2MB
    public $encryptName = true;
    public $detectMime = true;
    public $strictTypes = true;
}
