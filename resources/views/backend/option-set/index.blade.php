@extends('backend.master')

@section('title')
    Option Sets
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


        {{-- modal add --}}

        <div class="modal fade" id="optionSetAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Create Option set</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('option-set.store') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" placeholder="Enter Name"
                                    class="form-control @error('name') is-invalid @enderror" name="name">
                                @error('name')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="description">Description (Optional)</label>
                                <input type="text" placeholder="Description"
                                    class="form-control @error('description') is-invalid @enderror" name="description">
                                @error('description')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary float-right">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- end modal add --}}



        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 fw-bold fs-3">
                    Option Sets
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">

                                <a class="btn btn-sm btn-primary float-end mr-4 mt-3" data-bs-toggle="modal"
                                    data-bs-target="#optionSetAdd">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    Create option set
                                </a>

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
                                                <th>Option Set</th>
                                                <th>Description</th>
                                                <th>Option set elements</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($option_sets as $option_set)
                                                <tr>
                                                    <td>{{ $option_set->name }}</td>
                                                    <td>{{ $option_set->description }}</td>
                                                    <td>
                                                        <a href="{{ route('option-set.show', $option_set->id) }}"
                                                            class="btn btn-sm btn-light">
                                                            {{ count($option_set->elements) }} elements
                                                        </a>
                                                        @if ($option_set->status == 1)
                                                    <td class="text-center">
                                                        <a href="{{ route('option-set.status', $option_set->id) }}"
                                                            class="badge bg-primary text-decoration-none shadow-none">
                                                            Active
                                                        </a>
                                                    </td>
                                                @else
                                                    <td class="text-center">
                                                        <a href="{{ route('option-set.status', $option_set->id) }}"
                                                            class="badge bg-warning text-decoration-none shadow-none">
                                                            Disabled
                                                        </a>
                                                    </td>
                                            @endif
                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    <li>
                                                        <a class="btn btn-sm btn-primary"
                                                            href="{{ route('option-set.edit', $option_set->id) }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#optionSetEdit{{ $option_set->id }}"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="" data-bs-title="Edit">
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
                                                    <li>
                                                        <a class="btn btn-sm btn-secondary"
                                                            href="{{ route('option-set.duplicate', $option_set->id) }}"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="" data-bs-title="Duplicate">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-plus">
                                                                <line x1="12" y1="5" x2="12"
                                                                    y2="19"></line>
                                                                <line x1="5" y1="12" x2="19"
                                                                    y2="12"></line>
                                                            </svg>
                                                            Duplicate
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="btn btn-sm btn-danger" href="javascript:void(0);"
                                                            onclick="event.preventDefault();
                                                                    if(confirm('Are you really want to delete?')){
                                                                    document.getElementById('category-delete-{{ $option_set->id }}').submit()
                                                                    }"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="" data-bs-title="Delete">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-trash br-6 mb-1 p-1">
                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                <path
                                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                                </path>
                                                            </svg>
                                                            Delete
                                                        </a>
                                                        {{-- delete  --}}
                                                        <form method="post"
                                                            action="{{ route('option-set.destroy', $option_set->id) }}"
                                                            id="{{ 'category-delete-' . $option_set->id }}">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>

                                                        {{-- modal --}}

                                                        <div class="modal fade text-start"
                                                            id="optionSetEdit{{ $option_set->id }}" tabindex="-1"
                                                            role="dialog" aria-labelledby="exampleModalCenterTitle"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered"
                                                                role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="exampleModalLongTitle">Edit Option set</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form
                                                                            action="{{ route('option-set.update', $option_set) }}"
                                                                            method="post" enctype="multipart/form-data">
                                                                            @csrf
                                                                            @method('PUT')

                                                                            <div class="form-group">
                                                                                <label for="name">Name</label>
                                                                                <input type="text"
                                                                                    placeholder="Enter Name"
                                                                                    class="form-control @error('name') is-invalid @enderror"
                                                                                    name="name"
                                                                                    value="{{ $option_set->name }}">
                                                                                @error('name')
                                                                                    <div class="alert alert-danger mt-1">
                                                                                        {{ $message }}</div>
                                                                                @enderror
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="description">Description
                                                                                    (Optional)
                                                                                </label>
                                                                                <input type="text"
                                                                                    placeholder="Description"
                                                                                    class="form-control @error('description') is-invalid @enderror"
                                                                                    name="description"
                                                                                    value="{{ $option_set->description }}">
                                                                                @error('description')
                                                                                    <div class="alert alert-danger mt-1">
                                                                                        {{ $message }}</div>
                                                                                @enderror
                                                                            </div>
                                                                            <button type="submit"
                                                                                class="btn btn-sm btn-primary float-right">Update</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- // modal --}}
                                                    </li>
                                                </ul>
                                            </td>
                                            </tr>
                                            @endforeach

                                        </tbody>


                                    </table>
                                    {{ $option_sets->appends(request()->query())->links('pagination::bootstrap-5') }}
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
