<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeOption;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:attributes,name',
            'options.*.name' => 'required',
        ]);

        $attribute = Attribute::create([
            'name' => $request->name,
        ]);

        $data = [];

        foreach ($request->options as $key => $value) {
            $data[$key] = [
                'attribute_id' => $attribute->id,
                'value' => $value['name'],
                'created_at' => now(),
            ];
        }

        AttributeOption::insert($data);

        if ($request->routeIs('admin.attributes.create')) {
            return to_route('admin.attributes.index');
        }

    }
}
