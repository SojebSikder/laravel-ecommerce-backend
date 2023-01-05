@extends('backend.master')

@section('title')
    Edit footer
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
                    Footer
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="float-start">Edit footer</h5>
                                <a href="{{ route('footer.index') }}" class="btn btn-sm btn-primary float-end mt-3 mr-4">
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
                                    Footer list
                                </a>

                            </div>
                            <div class="card-body">
                                <form action="{{ route('footer.update', $footer->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col">

                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="sort_order">Sort order</label>
                                                    <input type="number" value="{{ $footer->sort_order }}"
                                                        class="form-control" id="sort_order" name="sort_order">
                                                </div>
                                            </div>

                                            <div class="col">
                                                <div class="form-group mb-3">
                                                    <label>Name<sup style="color: red">(*) </sup></label>
                                                    <input id="name" type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        name="name" placeholder="Name (caption)"
                                                        value="{{ $footer->name }}" />
                                                </div>
                                            </div>

                                            <div class="col">
                                                <div class="form-check form-switch mb-3 mt-5">
                                                    <input class="form-check-input" value="1" name="status"
                                                        type="checkbox" role="switch" id="status"
                                                        @if ($footer->status == '1') checked @endif>
                                                    <label class="form-check-label" for="status">Status</label>
                                                </div>
                                            </div>

                                            <div class="col">
                                                <div class="form-group mb-3">
                                                    <button id="submit" type="submit"
                                                        class="btn btn-primary mt-3">Submit</button>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </form>


                                <div class="col">
                                    <div id="panelContainer" class="col">
                                        <label>Item list</label>
                                        <?php $footerItem_id = 0; ?>
                                        @if ($footerItems)
                                            @foreach ($footerItems as $footerItem)
                                                <div class="bottom-value row" data-id="{{ $footerItem_id }}">
                                                    <input type="hidden" id="bottom_id" value="{{ $footerItem->id }}"
                                                        name="bottom_id">

                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="">Link Text</label>
                                                            <input type="text" name="name"
                                                                value="{{ $footerItem->name }}" id="name"
                                                                class="form-control" placeholder="Enter link text">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="">Link</label>
                                                            <input type="text" name="link"
                                                                value="{{ $footerItem->link }}" id="link"
                                                                class="form-control" placeholder="Enter link">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="">Sort order</label>
                                                            <input type="number" value="{{ $footerItem->sort_order }}"
                                                                class="form-control" id="list_sort_order"
                                                                name="list_sort_order">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <button type="button"
                                                                onclick="event.preventDefault();
                                                            if(confirm('Are you really want to delete?')){
                                                            document.getElementById('option-set-delete-{{ $footerItem->id }}').submit()
                                                            }"
                                                                class="btn btn-sm btn-danger">X</button>
                                                        </div>
                                                        <div>
                                                            @if ($footerItem->status == '1')
                                                                <td class="text-center">
                                                                    <a href="{{ route('footer-item.status', $footerItem->id) }}"
                                                                        class="badge badge-primary shadow-none">
                                                                        Active
                                                                    </a>
                                                                </td>
                                                            @else
                                                                <td class="text-center">
                                                                    <a href="{{ route('footer-item.status', $footerItem->id) }}"
                                                                        class="badge badge-warning shadow-none">
                                                                        Disabled
                                                                    </a>
                                                                </td>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Delete -->
                                                    <form method="post"
                                                        action="{{ route('footer-item.destroy', $footerItem->id) }}"
                                                        id="{{ 'option-set-delete-' . $footerItem->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    <!-- end Delete -->
                                                </div>

                                                <?php $footerItem_id++; ?>
                                            @endforeach
                                        @endif
                                    </div>

                                    <button id="addBottomBtn" type="button" class="btn btn-sm btn-primary mb-3">
                                        Add another
                                    </button>
                                    {{-- bottom content section --}}
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
    <script>
        const bottomResponse = document.querySelector('#bottomResponse');
        init();

        // remove option
        function remove(e) {
            const divs = document.querySelectorAll(".bottom-value")
            for (const el of divs) {
                if (el.dataset.id == e) {
                    el.remove()
                    break;
                }
            }
        }

        document.querySelector("#submit").addEventListener("click", save);

        function save(e) {
            let data = []
            const divs = document.querySelectorAll(".bottom-value")
            for (const el of divs) {
                let bottomEl = el.querySelector("#bottom_id");
                let bottom_id = null;
                if (bottomEl) {
                    bottom_id = bottomEl.value;
                }
                let link_text = el.querySelector("#name").value;
                let link = el.querySelector("#link").value;
                let list_sort_order = el.querySelector("#list_sort_order").value;
                data.push({
                    bottom_id: bottom_id,
                    footer_id: '{{ $footer->id }}',
                    link_text: link_text,
                    link: link,
                    sort_order: list_sort_order,
                });
            }

            console.log(data);

            data.map((dt) => {

                const formData = new FormData()
                formData.append('footer_item_id', dt.bottom_id)
                formData.append('footer_id', dt.footer_id)
                formData.append('name', dt.link_text)
                formData.append('link', dt.link)
                formData.append('sort_order', dt.sort_order)

                // insert new
                $.ajax({
                    url: "{{ route('footer-item.store') }}",
                    method: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: (res) => {
                        // bottomResponseGroup.classList.add("show")
                        // bottomResponse.innerHTML = res.message
                    }
                })

            })
        }

        function init() {
            // let id = 1;
            let id = Number('{{ $footerItem_id }}');
            const panelContainer = document.querySelector("#panelContainer")
            const addBottomBtn = document.querySelector("#addBottomBtn")

            function handleClick(e) {
                const html = `
        <div class="col">
            <div class="form-group">
                <label for="">Link Text</label>
                <input type="text" name="name" id="name"
                    class="form-control" placeholder="Enter link text">
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="">Link</label>
                <input type="text" name="link" id="link" class="form-control"
                    placeholder="Enter link">
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="">Sort order</label>
                <input type="number" value="0" class="form-control"
                    id="list_sort_order" name="list_sort_order">
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <button onclick="remove(${id})" type="button" class="btn btn-sm btn-danger">X</button>
            </div>
        </div>
        `
                const div = document.createElement("div");
                div.className = "bottom-value row";
                div.setAttribute("data-id", id);
                div.innerHTML = html;

                panelContainer.appendChild(div);
                id++;
            }
            addBottomBtn.addEventListener("click", handleClick)
        }
    </script>
@endsection
