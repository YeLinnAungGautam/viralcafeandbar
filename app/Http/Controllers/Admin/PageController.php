<?php

namespace App\Http\Controllers\Admin;

use App\Class\Page\PageQuery;
use App\Http\Resources\PageResource;
use App\Http\Traits\SaveTranslateTrait;
use App\Models\Page;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class PageController extends Controller
{
    use SaveTranslateTrait;
    //
    public function index(Request $request)
    {
        abort_if(Gate::denies('page_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pages = new PageQuery($request);
        $pages = $pages->withSearchByTitle()->paginate(10);


        return Inertia::render('Admin/Page/Index', [
            'pages' => $pages,
        ]);
    }
    public function create()
    {
        abort_if(Gate::denies('page_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return Inertia::render('Admin/Page/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'page_text' => 'required|string',
        ]);
        $page = Page::create([
            'title' => $request->title,
            'page_text' => $request->page_text,
            'excerpt' => $request->excerpt,
            'status' => $request->status,
        ]);
        $this->storeTranslate($request, $page);

        return to_route('admin.pages.index');
    }
    public function edit($id)
    {
        abort_if(Gate::denies('page_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $page = Page::with(['translates'])->findOrFail($id);


        return Inertia::render('Admin/Page/Edit', [
            'page' => new PageResource($page),
        ]);
    }

    public function show($id)
    {
        abort_if(Gate::denies('page_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $page = Page::with(['translates'])->findOrFail($id);

        return Inertia::render('Admin/Page/Show', [
            'page' => new PageResource($page),
        ]);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'page_text' => 'required|string',
        ]);
        $page = Page::findOrFail($id);
        $page->update([
            'title' => $request->title,
            'page_text' => $request->page_text,
            'excerpt' => $request->excerpt,
            'status' => $request->status,
        ]);
        $this->storeTranslate($request, $page);
        return to_route('admin.pages.edit', $id);
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('page_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $page = Page::findOrFail($id);
        $page->delete();
    }
};
