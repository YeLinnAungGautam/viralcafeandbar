<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\Sku;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProductsExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function view(): View
    {
        $products = Sku::with(['product', 'product.categories'])->get();

        return view("excels.product", [
            "products" => $products,
        ]);
    }

    // public function collection()
    // {
    //     return Product::all();
    // }
}
