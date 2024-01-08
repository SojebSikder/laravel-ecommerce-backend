@extends('backend.master')

@section('title')
    Menu
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
        <div class="modal fade" id="menu-create-modal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Create menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('menu.store') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="style">Color</label>
                                <input type="color" id="style" name="style">
                            </div>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <div class="form-check">
                                <input checked class="form-check-input" type="checkbox" value="1" name="is_link"
                                    id="is_link">
                                <label class="form-check-label" for="is_link">
                                    has link
                                </label>
                            </div>
                            <div id="link-group" class="form-group">
                                <label for="link">Link</label>
                                <input id="link" type="text" class="form-control" placeholder="Optional"
                                    name="link">
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" name="sub_menu"
                                    id="sub_menu">
                                <label class="form-check-label" for="sub_menu">
                                    Sub Menu Caption
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" name="is_right"
                                    id="is_right">
                                <label class="form-check-label" for="is_right">
                                    Right section
                                </label>
                            </div>
                            <div id="right" class="form-group">

                                <label for="text">Text</label>
                                <input id="text" type="text" class="form-control" name="text">

                                <label for="head">Head (Link text)</label>
                                <input id="head" type="text" class="form-control" name="head">

                                <label for="right_link">Right Link</label>
                                <input id="right_link" type="text" class="form-control" placeholder="Optional"
                                    name="right_link">

                                <label for="image">Image [Single] [416px by 236px]</label>
                                <input id="image" type="file" accept="image/*" value="{{ old('image') }}"
                                    class="form-control-file" name="image">
                            </div>

                            <button type="submit" class="btn btn-sm btn-primary float-right">Save</button>

                            <script>
                                // right section
                                const rightEl = document.querySelector('#right')
                                const isLinkCheckEl = document.querySelector('#is_link')
                                const linkGroup = document.querySelector('#link-group')
                                const rightCheckEl = document.querySelector('#is_right')
                                // hide right elements
                                rightEl.style.display = "none";

                                isLinkCheckEl.addEventListener("change", function(e) {
                                    if (e.target.checked == true) {
                                        linkGroup.style.display = "block";
                                    } else {
                                        linkGroup.style.display = "none";
                                    }
                                })

                                rightCheckEl.addEventListener("change", function(e) {
                                    if (e.target.checked == true) {
                                        rightEl.style.display = "block";
                                    } else {
                                        rightEl.style.display = "none";
                                    }
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
                    Menu
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">

                                <a data-bs-toggle="modal" data-bs-target="#menu-create-modal"
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
                                    <table class="table-bordered table-hover table-striped table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Menu</th>
                                                <th>Image</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Actions</th>
                                                <th>Sorting order</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($menus as $key => $menu)
                                                <tr>
                                                    <td>{{ $menu->name }}</td>

                                                    <td>
                                                        <a href="{{ Storage::url('menu/' . $menu->image) }}"
                                                            target="_blank" rel="noopener noreferrer">
                                                            <img style="max-width: 80px !important;" class="img-thumbnail"
                                                                src="{{ Storage::url('menu/' . $menu->image) }}"
                                                                alt="{{ $menu->image }}" data-toggle="tooltip"
                                                                data-placement="top" title="Click to view large mode">
                                                        </a>
                                                    </td>

                                                    @if ($menu->status == '1')
                                                        <td class="text-center">
                                                            <a href="{{ route('menu.status', $menu->id) }}"
                                                                class="badge bg-primary text-decoration-none shadow-none">
                                                                Active
                                                            </a>
                                                        </td>
                                                    @else
                                                        <td class="text-center">
                                                            <a href="{{ route('menu.status', $menu->id) }}"
                                                                class="badge bg-warning text-decoration-none shadow-none">
                                                                Disabled
                                                            </a>
                                                        </td>
                                                    @endif
                                                    <td class="text-center">
                                                        <ul class="table-controls">
                                                            <li>
                                                                <a href="javascript:void(0);" data-bs-toggle="modal"
                                                                    data-bs-target="#menu-edit-modal{{ $menu->id }}"
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
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0);"
                                                                    onclick="event.preventDefault();
                                                                        if(confirm('Are you really want to delete?')){
                                                                        document.getElementById('menu-delete-{{ $menu->id }}').submit()
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
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('sublink.show', $menu->id) }}"
                                                                    class="bs-tooltip" data-toggle="tooltip"
                                                                    data-placement="top" title=""
                                                                    data-original-title="Add Sublinks">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="feather feather-plus">
                                                                        <line x1="12" y1="5"
                                                                            x2="12" y2="19"></line>
                                                                        <line x1="5" y1="12"
                                                                            x2="19" y2="12"></line>
                                                                    </svg>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </td>

                                                    <td>
                                                        {{-- update sorting order --}}
                                                        <form method="post"
                                                            action="{{ route('menu.sortingOrder', $menu->id) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="form-group">
                                                                <input style="max-width: 130px;" type="number"
                                                                    class="form-control form-control-sm"
                                                                    id="sort{{ $menu->id }}" name="sort"
                                                                    placeholder="sort order"
                                                                    value="{{ $menu->sort_order }}">
                                                            </div>
                                                            <button type="submit"
                                                                class="btn btn-sm btn-primary">Save</button>
                                                        </form>
                                                    </td>

                                                    {{-- delete --}}
                                                    <form method="post" action="{{ route('menu.destroy', $menu->id) }}"
                                                        id="{{ 'menu-delete-' . $menu->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    {{-- modal --}}

                                                    <div class="modal fade" id="menu-edit-modal{{ $menu->id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLongTitle">
                                                                        Menu Edit</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form action="{{ route('menu.update', $menu) }}"
                                                                        method="post" enctype="multipart/form-data">
                                                                        @csrf
                                                                        @method('PUT')

                                                                        <div class="form-group">
                                                                            <label
                                                                                for="edit_style{{ $menu->id }}">Color</label>
                                                                            <input type="color"
                                                                                id="edit_style{{ $menu->id }}"
                                                                                name="style"
                                                                                value="{{ $menu->style }}">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label
                                                                                for="edit_name{{ $menu->id }}">Name</label>
                                                                            <input type="hidden" name="id"
                                                                                value="{{ $menu->id }}">
                                                                            <input type="text" class="form-control"
                                                                                id="edit_name{{ $menu->id }}"
                                                                                name="name"
                                                                                value="{{ $menu->name }}">
                                                                        </div>

                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox" value="1"
                                                                                name="is_link"
                                                                                id="edit_is_link{{ $menu->id }}"
                                                                                @if ($menu->is_link == 1) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="edit_is_link{{ $menu->id }}">
                                                                                has link?
                                                                            </label>
                                                                        </div>

                                                                        <div id="edit-link-group{{ $menu->id }}"
                                                                            class="form-group">
                                                                            <label
                                                                                for="link{{ $menu->id }}">Link</label>
                                                                            <input id="link{{ $menu->id }}"
                                                                                type="text" class="form-control"
                                                                                placeholder="Optional" name="link"
                                                                                value="{{ $menu->link }}">
                                                                        </div>

                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox" value="1"
                                                                                name="sub_menu"
                                                                                id="edit_sub_menu{{ $menu->id }}"
                                                                                @if ($menu->sub_menu == 1) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="edit_sub_menu{{ $menu->id }}">
                                                                                Sub Menu Caption
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox" value="1"
                                                                                name="is_right"
                                                                                id="edit_is_right{{ $menu->id }}"
                                                                                @if ($menu->is_right == 1) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="edit_is_right{{ $menu->id }}">
                                                                                Right section
                                                                            </label>
                                                                        </div>
                                                                        <div id="edit_right{{ $menu->id }}"
                                                                            class="form-group">

                                                                            <label
                                                                                for="edit_text{{ $menu->id }}">Text</label>
                                                                            <input id="edit_text{{ $menu->id }}"
                                                                                type="text" class="form-control"
                                                                                name="text"
                                                                                value="{{ $menu->text }}">

                                                                            <label for="edit_head{{ $menu->id }}">Head
                                                                                (Link
                                                                                text)
                                                                            </label>
                                                                            <input id="edit_head{{ $menu->id }}"
                                                                                type="text" class="form-control"
                                                                                name="head"
                                                                                value="{{ $menu->head }}">

                                                                            <label
                                                                                for="edit_right_link{{ $menu->id }}">Right
                                                                                Link</label>
                                                                            <input id="edit_right_link{{ $menu->id }}"
                                                                                type="text" class="form-control"
                                                                                placeholder="Optional" name="right_link"
                                                                                value="{{ $menu->right_link }}">

                                                                            <label
                                                                                for="edit_image{{ $menu->id }}">Image
                                                                                [Single]
                                                                                [416px by 236px]</label>
                                                                            <input id="edit_image{{ $menu->id }}"
                                                                                type="file" accept="image/*"
                                                                                value="{{ old('image') }}"
                                                                                class="form-control-file" name="image">
                                                                        </div>

                                                                        <button type="submit"
                                                                            class="btn btn-sm btn-primary float-right">Save</button>

                                                                        <script>
                                                                            // is_link checkbox
                                                                            if (document.querySelector('#edit_is_link{{ $menu->id }}').checked == true) {
                                                                                document.querySelector('#edit-link-group{{ $menu->id }}').style.display = "block"
                                                                            } else {
                                                                                document.querySelector('#edit-link-group{{ $menu->id }}').style.display = "none"
                                                                            }
                                                                            document.querySelector('#edit_is_link{{ $menu->id }}').addEventListener("change", function(e) {
                                                                                if (e.target.checked == true) {
                                                                                    document.querySelector('#edit-link-group{{ $menu->id }}').style.display = "block";
                                                                                } else {
                                                                                    document.querySelector('#edit-link-group{{ $menu->id }}').style.display = "none";
                                                                                }
                                                                            })
                                                                            // right elements
                                                                            document.querySelector('#edit_right{{ $menu->id }}').style.display = "none";

                                                                            if (document.querySelector('#edit_is_right{{ $menu->id }}').checked == true) {
                                                                                document.querySelector('#edit_right{{ $menu->id }}').style.display = "block"
                                                                            }
                                                                            document.querySelector('#edit_is_right{{ $menu->id }}').addEventListener("change", function(e) {
                                                                                if (e.target.checked == true) {
                                                                                    document.querySelector('#edit_right{{ $menu->id }}').style.display = "block";
                                                                                } else {
                                                                                    document.querySelector('#edit_right{{ $menu->id }}').style.display = "none";
                                                                                }
                                                                            })
                                                                        </script>

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
                                    {{ $menus->appends(request()->query())->links('pagination::bootstrap-5') }}
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
