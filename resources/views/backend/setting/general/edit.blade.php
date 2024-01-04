@extends('backend.master')

@section('title')
    General Setting
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
                    General Setting
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="float-start">General Setting</h5>
                                

                            </div>
                            <div class="card-body">
                                <form action="{{ route('general-setting.update', $generalSetting->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col">

                                            <div class="d-flex form-group justify-content-end mb-3">
                                                <button id="submit" type="submit"
                                                    class="btn btn-primary me-1 mt-3">Save</button>
                                            </div>

                                            {{-- image --}}
                                            <div class="col mb-4">
                                                <div>
                                                    {{-- <a href="{{ $generalSetting->image_url }}"
                                                        target="_blank" rel="noopener noreferrer"> --}}
                                                    <img style="max-width: 80px !important;" class="img-thumbnail"
                                                        src="{{ $generalSetting->image_url }}"
                                                        alt="{{ $generalSetting->image }}" data-toggle="tooltip"
                                                        data-placement="top" title="Click to view large mode">
                                                    {{-- </a> --}}
                                                </div>
                                                <div class="form-group">
                                                    <label for="name">Picture</label>
                                                    <label class="btn btn-info">
                                                        Choose file <input type="file" accept="image/*" name="logo"
                                                            id="uploadImage" class="d-none">
                                                    </label>

                                                    @error('logo')
                                                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col">
                                                <div class="form-group mb-3">
                                                    <label for="name">Name</label>
                                                    <input type="text" id="name"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        value="{{ $generalSetting->name }}" name="name">
                                                </div>
                                                @error('name')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                         


                                            <hr>
                                            {{-- seo --}}
                                            <div class="col">
                                                <h5>SEO tags</h5>
                                                <div class="col">
                                                    <div class="form-group mb-3">
                                                        <label for="meta_title">Meta Title</label>
                                                        <input type="text"
                                                            class="form-control @error('meta_title') is-invalid @enderror"
                                                            value="{{ $generalSetting->meta_title }}" name="meta_title"
                                                            placeholder='Title'>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="meta_description">Meta Description</label>
                                                        <textarea name="meta_description" placeholder="Description"
                                                            class="form-control @error('meta_description') is-invalid @enderror" cols="10" rows="5">{{ $generalSetting->meta_description }}</textarea>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="meta_keyword">Meta Keywords</label>
                                                        <input type="text"
                                                            class="form-control @error('meta_keyword') is-invalid @enderror"
                                                            value="{{ $generalSetting->meta_keyword }}"
                                                            name="meta_keyword" placeholder='Keywords'>
                                                    </div>

                                                </div>

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
@endsection
