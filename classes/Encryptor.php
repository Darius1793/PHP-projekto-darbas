<?php

class Encryptor {
    private static $method = "AES-256-CBC";

    public static function encrypt($data, $key) {
        $secretKey = hash("sha256", $key, true);
        $iv = openssl_random_pseudo_bytes(16);

        $encrypted = openssl_encrypt($data, self::$method, $secretKey, 0, $iv);

        return base64_encode($iv . $encrypted);
    }

    public static function decrypt($encryptedData, $key) {
        $secretKey = hash("sha256", $key, true);
        $data = base64_decode($encryptedData);

        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);

        return openssl_decrypt($encrypted, self::$method, $secretKey, 0, $iv);
    }
}