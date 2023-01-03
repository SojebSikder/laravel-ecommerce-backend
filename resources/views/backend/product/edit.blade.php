@extends('backend.master')

@section('title')
    Edit product
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('assets') }}/select2/css/select2.min.css">
@endsection

@section('content')
    <!-- Main section -->
    <main class="mt-5 pt-3">
        {{-- display error message --}}
        @if (Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert"
                style="
           margin: 10px 5px 10px 5px;">
                <strong>{{ Session::get('success') }}</strong>.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif (Session::has('warning'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert"
                style="
           margin: 10px 5px 10px 5px;">
                <strong>{{ Session::get('warning') }}</strong>.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{-- //display error message --}}

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 fw-bold fs-3">
                    Products
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="float-start">Edit product</h5>
                                <a href="{{ route('product.index') }}" class="btn btn-sm btn-primary float-end mt-3 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-list">
                                        <line x1="8" y1="6" x2="21" y2="6"></line>
                                        <line x1="8" y1="12" x2="21" y2="12"></line>
                                        <line x1="8" y1="18" x2="21" y2="18"></line>
                                        <line x1="3" y1="6" x2="3.01" y2="6"></line>
                                        <line x1="3" y1="12" x2="3.01" y2="12"></line>
                                        <line x1="3" y1="18" x2="3.01" y2="18"></line>
                                    </svg>
                                    Product list
                                </a>

                            </div>
                            <div class="card-body">
                                <form action="{{ route('product.update', $product->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col">

                                            <div class="d-flex form-group justify-content-end mb-3">
                                                <button id="submit" type="submit" class="btn btn-primary me-1 mt-3"
                                                    name="save">Save</button>
                                                <button id="submit" type="submit" class="btn btn-primary mt-3"
                                                    name="save_continue">Save
                                                    and Continue Edit</button>
                                            </div>

                                            <fieldset>
                                                <legend>Product info</legend>

                                                <div class="col">
                                                    <div class="form-check form-switch mb-3 mt-5">
                                                        <input class="form-check-input"
                                                            @if ($product->status == 1) checked @endif value="1"
                                                            name="status" type="checkbox" role="switch" id="status">
                                                        <label class="form-check-label" for="status">Active</label>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="form-group mb-3">
                                                        <label for="name">Name</label>
                                                        <input type="text" id="name"
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            value="{{ $product->name }}" name="name">
                                                    </div>
                                                    @error('name')
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col">
                                                    <div class="form-group mb-3">
                                                        <label for="slug">Slug</label>
                                                        <input type="text" id="slug"
                                                            class="form-control @error('slug') is-invalid @enderror"
                                                            value="{{ $product->slug }}" name="slug">
                                                    </div>
                                                    @error('slug')
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col mb-4">
                                                    <div class="form-group mb-3">
                                                        <label for="category_id">Categories</label>
                                                        <select id="categories-select2" class="form-select mb-3"
                                                            name="category_id[]" multiple="multiple">
                                                            {{-- <option value="0">None</option> --}}
                                                            @if (count($categories) > 0)
                                                                @foreach ($categories as $category)
                                                                    <?php $dash = ''; ?>
                                                                    <option value={{ $category->id }}>
                                                                        {{ $category->name }}</option>
                                                                    @if (count($category->sub_categories))
                                                                        @include('components.subcategory', [
                                                                            'sub_categories' =>
                                                                                $category->sub_categories,
                                                                        ])
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    @error('category_id')
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col mb-4">
                                                    <div class="form-group mb-3">
                                                        <label for="manufacturer_id">Manufacturer</label>
                                                        <select id="select2" class="form-select mb-3"
                                                            name="manufacturer_id">
                                                            <option value="0">None</option>
                                                            @if (count($manufacturers) > 0)
                                                                @foreach ($manufacturers as $manufacturer)
                                                                    <?php $dash = ''; ?>
                                                                    <option value={{ $manufacturer->id }}
                                                                        @if ($product->manufacturer_id == $manufacturer->id) selected @endif>
                                                                        {{ $manufacturer->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    @error('category_id')
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>


                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="description">Description</label>
                                                        <textarea name="description" id="description">{{ $product->description }}</textarea>
                                                    </div>
                                                </div>
                                            </fieldset>

                                            <fieldset>
                                                <legend>Prices</legend>
                                                <div class="col mt-4">
                                                    <div class="form-group mb-3">
                                                        <label for="price">Price</label>
                                                        <input type="number" id="price"
                                                            class="form-control @error('price') is-invalid @enderror"
                                                            value="{{ $product->price }}" name="price"
                                                            placeholder='0.00'>
                                                    </div>
                                                    @error('price')
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col mt-4">
                                                    <div class="form-group mb-3">
                                                        <label for="cost_per_item">Cost per item</label>
                                                        <input type="number" id="cost_per_item"
                                                            class="form-control @error('cost_per_item') is-invalid @enderror"
                                                            value="{{ $product->cost_per_item }}" name="cost_per_item"
                                                            placeholder='0.00'>
                                                        <label style="font-size: 0.9rem" class="text-muted">Customer won't
                                                            see
                                                            this</label>
                                                    </div>
                                                    @error('cost_per_item')
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col mt-4">
                                                    <div class="form-group mb-3">
                                                        <label for="discount">Discount <sup>%</sup></label>
                                                        <input type="number" id="discount"
                                                            class="form-control @error('discount') is-invalid @enderror"
                                                            value="{{ $product->discount }}" name="discount"
                                                            placeholder='e.g. 10'>
                                                    </div>
                                                    @error('discount')
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-check form-switch mt-2">
                                                    <input class="form-check-input"
                                                        @if ($product->is_sale) checked @endif value="1"
                                                        name="is_sale" type="checkbox" role="switch" id="is_sale">
                                                    <label class="form-check-label"
                                                        title="Check this if you want to provide discount"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        data-bs-title="Check this if you want to provide discount"
                                                        for="is_sale">Sale?</label>
                                                </div>
                                            </fieldset>

                                            <fieldset>
                                                <legend>Shipping</legend>
                                                <div class="d-flex">
                                                    <div class="col me-1">
                                                        <div class="form-group mb-3">
                                                            <label for="weight">Weight</label>
                                                            <input type="number" id="weight"
                                                                class="form-control @error('weight') is-invalid @enderror"
                                                                value="{{ $product->weight }}" name="weight">
                                                        </div>
                                                        @error('weight')
                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group mb-3">
                                                            <label for="weight_unit">Weight unit</label>
                                                            <?php
                                                            $weight_units = [
                                                                'kg' => 'kg',
                                                                'lb' => 'lb',
                                                                'oz' => 'oz',
                                                                'g' => 'g',
                                                            ];
                                                            ?>
                                                            <select
                                                                class="form-control @error('weight_unit') is-invalid @enderror"
                                                                name="weight_unit" id="weight_unit">
                                                                @foreach ($weight_units as $key => $value)
                                                                    <option value={{ $key }}>{{ $value }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @error('weight_unit')
                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>


                                            </fieldset>

                                            <fieldset>
                                                <legend>Inventory</legend>
                                                <div class="col mt-4">
                                                    <div class="form-group mb-3">
                                                        <label for="sku">SKU (Stock Keeping Unit)</label>
                                                        <input type="text" id="sku"
                                                            class="form-control @error('sku') is-invalid @enderror"
                                                            value="{{ $product->sku }}" name="sku">
                                                    </div>
                                                    @error('sku')
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-check form-switch mt-2">
                                                    <input class="form-check-input"
                                                        @if ($product->track_quantity) checked @endif value="1"
                                                        name="track_quantity" type="checkbox" role="switch"
                                                        id="track_quantity">
                                                    <label class="form-check-label" for="track_quantity">Track
                                                        quantity</label>
                                                </div>
                                                <div class="col mt-4">
                                                    <div class="form-group mb-3">
                                                        <label for="quantity">Stock quantity</label>
                                                        <input type="number" id="quantity"
                                                            class="form-control @error('quantity') is-invalid @enderror"
                                                            value="{{ $product->quantity }}" name="quantity"
                                                            placeholder='1000'>
                                                    </div>
                                                    @error('quantity')
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </fieldset>


                                            <fieldset>
                                                <legend>Multimedia</legend>

                                                {{-- image upload --}}
                                                <div class="col mb-4">
                                                    <div class="form-group">
                                                        <label for="name">Picture</label>
                                                        <label class="btn btn-success">
                                                            Upload files <input type="file" accept="image/*" multiple
                                                                name="image[]" id="uploadImage" class="d-none">
                                                        </label>

                                                        @error('image')
                                                            <div class="alert alert-danger mt-1">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div>
                                                        <table class="table-bordered table-hover table-striped table"
                                                            style="width: 100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th>Picture</th>
                                                                    <th>Display order</th>
                                                                    <th>Alt</th>
                                                                    <th>Title</th>
                                                                    <th class="text-center">Status</th>
                                                                    <th class="text-center">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($productImages as $productImage)
                                                                    <tr>
                                                                        <td>{{ $productImage->image }}</td>
                                                                        <td>{{ $productImage->sort_order }}</td>
                                                                        <td>{{ $productImage->sku }}</td>
                                                                        <td>{{ $productImage->price }}</td>
                                                                        <td>{{ $productImage->quantity }}</td>

                                                                        @if ($productImage->status == '1')
                                                                            <td class="text-center">
                                                                                <a href="{{ route('product.status', $productImage->id) }}"
                                                                                    class="badge bg-primary text-decoration-none shadow-none">
                                                                                    Active
                                                                                </a>
                                                                            </td>
                                                                        @else
                                                                            <td class="text-center">
                                                                                <a href="{{ route('product.status', $productImage->id) }}"
                                                                                    class="badge bg-warning text-decoration-none shadow-none">
                                                                                    Disabled
                                                                                </a>
                                                                            </td>
                                                                        @endif
                                                                        <td class="text-center">
                                                                            <ul class="table-controls">
                                                                                <li>
                                                                                    <a class="btn btn-sm btn-primary"
                                                                                        href="{{ route('product.edit', $productImage->id) }}"
                                                                                        data-bs-toggle="tooltip"
                                                                                        data-bs-placement="top"
                                                                                        title=""
                                                                                        data-bs-title="Edit">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                                            width="24" height="24"
                                                                                            viewBox="0 0 24 24"
                                                                                            fill="none"
                                                                                            stroke="currentColor"
                                                                                            stroke-width="2"
                                                                                            stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            class="feather feather-edit-2 br-6 mb-1 p-1">
                                                                                            <path
                                                                                                d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                                                            </path>
                                                                                        </svg>
                                                                                        Edit
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a class="btn btn-sm btn-danger"
                                                                                        href="javascript:void(0);"
                                                                                        onclick="event.preventDefault();
                                                                                        if(confirm('Are you really want to delete?')){
                                                                                        document.getElementById('product-delete-{{ $productImage->id }}').submit()
                                                                                        }"
                                                                                        data-bs-toggle="tooltip"
                                                                                        data-bs-placement="top"
                                                                                        title=""
                                                                                        data-bs-title="Delete">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                                            width="24" height="24"
                                                                                            viewBox="0 0 24 24"
                                                                                            fill="none"
                                                                                            stroke="currentColor"
                                                                                            stroke-width="2"
                                                                                            stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            class="feather feather-trash br-6 mb-1 p-1">
                                                                                            <polyline
                                                                                                points="3 6 5 6 21 6">
                                                                                            </polyline>
                                                                                            <path
                                                                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                                                            </path>
                                                                                        </svg>
                                                                                        Delete
                                                                                    </a>
                                                                                    {{-- delete  --}}
                                                                                    <form method="post"
                                                                                        action="{{ route('product.destroy', $productImage->id) }}"
                                                                                        id="{{ 'product-delete-' . $productImage->id }}">
                                                                                        @csrf
                                                                                        @method('DELETE')
                                                                                    </form>
                                                                                </li>
                                                                            </ul>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach

                                                            </tbody>


                                                        </table>
                                                        {{ $productImages->appends(request()->query())->links('pagination::bootstrap-5') }}
                                                    </div>
                                                </div>

                                            </fieldset>

                                            <fieldset>
                                                <legend>SEO tags</legend>
                                                <div class="col">
                                                    <div class="col">
                                                        <div class="form-group mb-3">
                                                            <label for="meta_title">Meta Title</label>
                                                            <input type="text"
                                                                class="form-control @error('meta_title') is-invalid @enderror"
                                                                value="{{ $product->meta_title }}" name="meta_title"
                                                                placeholder='Title'>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="meta_description">Meta Description</label>
                                                            <textarea name="meta_description" placeholder="Description"
                                                                class="form-control @error('meta_description') is-invalid @enderror" cols="10" rows="5">{{ $product->meta_description }}</textarea>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="meta_keyword">Meta Keywords</label>
                                                            <input type="text"
                                                                class="form-control @error('meta_keyword') is-invalid @enderror"
                                                                value="{{ $product->meta_keyword }}" name="meta_keyword"
                                                                placeholder='Keywords'>
                                                        </div>

                                                    </div>

                                                </div>
                                            </fieldset>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
    <!-- End Main section -->
@endsection

@section('script')
    <script src="{{ asset('assets') }}/tinymce/tinymce.min.js"></script>
    <script src="{{ asset('assets') }}/select2/js/select2.min.js"></script>
    <script>
        // categoris
        $(document).ready(function() {
            const categoriesSelect2 = $("#categories-select2");
            const selectedValues = <?php echo json_encode($product->categories); ?>;
            const newSelectedValues = selectedValues.map((selectedValue) => selectedValue.id)

            categoriesSelect2.select2({

                placeholder: "Select categories",
                allowClear: true,
                tags: true
            });

            categoriesSelect2.val(newSelectedValues).trigger('change');
        });

        $(document).ready(function() {
            $("#select2").select2();
        });
    </script>

    <script>
        // tinymce
        tinymce.init({
            selector: '#description',
        });
    </script>
    <script>
        const name = document.querySelector('#name');
        const slug = document.querySelector('#slug');
        name.addEventListener("keyup", function(e) {
            slug.value = replace(e.target.value.toLowerCase(), " ", "-")
        });
    </script>
@endsection
