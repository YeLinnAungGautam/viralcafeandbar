<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\ProductsImport;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException as ValidationValidationException;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function index(Request $request, $slug)
    {
        return Inertia::render('Admin/Excel/Index', [
            'title' => $slug,
        ]);
    }

    public function store(Request $request, $name)
    {
        $request->validate([
            'uploaded' => 'required|mimes:xlsx',
        ], [
            'uploaded.required' => "The excel file is required."
        ]);
        if ($request->images) {
            $this->imageUpload($request);
        }
        $importClass = new ProductsImport();
        $valid =   $importClass->validateExcelHeaders($request->uploaded);

        if ($valid['status']) {
            switch ($name) {
                case 'products':
                    Excel::import(new ProductsImport(), $request->uploaded);
                    break;
            }
        } else {
            throw ValidationValidationException::withMessages([$valid['message']]);
        }
    }
    public function imageUpload($request)
    {
        $path = storage_path('tmp/excels');

        foreach ($request->images as $file) {
            $name = $file->getClientOriginalName();
            $file->move($path, $name);
        }
    }
}
