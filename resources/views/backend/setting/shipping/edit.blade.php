@extends('backend.master')

@section('title')
    Edit shipping
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
                    Shippings
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="float-start">Edit shipping</h5>
                                <a href="{{ route('shipping.index') }}" class="btn btn-sm btn-primary float-end mt-3 mr-4">
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
                                    Shipping list
                                </a>

                            </div>
                            <div class="card-body">
                                <form action="{{ route('shipping.update', $shipping->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col">

                                            <div class="d-flex form-group justify-content-end mb-3">
                                                <button id="submit" type="submit"
                                                    class="btn btn-primary me-1 mt-3">Save</button>
                                            </div>

                                            <div class="col">
                                                <div class="form-check form-switch mb-3 mt-5">
                                                    <input class="form-check-input"
                                                        @if ($shipping->status == 1) checked @endif value="1"
                                                        name="status" type="checkbox" role="switch" id="status">
                                                    <label class="form-check-label" for="status">Active</label>
                                                </div>
                                            </div>

                                            <div class="col">
                                                <div class="form-group mb-3">
                                                    <label for="name">Name</label>
                                                    <input type="text" id="name"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        value="{{ $shipping->name }}" name="name">
                                                </div>
                                                @error('name')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- <div class="row">
                                                <div class="col">
                                                    <div class="form-group mb-3">
                                                        <button id="submit" type="submit"
                                                            class="btn btn-primary mt-3">Save</button>
                                                    </div>
                                                </div>
                                            </div> --}}
                                        </div>

                                    </div>
                                </form>
                                {{-- info --}}
                                <div class="row">
                                    <div class="col">
                                        <div id="panelContainer" class="col">
                                            <label>Shipping zones:</label>
                                            <?php $shippingList_id = 0; ?>
                                            @if ($shippingZone)
                                                @foreach ($shippingZone as $shippingList)
                                                    <div class="bottom-value row" data-id="{{ $shippingList_id }}">
                                                        <input type="hidden" id="bottom_id"
                                                            value="{{ $shippingList->id }}" name="bottom_id">

                                                        <div class="col">
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <label for="">Name</label>
                                                                    <input type="text" name="name"
                                                                        value="{{ $shippingList->name }}" id="name"
                                                                        class="form-control" placeholder="Enter name">
                                                                </div>
                                                            </div>
                                                            {{-- <div class="col">
                                                                <div>
                                                                    @foreach ($shippingList->tags as $tag)
                                                                        <span class="badge bg-secondary">{{ $tag->name }}</span>
                                                                    @endforeach
                                                                </div>
                                                            </div> --}}
                                                            <div class="col mt-2">
                                                                <a href="/setting/shipping-zone/{{ $shipping->id }}/address/{{ $shippingList->id }}/edit"
                                                                    class="btn btn-outline-info">Add countries</a>
                                                            </div>
                                                            <div class="col mt-2">
                                                                <a href="/setting/shipping-zone/{{ $shipping->id }}/payment-provider/{{ $shippingList->id }}/edit"
                                                                    class="btn btn-outline-info">Add payment provider</a>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <label for="">Price</label>
                                                                    <input type="number" name="price"
                                                                        value="{{ $shippingList->price }}" id="price"
                                                                        class="form-control" placeholder="Enter price">
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <div class="form-group">
                                                                            <label for="">Minimum time
                                                                                (days)</label>
                                                                            <input type="number"
                                                                                name="shipping_time_start"
                                                                                value="{{ $shippingList->shipping_time_start }}"
                                                                                id="shipping_time_start"
                                                                                class="form-control" placeholder="e.x. 3">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col">
                                                                        <div class="form-group">
                                                                            <label for="">Maximum time
                                                                                (days)</label>
                                                                            <input type="number" name="shipping_time_end"
                                                                                value="{{ $shippingList->shipping_time_end }}"
                                                                                id="shipping_time_end"
                                                                                class="form-control" placeholder="e.g. 5">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label for="">Sort order</label>
                                                                <input type="number"
                                                                    value="{{ $shippingList->sort_order }}"
                                                                    class="form-control" id="list_sort_order"
                                                                    name="list_sort_order">
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <button type="button"
                                                                    onclick="event.preventDefault();
                                                                        if(confirm('Are you really want to delete?')){
                                                                        document.getElementById('option-set-delete-{{ $shippingList->id }}').submit()
                                                                        }"
                                                                    class="btn btn-sm btn-danger">X</button>
                                                            </div>
                                                        </div>

                                                        <!-- Delete -->
                                                        <form method="post"
                                                            action="{{ route('shipping-zone.destroy', $shippingList->id) }}"
                                                            id="{{ 'option-set-delete-' . $shippingList->id }}">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                        <!-- end Delete -->
                                                    </div>

                                                    <?php $shippingList_id++; ?>
                                                @endforeach
                                            @endif
                                        </div>

                                        <div class="col mt-2">
                                            <button id="addBottomBtn" type="button" class="btn btn-sm btn-primary mb-3">
                                                Add another
                                            </button>
                                        </div>

                                        {{-- bottom content section --}}
                                    </div>
                                </div>
                                {{-- end info --}}
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
        // Return an array of the selected opion values
        // select is an HTML select element
        function getSelectValues(select) {
            var result = [];
            var options = select && select.options;
            var opt;

            for (var i = 0, iLen = options.length; i < iLen; i++) {
                opt = options[i];

                if (opt.selected) {
                    result.push(opt.value || opt.text);
                }
            }
            return result;
        }

        function save(e) {
            let data = []
            const divs = document.querySelectorAll(".bottom-value")
            for (const el of divs) {
                let bottomEl = el.querySelector("#bottom_id");
                let bottom_id = null;
                if (bottomEl) {
                    bottom_id = bottomEl.value;
                }
                let name = el.querySelector("#name").value;
                let price = el.querySelector("#price").value;
                let shipping_time_start = el.querySelector("#shipping_time_start").value;
                let shipping_time_end = el.querySelector("#shipping_time_end").value;
                let list_sort_order = el.querySelector("#list_sort_order").value;
                data.push({
                    bottom_id: bottom_id,
                    footer_id: '{{ $shipping->id }}',
                    name: name,
                    price: price,
                    shipping_time_start: shipping_time_start,
                    shipping_time_end: shipping_time_end,
                    sort_order: list_sort_order,
                });
            }

            data.map((dt) => {
                const formData = new FormData()
                formData.append('shipping_list_id', dt.bottom_id)
                formData.append('shipping_id', dt.footer_id)
                formData.append('name', dt.name)
                formData.append('price', dt.price)
                formData.append('shipping_time_start', dt.shipping_time_start)
                formData.append('shipping_time_end', dt.shipping_time_end)
                formData.append('sort_order', dt.sort_order)

                // insert new
                $.ajax({
                    url: "{{ route('shipping-zone.store') }}",
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
            let id = Number('{{ $shippingList_id }}');
            const panelContainer = document.querySelector("#panelContainer")
            const addBottomBtn = document.querySelector("#addBottomBtn")

            function handleClick(e) {
                const html = `
        <div class="col">
            <div class="form-group">
                <label for="">Name</label>
                <input type="text" name="name" id="name"
                    class="form-control" placeholder="Enter name">
            </div>
        </div>
        <div>
            <div class="col">
                <div class="form-group">
                    <label for="">Price</label>
                    <input type="number" name="price" id="price" value="0" class="form-control"
                        placeholder="Enter price">
                </div>
            </div>
            <div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="">Minimum time</label>
                            <input type="number" name="shipping_time_start"
                                value="2"
                                id="shipping_time_start" class="form-control"
                                placeholder="Enter minimum time">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="">Maximum time</label>
                            <input type="number" name="shipping_time_end"
                                value="4"
                                id="shipping_time_end" class="form-control"
                                placeholder="Enter maximum time">
                        </div>
                    </div>
                </div>
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
