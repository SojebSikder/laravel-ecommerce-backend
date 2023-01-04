@extends('backend.master')

@section('title')
    Coupons
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
                    Coupons
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">

                                <a href="{{ route('coupon.create') }}" class="btn btn-sm btn-primary float-end mt-3 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    Create coupon
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
                                                <th>Title</th>
                                                <th class="text-center">Status</th>
                                                <th>Method</th>
                                                <th>Type</th>
                                                <th>Used</th>
                                                <th>Created at</th>
                                                <th>Expiry at</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($coupons as $coupon)
                                                <tr>
                                                    <td>
                                                        <span style="font-weight: bold">
                                                            @if ($coupon->method == 'auto')
                                                                {{ $coupon->title }}
                                                            @else
                                                                {{ $coupon->code }}
                                                            @endif
                                                        </span>

                                                        <br>
                                                        {{ $coupon->amount }} @if ($coupon->amount_type == 'percentage')
                                                            %
                                                        @endif off entire order
                                                    </td>
                                                    @if ($coupon->status == '1')
                                                        <td class="text-center">
                                                            <a href="{{ route('coupon.status', $coupon->id) }}"
                                                                class="badge bg-primary text-decoration-none shadow-none">
                                                                Active
                                                            </a>
                                                        </td>
                                                    @else
                                                        <td class="text-center">
                                                            <a href="{{ route('coupon.status', $coupon->id) }}"
                                                                class="badge bg-warning text-decoration-none shadow-none">
                                                                Disabled
                                                            </a>
                                                        </td>
                                                    @endif

                                                    <td>{{ $coupon->method }}</td>
                                                    <td>{{ $coupon->coupon_type }}</td>
                                                    <td>{{ $coupon->uses }}</td>

                                                    <td>{{ date('d-M-Y', strtotime($coupon->created_at)) }}</td>
                                                    <td>{{ $coupon->expires_at }}</td>

                                                    <td class="text-center">
                                                        <ul class="table-controls">
                                                            <li>
                                                                <a class="btn btn-sm btn-primary"
                                                                    href="{{ route('coupon.edit', $coupon->id) }}"
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
                                                                    document.getElementById('coupon-delete-{{ $coupon->id }}').submit()
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
                                                                    action="{{ route('coupon.destroy', $coupon->id) }}"
                                                                    id="{{ 'coupon-delete-' . $coupon->id }}">
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
                                    {{ $coupons->appends(request()->query())->links('pagination::bootstrap-5') }}
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
