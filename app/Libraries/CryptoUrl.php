<?php

namespace App\Libraries;

class CryptoUrl
{
    // Kunci rahasia server (32 karakter / 256-bit)
    private static string $key = '4v8x!A%D*G-KaPdSgVkYp3s6v9y$B?E%';

    /**
     * Enkripsi Payload (AES-256-GCM)
     */
    public static function encrypt(array $data): string
    {
        $data['timestamp'] = time(); // Tambahkan timestamp waktu server (detik)
        $jsonString = json_encode($data);

        $cipher = "aes-256-gcm";
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);

        // Enkripsi data dengan tag otentikasi (anti-tampering)
        $encrypted = openssl_encrypt($jsonString, $cipher, self::$key, OPENSSL_RAW_DATA, $iv, $tag);

        // Gabungkan IV + TAG + Encrypted Data lalu encode ke URL-safe Base64
        $combined = $iv . $tag . $encrypted;
        return rtrim(strtr(base64_encode($combined), '+/', '-_'), '=');
    }

    /**
     * Dekripsi & Validasi Payload
     */
    public static function decrypt(string $token, int $maxAgeSeconds = 900): ?array
    {
        $cipher = "aes-256-gcm";
        $ivlen = openssl_cipher_iv_length($cipher);
        $taglen = 16;

        $combined = base64_decode(strtr($token, '-_', '+/'));

        if (strlen($combined) < ($ivlen + $taglen)) {
            return null;
        }

        $iv = substr($combined, 0, $ivlen);
        $tag = substr($combined, $ivlen, $taglen);
        $ciphertext = substr($combined, $ivlen + $taglen);

        $decryptedJson = openssl_decrypt($ciphertext, $cipher, self::$key, OPENSSL_RAW_DATA, $iv, $tag);

        if ($decryptedJson === false) {
            return null; // Dekripsi gagal (token diubah/dimanipulasi)
        }

        $data = json_decode($decryptedJson, true);

        // Validasi kadaluwarsa (15 menit = 900 detik)
        if (!isset($data['timestamp']) || (time() - $data['timestamp']) > $maxAgeSeconds) {
            return null; // Token kadaluwarsa
        }

        return $data;
    }
}
