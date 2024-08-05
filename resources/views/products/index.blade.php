@extends('layouts.main')

@section('title', 'Product Form')

@section('content')
    <div class="container mt-4">
        <form id="productForm" method="POST" action="{{ route('products.checkBoxes') }}">
            @csrf
            <div id="productContainer">
                <div class="product-field">
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="products[0][name]">Name</label>
                            <input type="text" class="form-control" name="products[0][name]" required>
                        </div>
                        <div class="form-group col-md-1">
                            <label for="products[0][length]">Length</label>
                            <input type="number" class="form-control" name="products[0][length]" required>
                        </div>
                        <div class="form-group col-md-1">
                            <label for="products[0][width]">Width</label>
                            <input type="number" class="form-control" name="products[0][width]" required>
                        </div>
                        <div class="form-group col-md-1">
                            <label for="products[0][height]">Height</label>
                            <input type="number" class="form-control" name="products[0][height]" required>
                        </div>
                        <div class="form-group col-md-1">
                            <label for="products[0][weight]">Weight</label>
                            <input type="number" class="form-control" name="products[0][weight]" required>
                        </div>
                        <div class="form-group col-md-1">
                            <label for="products[0][quantity]">Quantity</label>
                            <input type="number" class="form-control" name="products[0][quantity]" required>
                        </div>
                        <div class="form-group col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-product">-</button>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-primary" id="addProduct">Add Product</button>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>

        <div class="mt-4">
            @if (session('selectedBoxes'))

                <h2>Number of boxes used: {{ count(session('selectedBoxes')) }}</h2>

                <h3>Selected Boxes</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Box Name</th>
                            <th>Dimensions</th>
                            <th>Products</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (session('selectedBoxes') as $boxName => $productsInBox)
                            @php
                                $box = \App\Models\Box::where('name', $boxName)->first();
                            @endphp
                            <tr>
                                <td>{{ $boxName }}</td>
                                <td>{{ $box->length }}x{{ $box->width }}x{{ $box->height }}</td>
                                <td>
                                    <ul>
                                        @foreach ($productsInBox as $product)
                                            <li>
                                                Name: {{ $product['name'] }}<br>
                                                Length: {{ $product['length'] }}<br>
                                                Width: {{ $product['width'] }}<br>
                                                Height: {{ $product['height'] }}<br>
                                                Weight: {{ $product['weight'] }}<br>
                                                Quantity: {{ $product['quantity'] }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <h3>Total number of boxes per product</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Box Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (session('productsInBoxCount') as $productName => $boxCount)
                            <tr>
                                <td>{{ $productName }}</td>
                                <td>{{ $boxCount }} boxes</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if (!empty(session('unfitProducts')))
                <h3>Products that couldn't fit into any box</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (session('unfitProducts') as $product)
                            <tr>
                                <td>{{ json_encode($product) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        @if (session('error'))
            <div class="alert alert-danger mt-4">
                Error: {{ session('error') }}
            </div>
        @endif
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            let maxProducts = 10;
            let minProducts = 1;

            function updateRemoveButtons() {
                $('.remove-product').prop('disabled', $('.product-field').length <= minProducts);
            }

            $('#addProduct').click(function() {
                if ($('.product-field').length < maxProducts) {
                    let newProductField = $('.product-field:first').clone();
                    newProductField.find('input').val('');
                    let index = $('.product-field').length;
                    newProductField.find('input').each(function() {
                        let name = $(this).attr('name');
                        name = name.replace(/\d+/, index);
                        $(this).attr('name', name);
                    });
                    $('#productContainer').append(newProductField);
                    updateRemoveButtons();
                } else {
                    alert('Maximum of 10 products allowed.');
                }
            });

            $('#productContainer').on('click', '.remove-product', function() {
                if ($('.product-field').length > minProducts) {
                    $(this).closest('.product-field').remove();
                    updateRemoveButtons();
                }
            });

            updateRemoveButtons();
        });
    </script>
@endsection
