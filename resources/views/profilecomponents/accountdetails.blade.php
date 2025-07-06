<!-- Mobile Profile Section -->
<div class="profile-card mobile-profile-section d-block d-md-none">
    <div class="profile-info-card">
        <div class="profile-header">
            <h4 class="profile-title">Account Details</h4>
            <button class="edit-btn" id="mobile-edit-btn">
                <i class="bi bi-pencil"></i> EDIT
            </button>
        </div>
        
        <div class="profile-image-container">
            <div class="profile-image" id="mobile-profile-image">
                @if(auth()->user()->profile && auth()->user()->profile->image)
                    <img src="{{ asset('storage/images/' . auth()->user()->profile->image) }}">
                @else
                    <div class="profile-image-placeholder">
                        <i class="bi bi-person"></i>
                    </div>
                @endif
            </div>
            <form action="{{ route('profile.upload.image') }}" method="POST" enctype="multipart/form-data" id="imageUploadFormMobile">
                @csrf
                <div class="mb-3">
                    <input type="file" name="file" id="imageInputMobile" accept="image/*" class="d-none">
                    <label for="imageInputMobile" class="custom-file-upload">
                        <i class="bi bi-camera"></i> ADD IMAGE
                    </label>
                </div>
            </form>
        </div>
        
        @if(session('user_id'))
        <div class="profile-field">
            <label>NAME</label>
            <div class="value" id="mobile-name">{{ Auth::user()->name }}</div>
        </div>
        <div class="profile-field">
            <label>EMAIL</label>
            <div class="value" id="mobile-email">{{ Auth::user()->email }}</div>
        </div>
        @endif
        
        <div class="profile-field">
            <label>PHONE</label>
            <div class="value" id="mobile-phone">{{ Auth::user()->phone ?? 'ADD NUMBER' }}</div>
        </div>
        
        <div class="profile-field">
            <label>GENDER</label>
            <div class="gender-options">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender-mobile" id="male-mobile" value="male"
                           {{ (Auth::user()->profile->gender ?? '') == 'male' ? 'checked' : '' }}>
                    <label class="form-check-label" for="male-mobile">Male</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender-mobile" id="female-mobile" value="female"
                           {{ (Auth::user()->profile->gender ?? '') == 'female' ? 'checked' : '' }}>
                    <label class="form-check-label" for="female-mobile">Female</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender-mobile" id="other-mobile" value="other"
                           {{ (Auth::user()->profile->gender ?? '') == 'other' ? 'checked' : '' }}>
                    <label class="form-check-label" for="other-mobile">Other</label>
                </div>
            </div>
        </div>
        
        <div class="profile-field">
            <label>DATE</label>
            <div class="value" id="mobile-date">{{ Auth::user()->profile->date_of_birth ?? 'ADD DATE THE FORMAT IS YEAR-MONTH-DAY' }}</div>
        </div>
        
        <button class="save-btn" id="mobile-save-btn" style="display:none;">SAVE</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile Edit Button
    document.getElementById('mobile-edit-btn').addEventListener('click', function() {
        // Toggle buttons
        this.style.display = 'none';
        document.getElementById('mobile-save-btn').style.display = 'block';
        
        // Convert fields to inputs while preserving values
        convertFieldToInput('mobile-name', 'text');
        convertFieldToInput('mobile-email', 'email');
        convertFieldToInput('mobile-phone', 'tel');
        convertFieldToInput('mobile-date', 'date');
    });
    
    // Mobile Save Button
    document.getElementById('mobile-save-btn').addEventListener('click', saveMobileProfile);
});

function convertFieldToInput(id, type) {
    const element = document.getElementById(id);
    let value = element.textContent;
    
    // Handle placeholders
    if ((id === 'mobile-phone' && value === 'ADD NUMBER') || 
        (id === 'mobile-date' && value === 'ADD DATE')) {
        value = '';
    }
    
    // Convert date format for input
    if (type === 'date' && value && value.includes('/')) {
        const [month, day, year] = value.split('/');
        value = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
    }
    
    element.innerHTML = `<input type="${type}" class="form-control" value="${value}" id="${id}-input">`;
}

function saveMobileProfile() {
    // Get gender value
    const gender = document.querySelector('input[name="gender-mobile"]:checked')?.value;
    
    // Prepare data
    const profileData = {
        name: document.getElementById('mobile-name-input').value,
        email: document.getElementById('mobile-email-input').value,
        phone: document.getElementById('mobile-phone-input').value || null,
        gender: gender,
        date_of_birth: document.getElementById('mobile-date-input').value || null,
        _token: document.querySelector('meta[name="csrf-token"]').content
    };
    
    // Send to server
    fetch("{{ route('profile.save') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': profileData._token
        },
        body: JSON.stringify(profileData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Convert back to display mode
            document.getElementById('mobile-name').textContent = profileData.name;
            document.getElementById('mobile-email').textContent = profileData.email;
            document.getElementById('mobile-phone').textContent = profileData.phone || 'ADD NUMBER';
            
            // Format date for display
            let dateDisplay = 'ADD DATE';
            if (profileData.date_of_birth) {
                const [year, month, day] = profileData.date_of_birth.split('-');
                dateDisplay = `${month}/${day}/${year}`;
            }
            document.getElementById('mobile-date').textContent = dateDisplay;
            
            // Toggle buttons
            document.getElementById('mobile-save-btn').style.display = 'none';
            document.getElementById('mobile-edit-btn').style.display = 'block';
            
            alert('Profile updated successfully!');
        }
    });
}
</script>