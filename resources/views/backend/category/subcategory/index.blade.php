@extends('Backend.master')

@section('title')
    Subcategories
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
                    Subcategories → {{$category->name}}
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">

                                <a href="{{ route('category.create') }}" class="btn btn-sm btn-primary float-end mt-3 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    Create category
                                </a>

                            </div>
                            <div class="card-body">
                                <div>
                                    <table class="table-striped table-hover table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>Slug</th>
                                                <th>Subcategories</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($category->sub_categories as $category)
                                                <tr>
                                                    <td>{{ $category->name }}</td>
                                                    <td>{{ $category->slug }}</td>
                                                    <td>
                                                        <a href="{{ route('category.subcategory', $category->id) }}"
                                                            class="btn btn-sm btn-light">
                                                            {{ count($category->sub_categories) }} subcategories
                                                        </a>
                                                    </td>
                                                    @if ($category->status == '1')
                                                        <td class="text-center">
                                                            <a href="{{ route('category.status', $category->id) }}"
                                                                class="badge bg-primary text-decoration-none shadow-none">
                                                                Active
                                                            </a>
                                                        </td>
                                                    @else
                                                        <td class="text-center">
                                                            <a href="{{ route('category.status', $category->id) }}"
                                                                class="badge bg-warning text-decoration-none shadow-none">
                                                                Disabled
                                                            </a>
                                                        </td>
                                                    @endif
                                                    <td class="text-center">
                                                        <ul class="table-controls">
                                                            <li>
                                                                <a class="btn btn-sm btn-primary"
                                                                    href="{{ route('category.edit', $category->id) }}"
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
                                                                <a class="btn btn-sm btn-danger" href="javascript:void(0);"
                                                                    onclick="event.preventDefault();
                                                                    if(confirm('Are you really want to delete?')){
                                                                    document.getElementById('category-delete-{{ $category->id }}').submit()
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
                                                                    action="{{ route('category.destroy', $category->id) }}"
                                                                    id="{{ 'category-delete-' . $category->id }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>


                                    </table>
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
