<?php

namespace App\Http\Controllers\Api;

use App\Models\TagLine;
use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
use App\Http\Controllers\Controller;
use App\Http\Resources\TagLineResource;

class TagLineController extends Controller
{
    public function index()
    {
        $taglines = TagLine::active()->latest('id')->paginate(10);
        return response()->json([
            'total'          => $taglines->total(),
            'current_page'   => $taglines->currentPage(),
            'per_page'       => $taglines->perPage(),
            'data'           => TagLineResource::collection($taglines->items()),
            'has_more_pages' => $taglines->hasMorePages(),
        ]);
    }

    // public function show($id)
    // {
    //     $tagline = TagLine::active()->find($id);
    //     if (!$tagline) {
    //         return ResponseJson::error('Your banner was not found.', 404);
    //     } else {
    //         return ResponseJson::success(new TagLineResource($tagline));
    //     }
    // }
}
