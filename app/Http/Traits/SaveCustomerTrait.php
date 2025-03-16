<?php

namespace App\Http\Traits;

use App\Models\Customer;
use App\Models\CustomerMeta;
use Illuminate\Http\Request;

trait SaveCustomerTrait
{
    public function saveMeta($meta, $user)
    {
        if (!$meta) {
            return;
        }

        foreach ($meta as $key => $val) {
            if (is_array($val)) {
                foreach ($val as $k => $v) {
                    CustomerMeta::updateOrCreate([
                        'customer_id' => $user->id,
                        'meta_key'    => $k,
                        'meta_value'  => $v,
                    ], [
                        'user_id'    => $user->id,
                        'meta_key'   => $k,
                        'meta_value' => $v,
                    ]);
                }
            } else if (!is_null($val) && $val) {

                CustomerMeta::updateOrCreate([
                    'customer_id' => $user->id,
                    'meta_key'    => $key,
                ], [
                    'meta_value' => $val,
                ]);
            }
        }
    }
}
