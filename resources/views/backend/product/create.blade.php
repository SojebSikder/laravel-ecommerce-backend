@extends('backend.master')

@section('title')
    Create product
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
                                <h5 class="float-start">Create product</h5>
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
                                <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col">

                                            <div class="d-flex form-group justify-content-end mb-3">
                                                <button id="submit" type="submit" class="btn btn-primary me-1 mt-3"
                                                    name="save">Save</button>
                                                <button id="submit" value="1" type="submit" class="btn btn-primary mt-3"
                                                    name="save_continue">Save
                                                    and Continue Edit</button>
                                            </div>

                                            <fieldset>
                                                <legend>Product info</legend>

                                                <div class="col">
                                                    <div class="form-check form-switch mb-3 mt-5">
                                                        <input class="form-check-input" checked value="1"
                                                            name="status" type="checkbox" role="switch" id="status">
                                                        <label class="form-check-label" for="status">Active</label>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="form-group mb-3">
                                                        <label for="name">Name</label>
                                                        <input type="text" id="name"
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            value="{{ old('name') }}" name="name">
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
                                                            value="{{ old('slug') }}" name="slug">
                                                    </div>
                                                    @error('slug')
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col mb-4">
                                                    <div class="form-group mb-3">
                                                        <label for="category_id">Categories</label>
                                                        <select class="tagsselect2 form-select mb-3" name="category_id[]"
                                                            multiple="multiple">
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
                                                        <label for="option_set_id">Option sets</label>
                                                        <select id="option_set-select2" class="form-select mb-3"
                                                            name="option_set_id[]" multiple="multiple">
                                                            {{-- <option value="0">None</option> --}}
                                                            @if (count($option_sets) > 0)
                                                                @foreach ($option_sets as $option_set)
                                                                    <?php $dash = ''; ?>
                                                                    <option value={{ $option_set->id }}>
                                                                        {{ $option_set->name }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    @error('option_set_id')
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
                                                                    <option value={{ $manufacturer->id }}>
                                                                        {{ $manufacturer->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    @error('manufacturer_id')
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col mb-4">
                                                    <div class="form-group mb-3">
                                                        <label for="tags-select2">Tags</label>
                                                        <select id="tags-select2" class="form-select mb-3"
                                                            name="tags[]" multiple="multiple">
                                                            {{-- <option value="0">None</option> --}}
                                                            @if (count($tags) > 0)
                                                                @foreach ($tags as $tag)
                                                                    <?php $dash = ''; ?>
                                                                    <option value={{ $tag->name }}>
                                                                        {{ $tag->name }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    @error('tags')
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="description">Description</label>
                                                        <textarea name="description" id="description"></textarea>
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
                                                            value="{{ old('price') }}" name="price"
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
                                                            value="{{ old('cost_per_item') }}" name="cost_per_item"
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
                                                            value="{{ old('discount') }}" name="discount"
                                                            placeholder='e.g. 10'>
                                                    </div>
                                                    @error('discount')
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col mt-4">
                                                    <div class="form-group mb-3">
                                                        <label for="old_discount">Old Discount <sup>%</sup></label>
                                                        <input type="number" id="old_discount"
                                                            class="form-control @error('old_discount') is-invalid @enderror"
                                                            value="{{ old('old_discount') }}" name="old_discount"
                                                            placeholder='e.g. 10'>
                                                    </div>
                                                    @error('old_discount')
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-check form-switch mt-2">
                                                    <input class="form-check-input" value="1" name="is_sale"
                                                        type="checkbox" role="switch" id="is_sale">
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
                                                            <input type="text" id="weight"
                                                                class="form-control @error('weight') is-invalid @enderror"
                                                                value="{{ old('weight') }}" name="weight">
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
                                                            value="{{ old('sku') }}" name="sku">
                                                    </div>
                                                    @error('sku')
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-check form-switch mt-2">
                                                    <input class="form-check-input" value="1" name="track_quantity"
                                                        type="checkbox" role="switch" id="track_quantity">
                                                    <label class="form-check-label" for="track_quantity">Track
                                                        quantity</label>
                                                </div>
                                                <div class="col mt-4">
                                                    <div class="form-group mb-3">
                                                        <label for="quantity">Stock quantity</label>
                                                        <input type="number" id="quantity"
                                                            class="form-control @error('quantity') is-invalid @enderror"
                                                            value="{{ old('quantity') }}" name="quantity"
                                                            placeholder='1000'>
                                                    </div>
                                                    @error('quantity')
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
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
                                                                value="{{ old('meta_title') }}" name="meta_title"
                                                                placeholder='Title'>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="meta_description">Meta Description</label>
                                                            <textarea name="meta_description" placeholder="Description"
                                                                class="form-control @error('meta_description') is-invalid @enderror" cols="10" rows="5">{{ old('meta_description') }}</textarea>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="meta_keyword">Meta Keywords</label>
                                                            <input type="text"
                                                                class="form-control @error('meta_keyword') is-invalid @enderror"
                                                                value="{{ old('meta_keyword') }}" name="meta_keyword"
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
        // tags
        $(document).ready(function() {
            $("#tags-select2").select2({
                placeholder: "Find or create tags",
                allowClear: true,
                tags: true
            });
        });
        // select2
        $(document).ready(function() {
            $(".tagsselect2").select2({
                placeholder: "Select categories",
                allowClear: true,
                tags: true
            });
        });

        $(document).ready(function() {
            $("#option_set-select2").select2({
                placeholder: "Select option sets",
                allowClear: true,
                tags: true
            });
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
