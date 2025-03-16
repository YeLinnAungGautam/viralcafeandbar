<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeOption;
use Illuminate\Http\Request;

class AttributeOptionController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'attribute_id' => 'required',
            'options.*.name' => 'required',
        ]);

        $attribute = Attribute::find($request->attribute_id);

        $data = [];

        foreach ($request->options as $key => $value) {
            $data[$key] = [
                'attribute_id' => $attribute->id,
                'value' => $value['name'],
                'created_at' => now(),
            ];
        }

        AttributeOption::insert($data);

        if ($request->routeIs('admin.attribute-options.index')) {
            return to_route('admin.attribute-options.index');
        }
    }

    public function generateAttribute(Request $request)
    {
        $query = AttributeOption::whereIn('attribute_id', $request->ids);

        $data = $query->get()->toArray();

        $groupedData = collect($data)->groupBy('attribute_id');
        $attributeGroups = $groupedData->values()->toArray();
        $combinations = [];

        foreach ($this->generateCombinations($attributeGroups) as $combination) {
            $combinations[] = $combination;
        }

        return response()->json([
            'success' => true,
            'data' => $combinations,
        ]);
    }

    private function generateCombinations($groups, $currentCombination = [], $index = 0)
    {
        if ($index === count($groups)) {
            yield [
                'name' => implode('/', array_column($currentCombination, 'name')),
                'attribute_option_id' => array_column($currentCombination, 'attribute_option_id'),
                'vairations' => $this->getAttributeOptions(array_column($currentCombination, 'attribute_id')),
            ];
            return;
        }

        foreach ($groups[$index] as $item) {
            $currentCombination[$index] = [
                'name' => $item['value'],
                'attribute_option_id' => $item['id'],
                'attribute_id' => $item['attribute_id'],
            ];
            yield from $this->generateCombinations($groups, $currentCombination, $index + 1);
        }
    }

    private function getAttributeOptions($id)
    {
        $data = [];
        foreach ($id as $key => $value) {
            $data[$key] = AttributeOption::where('attribute_id', $value)->get();
        }

        return $data;
    }

    public function getSelectdAttribute(Request $request)
    {
        $attribute = AttributeOption::with('attribute')
            ->whereIn('attribute_id', $request->ids)
            ->get()
            ->groupBy('attribute.id')
            ->values();

        return response()->json([
            'success' => true,
            'data' => $attribute,
        ]);
    }
}
