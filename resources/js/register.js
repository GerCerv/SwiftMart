// <!-- REGISTER VALIDATIONS -->

document.getElementById('registerForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    let formData = new FormData(this);
    let errors = false;

    // Reset error messages
    document.querySelectorAll('.text-danger').forEach(el => el.innerText = "");

    // Frontend validation
    if (formData.get('name').trim() === '') {
        document.getElementById('nameError').innerText = "Full Name is required.";
        errors = true;
    }
    if (formData.get('email').trim() === '') {
        document.getElementById('emailError').innerText = "Email is required.";
        errors = true;
    }
    if (formData.get('password').length < 6) {
        document.getElementById('passwordError').innerText = "Password must be at least 6 characters.";
        errors = true;
    }
    if (formData.get('password') !== formData.get('password_confirmation')) {
        document.getElementById('passwordConfirmError').innerText = "Passwords do not match.";
        errors = true;
    }

    if (errors) return;

    // Send AJAX request
    fetch("{{ route('register') }}", {
        method: "POST",
        body: formData,
        headers: {
            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.errors) {
            // Display validation errors
            if (data.errors.name) document.getElementById('nameError').innerText = data.errors.name[0];
            if (data.errors.email) document.getElementById('emailError').innerText = data.errors.email[0];
            if (data.errors.password) document.getElementById('passwordError').innerText = data.errors.password[0];
        } else if (data.success) {
            // Show success toast
            let toastEl = document.getElementById('successToast');
            let toast = new bootstrap.Toast(toastEl);
            toast.show();

            // Hide toast after 3 seconds
            setTimeout(() => {
                toast.hide();
            }, 3000);

            // Reset the form
            document.getElementById('registerForm').reset();
        }
    })
    .catch(error => console.log(error));
});
