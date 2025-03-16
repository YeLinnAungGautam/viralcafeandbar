<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>SKU</th>
            <th>Type</th>
            <th>Product type</th>
            <th>Category</th>
            <th>Regular price</th>
            <th>Sale price</th>
            <th>Stock</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->product->name }}</td>
                <td>{{ $product->product->description }}</td>
                <td>{{ $product->code }}</td>
                <td>{{ $product->product->type }}</td>
                <td>{{ $product->product->product_type }}</td>
                <td>
                    @foreach ($product->product->categories as $index => $category)
                        <span>
                            {{ $category->name }}
                            @if ($index != 0)
                                ,
                            @endif
                        </span>
                    @endforeach
                </td>
                <td>{{ $product->original_price }}</td>
                <td>{{ $product->sale_price }}</td>
                <td>{{ $product->stock }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
