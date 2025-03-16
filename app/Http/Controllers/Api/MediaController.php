<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\MediaUploadingTrait;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    use MediaUploadingTrait;
}
