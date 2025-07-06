<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Registration - Fresh Market</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        /* Body styling for centering */
        body {
            background: linear-gradient(to right, #e9f5ee, #ffffff);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
            margin: 0; /* Ensure no default margins */
        }

        /* Auth card styling */
        .auth-card {
            background: #fff;
            border-radius: 20px;
            padding: 50px; /* Increased padding for spacious feel */
            max-width: 600px; /* Increased card width */
            width: 100%;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
        }

        .auth-card h2 {
            font-weight: 600;
            color: #28a745;
            margin-bottom: 10px;
        }

        .auth-card p {
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

        /* Fade-in animation */
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

        /* Transition on focus for inputs */
        .form-control {
            transition: all 0.3s ease;
        }

        .text-success a {
            color: #28a745;
        }

        /* Ensure form elements are well-spaced */
        .form-label {
            font-weight: 500;
            color: #495057;
        }

        .form-control {
            border-radius: 8px;
        }

        /* Center the card container */
        .auth-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card" id="authCard">
            <div class="auth-section register-section">
                <div class="text-center mb-4">
                    <i class="bi bi-shop fs-1 text-success mb-3"></i>
                    <h2>Vendor Registration</h2>
                    <p class="text-muted">Join our marketplace</p>
                </div>

                <!-- Success Message -->
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('vendor.register') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="store_name" class="form-label">Store Name</label>
                            <input type="text" class="form-control" id="store_name" name="store_name" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">I agree to the <a href="#">Terms & Conditions</a></label>
                    </div>
                    <button type="submit" class="btn btn-success w-100 py-2">
                        <i class="bi bi-person-plus me-1"></i> Register
                    </button>
                </form>
                <div class="text-center">
                <small class="text-muted">You already have account?
                    <a href="{{ route('vendor.login') }}" class="text-success fw-semibold">Log in</a>
                </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (optional, for form interactions) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>