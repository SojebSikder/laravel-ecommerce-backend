@extends('backend.master')

@section('title')
    Demo
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
                    Demo
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header"></div>
                            <div class="card-body">
                                <div>
                                    {{-- simple calculator --}}
                                    <form action="{{ route('calculate') }}" method="post">
                                        @csrf
                                        
                                        <div class="col">
                                            <div class="form-group mb-3">
                                                <label for="num1">Enter first number</label>
                                                <input type="text" id="num1"
                                                    class="form-control @error('num1') is-invalid @enderror"
                                                    value="{{ old('num1') }}" name="num1">
                                            </div> 
                                        </div>

                                        <div class="col">
                                            <div class="form-group mb-3">
                                                <label for="num2">Enter second number</label>
                                                <input type="text" id="num2"
                                                    class="form-control @error('num2') is-invalid @enderror"
                                                    value="{{ old('num2') }}" name="num2">
                                            </div> 
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group mb-3">
                                                    <button id="submit" type="submit"
                                                        class="btn btn-primary mt-3">Calculate</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    {{-- show result --}}
                                    @if (Session::has('result'))
                                        <strong>{{ Session::get('result') }}</strong>.
                                    @endif
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
