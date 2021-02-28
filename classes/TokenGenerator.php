<?php
/*
 * Copyright (c) 2021 Azeyn
 *
 * The galleries plugin is licensed under the PolyForm Noncommercial License 1.0.0
 */

namespace Azeyn\Galleries\Classes;

use Azeyn\Galleries\Models\Settings;

class TokenGenerator
{
    private static function getCipher()
    {
        $ciphers = openssl_get_cipher_methods();
        $ciphers = array_filter($ciphers, static function ($cipher) {
            return stripos($cipher, 'ecb') === false;
        });
        $ciphers = array_filter($ciphers, static function ($cipher) {
            return stripos($cipher, 'bf') !== false;
        });

        if (count($ciphers) === 0) {
            return false;
        }

        if (in_array('bf-cfb', $ciphers, true)) {
            return 'bf-cfb';
        }
        if (in_array('bf-ofb', $ciphers, true)) {
            return 'bf-ofb';
        }
        return $ciphers[0];
    }

    public static function encode($data)
    {
        $cipher = self::getCipher();
        if (!$cipher) {
            return false;
        }

        $iv_length = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($iv_length);
        if (!$iv) {
            return false;
        }

        $encrypted = openssl_encrypt($data, $cipher, Settings::get('secret'), 0, $iv);
        if (!$encrypted) {
            return false;
        }

        return rtrim(strtr(base64_encode($iv . $encrypted), '+/', '-_'), '=');
    }

    public static function decode($secret)
    {
        $cipher = self::getCipher();
        if (!$cipher) {
            return false;
        }

        $secret = base64_decode(strtr($secret, '-_', '+/'), true);

        $iv_length = openssl_cipher_iv_length($cipher);

        $iv = substr($secret, 0, $iv_length);
        $encrypted = substr($secret, $iv_length);
        if (empty($iv) || empty($encrypted)) {
            return false;
        }

        return openssl_decrypt($encrypted, $cipher, Settings::get('secret'), 0, $iv);
    }
}
