<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Models\Localization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Class\Localization\LocalizatonQuery;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Pagination\LengthAwarePaginator;

class LocalizationController extends Controller
{
    //
    public function index(Request $request)
    {
        abort_if(Gate::denies('localization_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $localizations = new LocalizatonQuery($request);
        $localizations = $localizations
            ->withFilterByKey()
            ->withFilterByValue()
            ->withLanguage()
            ->latest()
            ->get()
            ->groupBy('key');


        $groupedArray = $localizations->map(function ($group, $key) {
            return [
                'key'     => $key,
                'records' => $group->toArray(),
            ];
        })->values()->toArray();


        $currentPage      = LengthAwarePaginator::resolveCurrentPage();
        $perPage          = 20;
        $paginatedGrouped = new LengthAwarePaginator(
            array_slice($groupedArray, ($currentPage - 1) * $perPage, $perPage, true),
            count($groupedArray),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()],
        );




        return Inertia::render("Admin/Localization/Index", [
            'localizations' => $paginatedGrouped,
        ]);
    }
    public function create()
    {
        abort_if(Gate::denies('localization_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return Inertia::render("Admin/Localization/Create");
    }

    public function store(Request $request)
    {
        $request->validate([
            'key'                => 'required|regex:/^[a-z]+(?:_[a-z]+)*$/',
            'translates'         => 'required|array',
            'translates.*.value' => 'required|string',
        ], [
            'translates.*.value.required' => 'The translation value is required.',
        ]);

        $translates = $request->input('translates', []);

        foreach ($translates as $key => $value) {
            $localization = Localization::create([
                'key'         => $request->key,
                'value'       => $value['value'],
                'language_id' => $value['language_id'],
            ]);
        }

        return to_route('admin.localizations.index');
    }
    public function edit($id)
    {
        abort_if(Gate::denies('localization_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $localization = Localization::with('language')->findOrFail($id);

        return Inertia::render("Admin/Localization/Edit", [
            'localization' => $localization,
        ]);
    }

    public function update($id, Request $request)
    {

        $request->validate([
            'key'                => 'required|regex:/^[a-z]+(?:_[a-z]+)*$/|',
            'translates'         => 'required|array',
            'translates.*.value' => 'required|string',

        ], [
            'translates.*.value.required' => 'The translation value is required.',
        ]);

        $translates = $request->input('translates', []);

        $data = [
            'key'         => $request->key,
            'value'       => $translates[0]['value'],
            'language_id' => $translates[0]['language_id'],
            'created_at'  => now(),
        ];

        $localization = Localization::findOrFail($id);
        $localization->update($data);

        return to_route('admin.localizations.index');
    }
    public function destroy(Request $request)
    {
        abort_if(Gate::denies('localization_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $localization = Localization::findOrFail($request);
        $localization->delete();
    }
    public function handleDelete(Request $request)
    {
        abort_if(Gate::denies('localization_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $localizations = $request->input('data');
        foreach ($localizations as $localization) {
            $data = Localization::findOrFail($localization['id']);
            $data->delete();
        };
        return to_route('admin.localizations.index');
    }

    public function handleUpdate(Request $request)
    {
        $localizations = $request->input('data');
        $request->validate([

            'data.*.value' => 'required',

        ]);

        foreach ($localizations as $localization) {
            $data = Localization::findOrFail($localization['id']);
            $data->update([
                'value' => $localization['value'],
            ]);
        };
    }
}
