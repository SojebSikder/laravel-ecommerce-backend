@extends('Backend.master')

@section('title')
    Categories
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
                    Categories
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="float-start">Create category</h5>
                                <a href="{{ route('category.index') }}" class="btn btn-sm btn-primary float-end mt-3 mr-4">
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
                                    Category list
                                </a>

                            </div>
                            <div class="card-body">
                                <form action="{{ route('category.store') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group mb-3">
                                                <label for="name">Name</label>
                                                <input type="text" id="name"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    value="{{ old('name') }}" name="name"
                                                    placeholder='e.g. Vegetables'>
                                            </div>
                                            @error('name')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col">
                                            <div class="form-group mb-3">
                                                <label for="parent_category_id">Parent Category</label>
                                                <select class="select2 form-select mb-3" name="parent_category_id">
                                                    <option value="0"> ===Select parent category===</option>
                                                    @if (count($parent_categories) > 0)
                                                        @foreach ($parent_categories as $subcategory)
                                                            <option value={{ $subcategory->id }}
                                                                @if (request('parent_id') == $subcategory->id) selected @endif>
                                                                {{ $subcategory->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            @error('parent_category_id')
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
        $(".select2").select2();
    </script>
@endsection
