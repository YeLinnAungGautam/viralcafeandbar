<?php

function encryptApiToken()
{
    $secretKey   = config('key.api_protect.secret_key');
    $publicToken = config('key.api_protect.public_token');

    $token = $publicToken . '|' . now()->addDays(3)->format("Y-m-d");

    $hash = hash_hmac('sha256', $token, key: $secretKey);

    return $hash;
}

function verifyHmacWithExpiry($hash)
{
    if (!is_null($hash)) {
        $providedHmac = encryptApiToken();

        if (!hash_equals($hash, $providedHmac)) {
            return false;
        }

        return true;
    } else {
        return false;
    }

}
