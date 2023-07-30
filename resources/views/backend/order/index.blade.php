@extends('backend.master')

@section('title')
    Orders
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
                    Orders
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">

                                <form method="get">
                                    <input class="form-control" value="{{ request('date') }}" type="date" name="date">
                                    <button type="submit" class="btn btn-sm btn-primary mt-3">
                                        Search
                                    </button>
                                </form>

                                {{-- <a href="{{ route('order.create') }}" class="btn btn-sm btn-primary float-end mt-3 mr-4">
                                    Create order
                                </a> --}}

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
                                                <th>Order #</th>
                                                <th>Date</th>
                                                <th>Order status</th>
                                                <th>Payment status</th>
                                                <th>Fulfillment status</th>
                                                <th>Customer</th>
                                                <th>Items</th>
                                                <th>Order total</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td>#{{ $order->invoice_number }}</td>
                                                    <td>
                                                        {{ date('d M Y', strtotime($order->created_at)) }} at
                                                        {{ date('h:i a', strtotime($order->created_at)) }}
                                                        ({{ $order->created_at->diffForHumans() }})
                                                    </td>

                                                    <td class="text-center">
                                                        <div class="badge bg-primary text-decoration-none shadow-none">
                                                            {{ $order->status }}
                                                        </div>
                                                    </td>

                                                    <td>{{ $order->payment_status }}</td>
                                                    <td>{{ $order->fulfillment_status }}</td>
                                                    <td>{{ $order->fname }} {{ $order->lname }}</td>
                                                    <td>
                                                        {{ count($order->order_items) }} items

                                                    </td>
                                                    <td>{{ $order->order_total }}</td>

                                                    <td class="text-center">
                                                        <ul class="table-controls">
                                                            <li>
                                                                <a class="btn btn-sm btn-primary"
                                                                    href="{{ route('order.show', $order->id) }}"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="" data-bs-title="View">
                                                                    <i class="bi bi-eye"></i>
                                                                    View
                                                                </a>
                                                            </li>

                                                        </ul>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>


                                    </table>
                                    {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
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
