<?php

namespace App\Http\Controllers\Admin;

use App\Class\Tag\TagQuery;
use App\Models\Tag;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Resources\TagResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Traits\SaveTranslateTrait;
use Symfony\Component\HttpFoundation\Response;

class TagController extends Controller
{
    use SaveTranslateTrait;
    //
    public function index(Request $request)
    {
        abort_if(Gate::denies('tag_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tags = new TagQuery($request);
        $tags = $tags->withSearchByName()->paginate(10);



        return Inertia::render('Admin/Tag/Index', [
            'tags' => $tags,
        ]);
    }
    public function create()
    {
        abort_if(Gate::denies('tag_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return Inertia::render('Admin/Tag/Create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ],);

        $tag = Tag::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        $this->storeTranslate($request, $tag);
        return to_route('admin.tags.index');
    }
    public function edit($id)
    {
        abort_if(Gate::denies('tag_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tag = Tag::with(['translates:id,model_id,key,value,language_id'])->findOrFail($id);

        return Inertia::render('Admin/Tag/Edit', [
            'tag' => new TagResource($tag),
        ],);
    }
    public function show($id)
    {
        abort_if(Gate::denies('tag_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $tag = Tag::with(['translates:id,model_id,key,value,language_id'])->findOrFail($id);

        return Inertia::render('Admin/Tag/Show', [
            'tag' => new TagResource($tag),
        ]);
    }
    public function update($id, Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $tag = Tag::findOrFail($id);

        $tag->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        $this->storeTranslate($request, $tag);


        return to_route('admin.tags.edit', $id);
    }
    public function destroy($id)
    {
        abort_if(Gate::denies('tag_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $tag = Tag::findOrFail($id);
        $tag->delete();
    }
}
