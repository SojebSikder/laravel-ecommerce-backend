@extends('backend.master')

@section('title')
    Add product
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
                    Products
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="float-start">Add product</h5>
                                <a href="{{ route('order-draft.show', $order_draft->id) }}"
                                    class="btn btn-sm btn-primary float-end mr-4 mt-3">
                                    <i class="bi bi-chevron-compact-left"></i>
                                    Done
                                </a>
                            </div>
                            <div class="card-body">
                                <div>
                                    <table class="table-bordered table-hover table-striped table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Picture</th>
                                                <th>Product</th>
                                                <th>SKU</th>
                                                <th>Price</th>
                                                <th>Stock quantity</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Actions</th>
                                                <th class="text-center">Variants</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($products as $product)
                                                <tr>
                                                    <td>
                                                        @if (count($product->images) > 0)
                                                            <a href="{{ $product->images[0]->image_url }}" target="_blank"
                                                                rel="noopener noreferrer">
                                                                <img style="width:50px; min-width: 50px;"
                                                                    class="img-thumbnail"
                                                                    src="{{ $product->images[0]->image_url }}"
                                                                    alt="{{ $product->images[0]->alt_text }}">
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>{{ $product->name }}</td>
                                                    <td>{{ $product->sku }}</td>
                                                    <td>{{ $product->price }}</td>
                                                    <td>{{ $product->quantity }}</td>

                                                    @if ($product->status == '1')
                                                        <td class="text-center">
                                                            <a href="{{ route('product.status', $product->id) }}"
                                                                class="badge bg-primary text-decoration-none shadow-none">
                                                                Active
                                                            </a>
                                                        </td>
                                                    @else
                                                        <td class="text-center">
                                                            <a href="{{ route('product.status', $product->id) }}"
                                                                class="badge bg-warning text-decoration-none shadow-none">
                                                                Disabled
                                                            </a>
                                                        </td>
                                                    @endif
                                                    <td class="text-center">
                                                        <ul class="table-controls">
                                                            <li>
                                                                <a class="btn btn-sm btn-primary" href="javascript:void(0);"
                                                                    onclick="event.preventDefault();
                                                                    document.getElementById('product-add-{{ $product->id }}').submit()
                                                                    "
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="" data-bs-title="Add this product">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="feather feather-plus">
                                                                        <line x1="12" y1="5" x2="12"
                                                                            y2="19"></line>
                                                                        <line x1="5" y1="12" x2="19"
                                                                            y2="12"></line>
                                                                    </svg>
                                                                    Add this
                                                                </a>

                                                                <form method="post"
                                                                    action="{{ route('order-draft.product.store', $order_draft->id) }}?order_draft_id={{ $order_draft->id }}"
                                                                    id="{{ 'product-add-' . $product->id }}">
                                                                    @csrf
                                                                    <input type="hidden" name="product_id"
                                                                        value="{{ $product->id }}">
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </td>

                                                    <td>
                                                        <fieldset>
                                                            {{-- <legend>Variants</legend> --}}
                                                            <div class="card-body">
                                                                <div>
                                                                    <table
                                                                        class="table-bordered table-hover table-striped table"
                                                                        style="width: 100%;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Picture</th>
                                                                                <th>Variant</th>
                                                                                <th>Price</th>
                                                                                <th>Quantity</th>
                                                                                <th class="text-center">Actions</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($product->variants as $variant)
                                                                                <tr>
                                                                                    <td>
                                                                                        @if (count($variant->images) > 0)
                                                                                            <a href="{{ $variant->images[0]->image_url }}"
                                                                                                target="_blank"
                                                                                                rel="noopener noreferrer">
                                                                                                <img style="width:50px; min-width: 50px;"
                                                                                                    class="img-thumbnail"
                                                                                                    src="{{ $variant->images[0]->image_url }}"
                                                                                                    alt="{{ $variant->images[0]->alt_text }}">
                                                                                            </a>
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>
                                                                                        @foreach ($variant->variant_attributes as $variant_attribute)
                                                                                            {{ $variant_attribute->attribute->name }}:
                                                                                            {{ $variant_attribute->attribute_value->name }}
                                                                                            <br>
                                                                                        @endforeach

                                                                                    </td>
                                                                                    <td>{{ $variant->price }}</td>
                                                                                    <td>{{ $variant->quantity }}</td>
                                                                                    <td class="text-center">
                                                                                        <ul class="table-controls">
                                                                                            <li>
                                                                                                <a class="btn btn-sm btn-primary"
                                                                                                    href="javascript:void(0);"
                                                                                                    onclick="event.preventDefault();
                                                                                                document.getElementById('product-variant-add-{{ $variant->id }}').submit()
                                                                                                "
                                                                                                    data-bs-toggle="tooltip"
                                                                                                    data-bs-placement="top"
                                                                                                    title=""
                                                                                                    data-bs-title="Add this product">
                                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                        width="24"
                                                                                                        height="24"
                                                                                                        viewBox="0 0 24 24"
                                                                                                        fill="none"
                                                                                                        stroke="currentColor"
                                                                                                        stroke-width="2"
                                                                                                        stroke-linecap="round"
                                                                                                        stroke-linejoin="round"
                                                                                                        class="feather feather-plus">
                                                                                                        <line
                                                                                                            x1="12"
                                                                                                            y1="5"
                                                                                                            x2="12"
                                                                                                            y2="19">
                                                                                                        </line>
                                                                                                        <line
                                                                                                            x1="5"
                                                                                                            y1="12"
                                                                                                            x2="19"
                                                                                                            y2="12">
                                                                                                        </line>
                                                                                                    </svg>
                                                                                                    Add this
                                                                                                </a>

                                                                                                <form method="post"
                                                                                                    action="{{ route('order-draft.product.store', $order_draft->id) }}?order_draft_id={{ $order_draft->id }}"
                                                                                                    id="{{ 'product-variant-add-' . $variant->id }}">
                                                                                                    @csrf
                                                                                                    <input type="hidden"
                                                                                                        name="product_id"
                                                                                                        value="{{ $product->id }}">
                                                                                                    <input type="hidden"
                                                                                                        name="variant_id"
                                                                                                        value="{{ $variant->id }}">
                                                                                                </form>
                                                                                            </li>
                                                                                        </ul>
                                                                                    </td>

                                                                                </tr>
                                                                            @endforeach

                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>

                                                        </fieldset>
                                                    </td>

                                                    <hr>
                                                </tr>
                                            @endforeach

                                        </tbody>


                                    </table>
                                    {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
                                </div>

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
@endsection
