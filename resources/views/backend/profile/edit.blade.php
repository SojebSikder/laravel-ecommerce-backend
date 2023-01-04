@extends('backend.master')

@section('title')
    Update Profile
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
                    Profile
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="float-start">Edit profile</h5>
                            </div>
                            
                            <div class="card-body">
                                <form action="{{ route('user.profile.update') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-md-lg-7 col-md-7 col-sm-12">

                                            <div class="col">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group mb-3">
                                                            <label>First name<sup style="color: red">(*) </sup></label>
                                                            <input id="fname" type="text"
                                                                class="form-control @error('fname') is-invalid @enderror"
                                                                name="fname" placeholder="Enter first name"
                                                                value="{{ $user->fname }}" />
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group mb-3">
                                                            <label>Last name<sup style="color: red">(*) </sup></label>
                                                            <input id="lname" type="text"
                                                                class="form-control @error('lname') is-invalid @enderror"
                                                                name="lname" placeholder="Enter last name"
                                                                value="{{ $user->lname }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group mb-3">
                                                    <label>Email<sup style="color: red">(*) </sup></label>
                                                    <input id="email" type="text"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        name="email" placeholder="e.g. admin@example.com"
                                                        value="{{ $user->email }}" />
                                                </div>
                                            </div>
                                            <div class="col">
                                                <h4>Change password</h4>
                                            </div>

                                            <div class="col">
                                                <div class="form-group mb-3">
                                                    <label>Old password</label>
                                                    <input id="old_password" type="text"
                                                        class="form-control @error('old_password') is-invalid @enderror"
                                                        name="old_password" placeholder="Enter your old password"
                                                        value="{{ old('old_password') }}" />
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group mb-3">
                                                            <label>New password</label>
                                                            <input id="password" type="text"
                                                                class="form-control @error('password') is-invalid @enderror"
                                                                name="password" placeholder="Enter new password"
                                                                value="{{ old('password') }}" />
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group mb-3">
                                                            <label>Confirm password</label>
                                                            <input id="confirm_password" type="text"
                                                                class="form-control @error('confirm_password') is-invalid @enderror"
                                                                name="confirm_password" placeholder="Confirm password"
                                                                value="{{ old('confirm_password') }}" />
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col">
                                                <div class="form-group mb-3">
                                                    <button type="submit" class="btn btn-primary mt-3">Save</button>
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
