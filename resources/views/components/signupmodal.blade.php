<!-- Sign Up Modal -->
<div class="modal fade" id="signupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> 
        <div class="modal-content">
            <div class="modal-body p-0 d-flex">
                
                <!-- Left Side: Logo Section -->
                <div class="left-side">
                    <img src="{{ asset('images/swift.png') }}" alt="Logo" class="logo">
                </div>

                <!-- Right Side: Form Section -->
                <div class="right-side">
                    <h5 class="text-center mb-3">Sign Up</h5>
                    <form id="registerForm">
                        @csrf
                        <div class="mb-3">
                            <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                            <div class="invalid-feedback" id="nameError"></div>
                        </div>
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                            <div class="invalid-feedback" id="emailError"></div>
                        </div>
                        <div class="mb-3">
                            <input type="tel" name="phone" class="form-control" placeholder="Phone" required>
                            <div class="invalid-feedback" id="phoneError"></div>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                            <div class="invalid-feedback" id="passwordError"></div>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                            <div class="invalid-feedback" id="passwordConfirmError"></div>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Sign Up</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Toast -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Registration successful! Please check your email for verification.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Error Toast -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="errorToastMessage">
                Registration failed. Please try again.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.getElementById('registerForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    let formData = new FormData(this);
    let errors = false;

    // Reset error states
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.innerText = "");

    // Frontend validation
    if (!formData.get('name').trim()) {
        document.getElementById('nameError').innerText = "Full name is required";
        document.querySelector('input[name="name"]').classList.add('is-invalid');
        errors = true;
    }

    if (!formData.get('email').trim()) {
        document.getElementById('emailError').innerText = "Email is required";
        document.querySelector('input[name="email"]').classList.add('is-invalid');
        errors = true;
    } else if (!validateEmail(formData.get('email').trim())) {
        document.getElementById('emailError').innerText = "Invalid email format";
        document.querySelector('input[name="email"]').classList.add('is-invalid');
        errors = true;
    }

    if (!formData.get('phone').trim()) {
        document.getElementById('phoneError').innerText = "Phone number is required";
        document.querySelector('input[name="phone"]').classList.add('is-invalid');
        errors = true;
    } else if (!validatePhone(formData.get('phone').trim())) {
        document.getElementById('phoneError').innerText = "Invalid phone number (10-15 digits)";
        document.querySelector('input[name="phone"]').classList.add('is-invalid');
        errors = true;
    }

    if (!formData.get('password').trim()) {
        document.getElementById('passwordError').innerText = "Password is required";
        document.querySelector('input[name="password"]').classList.add('is-invalid');
        errors = true;
    } else if (formData.get('password').trim().length < 6) {
        document.getElementById('passwordError').innerText = "Password must be at least 6 characters";
        document.querySelector('input[name="password"]').classList.add('is-invalid');
        errors = true;
    }

    if (formData.get('password') !== formData.get('password_confirmation')) {
        document.getElementById('passwordConfirmError').innerText = "Passwords don't match";
        document.querySelector('input[name="password_confirmation"]').classList.add('is-invalid');
        errors = true;
    }

    if (errors) return;

    // Send AJAX request
    fetch("{{ route('register') }}", {
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
            // Show success toast
            document.getElementById('successToast').querySelector('.toast-body').textContent = 
                data.message || 'Registration successful! Please check your email for verification.';
            
            const successToast = new bootstrap.Toast(document.getElementById('successToast'));
            successToast.show();
            
            // Reset form and close modal after delay
            setTimeout(() => {
                document.getElementById('registerForm').reset();
                bootstrap.Modal.getInstance(document.getElementById('signupModal')).hide();
            }, 3000);
        } else if (data.errors) {
            // Display validation errors
            for (const [field, messages] of Object.entries(data.errors)) {
                const input = document.querySelector(`[name="${field}"]`);
                const errorDiv = document.getElementById(`${field}Error`);
                if (input && errorDiv) {
                    input.classList.add('is-invalid');
                    errorDiv.textContent = messages[0];
                }
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('errorToastMessage').textContent = 
            error.message || 'Registration failed. Please try again.';
        
        const errorToast = new bootstrap.Toast(document.getElementById('errorToast'));
        errorToast.show();
    });
});

// Email validation
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
}

// Phone validation (10-15 digits)
function validatePhone(phone) {
    const re = /^[0-9]{10,15}$/;
    return re.test(phone);
}
</script>