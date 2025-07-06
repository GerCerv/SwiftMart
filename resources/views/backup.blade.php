<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Food Market</title>
    <style>
        
    </style>

    <link rel="stylesheet" href="{{ asset('css/style.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/modal.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/uppernav.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/middlenav.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/sidebar.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/popups.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/lowernav.css')}}" />



    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://kit.fontawesome.com/YOUR-FONTAWESOME-KIT.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
</head>
<body>

<!-- Upper navigation -->
<x-uppernav/>

<!-- Login Modal -->
<x-loginmodal/>

<!-- Sign Up Modal -->
<x-signupmodal/>

<!-- Middle Navigation (Sticky) -->
<x-middlenav/>

<!-- Lower Navigation -->
<x-lowernav/>

<!-- Side Navigation Bar -->
<x-sidebar/>




<!-- Content Layout: Categories (Left) & Carousel (Right) -->
<div class="container my-4" >
    <div class="row" >
        <!-- Image Slider (Left Side) -->
        <div class="col-lg-6" style="background-color:green;">
            <h2 class="text-center mb-4">Featured Products</h2>
            <div id="carouselExample" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2000">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('images/chono.jpg') }}" class="d-block w-100" alt="Vegetables">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/hina.jpg') }}" class="d-block w-100" alt="Fruits">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/anime.jpg') }}" class="d-block w-100" alt="Meat">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>

            <!-- Two Smaller Featured Products Below -->
            <div class="row mt-3">
                <div class="col-6">
                    <div class="card">
                        <img src="{{ asset('images/chono.jpg') }}" style="height:200px;object-fit: cover;" class="card-img-top" alt="Small Product 1">
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <img src="{{ asset('images/hina.jpg') }}" style="height:200px;object-fit: cover;" class="card-img-top" alt="Small Product 2">
                    </div>
                </div>
            </div>
        </div>
       

        <!-- Categories (Right Side) -->
        <div class="col-lg-6">
            <h2 class="text-center mb-4">HOT DEALS</h2>

            <!-- Desktop View (3 per row) -->
            <div class="row g-4 d-none d-md-flex">
                <div class="col-4 col-md-4">
                    <div class="category-card">
                        <img src="{{ asset('images/emp.jpg') }}" class="img-fluid category-img" alt="Vegetables">
                        <div class="p-3 text-center"><h5>Vegetables</h5></div>
                    </div>
                </div>
                <div class="col-4 col-md-4">
                    <div class="category-card">
                        <img src="{{ asset('images/emp.jpg') }}" class="img-fluid category-img" alt="Fruits">
                        <div class="p-3 text-center"><h5>Fruits</h5></div>
                    </div>
                </div>
                <div class="col-4 col-md-4">
                    <div class="category-card">
                        <img src="{{ asset('images/emp.jpg') }}" class="img-fluid category-img" alt="Meat">
                        <div class="p-3 text-center"><h5>Meat</h5></div>
                    </div>
                </div>
                <div class="col-4 col-md-4">
                    <div class="category-card">
                        <img src="{{ asset('images/emp.jpg') }}" class="img-fluid category-img" alt="Fish">
                        <div class="p-3 text-center"><h5>Fish</h5></div>
                    </div>
                </div>
                <div class="col-4 col-md-4">
                    <div class="category-card">
                        <img src="{{ asset('images/emp.jpg') }}" class="img-fluid category-img" alt="Fish">
                        <div class="p-3 text-center"><h5>Fish</h5></div>
                    </div>
                </div>
                <div class="col-4 col-md-4">
                    <div class="category-card">
                        <img src="{{ asset('images/emp.jpg') }}" class="img-fluid category-img" alt="Fish">
                        <div class="p-3 text-center"><h5>Fish</h5></div>
                    </div>
                </div>
            </div>

            <!-- Mobile View (Slider with 2 items per row) -->
            <div class="d-md-none">
                <div class="slider-container">
                    <div class="slider">
                        <div class="slider-item">
                            <img src="{{ asset('images/emp.jpg') }}" class="img-fluid category-img" alt="Vegetables">
                            <div class="p-3 text-center"><h5>Vegetables</h5></div>
                        </div>
                        <div class="slider-item">
                            <img src="{{ asset('images/emp.jpg') }}" class="img-fluid category-img" alt="Fruits">
                            <div class="p-3 text-center"><h5>Fruits</h5></div>
                        </div>
                        <div class="slider-item">
                            <img src="{{ asset('images/emp.jpg') }}" class="img-fluid category-img" alt="Meat">
                            <div class="p-3 text-center"><h5>Meat</h5></div>
                        </div>
                        <div class="slider-item">
                            <img src="{{ asset('images/emp.jpg') }}" class="img-fluid category-img" alt="Fish">
                            <div class="p-3 text-center"><h5>Fish</h5></div>
                        </div>
                        <div class="slider-item">
                            <img src="{{ asset('images/emp.jpg') }}" class="img-fluid category-img" alt="Fish">
                            <div class="p-3 text-center"><h5>Fish</h5></div>
                        </div>
                        <div class="slider-item">
                            <img src="{{ asset('images/emp.jpg') }}" class="img-fluid category-img" alt="Fish">
                            <div class="p-3 text-center"><h5>Fish</h5></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div>
    <h1>HELLO WORLD</h1>
</div>


<x-footernav/>



<!-- Dark Mode Toggle Script -->
<script>
    const darkModeSwitch = document.getElementById('darkModeSwitch');
    const darkModeSwitchMobile = document.getElementById('darkModeSwitchMobile');
    const body = document.body;

    function setDarkMode(enabled) {
        if (enabled) {
            body.classList.add('dark-mode');
            localStorage.setItem('darkMode', 'enabled');
            darkModeSwitch.checked = true;
            darkModeSwitchMobile.checked = true;
        } else {
            body.classList.remove('dark-mode');
            localStorage.setItem('darkMode', 'disabled');
            darkModeSwitch.checked = false;
            darkModeSwitchMobile.checked = false;
        }
    }

    // Check Local Storage
    if (localStorage.getItem('darkMode') === 'enabled') {
        setDarkMode(true);
    }

    // Sync Switches
    darkModeSwitch.addEventListener('change', () => setDarkMode(darkModeSwitch.checked));
    darkModeSwitchMobile.addEventListener('change', () => setDarkMode(darkModeSwitchMobile.checked));





    
</script>
<!-- LOGIN
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    let formData = new FormData(this);

    fetch("{{ route('login') }}", {
        method: "POST",
        body: formData,
        headers: {
            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let toastEl = document.getElementById('loginSuccessToast');
            let toast = new bootstrap.Toast(toastEl);
            toast.show();

            setTimeout(() => {
                window.location.href = "{{ url('/home') }}";
            }, 3000);
        } else {
            let toastEl = document.getElementById('loginErrorToast');
            let toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    })
    .catch(error => console.log(error));
});
</script> -->




<!-- REGISTER -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- REGISTER VALIDATIONS -->
<script>
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
</script>
<!-- LOGIN VALIDATION
<script>
document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    let formData = new FormData(this);
    let errors = false;

    // Reset error messages
    document.querySelectorAll('.text-danger').forEach(el => el.innerText = "");

    // Frontend validation
    if (formData.get('email').trim() === '') {
        document.getElementById('emailError').innerText = "Email is required.";
        errors = true;
    }
    if (formData.get('password').trim() === '') {
        document.getElementById('passwordError').innerText = "Password is required.";
        errors = true;
    }

    if (errors) return;

    // Send AJAX request
    fetch("{{ route('login') }}", {
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
            if (data.errors.email) document.getElementById('emailError').innerText = data.errors.email[0];
            if (data.errors.password) document.getElementById('passwordError').innerText = data.errors.password[0];
        } else if (data.success) {
            // Show success toast
            let toastEl = document.getElementById('loginSuccessToast');
            let toast = new bootstrap.Toast(toastEl);
            toast.show();

            // Redirect after 2 seconds
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 2000);
        } else {
            // Show error toast for invalid credentials
            let errorToastEl = document.getElementById('loginErrorToast');
            let errorToast = new bootstrap.Toast(errorToastEl);
            errorToast.show();
        }
    })
    .catch(error => console.log(error));
});
</script> -->


</body>
</html>
