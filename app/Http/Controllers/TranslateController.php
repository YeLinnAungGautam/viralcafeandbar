<?php

namespace App\Http\Controllers;

use App\Http\Traits\SaveTranslateTrait;
use App\Models\Translate;
use Illuminate\Http\Request;

class TranslateController extends Controller
{
    public function destroy($id)
    {
        Translate::findOrFail($id)->delete();
    }
}
