<?php

namespace App\Http\Controllers\Admin;

use App\Class\TagLine\TagLineQuery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TagLineResource;
use Illuminate\Support\Facades\Gate;
use App\Http\Traits\SaveTranslateTrait;
use App\Models\Tag;
use App\Models\TagLine;
use Exception;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;


class TagLineController extends Controller
{
    //
    use SaveTranslateTrait;
    public function index(Request $request)
    {
        abort_if(Gate::denies('tag_line_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $taglines = new TagLineQuery($request);
        $taglines = $taglines->withSearchByName()
            ->paginate(10);

        return Inertia::render('Admin/CatchLine/Index', [
            'taglines' => $taglines,
        ]);
    }
    public function create()
    {
        abort_if(Gate::denies('tag_line_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        return Inertia::render('Admin/CatchLine/Create',);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string',
            'type'        => 'required',
            'product_id'  => 'required_without:category_id|nullable',
            'category_id' => 'required_without:product_id|nullable',
        ], [
            'product_id.required_without'  => 'The product field is required.',
            'category_id.required_without' => 'The category field is required.',
        ]);

        DB::beginTransaction();

        try {
            $tagline = TagLine::create([
                'name'        => $request->name,
                'status'      => $request->status,
                'category_id' => $request->category_id,
                'product_id'  => $request->product_id,
            ]);

            $this->storeTranslate($request, $tagline);

            $files = $request->input('upload');

            if ($request->input('upload')) {
                $files = $request->input('upload');
                foreach ($files as $file => $value) {
                    if (Storage::disk('tmp')->exists('uploads/' . $value)) {
                        $tagline->addMedia(storage_path('tmp/uploads/' . basename($value)))->toMediaCollection('images');
                    }
                }
            }
            DB::commit();

            return to_route('admin.tag-lines.index');
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function edit($id)
    {
        abort_if(Gate::denies('tag_line_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tagline = TagLine::with([
            'translates:id,model_id,key,value,language_id',
            'tags',
            'product',
            'category',
        ])->findOrFail($id);

        $tags = Tag::active()->select('id', 'name')->get();

        return Inertia::render('Admin/CatchLine/Edit', [
            'tagline' => new TagLineResource($tagline),
            'tags'    => $tags,
        ]);
    }
    public function show($id)
    {
        abort_if(Gate::denies('tag_line_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tagline = TagLine::with([
            'translates:id,model_id,key,value,language_id',
            'tags',
        ])->findOrFail($id);

        return Inertia::render('Admin/CatchLine/Show', [
            'tagline' => new TagLineResource($tagline),
        ]);
    }
    public function update($id, Request $request)
    {
        $request->validate([
            'name'        => 'required|string',
            'type'        => 'required',
            'product_id'  => 'required_without:category_id|nullable',
            'category_id' => 'required_without:product_id|nullable',
        ], [
            'product_id.required_without'  => 'The product field is required.',
            'category_id.required_without' => 'The category field is required.',
        ]);
        DB::beginTransaction();


        try {
            $tagline = TagLine::findOrFail($id);

            $tagline->update([
                'name'        => $request->name,
                'status'      => $request->status,
                'category_id' => $request->type == 'category' ? $request->category_id : null,
                'product_id'  => $request->type == 'product' ? $request->product_id : null,
            ]);

            $this->storeTranslate($request, $tagline);

            if ($request->input('upload')) {
                $files = $request->input('upload');
                foreach ($files as $file) {
                    if (Storage::disk('tmp')->exists('uploads/' . $file)) {
                        $tagline->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('images');
                    }
                }
            }

            DB::commit();

            return to_route('admin.tag-lines.edit', $id);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function destroy($id)
    {
        abort_if(Gate::denies('tag_line_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tagline = TagLine::findOrFail($id);
        $tagline->delete();
    }
}
