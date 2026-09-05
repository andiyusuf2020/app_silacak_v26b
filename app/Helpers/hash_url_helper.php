<?php

use CodeIgniter\HTTP\URI;

if (!function_exists('hash_url')) {
    function hash_url(string $route, array $params = []): string
    {
        $queryString = http_build_query($params);
        $secretKey   = env('app.secretKey');
        $hash        = hash_hmac('sha256', $queryString, $secretKey);
        return site_url("{$route}?{$queryString}&hash={$hash}");
    }
}
