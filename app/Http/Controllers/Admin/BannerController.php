<?php

namespace App\Http\Controllers\Admin;

use App\Class\Banner\BannerQuery;
use Inertia\Inertia;
use App\Models\Banner;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ProductService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\BannerResource;
use App\Http\Traits\SaveTranslateTrait;
use Exception;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class BannerController extends Controller
{
    //
    use SaveTranslateTrait;
    public function __construct(public ProductService $productService) {}
    public function index(Request $request)
    {
        abort_if(Gate::denies('banner_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $banners = new BannerQuery($request);
        $banners = $banners->withCategory()
            ->withProduct()
            ->withSearchByTitle()
            ->withSearchByCategory()
            ->withSearchByProduct()
            ->paginate(10);


        return Inertia::render('Admin/Banner/Index', [
            'banners' => $banners,
        ]);
    }

    public function create()
    {
        abort_if(Gate::denies('banner_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return Inertia::render('Admin/Banner/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string',
            'description' => 'required',
            'type'        => 'required',
            'product_id'  => 'required_without:category_id|nullable',
            'category_id' => 'required_without:product_id|nullable',
        ], [
            'product_id.required_without'  => 'The product field is required.',
            'category_id.required_without' => 'The category field is required.',
        ]);

        DB::beginTransaction();

        try {
            $banner = Banner::create([
                'title'       => $request->title,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'product_id'  => $request->product_id,
                'banner_type'        => $request->banner_type,
                'status'      => $request->status,
            ]);


            $this->storeTranslate($request, $banner);


            if ($request->input('upload')) {
                $file = $request->input('upload');
                if (Storage::disk('tmp')->exists('uploads/' . $file[0])) {
                    $banner->addMedia(storage_path('tmp/uploads/' . basename($file[0])))->toMediaCollection('images');
                }
            }


            DB::commit();

            return to_route('admin.banners.index');
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function edit($id)
    {
        abort_if(Gate::denies('banner_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $banner = Banner::with(['product', 'category'])->findOrFail($id);

        return Inertia::render('Admin/Banner/Edit', [
            'banner' => new BannerResource($banner),

        ]);
    }

    public function show($id)
    {
        abort_if(Gate::denies('banner_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $banner = Banner::with(['product', 'category'])->findOrFail($id);
        return Inertia::render('Admin/Banner/Show', [
            'banner' => new BannerResource($banner),
        ]);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'title'       => 'required|string',
            'description' => 'required',
            'type'        => 'required',
            'product_id'  => 'required_without:category_id',
            'category_id' => 'required_without:product_id',
        ], [
            'product_id.required_without'  => 'The product field is required.',
            'category_id.required_without' => 'The category field is required.',
        ]);
        try {
            $banner = Banner::findOrFail($id);
            $banner->update([
                'title'       => $request->title,
                'description' => $request->description,
                'banner_type'        => $request->banner_type,
                'category_id' => $request->type == 'category' ? $request->category_id : null,
                'product_id'  => $request->type == 'product' ? $request->product_id : null,
                'status'      => $request->status,
            ]);

            $this->storeTranslate($request, $banner);


            if ($request->input('upload')) {
                $file = $request->input('upload');
                if (Storage::disk('tmp')->exists('uploads/' . $file[0])) {
                    $banner->addMedia(storage_path('tmp/uploads/' . basename($file[0])))->toMediaCollection('images');
                }
            }


            DB::commit();

            return to_route('admin.banners.edit', $id);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('banner_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $banner = Banner::findOrFail($id);
        $banner->delete();
    }
}
