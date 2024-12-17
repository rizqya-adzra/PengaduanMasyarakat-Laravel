<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            padding: 50px;
        }
        h1 {
            font-size: 100px;
            margin: 0;
            color: #ff6f61;
        }
        p {
            font-size: 20px;
            margin: 10px 0;
        }
        a {
            color: #007bff;
            text-decoration: none;
            font-size: 18px;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>403</h1>
    <p>Sorry, you don't have permission to access this page.</p>
    @if (Auth::user()->role == "GUEST")
    <a href="{{ route('guest.index') }}">Go Back Home</a>
    @elseif(Auth::user()->role == "STAFF")
    <a href="{{ route('staff.index') }}">Go Back Home</a>
    @elseif(Auth::user()->role == "HEAD_STAFF")
    <a href="{{ route('head_staff.index') }}">Go Back Home</a>
    @endif
</body>
</html>
