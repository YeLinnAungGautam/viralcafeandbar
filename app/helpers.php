<?php

use App\Models\Setting;

if (!function_exists('customer_currency')) {

    function customer_currency($value, $hasCode = true)
    {
        $setting = Setting::pluck('value', 'key')->toArray();

        if ($value !== null && $value !== '') {
            $parts = explode('.', number_format((float) $value, $setting['number_decimal'], '.', ''));

            $thousand_separator = $setting['thousand_separator'] ?? '';

            $parts[0] = preg_replace('/\B(?=(\d{3})+(?!\d))/', $thousand_separator, $parts[0]);

            $decimal = $setting['decimal_separator'] ?? '';

            $currency = $hasCode ? $setting['currency'] : "";

            return implode($decimal, $parts) . $currency;
        }

        return $value;
    }

    function convertToZawgyi(?string $text = null)
    {
        return Rabbit::uni2zg($text);
    }

}
