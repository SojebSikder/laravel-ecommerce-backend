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
                                                        placeholder='e.g. Vegetables'>
                                                </div>
                                                @error('name')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col mb-4">
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

                                            {{-- image --}}
                                            <div class="col mb-4">
                                                <div class="form-group">
                                                    <label for="name">Picture</label>
                                                    <label class="btn btn-info">
                                                        Choose file <input type="file" accept="image/*" name="image"
                                                            id="uploadImage" class="d-none">
                                                    </label>

                                                    @error('image')
                                                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <textarea name="description" id="description"></textarea>
                                                </div>
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
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            mergetags_list: [{
                    value: 'First.Name',
                    title: 'First Name'
                },
                {
                    value: 'Email',
                    title: 'Email'
                },
            ]
        });
    </script>
@endsection
