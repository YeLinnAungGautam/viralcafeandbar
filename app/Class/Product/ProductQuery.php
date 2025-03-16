<?php

namespace App\Class\Product;

use App\Models\Product;
use App\Models\Translate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductQuery
{
    /**
     * Create a new class instance.
     */
    private $query;

    public function __construct(public ?Request $request = null)
    {
        $this->query = Product::query();
    }


    public function withActive()
    {
        $this->query->isActive();
        return $this;
    }

    public function withTranslates()
    {
        $this->query->with('translates');
        return $this;
    }

    public function withVideo()
    {
        $this->query->with('video');
        return $this;
    }

    public function withSkus()
    {
        $this->query->with('skus');
        return $this;
    }

    public function withSorting()
    {
        $sortOrder = $this->request->input('sort_by');
        $column    = $this->request->input('column') ?? 'id';

        $columns   = ['original_price', 'stock'];
        $isInclude = in_array($column, $columns);

        if ($sortOrder) {
            $this->query
                ->when($isInclude, function ($subQuery1) use ($column, $sortOrder) {
                    $subQuery1->join('skus', 'skus.product_id', '=', 'products.id')
                        ->orderBy("skus.{$column}", $sortOrder);
                })
                ->when(!$isInclude, function ($subQuery2) use ($column, $sortOrder) {
                    $subQuery2->orderBy($column, $sortOrder);
                });
        }

        return $this;
    }

    public function withCategories()
    {
        $this->query->with('categories');
        return $this;
    }

    public function withSkuAttributeOptions()
    {
        $this->query->with('skuAttributeOptions');
        return $this;
    }

    public function withAttributes()
    {
        $this->query->with('attributes');
        return $this;
    }

    public function withFilterByName()
    {
        if ($this->request->input('keyword')) {
            $this->query->where("name", "like", "%{$this->request->keyword}%");
        }
        return $this;
    }

    public function withGlobalFilterByKeyword()
    {
        if ($this->request->input("keyword")) {
            $this->query->whereHas(
                "translates",
                function ($query) {
                    $query->where('value', 'like', "%{$this->request->keyword}%")
                        ->orWhere('value', 'like', "%{$this->request->keyword}%");
                }
            );
        }
        return $this;
    }

    // public function searchByKeyword()
    // {
    //     if ($this->request->input('keyword')) {
    //         $this->query->where("name", "like", "%{$this->request->keyword}%");
    //     }
    //     return $this;
    // }

    public function withFilterByCategoryId($categoryIds = [])
    {
        $categoryIds = count($categoryIds) > 0 ? $categoryIds : explode(',', $this->request->category_id);

        if (isset($this->request->category_id)) {
            $this->query->whereHas('categories', function ($subQuery) use ($categoryIds) {
                return $subQuery->whereIn('categories.id', $categoryIds);
            });
        }

        return $this;
    }

    public function withFilterByOffers()
    {
        if (isset($this->request->status) && $this->request->status == 'offers') {
            $this->query->whereHas('skus', function ($subQuery) {
                $currentDate = Carbon::now();

                return $subQuery->where(function ($query) use ($currentDate) {
                    $query->where(function ($q) use ($currentDate) {
                        $q->where('skus.discount_schedule', true)
                            ->where('skus.discount_start_date', '<=', $currentDate)
                            ->where('skus.discount_end_date', '>=', $currentDate);
                    })
                        ->orWhere(function ($q) use ($currentDate) {
                            $q->where('skus.discount_schedule', false)
                                ->whereNotNull('skus.sale_price')
                                ->whereNull('skus.discount_start_date')
                                ->whereNull('skus.discount_end_date');
                        });
                });
            });
        }
        return $this;
    }

    public function withMinMaxOriginalPrice()
    {
        $this->query->addSelect([
            'products.*',
            DB::raw('(SELECT CAST(MAX(skus.original_price) AS UNSIGNED) FROM skus WHERE skus.product_id = products.id) as max_original_price'),
            DB::raw('(SELECT CAST(MIN(skus.original_price) AS UNSIGNED) FROM skus WHERE skus.product_id = products.id) as min_original_price'),
        ]);

        return $this;
    }

    public function withMinMaxSalePrice()
    {
        $currentDate = now();

        $this->query->addSelect([
            'products.*',
            DB::raw('(SELECT CAST(MAX(skus.sale_price) AS UNSIGNED) FROM skus
            WHERE skus.product_id = products.id
            AND (
                (skus.discount_schedule = true AND skus.discount_start_date <= "' . $currentDate . '" AND skus.discount_end_date >= "' . $currentDate . '")
                OR
                (skus.discount_schedule = false AND skus.sale_price IS NOT NULL AND skus.discount_start_date IS NULL AND skus.discount_end_date IS NULL)
            )) as max_sale_price'),
            DB::raw('(SELECT CAST(MIN(skus.sale_price) AS UNSIGNED) FROM skus
            WHERE skus.product_id = products.id
            AND (
                (skus.discount_schedule = true AND skus.discount_start_date <= "' . $currentDate . '" AND skus.discount_end_date >= "' . $currentDate . '")
                OR
                (skus.discount_schedule = false AND skus.sale_price IS NOT NULL AND skus.discount_start_date IS NULL AND skus.discount_end_date IS NULL)
            )) as min_sale_price'),
        ]);

        return $this;
    }

    public function withMinDiscount()
    {
        $this->query->addSelect([
            'products.*',
            DB::raw('(SELECT MIN(skus.discount) FROM skus WHERE skus.product_id = products.id) as min_discount'),
        ]);
        return $this;
    }

    public function withTotalStock()
    {
        $this->query->addSelect([
            'products.*',
            DB::raw('(SELECT CAST(COALESCE(SUM(skus.stock), 0) AS UNSIGNED) FROM skus WHERE skus.product_id = products.id) as total_stock'),
        ]);

        return $this;
    }

    public function withWishlistStatus()
    {
        if (Auth::guard('sanctum')->check()) {
            $user = Auth::guard('sanctum')->user();

            $this->query->addSelect([
                'products.*',
                'in_wishlist' => DB::raw("(SELECT COUNT(*) > 0 FROM wishlists WHERE wishlists.product_id = products.id AND wishlists.customer_id = {$user->id}) as in_wishlist"),
            ]);
        } else {
            $this->query->addSelect([
                'products.*',
                'in_wishlist' => DB::raw('false as in_wishlist'),
            ]);
        }


        return $this;
    }

    public function whereNotId($id)
    {
        $this->query->where('products.id', '!=', $id);
        return $this;
    }

    public function withProductIn($id)
    {
        $this->query->whereIn('products.id', $id);
        return $this;
    }

    public function withNewArrival()
    {
        if ($this->request->status == 'arrival' && isset($this->request)) {
            // $this->query->newArrival();
            $this->query;
        }
        return $this;
    }

    public function show($id)
    {
        return $this->query->where('id', $id)->first();
    }

    public function get()
    {
        return $this->query->get();
    }

    public function latest()
    {
        $this->query->latest('id');
        return $this;
    }

    public function paginate($perPage = 10)
    {
        return $this->query->paginate($perPage)->withQueryString();
    }
}
