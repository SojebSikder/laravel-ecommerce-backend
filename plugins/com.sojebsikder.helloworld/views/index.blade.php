<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h1>Hello world</h1>
    {{-- simple calculator --}}
    <form action="{{ route('hello') }}" method="post">
        @csrf
        <input type="text" name="num1" placeholder="Enter first number">
        <input type="text" name="num2" placeholder="Enter second number">
        <button type="submit">Calculate</button>
    </form>

    {{-- show result --}}
    @if (Session::has('result'))
        <strong>{{ Session::get('result') }}</strong>.
    @endif

</body>

</html>
