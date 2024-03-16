<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    {{-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login </title>
    <link rel="icon" type="image/x-icon" href="{{ Storage::url('setting/' . SettingHelper::get('logo')) }}" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{ asset('assets') }}/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <style>
        body {
            background-color: #f8f9fa;
            color: #25292e;
            font-family: Inter-Medium, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
            font-weight: 400;
            font-size: 0.9375rem;
            line-height: 1.6;
            letter-spacing: -0.009rem;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, .1), 0 3px 6px rgba(0, 0, 0, .05);
        }

        .header {
            font-size: 1.5625rem;
            line-height: 1.4;
            letter-spacing: -0.021rem;
            font-weight: 500;
            margin-bottom: 24px;
            margin-left: 12px;
        }

        input.form-control {
            height: 50px;
            border-radius: 8px;
        }

        .btn {
            width: 100%;
            border-radius: 8px;
        }

        .height{
            height: 100vh;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center align-items-center height">
            <div class="col col-lg-7">

                <div>
                    <div class="header">
                        Log In to {{ SettingHelper::getSiteName() }}
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <!-- Validation Errors -->
                        <x-auth-validation-errors class="mb-4" style="color: red" :errors="$errors" />

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input class="form-control" id="email" name="email" type="email"
                                    value="{{ old('email') }}" required>
                            </div>
                            <div class="form-group mb-2">
                                <label for="password">Password</label>
                                <input id="password" name="password" type="password" class="form-control" required
                                    autocomplete="off">
                            </div>
                            <div class="d-sm-flex justify-content-between">
                                <div class="form-group">
                                    <label class="d-inline-block" class="form-check-label" for="remember">Remember
                                        me:</label>
                                    <input type="checkbox" id="remember" name="remember">
                                </div>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary" value="">Log In</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>



    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('assets') }}/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
