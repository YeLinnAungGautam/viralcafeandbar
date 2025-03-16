<?php

namespace App\Http\Controllers\Admin;

use App\Class\Promotion\PromotionQuery;
use Exception;
use App\Models\Tag;
use Inertia\Inertia;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Traits\SaveTranslateTrait;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PromotionResource;
use Symfony\Component\HttpFoundation\Response;

class PromotionController extends Controller
{
    //
    use SaveTranslateTrait;
    public function index(Request $request)
    {
        abort_if(Gate::denies('promotion_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $promotions = new PromotionQuery($request);
        $promotions = $promotions->withSearchByName()
            ->withTags()
            ->paginate(10);

        return Inertia::render('Admin/Notification/Index', [
            'promotions' => $promotions,
        ]);
    }
    public function create()
    {
        abort_if(Gate::denies('promotion_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tags = Tag::active()->select('id', 'name')->get();

        return Inertia::render('Admin/Notification/Create', [
            'tags' => $tags,
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string',
            'description'       => 'required',
            'type'              => 'required',
            'short_description' => 'required',
            'tags'              => 'required|array',
            'product_id'        => 'required_without:category_id|nullable',
            'category_id'       => 'required_without:product_id|nullable',
        ], [
            'product_id.required_without'  => 'The product field is required.',
            'category_id.required_without' => 'The category field is required.',
        ]);

        DB::beginTransaction();

        try {
            $promotion = Promotion::create([
                'name'              => $request->name,
                'description'       => $request->description,
                'short_description' => $request->short_description,
                'status'            => $request->status,
                'category_id'       => $request->category_id,
                'product_id'        => $request->product_id,
            ]);

            $tags = $request->input('tags', []);

            $promotion->tags()->sync($tags);

            $this->storeTranslate($request, $promotion);

            $files = $request->input('upload');

            if ($request->input('upload')) {
                $files = $request->input('upload');
                foreach ($files as $file => $value) {
                    if (Storage::disk('tmp')->exists('uploads/' . $value)) {
                        $promotion->addMedia(storage_path('tmp/uploads/' . basename($value)))->toMediaCollection('images');
                    }
                }
            }
            DB::commit();

            return to_route('admin.notifications.index');
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function edit($id)
    {
        abort_if(Gate::denies('promotion_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $promotion = Promotion::with([
            'translates',
            'tags',
            'product',
            'category',
        ])->findOrFail($id);

        $tags = Tag::active()->select('id', 'name')->get();

        return Inertia::render('Admin/Notification/Edit', [
            'promotion' => new PromotionResource($promotion),
            'tags'      => $tags,
        ]);
    }
    public function show($id)
    {
        abort_if(Gate::denies('promotion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $promotion = Promotion::with([
            'translates',
            'tags',
            'product',
            'category'
        ])->findOrFail($id);

        return Inertia::render('Admin/Notification/Show', [
            'promotion' => new PromotionResource($promotion),
        ]);
    }
    public function update($id, Request $request)
    {
        $request->validate([
            'name'              => 'required|string',
            'description'       => 'required',
            'short_description' => 'required',
            'type'              => 'required',
            'tags'              => 'required|array',
            'product_id'        => 'required_without:category_id|nullable',
            'category_id'       => 'required_without:product_id|nullable',
        ], [
            'product_id.required_without'  => 'The product field is required.',
            'category_id.required_without' => 'The category field is required.',
        ]);
        DB::beginTransaction();


        try {
            $promotion = Promotion::findOrFail($id);

            $promotion->update([
                'name'        => $request->name,
                'description' => $request->description,
                'status'      => $request->status,
                'short_description' => $request->short_description,
                'category_id' => $request->category_id,
                'product_id'  => $request->product_id,
            ]);

            $tags = $request->input('tags', []);

            $promotion->tags()->sync($tags);

            $this->storeTranslate($request, $promotion);

            if ($request->input('upload')) {
                $files = $request->input('upload');
                foreach ($files as $file) {
                    if (Storage::disk('tmp')->exists('uploads/' . $file)) {
                        $promotion->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('images');
                    }
                }
            }

            DB::commit();

            return to_route('admin.notifications.edit', $id);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function destroy($id)
    {
        abort_if(Gate::denies('promotion_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $promotion = Promotion::findOrFail($id);
        $promotion->delete();
    }
}
