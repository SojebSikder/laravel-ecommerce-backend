@extends('backend.master')

@section('title')
    Edit variant
@endsection

@section('style')
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
                    Variants
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="float-start">Edit variant</h5>
                                <a href="{{ route('product.edit', $variant->product_id) }}"
                                    class="btn btn-sm btn-primary float-end mr-4 mt-3">
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
                                    <- Back </a>

                            </div>
                            <div class="card-body">
                                <form action="{{ route('variant.update', $variant->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col">

                                            <div class="d-flex form-group justify-content-end mb-3">
                                                <button id="submit" type="submit" class="btn btn-primary me-1 mt-3"
                                                    name="save">Save</button>
                                                <button id="submit" value="1" type="submit"
                                                    class="btn btn-primary mt-3" name="save_continue">Save
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



                                                <div class="col mb-4">
                                                    <div class="form-group">
                                                        <a href="{{ route('variant_attribute_create', $variant->id) }}"
                                                            class="btn btn-primary me-1 mt-3">Add attribute</a>

                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div>
                                                        <table class="table-bordered table-hover table-striped table"
                                                            style="width: 100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th>Attribute</th>
                                                                    <th>Attribute value</th>

                                                                    <th class="text-center">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if (count($variant->variant_attributes) > 0)
                                                                    @foreach ($variant->variant_attributes as $variant_attribute)
                                                                        <tr>
                                                                            <td>{{ $variant_attribute->attribute->name }}
                                                                            </td>
                                                                            <td>{{ $variant_attribute->attribute_value->name }}
                                                                            </td>

                                                                            <td class="text-center">
                                                                                <ul class="table-controls">
                                                                                    <li>
                                                                                        <a class="btn btn-sm btn-primary"
                                                                                            href="{{ route('variant_attribute.edit', $variant_attribute->id) }}"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-placement="top"
                                                                                            title=""
                                                                                            data-bs-title="Edit">
                                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                width="24"
                                                                                                height="24"
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
                                                                                            {{-- onclick="event.preventDefault();
                                                                                            if(confirm('Are you really want to delete?')){
                                                                                            document.getElementById('variant-attribute-delete-{{ $variant_attribute->id }}').submit() }" --}}
                                                                                            onclick="deleteAttribute({{ $variant_attribute->id }})"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-placement="top"
                                                                                            title=""
                                                                                            data-bs-title="Delete">
                                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                width="24"
                                                                                                height="24"
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
                                                                                        {{-- <form method="post"
                                                                                            action="{{ route('variant_attribute.destroy', $variant->id) }}"
                                                                                            id="{{ 'variant-attribute-delete-' . $variant->id }}">
                                                                                            @csrf
                                                                                            @method('DELETE')
                                                                                        </form> --}}

                                                                                    </li>
                                                                                </ul>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif

                                                            </tbody>
                                                        </table>
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
                                                            value="{{ $variant->price }}" name="price"
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
                                                            value="{{ $variant->cost_per_item }}" name="cost_per_item"
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
                                                            value="{{ $variant->discount }}" name="discount"
                                                            placeholder='e.g. 10'>
                                                    </div>
                                                    @error('discount')
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-check form-switch mt-2">
                                                    <input class="form-check-input"
                                                    @if($variant->is_sale) checked @endif value="1" name="is_sale"
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
                                                                value="{{ $variant->weight }}" name="weight">
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
                                                                    <option @if($variant->weight_unit == $key) selected @endif value={{ $key }}>{{ $value }}
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
                                                            value="{{ $variant->sku }}" name="sku">
                                                    </div>
                                                    @error('sku')
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-check form-switch mt-2">
                                                    <input class="form-check-input"
                                                    @if($variant->track_quantity) checked @endif value="1" name="track_quantity"
                                                        type="checkbox" role="switch" id="track_quantity">
                                                    <label class="form-check-label" for="track_quantity">Track
                                                        quantity</label>
                                                </div>
                                                <div class="col mt-4">
                                                    <div class="form-group mb-3">
                                                        <label for="quantity">Stock quantity</label>
                                                        <input type="number" id="quantity"
                                                            class="form-control @error('quantity') is-invalid @enderror"
                                                            value="{{ $variant->quantity }}" name="quantity"
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
                                                                    <th class="text-center">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($variantImages as $variantImage)
                                                                    <tr>
                                                                        <td>
                                                                            <a href="{{ $variantImage->image_url }}"
                                                                                target="_blank" rel="noopener noreferrer">
                                                                                <img width="150" class="img-thumbnail"
                                                                                    src="{{ $variantImage->image_url }}"
                                                                                    alt="{{ $variantImage->alt_text }}"
                                                                                    title="{{ $variantImage->title }}">
                                                                            </a>
                                                                        </td>
                                                                        <td>{{ $variantImage->sort_order }}</td>
                                                                        <td>{{ $variantImage->alt_text }}</td>
                                                                        <td>{{ $variantImage->title }}</td>

                                                                        <td class="text-center">
                                                                            <ul class="table-controls">
                                                                                <li>
                                                                                    <a class="btn btn-sm btn-primary"
                                                                                        href="javascript:void(0);"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#settingEdit{{ $variantImage->id }}"
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
                                                                                        {{-- onclick="event.preventDefault();
                                                                                        if(confirm('Are you really want to delete?')){
                                                                                        document.getElementById('product-delete-{{ $variantImage->id }}').submit() }" --}}
                                                                                        onclick="deleteImage({{ $variantImage->id }})"
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


                                                                                </li>
                                                                            </ul>
                                                                        </td>
                                                                        {{-- modal --}}
                                                                        <div class="modal fade"
                                                                            id="settingEdit{{ $variantImage->id }}"
                                                                            tabindex="-1" role="dialog"
                                                                            aria-labelledby="exampleModalCenterTitle"
                                                                            aria-hidden="true">
                                                                            <div class="modal-dialog modal-dialog-centered"
                                                                                role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title"
                                                                                            id="exampleModalLongTitle">
                                                                                            Info</h5>
                                                                                        <button type="button"
                                                                                            class="btn-close"
                                                                                            data-bs-dismiss="modal"
                                                                                            aria-label="Close"></button>
                                                                                    </div>
                                                                                    <div class="modal-body">


                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                for="sort_order{{ $variantImage->id }}">Display
                                                                                                order</label>
                                                                                            <input type="number"
                                                                                                class="form-control"
                                                                                                id="sort_order{{ $variantImage->id }}"
                                                                                                name="title"
                                                                                                value="{{ $variantImage->sort_order }}">
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                for="title{{ $variantImage->id }}">Title</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                id="title{{ $variantImage->id }}"
                                                                                                name="title"
                                                                                                value="{{ $variantImage->title }}">
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                for="alt_text{{ $variantImage->id }}">Alt</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                id="alt_text{{ $variantImage->id }}"
                                                                                                name="alt_text"
                                                                                                value="{{ $variantImage->alt_text }}">
                                                                                        </div>

                                                                                        <button
                                                                                            onclick="updateImageDetails(
                                                                                                {{ $variantImage->id }},
                                                                                              document.querySelector('#sort_order{{ $variantImage->id }}').value, 
                                                                                              document.querySelector('#alt_text{{ $variantImage->id }}').value, 
                                                                                              document.querySelector('#title{{ $variantImage->id }}').value                                                                                                
                                                                                            )"
                                                                                            type="button"
                                                                                            class="btn btn-sm btn-primary float-right">Update</button>

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        {{-- // modal --}}
                                                                    </tr>
                                                                @endforeach

                                                            </tbody>


                                                        </table>
                                                        {{ $variantImages->appends(request()->query())->links('pagination::bootstrap-5') }}
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
    <script>
        // delete attribute
        async function deleteAttribute(id) {
            try {
                if (!confirm('Are you sure?')) {
                    return;
                }
                const response = await fetch(
                    `/variant/variant_attribute/${id}?_method=DELETE&_token={{ csrf_token() }}`, {
                        method: 'DELETE',
                    })
                window.location.reload()
            } catch (error) {
                alert("Something went wrong")
            }
        }
        // delete image
        async function deleteImage(id) {
            try {
                if (!confirm('Are you sure?')) {
                    return;
                }
                const delete_Image = await fetch(
                    `/product/variant/image/${id}/delete?_method=DELETE&_token={{ csrf_token() }}`, {
                        method: 'DELETE',
                    })
                window.location.reload()
            } catch (error) {
                alert("Something went wrong")
            }
        }
    </script>
@endsection
