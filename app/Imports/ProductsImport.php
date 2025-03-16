<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Language;
use App\Models\Product;
use App\Models\Sku;
use App\Models\Translate;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\HeadingRowImport;
use Maatwebsite\Excel\Validators\Failure;

class ProductsImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, SkipsEmptyRows, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected $expectedHeaders = [
        'name',
        'description',
        'sku',
        'type',
        'category',
        'regular_price',
        'sale_price',
        'stock'
    ];
    public function model(array $row)
    {
        DB::beginTransaction();
        try {



            $product = $this->_productStore($row);

            $this->_skuStore($product, $row);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function _productStore($row)
    {
        $product = Product::updateOrCreate([
            'name'           => $row['name'],
            'description'    => $row['description'],
            'type'           => $row['type'] ? strtolower($row['type']) : 'simple',
            'product_status' => isset($row['product_status']) ? strtolower($row['product_status']) : null,
        ]);

        if (isset($row['upload'])) {
            $images = preg_replace('/\s*,\s*/', ',', $row['upload']);
            $images = explode(",", $images);
            foreach ($images as $image) {
                $product->addMedia(storage_path('tmp/excels/' . $image))->toMediaCollection('images');
            }
        }

        Translate::insert([
            [
                'key'         => 'name',
                'value'       => $product->name,
                'model_id'    => $product->id,
                'model_type'  => get_class($product),
                'language_id' => Language::default()->value('id'),
            ],
            [
                'key'         => 'description',
                'value'       => $product->description,
                'model_id'    => $product->id,
                'model_type'  => get_class($product),
                'language_id' => Language::default()->value('id'),
            ],
        ]);

        $category = Category::updateOrCreate([
            'name' => $row['category'],
        ]);

        Translate::insert([
            'key'         => 'name',
            'value'       => $category->name,
            'model_id'    => $category->id,
            'model_type'  => get_class($category),
            'language_id' => Language::default()->value('id'),
        ]);

        $product->categories()->sync($category->id);

        return $product;
    }

    public function _skuStore(Product $product, $row)
    {
        $data = [];

        $data[] = [
            'code'           => isset($row['sku']) ? $row['sku'] : null,
            'stock'          => $row['stock'],
            'original_price' => $row['regular_price'],
            'sale_price'     => $row['sale_price'],
            'product_id'     => $product->id,
            'created_at'     => now(),
            'updated_at'     => now(),
        ];

        Sku::insert($data);
    }


    public function headings(): array
    {
        return ['name', 'description', 'sku', 'type', 'product_type', 'category', 'regular_price', 'sale_price', 'stock', 'upload'];
    }



    public function rules(): array
    {

        return [
            '*.name'          => 'required',
            '*.sku'           => 'nullable|unique:skus,code',
            '*.category'      => 'required',
            '*.regular_price' => 'required|numeric',
            '*.sale_price'    => 'nullable|numeric|lt:*.regular_price',
            '*.stock'         => 'required|numeric',
        ];
    }

    public function onFailure(Failure ...$failures) {}

    public function batchSize(): int
    {
        return 10000;
    }

    public function chunkSize(): int
    {
        return 10000;
    }
    function removeLastComma($string)
    {
        $string = preg_replace('/,?\s*$/', '', $string);


        return $string; // Output: "hello, world"
    }

    public function validateExcelHeaders($filePath)
    {
        $actualHeaders = (new HeadingRowImport)->toArray($filePath);

        if (!empty($actualHeaders) && isset($actualHeaders[0][0])) {
            $actualHeaders = $actualHeaders[0][0];

            return   $this->validateHeaders($actualHeaders);
        }
        return false;
    }

    private function validateHeaders(array $actualHeaders)
    {
        $missingHeaders = array_diff($this->expectedHeaders, $actualHeaders);
        if (empty($missingHeaders)) {
            return [
                'status' => true,

            ];;
        }
        $missingHeaders = implode(', ', $missingHeaders);

        return [
            'status' => false,
            'message' => "Missing headers {$missingHeaders}"
        ];
    }
}
