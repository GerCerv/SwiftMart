<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Vendor Login - Fresh Market</title>

  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(to right, #e9f5ee, #ffffff);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Segoe UI', sans-serif;
    }

    .login-card {
      background: #fff;
      border-radius: 20px;
      padding: 40px;
      max-width: 450px;
      width: 100%;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      animation: fadeIn 1s ease-in-out;
    }

    .login-card h2 {
      font-weight: 600;
      color: #28a745;
      margin-bottom: 10px;
    }

    .login-card p {
      color: #6c757d;
    }

    .btn-success {
      background-color: #28a745;
      border: none;
    }

    .btn-success:hover {
      background-color: #218838;
    }

    .form-control:focus {
      border-color: #28a745;
      box-shadow: 0 0 0 0.25rem rgba(40,167,69,0.25);
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .text-success a {
      color: #28a745;
    }
  </style>
</head>
<body>

<div class="login-card">
  <div class="text-center mb-4">
    <i class="bi bi-shop fs-1 text-success"></i>
    <h2>Vendor Login</h2>
    <p class="text-muted">Access your vendor dashboard</p>
  </div>

  <!-- Display Error Messages -->
  @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
  @endif

  <!-- Display session errors -->
  @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
  @endif

  <form method="POST" action="{{ route('vendor.login') }}">
    @csrf

    <div class="mb-3">
      <label for="email" class="form-label">Email address</label>
      <input type="email" class="form-control" id="email" name="email" required autofocus>
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>

    <div class="mb-3 form-check">
      <input type="checkbox" class="form-check-input" id="remember" name="remember">
      <label class="form-check-label" for="remember">Remember me</label>
    </div>

    <div class="d-grid mb-3">
      <button type="submit" class="btn btn-success py-2">
        <i class="bi bi-box-arrow-in-right me-1"></i> Login
      </button>
    </div>

    <div class="text-center">
      <small class="text-muted">Don't have an account?
        <a href="{{ route('vendor.register') }}" class="text-success fw-semibold">Register</a>
      </small>
    </div>
  </form>
</div>

</body>
</html>
