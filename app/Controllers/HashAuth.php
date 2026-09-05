<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Controller;

class HashCek extends Controller
{
    public function tes($da_reg)
    {
        // Ambil parameter dan hash
        $receivedParams = $da_reg->getGet();
        $receivedHash   = $receivedParams['hash'] ?? '';
        unset($receivedParams['hash']);

        // Validasi hash
        $queryString = http_build_query($receivedParams);
        $secretKey    = env('app.secretKey');
        $computedHash = hash_hmac('sha256', $queryString, $secretKey);

        if (!hash_equals($computedHash, $receivedHash)) {
            throw PageNotFoundException::forPageNotFound();
        } else {
            // Contoh penggunaan parameter
            // $userId = $receivedParams['user_id'] ?? null;
            //$action = $receivedParams['action'] ?? null;
            return; //"Hash valid! User ID: {$userId}, Action: {$action}";
        }
    }
}
