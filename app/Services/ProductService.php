<?php

namespace App\Services;

use App\Class\Product\ProductQuery;
use App\Helpers\ResponseJson;
use App\Http\Traits\SaveTranslateTrait;
use App\Models\Product;
use App\Models\Sku;
use App\Models\SkuAttributeOption;
use App\Models\Video;
use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductService
{
    use SaveTranslateTrait;

    public function listing(Request $request)
    {
        $products = new ProductQuery($request);
        $products = $products
            ->withSkus()
            ->withTranslates()
            ->withMinMaxOriginalPrice()
            ->withMinMaxSalePrice()
            ->withGlobalFilterByKeyword()
            ->withTotalStock()
            ->withSorting()
            ->latest()
            ->paginate(10);

        return $products;
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $additional = collect($request->translates)->value('additionals');

            $additionalStore = [];

            foreach ($additional as $key => $value) {
                if ($value['text'] != null) {
                    $additionalStore[] = $value;
                }
            }

            $product = Product::create([
                'name'           => $request->name,
                'description'    => $request->description,
                'type'           => $request->type,
                'active'         => $request->active,
                'product_status' => $request->product_status ? 'arrival' : 'featured',
                'additionals'    => json_encode($additionalStore, true),
                'has_additional' => $request->has_additional,
            ]);

            $this->storeTranslate($request, $product);

            $skuInsertData = [];

            $requestSkus = $request->skus;

            foreach ($requestSkus as $key => $value) {
                $skuInsertData[] = [
                    'code'                => $value['code'],
                    'stock'               => $value['stock'],
                    'stock_status'        => $value['stock_status'],
                    'weight'              => $value['weight'],
                    'original_price'      => $value['price'],
                    'discount_schedule'   => $value['discount_schedule'],
                    'sale_price'          => $value['sale_price'],
                    'discount_type'       => $value['sale_price'] ? 'percent' : null,
                    'discount'            => $value['sale_price'] ? round((($value['price'] - $value['sale_price']) / $value['price']) * 100, 2) : null,
                    'attribute_option_id' => isset($value['attribute_option_id']) ? $value['attribute_option_id'] : [],
                    'upload'              => $value['upload'],
                ];
            }

            if ($request->input('upload')) {
                foreach ($request->input('upload') as $file) {
                    $product->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('images');
                }
            }

            $product->attributes()->attach($request->attribute_id);

            $product->categories()->attach($request->category);

            $skus = $product->skus()->createMany($skuInsertData);

            $data = [];

            foreach ($skus as $index => $sku) {
                $attributeOptionIds = $skuInsertData[$index]['attribute_option_id'];
                foreach ($attributeOptionIds as $attributeOptionId) {
                    $data[] = [
                        'sku_id'              => $sku->id,
                        'attribute_option_id' => $attributeOptionId,
                    ];
                }

                if ($skuInsertData[$index]['upload']) {
                    foreach ($skuInsertData[$index]['upload'] as $file) {
                        if (Storage::disk('tmp')->exists('uploads/' . $file)) {
                            $sku->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('images');
                        }
                    }
                }
            }

            SkuAttributeOption::insert($data);

            $imageLinkFile = null;
            $videoLinkFile = null;


            if ($request->hasFile('image_link')) {
                $file          = $request->input('image_link');
                $imageLinkFile = basename($file[0]);
                $tmpPath       = storage_path('tmp/uploads/' . $imageLinkFile);
                Storage::putFileAs('public/images/', new File($tmpPath), $imageLinkFile);
            }

            if ($request->hasFile('video_link')) {
                $file          = $request->input('video_link');
                $videoLinkFile = basename($file[0]);
                $tmpPath       = storage_path('tmp/uploads/' . $videoLinkFile);
                Storage::putFileAs('public/videos/', new File($tmpPath), $videoLinkFile);
            }

            Video::create([
                'model_id'   => $product->id,
                'model_type' => get_class($product),
                'image_link' => $imageLinkFile,
                'video_link' => $videoLinkFile,
            ]);

            DB::commit();
            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Product $product, Request $request)
    {
        DB::beginTransaction();
        try {
            $additional = collect($request->translates)->value('additionals');

            $additionalStore = [];

            foreach ($additional as $key => $value) {
                if ($value['text'] != null) {
                    $additionalStore[] = $value;
                }
            }

            $product->update([
                'name'           => $request->name,
                'description'    => $request->description,
                'type'           => $request->type,
                'active'         => $request->active,
                'product_status' => $request->product_status ? 'arrival' : 'featured',
                'additionals'    => json_encode($additionalStore, true),
                'has_additional' => $request->has_additional,
            ]);

            $this->storeTranslate($request, $product);

            if ($product->type == Product::PRODUCT_TYPE['simple']) {
                $sku = $request->skus;
                $this->updateVairation($sku[0], $product->sku);
            }

            $media = $product->images->pluck('file_name')->toArray();

            foreach ($request->input('upload', []) as $file) {
                if (!in_array($file, $media)) {
                    if (Storage::disk('tmp')->exists('uploads/' . $file)) {
                        $product->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('images');
                    }
                }
            }

            $order = $request->input('upload', []);

            $mediaItems = Media::whereIn('file_name', $order)->get();

            foreach ($mediaItems as $index => $media) {
                $originalName = $media->file_name;
                if (in_array($originalName, $order)) {
                    $media->update([
                        'order_column' => array_search($originalName, $order) + 1,
                    ]);
                }
            }

            $product->load('video');

            $imageLinkFile = $product->video ? $product->video->image_link : null;
            $videoLinkFile = $product->video ? $product->video->video_link : null;

            // Delete and update image_link if a new one is provided
            if ($request->hasFile('image_link')) {
                if ($imageLinkFile && Storage::exists('public/images/' . $imageLinkFile)) {
                    Storage::delete('public/images/' . $imageLinkFile);
                }

                $file          = $request->input('image_link');
                $imageLinkFile = basename($file[0]);
                $tmpPath       = storage_path('tmp/uploads/' . $imageLinkFile);
                Storage::putFileAs('public/images', new File($tmpPath), $imageLinkFile);
            }

            if ($request->hasFile('video_link')) {

                if ($videoLinkFile && Storage::exists('public/videos/' . $videoLinkFile)) {
                    Storage::delete('public/videos/' . $videoLinkFile);
                }

                $file          = $request->input('video_link');
                $videoLinkFile = basename($file[0]);
                $tmpPath       = storage_path('tmp/uploads/' . $videoLinkFile);
                Storage::putFileAs('public/videos/', new File($tmpPath), $videoLinkFile);

                $product->video()->updateOrCreate([
                    'image_link' => $imageLinkFile,
                    'video_link' => $videoLinkFile,
                ], [
                    'model_id'   => $product->id,
                    'model_type' => Product::class,
                ]);
            }



            $product->attributes()->sync($request->attribute_id);

            $product->categories()->sync($request->category);

            DB::commit();

            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function storeVairation($request)
    {
        DB::beginTransaction();
        try {
            $sku = Sku::create([
                'product_id'          => $request['product_id'],
                'code'                => $request['code'],
                'stock'               => $request['stock'],
                'stock_status'        => $request['stock_status'],
                'weight'              => $request['weight'],
                'original_price'      => $request['price'],
                'sale_price'          => $request['sale_price'],
                'discount_type'       => $request['sale_price'] ? 'percent' : null,
                'discount'            => $request['sale_price'] ? round((($request['price'] - $request['sale_price']) / $request['price']) * 100, 2) : null,
                'discount_schedule'   => $request['discount_schedule'],
                'discount_start_date' => $request['discount_schedule'] ? Carbon::parse($request['discount_start_date'])->format('Y-m-d H:i:s') : null,
                'discount_end_date'   => $request['discount_schedule'] ? Carbon::parse($request['discount_end_date'])->format('Y-m-d H:i:s') : null,
            ]);

            if ($request['upload']) {
                foreach ($request['upload'] as $file) {
                    if (Storage::disk('tmp')->exists('uploads/' . $file)) {
                        $sku->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('images');
                    }
                }
            }

            $data = [];
            foreach ($request['attribute_option_id'] as $key => $value) {
                $data[] = [
                    'sku_id'              => $sku->id,
                    'attribute_option_id' => $value,
                    'created_at'          => now(),
                    'updated_at'          => now(),
                ];
            }

            SkuAttributeOption::insert($data);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateVairation($request, Sku $sku)
    {
        DB::beginTransaction();
        try {
            $sku->update([
                'code'                => $request['code'],
                'stock'               => $request['stock'],
                'weight'              => $request['weight'],
                'original_price'      => $request['price'],
                'sale_price'          => $request['sale_price'],
                'expense'             => $request['expense'],
                'discount_type'       => $request['sale_price'] ? 'percent' : null,
                'discount'            => $request['sale_price'] ? round((($request['price'] - $request['sale_price']) / $request['price']) * 100, 2) : null,
                'discount_schedule'   => $request['discount_schedule'],
                'discount_start_date' => $request['discount_schedule'] ? Carbon::parse($request['discount_start_date'])->format('Y-m-d H:i:s') : null,
                'discount_end_date'   => $request['discount_schedule'] ? Carbon::parse($request['discount_end_date'])->format('Y-m-d H:i:s') : null,
            ]);

            $media = $sku->images->pluck('file_name')->toArray();

            foreach ($request['upload'] as $file) {
                if (!in_array($file, $media)) {
                    if (Storage::disk('tmp')->exists('uploads/' . collect($file)->values())) {
                        $sku->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('images');
                    }
                }
            }

            $skuTotal = $sku['stock'];

            $sku->refresh();

            $sku->update([
                'stock_status' => $skuTotal != 0 ? 'in_stock' : $request['stock_status'],
            ]);

            $order = $request['upload'];

            $mediaItems = Media::whereIn('file_name', $order)->get();

            foreach ($mediaItems as $index => $media) {
                $originalName = $media->file_name;
                if (in_array($originalName, $order)) {
                    $media->update([
                        'order_column' => array_search($originalName, $order) + 1,
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function show($id)
    {
        $product = Product::with(['categories', 'skus', 'attributes', 'skuAttributeOptions', 'translates'])->findOrFail($id);

        return $product;
    }

    public function toggleWishlist(Product $product)
    {
        $authId   = Auth::id();
        $wishlist = Wishlist::where('product_id', $product->id)
            ->where('customer_id', $authId)
            ->first();

        if (is_null($wishlist)) {
            Wishlist::create([
                'product_id'  => $product->id,
                'customer_id' => $authId,
            ]);

            return ResponseJson::success(null, 'Add to wishlist successful.');
        } else {
            $wishlist->delete();

            return ResponseJson::success(null, 'Remove from wishlist successful.');
        }
    }
}
