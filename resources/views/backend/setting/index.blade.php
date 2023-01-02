@extends('backend.master')

@section('title')
    Settings
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('assets') }}/select2/css/select2.min.css">
    <script src="{{ asset('assets') }}/tinymce/tinymce.min.js"></script>
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
                    Settings
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">


                                {{-- search --}}
                                <form method="get">
                                    <div>
                                        <input class="form-control-sm float-end me-3 mt-3" name="q"
                                            value="{{ request('q') }}" type="text" placeholder="search">
                                    </div>
                                </form>

                            </div>
                            <div class="card-body">
                                <div>
                                    <table class="table-bordered table-hover table-striped table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Label</th>
                                                <th>Key</th>
                                                <th>Description</th>
                                                <th>Value</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($settings as $setting)
                                                <tr>
                                                    <td>{{ $setting->label }}</td>
                                                    <td>{{ $setting->key }}</td>
                                                    <td>{{ $setting->description }}</td>
                                                    <td>{{ $setting->value }}</td>

                                                    <td class="text-center">
                                                        <ul class="table-controls">
                                                            <li>
                                                                <a class="btn btn-sm btn-primary" href="javascript:void(0);"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#settingEdit{{ $setting->id }}"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    data-bs-title="Edit">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="feather feather-edit-2 br-6 mb-1 p-1">
                                                                        <path
                                                                            d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                                        </path>
                                                                    </svg>
                                                                    Edit
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </td>

                                                    {{-- modal --}}
                                                    <div class="modal fade" id="settingEdit{{ $setting->id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLongTitle">
                                                                        Setting</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form action="{{ route('setting.update', $setting) }}"
                                                                        method="post" enctype="multipart/form-data">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <div class="form-group">
                                                                            <label for="label">Label</label>
                                                                            <input type="text" disabled
                                                                                class="form-control" id="label"
                                                                                name="label"
                                                                                value="{{ $setting->label }}">
                                                                        </div>

                                                                        @if ($setting->value_type == 'html')
                                                                            <div class="form-group">
                                                                                <label
                                                                                    class="form-control-plaintext">Value<sup
                                                                                        style="color:red;"
                                                                                        title="Must fill out this">*</sup></label>
                                                                                <textarea rows="5" class="jqte-test" id="editor{{ $setting->id }}" name="value">{{ $setting->value }}</textarea>
                                                                            </div>

                                                                            <script>
                                                                                // tinymce
                                                                                tinymce.init({
                                                                                    selector: '#editor{{ $setting->id }}',
                                                                                });
                                                                            </script>
                                                                        @elseif($setting->value_type == 'textarea')
                                                                            <div class="form-group">
                                                                                <label
                                                                                    class="form-control-plaintext">Value<sup
                                                                                        style="color:red;"
                                                                                        title="Must fill out this">*</sup></label>
                                                                                <textarea rows="5" class="form-control" id="value" name="value">{{ $setting->value }}</textarea>
                                                                            </div>
                                                                        @elseif($setting->value_type == 'color')
                                                                            <div class="form-group">
                                                                                <label
                                                                                    class="form-control-plaintext">Value<sup
                                                                                        style="color:red;"
                                                                                        title="Must fill out this">*</sup></label>
                                                                                <input type="color" id="value"
                                                                                    name="value"
                                                                                    value="{{ $setting->value }}">
                                                                            </div>
                                                                        @elseif($setting->value_type == 'checkbox')
                                                                            <div class="form-group">
                                                                                <label
                                                                                    class="form-control-plaintext">Value<sup
                                                                                        style="color:red;"
                                                                                        title="Must fill out this">*</sup></label>
                                                                                <input type="checkbox" id="value"
                                                                                    name="value" value="1"
                                                                                    @if ($setting->value == 1) checked @endif>
                                                                            </div>
                                                                        @else
                                                                            <div class="form-group">
                                                                                <label for="value">Value<sup
                                                                                        style="color:red;"
                                                                                        title="Must fill out this">*</sup></label>
                                                                                <input type="text" class="form-control"
                                                                                    id="value" name="value"
                                                                                    value="{{ $setting->value }}">
                                                                            </div>
                                                                        @endif

                                                                        <button type="submit"
                                                                            class="btn btn-sm btn-primary float-right">Update</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- // modal --}}
                                                </tr>
                                            @endforeach

                                        </tbody>


                                    </table>
                                    {{ $settings->appends(request()->query())->links('pagination::bootstrap-5') }}
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
