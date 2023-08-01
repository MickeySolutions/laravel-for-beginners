<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Home Page (Welcome from blade template)</h1>
    <h2>My name is, {{ $firstName }} {{ $lastName }}</h2>
    <h2>Today year is {{ date('Y') }}</h2>
    <h2>My pets names are:</h2>
    <ul>
        @foreach ( $petsNames as $name )
            <li>{{ $name }}</li>
        @endforeach
    </ul>
    <a href="/about">Go to About Page</a>
</body>
</html>
