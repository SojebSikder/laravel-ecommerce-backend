@extends('backend.master')

@section('title')
    Create currency
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
                    Currency
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="float-start">Create currency</h5>
                                <a href="{{ route('currency.index') }}" class="btn btn-sm btn-primary float-end mr-4 mt-3">
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
                                    currency list
                                </a>

                            </div>
                            <div class="card-body">
                                <form action="{{ route('currency.store') }}" method="POST" enctype="multipart/form-data">
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

                                            <div class="col">
                                                <div class="form-group mb-3">
                                                    <label for="name">Name</label>
                                                    <input type="text" id="name"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        value="{{ old('name') }}" name="name"
                                                        placeholder="e.g. US Dollar">
                                                </div>
                                                @error('name')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col">
                                                <div class="form-group mb-3">
                                                    <label for="currency_code">Currency code</label>
                                                    <input type="text" id="currency_code"
                                                        class="form-control @error('currency_code') is-invalid @enderror"
                                                        value="{{ old('currency_code') }}" name="currency_code"
                                                        placeholder="e.g. USD">
                                                </div>
                                                @error('currency_code')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col">
                                                <div class="form-group mb-3">
                                                    <label for="currency_sign">Currency sign</label>
                                                    <input type="text" id="currency_sign"
                                                        class="form-control @error('currency_sign') is-invalid @enderror"
                                                        value="{{ old('currency_sign') }}" name="currency_sign"
                                                        placeholder="e.g. $">
                                                </div>
                                                @error('currency_sign')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col">
                                                <div class="form-group mb-3">
                                                    <label for="rate">Rate</label>
                                                    <input type="text" id="rate"
                                                        class="form-control @error('rate') is-invalid @enderror"
                                                        value="{{ old('rate') }}" name="rate"
                                                        placeholder="e.g. 1.0000">
                                                </div>
                                                @error('rate')
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
@endsection
