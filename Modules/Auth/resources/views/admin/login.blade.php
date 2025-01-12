
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite('Modules/Auth/resources/assets/sass/page/login.scss')
    {!! RecaptchaV3::initJs() !!}
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="form-group">
                <label for="name">Email Address</label>
                <input type="text" id="name" name="name" value="{{old('name', '')}}" class="form-control" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember Me</label>
            </div>
            {!! RecaptchaV3::field('login') !!}
            <div class="form-group">
                @if($errors->any())
                    @foreach($errors->all() as $error)
                        <div style="color:red">{{$error}}</div>
                    @endforeach
                @endif
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>
</body>
</html>
