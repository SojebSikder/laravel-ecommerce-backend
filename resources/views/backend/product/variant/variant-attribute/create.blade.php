@extends('backend.master')

@section('title')
    Create variant attribute
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
                    Variant Attribute
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="float-start">Create variant attribute</h5>
                                <a href="{{ route('variant.edit', $variant_id) }}" class="btn btn-sm btn-primary float-end mr-4 mt-3">
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
                                   <- Back
                                </a>

                            </div>
                            <div class="card-body">
                                <form action="{{ route('variant_attribute.store') }}?variant_id={{$variant_id}}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col">

                                            <div class="d-flex form-group justify-content-end mb-3">
                                                <button id="submit" type="submit"
                                                    class="btn btn-primary me-1 mt-3">Save</button>
                                            </div>

                                            <div class="col">
                                                <div class="form-check form-switch mb-3 mt-5">
                                                    <input class="form-check-input" checked value="1" name="status"
                                                        type="checkbox" role="switch" id="status">
                                                    <label class="form-check-label" for="status">Active</label>
                                                </div>
                                            </div>

                                            <div class="col mb-4">
                                                <div class="form-group mb-3">
                                                    <label for="attribute_id">Attribute</label>
                                                    <select id="attribute-select2" class="form-select mb-3"
                                                        name="attribute_id">
                                                        <option value="0">None</option>
                                                        @if (count($attributes) > 0)
                                                            @foreach ($attributes as $attribute)
                                                                <?php $dash = ''; ?>
                                                                <option value={{ $attribute->id }}>
                                                                    {{ $attribute->name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                @error('attribute_id')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col mb-4">
                                                <div class="form-group mb-3">
                                                    <label for="attribute_value_id">Attribute value</label>
                                                    <select id="attribute-value-select2" class="form-select mb-3"
                                                        name="attribute_value_id">
                                                        <option value="0">None</option>
                                                        {{-- @if (count($attributes) > 0)
                                                            @foreach ($attributes as $attribute)
                                                               
                                                                <option value={{ $attribute->id }}>
                                                                    {{ $attribute->name }}</option>
                                                            @endforeach
                                                        @endif --}}
                                                    </select>
                                                </div>
                                                @error('attribute_value_id')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group mb-3">
                                                        <button id="submit" type="submit"
                                                            class="btn btn-primary mt-3">Save</button>
                                                    </div>
                                                </div>
                                            </div>
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
    <script src="{{ asset('assets') }}/select2/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            const attributeSelect = $("#attribute-select2");
            attributeSelect.select2();

            const attributeValueSelect = $("#attribute-value-select2");

            attributeSelect.change(function() {
                const attributeId = $(this).val();

                const attributesList = <?php echo json_encode($attributes); ?>;
                const selectedAttributes = attributesList.find((attribute) => attribute.id == attributeId);

                const mappedAttributes = selectedAttributes.attribute_values.map((selectedValue) => {
                    return {
                        id: selectedValue.id,
                        text: selectedValue.name
                    }
                })

                attributeValueSelect.select2({
                    data: mappedAttributes
                });
            });

        });
    </script>
@endsection
