<?php

namespace App\Http\Controllers\Admin;

use App\Class\Language\LanguageQuery;
use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;


class LanguageController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('language_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $languages = new LanguageQuery($request);
        $languages = $languages->withSearchByName()->paginate(10);


        return Inertia::render('Admin/Language/Index', [
            'languages' => $languages,
        ]);
    }

    public function create()
    {
        abort_if(Gate::denies('language_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return Inertia::render('Admin/Language/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:languages,name',
            'code' => 'required|unique:languages,code',
        ]);

        $data =  Language::create([
            'name' => $request->name,
            'code' => $request->code,
            'is_default' => $request->is_default,
            'active' => $request->active,
        ]);


        return to_route('admin.languages.index');
    }

    public function edit($id)
    {
        abort_if(Gate::denies('language_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $language = Language::findOrFail($id);

        return Inertia::render('Admin/Language/Edit', [
            'language' => $language,
        ]);
    }

    public function update($id, Request $request)
    {

        $request->validate([
            'name' => 'required|unique:languages,name,' . $id,
            'code' => 'required|unique:languages,code,' . $id,
        ]);

        $language = Language::findOrFail($id);

        $language->update([
            'name' => $request->name,
            'code' => $request->code,
            'is_default' => $request->is_default,
            'active' => $request->active,
        ]);


        return to_route('admin.languages.edit', $id);
    }

    public function show($id) {}

    public function destroy($id)
    {
        abort_if(Gate::denies('language_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $language = Language::findOrFail($id);
        $language->delete();
    }
}
