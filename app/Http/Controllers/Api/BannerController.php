<?php

namespace App\Http\Controllers\Api;

use App\Class\Banner\BannerQuery;
use App\Helpers\ResponseJson;
use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index(Request $request)
    {

        $banners = (new BannerQuery($request))
            ->withActive()
            ->withFilterByType()
            ->paginate(10);

        return response()->json([
            'total'          => $banners->total(),
            'current_page'   => $banners->currentPage(),
            'per_page'       => $banners->perPage(),
            'data'           => BannerResource::collection($banners->items()),
            'has_more_pages' => $banners->hasMorePages(),
        ]);
    }
    public function show($id)
    {
        $banner = Banner::active()->find($id);

        if (!$banner) {
            return ResponseJson::error('Your banner was not found.', 404);
        } else {
            return ResponseJson::success(new BannerResource($banner));
        }
    }
}
