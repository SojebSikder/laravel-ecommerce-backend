@extends('backend.master')

@section('title')
    Menu Sublinks
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

        <div class="modal fade" id="sublink-modal-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Sublink Add</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('sublink.store') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="menu_id" value="{{ $menu->id }}">

                            <div class="form-group">
                                <label for="sort_order">Sorting order</label>
                                <input id="sort_order" placeholder="sort_order" value="0" type="text"
                                    class="form-control" name="sort_order">
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" name="is_parent"
                                    id="is_parent">
                                <label class="form-check-label" for="is_parent">
                                    Is parent?
                                </label>
                            </div>

                            <div id="name-group">
                                <div class="form-group">
                                    <label for="name">Name<sup style="color:red;"
                                            title="Must fill out this">*</sup></label>
                                    <input id="name" placeholder="Name" type="text" class="form-control"
                                        name="name">
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea id="description" placeholder="Description" type="text" class="form-control" name="description"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="image">Image<sup style="color:red;" title="Must fill out this">*</sup>
                                        [Single] [1296px by 410px]</label>
                                    <input id="image" type="file" accept="image/*" value="{{ old('image') }}"
                                        class="form-control-file" name="image">
                                </div>


                                <div class="form-group">
                                    <label>Parent Menu<sup style="color:red;" title="Must fill out this">*</sup></label>
                                    <select class="form-control basic" id="parent_id" name="parent_id">
                                        <option value="0">===Select===</option>
                                        @foreach ($parent_sublinks as $parent_sublink)
                                            <option value="{{ $parent_sublink->id }}">{{ $parent_sublink->head }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div id="head-group" class="form-group d-none">
                                <label for="head">Head (Sublink caption)</label>
                                <input id="head" placeholder="Head" type="text" class="form-control" name="head">
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" name="is_link"
                                    id="is_link">
                                <label class="form-check-label" for="is_link">
                                    has link? (url)
                                </label>
                            </div>
                            <div id="category-group" class="form-group">
                                <label>Category</label>
                                <select class="form-control basic" id="category_id" name="category_id">
                                    <option value="0">===Select===</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="link-group" class="form-group d-none">
                                <label for="link">Link</label>
                                <input id="link" placeholder="Link" type="text" class="form-control"
                                    name="link">
                            </div>
                            {{-- SEO --}}
                            <div class="col">
                                <label for="">SEO</label>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group mb-3">
                                            <label>Meta title<sup style="color:red;">(optional)</sup></label>
                                            <input id="meta_title" type="text" class="form-control" name="meta_title"
                                                placeholder="Meta title" value="{{ old('meta_title') }}" />
                                        </div>
                                        <div class="form-group">
                                            <label>Meta description<sup style="color:red;">(optional)</sup></label>
                                            <textarea type="text" maxlength="320" class="form-control" placeholder="e.g. Buy cool shirt now"
                                                name="meta_description">{{ old('meta_description') }}</textarea>
                                            <label>Limit 320 character</label>
                                        </div>
                                        <div class="form-group">
                                            <label>Meta keywords<sup style="color:red;">(optional)</sup></label>
                                            <input type="text" class="form-control"
                                                placeholder="e.g. shirt, blue-shirt" value="{{ old('meta_keyword') }}"
                                                name="meta_keyword">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- end SEO --}}

                            <button type="submit" class="btn btn-sm btn-primary float-right">Save</button>

                            <script>
                                // elements
                                const isParentCheckEl = document.querySelector('#is_parent');
                                const nameGroup = document.querySelector('#name-group');
                                const headGroup = document.querySelector('#head-group');
                                const isLinkCheckEl = document.querySelector('#is_link');
                                const categoryGroup = document.querySelector('#category-group');
                                const linkGroup = document.querySelector('#link-group');

                                // for name and head
                                isParentCheckEl.addEventListener("change", function(e) {
                                    nameGroup.classList.toggle("d-none");
                                    headGroup.classList.toggle("d-none");
                                })
                                isLinkCheckEl.addEventListener("change", function(e) {
                                    categoryGroup.classList.toggle("d-none");
                                    linkGroup.classList.toggle("d-none");
                                })
                            </script>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- end modal add --}}

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 fw-bold fs-3">
                    Menu Sublinks
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">

                                <a data-bs-toggle="modal" data-bs-target="#sublink-modal-create"
                                    class="btn btn-sm btn-primary float-end mr-4 mt-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    Create menu
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
                                    <table class="style-3 table">
                                        <thead>
                                            <tr>
                                                <th>Sublink</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($sublinks as $key => $sublink)
                                                <tr>
                                                    <td>
                                                        ({{ $sublink->sort_order }}) {{ $sublink->head }} -
                                                        <div style="display: inline-flex;">
                                                            <!-- Edit -->
                                                            <a href="javascript:void(0);" data-bs-toggle="modal"
                                                                data-bs-target="#parentMenuEdit{{ $sublink->id }}" class="bs-tooltip"
                                                                data-bs-toggle="tooltip" data-placement="top" title=""
                                                                data-bs-original-title="Edit">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-edit-2 br-6 mb-1 p-1">
                                                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                                    </path>
                                                                </svg>
                                                            </a>
                                                            {{-- delete sublink child --}}
                                                            <form method="post" action="{{ route('sublink.destroy', $sublink->id) }}"
                                                                id="{{ 'sublink-delete-' . $sublink->id }}">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                            <a href="javascript:void(0);"
                                                                onclick="event.preventDefault();
                                                                if(confirm('Are you really want to delete?')){
                                                                document.getElementById('sublink-delete-{{ $sublink->id }}').submit()
                                                                }"
                                                                class="bs-tooltip" data-toggle="tooltip" data-placement="top"
                                                                title="" data-original-title="Delete">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-trash br-6 mb-1 p-1">
                                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                                    <path
                                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                                    </path>
                                                                </svg>
                                                            </a>
                                                            <!-- status -->
                                                            @if ($sublink->status == '1')
                                                                <a href="{{ route('sublink.status', $sublink->id) }}"
                                                                    class="badge badge-primary shadow-none">Active</a>
                                                            @else
                                                                <a href="{{ route('sublink.status', $sublink->id) }}"
                                                                    class="badge badge-warning shadow-none">Disabled</a>
                                                            @endif
                                                            {{-- parent modal --}}
                                                            <div class="modal fade" id="parentMenuEdit{{ $sublink->id }}"
                                                                tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLongTitle">Parent
                                                                                Sublink Edit</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <form action="{{ route('sublink.update', $sublink) }}"
                                                                                method="post" enctype="multipart/form-data">
                                                                                @csrf
                                                                                @method('PUT')
                
                                                                                <div style="display: inline-block;" class="form-group">
                                                                                    <label for="sort{{ $sublink->id }}">Sorting
                                                                                        order</label>
                                                                                    <input style="max-width: 130px;" type="number"
                                                                                        class="form-control form-control-sm"
                                                                                        id="sort{{ $sublink->id }}" name="sort_order"
                                                                                        placeholder="sort order"
                                                                                        value="{{ $sublink->sort_order }}" />
                                                                                </div>
                
                                                                                <div class="form-group">
                                                                                    <input type="hidden" name="id"
                                                                                        value="{{ $sublink->id }}">
                                                                                    <input type="hidden" name="menu_id"
                                                                                        value="{{ $menu->id }}">
                                                                                    <label for="head{{ $sublink->id }}">Head</label>
                                                                                    <input type="text" class="form-control"
                                                                                        id="head{{ $sublink->id }}" name="head"
                                                                                        value="{{ $sublink->head }}">
                                                                                </div>
                
                
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input" type="checkbox"
                                                                                        value="1" name="is_link"
                                                                                        id="is_link{{ $sublink->id }}"
                                                                                        @if ($sublink->is_link == 1) checked @endif>
                                                                                    <label class="form-check-label"
                                                                                        for="is_link{{ $sublink->id }}">
                                                                                        has link? (url)
                                                                                    </label>
                                                                                </div>
                                                                                <div id="category-group{{ $sublink->id }}"
                                                                                    class="form-group">
                                                                                    <label>Category</label>
                                                                                    <select class="form-control basic" id="category_id"
                                                                                        name="category_id">
                                                                                        <option value="0">===Select===</option>
                                                                                        @foreach ($categories as $cat)
                                                                                            @if ($cat->id == $sublink->category_id)
                                                                                                <option selected
                                                                                                    value="{{ $cat->id }}">
                                                                                                    {{ $cat->name }}
                                                                                                </option>
                                                                                            @else
                                                                                                <option value="{{ $cat->id }}">
                                                                                                    {{ $cat->name }}
                                                                                                </option>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <div id="link-group{{ $sublink->id }}"
                                                                                    class="form-group d-none">
                                                                                    <label for="link">Link</label>
                                                                                    <input id="link" placeholder="Link"
                                                                                        value="{{ $sublink->link }}" type="text"
                                                                                        class="form-control" name="link">
                                                                                </div>
                
                                                                                <button type="submit"
                                                                                    class="btn btn-sm btn-primary float-right">Update</button>
                                                                                <script>
                                                                                    if (document.querySelector("#is_link{{ $sublink->id }}").checked) {
                                                                                        document.querySelector('#category-group{{ $sublink->id }}').classList.add("d-none");
                                                                                        document.querySelector('#link-group{{ $sublink->id }}').classList.remove("d-none");
                                                                                    } else {
                                                                                        document.querySelector('#category-group{{ $sublink->id }}').classList.remove("d-none");
                                                                                        document.querySelector('#link-group{{ $sublink->id }}').classList.add("d-none");
                                                                                    }
                                                                                    document.querySelector("#is_link{{ $sublink->id }}").addEventListener("change", function(e) {
                
                                                                                        if (e.target.checked == true) {
                                                                                            document.querySelector('#category-group{{ $sublink->id }}').classList.add("d-none");
                                                                                            document.querySelector('#link-group{{ $sublink->id }}').classList.remove("d-none");
                                                                                        } else {
                                                                                            document.querySelector('#category-group{{ $sublink->id }}').classList.remove("d-none");
                                                                                            document.querySelector('#link-group{{ $sublink->id }}').classList.add("d-none");
                                                                                        }
                
                                                                                    });
                                                                                </script>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- // parent modal --}}
                
                                                        </div>
                                                        <ul>
                                                            @if (count($sublink->sublinks) > 0)
                                                                @foreach ($sublink->sublinks as $sub)
                                                                    <li>
                                                                        ({{ $sub->sort_order }})
                                                                        {{ $sub->name }} -
                                                                        <div style="display: inline-flex;">
                                                                            <!-- Edit -->
                                                                            <a href="javascript:void(0);" data-bs-toggle="modal"
                                                                                data-bs-target="#childMenuEdit{{ $sub->id }}"
                                                                                class="bs-tooltip" data-bs-toggle="tooltip"
                                                                                data-bs-placement="top" title=""
                                                                                data-bs-original-title="Edit">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                                    stroke="currentColor" stroke-width="2"
                                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                                    class="feather feather-edit-2 br-6 mb-1 p-1">
                                                                                    <path
                                                                                        d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                                                    </path>
                                                                                </svg>
                                                                            </a>
                
                                                                            {{-- delete sublink child --}}
                                                                            <form method="post"
                                                                                action="{{ route('sublink.destroy', $sub->id) }}"
                                                                                id="{{ 'sublink-child-delete-' . $sub->id }}">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                            </form>
                                                                            <a href="javascript:void(0);"
                                                                                onclick="event.preventDefault();
                                                                                if(confirm('Are you really want to delete?')){
                                                                                document.getElementById('sublink-child-delete-{{ $sub->id }}').submit()
                                                                                }"
                                                                                class="bs-tooltip" data-toggle="tooltip"
                                                                                data-placement="top" title=""
                                                                                data-original-title="Delete">
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
                                                                            </a>
                                                                            <!-- status -->
                                                                            @if ($sub->status == '1')
                                                                                <a href="{{ route('sublink.status', $sub->id) }}"
                                                                                    class="badge badge-primary shadow-none">Active</a>
                                                                            @else
                                                                                <a href="{{ route('sublink.status', $sub->id) }}"
                                                                                    class="badge badge-warning shadow-none">Disabled</a>
                                                                            @endif
                                                                            {{-- child modal --}}
                                                                            <div class="modal fade" id="childMenuEdit{{ $sub->id }}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="exampleModalCenterTitle"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog modal-dialog-centered"
                                                                                    role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="exampleModalLongTitle">Child Sublink
                                                                                                Edit</h5>
                                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <form
                                                                                                action="{{ route('sublink.update', $sub) }}"
                                                                                                method="post"
                                                                                                enctype="multipart/form-data">
                                                                                                @csrf
                                                                                                @method('PUT')
                
                                                                                                <div style="display: inline-block;"
                                                                                                    class="form-group">
                                                                                                    <label
                                                                                                        for="sort{{ $sub->id }}">Sorting
                                                                                                        order</label>
                                                                                                    <input style="max-width: 130px;"
                                                                                                        type="number"
                                                                                                        class="form-control form-control-sm"
                                                                                                        id="sort{{ $sub->id }}"
                                                                                                        name="sort_order"
                                                                                                        placeholder="sort order"
                                                                                                        value="{{ $sub->sort_order }}" />
                                                                                                </div>
                
                                                                                                <div class="form-group">
                                                                                                    <input type="hidden" name="id"
                                                                                                        value="{{ $sub->id }}">
                                                                                                    <input type="hidden" name="menu_id"
                                                                                                        value="{{ $menu->id }}">
                                                                                                    <label
                                                                                                        for="name{{ $sub->id }}">Name</label>
                                                                                                    <input type="text"
                                                                                                        class="form-control"
                                                                                                        id="name{{ $sub->id }}"
                                                                                                        name="name"
                                                                                                        value="{{ $sub->name }}">
                
                
                                                                                                    <div class="form-group">
                                                                                                        <label
                                                                                                            for="description{{ $sub->id }}">Description</label>
                                                                                                        <textarea id="description{{ $sub->id }}" placeholder="Description" type="text" class="form-control"
                                                                                                            name="description">{{ $sub->description }}</textarea>
                                                                                                    </div>
                
                                                                                                    <div class="row">
                                                                                                        <div class="form-group">
                                                                                                            <label
                                                                                                                for="image{{ $sub->id }}">Image<sup
                                                                                                                    style="color:red;"
                                                                                                                    title="Must fill out this">*</sup>
                                                                                                                [Single] [1296px by
                                                                                                                410px]</label>
                                                                                                            <input
                                                                                                                id="image{{ $sub->id }}"
                                                                                                                type="file"
                                                                                                                accept="image/*"
                                                                                                                value="{{ old('image') }}"
                                                                                                                class="form-control-file"
                                                                                                                name="image">
                                                                                                        </div>
                                                                                                        <div class="form-group">
                                                                                                            <a href="{{ Storage::url('menu/sublink/' . $sub->image) }}"
                                                                                                                target="_blank"
                                                                                                                rel="noopener noreferrer">
                                                                                                                <img style="max-width: 80px !important;"
                                                                                                                    class="img-thumbnail"
                                                                                                                    src="{{ Storage::url('menu/sublink/' . $sub->image) }}"
                                                                                                                    alt="{{ $sub->image }}"
                                                                                                                    data-toggle="tooltip"
                                                                                                                    data-placement="top"
                                                                                                                    title="Click to view large mode">
                                                                                                            </a>
                                                                                                        </div>
                                                                                                    </div>
                
                                                                                                </div>
                
                
                                                                                                <div class="form-check">
                                                                                                    <input class="form-check-input"
                                                                                                        type="checkbox" value="1"
                                                                                                        name="is_link"
                                                                                                        id="is_link{{ $sub->id }}"
                                                                                                        @if ($sub->is_link == 1) checked @endif>
                                                                                                    <label class="form-check-label"
                                                                                                        for="is_link{{ $sub->id }}">
                                                                                                        has link? (url)
                                                                                                    </label>
                                                                                                </div>
                                                                                                <div id="category-group{{ $sub->id }}"
                                                                                                    class="form-group">
                                                                                                    <label>Category</label>
                                                                                                    <select class="form-control basic"
                                                                                                        id="category_id"
                                                                                                        name="category_id">
                                                                                                        <option value="0">===Select===
                                                                                                        </option>
                                                                                                        @foreach ($categories as $cat)
                                                                                                            @if ($cat->id == $sub->category_id)
                                                                                                                <option selected
                                                                                                                    value="{{ $cat->id }}">
                                                                                                                    {{ $cat->name }}
                                                                                                                </option>
                                                                                                            @else
                                                                                                                <option
                                                                                                                    value="{{ $cat->id }}">
                                                                                                                    {{ $cat->name }}
                                                                                                                </option>
                                                                                                            @endif
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </div>
                                                                                                <div id="link-group{{ $sub->id }}"
                                                                                                    class="form-group d-none">
                                                                                                    <label for="link">Link</label>
                                                                                                    <input id="link"
                                                                                                        placeholder="Link"
                                                                                                        value="{{ $sub->link }}"
                                                                                                        type="text"
                                                                                                        class="form-control"
                                                                                                        name="link">
                                                                                                </div>
                
                                                                                                {{-- SEO --}}
                                                                                                <div class="col">
                                                                                                    <label for="">SEO</label>
                                                                                                    <div class="row">
                                                                                                        <div class="col">
                                                                                                            <div class="form-group mb-3">
                                                                                                                <label>Meta title<sup
                                                                                                                        style="color:red;">(optional)</sup></label>
                                                                                                                <input id="meta_title"
                                                                                                                    type="text"
                                                                                                                    class="form-control"
                                                                                                                    name="meta_title"
                                                                                                                    placeholder="Meta title"
                                                                                                                    value="{{ $sub->meta_title }}" />
                                                                                                            </div>
                                                                                                            <div class="form-group">
                                                                                                                <label>Meta description<sup
                                                                                                                        style="color:red;">(optional)</sup></label>
                                                                                                                <textarea type="text" maxlength="320" class="form-control" placeholder="e.g. Buy cool shirt now"
                                                                                                                    name="meta_description">{{ $sub->meta_description }}</textarea>
                                                                                                                <label>Limit 320
                                                                                                                    character</label>
                                                                                                            </div>
                                                                                                            <div class="form-group">
                                                                                                                <label>Meta keywords<sup
                                                                                                                        style="color:red;">(optional)</sup></label>
                                                                                                                <input type="text"
                                                                                                                    class="form-control"
                                                                                                                    placeholder="e.g. shirt, blue-shirt"
                                                                                                                    value="{{ $sub->meta_keyword }}"
                                                                                                                    name="meta_keyword">
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                {{-- end SEO --}}
                
                                                                                                <button type="submit"
                                                                                                    class="btn btn-sm btn-primary float-right">Update</button>
                                                                                                <script>
                                                                                                    if (document.querySelector("#is_link{{ $sub->id }}").checked) {
                                                                                                        document.querySelector('#category-group{{ $sub->id }}').classList.add("d-none");
                                                                                                        document.querySelector('#link-group{{ $sub->id }}').classList.remove("d-none");
                                                                                                    } else {
                                                                                                        document.querySelector('#category-group{{ $sub->id }}').classList.remove("d-none");
                                                                                                        document.querySelector('#link-group{{ $sub->id }}').classList.add("d-none");
                                                                                                    }
                                                                                                    document.querySelector("#is_link{{ $sub->id }}").addEventListener("change", function(e) {
                
                                                                                                        if (e.target.checked == true) {
                                                                                                            document.querySelector('#category-group{{ $sub->id }}').classList.add("d-none");
                                                                                                            document.querySelector('#link-group{{ $sub->id }}').classList.remove("d-none");
                                                                                                        } else {
                                                                                                            document.querySelector('#category-group{{ $sub->id }}').classList.remove("d-none");
                                                                                                            document.querySelector('#link-group{{ $sub->id }}').classList.add("d-none");
                                                                                                        }
                
                                                                                                    });
                                                                                                </script>
                                                                                            </form>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            {{-- // child modal --}}
                
                                                                        </div>
                
                                                                    </li>
                                                                @endforeach
                                                            @endif
                                                        </ul>
                                                    </td>
                                                </tr>
                                            @endforeach
                
                                        </tbody>
                                    </table>
                                    {{ $sublinks->appends(request()->query())->links('pagination::bootstrap-5') }}
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
