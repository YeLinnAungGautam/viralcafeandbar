<?php

namespace App\Http\Controllers\Admin;

use App\Class\Template\TemplateQuery;
use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TemplateController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('template_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $templates = new TemplateQuery($request);
        $templates = $templates->withSearchByTitle()->paginate(10);
        return Inertia::render('Admin/Template/Index', [
            'templates' => $templates
        ]);
    }
    public function create()
    {
        abort_if(Gate::denies('template_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return Inertia::render('Admin/Template/Cretae');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:templates,title',
            'content' => 'required'
        ]);
        $template = Template::create([
            'title' => $request->title,
            'content' => $request->content,
        ]);
        return to_route('admin.templates.index');
    }

    public function edit($id)
    {
        abort_if(Gate::denies('template_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $template = Template::findOrFail($id);
        return Inertia::render('Admin/Template/Edit', [
            'template' => $template
        ]);
    }

    public function show($id)
    {
        abort_if(Gate::denies('template_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $template = Template::findOrFail($id);
        return Inertia::render('Admin/Template/Show', [
            'template' => $template
        ]);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'title' => 'required|unique:templates,title,' . $id,
            'content' => 'required'
        ]);

        $template = Template::findOrFail($id);

        $template->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return to_route('admin.templates.edit', $id);
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('template_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $template = Template::findOrFail($id);
        $template->delete();
    }
}
