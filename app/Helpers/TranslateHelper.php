<?php

use App\Models\Language;
use App\Models\Localization;
use App\Models\Order;

class TranslateHelper
{
    public static function getTransalateMessage($key, $spend, $gain)
    {
        $languages    = self::getLanguages();
        $language_ids = $languages->pluck('id')->toArray();

        $bodyMessage = Localization::getValueByKey($key, $language_ids)->toArray();

        $translates = [];
        foreach ($languages as $index => $value) {

            if (!isset($bodyMessage[$index]['value'])) {
                $body = config("messages.$key");
            } else {
                $body = $bodyMessage[$index]['value'];
            }

            $translates[$value['code']] = [
                'title' => 'Point Earned',
                'body'  => sprintf($body, $spend, $gain),
            ];
        }

        return $translates;
    }

    public static function getOrderMessage(Order $order)
    {
        $languages    = self::getLanguages();
        $language_ids = $languages->pluck('id')->toArray();
        $key          = '';

        switch ($order->order_status):
            case 'confirmed':
                $key = 'order_confirm';
                $title = 'Order Confirmation';
                break;

            case 'pending':
                $key = 'order_pending';
                $title = 'Order Pending';
                break;

            case 'cancel':
                $key = 'order_cancel';
                $title = 'Order Cancellation';
                break;
        endswitch;

        $bodyMessage = Localization::getValueByKey($key, $language_ids)->toArray();

        $translates = [];
        foreach ($languages as $index => $value) {

            if (!isset($bodyMessage[$index]['value'])) {
                $body = config("messages.$key");
            } else {
                $body = $bodyMessage[$index]['value'];
            }

            $translates[$value['code']] = [
                'title' => $title,
                'body'  => sprintf($body, $order->order_no),
            ];
        }

        return $translates;
    }

    private static function getLanguages()
    {
        $languages = Language::all();
        return $languages;
    }
}
