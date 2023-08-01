@extends('backend.master')

@section('title')
    Todo
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
                    Todo
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header"></div>
                            <div class="card-body">
                                <div>
                                    {{-- simple calculator --}}
                                    <form action="{{ route('todo.store') }}" method="post">
                                        @csrf

                                        <div class="col">
                                            <div class="form-group mb-3">
                                                <label for="title">Title</label>
                                                <input type="text" id="title"
                                                    class="form-control @error('title') is-invalid @enderror"
                                                    value="{{ old('title') }}" name="title">
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="form-group mb-3">
                                                <label for="content">Content</label>
                                                <textarea name="content" id="" class="form-control @error('num2') is-invalid @enderror">{{ old('num2') }}</textarea>

                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group mb-3">
                                                    <button id="submit" type="submit" class="btn btn-primary mt-3">Add
                                                        new</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    {{-- show result --}}
                                    @if (Session::has('result'))
                                        <strong>{{ Session::get('result') }}</strong>.
                                    @endif
                                </div>

                                <div class="mt-5">
                                    <table class="table-bordered table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Title</th>
                                                <th scope="col">Content</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($todos) > 0)
                                                @foreach ($todos as $todo)
                                                    <tr>
                                                        <td>{{ $todo['title'] }}</td>
                                                        <td>{{ $todo['content'] }}</td>
                                                        <td>
                                                            <a href="{{ route('todo.destroy', $todo['id']) }}"
                                                                class="btn btn-danger">Delete</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
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
