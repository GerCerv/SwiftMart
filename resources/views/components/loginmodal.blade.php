<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body p-0 d-flex">
                <!-- Logo Image -->
                <div class="login-image">
                    <img src="{{ asset('images/swift.png') }}" alt="Logo">
                </div>
                <!-- Login Form -->
                <div class="login-form">
                    <h5 class="text-center mb-3">Login</h5>
                    <form id="loginForm" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                            <div class="invalid-feedback" id="emailError"></div>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                            <div class="invalid-feedback" id="passwordError"></div>
                        </div>
                        <button type="submit" class="btn btn-success w-100 mt-3" id="loginButton">
                            <span id="loginButtonText" class="text-white fw-bold">Login</span>
                            <span id="loginButtonSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Toast -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="loginSuccessToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Login successful! Welcome, <span id="userName" class="name"></span>! Redirecting...
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Error Toast -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="loginErrorToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="errorToastMessage">
                Invalid credentials. Please try again.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Verification Toast -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="verificationToast" class="toast align-items-center text-white bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Please verify your email first. We've sent you a new verification link.
                <div class="mt-2">
                    <a href="#" id="resendVerificationLink" class="text-white">Resend verification email</a>
                </div>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    // Show loading state
    const loginButton = document.getElementById('loginButton');
    const loginButtonText = document.getElementById('loginButtonText');
    const loginButtonSpinner = document.getElementById('loginButtonSpinner');
    
    loginButton.disabled = true;
    loginButtonText.textContent = "Logging in...";
    loginButtonSpinner.classList.remove('d-none');
    
    let formData = new FormData(this);
    let errors = false;

    // Reset error states
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.innerText = "");

    // Frontend validation
    if (formData.get('email').trim() === '') {
        document.getElementById('emailError').innerText = "Email is required.";
        document.querySelector('input[name="email"]').classList.add('is-invalid');
        errors = true;
    } else if (!validateEmail(formData.get('email').trim())) {
        document.getElementById('emailError').innerText = "Invalid email format.";
        document.querySelector('input[name="email"]').classList.add('is-invalid');
        errors = true;
    }

    if (formData.get('password').trim() === '') {
        document.getElementById('passwordError').innerText = "Password is required.";
        document.querySelector('input[name="password"]').classList.add('is-invalid');
        errors = true;
    }

    if (errors) {
        resetLoginButton();
        // Add shake animation to invalid fields
        document.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.add('animate__animated', 'animate__shakeX');
            el.addEventListener('animationend', () => {
                el.classList.remove('animate__animated', 'animate__shakeX');
            }, {once: true});
        });
        return;
    }

    // Send AJAX request
    fetch("{{ route('login') }}", {
        method: "POST",
        body: formData,
        headers: {
            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
            "Accept": "application/json"
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success toast with user's name
            document.getElementById('userName').textContent = data.name;
            let toastEl = document.getElementById('loginSuccessToast');
            let toast = new bootstrap.Toast(toastEl);
            toast.show();

            // Redirect after delay
            setTimeout(() => {
                window.location.href = data.redirect || "{{ url('/home') }}";
            }, 1000);
        } else if (data.requires_verification) {
            // Show verification required toast
            document.getElementById('errorToastMessage').textContent = data.message;
            let toastEl = document.getElementById('verificationToast');
            let toast = new bootstrap.Toast(toastEl);
            toast.show();
            resetLoginButton();
        } else {
            // Show error toast with animation
            document.getElementById('errorToastMessage').textContent = data.message || 'Invalid credentials. Please try again.';
            let toastEl = document.getElementById('loginErrorToast');
            toastEl.classList.add('animate__animated', 'animate__headShake');
            let toast = new bootstrap.Toast(toastEl);
            toast.show();
            
            // Remove animation class after it plays
            toastEl.addEventListener('animationend', () => {
                toastEl.classList.remove('animate__animated', 'animate__headShake');
            }, {once: true});
            
            // Show field errors if any
            if (data.errors) {
                for (const [field, messages] of Object.entries(data.errors)) {
                    const input = document.querySelector(`[name="${field}"]`);
                    const errorDiv = document.getElementById(`${field}Error`);
                    if (input && errorDiv) {
                        input.classList.add('is-invalid', 'animate__animated', 'animate__shakeX');
                        errorDiv.textContent = messages[0];
                        
                        // Remove animation class after it plays
                        input.addEventListener('animationend', () => {
                            input.classList.remove('animate__animated', 'animate__shakeX');
                        }, {once: true});
                    }
                }
            }
            resetLoginButton();
        }
    })
    .catch(error => {
        resetLoginButton();
        console.error('Error:', error);
        
        // Show error toast with animation
        document.getElementById('errorToastMessage').textContent = error.message || 'An error occurred. Please try again.';
        let toastEl = document.getElementById('loginErrorToast');
        toastEl.classList.add('animate__animated', 'animate__headShake');
        let toast = new bootstrap.Toast(toastEl);
        toast.show();
        
        // Remove animation class after it plays
        toastEl.addEventListener('animationend', () => {
            toastEl.classList.remove('animate__animated', 'animate__headShake');
        }, {once: true});
    });
    
    function resetLoginButton() {
        loginButton.disabled = false;
        loginButtonText.textContent = "Login";
        loginButtonSpinner.classList.add('d-none');
    }
});

// Resend verification email handler
document.getElementById('resendVerificationLink').addEventListener('click', function(e) {
    e.preventDefault();
    const email = document.querySelector('input[name="email"]').value;
    
    fetch('/email/verification-notification', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'verification-link-sent') {
            document.getElementById('errorToastMessage').textContent = 'New verification link sent!';
            let toastEl = document.getElementById('loginErrorToast');
            let toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    })
    .catch(error => console.error('Error:', error));
});

// Function to validate email format
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
}
</script>
<style>
.name {
    color: white;
}
/* Custom animation for modal entrance */
        .modal-content {
            animation: uniqZoomInFromFront 0.5s ease-out forwards;
        }
        @keyframes uniqZoomInFromFront {
            from {
                transform: scale(0.8) translateZ(-100px);
                opacity: 0;
            }
            to {
                transform: scale(1) translateZ(0);
                opacity: 1;
            }
        }
        /* Ensure perspective for 3D effect */
        .modal-dialog {
            perspective: 1000px;
        }
        /* Custom animation for form inputs on focus */
        .form-control:focus {
            box-shadow: 0 0 15px rgba(0, 255, 128, 0.5);
            transform: scale(1.02);
            transition: all 0.3s ease;
        }
        /* Button hover animation */
        .btn-success {
            transition: all 0.3s ease;
        }
        .btn-success:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 255, 128, 0.4);
        }
        /* Toast entrance animation */
        .toast-entrance {
            animation: fadeInRight 0.5s ease-out forwards;
        }
        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        /* Image entrance animation */
        .login-image img {
            animation: bounceIn 0.8s ease-out forwards;
            
        }
        @keyframes bounceIn {
            0% {
                transform: scale(0.3);
                opacity: 0;
            }
            60% {
                transform: scale(1.1);
                opacity: 1;
            }
            100% {
                transform: scale(1);
            }
        }
</style>