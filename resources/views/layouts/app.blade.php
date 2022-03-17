<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <script src="https://kit.fontawesome.com/dd47aab308.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic:wght@700;900&display=swap" rel="stylesheet">

    <title>Todo</title>
</head>
<body>
        <div class="header">
            <h1 class="font">Todo</h1>
            @auth
                <a class="font" href="{{ route('logout') }}">Logout</a>
            @endauth
        </div>
    <div class="main">
        @yield('content')
    </div>
</body>
</html>
