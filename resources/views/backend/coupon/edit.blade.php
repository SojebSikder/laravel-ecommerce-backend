@extends('backend.master')

@section('title')
    Edit coupon
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
                    Coupons
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="float-start">Edit coupon</h5>
                                <a href="{{ route('coupon.index') }}" class="btn btn-sm btn-primary float-end mt-3 mr-4">
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
                                    Coupon list
                                </a>

                            </div>
                            <div class="card-body">
                                <form action="{{ route('coupon.update', $coupon->id) }}" method="post">
                                    @csrf
                                    @method('PUT')

                                    <div class="d-flex form-group justify-content-end mb-3">
                                        <button id="submit" type="submit"
                                            class="btn btn-primary me-1 mt-3">Save</button>
                                    </div>

                                    <!-- status -->
                                    <div class="form-check form-switch mb-3 mt-5">
                                        <input class="form-check-input" @if ($coupon->status == 1) checked @endif
                                            value="1" name="status" type="checkbox" role="switch" id="status">
                                        <label class="form-check-label" for="status">Publish</label>
                                    </div>
                                    <!-- discount type -->
                                    <div class="row">
                                        <div class="col-6">
                                            <?php
                                            $coupon_types = [
                                                'order' => 'Order',
                                                'product' => 'Product',
                                                'category' => 'Category',
                                            ];
                                            ?>
                                            <div class="form-group mb-3">
                                                <label hidden for="">Discount Type</label>
                                                <!-- <sup style="color: red" title="You can skip this field">(optional)</sup> -->
                                                <select hidden id="coupon_type" name="coupon_type" class="form-control">
                                                    <option value="">===Select===</option>
                                                    @foreach ($coupon_types as $key => $value)
                                                        <option value="{{ $key }}"
                                                            @if ($coupon->coupon_type == $key) selected @endif>
                                                            {{ $value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- method -->
                                    <div class="row d-none mb-3">
                                        <div class="col">
                                            <label for="">Method</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="method" value="code"
                                                    id="codeMethod" @if ($coupon->method == 'code') checked @endif>
                                                <label class="form-check-label" for="codeMethod">
                                                    Discount code
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="method"
                                                    value="auto" id="autoMethod"
                                                    @if ($coupon->method == 'auto') checked @endif>
                                                <label class="form-check-label" for="autoMethod">
                                                    Automatic discount
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- discount code -->
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group mb-3">
                                                <div id="codeGroup">
                                                    <label>Discount Code</label>
                                                    <div class="row">
                                                        <div class="col form-group mb-3">
                                                            <input id="code" type="text" class="form-control"
                                                                value="{{ $coupon->code }}" name="code">
                                                        </div>
                                                        <div class="col form-group mb-3">
                                                            <button id="generateCode" type="button"
                                                                class="btn btn-outline-info">Generate</button>
                                                        </div>
                                                    </div>
                                                    <label>Customers must enter this code at checkout.</label>
                                                </div>

                                                <div id="titleGroup">
                                                    <label>Title</label>
                                                    <div class="row">
                                                        <div class="col form-group mb-3">
                                                            <input type="text" class="form-control"
                                                                value="{{ old('title') }}" name="title">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <hr>
                                    <!-- amount -->
                                    <div class="row">
                                        <div class="col-6">
                                            <?php
                                            $amount_types = [
                                                'fixed' => 'Fixed',
                                                'percentage' => 'Percentage',
                                            ];
                                            
                                            ?>
                                            <div class="form-group mb-3">
                                                <label for="">Amount Type</label>
                                                {{-- <sup style="color: red" title="You can skip this field">(optional)</sup> --}}
                                                <select name="amount_type" class="form-control">
                                                    <option value="">===Select===</option>
                                                    @foreach ($amount_types as $key => $value)
                                                        <option value="{{ $key }}"
                                                            @if ($coupon->amount_type == $key) selected @endif>
                                                            {{ $value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group mb-3">
                                                <label>Amount</label>
                                                <input type="number" class="form-control" value="{{ $coupon->amount }}"
                                                    name="amount">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <!-- Minimum purchase requirements -->
                                    <div id="minimumGroup">
                                        <div class="row">
                                            <div class="col-6">
                                                <label for="">Minimum purchase requirements</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="min_type"
                                                        value="none" id="minNone"
                                                        @if ($coupon->min_type == 'none') checked @endif>
                                                    <label class="form-check-label" for="minNone">No minimum
                                                        requirements</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="min_type"
                                                        value="amount" id="minAmount"
                                                        @if ($coupon->min_type == 'amount') checked @endif>
                                                    <label class="form-check-label" for="minAmount">
                                                        Minimum purchase amount
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="min_type"
                                                        value="quantity" id="minQuantity"
                                                        @if ($coupon->min_type == 'quantity') checked @endif>
                                                    <label class="form-check-label" for="minQuantity">
                                                        Minimum quantity of items
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="minGroup" class="row">
                                            <div id="minAmountGroup" class="col-6">
                                                <div class="form-group mb-3">
                                                    <label>Minimum purchase amount</label>
                                                    <input type="number" class="form-control"
                                                        value="{{ $coupon->min_amount }}" name="min_amount">
                                                </div>
                                            </div>
                                            <div id="minQuantityGroup" class="col-6">
                                                <div class="form-group mb-3">
                                                    <label>Minimum quantity of items</label>
                                                    <input type="number" class="form-control"
                                                        value="{{ $coupon->min_qnty }}" name="min_qnty">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <!-- maximum uses -->
                                    <div id="maximumUsesGroup" class="row">
                                        <div class="col-6">
                                            <div class="form-group mb-3">
                                                <label>Limit number of times this discount can be used in total</label>
                                                <input type="number" class="form-control"
                                                    value="{{ $coupon->max_uses }}" name="max_uses">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group mb-3">
                                                <label>Limit to one use per customer</label>
                                                <input type="number" class="form-control"
                                                    value="{{ $coupon->max_uses_user }}" name="max_uses_user">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <!-- Active date -->
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group mb-3">
                                                <label>Start date</label>
                                                <input type="date" class="form-control"
                                                    value="{{ $coupon->starts_at }}" name="starts_at">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- deactive date -->
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group mb-3">
                                                <label>End date</label>
                                                <input type="date" class="form-control"
                                                    value="{{ $coupon->expires_at }}" name="expires_at">
                                            </div>
                                        </div>
                                    </div>
                                    <!--/ end date -->

                                    <button type="submit" class="btn btn-primary mt-3">Save</button>
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
        // select2
        $(".select2").select2();
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

    <script>
        /**
         * trigger
         */
        // discount type
        const coupon_type = document.querySelector('#coupon_type');
        // method
        const codeMethod = document.querySelector('#codeMethod');
        const autoMethod = document.querySelector('#autoMethod');
        // min requirement
        const minNone = document.querySelector('#minNone');
        const minAmount = document.querySelector('#minAmount');
        const minQuantity = document.querySelector('#minQuantity');


        /**
         * group
         */
        // group
        const codeGroup = document.querySelector('#codeGroup');
        const titleGroup = document.querySelector('#titleGroup');
        // min requirement
        const minNoneGroup = document.querySelector('#minNoneGroup');
        const minAmountGroup = document.querySelector('#minAmountGroup');
        const minQuantityGroup = document.querySelector('#minQuantityGroup');
        // max uses
        const maximumUsesGroup = document.querySelector('#maximumUsesGroup');

        /**
         * init event
         */
        if (document.querySelector('input[name="method"]:checked').value == "code") {
            toggleCodeMethod()
        } else {
            toggleAutoMethod()
        }

        if (document.querySelector('input[name="min_type"]:checked').value == "amount") {
            toggleMinAmount();
        } else if (document.querySelector('input[name="min_type"]:checked').value == "quantity") {
            toggleMinQuantity();
        } else {
            toggleMinNone();
        }
        // toggleDiscountType();

        /** 
         * event listener
         */
        // discount type
        coupon_type.addEventListener("change", function(e) {
            toggleDiscountType(e.target.value)
        })
        // method
        codeMethod.addEventListener("change", function(e) {
            toggleCodeMethod()
        })
        autoMethod.addEventListener("change", function(e) {
            toggleAutoMethod()
        })
        // min requirement
        minNone.addEventListener("change", function(e) {
            toggleMinNone()
        })
        minAmount.addEventListener("change", function(e) {
            toggleMinAmount()
        })
        minQuantity.addEventListener("change", function(e) {
            toggleMinQuantity()
        })


        /** 
         * event
         */
        // discount type
        function toggleDiscountType(e = "order") {

        }

        // method
        function toggleCodeMethod() {
            // when code method selected
            codeGroup.classList.remove("d-none")
            titleGroup.classList.add("d-none")
            maximumUsesGroup.classList.remove("d-none")
        }

        function toggleAutoMethod() {
            // when auto method selected
            codeGroup.classList.add("d-none")
            titleGroup.classList.remove("d-none")
            maximumUsesGroup.classList.add("d-none")
        }
        // min requirement
        function toggleMinNone() {
            minAmountGroup.classList.add("d-none")
            minQuantityGroup.classList.add("d-none")
        }

        function toggleMinAmount() {
            minAmountGroup.classList.remove("d-none")
            minQuantityGroup.classList.add("d-none")
        }

        function toggleMinQuantity() {
            minAmountGroup.classList.add("d-none")
            minQuantityGroup.classList.remove("d-none")
        }
    </script>

    <script>
        // Generate discount code
        const code = document.querySelector('#code');
        const generateCodeTrigger = document.querySelector('#generateCode');

        generateCodeTrigger.addEventListener("click", function() {
            generateCodeClick();
        })

        // generateCodeClick();

        function generateCodeClick() {
            code.value = generateCode(16);
        }

        function generateCode(length) {
            const possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            var text = "";

            for (var i = 0; i < length; i++) {
                text += possible.charAt(Math.floor(Math.random() * possible.length));
            }
            return text;
        }
    </script>
@endsection
