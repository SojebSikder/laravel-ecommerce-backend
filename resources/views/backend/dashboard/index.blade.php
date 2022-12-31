@extends('backend.master')

@section('title')
    {{ auth()->user()->fname }}'s Dashboard
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
                    Dashboard
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="card text-bg-primary mb-3" style="max-width: 18rem;">
                        <div class="card-header">Primary Card</div>
                        <div class="card-body">
                            <h5 class="card-title">Primary card title</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk
                                of
                                the card's content.</p>
                        </div>
                        <div class="card-footer">
                            <div class="text-center">
                                <a href="#" class="btn btn-primary">
                                    More info
                                    <i class="bi bi-arrow-right-circle"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-bg-success mb-3" style="max-width: 18rem;">
                        <div class="card-header">Success Card</div>
                        <div class="card-body">
                            <h5 class="card-title">Success card title</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk
                                of
                                the card's content.</p>
                        </div>
                        <div class="card-footer">
                            <div class="text-center">
                                <a href="#" class="btn btn-success">
                                    More info
                                    <i class="bi bi-arrow-right-circle"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-bg-warning mb-3" style="max-width: 18rem;">
                        <div class="card-header">Warning Card</div>
                        <div class="card-body">
                            <h5 class="card-title">Warning card title</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk
                                of
                                the card's content.</p>
                        </div>
                        <div class="card-footer">
                            <div class="text-center">
                                <a href="#" class="btn btn-warning">
                                    More info
                                    <i class="bi bi-arrow-right-circle"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-bg-danger mb-3" style="max-width: 18rem;">
                        <div class="card-header">Danger Card</div>
                        <div class="card-body">
                            <h5 class="card-title">Danger card title</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk
                                of the card's content.</p>
                        </div>
                        <div class="card-footer">
                            <div class="text-center">
                                <a href="#" class="btn btn-danger">
                                    More info
                                    <i class="bi bi-arrow-right-circle"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            Charts
                        </div>
                        <div class="card-body">
                            <canvas class="chart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            Charts
                        </div>
                        <div class="card-body">
                            <canvas class="chart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <div class="card">
                        <div class="card-header">
                            Data tables
                        </div>
                        <div class="card-body">
                            <div>
                                <table class="table-striped table" style="width: 100%;">
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                    </tr>
                                    <tr>
                                        <td>Sojeb</td>
                                        <td>9999</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- End Main section -->
@endsection
