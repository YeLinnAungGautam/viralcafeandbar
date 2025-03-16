<?php

namespace App\Http\Controllers\Admin;

use App\Class\Category\CategoryQuery;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Traits\SaveTranslateTrait;
use App\Models\Category;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{

    use SaveTranslateTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $categories = new CategoryQuery($request);
        $categories = $categories->withSearchByName()->paginate(10);

        return Inertia::render('Admin/Category/Index', [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        abort_if(Gate::denies('category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return Inertia::render('Admin/Category/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);
        DB::beginTransaction();
        try {
            $category = Category::create([
                'name' => $request->name,
                'parent_id' => $request->parent,
            ]);

            $this->storeTranslate($request, $category);

            if ($request->input('upload')) {
                $file = $request->input('upload');
                if (Storage::disk('tmp')->exists('uploads/' . $file[0])) {
                    $category->addMedia(storage_path('tmp/uploads/' . basename($file[0])))->toMediaCollection('images');
                }
            }


            DB::commit();


            return to_route('admin.categories.index');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function show($id)
    {
        abort_if(Gate::denies('category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $category = Category::with('translates')->findOrFail($id);

        return Inertia::render('Admin/Category/Show', [
            'category' => new CategoryResource($category),
        ]);
    }

    public function edit($id)
    {
        abort_if(Gate::denies('category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $category = Category::with('translates')->findOrFail($id);
        return Inertia::render('Admin/Category/Edit', [
            'category' => new CategoryResource($category),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
        ]);


        DB::beginTransaction();
        try {
            $category = Category::findOrFail($id);

            $category->update([
                'name' => $request->name,
                'parent_id' => $request->parent,
            ]);

            $this->storeTranslate($request, $category);

            if ($request->input('upload')) {
                $file = $request->input('upload');

                if (Storage::disk('tmp')->exists('uploads/' . $file[0])) {

                    $category->addMedia(storage_path('tmp/uploads/' . basename($file[0])))->toMediaCollection('images');
                }
            }



            DB::commit();

            return to_route('admin.categories.edit', $id);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $category = Category::findOr($id);
        $category->delete();
    }
}
