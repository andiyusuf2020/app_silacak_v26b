<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Exceptions\PageNotFoundException;
use PhpParser\Node\Stmt\Return_;

class Profile extends Controller
{
    public function index()
    {
        return view('hash');
    }
    public function action()
    {
        $request = $this->request;

        // Ambil parameter dan hash
        $receivedParams = $request->getGet();
        $receivedHash   = $receivedParams['hash'] ?? '';
        unset($receivedParams['hash']);

        // Validasi hash
        $queryString = http_build_query($receivedParams);
        $secretKey    = env('app.secretKey');
        $computedHash = hash_hmac('sha256', $queryString, $secretKey);

        if (!hash_equals($computedHash, $receivedHash)) {
            throw PageNotFoundException::forPageNotFound();
        }

        // Contoh penggunaan parameter
        $userId = $receivedParams['user_id'] ?? null;
        $action = $receivedParams['action'] ?? null;

        return "Hash valid! User ID: {$userId}, Action: {$action}";
    }
}
