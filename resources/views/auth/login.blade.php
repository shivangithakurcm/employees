<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #1a1a1a;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-box {
            background: #222;
            border: 1px solid #444;
            border-radius: 12px;
            padding: 40px;
            width: 380px;
        }
        .login-box h2 {
            color: #f0c040;
            text-align: center;
            margin-bottom: 25px;
            font-size: 1.8rem;
        }
        .form-control {
            background: #333;
            border: 1px solid #555;
            color: #fff;
        }
        .form-control:focus {
            background: #3a3a3a;
            color: #fff;
            border-color: #f0c040;
            box-shadow: none;
        }
        .form-label { color: #ccc; }
        .btn-login {
            background: #f0c040;
            color: #000;
            font-weight: bold;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .btn-login:hover { background: #d4a800; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login</h2>

        @if($errors->any())
            <div class="alert alert-danger py-2">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">User ID (Email)</label>
                <input type="email"
                       name="email"
                       class="form-control"
                       placeholder="admin@gmail.com"
                       value="{{ old('email') }}"
                       required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password"
                       name="password"
                       class="form-control"
                       placeholder="Password"
                       required>
            </div>
            <button type="submit" class="btn-login mt-2">Login</button>
        </form>
    </div>
</body>
</html>