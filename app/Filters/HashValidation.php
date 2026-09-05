<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Exceptions\PageNotFoundException;

class HashValidation implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $receivedParams = $request->getGet();
        $receivedHash   = $receivedParams['hash'] ?? '';
        unset($receivedParams['hash']);

        $queryString = http_build_query($receivedParams);
        $secretKey    = env('app.secretKey');
        $computedHash = hash_hmac('sha256', $queryString, $secretKey);

        if (!hash_equals($computedHash, $receivedHash)) {
            throw PageNotFoundException::forPageNotFound();
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
