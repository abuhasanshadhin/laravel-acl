<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SB Admin - Login</title>

  <!-- Custom styles for this template-->
  <link href="{{ asset('public/assets/admin') }}/css/sb-admin.css" rel="stylesheet">

</head>

<body class="bg-dark">

  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Login</div>
      <div class="card-body">

        @if (session('error'))
            <div class="alert alert-danger text-center">{!! session('error') !!}</div>
        @endif

        <form action="{{ route('admin.loginProcess') }}" method="POST">
            @csrf

          <div class="form-group">
            <div class="form-label-group">
              <input type="text" name="username" id="username" value="{{ old('username') }}" class="form-control" placeholder="Username" required>
              <label for="username">Username</label>
            </div>
            @if ($errors->has('username'))
                <span class="text-danger">{{ $errors->first('username') }}</span>
            @endif
          </div>
          <div class="form-group">
            <div class="form-label-group">
              <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
              <label for="password">Password</label>
            </div>
            @if ($errors->has('password'))
                <span class="text-danger">{{ $errors->first('password') }}</span>
            @endif
          </div>
          <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="remember" id="remember-me" class="batch custom-control-input">
                <label class="custom-control-label" for="remember-me">Remember me</label>
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
      </div>
    </div>
  </div>

</body>

</html>
