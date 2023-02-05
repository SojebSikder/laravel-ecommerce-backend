@extends('backend.master')

@section('title')
    Select country
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
                    Shippings
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="float-start">Edit country</h5>
                                <a href="{{ route('shipping.edit', $shipping->id) }}"
                                    class="btn btn-sm btn-primary float-end mt-3 mr-4">
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
                                    Back
                                </a>

                            </div>
                            <div class="card-body">
                                <form action="{{ route('shipping.shipping-zone.update', $shippingZone->id) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col">

                                            <div class="d-flex form-group justify-content-end mb-3">
                                                <button id="submit" type="submit"
                                                    class="btn btn-primary me-1 mt-3">Save</button>
                                            </div>

                                            <input type="hidden" name="shipping_id" value="{{ $shipping->id }}">
                                            <input type="hidden" name="shipping_zone_id" value="{{ $shippingZone->id }}">

                                            <div class="col">
                                                <div class="form-group mb-3">
                                                    <label>Shipping zone Name<sup style="color: red">(*) </sup></label>
                                                    <input id="name" disabled type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        name="name" placeholder="Name"
                                                        value="{{ $shippingZone->name }}" />
                                                </div>
                                            </div>

                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="tags">Payment methods</label>
                                                    <select id="tags" class="form-control tags" multiple="multiple"
                                                        name="payment_providers[]">
                                                        @foreach ($payment_providers as $payment_provider)
                                                            <option value="{{ $payment_provider->name }}">
                                                                {{ $payment_provider->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col">
                                                <input type="checkbox" id="checkbox">
                                                <label for="checkbox">Select all</label>
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
            const tags = $(`.tags`);
            tags.select2({
                multiple: true,
                placeholder: "Select payment method",
                allowClear: true,
                tags: true
            });

            const selectedValues = <?php echo json_encode($shippingZone->payment_providers); ?>;
            const newSelectedValues = selectedValues.map((selectedValue) => selectedValue.name)
            tags.val(newSelectedValues).trigger('change');

        });

        $("#checkbox").click(function() {
            if ($("#checkbox").is(':checked')) {
                $(".tags > option").prop("selected", "selected");
                $(".tags").trigger("change");
            } else {
                $(".tags > option").removeAttr("selected");
                $(".tags").trigger("change");
            }
        });
    </script>
@endsection
