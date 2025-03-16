<?php

namespace App\Http\Controllers\Api;

use App\Class\Product\ProductQuery;
use App\Helpers\ResponseJson;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ProductResource;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Wishlist;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct(public ProductService $productService) {}

    public function index(Request $request)
    {
        $products = new ProductQuery($request);

        $products = $products
            ->withActive()
            ->withSkus()
            ->withNewArrival()
            ->withTranslates()
            ->withMinMaxOriginalPrice()
            ->withMinMaxSalePrice()
            ->withMinDiscount()
            ->withTotalStock()
            ->withWishlistStatus()
            ->withGlobalFilterByKeyword()
            ->withFilterByCategoryId()
            ->withFilterByOffers()
            ->paginate(6);

        return response()->json([
            'total'          => $products->total(),
            'current_page'   => $products->currentPage(),
            'per_page'       => $products->perPage(),
            'data'           => ProductResource::collection($products->items()),
            'has_more_pages' => $products->hasMorePages(),
        ]);
    }

    public function show($id)
    {
        $product = new ProductQuery();

        $product = $product
            ->withMinMaxOriginalPrice()
            ->withMinMaxSalePrice()
            ->withVideo()
            ->withCategories()
            ->withSkus()
            ->withMinDiscount()
            ->withTranslates()
            ->withTotalStock()
            ->withWishlistStatus()
            ->show($id);

        if (!$product) {
            return ResponseJson::error('Product was not found!', 404);
        }
        return ResponseJson::success(new ProductResource($product));
    }

    public function wishlist(Request $request)
    {
        $authId = Auth::id();

        $productIds = Wishlist::where('customer_id', $authId)
            ->pluck('product_id')
            ->toArray();

        $products = new ProductQuery($request);

        $products = $products
            ->withTranslates()
            ->withVideo()
            ->withCategories()
            ->withMinMaxOriginalPrice()
            ->withMinMaxSalePrice()
            ->withMinDiscount()
            ->withTotalStock()
            ->withWishlistStatus()
            ->withFilterByCategoryId()
            ->withProductIn($productIds)
            ->paginate(10);

        return response()->json([
            'total'          => $products->total(),
            'current_page'   => $products->currentPage(),
            'per_page'       => $products->perPage(),
            'data'           => ProductResource::collection($products->items()),
            'has_more_pages' => $products->hasMorePages(),
        ]);
    }

    public function toggleWishlist($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return ResponseJson::error('Product was not found!', 404);
        }

        return $this->productService->toggleWishlist($product);
    }

    public function filterByKeyword(Request $request)
    {
        $products = [];

        if ($request->keyword) {
            $products = new ProductQuery($request);
            $products = $products->withFilterByName()
                ->get();
        }

        return ResponseJson::success($products);
    }

    public function related($id)
    {
        $products = new ProductQuery();

        $categoryIds = ProductCategory::where('product_id', $id)
            ->pluck('category_id')
            ->toArray();

        $products = $products
            ->withActive()
            ->whereNotId($id)
            ->withCategories()
            ->withFilterByCategoryId($categoryIds)
            ->withMinMaxOriginalPrice()
            ->withMinMaxSalePrice()
            ->withMinDiscount()
            ->withSkus()
            ->withTranslates()
            ->withTotalStock()
            ->withWishlistStatus()
            ->paginate(8);

        return response()->json([
            'total'          => $products->total(),
            'current_page'   => $products->currentPage(),
            'per_page'       => $products->perPage(),
            'data'           => ProductResource::collection($products->items()),
            'has_more_pages' => $products->hasMorePages(),
        ]);
    }
}
