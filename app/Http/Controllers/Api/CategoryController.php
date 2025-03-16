<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseJson;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::isParent()
            ->with('translates')
            ->orderBy('name', 'asc')
            ->get();

        return ResponseJson::success(CategoryResource::collection($categories));
    }
    public function filterByKeyword(Request $request)
    {


        $categories = [];
        if ($request->keyword) {
            $categories = Category::where("name", "like", "%{$request->keyword}%")->get();
        }


        return ResponseJson::success(CategoryResource::collection($categories));
    }

}
