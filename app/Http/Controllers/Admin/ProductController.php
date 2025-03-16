<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProductsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Traits\MediaUploadingTrait;
use App\Models\Attribute;
use App\Models\AttributeOption;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sku;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    use MediaUploadingTrait;

    public function __construct(public ProductService $productService) {}

    public function index(Request $request)
    {
        abort_if(Gate::denies('product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = $this->productService->listing($request);

        return Inertia::render('Admin/Product/Index', [
            'products' => $products,
        ]);
    }

    public function create()
    {
        abort_if(Gate::denies('product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $attributes       = Attribute::orderBy('name', 'asc')->get();
        $categories       = Category::orderBy('name', 'asc')->get();
        $attributeOptions = AttributeOption::orderBy('value', 'asc')->get();

        return Inertia::render('Admin/Product/Create', [
            'attributes'       => $attributes,
            'categories'       => $categories,
            'attributeOptions' => $attributeOptions,
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        $product =   $this->productService->store($request);

        return to_route('admin.products.index');
    }

    public function edit($id)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $attributes = Attribute::orderBy('name', 'asc')->get();
        $products   = $this->productService->show($id);
        $categories = Category::isParent()
            ->orderBy('name', 'asc')
            ->get();

        return Inertia::render('Admin/Product/Edit', [
            'product'    => new ProductResource($products),
            'attributes' => $attributes,
            'categories' => $categories,
        ]);
    }

    public function show($id)
    {
        $product = $this->productService->show($id);
        return Inertia::render('Admin/Product/Show', [
            'product' => new ProductResource($product),
        ]);
    }

    public function update($id, UpdateProductRequest $request)
    {
        $product = Product::findOrFail($id);
        $this->productService->update($product, $request);

        return to_route('admin.products.edit', $id);
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $product = Product::find($id);
        $product->delete();
    }

    public function storeVairation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'price'               => 'required|numeric',
            'sale_price'          => 'nullable|numeric|lt:price',
            'stock'               => 'nullable|numeric',
            'discount_schedule'   => 'boolean',
            'discount_start_date' => 'required_if:discount_schedule,true',
            'discount_end_date'   => 'required_if:discount_schedule,true',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $this->productService->storeVairation($validator->valid());
    }

    public function updateVairation(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'price'               => 'required|numeric',
            'sale_price'          => 'nullable|numeric|lt:price',
            'stock'               => 'nullable|numeric',
            'discount_schedule'   => 'boolean',
            'discount_start_date' => 'required_if:discount_schedule,true',
            'discount_end_date'   => 'required_if:discount_schedule,true',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $sku = Sku::find($id);

        $this->productService->updateVairation($validator->valid(), $sku);
    }

    public function removeVairation($id)
    {
        Sku::find($id)->delete();
    }

    public function export()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }
}
