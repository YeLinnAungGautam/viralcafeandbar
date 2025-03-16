<?php

namespace App\Http\Traits;

use App\Models\Translate;
use Illuminate\Http\Request;


trait SaveTranslateTrait
{
    public function storeTranslate(Request $request, $model)
    {
        $translates = $request->input('translates', []);

        foreach ($translates as $translate) {
            $data = collect($translate)->except('language_id', 'label', 'id');

            foreach ($data as $key => $value) {
                Translate::updateOrCreate(
                    [
                        'model_id'    => $model->id,
                        'model_type'  => get_class($model),
                        'key'         => $key,
                        'language_id' => $translate['language_id'],
                    ],
                    [
                        'value' => $value,
                    ],
                );
            }
        }
    }
}
