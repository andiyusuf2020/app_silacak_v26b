<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Deepseek extends BaseConfig
{
    // API Key DeepSeek - diambil dari .env
    public $apiKey = '';

    // Base URL API DeepSeek
    public $apiUrl = 'https://api.deepseek.com/v1';

    // Model default yang digunakan
    public $defaultModel = 'deepseek-chat';

    // Parameter default untuk API
    public $defaultParams = [
        'temperature' => 0.7,
        'max_tokens' => 2000,
        'top_p' => 1,
        'frequency_penalty' => 0,
        'presence_penalty' => 0,
    ];

    // Konstruktor untuk mengambil nilai dari .env
    public function __construct()
    {
        $this->apiKey = getenv('DEEPSEEK_API_KEY') ?: $this->apiKey;

        // Validasi konfigurasi
        if (empty($this->apiKey)) {
            throw new \RuntimeException('DeepSeek API key tidak ditemukan. Harap set di .env file.');
        }
    }

    /**
     * Mendapatkan headers untuk request API
     */
    public function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }
}
