@extends('backend.master')

@section('title')
    Create customer
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
                    Customers
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="float-start">Create customer</h5>
                                <a href="{{ route('customer.index') }}" class="btn btn-sm btn-primary float-end mt-3 mr-4">
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
                                    Customer list
                                </a>

                            </div>
                            <div class="card-body">
                                <form action="{{ route('customer.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col">

                                            <div class="d-flex form-group justify-content-end mb-3">
                                                <button id="submit" type="submit"
                                                    class="btn btn-primary me-1 mt-3">Save</button>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-lg-7 col-md-7 col-sm-12">
                    
                                                    <div class="col">
                                                        <div class="form-check form-switch mb-3 mt-5">
                                                            <input class="form-check-input" checked value="1" name="status"
                                                                type="checkbox" role="switch" id="status">
                                                            <label class="form-check-label" for="status">Active</label>
                                                        </div>
                                                    </div>
                    
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="fname">First name</label>
                                                            <input name="fname" value="{{ old('fname') }}" placeholder="First name"
                                                                id="fname" class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="lname">Last name</label>
                                                            <input name="lname" value="{{ old('lanme') }}" placeholder="Last name"
                                                                id="lname" class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="email">Email</label>
                                                            <input name="email" value="{{ old('email') }}" placeholder="Email" id="email"
                                                                class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="password">Password</label>
                                                            <input name="password" placeholder="Password" id="password" class="form-control"
                                                                type="text">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="cpassword">Confirm Password</label>
                                                            <input name="cpassword" placeholder="Password" id="cpassword" class="form-control"
                                                                type="text">
                                                        </div>
                                                    </div>
                    
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="type">User type</label>
                                                            <?php
                                                            $types = [
                                                                'user' => 'User',
                                                                'admin' => 'Admin',
                                                                'su_admin' => 'Super Admin',
                                                            ];
                                                            ?>
                                                            <select class="form-control" name="type" id="type">
                                                                <option value="">Select role type</option>
                                                                @foreach ($types as $key => $value)
                                                                    <option value="{{ $key }}"
                                                                        @if ($key == 'user') selected @endif>{{ $value }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
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
@endsection
